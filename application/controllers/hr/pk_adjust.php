<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pk_adjust extends Controller {

	function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('loginFlag'))
		{
			redirect('account/login');
		}
		$this->load->library('email');
		$this->load->model('rkk_model3');
		$this->load->model('achv_biz_model');
		$this->load->model('achv_bhv_model');
		$this->load->model('project_model');
		$this->load->model('report_model');

		$this->load->model('om_model');
		$this->load->model('account_model');
		$this->load->model('general_model');
		$this->load->model('aspect_model');

	}

	public function index()
	{
		$period       = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$pers_admin   = $this->session->userdata('PersAdmin');
		$nik          = $this->session->userdata('NIK');
		$user_dtl     = $this->account_model->get_User_byNIK($nik);

		$data['period']   = $period;
		$data['user_dtl'] = $user_dtl;

		$this->load->view('hr/pk_adjust/main_view', $data);
	}

	public function show_root_org()
	{
		$pers_admin = $this->session->userdata('PersAdmin');
		$is_sap     = $this->session->userdata('isSAP');
		$period = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		if ($period->Tahun < date('Y')) {
			$month = 12;
		} else {
			$month   = date('m');
		}
		
		$begin      = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, substr($period->BeginDate, 0,4)));
		$end        = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, substr($period->EndDate, 0,4))); 
		$org_ls     = $this->om_model->get_hr_org_list($is_sap,$pers_admin,$begin,$end);
		$org_opt    = array(''=>'');
		foreach ($org_ls as $row) {
			$org_opt[$row->OrganizationID] = $row->OrganizationName;
		}
		$data['org_ls'] = $org_opt;
		$data['num'] 		= 0;

		$this->load->view('hr/pk_adjust/org_opt', $data);

	}

	public function show_child_org()
	{
		
		$is_sap    = $this->session->userdata('isSAP');
		$period = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		if ($period->Tahun < date('Y')) {
			$month = 12;
		} else {
			$month   = date('m');
		}
		
		$begin     = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, substr($period->BeginDate, 0,4)));
		$end       = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, substr($period->EndDate, 0,4)));
		$parent_id = $this->input->post('parent');
		$num       = $this->input->post('num');
		$c_org     = $this->om_model->count_org_byParent($is_sap,$parent_id,$begin,$end);
		if ($c_org) {
			$org_ls = $this->om_model->get_org_byParent_list($is_sap,$parent_id,$begin,$end);
			$org_opt    = array(''=>'');
			foreach ($org_ls as $row) {
				$org_opt[$row->OrganizationID] = $row->OrganizationName;

			}
			$data['org_ls'] = $org_opt;
			$data['num']    = $num + 1;
			$this->load->view('hr/pk_adjust/org_opt', $data);
		}
	}

	public function show_achv()
	{
		$this->load->model('adjust_model');

		$is_sap = $this->session->userdata('isSAP');
		$org_id = $this->input->post('org_id');
		$scope  = $this->input->post('scope');

		$period = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		if ($period->Tahun < date('Y')) {
			$month = 12;
		} else {
			$month   = date('m');
		}
		
		$data['process'] = 'hr/pk_adjust/process';
		$data['hidden']  = array(
			'org_id' => $org_id,
			'scope'  => $scope,
			'month'  => $month
		);
		
		$begin  = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, substr($period->BeginDate, 0,4)));
		$end    = date('Y-m-t H:i:s', mktime(0, 0, 0, $month, 1, substr($period->EndDate, 0,4)));

		$aspect = $this->aspect_model->get_setting_list($is_sap,$org_id,$begin,$end);
		$c_aspect = count($aspect);
		$data['c_aspect'] = $c_aspect;
		$data['aspect_ls']   = $aspect;
		foreach ($aspect as $row) {
			$frequency = $row->frequency;  
		}

		$month_ls = explode(';', $frequency);

		$sub_period = count($month_ls);
		$data['sub_period'] = $sub_period;
		switch ($scope) {
			case 1:
				$temp_ls = $this->om_model->get_hold_tree_byOrg_list($is_sap, $org_id,$begin,$end);
				break;
			
			default:
				$temp_ls = $this->om_model->get_hold_byOrg_list($is_sap, $org_id,$begin,$end);
				break;
		}
		$temp_arr = array();
		$i = 0;

		foreach ($temp_ls as $row) {
			$temp_arr[$i] = array(
				'nik'       => $row->NIK,
				'fullname'  => $row->Fullname,
				'org_name'  => $row->org_name,
				'post_name' => $row->post_name
			);

			$total = 0;
			
			$ba_ytd = '-';
			$c_rkk      = $this->rkk_model3->count_rkk_nik($row->NIK,$period->BeginDate,$period->EndDate,array(3,4,5));
			$c_rkk_main = $this->rkk_model3->count_rkk_nik_main($row->NIK,$period->BeginDate,$period->EndDate,array(3,4,5));// Mencari RKK pada sub periode ini
			$c_rkk_non  = $c_rkk - $c_rkk_main;

			foreach ($aspect as $row_2) {
				if ($row_2->aspect_id == 1) {
					$c_rkk      = $this->rkk_model3->count_rkk_nik($row->NIK,$period->BeginDate,$period->EndDate,array(3,4,5));
					if ($c_rkk ) {
					$rkk_ls   = $this->rkk_model3->get_rkk_nik_list($row->NIK,$period->BeginDate,$period->EndDate,'approve');
					$ytd_ls   = array();
					$month_ls = array(0=>0);
					$achv_ls 	= array();
					$x = 0;
					$dur_ls   = array();
					$durXytd   = array();
					$this_month = 12;
					foreach ($rkk_ls as $row) {
						$rkk_id = $row->RKKID;
						$c_achv = $this->report_model->count_achv_rkk($rkk_id,$this_month,'approve');
						$achv_ls[$row->RKKID] = '-';
						if ($c_achv) {
							$achv                 = $this->report_model->get_achv_rkk_last($rkk_id,$this_month,'approve');
							$ytd_ls[$x]           = $achv->YTD_TPC;
							$achv_ls[$row->RKKID] = $achv->YTD_TPC;
							
							$ba_cur       				= $achv->Cur_TPC;
							$month_ls[$x]         = $achv->Month;
							$m1                   = date('n',strtotime($row->BeginDate));
							$m2                   = date('n',strtotime($row->EndDate));
							if ($m2 > $achv->Month) {
								$m2 = $achv->Month;
							}
							$dur_ls[$x]           = ($m2 - $m1 )+1;
							$durXytd[$x]          = (($m2 - $m1 )+1) * $achv->YTD_TPC;
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
					$total += $ba_ytd * $row_2->percent /100 ;
					$temp_arr[$i][$row_2->aspect_id] = round($ba_ytd * $row_2->percent /100,2); 
					} else {
						$temp_arr[$i][$row_2->aspect_id] = 0;
					} 
				} else { // Untuk Aspect Behavior
					

					$month_year = '12'.substr($period->EndDate,0,4);
					$c_achv = $this->achv_bhv_model->count_header($month_year,$row->NIK,$row_2->aspect_setting_id,TRUE,array(3,4,5));
		
					
					if ($c_achv  ) { // Jika Ada Achv
						$achv = $this->achv_bhv_model->get_header_byAspect_row($month_year,$row->NIK,$row_2->aspect_setting_id,TRUE,array(3,4,5));
						$temp_arr[$i][$row_2->aspect_id] = round(($achv->total_achv * $row_2->percent /100),2) ;
						$total += ($achv->total_achv * $row_2->percent /100) ;

					} else {
						if (!isset($temp_arr[$i][$row_2->aspect_id]) OR $temp_arr[$i][$row_2->aspect_id] == 0 ) {
							$temp_arr[$i][$row_2->aspect_id] = 0;
						}
						$total += 0 ;
					}


				}
			}

				//////////////////
				// Project  //
				//////////////////
				
				$c_project = $this->project_model->count_member_result($row->NIK,$period->BeginDate,$period->EndDate);
				
				switch ($c_project) {
					case 0:
						$r_project = 0;
						break;
					case 1:
						$r_project = $this->project_model->sum_member_result($row->NIK,$period->BeginDate,$period->EndDate);
						if ($r_project > 0.3) {
							$r_project = 0.3;
						}
						break;
					case 2:
						$r_project = $this->project_model->sum_member_result($row->NIK,$period->BeginDate,$period->EndDate);
						if ($r_project > 0.6) {
							$r_project = 0.6;
						}
						break;
					default:
						$r_project = $this->project_model->sum_member_result($row->NIK,$period->BeginDate,$period->EndDate);
						if ($r_project > 0.6) {
							$r_project = 0.6;
						}
						break;
				}

				
				$grand_total              = round(($total + $r_project),2);
				$category                 = $this->report_model->get_category_row($grand_total,$period->BeginDate,$period->EndDate)->cat_en_short;
				$temp_arr[$i]['project']  = round($r_project,2); 
				$temp_arr[$i]['total']    = $grand_total;
				$temp_arr[$i]['category'] = $category; 
				$temp_arr[$i]['notes_link'] = 'manager/pk_adjust/notes_form/'.$row->NIK.'/'.$period->BeginDate.'/'.$period->EndDate;

				$c_adj = $this->adjust_model->count_result($row->NIK,$period->BeginDate,$period->EndDate);
				if ($c_adj) {
					$adj = $this->adjust_model->get_result_row($row->NIK,$period->BeginDate,$period->EndDate)->after_value;
					$temp_arr[$i]['adjustment'] = form_number('nm_adj_'.$row->NIK,round($adj,2),'class="span1" min="0" max="5" step="0.01"'); 
				
				} else {
					$temp_arr[$i]['adjustment'] = form_number('nm_adj_'.$row->NIK,round(($total + $r_project),2),'class="span1" min="0" max="5" step="0.01"'); 
					
				}

				
			$i++;
			unset($c_rkk);
		}
		$data['temp_list'] = $temp_arr;
		$this->load->view('hr/pk_adjust/achv_list', $data, FALSE);
	}

	public function lock()
	{
		$is_sap = $this->session->userdata('isSAP');
		$org_id = $this->input->post('org_id');
		$scope  = $this->input->post('scope');
		$month  = date('m');
		$period = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		
		$begin  = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, substr($period->BeginDate, 0,4)));
		$end    = date('Y-m-t H:i:s', mktime(0, 0, 0, $month, 1, substr($period->EndDate, 0,4)));

		$aspect = $this->aspect_model->get_setting_list($is_sap,$org_id,$begin,$end);
		$c_aspect = count($aspect);

		$data['aspect_ls']   = $aspect;
		foreach ($aspect as $row) {
			$frequency = $row->frequency;  
		}

		$month_ls = explode(';', $frequency);
		$sub_period = count($month_ls);
		switch ($scope) {
			case 1:
				$temp_ls = $this->om_model->get_hold_tree_byOrg_list($is_sap, $org_id,$begin,$end,1);
				break;
			
			default:
				$temp_ls = $this->om_model->get_hold_byOrg_list($is_sap, $org_id,$begin,$end,1);
				break;
		}
		$temp_arr = array();

		foreach ($temp_ls as $row) {
			$c_rkk      = $this->rkk_model3->count_rkk_nik($row->NIK,$period->BeginDate,$period->EndDate,array(3,4,5));
			$c_rkk_main = $this->rkk_model3->count_rkk_nik_main($row->NIK,$period->BeginDate,$period->EndDate,array(3,4,5));// Mencari RKK pada sub periode ini
			$c_rkk_non  = $c_rkk - $c_rkk_main;
			foreach ($aspect as $row_2) {
				if ($row_2->aspect_id == 1) {

					if ($c_rkk == 1) {
						$rkk_id = $this->rkk_model3->get_rkk_nik_last($row->NIK,$period->BeginDate,$period->EndDate,array(3,4,5))->RKKID;
						$c_achv = $this->achv_biz_model->count_header($rkk_id,array(3,4,5));

						if ($c_achv) {
							$achv_ls = $this->achv_biz_model->get_header_list($rkk_id,array(3,4,5));
							$this->achv_biz_model->edit_header_status($row_4->RKKAchievementID,4);//Memberikan status Lock Adjustmnt agar tidak dapat diubah
							
						}

					} elseif ($c_rkk > 1) {
						$rkk_ls = $this->rkk_model3->get_rkk_nik_list($row->NIK,$period->BeginDate,$period->EndDate,array(3,4,5));
						foreach ($rkk_ls as $row_3) {
							$rkk_id = $row_3->RKKID;
							$c_achv = $this->achv_biz_model->count_header($rkk_id,array(3,4,5));

							if ($c_achv) {
								$achv_ls = $this->achv_biz_model->get_header_list($rkk_id,array(3,4,5));
								$this->achv_biz_model->edit_header_status($row_4->RKKAchievementID,4);//Memberikan status Lock Adjustmnt agar tidak dapat diubah
							}

						}


					}
				} else {
					$month_year = '12'.substr($period->EndDate,0,4);
					$c_achv     = $this->achv_bhv_model->count_header($month_year,$row->NIK,$row_2->aspect_setting_id,TRUE,array(3,4,5));
					if ($c_achv  ) { // Jika Ada Achv
						$bhv_id = $this->achv_bhv_model->get_header_byAspect_row($month_year,$row->NIK,$row_2->aspect_setting_id,TRUE,array(3,4,5))->header_id;
						$this->achv_bhv_model->edit_header_status($bhv_id,4);//Memberikan status Lock Adjustmnt agar tidak dapat diubah

					}
				}
			}

		}
	}

	public function unlock()
	{
		$is_sap = $this->session->userdata('isSAP');
		$org_id = $this->input->post('org_id');
		$scope  = $this->input->post('scope');
		$month  = date('m');
		$period = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		
		$begin  = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, substr($period->BeginDate, 0,4)));
		$end    = date('Y-m-t H:i:s', mktime(0, 0, 0, $month, 1, substr($period->EndDate, 0,4)));

		$aspect = $this->aspect_model->get_setting_list($is_sap,$org_id,$begin,$end);
		$c_aspect = count($aspect);

		$data['aspect_ls']   = $aspect;
		foreach ($aspect as $row) {
			$frequency = $row->frequency;  
		}

		$month_ls = explode(';', $frequency);
		$sub_period = count($month_ls);
		switch ($scope) {
			case 1:
				$temp_ls = $this->om_model->get_hold_tree_byOrg_list($is_sap, $org_id,$begin,$end,1);
				break;
			
			default:
				$temp_ls = $this->om_model->get_hold_byOrg_list($is_sap, $org_id,$begin,$end,1);
				break;
		}
		$temp_arr = array();

		foreach ($temp_ls as $row) {
			$c_rkk      = $this->rkk_model3->count_rkk_nik($row->NIK,$period->BeginDate,$period->EndDate,array(3,4,5));
			$c_rkk_main = $this->rkk_model3->count_rkk_nik_main($row->NIK,$period->BeginDate,$period->EndDate,array(3,4,5));// Mencari RKK pada sub periode ini
			$c_rkk_non  = $c_rkk - $c_rkk_main;
			foreach ($aspect as $row_2) {
				if ($row_2->aspect_id == 1) {

					if ($c_rkk == 1) {
						$rkk_id = $this->rkk_model3->get_rkk_nik_last($row->NIK,$period->BeginDate,$period->EndDate,array(3,4,5))->RKKID;
						$c_achv = $this->achv_biz_model->count_header($rkk_id,array(3,4,5));

						if ($c_achv) {
							$achv_ls = $this->achv_biz_model->get_header_list($rkk_id,array(3,4,5));
							$this->achv_biz_model->edit_header_status($row_4->RKKAchievementID,3);//Memberikan status UnLock Adjustmnt agar tidak dapat diubah
							
						}

					} elseif ($c_rkk > 1) {
						$rkk_ls = $this->rkk_model3->get_rkk_nik_list($row->NIK,$period->BeginDate,$period->EndDate,array(3,4,5));
						foreach ($rkk_ls as $row_3) {
							$rkk_id = $row_3->RKKID;
							$c_achv = $this->achv_biz_model->count_header($rkk_id,array(3,4,5));

							if ($c_achv) {
								$achv_ls = $this->achv_biz_model->get_header_list($rkk_id,array(3,4,5));
								$this->achv_biz_model->edit_header_status($row_4->RKKAchievementID,3);//Memberikan status UnLock Adjustmnt agar tidak dapat diubah
							}

						}


					}
				} else {
					$month_year = '12'.substr($period->EndDate,0,4);
					$c_achv     = $this->achv_bhv_model->count_header($month_year,$row->NIK,$row_2->aspect_setting_id,TRUE,array(3,4,5));
					if ($c_achv  ) { // Jika Ada Achv
						$bhv_id = $this->achv_bhv_model->get_header_byAspect_row($month_year,$row->NIK,$row_2->aspect_setting_id,TRUE,array(3,4,5))->header_id;
						$this->achv_bhv_model->edit_header_status($bhv_id,3);//Memberikan status UnLock Adjustmnt 
					}
				}
			}

		}
	}

	public function process()
	{
		$this->load->model('adjust_model');

		$is_sap = $this->session->userdata('isSAP');
		$org_id = $this->input->post('org_id');
		$scope  = $this->input->post('scope');
		$period = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		if ($period->Tahun < date('Y')) {
			$month = 12;
		} else {
			$month   = date('m');
		}
		
		$begin  = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, substr($period->BeginDate, 0,4)));
		$end    = date('Y-m-t H:i:s', mktime(0, 0, 0, $month, 1, substr($period->EndDate, 0,4)));

		$aspect = $this->aspect_model->get_setting_list($is_sap,$org_id,$begin,$end);
		$c_aspect = count($aspect);
		
		foreach ($aspect as $row) {
			$frequency = $row->frequency;  
		}

		$month_ls = explode(';', $frequency);
		$sub_period = count($month_ls);

		switch ($scope) {
			case 1:
				$temp_ls = $this->om_model->get_hold_tree_byOrg_list($is_sap, $org_id,$begin,$end,1);
				break;
			
			default:
				$temp_ls = $this->om_model->get_hold_byOrg_list($is_sap, $org_id,$begin,$end,1);
				break;
		}
		$temp_arr = array();

		$c_add    = 0;
		$c_edit   = 0;
		foreach ($temp_ls as $row) {

			// Menentukan Awal dan Akhir waktu tiap sub periode penilaian
			// echo '<br>nm_adj_'.$row->NIK.'_'.$x .' : ';
			$after_value = $this->input->post('nm_adj_'.$row->NIK);
			$total = $this->input->post('hd_total_'.$row->NIK);
	
			$check = $this->adjust_model->count_result($row->NIK,$period->BeginDate,$period->EndDate);
			if ($check == 0) { // INSERT
				$this->adjust_model->add_result($row->NIK,$period->BeginDate,$period->EndDate,$after_value,$total);
				$c_add++;
			} else { // UPDATE
				$old = $this->adjust_model->get_result_row($row->NIK,$period->BeginDate,$period->EndDate)->after_value;
				if ($after_value!=$old) {
					$this->adjust_model->edit_result($row->NIK,$period->BeginDate,$period->EndDate,$after_value,$total);
					$c_edit++;
				}
			}
			$this->report_model->lock_achv($row->NIK,12,$begin,$end);
			unset($after_value);

			/**
			 * SEND EMAIL TO NIK 
			 */
			$sub_name      = $this->account_model->get_User_byNIK($row->NIK)->Fullname;
			$sub_email   = $this->account_model->get_User_byNIK($row->NIK)->Email;
			
			$config['smtp_host'] ="10.10.55.10";
			$config['smtp_user'] ="pms@chr.kompasgramedia.com";
			$config['smtp_pass'] ="Abc123"; 
			$config['mailtype']  ='html';
			$config['priority']  =1;
			$config['protocol']  ='smtp';
			$this->email->initialize($config);
			$this->email->from('pms@chr.kompasgramedia.com', '[PMS Online] DO NOT REPLY THIS EMAIL!');
			$this->email->to($sub_email);
			$this->email->subject('[PMS Online] PK Adjustment Information');
			$this->email->message("<h2>Information</h2>
				".$sub_name." for PK Adjustment has been adjust, 
				please check your PMS Online.<br> 
				<br>
				If you're not ".$sub_name.",please ignore this email. <br>Thank you,<br><br>PMS Online");
			
			if($this->email->send()){
				$array = array(
					'notif_text' => 'Email has been sent',
					'notif_type' => 'alert-success'
				);
			}else{
				$array = array(
					'notif_text' => 'Email has not been sent',
					'notif_type' => 'alert-danger'
				);
			}
			$this->session->set_userdata( $array );

			
		}

		if ($c_add > 0 && $c_edit > 0 ) {			
			$this->session->set_userdata('notif_type','alert-info');
			$this->session->set_userdata('notif_text', $c_add . ' data(s) Added & '. $c_edit .' data(s) Edited' );
		} elseif ($c_add >0 && $c_edit == 0) {
			$this->session->set_userdata('notif_type','alert-info');
			$this->session->set_userdata('notif_text', $c_add . ' data(s) Added' );
		} elseif ($c_add == 0 && $c_edit > 0 ) {
			$this->session->set_userdata('notif_type','alert-info');
			$this->session->set_userdata('notif_text',  $c_edit .' data(s) Edited' );
			
		} else {
			$this->session->set_userdata('notif_type','alert-error');
			$this->session->set_userdata('notif_text', 'No data change' );

		}
		///////////////////////////////////////////
		// Menampilkan hasil notif penyimpanan //
		///////////////////////////////////////////
		redirect('hr/pk_adjust');
	}

	public function check_notif()
	{
		
		if ($this->session->userdata('notif_text')) {
			$data['notif_type'] = $this->session->userdata('notif_type');
			$data['notif_text'] = $this->session->userdata('notif_text');
			$this->session->unset_userdata('notif_type');
			$this->session->unset_userdata('notif_text');
			$this->load->view('template/notif_view', $data, FALSE);
			# code...
		}
	}
}

/* End of file pk_adjustment.php */
/* Location: ./application/controllers/hr/pk_adjust.php */