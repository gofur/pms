<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pass_reset extends Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('account_model');
	}
	public function index()
	{

	
		$count = $this->session->userdata('count');
		$notif = $this->session->userdata('notif');
		if ($notif != '') {
			$data['notif'] = $notif;
			$data['count'] = $count;
		} 

		$nik     = $this->session->userdata('NIK');
		$admin   = $this->account_model->get_User_byNIK($nik);
		$user_ls = $this->account_model->get_User_byHr_list($admin->PersAdmin);
		$admin->PersAdmin;
		$data['user_ls'] = $user_ls;
		$data['process'] = 'hr/pass_reset/process';

		$this->load->view('hr/pass_reset/list_view', $data, FALSE);
	}

	public function process()
	{
		$nik_ls = $this->input->post('chk_nik');

		$count  = 0 ;
		foreach ($nik_ls as $key => $user_nik) {
			echo $user_nik.'<br>';
			$user    = $this->account_model->get_User_byNIK($user_nik);
			// $words   = explode(" ", $user->Fullname);
			// $newpass = $user->NIK;
			// foreach ($words as $word) {
			// 	$newpass .= $word[0];
			// }
			$newpass = 'abc123';
			$this->account_model->change_password($user_nik,$newpass);
			$count++;
		}

		if ($count == 0) {
			$notif = 'template/notif/pass_reset_failed';
		} elseif ($count > 0) {
			$notif = 'template/notif/pass_reset_ok';

		} 
		$array = array(
			'count' => $count,
			'notif' => $notif
		);
		
		$this->session->set_userdata( $array );
		redirect('hr/pass_reset');
	}

}

/* End of file pass_reset.php */
/* Location: ./application/controllers/hr/pass_reset.php */