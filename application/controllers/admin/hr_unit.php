<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class hr_unit extends Controller {
	function __construct(){
		parent::__construct();
		if(!$this->session->userdata('loginFlag')){
			redirect('account/login');
		}
		
		$this->load->model('general_model');
		$this->load->model('account_model');
	}
	public function index()
	{
		$hr_man = $this->account_model->get_User_byRole_list(5);
		$hr_off = $this->account_model->get_User_byRole_list(6);

		$data['hr_man'] = $hr_man;
		$data['hr_off'] = $hr_off;
		
		$this->load->view('admin/hr_unit/main_view',$data);
		
	}

	public function add($mode = 'off')
	{
		$hidden = array(
			'hdn_mode' => $mode,
		);
		$data['hidden'] = $hidden;
		$this->load->view('admin/hr_unit/add_form', $data, FALSE);
	}

	public function add_process()
	{
		$mode = $this->input->post('hdn_mode');
		$nik  = $this->input->post('txt_nik');
		switch ($mode) {
			case 'man':
				$role = 5;
				break;
			case 'off':
				$role = 6;
				break;
		}
		$pers = $this->account_model->get_User_byNIK($nik);
		$this->account_model->edit_user_role($pers->UserID,$role);
		redirect('admin/hr_unit');
	}

	public function remove_process($nik='')
	{
		$pers = $this->account_model->get_User_byNIK($nik);
		switch ($pers->esg) {
			case '40':
				// Direktur
				$role = 4;
				break;
			case '37':
			case '35':
			case '34':
			case '29':
				// Managerial
				$role = 7;
				break;
			
			default:
				// Profesional
				$role = 8;
				break;
		}
		$this->account_model->edit_user_role($pers->UserID,$role);
		redirect('admin/hr_unit');
				
	}



}

/* End of file hr_unit.php */
/* Location: ./application/controllers/admin/hr_unit.php */