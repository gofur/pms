<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pk_unit_report extends Controller {

	function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('loginFlag'))
		{
			redirect('account/login');
		}
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
		$role_id    = $this->session->userdata('roleID');

		$period = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$nik        = $this->session->userdata('NIK');
		$user_dtl   = $this->account_model->get_User_byNIK($nik);

		$data['period']   = $period;
		$data['user_dtl'] = $user_dtl;
		$data['legend'] = $this->general_model->get_Scale_list(2,$period->BeginDate,$period->EndDate);

		switch ($role_id) {
			case 4:
			case 7:
				$data['post_ls_SAP']      = $this->account_model->get_Holder_list($nik,1,$period->BeginDate,$period->EndDate);
				$data['post_ls_nonSAP']   = $this->account_model->get_Holder_list($nik,0,$period->BeginDate,$period->EndDate);
				$data['assign_ls_SAP']    = $this->account_model->get_Assignment_list($nik,1,$period->BeginDate,$period->EndDate);
				$data['assign_ls_nonSAP'] = $this->account_model->get_Assignment_list($nik,0,$period->BeginDate,$period->EndDate);

				$this->load->view('report/pk_unit/manager_header', $data);
				break;
			case 5:
			case 6:
				$pers_admin = $this->session->userdata('PersAdmin');

				$this->load->view('report/pk_unit/hr_header', $data);
				break;

		}
	}

	public function show_root_org()
	{
		$role_id    = $this->session->userdata('roleID');
		$is_sap     = $this->session->userdata('isSAP');
		$pers_admin = $this->session->userdata('PersAdmin');
		$period = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		if ($period->Tahun < date('Y')) {
			$month = 12;
		} else {
			$month   = date('m');
		}
		
		$begin      = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, substr($period->BeginDate, 0,4)));
		$end        = date('Y-m-t H:i:s', mktime(23, 59, 59, $month, 1, substr($period->BeginDate, 0,4))); 
		switch ($role_id) {
			case 4:
			case 7:
				$param = $this->input->post('post_id');
				list($is_sap,$post_id) = explode('.', $param);
				$c_post = $this->om_model->count_post_byID($is_sap, $post_id, $begin,$end);
				if ($c_post) {
					$org_id = $this->om_model->get_post_row($is_sap, $post_id, $begin,$end)->OrganizationID;
				}
				$org_ls = $this->om_model->get_org_byParent_list($is_sap,$org_id,$begin,$end);
				break;
			case 5:
			case 6:
				$org_ls     = $this->om_model->get_hr_org_list($is_sap,$pers_admin,$begin,$end);
				
				break;
		}
		$org_opt    = array(''=>'');
		foreach ($org_ls as $row) {
			$org_opt[$row->OrganizationID] = $row->OrganizationName;
		}
		$data['org_ls'] = $org_opt;
		$data['num'] 		= 0;

		$this->load->view('hr/pk_adjust/org_opt', $data);
	}

	public function show_achv()
	{
		$this->load->model('adjust_model');
		$role_id = $this->session->userdata('roleID');
		$is_sap  = $this->session->userdata('isSAP');
		$org_id  = $this->input->post('org_id');
		$post    = $this->input->post('post_id');
		$scope   = $this->input->post('scope');

		$period = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		if ($period->Tahun < date('Y')) {
			$month = 12;
		} else {
			$month   = date('m');
		}
		
		$begin  = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, substr($period->BeginDate, 0,4)));
		$end    = date('Y-m-t H:i:s', mktime(23, 59, 59, $month, 1, substr($period->BeginDate, 0,4)));



		if ((trim($org_id) == '' || is_null($org_id)) ) {
			list($is_sap,$post_id) = explode('.', $post);
			$c_post  = $this->om_model->count_post_byID($is_sap, $post_id, $begin,$end);
			$org_id = $this->om_model->get_post_row($is_sap, $post_id, $begin,$end)->OrganizationID;
		}

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


			// Menentukan Awal dan Akhir waktu tiap sub periode penilaian
			$total = 0;
			
			$ba_ytd = '-';
			foreach ($aspect as $row_2) {
				if ($row_2->aspect_id == 1) { // Untuk Aspect Bisnis / Performa

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
								
								$ba_cur               = $achv->Cur_TPC;
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
						if ($total_dur == 0) {
							$ba_ytd = 0;
	
						} else {
							$ba_ytd = $total_ytd / $total_dur;
							
						}
						$total += $ba_ytd * $row_2->percent /100 ;
						$temp_arr[$i][$row_2->aspect_id] = round($ba_ytd * $row_2->percent /100,2); 
					}	else {
						$temp_arr[$i][$row_2->aspect_id] = 0;
					}	
				} else {
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
			
			$c_project = $this->project_model->count_member_result($row->NIK,$begin,$end);
			
			switch ($c_project) {
				case 0:
					$r_project = 0;
					break;
				case 1:
					$r_project = $this->project_model->sum_member_result($row->NIK,$begin,$end);
					if ($r_project > 0.3) {
						$r_project = 0.3;
					}
					break;
				case 2:
					$r_project = $this->project_model->sum_member_result($row->NIK,$begin,$end);
					if ($r_project > 0.6) {
						$r_project = 0.6;
					}
					break;
				default:
					$r_project = $this->project_model->sum_member_result($row->NIK,$begin,$end);
					if ($r_project > 0.6) {
						$r_project = 0.6;
					}
					break;
			}
			$grand_total = round(($total + $r_project),2); ;
			$c_adj_val = $this->adjust_model->count_result($row->NIK,$begin,$end);
			if ($c_adj_val > 0) {
				if($this->adjust_model->get_result_row($row->NIK,$begin,$end)->after_value == NULL) {
					$adj_val  = '-';
					$cat      = $this->report_model->get_category_row($grand_total,$begin,$end);
				} else {
					$adj_val  = round($this->adjust_model->get_result_row($row->NIK,$begin,$end)->after_value,2);
					$cat      = $this->report_model->get_category_row($adj_val,$begin,$end);
					
				}
				
			} else {
				$adj_val  = '-';
				$cat      = $this->report_model->get_category_row($grand_total,$begin,$end);

			}

			$temp_arr[$i]['project']    = round($r_project,2); 
			$temp_arr[$i]['total']      = $grand_total;
			$temp_arr[$i]['adjustment'] = $adj_val;
			$temp_arr[$i]['category']   = $cat->cat_en_short;
			$temp_arr[$i]['color']      = $cat->Colour;
			$temp_arr[$i]['notes_link'] = 'report/pk_unit_report/show_notes/'.$row->NIK.'/'.$begin.'/'.$end;
			
			$c_adj = $this->adjust_model->count_result($row->NIK,$begin,$end);
			if ($c_adj) {
				$this->adjust_model->edit_before($row->NIK,$begin,$end,$grand_total);
			} else {
				$this->adjust_model->add_before($row->NIK,$begin,$end,$grand_total);
			}
				
			$i++;
			unset($c_rkk);
		}
		$data['temp_list']  = $temp_arr;
		$data['export_xls'] = 'report/pk_unit_report/export_xls/'.$org_id.'/'.$scope;

		$this->load->view('report/pk_unit/achv_list', $data, FALSE);
	}

	public function show_bellcurve($mode='before')
	{
		$this->load->model('adjust_model');
		$role_id = $this->session->userdata('roleID');
		$is_sap  = $this->session->userdata('isSAP');
		$org_id  = $this->input->post('org_id');
		$post    = $this->input->post('post_id');
		$scope   = $this->input->post('scope');
		$period = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		if ($period->Tahun < date('Y')) {
			$month = 12;
		} else {
			$month   = date('m');
		}
		
		
		$begin  = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, substr($period->BeginDate, 0,4)));
		$end    = date('Y-m-t H:i:s', mktime(23, 59, 59, $month, 1, substr($period->EndDate, 0,4)));
		if ((trim($org_id) == '' || is_null($org_id)) ) {
			list($is_sap,$post_id) = explode('.', $post);
			$c_post  = $this->om_model->count_post_byID($is_sap, $post_id, $begin,$end);
			$org_id = $this->om_model->get_post_row($is_sap, $post_id, $begin,$end)->OrganizationID;
		}
		switch ($scope) {
			case 1:
				$temp_ls = $this->om_model->get_hold_tree_byOrg_list($is_sap, $org_id,$begin,$end);
				break;
			
			default:
				$temp_ls = $this->om_model->get_hold_byOrg_list($is_sap, $org_id,$begin,$end);
				break;
		}
		$nik_ls = array();
		foreach ($temp_ls as $row) {
			$nik_ls[] = $row->NIK;
		}

		switch ($mode) {
			case 'after':
				$data['mode']  	= $mode;
				$data['title']  = 'After Adjustment';
				$data['color']  = 'scheme02';
				break;
			
			default:
				$data['mode'] 	= 'before';
				$data['title']  = 'Before Adjustment';
				$data['color']  = 'scheme01';
				# code...
				break;
		}

		$data['bc_ls'] = $this->report_model->get_bellcurve_list($nik_ls,$begin,$end);
		$this->load->view('report/pk_unit/bellcurve_view',$data, FALSE);
	}

	public function export_xls($org_id,$scope)
	{
		$this->load->library('excel');
		$this->load->model('adjust_model');

		$role_id  = $this->session->userdata('roleID');
		$is_sap   = $this->session->userdata('isSAP');

		$period = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		if ($period->Tahun < date('Y')) {
			$month = 12;
		} else {
			$month   = date('m');
		}
		
		$begin    = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, substr($period->BeginDate, 0,4)));
		$end      = date('Y-m-t H:i:s', mktime(23, 59, 59, $month, 1, substr($period->BeginDate, 0,4)));
		$aspect   = $this->aspect_model->get_setting_list($is_sap,$org_id,$begin,$end);
		$c_aspect = count($aspect);
		foreach ($aspect as $row) {
			$frequency = $row->frequency;  
		}
		$month_ls = explode(';', $frequency);
		$sub_period = count($month_ls);
		
		switch ($scope) {
			case 1:
				$temp_ls = $this->om_model->get_hold_tree_byOrg_list($is_sap, $org_id,$begin,$end);
				break;
			
			default:
				$temp_ls = $this->om_model->get_hold_byOrg_list($is_sap, $org_id,$begin,$end);
				break;
		}

		$columns = $this->create_col('ZZ');
		$this->excel->setActiveSheetIndex(0); // mengaktifkan sheet yang akan digunakan 

		$this->excel->getActiveSheet()->setTitle('Report Unit'); // mengganti nama sheet yang aktif
		$this->excel->getActiveSheet()->setCellValue($columns[0].'1', 'NIK');
		$this->excel->getActiveSheet()->setCellValue($columns[1].'1', 'Name');
		$this->excel->getActiveSheet()->setCellValue($columns[2].'1', 'Organization');
		$this->excel->getActiveSheet()->setCellValue($columns[3].'1', 'Position');
		$c_col = 4;
		$i = 0;

		$flag = 0;
		foreach ($aspect as $row) {
			if ($flag < 2) {
				$this->excel->getActiveSheet()->setCellValue($columns[$c_col].'1', $row->label. ' ('.$row->percent.'%)');
				$c_col++;
				$flag++;
			}
		}
		$this->excel->getActiveSheet()->setCellValue($columns[$c_col].'1', 'Project');
		$c_col++;
		$this->excel->getActiveSheet()->setCellValue($columns[$c_col].'1', 'Total');
		$c_col++;
		$this->excel->getActiveSheet()->setCellValue($columns[$c_col].'1', 'After Adjustment');
		$c_col++;
		$this->excel->getActiveSheet()->setCellValue($columns[$c_col].'1', 'Category');
		$c_col++;


		$c_row = 2;
		foreach ($temp_ls as $row) {
			$total = 0;
			$this->excel->getActiveSheet()->setCellValue($columns[0].$c_row, $row->NIK);
			$this->excel->getActiveSheet()->setCellValue($columns[1].$c_row, $row->Fullname);
			$this->excel->getActiveSheet()->setCellValue($columns[2].$c_row, $row->org_name);
			$this->excel->getActiveSheet()->setCellValue($columns[3].$c_row, $row->post_name);
			$c_col = 4;

			// Menentukan Awal dan Akhir waktu tiap sub periode penilaian
			
			$ba_ytd = 0;
			$flag   = 0;
			foreach ($aspect as $row_2) {
				if ($row_2->aspect_id == 1) { // Untuk Aspect Bisnis / Performa

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


						$total += $ba_ytd * $row_2->percent /100 ;
						$res_asp = round($ba_ytd * $row_2->percent /100,2); 
					} else {
						$res_asp = '-';
					}
					$this->excel->getActiveSheet()->setCellValue($columns[$c_col].$c_row, $res_asp);
					$c_col++; 		
				}	else { // Untuk Aspect Behavior
					$month_year = '12'.substr($period->EndDate,0,4);
					$c_achv = $this->achv_bhv_model->count_header($month_year,$row->NIK,$row_2->aspect_setting_id,TRUE,array(3,4,5));
		
					
					if ($c_achv  ) { // Jika Ada Achv
						$achv = $this->achv_bhv_model->get_header_byAspect_row($month_year,$row->NIK,$row_2->aspect_setting_id,TRUE,array(3,4,5));
						$res_asp = round(($achv->total_achv * $row_2->percent /100),2) ;
						$total += ($achv->total_achv * $row_2->percent /100) ;
						$this->excel->getActiveSheet()->setCellValue($columns[$c_col].$c_row, $res_asp);
						$c_col++;

					} else {
						if (!isset($res_asp) OR $res_asp == 0 ) {
							$res_asp = 0;
						}
						$total += 0 ;
					}


				}
			}
			$c_col = 6;
			//////////////////
			// Project  //
			//////////////////
			
			$c_project = $this->project_model->count_member_result($row->NIK,$begin,$end);
			
			switch ($c_project) {
				case 0:
					$r_project = 0;
					break;
				case 1:
					$r_project = $this->project_model->sum_member_result($row->NIK,$begin,$end);
					if ($r_project > 0.3) {
						$r_project = 0.3;
					}
					break;
				case 2:
					$r_project = $this->project_model->sum_member_result($row->NIK,$begin,$end);
					if ($r_project > 0.6) {
						$r_project = 0.6;
					}
					break;
				default:
					$r_project = $this->project_model->sum_member_result($row->NIK,$begin,$end);
					if ($r_project > 0.6) {
						$r_project = 0.6;
					}
					break;
			}
			$total += $r_project ;

			////////////////////
			// Adjustment //
			////////////////////
			$total = round($total,2);
			$c_adj_val = $this->adjust_model->count_result($row->NIK,$begin,$end);
			if ($c_adj_val > 0) {
				if($this->adjust_model->get_result_row($row->NIK,$begin,$end)->after_value == NULL) {
					$adj_val  = '-';
					$cat      = $this->report_model->get_category_row($total,$begin,$end);
				} else {
					$adj_val  = round($this->adjust_model->get_result_row($row->NIK,$begin,$end)->after_value,2);
					$cat      = $this->report_model->get_category_row($adj_val,$begin,$end);
					
				}
				
			} else {
				$adj_val  = '-';
				$cat      = $this->report_model->get_category_row($total,$begin,$end);

			}
			if (count($cat)) {
				$category = $cat->cat_en_short;
			
			} else {
				$category = '-';
			}
			$this->excel->getActiveSheet()->setCellValue($columns[$c_col].$c_row, $r_project);
			$c_col++;
			$this->excel->getActiveSheet()->setCellValue($columns[$c_col].$c_row, round($total,2));
			$c_col++;
			$this->excel->getActiveSheet()->setCellValue($columns[$c_col].$c_row, round($adj_val),2);
			$c_col++;
			$this->excel->getActiveSheet()->setCellValue($columns[$c_col].$c_row, $category);
			$c_col++;
			
			$c_row++;
		}
		$filename = 'PMS - Report Unit - '.date('ymd his').'.xls';
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"'); 
		header('Cache-Control: max-age=0'); 
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

		// $filename = 'PMS - Report Unit - '.date('ymd his').'.xlsx';
		// header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		// header('Content-Disposition: attachment;filename="'.$filename.'"');
		// header('Cache-Control: max-age=0');
		// $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');

		$objWriter->save('php://output');

		$this->excel->disconnectWorksheets();
	}
	private function create_col($end_column, $first_letters = '')
	{
		$columns 	= array();
		$length 	= strlen($end_column);
		$letters 	= range('A', 'Z');

		// Iterate over 26 letters.
		foreach ($letters as $letter) {
				// Paste the $first_letters before the next.
				$column = $first_letters . $letter;

				// Add the column to the final array.
				$columns[] = $column;

				// If it was the end column that was added, return the columns.
				if ($column == $end_column)
						return $columns;
		}

		// Add the column children.
		foreach ($columns as $column) {
				// Don't itterate if the $end_column was already set in a previous itteration.
				// Stop iterating if you've reached the maximum character length.
				if (!in_array($end_column, $columns) && strlen($column) < $length) {
						$new_columns = $this->create_col($end_column, $column);
						// Merge the new columns which were created with the final columns array.
						$columns = array_merge($columns, $new_columns);
				}
		}

		return $columns;
	}	

	public function show_notes($nik='',$begin='',$end='')
	{
		$this->load->model('adjust_model');

		$c_result = $this->adjust_model->count_result($nik,$begin,$end);

		if ($c_result) {
			$result = $this->adjust_model->get_result_row($nik,$begin,$end);
			$data['notes'] 		= $result->notes;
		} else {
			$data['notes']    = '';

		}

		$this->load->view('report/pk_unit/notes_view', $data, FALSE);
	}
}

/* End of file pk_unit_report.php */
/* Location: ./application/controllers/report/pk_unit_report.php */
