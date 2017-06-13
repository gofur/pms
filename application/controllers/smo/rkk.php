<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rkk extends Controller{

  public function __construct()
  {
    parent::__construct();
    if(!$this->session->userdata('loginFlag')){
			redirect('account/login');
		}
		//check validasi akses
		$url_value = $this->uri->segment(1, 0);
		if($this->uri->segment(2, 0)!=''){
			$url_value .='/'.$this->uri->segment(2, 0);
		}
		if($this->system_model->check_roleAccess($this->session->userdata('roleID'),$url_value)==0){
			redirect('home');
		}
    $this->load->model('general_model');
    $this->load->model('om_model');
    $this->load->model('org_model');
    $this->load->model('rkk_model3');
    $this->load->library('parser');
  }

  function index()
  {
    $period = $this->general_model->get_Period_row($this->session->userdata('active_period'));
    $data['period']    = $period->Tahun;
    $data['begin']     = date('Y/m/d',strtotime($period->BeginDate));
    $data['end']       = date('Y/m/d',strtotime($period->EndDate));
    $data['persAdmin'] = $this->session->userdata('PersAdmin');
    // $this->load->view('smo/rkk/main_view', $data);
    $this->parser->parse('smo/rkk/main_view', $data);

  }


  public function show_person()
  {
    $org_id = $this->input->post('org_id');
    $period = $this->general_model->get_Period_row($this->session->userdata('active_period'));
    $begin  = $period->BeginDate;
    $end    = $period->EndDate;

    $list = $this->om_model->get_hold_byOrg_list(1,$org_id,$begin,$end);
    foreach ($list as $row) {
      $rkkPers_count = $this->rkk_model3->count_rkk_holder($row->NIK,$row->PositionID,1,$begin,$end);
      $data['nik'] = $row->NIK;
      $data['fullname'] = $row->Fullname;
      $data['postName'] = $row->post_name;

      if ($rkkPers_count) {
        // RKK sudah dibuat
        $rkkPers = $this->rkk_model3->get_rkk_byNIKPosition($row->NIK,$row->PositionID,$begin,$end);
        switch ($rkkPers->statusFlag) {
          case 0: // Draft
            $weight = $this->rkk_model3->count_weight_rkk($rkkPers->RKKID,$begin,$end);
            if (is_null($weight)) {
              $weight = 0;
            }

            $view = 'smo/rkk/row_open';
            $data['status'] = '<span class="label label-default">Draft '.$weight.'%</span>';

            break;
          case 2: // Reject

            $view = 'smo/rkk/row_open';
            $data['status'] = '<span class="label label-danger">Rejected</span>';

            break;
          case 1: // Pending
            $view = 'smo/rkk/row_disable';
            $data['status'] = '<span class="label label-warning">Pending</span>';
            break;
          case 4: // Adjust
          case 5: // Final

            $view = 'smo/rkk/row_disable';
            $data['status'] = '<span class="label label-inverse">Locked</span>';


            break;
          case 3: // Aprove

            $view = 'smo/rkk/row_close';
            $data['status'] = '<span class="label label-success">Approved</span>';
            break;
        }

        $data['rkkId'] = $rkkPers->RKKID;
        $data['begin']  = date('d M Y',strtotime($rkkPers->BeginDate));
        $data['end']    = date('d M Y',strtotime($rkkPers->EndDate));

      } else {
        // RKK belum dibuat
        $data['rkkId'] = $row->NIK.'|'.$row->PositionID;
        $data['begin']  = '-';
        $data['end']    = '-';
        $data['status'] = '<span class="label label-default">Not  Created</span>';
        $view = 'smo/rkk/row_blank';
      }

      $this->parser->parse($view, $data);
    }
  }

  public function create_rkk()
  {
    $this->load->model('idp_model');
    $begin     = $this->input->post('dt_begin');
    $end       = $this->input->post('dt_end');
    $pers_ls   = $this->input->post('chk_rkk');
    $count_rkk = 0;
    $count_idp = 0;

    foreach ($pers_ls as $key => $value) {
      list($nik,$post_id) = explode('|',$value);
      $c_rkk = $this->rkk_model3->count_rkk_holder($nik,$post_id,1,$begin,$end);
      $spr = $this->om_model->get_superior_row($post_id,$begin,$end);
      if ($c_rkk == 0) {
        $rkk_id = $this->rkk_model3->add_rkk($nik,$post_id,1,$spr->nik,$spr->post_id, 1, $begin,$end);
        $count_rkk++;
      } else {

        $rkk_id = $this->rkk_model3->get_rkk_holder_last($nik,$post_id,1,$begin,$end)->RKKID;
      }
      #IDP
      $c_idp = $this->idp_model->count_DP($rkk_id,$begin,$end);
      if ($c_idp == 0) {
        $this->idp_model->add_Header($rkk_id,$begin,$end);
        $count_idp++;
      }

    }
    $respond['rkk'] = $count_rkk;
    $respond['idp'] = $count_idp;
    echo json_encode($respond);

  }

  public function edit_rkk()
  {
    $begin     = $this->input->post('dt_begin');
    $end       = $this->input->post('dt_end');
    $rkk_ls   = $this->input->post('chk_rkk');
    $count_rkk = 0;

    foreach ($rkk_ls as $key => $rkk_id) {
      $this->rkk_model3->edit_rkk($rkk_id,$begin,$end);
      $count_rkk++;

    }
    $respond['rkk'] = $count_rkk;
    echo json_encode($respond);
  }

  public function rev_rkk()
  {
    $this->load->model('revision_rkk_model');
    $rkk_ls = $this->input->post('chk_rkk');
    $period = $this->general_model->get_Period_row($this->session->userdata('active_period'));
    $begin  = $period->BeginDate;
    $end    = $period->EndDate;
    $count  = 0;

    foreach ($rkk_ls as $key => $rkk_id) {
      $this->revision_rkk_model->edit_rkk_status($rkk_id, 0);
      $idp = $this->revision_rkk_model->get_idp_by_rkk($rkk_id,$begin,$end);
      $this->revision_rkk_model->edit_idp_status($idp->IDPID, 0);
      $count++;
    }
    $respond['rkk'] = $count;
    echo json_encode($respond);
  }

  public function show_rel_to()
  {
    $rkk_id = $this->input->post('rkk_id');
    $this->load->model('org_model');

    $period = $this->general_model->get_Period_row($this->session->userdata('active_period'));
    $begin  = $period->BeginDate;
    $end    = $period->EndDate;
    $rel_ls = $this->rkk_model3->get_rkk_rel_BA_list($rkk_id,$begin,$end);
    $result = array();
    foreach ($rel_ls as $row) {
      $post_name = $this->org_model->get_Position_row($row->chief_post_id,1,$begin,$end)->PositionName;
      $result[] = array(
        'rel_id'    => $row->R_RKKID,
        'nik'       => $row->chief_nik,
        'name'      => $row->fullname,
        'post_id'   => $row->chief_post_id,
        'post_name' => $post_name,
        'begin'     => date('d M Y',strtotime($row->BeginDate)),
        'end'       => date('d M Y',strtotime($row->EndDate)),
      );
    }
    $json['result'] = $result;
    echo json_encode($json);
  }

  public function fix_rel()
  {
    $rkk_id = $this->input->post('rkk_id');
    $this->load->model('om_model');
    $this->load->model('rkk_model3');

    $rkk = $this->rkk_model3->get_rkk_row($rkk_id);

    $spr_ls = $this->om_model->get_superior_list($rkk->PositionID,$rkk->BeginDate,$rkk->EndDate);

    $add  = 0;
    $edit = 0;
    foreach ($spr_ls as $spr) {
      if ($rkk->BeginDate > $spr->begin ) {
        $rel_begin = $rkk->BeginDate;
      } else {
        $rel_begin = $spr->begin;
      }

      if ($rkk->EndDate < $spr->end ) {
        $rel_end = $rkk->EndDate;
      } else {
        $rel_end = $spr->end;
      }
      $check = $this->rkk_model3->check_rkkRel($rkk_id,$spr->nik,$spr->post_id);
      if($check) {
        $rel = $this->rkk_model3->get_rkkRel_byRKKnSpr($rkk_id,$spr->nik,$spr->post_id);
        $this->rkk_model3->edit_rkk_rel($rel->R_RKKID, $rel_begin,$rel_end);
        $edit++;
      } else {
        $this->rkk_model3->add_rkk_rel($rkk_id, $spr->nik,$spr->post_id, 1, $rel_begin,$rel_end);
        $add++;
      }
    }

    $respond['add']  = $add;
    $respond['edit'] = $edit;
    echo json_encode($respond);
  }
}
