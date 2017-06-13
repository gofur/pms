<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Home extends Controller {
	function __construct(){
		parent::__construct();
		if ($this->session->userdata('loginFlag')==0){
			redirect('account/login');
		}
	}
	function index(){
		$this->load->model('general_model');
		$this->load->model('rkk_model3');
		$this->load->model('account_model');
		$this->load->model('report_model');
		$this->load->model('org_model');
		$this->load->model('behaviour_model');
		
		$period     = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$this_year  = date('Y');
		if ($this_year > $period->Tahun) {
			$this_month	= 12;
		} else {
			$this_month	= date('m');
			
		}

		// $period       = $this->general_model->get_ActivePeriode();
		$sess_nik   = $this->session->userdata('NIK');

		$c_rkk = $this->rkk_model3->count_rkk_nik($sess_nik,$period->BeginDate,$period->EndDate,array(1,2,3,4,5));
		if ($c_rkk) {
			$rkk_ls   = $this->rkk_model3->get_rkk_nik_list($sess_nik,$period->BeginDate,$period->EndDate,array(1,2,3,4,5));
		}

		////////////////////
		// Notification //
		////////////////////

		$notif_ls   = '';

		$rkk_pend   = $this->rkk_model3->count_rkk_nik($sess_nik,$period->BeginDate,$period->EndDate,'pending');
		if ($rkk_pend) {
			$dat['counter'] = $rkk_pend;
			$notif_ls .= $this->load->view('template/notif/rkk_pend', $dat, TRUE);
			unset($dat);
		}

		$rkk_reject = $this->report_model->count_rkk_B($sess_nik,date('Y-m-d 00:00:00'),date('Y-m-d 23:59:59'),'reject');
		if ($rkk_reject) {
			$rkk_B = $this->report_model->get_rkk_B_list($sess_nik,date('Y-m-d 00:00:00'),date('Y-m-d 23:59:59'),'reject');
			foreach ($rkk_B as $row) {
				$dat['name'] = $row->NIK . ' - '.$row->Fullname;
				$notif_ls .= $this->load->view('template/notif/rkk_reject', $dat, TRUE);
				unset($dat);
			}
		}

		$ach_pend = $this->report_model->count_achv_rkk_B($sess_nik,0,$period->BeginDate,$period->EndDate,'pending');
		if ($ach_pend) {
			$ach_B = $this->report_model->get_achv_rkk_B_list($sess_nik,0,$period->BeginDate,$period->EndDate,'pending');
			foreach ($ach_B as $row) {
				$dat['name']  = $row->NIK . ' - '.$row->Fullname;
				$dat['month'] = date('F',mktime(0,0,0,$row->Month,$row->Month,2000));
				$notif_ls .= $this->load->view('template/notif/achv_pend', $dat, TRUE);

				unset($dat);

			}
		}

		if ($c_rkk) {
			foreach ($rkk_ls as $row) {
				$ach_reject = $this->report_model->count_achv_rkk($row->RKKID,0,'reject');
				if ($ach_reject) {
					$achv = $this->report_model->get_achv_rkk_list($row->RKKID,0,'reject');

					foreach ($achv as $row) {
						$dat['month'] = date('F',mktime(0,0,0,$row->Month,$row->Month,2000));
						$rkk = $this->rkk_model3->get_rkk_row($row->RKKID);
						$post = $this->org_model->get_Position_row($rkk->PositionID,$rkk->isSAP,$rkk->BeginDate,$rkk->EndDate)->PositionName;
						$dat['as'] = $post;
						$notif_ls .= $this->load->view('template/notif/achv_reject', $dat, TRUE);
						unset($dat);
					}
				}
				
			}
		}

		$data['notif_ls'] = $notif_ls;

		// end of Notifcation

		
		///////////////
		// Project //
		///////////////

		$c_proj = $this->report_model->count_project($sess_nik,$period->BeginDate,$period->EndDate); 
		
		$proj_result = $this->report_model->sum_result($sess_nik,$period->BeginDate,$period->EndDate);
		if ($c_proj == 1) {
			if ($proj_result > 0.30) {
				$proj_result = 0.30;
			}
		} elseif ($c_proj >= 2) {
			if ($proj_result > 0.60) {
				$proj_result = 0.60;
			}
		} 

		$data['proj_result'] = round($proj_result,2);

		// end of Project

		/////////////////////
		// Gauge Visual  //
		/////////////////////
		
		$data['color_range'] = $this->general_model->get_Scale_list(2,date('Y-m-d'),date('Y-m-d'));
		$data['max_high']    = $this->general_model->get_Scale_statistic(2,date('Y-m-d'),date('Y-m-d'))->high_max;

		// end of Gauge Visual
		
		////////////////////
		// Gauge Value  //
		////////////////////
		$ba_cur = 0;
		$ba_ytd = 0;
		
		
		if ($c_rkk ) {
			$rkk_ls   = $this->rkk_model3->get_rkk_nik_list($sess_nik,$period->BeginDate,$period->EndDate,'approve');
			$ytd_ls   = array();
			$month_ls = array(0=>0);
			$achv_ls 	= array();
			$x = 0;
			$dur_ls   = array();
			$durXytd   = array();
			foreach ($rkk_ls as $row) {
				$rkk_id = $row->RKKID;
				$c_achv = $this->report_model->count_achv_rkk($rkk_id,$this_month,'approve');
				$achv_ls[$row->RKKID] = '-';
				if ($c_achv) {
					$achv                 = $this->report_model->get_achv_rkk_last($rkk_id,$this_month,'approve');
					$ytd_ls[$x]           = $achv->YTD_TPC;
					$achv_ls[$row->RKKID] = $achv->YTD_TPC;
					
					$ba_cur       				= $achv->Cur_TPC;
					$rkk   = $this->rkk_model3->get_rkk_row($achv->RKKID);
					$m1    = date('n',strtotime($rkk->BeginDate));
					$m2    = date('n',strtotime($rkk->EndDate));
					if ($m2 > $achv->Month) {
						$m2 = $achv->Month;
					}
					$month_ls[$x] = $achv->Month;
					$dur_ls[$x] 	= ($m2 - $m1 )+1;
					$durXytd[$x] 	= (($m2 - $m1 )+1) * $achv->YTD_TPC;
					$x++;
				}

				unset($rkk_id);
				unset($c_achv);
			}
		

			$temp = 0;
			
			$total_dur = array_sum($dur_ls);
			$total_ytd = array_sum($durXytd);
			if ($total_ytd !=0) {
				$ba_ytd = $total_ytd / $total_dur;
				
			} else {
				$ba_ytd = 0;
			}
		}


		/** 
		 * Behaviour  Chart Diagram
		 * Ambil data total_achievement 
		 */
		
		$sess_nik;
		// $period     = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		// $bulan_sekarang=$this_month.date("Y");
		$bulan_sekarang = $this_month.$period->Tahun;
		
		$count_get_performance = $this->behaviour_model->get_count_bhv_header_last_data($bulan_sekarang,$sess_nik);

		if($count_get_performance)
		{
			$get_performance = $this->behaviour_model->get_bhv_header_last_data($bulan_sekarang,$sess_nik);
			$get_data_aspect_setting_row = $this->behaviour_model->get_aspect_setting_data_list_by_row($get_performance->aspect_setting_id);
			$data['percentage']=$get_data_aspect_setting_row->percentage;
			//$nilai_behaviour= $get_data_aspect_setting_row->percentage/100*round($get_performance->total_achievement,2);
			$nilai_behaviour=round($get_performance->total_achievement,2);
			$data['total_behaviour'] = $nilai_behaviour;
			$be_cur = $nilai_behaviour;
			$be_ytd = $nilai_behaviour;	

		}else{
			$be_cur = 0;
			$be_ytd = 0;
			$data['percentage']=30;
		}

		$gt_cur = (0.7 * $ba_cur) + (0.3 * $be_cur) + $proj_result;
		$gt_ytd = (0.7 * $ba_ytd) + (0.3 * $be_ytd) + $proj_result;

		$data['ba_cur'] = $ba_cur * 10;
		$data['ba_ytd'] = $ba_ytd * 10;

		$data['be_cur'] = $be_cur * 10;
		$data['be_ytd'] = $be_ytd * 10;

		$data['gt_cur'] = round($gt_cur * 10 ,2);
		$data['gt_ytd'] = round($gt_ytd * 10 ,2);
		
		// end of Gauge Value
		
		////////////////
		// Table BA //
		////////////////
		if ($c_rkk ) {
			$post_name =  array();
			foreach ($rkk_ls as $row) {
				$post_name[$row->RKKID] = $this->org_model->get_Position_row($row->PositionID,$row->isSAP,$row->BeginDate,$row->EndDate)->PositionName;
			}
			$data['rkk_ls']    = $rkk_ls;
			$data['post_name'] = $post_name;
			$data['achv_ls']   = $achv_ls;
		}	else {
			$data['rkk_ls'] = array();

		}

		$this->load->view('home_view',$data);	
		

	}

	public function show_monthly_achv()
	{
		$this->load->model('report_model');
		$this->load->model('general_model');
		$rkk_id = $this->input->post('rkk_id');
		$period     = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$this_year  = date('Y');
		if ($this_year > $period->Tahun) {
			$month	= 12;
		} else {
			$month	= date('m');
			
		}
		$c_achv = $this->report_model->count_achv_rkk($rkk_id,$month);
		if ($c_achv) {
			$achv_ls = $this->report_model->get_achv_rkk_list($rkk_id,$month);
			$data['achv_ls'] = $achv_ls;
			$this->load->view('achv_view', $data, FALSE);
		} else {
			$data['notif_type'] = '';
			$data['notif_text'] = 'This RKK doesn&#39;t have Achievement';
			$this->load->view('template/notif_view', $data, FALSE);
		}

	}

	
}
