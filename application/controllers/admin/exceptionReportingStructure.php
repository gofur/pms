<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ExceptionReportingStructure extends Controller {

	function __construct(){
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
		$this->load->model('account_model');
		$this->load->model('org_model');
		$this->load->model('om_model');
	}
	function index(){
		$data['notif_text']=$this->session->flashdata('notif_text');
		$data['notif_type']=$this->session->flashdata('notif_type');

		switch ($this->session->userdata('roleID')) {
			case 5:
			case 6:
			case 9:
				$pers_admin = $this->session->userdata('PersAdmin');
				break;

			default:
				$pers_admin = '';
				break;
		}
		$dataVariable=$this->om_model->get_execReportStruct_list($pers_admin);

		$i=0;
		$temp = array();
		foreach ($dataVariable as $row) {
			$chief = $this->org_model->get_Position_row($row->ChiefPositionID,$row->Chief_isSAP);
			$post = $this->org_model->get_Position_row($row->PositionID,$row->isSAP);

			if (count($chief)  && count($post)  ) {
				$temp[$i]['ExceptionReportingStructureID']=$row->ExceptionReportingStructureID;
				$temp[$i]['ChiefPositionID']=$row->ChiefPositionID;
				$temp[$i]['Chief_isSAP']=$row->Chief_isSAP;
				$temp[$i]['PositionID']=$row->PositionID;
				$temp[$i]['isSAP']=$row->isSAP;
				$temp[$i]['BeginDate']=$row->BeginDate;
				$temp[$i]['EndDate']=$row->EndDate;
				$temp[$i]['PositionNameChief']=$chief->PositionName;
				$temp[$i]['SubOrdinatePositionName']=$post->PositionName;
				$i++;
			}
		}
		$data['rowsChiefNonSAP']=$temp;
		$this->load->view('template/top_1_view');
		$this->load->view('admin/exceptionReportingStructure_view',$data);
		$this->load->view('template/bottom_1_view');
		$this->load->view('admin/exceptionReportingStructure_view_js');
	}

	function add(){
		$data['process']='admin/exceptionReportingStructure/add_process';
		$data['title']='Add Exception Reporting Structure';
		$this->load->view('template/top_1_view');
		$this->load->view('admin/exceptionReportingStructure_form',$data);
		$this->load->view('template/bottom_1_view');
		$this->load->view('admin/exceptionReportingStructure_form_js');
	}
	function add_process(){
		$start_date   = $this->input->post('TxtStartDate');
		$end_date     = $this->input->post('TxtEndDate');
		$position_sup = $this->input->post('slc_position');

		for ($i=1; $i <=7 ; $i++) {
			$org_sub_temp = $this->input->post('slc_org_sub_'.$i);
			if ($org_sub_temp!=''){
				$org_sub = $org_sub_temp;
			}
		}

		$post_rows = $this->om_model->get_post_byOrg_list(1,$org_sub,date('Y-m-d'),date('Y-m-d'));
		$count=0;

		switch ($this->session->userdata('roleID'))
		{
			case 5: // HR Manager
			case 6: // HR Admin
				$pers_admin = $this->session->userdata('PersAdmin');
				break;
			default:
				$pers_admin = '';
				break;
		}

		foreach ($post_rows as $row) {
			$position_sub = $this->input->post('chk_post_'.$row->PositionID);
			if ($position_sub==1){

				if(!$this->om_model->check_excReportStruct($row->PositionID,1,$start_date,$end_date)){
					$this->om_model->add_excReportStruct($position_sup,1,$row->PositionID,1,$start_date,$end_date,$pers_admin);
					$count +=1;
				}
			}
		}

		$this->session->set_flashdata('notif_text','Success Add '.$count.' Exception Reporting Structure');
		$this->session->set_flashdata('notif_type','alert-success');

		redirect('admin/exceptionReportingStructure');
	}
	function delimit($id){
		$data['process']='admin/exceptionReportingStructure/delimit_process';
		$data['title']='Delimit Exception Reporting Structure';

		$data['old'] = $this->general_model->get_ExceptionReportingStructure_row($id);

		if($id!=0)
		{
			$old_position_sup=$this->general_model->get_ExceptionReportingStructure_row($id)->ChiefPositionID;
			$old_position_sub=$this->general_model->get_ExceptionReportingStructure_row($id)->PositionID;

			if($old_position_sup >= '50000000')
			{
				$data['org_sup'] = $this->org_model->get_Position_row($old_position_sup, 1)->OrganizationName;
				$data['position_sup'] = $this->org_model->get_Position_row($old_position_sup, 1)->PositionName;
				$data['org_sub'] = $this->org_model->get_Position_row($old_position_sub, 1)->OrganizationName;
				$data['position_sub'] = $this->org_model->get_Position_row($old_position_sub, 1)->PositionName;
				$old=$this->general_model->get_ExceptionReportingStructure_row($id);
			}
			else
			{
				$data['org_sup'] = $this->org_model->get_Position_row($old_position_sup, 0)->OrganizationName;
				$data['position_sup'] = $this->org_model->get_Position_row($old_position_sup, 0)->PositionName;
				$data['org_sub'] = $this->org_model->get_Position_row($old_position_sub, 0)->OrganizationName;
				$data['position_sub'] = $this->org_model->get_Position_row($old_position_sub, 0)->PositionName;
				$old=$this->general_model->get_ExceptionReportingStructure_row($id);
			}
			$data['old']=$old;
		}

		$this->load->view('template/top_1_view');
		$this->load->view('admin/exceptionReportingStructure_form_delimit',$data);
		$this->load->view('template/bottom_1_view');
		$this->load->view('admin/exceptionReportingStructure_form_js');

	}

	function delimit_process(){
		$id = $this->input->post('hdn_id');
		$end_date = $this->input->post('TxtEndDate');
		$this->general_model->edit_ExceptionReportingStructure($id,$end_date);
		$this->session->set_flashdata('notif_text','Success Delimit Exception Reporting Structure');
		$this->session->set_flashdata('notif_type','alert-success');
		redirect('admin/exceptionReportingStructure');

	}

	public function remove($id)
	{
		$this->general_model->remove_ExceptionReportingStructure($id);
		$this->session->set_flashdata('notif_text','Success Remove Exception Reporting Structure');
		$this->session->set_flashdata('notif_type','alert-success');
		// redirect('admin/exceptionReportingStructure');
	}

	function ajax_unit(){
		switch ($this->session->userdata('roleID'))
		{
			case 1: // Super Admin
			case 3: // CHR Admin
				$rows = $this->om_model->get_org_byParent_list(TRUE,0,date('Y-m-d'),date('Y-m-d'));

				break;
			case 5: // HR Manger
			case 6: // HR Admin
			case 9: // SMO
				$pers_admin = $this->session->userdata('PersAdmin');
				$org_ls     = $this->om_model->get_hr_org_list(TRUE,$pers_admin,date('Y-m-d'),date('Y-m-d'));
				$org_id     = array();
				foreach ($org_ls as $row) {
					$org_id[] = $row->OrganizationID;
				}
				$rows = $this->om_model->get_org_byID_list(TRUE,$org_id,date('Y-m-d'),date('Y-m-d'));

				break;
		}

		$output = '<option value="">Select One</option>';
		foreach ($rows as $row) {
			$output .= '<option value="'.$row->OrganizationID.'">'.$row->OrganizationName.'</option>';
		}
		echo $output;
	}

	function ajax_sub_unit(){

		$org_parent = $this->input->post('org_parent');
		$element = $this->input->post('element');

		$output = '<option value="">Select One</option>';

		$rows = $this->om_model->get_org_byParent_list(TRUE,$org_parent,date('Y-m-d'),date('Y-m-d'));
		foreach ($rows as $row) {
			$output .= '<option value="'.$row->OrganizationID.'">'.$row->OrganizationName.'</option>';
		}

		echo $output;

	}

	function ajax_position(){
		$org_id = $this->input->post('org_id');

		$output = '<option value="">Select One</option>';
		$rows = $this->org_model->get_Position_list($org_id,1,date('Y-m-d'),date('Y-m-d'));
		foreach ($rows as $row) {
			$output .= '<option value="'.$row->PositionID.'">'.$row->PositionID.' - '.$row->PositionName.'</option>';
		}
		echo $output;
	}

	function ajax_position_sub(){
		$org_id = $this->input->post('org_id');

		$output = '<option value="">Select One</option>';
		$rows = $this->org_model->get_Position_list($org_id,1,date('Y-m-d'),date('Y-m-d'));
		foreach ($rows as $row) {
			$data['id'] = $row->PositionID;
			$data['name'] = $row->PositionID .' - '. $row->PositionName;
			$this->load->view('admin/exceptionReportingStructure_form_position', $data, FALSE);
		}

	}

}
