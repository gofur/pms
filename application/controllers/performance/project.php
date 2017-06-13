<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project extends Controller {
	function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('loginFlag'))
		{
			redirect('account/login');
		}
		$this->load->model('project_model');
		$this->load->model('report_model');

		$this->load->model('om_model');
		$this->load->model('account_model');
		$this->load->model('general_model');


	}
	public function index()
	{
		$period   = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$nik      = $this->session->userdata('NIK');
		$user_dtl = $this->account_model->get_User_byNIK($nik);

		$data['period']   = $period;
		$data['user_dtl'] = $user_dtl;
		$data['post_ls_SAP']      = $this->account_model->get_Holder_list($nik,1,$period->BeginDate,$period->EndDate);
		$data['post_ls_nonSAP']   = $this->account_model->get_Holder_list($nik,0,$period->BeginDate,$period->EndDate);
		$data['assign_ls_SAP']    = $this->account_model->get_Assignment_list($nik,1,$period->BeginDate,$period->EndDate);
		$data['assign_ls_nonSAP'] = $this->account_model->get_Assignment_list($nik,0,$period->BeginDate,$period->EndDate);
		$this->load->view('performance/project/header', $data);
	}

	public function show_achv()
	{
		$org_id  = $this->input->post('org_id');
		$scope   = $this->input->post('scope');
		$post    = $this->input->post('post_id');
		$period = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		if ((trim($org_id) == '' || is_null($org_id)) ) {
			list($is_sap,$post_id) = explode('.', $post);
			$c_post  = $this->om_model->count_post_byID($is_sap, $post_id, $begin,$end);
			$org_id = $this->om_model->get_post_row($is_sap, $post_id, $begin,$end)->OrganizationID;
		}
		switch ($scope) {
			case 1:
				$temp_ls = $this->om_model->get_hold_tree_byOrg_list($is_sap, $org_id,$begin,$end,1);
				break;
			
			default:
				$temp_ls = $this->om_model->get_hold_byOrg_list($is_sap, $org_id,$begin,$end,1);
				break;
		}

		$nik_ls = array();
		foreach ($temp_ls as $krow) {
			$nik_ls[] = $row->NIK;
		}
		$result = $this->report_model->get_project_list($nik_ls,$period->BeginDate,$period->EndDate);

	}

}

/* End of file project.php */
/* Location: ./application/controllers/performance/project.php */