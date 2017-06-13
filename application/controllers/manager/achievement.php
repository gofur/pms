<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Achievement extends Controller {

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
    $this->load->model('achievement_model');
    $this->load->model('account_model');
    $this->load->model('org_model');
    $this->load->model('rkk_model3');
  }

  public function index()
  {
    $period   = $this->general_model->get_Period_row($this->session->userdata('active_period'));
    $user_id  = $this->session->userdata('userID');
    $nik      = $this->session->userdata('NIK');
    $user_dtl = $this->account_model->get_User_byNIK($nik);
    $start    = $period->BeginDate;
    $end      = $period->EndDate;
    $data['period']       = $period;
    $data['filter_start'] = substr($start, 0,10);
    $data['filter_end']   = substr($end, 0,10);
    $data['period']       = $period;
    $data['user_dtl']     = $user_dtl;

    $this->load->view('template/top_1_view');
    $this->load->view('manager/achv/head_view',$data);
    $post_ls = $this->account_model->get_post_list($nik,$start,$end);
    foreach ($post_ls as $row) {
      if (strtotime($period->BeginDate) >= strtotime($row->hold_begin)) {
        $begda = $period->BeginDate;
      } else {
        $begda = $row->hold_begin;

      }

      if (strtotime($period->EndDate) >= strtotime($row->hold_end)) {
        $endda = $period->EndDate;
      } else {
        $endda = $row->hold_end;
      }

      $sub_ls = $this->rkk_model3->get_rkk_sub_list($nik,$row->post_id,1,$begda,$endda);
      // echo var_dump($sub_ls);
      $bhv_arr  = array();
      $achv_arr = array();
      foreach ($sub_ls as $sub) {

        $achv = array();
        for ($month=1; $month <=12 ; $month++) {
          $result = $this->achievement_model->get_month_header($sub->RKKID,$month);
          if ($result) {
            // echo date('M',mktime(0,0,0,$month,1,2000)) .' = '. $result->cur_tpc.'/'.$result->ytd_tpc .' ['.$result->status.']';
            switch ($result->status) {
              case 0: // Not Submitted
                $achv[$month] = '<span class="label">-</span>';
                break;
              case 1: // Pending
                $achv[$month] = '<span class="label label-warning">'. $result->cur_tpc.'<hr>'.$result->ytd_tpc .'</span>';
                break;
              case 2: // Rejected
                $achv[$month] = '<span class="label label-important">'. $result->cur_tpc.'<hr>'.$result->ytd_tpc .'</span>';
                break;
              case 3: // Approved
                $achv[$month] = '<span class="label label-success">'. $result->cur_tpc.'<hr>'.$result->ytd_tpc .'</span>';
                break;
              case 5: // Lock
                $achv[$month] = '<span class="label label-success">'. $result->cur_tpc.'<hr>'.$result->ytd_tpc .'</span>';
                break;
              default:
                $achv[$month] = '<span class=" label-inverse">Error</span>';

                break;
            }
          } else {
            $achv[$month] = '<span class="label">-</span>';
            // echo date('M',mktime(0,0,0,$month,1,2000)) .' = 0';
          }

          // echo '<br>';
        }
        $achv_arr[$sub->RKKID] = $achv;

        // Behaviour
        $this->load->model('behaviour_model');
        unset($bhv);
        $chk_bhv = $this->behaviour_model->get_count_bhv_header_last_data('12'.substr($period->BeginDate, 0,4),$sub->NIK);

        if ($chk_bhv) {
          $bhv = $this->behaviour_model->get_bhv_header_last_data('12'.substr($period->BeginDate, 0,4),$sub->NIK);
          switch ($bhv->status) {
            case 0: // Not Submitted
                $bhv_arr[$sub->RKKID] = '<span class="label">Pending</span>';
                break;
              case 1: // Pending
                $bhv_arr[$sub->RKKID] = '<span class="label label-warning">Pending</span>';
                break;
              case 2: // Rejected
                $bhv_arr[$sub->RKKID] = '<span class="label label-important">Reject</span>';
                break;
              case 3: // Approved
                $bhv_arr[$sub->RKKID] = '<span class="label label-success">'. round($bhv->total_achievement,2) .'</span>';
                break;
              case 5: // Lock
                $bhv_arr[$sub->RKKID] = '<span class="label label-success">'. round($bhv->total_achievement,2) .'</span>';
                break;
              default:
                $bhv_arr[$sub->RKKID] = '<span class=" label-inverse">Error</span>';

                break;
          }

        } else {
          $bhv_arr[$sub->RKKID] = '<span class="label">-</span>';
        }

      }
      $data['sub_ls']    = $sub_ls;
      $data['achv_arr']  = $achv_arr;
      $data['bhv_arr']   = $bhv_arr;
      $data['post_name'] = $row->post_name;

      $this->load->view('manager/achv/subordinate_table',$data);

    }

    $this->load->view('template/bottom_1_view');
    $this->load->view('manager/achv/js');


  }

  public function show_sub()
  {
    $period   = $this->general_model->get_Period_row($this->session->userdata('active_period'));

    $sup_rkk_id = $this->input->post('rkk');
    $sup_rkk    = $this->rkk_model3->get_rkk_row($sup_rkk_id);

    if (strtotime($period->BeginDate) >= strtotime($sup_rkk->BeginDate)) {
      $begda = $period->BeginDate;
    } else {
      $begda = $row->BeginDate;

    }

    if (strtotime($period->EndDate) >= strtotime($sup_rkk->EndDate)) {
      $endda = $period->EndDate;
    } else {
      $endda = $row->EndDate;
    }

    $sub_rkk_ls = $this->rkk_model3->get_rkk_sub_list($sup_rkk->NIK,$sup_rkk->PositionID,1,$begda,$endda);

    if (count($sub_rkk_ls)) {
      $bhv_arr  = array();
      $achv_arr = array();
      foreach ($sub_rkk_ls as $sub) {
        $achv = array();
        for ($month=1; $month <=12 ; $month++) {
          $result = $this->achievement_model->get_month_header($sub->RKKID,$month);

          if ($result) {
            switch ($result->status) {
              case 0: // Not Submitted
              $achv[$month] = '<span class="label">-</span>';
              break;
              case 1: // Pending
              $achv[$month] = '<span class="label label-warning">'. $result->cur_tpc.'<hr>'.$result->ytd_tpc .'</span>';
              break;
              case 2: // Rejected
              $achv[$month] = '<span class="label label-important">'. $result->cur_tpc.'<hr>'.$result->ytd_tpc .'</span>';
              break;
              case 3: // Approved
              $achv[$month] = '<span class="label label-success">'. $result->cur_tpc.'<hr>'.$result->ytd_tpc .'</span>';
              break;
              case 5: // Lock
              $achv[$month] = '<span class="label label-success">'. $result->cur_tpc.'<hr>'.$result->ytd_tpc .'</span>';
              break;
              default:
              $achv[$month] = '<span class=" label-inverse">Error</span>';

              break;
            }

          } else {
            $achv[$month] = '<span class="label">-</span>';
          }
        }
        $achv_arr[$sub->RKKID] = $achv;
        // Behaviour
        $this->load->model('behaviour_model');
        unset($bhv);
        $chk_bhv = $this->behaviour_model->get_count_bhv_header_last_data('12'.substr($period->BeginDate, 0,4),$sub->NIK);

        if ($chk_bhv) {
          $bhv = $this->behaviour_model->get_bhv_header_last_data('12'.substr($period->BeginDate, 0,4),$sub->NIK);
          switch ($bhv->status) {
            case 0: // Not Submitted
            $bhv_arr[$sub->RKKID] = '<span class="label">Pending</span>';
            break;
            case 1: // Pending
            $bhv_arr[$sub->RKKID] = '<span class="label label-warning">Pending</span>';
            break;
            case 2: // Rejected
            $bhv_arr[$sub->RKKID] = '<span class="label label-important">Reject</span>';
            break;
            case 3: // Approved
            $bhv_arr[$sub->RKKID] = '<span class="label label-success">'. round($bhv->total_achievement,2) .'</span>';
            break;
            case 5: // Lock
            $bhv_arr[$sub->RKKID] = '<span class="label label-success">'. round($bhv->total_achievement,2) .'</span>';
            break;
            default:
            $bhv_arr[$sub->RKKID] = '<span class=" label-inverse">Error</span>';

            break;
          }

        } else {
          $bhv_arr[$sub->RKKID] = '<span class="label">-</span>';
        }

      }
      $data['sub_ls']    = $sub_rkk_ls;
      $data['achv_arr']  = $achv_arr;
      $data['bhv_arr']   = $bhv_arr;
      $data['post_name'] = '';

      echo $this->load->view('manager/achv/sub_achv_list',$data,TRUE);

    } else {
      echo "No Data Here";
    }



  }

}

/* End of file achievement.php */
/* Location: ./application/controllers/manager/achievement.php */
