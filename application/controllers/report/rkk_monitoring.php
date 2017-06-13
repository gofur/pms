<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rkk_monitoring extends Controller {

	function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('loginFlag'))
		{
			redirect('account/login');
		}
		$this->load->model('rkk_model3');
		$this->load->model('idp_model');
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

				$this->load->view('report/rkk_monitoring/manager_header', $data);
				break;
			case 5:
			case 6:
				$pers_admin = $this->session->userdata('PersAdmin');

				$this->load->view('report/rkk_monitoring/hr_header', $data);
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

	public function show_list()
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

		$submit = 0;
		$all    = 0;
		foreach ($temp_ls as $row) {
			$sub_rkk = $this->rkk_model3->get_rkk_holder_last($row->NIK,$row->PositionID,1,$period->BeginDate,$period->EndDate);
			if (count($sub_rkk)) {
				if ($sub_rkk->statusFlag == 0) {
					$rkk_stat = '<span class="label">Draft</span>';

				} else if ($sub_rkk->statusFlag == 1) {
					$rkk_stat = '<span class="label">Draft</span>';

				} else if ($sub_rkk->statusFlag == 2) {
					$rkk_stat = '<span class="label label-important">Rejected</span>';


				} else if ($sub_rkk->statusFlag == 3) {
					$rkk_stat = '<span class="label label-success">Agreed</span>';


				} else if ($sub_rkk->statusFlag == 4) {
					$rkk_stat = '<span class="label label-success">Lock</span>';


				} else if ($sub_rkk->statusFlag == 5) {
					$rkk_stat = '<span class="label label-success">Final</span>';

				}

				$sub_idp = $this->idp_model->get_Header_byRKKID_row($sub_rkk->RKKID,$period->BeginDate,$period->EndDate);
				if (count($sub_idp)) {
					if ($sub_idp->StatusFlag == 0) {
						$idp_stat = '<span class="label">Draft</span>';
						# code...
					} else if ($sub_idp->StatusFlag == 1) {
						$idp_stat = '<span class="label ">Draft</span>';

					} else if ($sub_idp->StatusFlag == 2) {
						$idp_stat = '<span class="label label-important">Rejected</span>';

					} else if ($sub_idp->StatusFlag == 3) {
						$idp_stat = '<span class="label label-success">Agreed</span>';

					} else if ($sub_idp->StatusFlag == 4) {
						$idp_stat = '<span class="label label-success">Lock</span>';

					} else if ($sub_idp->StatusFlag == 5) {
						$idp_stat = '<span class="label label-success">Final</span>';

					}

					if (($sub_rkk->statusFlag == 0 OR $sub_rkk->statusFlag == 2 ) && $sub_idp->StatusFlag == 1 ) {
						$stat = '<span class="label label-info">Not Assign</span>';

					} elseif ($sub_rkk->statusFlag == 1 && $sub_idp->StatusFlag == 1) {
						$stat = '<span class="label label-warning">Assigned</span>';
					} else {
						$stat = '';
					}

					if (($sub_rkk->statusFlag == 1 && $sub_idp->StatusFlag == 1) || ($sub_rkk->statusFlag == 3 && $sub_idp->StatusFlag == 3) || ($sub_rkk->statusFlag == 4 && $sub_idp->StatusFlag == 4) || ($sub_rkk->statusFlag == 5 && $sub_idp->StatusFlag == 5) ) {
						$submit+=1;
					} 
				}
				$kpi_num = $this->rkk_model3->count_kpi($sub_rkk->RKKID,$begin,$end);
			} else {
				$rkk_stat = '<span class="label">Not Created</span>';
				$idp_stat = '<span class="label">Not Created</span>';
				$stat = '';
				$kpi_num = 0;
			}
			$all+=1;
			$temp_arr[$i] = array(
				'nik'       => $row->NIK,
				'fullname'  => $row->Fullname,
				'org_name'  => $row->org_name,
				'post_name' => $row->post_name,
				'kpi_num'   => $kpi_num,
				'rkk'				=> $rkk_stat,
				'idp'				=> $idp_stat,
				'status'    => $stat
			);
			$i++;

		}
		$data['temp_list']  = $temp_arr;
		$data['export_xls'] = 'report/rkk_monitoring/export_xls/'.$org_id.'/'.$scope;
		$data['all']        = $all;
		$data['submit']     = $submit;
		$data['not_sub']    = $all - $submit;
		$data['perc']       = round($submit/$all*100,2);
		$this->load->view('report/rkk_monitoring/rkk_list', $data, FALSE);
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
		$this->excel->createSheet();
		$this->excel->setActiveSheetIndex(1); // mengaktifkan sheet yang akan digunakan 

		$this->excel->getActiveSheet()->setTitle('Detail'); // mengganti nama sheet yang aktif
		$this->excel->getActiveSheet()->setCellValue($columns[0].'1', 'NIK');
		$this->excel->getActiveSheet()->setCellValue($columns[1].'1', 'Name');
		$this->excel->getActiveSheet()->setCellValue($columns[2].'1', 'Organization');
		$this->excel->getActiveSheet()->setCellValue($columns[3].'1', 'Position');
		$this->excel->getActiveSheet()->setCellValue($columns[4].'1', 'KPI Num');
		$this->excel->getActiveSheet()->setCellValue($columns[5].'1', 'RKK');
		$this->excel->getActiveSheet()->setCellValue($columns[6].'1', 'IDP');
		$this->excel->getActiveSheet()->setCellValue($columns[7].'1', 'Status');

		$i = 0;

		$flag = 0;
		

		$submit = 0;
		$all    = 0;
		$c_row = 2;
		foreach ($temp_ls as $row) {

			$sub_rkk = $this->rkk_model3->get_rkk_holder_last($row->NIK,$row->PositionID,1,$begin,$end);
			if (count($sub_rkk)) {
				if ($sub_rkk->statusFlag == 0) {
					$rkk_stat = 'Draft';

				} else if ($sub_rkk->statusFlag == 1) {
					$rkk_stat = 'Draft';

				} else if ($sub_rkk->statusFlag == 2) {
					$rkk_stat = 'Rejected';


				} else if ($sub_rkk->statusFlag == 3) {
					$rkk_stat = 'Agreed';


				} else if ($sub_rkk->statusFlag == 4) {
					$rkk_stat = 'Lock';


				} else if ($sub_rkk->statusFlag == 5) {
					$rkk_stat = 'Final';

				}

				$sub_idp = $this->idp_model->get_Header_byRKKID_row($sub_rkk->RKKID,$begin,$end);
				if (count($sub_idp)) {
					if ($sub_idp->StatusFlag == 0) {
						$idp_stat = 'Draft';

					} else if ($sub_idp->StatusFlag == 1) {
						$idp_stat = 'Draft';

					} else if ($sub_idp->StatusFlag == 2) {
						$idp_stat = 'Rejected';

					} else if ($sub_idp->StatusFlag == 3) {
						$idp_stat = 'Agreed';

					} else if ($sub_idp->StatusFlag == 4) {
						$idp_stat = 'Lock';

					} else if ($sub_idp->StatusFlag == 5) {
						$idp_stat = 'Final';
					}

					if (($sub_rkk->statusFlag == 0 OR $sub_rkk->statusFlag == 2 ) && $sub_idp->StatusFlag == 1 ) {
						$stat = 'Not Assign';

					} elseif ($sub_rkk->statusFlag == 1 && $sub_idp->StatusFlag == 1) {
						$stat = 'Assigned';
					} else {
						$stat = '';
					}

					if (($sub_rkk->statusFlag == 1 && $sub_idp->StatusFlag == 1) || ($sub_rkk->statusFlag == 3 && $sub_idp->StatusFlag == 3) || ($sub_rkk->statusFlag == 4 && $sub_idp->StatusFlag == 4) || ($sub_rkk->statusFlag == 5 && $sub_idp->StatusFlag == 5) ) {
						$submit+=1;
					} 
				}
				$kpi_num = $this->rkk_model3->count_kpi($sub_rkk->RKKID,$begin,$end);

			} else {
				$rkk_stat = 'Not Created';
				$idp_stat = 'Not Created';
				$stat = '';
				$kpi_num = 0;

			}
			$all+=1;
			$this->excel->getActiveSheet()->setCellValue($columns[0].$c_row, $row->NIK);
			$this->excel->getActiveSheet()->setCellValue($columns[1].$c_row, $row->Fullname);
			$this->excel->getActiveSheet()->setCellValue($columns[2].$c_row, $row->org_name);
			$this->excel->getActiveSheet()->setCellValue($columns[3].$c_row, $row->post_name);
			$this->excel->getActiveSheet()->setCellValue($columns[4].$c_row, $kpi_num);
			$this->excel->getActiveSheet()->setCellValue($columns[5].$c_row, $rkk_stat);
			$this->excel->getActiveSheet()->setCellValue($columns[6].$c_row, $idp_stat);
			$this->excel->getActiveSheet()->setCellValue($columns[7].$c_row, $stat);
			
			$c_row++;
		}
		$this->excel->createSheet();
		$this->excel->setActiveSheetIndex(0); // mengaktifkan sheet yang akan digunakan 
		$this->excel->getActiveSheet()->setTitle('Rekap'); // mengganti nama sheet yang aktif
		$this->excel->getActiveSheet()->setCellValue($columns[0].'1', 'All');
		$this->excel->getActiveSheet()->setCellValue($columns[1].'1', 'Submited');
		$this->excel->getActiveSheet()->setCellValue($columns[2].'1', 'Not Yet Submited');
		$this->excel->getActiveSheet()->setCellValue($columns[3].'1', '% Submited');
		$this->excel->getActiveSheet()->setCellValue($columns[0].'2', $all);
		$this->excel->getActiveSheet()->setCellValue($columns[1].'2', $submit);
		$this->excel->getActiveSheet()->setCellValue($columns[2].'2', $all-$submit);
		$this->excel->getActiveSheet()->setCellValue($columns[3].'2', round(($submit/$all*100),2));

		$filename = 'PMS - RKK Monitoring - '.date('ymd his').'.xls';
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

	
}

/* End of file pk_unit_report.php */
/* Location: ./application/controllers/report/pk_unit_report.php */
