<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Project extends Controller 
{
	function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('loginFlag'))
		{
			redirect('account/login');
		}
		$this->load->model('project_model');
		$this->load->model('general_model');
		$this->load->model('account_model');
		$admin = $this->account_model->get_Holder_byNIK($this->session->userdata('NIK'),$this->session->userdata('isSAP'));
	}

	function index()
	{
		$link['add_project'] 		= 'misc/project/add_project/';
		$link['edit_project'] 	= 'misc/project/edit_project/';
		$link['delete_project'] = 'misc/project/delete_project/';
		$link['member_list'] 		= 'misc/project/member_list/';
		$data['link'] 					= $link;
		switch ($this->session->userdata('roleID')) {
			case 1: //Super Admin
			case 2: //CEO
			case 4: 

				$view = 'misc/project_view';
				break;
			case 3:

				$view = 'misc/project_view';
				break;
			case 6:
			case 7: //Managerial
				$data['project_list'] = $this->project_model->get_project_unit_list();
				$view = 'misc/project_view';

				break;
			default:
				$data['project_list'] = $this->project_model->get_project_assignment_list($this->session->userdata('NIK'));
				$view = 'misc/project_assignment_view';

				break;
		}
		
		$this->load->view('template/top_1_view');
		$this->load->view($view,$data);
		$this->load->view('template/bottom_1_view');
	}
	function add_project()
	{
		$this->load->model('org_model');
		$period = $this->general_model->get_ActivePeriode();
		$data['title']    = 'Add Project';
		$data['action']   = 'misc/project/add_project_process';
		$data['org_list'] = $this->org_model->get_Organization_list(50002147,1,$period->BeginDate,$period->EndDate);
		switch ($this->session->userdata('roleID')) {
			case 1: //Super Admin
			case 2: //CEO
			case 4: 
				$data['option'] = array(0=>'Corporate',1=>'Unit');
				break;
			case 3: //CHR Admin
				$data['option'] = array(0=>'Corporate');
				break;
			case 6:
			case 7: //Managerial
				$data['option'] = array(1=>'Unit');
				break;

			default:
				$data['option'] = array();
				break;
		}
		$this->load->view('template/top_popup_1_view');
		$this->load->view('misc/project_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('misc/project_form_js');

	}

	function add_project_process()
	{
		$project_name = $this->input->post('txt_project_name');
		$description 	= $this->input->post('txt_description');
		$scope 				= $this->input->post('slc_scope');
		$unit 				= $this->input->post('slc_unit');
		$begin_date 	= $this->input->post('txt_begin_date');
		$end_date 		= $this->input->post('txt_end_date');
		if ($scope == 1) {
			//Unit
			$this->project_model->add_project($project_name,$description,$scope,$begin_date,$end_date,$unit,1);
		} else {
			$this->project_model->add_project($project_name,$description,$scope,$begin_date,$end_date);
		}
		
		$data['notif_text'] = 'Success Add Project';
		$data['notif_type']	= 'alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}

	function edit_project($project_id)
	{
		$data['title'] = 'Edit Project';
		$data['action'] = 'misc/project/edit_project_process';
		$data['old']	= $this->project_model->get_project_row($project_id);
		switch ($this->session->userdata('roleID')) {
			case 1:
				$data['option'] = array(0=>'Corporate',1=>'Unit');
				break;
			case 3:
				$data['option'] = array(0=>'Corporate');

				break;
			case 6:
				$data['option'] = array(1=>'Unit');
				break;

			default:
				$data['option'] = array();
				break;
		}
		$this->load->view('template/top_popup_1_view');
		$this->load->view('misc/project_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('misc/project_form_js');

	}

	function edit_project_process()
	{
		$project_id		= $this->input->post('hdn_project_id');
		$project_name = $this->input->post('txt_project_name');
		$description 	= $this->input->post('txt_description');
		$scope 				= $this->input->post('slc_scope');
		$begin_date 	= $this->input->post('txt_begin_date');
		$end_date 		= $this->input->post('txt_end_date');

		$this->project_model->edit_project($project_id,$project_name,$description,$scope,$begin_date,$end_date);
		$data['notif_text'] = 'Success Edit Project';
		$data['notif_type']	= 'alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}

	function delete_project($project_id)
	{
		$this->project_model->delete_project($project_id);
		$data['notif_text'] = 'Success Delete Project';
		$data['notif_type']	= 'alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}

	function member_list($project_id)
	{
		$header = $this->project_model->get_project_row($project_id);
		if ($header->IsActive == 0)
		{
			redirect('misc/project');
		}
		$data['header'] = $header;
		$data['member_list'] = $this->project_model->get_member_list($project_id,1);
		$link['project_list'] 	= 'misc/project/';
		$link['add_member'] 		= 'misc/project/add_member/'.$project_id;
		$link['edit_member'] 		= 'misc/project/edit_member/';
		$link['delete_member'] 	= 'misc/project/delete_member/';
		$data['link'] 					= $link;
		;
		if($this->session->userdata('roleID') == 1 OR $this->session->userdata('roleID') == 3 OR $this->session->userdata('roleID') == 6)
		{
			$view = 'misc/project_member_view';
		}
		else
		{
			$view = 'misc/project_assignment_member_view';
		}
		$this->load->view('template/top_1_view');
		$this->load->view($view,$data);
		$this->load->view('template/bottom_1_view');
	}

	function add_member($project_id)
	{
		$data['title'] = 'Add Member';
		$data['project_id'] = $project_id;
		$data['action'] = 'misc/project/add_member_process';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('misc/project_member_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('misc/project_add_member_form_js');

	}

	function add_member_process()
	{
		$project_id = $this->input->post('hdn_project_id');
		$nik = $this->input->post('txt_nik');
		$role = $this->input->post('txt_role');
		$target = $this->input->post('txt_target');
		$header = $this->project_model->get_project_row($project_id);


		if($header->Scope==0)
		{
			$this->project_model->add_member($project_id,$nik,$role,$target);	
			$data['notif_text'] = 'Success Add Member';
			$data['notif_type']	= 'alert-success';
		}
		else if($header->Scope==1)
		{
			
			$person = $this->account_model->get_Holder_byNIK($nik,$this->session->userdata('isSAP'));
			if (count($person))
			{
				$person_rootID = root_org_id($person->OrganizationID,$this->session->userdata('isSAP'));
			}
			
			if (count($person) && $this->rootID == $person_rootID)
			{
				$this->project_model->add_member($project_id,$nik,$role,$target);	
				$data['notif_text'] = 'Success Add Member';
				$data['notif_type']	= 'alert-success';
			}
			else
			{
				$data['notif_text'] = 'Cannot add Member from other Unit';
				$data['notif_type']	= 'alert-error';
			}
			
		}

		
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
	function edit_member($member_id)
	{
		$data['title'] = 'Add Member';
		$data['action'] = 'misc/project/edit_member_process';
		$data['old'] = $this->project_model->get_member_row($member_id);
		$this->load->view('template/top_popup_1_view');
		$this->load->view('misc/project_member_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('misc/project_edit_member_form_js');

	}
	function edit_member_process()
	{
		$member_id = $this->input->post('hdn_member_id');
		$role = $this->input->post('txt_role');
		$target = $this->input->post('txt_target');
		$point = $this->input->post('slc_point');
		$this->project_model->edit_member($member_id,$nik,$role,$target,$point);

		$data['notif_text'] = 'Success Edit Member';
		$data['notif_type']	= 'alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
	function delete_member($member_id)
	{
		$this->project_model->delete_member($member_id);
		$data['notif_text'] = 'Success Delete Member';
		$data['notif_type']	= 'alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}

}
