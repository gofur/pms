<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('account_model');
	}
	function index(){
		if($this->session->userdata('loginFlag')){
			redirect('home');
		}else{
			redirect('account/login');
		}
	}
	function login(){
		if($this->session->userdata('loginFlag')){
			redirect('home');
		}
		$data['notif'] = $this->session->userdata('notif');
		$this->session->unset_userdata('notif');
		$this->load->view('login_view',$data);
	}
	function login_process(){
		$this->load->model('general_model');
		$this->load->model('Org_model');
		$Periode = $this->general_model->get_ActivePeriode();

		$NIK = $this->input->post('txtNIK');
		$password = $this->input->post('txtPass');
		
		$db = $this->account_model->get_User_byNIK($NIK);
		$begin = date('Y-m-d',mktime(0,0,0,1,1,$Periode->Tahun));
		$end   = date('Y-m-t',mktime(0,0,0,12,1,$Periode->Tahun));
		$post_SAP = count($this->account_model->get_Holder_list($NIK,1,$begin,$end));
		$post_non = count($this->account_model->get_Holder_list($NIK,0,$begin,$end));
		if ($db->RoleID != 1 && $db->RoleID != 5 && $db->RoleID != 6) {
			if($post_SAP==1 && $post_non ==0){
				$def_post = $this->account_model->get_Holder_byNIK($NIK,1);
				$this->session->set_userdata('Holder','1.'.$def_post->HolderID);
				$this->load->model('org_model');
				
				$subordinate = $this->org_model->get_directSubordinate_list(1, $def_post->PositionID,$begin,$end);

				$c_sub = count($subordinate);
				// if ($def_post->Chief==2) {
				if ($c_sub>0) {
					switch ($def_post->PositionGroup) {
						case 40: //direktur
							$this->account_model->edit_user_role($db->UserID,4);
							break;
						default: // GM, Manager dan superitendent
							$this->account_model->edit_user_role($db->UserID,7);
							break;
					}
				}

			}elseif ($post_SAP==0 && $post_non==1) {
				$def_post = $this->account_model->get_Holder_byNIK($NIK,0);
				$this->session->set_userdata('Holder','0.'.$def_post->HolderID);
				//$subordinate = $this->org_model->get_directSubordinate_list(1,$def_post->PositionID,$begin,$end;
$subordinate = $this->org_model->get_directSubordinate_list(1,$def_post->PositionID,$begin,$end);
				$c_sub = count($subordinate);
				// if ($def_post->Chief==2) {
				if ($c_sub>0) {
					switch ($def_post->PositionGroup) {
						case 40: //direktur
							$this->account_model->edit_user_role($db->UserID,4);
							break;
						default: // GM, Manager dan superitendent
							$this->account_model->edit_user_role($db->UserID,7);
							break;
					}
				}
				
			}elseif($post_SAP >= 1 OR $post_non >= 1){

				$post_list_sap = $this->account_model->get_Holder_list($NIK,1);
				$post_list_non = $this->account_model->get_Holder_list($NIK,0);
				$role=8;
				$this->load->model('org_model');
				foreach ($post_list_sap as $row) {
          // $subordinate = $this->org_model->get_directSubordinate_list(1,$row->PositionID,$begin,$end;
					$subordinate = $this->org_model->get_directSubordinate_list(1,$row->PositionID,$begin,$end);
					$c_sub = count($subordinate);

					if ($c_sub>0 AND $row->Chief == 2 AND $row->PositionGroup==40) {
						$role = 4;
					}elseif ($c_sub>0) {
						$role = 7;
					}elseif ($row->Chief == 2) {
						$role = 7;
					}

				}
				foreach ($post_list_non as $row) {
					if ($row->Chief==2 AND $row->PositionGroup==40) {
						$role = 4;
					}elseif ($row->Chief==2) {
						$role = 7;
					}
				}
				$this->account_model->edit_user_role($db->UserID,$role);
			}
			$db = $this->account_model->get_User_byNIK($NIK);

			if($post_SAP == 1 XOR $post_non == 1){
				$subordinate = count($this->Org_model->get_directSubordinate_list($db->isSAP,$def_post->PositionID,$begin,$end));
				if ($db->RoleID == 8 && $subordinate>0){
					$this->account_model->edit_User_role($db->UserID,7);
				}
			}
		}
		// ------------------------------------------------------------------------------------------
		// Khusus GM CMS
		if ($NIK == '000344') {
			$db = $this->account_model->get_User_byNIK($NIK);
			$this->account_model->edit_User_role($db->UserID,4);
			
		}
		// ------------------------------------------------------------------------------------------
		
		if (md5($password)==$db->Password){
			$db = $this->account_model->get_User_byNIK($NIK);

			$newdata = array(
				'username'      => $db->Fullname,
				'userID'        => $db->UserID,
				'roleID'        => $db->RoleID,
				'PersAdmin'     => $db->PersAdmin,
				'NIK'           => $NIK,
				'isSAP'         => $db->isSAP,
				'esg'           => $db->esg,
				'loginFlag'     => TRUE,
				'active_period' => $this->general_model->get_ActivePeriode()->PeriodePMID,
			);
			$this->session->set_userdata($newdata);

			redirect('home');
		}else{
			$this->session->set_userdata('notif',2);
			redirect('account/login');
		}
	}
	
	function logout(){
		$this->session->sess_destroy();
		redirect('account/login');
	}

	function change_pass(){
		if(!$this->session->userdata('loginFlag')){
			redirect('account/login');
		}
		$data['notif_type'] = $this->session->userdata('notif_type');
		$data['notif'] = $this->session->userdata('notif');
		$data['title']='Change Password';
		
		$this->load->view('template/top_1_view');
		$this->load->view('change_pass_form',$data);
		$this->load->view('template/bottom_1_view');
		$this->load->view('change_pass_form_js');
	}

	function change_pass_process(){

		//$oldPassword=$this->input->post('TxtOldPassword');
		$newPassword=$this->input->post('TxtNewPassword');
		$old = $this->account_model->get_User_row($this->session->userdata('userID'))->Password;
		
		/*if(md5($oldPassword)!=$old){
			$this->session->set_userdata('notif_type','alert-error');
			$this->session->set_userdata('notif','Old Password not Valid');
		}else{*/
			$this->account_model->change_password($this->session->userdata('NIK'),$newPassword);
			$this->session->set_userdata('notif_type','alert-success');
			$this->session->set_userdata('notif','Password has been change');
			
		//}
			redirect('account/change_pass');

	}

	public function forgot()
	{
		if($this->session->userdata('loginFlag')){
			redirect('home');
		}
		$data['title']='Forgot Password';
		$data['notif_type'] = $this->session->userdata('notif_type');
		$data['notif'] = $this->session->userdata('notif');
		// $this->load->view('template/top_1_view');
		$this->load->view('forgot_form',$data);
		// $this->load->view('template/bottom_1_view');
	}

	public function forgot_process()
	{
		$nik   = $this->input->post('txtNIK');
		$email = strtoupper($this->input->post('txtEmail'));

		$user = $this->account_model->get_User_nik($nik);

		if (count($user)) {
			if (strtoupper($user->Email) == $email) {
				$this->account_model->change_password($nik,'abc123');
				$this->session->set_userdata('notif',1);
				redirect('account/');
				
			} else {
				$this->session->set_userdata('notif',3);
				redirect('account/forgot');

			}
		} else {
			$this->session->set_userdata('notif',2);
			redirect('account/forgot');

		}

	}

}
