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
		$this->load->model('general_model');
		$this->load->model('project_model');
		$this->load->model('account_model');
	}

	function index()
	{
		$start   = $this->input->post('dt_start');
		$end     = $this->input->post('dt_end');
		$role_id = $this->session->userdata('roleID');
		$nik     = $this->session->userdata('NIK');

		if ($start == '' && $end == '') {
			$period = $this->general_model->get_Period_row($this->session->userdata('active_period'));
			$start  = $period->BeginDate;
			$end    = $period->EndDate;
		}
		$data['start']       = substr($start, 0,10);
		$data['end']         = substr($end, 0,10);
		$data['link_add']    = 'misc/project/add_project/';
		$data['link_detail'] = 'misc/project/detail/';
		$data['link_edit']   = 'misc/project/edit_project/';
		$data['link_remove'] = 'misc/project/delete_project/';
		switch ($role_id) {
			case 1: //super admin
				$data['list'] = $this->project_model->get_list($nik,2, $start, $end,2);
				$view = 'misc/project/admin_view';

				break;
			case 3: //CHR Admin
				$data['list'] = $this->project_model->get_list($nik,2, $start, $end,0);
				$view = 'misc/project/admin_view';

				break;
			case 6: //HR Unit
			case 5: //HR Manager
				$is_sap     = $this->session->userdata('isSAP');
				$pers_admin = $this->session->userdata('PersAdmin');
				$data['list'] = $this->project_model->get_list($nik,2, $start, $end,1,$pers_admin,$is_sap);
				$view = 'misc/project/admin_view';

				break;
			case 4: //Director
			case 7: //Manager
				$this->load->model('org_model');
				$nik_ls  = array($nik);
				$post_ls = $this->account_model->get_Holder_list($nik,1,$start,$end);
				foreach ($post_ls as $row_1) {
					$post_id = $row_1->PositionID;
					$sub_ls  = $this->org_model->get_directSubordinate_list(1,$post_id,$start,$end);
					foreach ($sub_ls as $row_2) {
						$nik_ls[] = $row_2->NIK;
					}
				}

				$data['list'] = $this->project_model->get_sub_list($nik_ls,$start,$end);
				$view = 'misc/project/manager_view';
				break;
			default:
			
				$data['list'] = $this->project_model->get_assign_list($nik,$start,$end);
				$view = 'misc/project/view';
				break;
		}

		$this->load->view($view,$data);
	}

	public function detail($project_id = 0)
	{
		$project   = $this->project_model->get_row($project_id);
		$member_ls = $this->project_model->get_member_list($project_id, 2, 2);
		$role_id   = $this->session->userdata('roleID');
		$name_ls   = array();
		foreach ($member_ls as $row) {
			$name_ls[$row->member_id] = $this->account_model->get_User_byNIK($row->nik)->Fullname;
		}
		$data['project']     = $project;
		$data['member_ls']   = $member_ls;
		$data['name_ls']     = $name_ls;
		$data['link_add']    = 'misc/project/add_member/'.$project_id;
		$data['link_edit']   = 'misc/project/edit_member/';
		$data['link_remove'] = 'misc/project/delete_member/';

		switch ($role_id) {
			case 1: //super admin
			case 3: //CHR Admin
			case 5: //HR Manager
			case 6: //HR Unit
			case 4: //Director
			case 7: //Manager
				$view = 'misc/project/detail_admin';

				break;
			default:
				$nik = $this->session->userdata('NIK');
				if ($this->project_model->check_member($project_id,$nik,1)) {
					$view = 'misc/project/detail_admin';
						
				} else {
					$view = 'misc/project/detail';
					
				}
				break;
		}
		$this->load->view($view,$data);
	}

	public function add_project()
	{
		$period = $this->general_model->get_ActivePeriode();
		$role_id = $this->session->userdata('roleID');
		switch ($role_id) {
			case 1: //super admin
				$data['scope_list'] = array(0=>'Corporate',1=>'Unit');
				$data['scope'] = '';
				break;
			case 3: //CHR Admin
				$data['scope_list'] = array(0=>'Corporate');
				$data['scope'] = 0;

				break;
			case 5: //HR Manager
			case 6: //HR Unit
			case 4: //Director
			case 7: //Manager
				$data['scope_list'] = array(1=>'Unit');
				$data['scope'] = 1;

				break;
			default:
				
				break;
		}
		$data['title']   = '';
		$data['doc_num'] = '';
		$data['desc']    = '';
		$data['unit']    = '';
		$data['start']   = substr($period->BeginDate, 0,10);
		$data['end']     = substr($period->EndDate, 0,10);

		$data['action'] = 'misc/project/add_project_process';
		$data['hidden'] = array();
		$this->load->view('misc/project/add_form',$data);

	}

	public function add_project_process()
	{
		$start   = $this->input->post('dt_start');
		$end     = $this->input->post('dt_end');
		$title   = $this->input->post('txt_title');
		$doc_num = $this->input->post('txt_doc');
		$desc    = $this->input->post('txt_desc');
		$scope   = $this->input->post('slc_scope');
		$leader  = $this->input->post('txt_nik');

		if ($scope == 1){
			$pers_admin = $this->session->userdata('PersAdmin');
			$is_sap     = $this->session->userdata('isSAP');
			$this->project_model->add_project($title,$doc_num, $desc, $start, $end,$leader,'Project Leader', $scope, $pers_admin, $is_sap);
		} else {
			$this->project_model->add_project($title,$doc_num, $desc, $start, $end,$leader,'Project Leader', $scope);
		}

		redirect('misc/project');
	}

	public function edit_project($project_id=0)
	{
		$old = $this->project_model->get_row($project_id);
		$data['title']   = $old->project_name;
		$data['doc_num'] = $old->doc_num;
		$data['desc']    = $old->description;
		$role_id = $this->session->userdata('roleID');
		switch ($role_id) {
			case 1: //super admin
				$data['scope_list'] = array(0=>'Corporate',1=>'Unit');
				$data['scope'] = $old->scope;
				break;
			case 3: //CHR Admin
				$data['scope_list'] = array(0=>'Corporate');
				$data['scope'] = 0;

				break;
			case 5: //HR Unit
			case 6: //HR Unit
			case 4: //Director
			case 7: //Manager
				$data['scope_list'] = array(1=>'Unit');
				$data['scope'] = 1;

				break;
			default:
				
				break;
		}
		$data['start']  = $old->begin_date;
		$data['end']    = $old->end_date;
		$data['action'] = 'misc/project/edit_project_process';
		$data['hidden'] = array('project_id' => $project_id);
		$this->load->view('misc/project/edit_form',$data);
		
	}

	public function edit_project_process()
	{
		$project_id = $this->input->post('project_id');
		$start   = $this->input->post('dt_start');
		$end     = $this->input->post('dt_end');
		$doc_num = $this->input->post('txt_doc');
		$title   = $this->input->post('txt_title');
		$desc    = $this->input->post('txt_desc');
		$scope   = $this->input->post('slc_scope');
		$this->project_model->edit_project($project_id,$title,$doc_num, $desc, $start, $end);
		redirect('misc/project');
	}


	public function delete_project($project_id=0)
	{
		$this->project_model->delete_project($project_id);
		redirect('misc/project');
	}

	public function add_member($project_id=0)
	{
		$data['action'] = 'misc/project/add_member_process';
		$data['hidden'] = array(
			'project_id' => $project_id
		);
		$data['nik']  = '';
		$data['kpi']  = '';
		$data['role'] = '';
		$data['result'] = 0.00;

		$this->load->view('misc/project/member_form', $data, FALSE);
	}

	public function add_member_process()
	{
		$project_id = $this->input->post('project_id');
		$this->form_validation->set_rules('txt_role', 'Role Name', 'trim|required|min_length[3]|max_length[100]|xss_clean');
		// $this->form_validation->set_rules('txt_kpi', 'KPI', 'trim|min_length[10]|max_length[255]|xss_clean');
		// $this->form_validation->set_rules('txt_role', 'Role Name', 'trim|required|min_length[3]|max_length[100]|xss_clean');
		if ($this->form_validation->run()) {
			$nik  = $this->input->post('txt_nik');
			$kpi  = $this->input->post('txt_kpi');
			$role = $this->input->post('txt_role');
			$result = $this->input->post('nm_result');
			$member_id = $this->project_model->add_member($project_id,$nik,$kpi,$role,0);
			$this->project_model->edit_member_result($member_id,$result);

			redirect('misc/project/detail/'.$project_id);
		} else {
			redirect('misc/project/add_member/'.$project_id);
		}
	}

	public function edit_member($member_id=0)
	{
		$data['action'] = 'misc/project/edit_member_process';
		$data['hidden'] = array('member_id' => $member_id);
		$old = $this->project_model->get_member_row($member_id);
		$data['nik']  = $old->nik;
		$data['kpi']  = $old->kpi;
		$data['role'] = $old->role_name;
		if ($old->result=='') {
			$data['result'] = 0;

		} else {
			$data['result'] = $old->result;

		}

		$this->load->view('misc/project/member_form', $data, FALSE);
	}

	public function edit_member_process()
	{
		$member_id = $this->input->post('member_id');
		$this->form_validation->set_rules('txt_nik', 'NIK', 'trim|required|min_length[6]|max_length[6]|xss_clean');
		$this->form_validation->set_rules('txt_kpi', 'KPI', 'trim|min_length[10]|max_length[255]|xss_clean');

		$this->form_validation->set_rules('txt_role', 'Role Name', 'trim|required|min_length[3]|max_length[100]|xss_clean');
		$old = $this->project_model->get_member_row($member_id);
		$this->form_validation->set_rules('nm_result', 'Result', 'trim|required|xss_clean');
		if ($this->form_validation->run()) {
			$nik    = $this->input->post('txt_nik');
			$kpi    = $this->input->post('txt_kpi');
			$role   = $this->input->post('txt_role');
			$result = $this->input->post('nm_result');
			$this->project_model->edit_member($member_id,$kpi,$role);
			$this->project_model->edit_member_result($member_id,$result);
			redirect('misc/project/detail/'.$old->project_id);

		} else {
			redirect('misc/project/edit_member/'.$member_id);
		}
	}

	public function delete_member($member_id=0)
	{
		$old = $this->project_model->get_member_row($member_id);
		$this->project_model->delete_member($member_id);
		redirect('misc/project/detail/'.$old->project_id);
	}

	public function nik_to_name()
	{
		$this->load->model('account_model');
		$nik = $this->input->post('nik');
		if (strlen($nik)==6) {
			$user = $this->account_model->get_User_byNIK($nik);
			if (count($user)==1) {
				echo $user->Fullname;
			} else {
				echo '';
			}
		}
	}

}
