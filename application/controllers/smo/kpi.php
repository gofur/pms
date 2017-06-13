<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kpi extends Controller{

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
    $this->load->model('rkk_model3');

		$this->load->model('general_model');
    $this->load->model('org_model');
		$this->load->model('om_model');
		$this->load->model('account_model');
    $this->load->library('parser');

  }

  function index()
  {
    $period         = $this->general_model->get_Period_row($this->session->userdata('active_period'));
    $data['period'] = $period->Tahun;
    $data['rkk_id']  = '-';
    $data['emp']     = '';
    $data['post']    = '';
    $data['org']     = '';

    $this->parser->parse('smo/kpi/main_view', $data);
  }

  function plan($rkk_id){
    $period = $this->general_model->get_Period_row($this->session->userdata('active_period'));
    $rkk    = $this->rkk_model3->get_rkk_row($rkk_id);
    $begin  = $period->BeginDate;
    $end    = $period->EndDate;
    $emp    = $this->account_model->get_User_byNIK($rkk->NIK);
    $post   = $this->om_model->get_post_row(1,$rkk->PositionID,$begin,$end);
    $org    = $this->om_model->get_org_row(1,$post->OrganizationID,$begin,$end);
    $data['period']  = $period->Tahun;
    $data['rkk_id']  = $rkk_id;
    $data['nik']     = $rkk->NIK;
    $data['emp']     = $rkk->NIK . ' - '. $emp->Fullname;
    $data['post']    = $post->PositionID .' - '.$post->PositionName;
    $data['post_id'] = $rkk->PositionID;
    $data['org']     = $org->OrganizationID .' - '. $org->OrganizationName;

    $this->parser->parse('smo/kpi/main_view', $data);
  }


  public function checkRkk()
  {
    $period  = $this->general_model->get_Period_row($this->session->userdata('active_period'));
    $nik     = $this->input->post('nik');
    $post_id = $this->input->post('postId');
    $begin   = $period->BeginDate;
    $end     = $period->EndDate;
    $count_rkk = $this->rkk_model3->count_rkk_holder($nik,$post_id,1, $begin,$end,'all');
    if ($count_rkk == 0) {
      $respond['rkk'] = FALSE;
      $respond['status'] = array(
        'text'  => 'not created',
        'label' => '');


    } else {
      $respond['rkk'] = TRUE;
      $rkk = $this->rkk_model3->get_rkk_holder_last($nik,$post_id,TRUE, $begin, $end,'all');
      $rel_ls = $this->rkk_model3->get_rkk_rel_BA_list($rkk->RKKID,$begin,$end);
      $i = 0;
      foreach ($rel_ls as $rel) {
        $post = $this->om_model->get_post_row(1,$rel->chief_post_id,$begin,$end);
        $respond['reportTo'][$i] = array(
          'nik'      => $rel->chief_nik,
          'name'     => $rel->fullname,
          'postId'   => $rel->chief_post_id,
          'begin'    => date('d M', strtotime($rel->BeginDate)),
          'end'      => date('d M', strtotime($rel->EndDate)),
          'postName' => $post->PositionName
        );
        $i++;
      }
      $respond['weight'] = $this->rkk_model3->count_weight_rkk($rkk->RKKID, $begin, $end);

      $respond['date'] = array(
        'begin' => date('d M Y',strtotime($rkk->BeginDate)),
        'end'   => date('d M Y',strtotime($rkk->EndDate))
      );



      switch ($rkk->statusFlag) {
        case 0:
          $respond['status'] = array(
            'text'  => 'Draft',
            'label' => '');
          break;
        case 1:

          $respond['status'] = array(
            'text'  => 'Assign',
            'label' => 'label-warning');
          break;
        case 2:

          $respond['status'] = array(
            'text'  => 'Rejected',
            'label' => 'label-important');
          break;
        case 3:

          $respond['status'] = array(
            'text'  => 'Agree',
            'label' => 'label-success');
          break;
        default:

          $respond['status'] = array(
            'text'  => 'lock',
            'label' => 'label-inverse');
          break;
      }

    }

    echo json_encode($respond);
  }

  public function detailRkk()
  {
    $period            = $this->general_model->get_Period_row($this->session->userdata('active_period'));
    $nik               = $this->input->post('nik');
    $post_id           = $this->input->post('postId');
    $begin             = $period->BeginDate;
    $end               = $period->EndDate;
    $post              = $this->org_model->get_Position_row($post_id,1,$begin,$end);
    $rkk               = $this->rkk_model3->get_rkk_holder_last($nik,$post_id,TRUE, $begin, $end,'all');
    $persp_ls          = $this->general_model->get_Perspective_List($begin,$end);
    $respond['rkk_id'] = $rkk->RKKID;
    $respond['org_id'] = $post->OrganizationID;

    $persp_i = 0;
    foreach ($persp_ls as $persp) {
      $weight = $this->rkk_model3->sum_weight_persp($rkk->RKKID,$persp->PerspectiveID,$begin,$end);

      $so_ls = $this->rkk_model3->get_so_persp_list($rkk->RKKID,$persp->PerspectiveID,$begin,$end);
      $respond['persp'][$persp_i] = array(
        'persp_id'     => $persp->PerspectiveID,
        'persp_label'  => $persp->Perspective,
        'persp_weight' => $weight,
      );
      $respond['persp'][$persp_i]['so'] = array();
      $so_i = 0;
      foreach ($so_ls as $so) {
        $so_weight = $this->rkk_model3->count_weight_so($rkk_id=0,$so->SasaranStrategisID,$begin,$end);
        $respond['persp'][$persp_i]['so'][$so_i] = array(
          'so_id'    => $so->SasaranStrategisID,
          'so_label' => $so->SasaranStrategis,
          'so_desc'     => $so->Description,
          'so_weight'   => $so_weight
        );
        $so_i++;
      }
      $persp_i++;
    }
    switch ($rkk->statusFlag) {
      case 0:
      case 2:
        $ls = $this->general_model->get_PCFormula_list(0,'',$begin,$end);
        $i = 0;
        $respond['formula'] = array();
        foreach ($ls as $row) {
          $respond['formula'][$i] = array(
            'formulaId' => $row->PCFormulaID,
            'formulaLabel'  => $row->PCFormula,
          );
          $i++;
        }
        $ls= $this->general_model->get_Satuan_list($begin,$end);
        $i = 0;
        $respond['unit'] = array();
        foreach ($ls as $row) {
          $respond['unit'][$i] = array(
            'unitId' => $row->SatuanID,
            'unitLabel'  => $row->Satuan,
          );
          $i++;
        }
        $ls    = $this->general_model->get_YTD_list($begin,$end);
        $i = 0;
        $respond['ytd'] = array();
        foreach ($ls as $row) {
          $respond['ytd'][$i] = array(
            'ytdId' => $row->YTDID,
            'ytdLabel'  => $row->YTD,
          );
          $i++;
        }
        echo $this->parser->parse('smo/kpi/open_view', $respond, TRUE);
        break;

      default:
        echo $this->parser->parse('smo/kpi/locked_view', $respond, TRUE);

        break;
    }

  }


  public function createSo()
  {
    $persp = $this->input->post('hdn_persp');
    $desc  = $this->input->post('txt_desc');
    $text  = $this->input->post('txt_so');
    $orgId = $this->input->post('hdn_org');
    $rkkId = $this->input->post('hdn_rkk');
    $rkk   = $this->rkk_model3->get_rkk_row($rkkId);
    $this->rkk_model3->add_so($orgId,$persp,$text,$desc,$rkk->BeginDate,$rkk->EndDate);
    $respond['status'] = TRUE;
    echo json_encode($respond);
  }

  public function editSo()
  {
    $so_id = $this->input->post('hdn_so');
    $persp = $this->input->post('hdn_persp');
    $desc  = $this->input->post('txt_desc');
    $text  = $this->input->post('txt_so');

    $rkkId = $this->input->post('hdn_rkk');
    $rkk   = $this->rkk_model3->get_rkk_row($rkkId);
    $this->rkk_model3->edit_so($so_id,$persp,$text,$desc,$rkk->BeginDate,$rkk->EndDate);
    $respond['status'] = TRUE;
    echo json_encode($respond);
  }

  public function removeSo()
  {
    $so_id = $this->input->post('soId');
    $this->rkk_model3->remove_so($so_id);
  }

  public function showKpi()
  {
    $rkk_id = $this->input->post('rkk_id');
    $so_id  = $this->input->post('so_id');
    $period = $this->general_model->get_Period_row($this->session->userdata('active_period'));
    $begin  = $period->BeginDate;
    $end    = $period->EndDate;
    $kpi_ls   = $this->rkk_model3->get_kpi_so_list($rkk_id,$so_id,$begin,$end);

    if (count($kpi_ls) == 0) {
      $data['notif_type'] = '';
			$data['notif_text'] = 'This SO doesn&#39;t have KPI';
			$this->load->view('template/notif_view', $data, FALSE);
    } else {
      foreach ($kpi_ls as $kpi) {
        $data['kpiLs'][] = array(
          'kpiId'       => $kpi->KPIID,
          'kpiBegin'    => substr($kpi->KPI_BeginDate, 0,10),
          'kpiEnd'      => substr($kpi->KPI_EndDate, 0,10),
          'kpiLabel'    => $kpi->KPI,
          'kpiYtd'      => $kpi->YTD,
          'kpiCounting' => $kpi->CaraHitung,
          'kpiFormula'  => $kpi->PCFormula,
          'kpiYearTarget'   => $kpi->TargetAkhirTahun,
          'kpiWeight'   => round($kpi->Bobot,2),
          'kpiRef'      => $kpi->Reference


        );
      }
      // $data['kpi_ls'] = $kpi_ls;
      $this->parser->parse('smo/kpi/kpi_list', $data );
    }


  }

  public function weightTotal()
  {
    $period            = $this->general_model->get_Period_row($this->session->userdata('active_period'));
    $nik               = $this->input->post('nik');
    $post_id           = $this->input->post('postId');
    $begin             = $period->BeginDate;
    $end               = $period->EndDate;
    $rkk               = $this->rkk_model3->get_rkk_holder_last($nik,$post_id,TRUE, $begin, $end,'all');
    $weight =  $this->rkk_model3->count_weight_rkk($rkk->RKKID, $begin, $end);
    $respond['totalWeight'] = $weight;
    echo json_encode($respond);
  }

  public function weightPersp($value='')
  {
    $period   = $this->general_model->get_Period_row($this->session->userdata('active_period'));
    $nik      = $this->input->post('nik');
    $post_id  = $this->input->post('postId');
    $persp_id = $this->input->post('perspId');
    $begin    = $period->BeginDate;
    $end      = $period->EndDate;
    $rkk      = $this->rkk_model3->get_rkk_holder_last($nik,$post_id,TRUE, $begin, $end,'all');
    $weight   = $this->rkk_model3->sum_weight_persp($rkk->RKKID,$persp_id,$begin,$end);

    $respond['weight'] = $weight;
    echo json_encode($respond);
  }

  public function getKpi()
  {
    $kpi_id = $this->input->post('kpiId');

    $kpi    = $this->rkk_model3->get_kpi_row($kpi_id);
    $target = $this->rkk_model3->get_target_list($kpi_id);
    $data['kpi'] = $kpi;
    $data['target'] = $target;
    echo json_encode($data);
  }

  public function createKpi()
  {
    $rkk_id    = $this->input->post('hdn_rkk');
    $so_id     = $this->input->post('hdn_kpi_so');
    $rkk       = $this->rkk_model3->get_rkk_row($rkk_id);

    $kpi_text   = $this->input->post('txt_kpi');
    $kpi_desc   = str_replace("'", '&#39;', $this->input->post('txt_kpi_desc'));
    $satuan     = $this->input->post('slc_unit');
    $formula    = $this->input->post('slc_formula');
    $ytd        = $this->input->post('slc_ytd');
    $weight     = $this->input->post('nm_weight');

    $months     = $this->input->post('chk_months');
    $targets    = array();
    if (count($months)) {
      foreach ($months as $key => $month) {
        $targets[$month] = $this->input->post('nm_target_'.$month);
      }
      $this->rkk_model3->add_kpi($rkk_id, 0, $so_id, $satuan ,$formula  ,$ytd  ,$kpi_text ,$kpi_desc , $weight, 0, $rkk->BeginDate, $rkk->EndDate, $targets);
    }

    $respond['status'] = TRUE;
    echo json_encode($respond);

  }

  public function editKpi()
  {
    $kpi_id = $this->input->post('hdn_kpi');
    $kpi    = $this->rkk_model3->get_kpi_row($kpi_id);
    $rkk_id    = $this->input->post('hdn_rkk');
    $so_id     = $this->input->post('hdn_so');
    $rkk       = $this->rkk_model3->get_rkk_row($rkk_id);

    $kpi_text   = $this->input->post('txt_kpi');
    $kpi_desc   = str_replace("'", '&#39;', $this->input->post('txt_kpi_desc'));
    $satuan     = $this->input->post('slc_unit');
    $formula    = $this->input->post('slc_formula');
    $ytd        = $this->input->post('slc_ytd');
    $weight     = $this->input->post('nm_weight');

    $months     = $this->input->post('chk_months');
    $targets    = array();
    if (count($months)) {
      foreach ($months as $key => $month) {
        $targets[$month] = $this->input->post('nm_target_'.$month);
      }
    	$this->rkk_model3->edit_kpi($kpi_id,0, $kpi->SasaranStrategisID, $satuan,$formula,$ytd,$kpi_text,$kpi_desc, $weight, 0, $rkk->BeginDate, $rkk->EndDate,$months,$targets);

    }

    $respond['status'] = TRUE;
    echo json_encode($respond);
  }

  public function removeKpi()
  {
    $kpi_id = $this->input->post('kpiId');
    $this->rkk_model3->remove_kpi($kpi_id);

    $respond['status'] = TRUE;
    echo json_encode($respond);
  }

  public function cascadeKpi()
  {

  }

}
