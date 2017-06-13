<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends Controller {
	var $index;
	var $depth=1;
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
		$this->load->model('account_model');
		$this->load->model('org_model');
	}

	function index(){
		$data['notif_text']=$this->session->flashdata('notif_text');
		$data['notif_type']=$this->session->flashdata('notif_type');
		switch ($this->session->userdata('roleID')) {
			case 1:
				$data['userList'] =$this->account_model->get_User_list('','');
				
				break;
			case 3:
				$data['userList'] =$this->account_model->get_User_list('','');
				break;
			case 6:
				$this->index=0;
				$adminOrgID = $this->account_model->get_Holder_byNIK($this->session->userdata('NIK'),0)->OrganizationID;

				$rootID =  root_org_id($adminOrgID,$this->session->userdata('isSAP'),$result='');
				$temp_list = array();
				$OrgId_List = $this->org_tree($rootID,$temp_list);
	
				$data['userList'] =$this->account_model->get_UserUnit_list($OrgId_List, '','');
				break;
			default:
				# code...
				break;
		}

		$this->load->view('template/top_1_view');
		$this->load->view('admin/user_view',$data);
		$this->load->view('template/bottom_1_view');
	}
	function detail($id){
		$data['notif_text']=$this->session->flashdata('notif_text');
		$data['notif_type']=$this->session->flashdata('notif_type');
		$head= $this->account_model->get_User_row($id);
		$data['head']=$head;
		$data['holderList'] = $this->account_model->get_Holder_list($head->NIK,0,date('Y-m-d'),date('Y-m-d'));
		$this->load->view('template/top_1_view');
		$this->load->view('admin/userDetail_view',$data);
		$this->load->view('template/bottom_1_view');
	}
	function add(){
		$this->index=0;
		$adminRole = $this->session->userdata('roleID');
		$isSAP = $this->session->userdata('isSAP');
		switch ($adminRole) {
			case 1:
				$rootID = 0;
				break;
			case 3:
				$rootID = 0;
				break;
			case 6:
				$admin = $this->account_model->get_Holder_byNIK($this->session->userdata('NIK'),$isSAP);
				$rootID = root_org_id($admin->OrganizationID,$isSAP);
				$data['orgRoot']=$this->org_model->get_Organization_row($rootID,$isSAP);
				break;
			default:
				# code...
				break;
		}
		$temp_list = array();
		$data['orgList']=$this->build_tree($isSAP,$rootID,$temp_list);
		$data['roleList']=$this->account_model->get_Role_list(date('Y-m-d'),date('Y-m-d'),$adminRole);
		$data['process']='admin/user/add_process';
		$data['title']='Add User';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('admin/user_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('admin/user_form_js');

	}
	function ajax_position($id,$current=''){
		$data['postList'] = $this->org_model->get_position_available($id,$current,0,date('Y-m-d'),date('Y-m-d'));
		$data['current']=$current;
		$this->load->view('admin/user_form_position',$data);
	}
	function add_process(){
		$NIK = $this->input->post('TxtNIK');
		$BeginDate = $this->input->post('TxtStartDate');
		$EndDate = $this->input->post('TxtEndDate');
		$Fullname = $this->input->post('TxtFullname');
		$Email = $this->input->post('TxtEmail');
		$Mobile = $this->input->post('TxtMobile');
		$statusFlag = $this->input->post('SlcStatus');
		$RoleID = $this->input->post('SlcRole');
		$Org = $this->input->post('SlcOrg');
		$Post = $this->input->post('SlcPost');
		if(!$this->account_model->check_NIK_isUsed($NIK)){
			$this->account_model->add_User($NIK,$Fullname,'',$Email,$Mobile,$RoleID,$statusFlag,0,$BeginDate,$EndDate);	
		}
		$this->org_model->add_Holder(0,$NIK,$Post,$BeginDate,$EndDate);
		$data['notif_text']='Success Add User and Holder';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
	function add_Holder($NIK){
		$data['NIK']=$NIK;
		$data['process']='admin/user/add_Holder_process';
		$data['title']='Add Holder';
		$this->index=0;
		$adminRole = $this->session->userdata('roleID');
		$isSAP = $this->session->userdata('isSAP');
		switch ($adminRole) {
			case 1:
				$rootID = 0;
				break;
			case 3:
				$rootID = 0;
				break;
			case 6:
				//$admin = $this->account_model->get_User_row($this->session->userdata('userID'),$isSAP);
				$admin = $this->account_model->get_Holder_byNIK($this->session->userdata('NIK'),$this->session->userdata('isSAP'));
				$rootID = root_org_id($admin->OrganizationID,$isSAP);
				$data['orgRoot']=$this->org_model->get_Organization_row($rootID,$isSAP);
				break;
			default:
				# code...
				break;
		}
		$temp_list = array();
		$data['orgList']=$this->build_tree($isSAP,$rootID,$temp_list);
		$this->load->view('template/top_popup_1_view');
		$this->load->view('admin/userHolder_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('admin/userHolder_form_js');
	}
	function add_Holder_process(){
		$NIK = $this->input->post('TxtNIK');
		$BeginDate = $this->input->post('TxtStartDate');
		$EndDate = $this->input->post('TxtEndDate');
		$Post = $this->input->post('SlcPost');
		$isMain = $this->input->post('chkMain');
		$this->org_model->add_Holder(0,$NIK,$Post,$BeginDate,$EndDate,$isMain);
		$data['notif_text']='Success Add Holder';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
	function edit($UserID){
		$adminRole = $this->session->userdata('roleID');
		$isSAP = $this->session->userdata('isSAP');
		switch ($adminRole) {
			case 1:
				$rootID = 0;
				break;
			case 3:
				$rootID = 0;
				break;
			case 6:
				//$admin = $this->account_model->get_User_row($this->session->userdata('userID'),$isSAP);
				$admin = $this->account_model->get_Holder_byNIK($this->session->userdata('NIK'),$isSAP);
				$rootID = root_org_id($admin->OrganizationID,$isSAP);
				$data['orgRoot']=$this->org_model->get_Organization_row($rootID,$isSAP);
				break;
			default:
				# code...
				break;
		}
		$data['old'] = $this->account_model->get_User_row($UserID,0);
		$temp_list = array();

		$data['orgList']=$this->build_tree($isSAP,$rootID,$temp_list);
		$data['roleList']=$this->account_model->get_Role_list(date('Y-m-d'),date('Y-m-d'));
		$data['process']='admin/user/edit_process';
		$data['title']='Edit User';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('admin/user_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('admin/user_form_js');
	}
	function edit_process(){
		$UserID= $this->input->post('TxtUserID');
		$EndDate = $this->input->post('TxtEndDate');
		$Fullname = $this->input->post('TxtFullname');
		$Email = $this->input->post('TxtEmail');
		$Mobile = $this->input->post('TxtMobile');
		$statusFlag = $this->input->post('SlcStatus');
		$RoleID = $this->input->post('SlcRole');
		$this->account_model->edit_User($UserID,$Fullname,'',$Email,$Mobile,$RoleID,$EndDate);
		$data['notif_text']='Success Edit User';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
	function edit_holder($HolderID){
		$data['old']=$this->account_model->get_Holder_row($HolderID,0);
		$data['process']='admin/user/edit_holder_process';
		$data['title']='Edit Holder';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('admin/userHolder_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('admin/userHolder_form_js');
	}
	function edit_holder_process(){
		$HolderID = $this->input->post('TxtHolderID');
		$EndDate = $this->input->post('TxtEndDate');
		$isMain = $this->input->post('chkMain');
		$this->org_model->edit_Holder(0,$HolderID,$EndDate,$isMain);
		$data['notif_text']='Success Edit Holder';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
	function import_from_portal()
	{
		$user_list = $this->account_model->get_temp_user_list();
		$counter_1 = 0;
		$counter_2 = 0;

		foreach ($user_list as $row) 
		{
			$user = $this->account_model->get_User_byNIK($row->nik);
			if(count($user))
			{
				$this->account_model->edit_User($user->UserID,str_replace("'", " ", $row->Nama),'',$row->email,$row->mobile,8,'9999-12-31',$row->password);
				$this->account_model->delete_temp_user($row->nik);
				$counter_2++;
			}
			else
			{
				$this->account_model->add_User($row->nik,str_replace("'", " ", $row->Nama) ,'',$row->email,$row->mobile,8,1,1,date('Y-m-d'),'9999-12-31',$row->password);
				$this->account_model->delete_temp_user($row->nik);
				$counter_1++;

			}
		}
		$counter_3= count($user_list);
		$counter_4 = $counter_1 + $counter_2;
		$this->session->set_flashdata('notif_text', $counter_4 .'/'. $counter_3 .'data. '.$counter_1.' updated, '.$counter_2.' added');
		$this->session->set_flashdata('notif_type','alert-success');
		redirect('admin/user');
	}
	private function org_tree($parent,$list){
		$temp = $this->org_model->get_Organization_list($parent,0,'','');
		$list[$this->index]=$parent;
		$this->index++;
		foreach ($temp as $row) {
			$list[$this->index]=$row->OrganizationID;
			$this->index+=1;
			if ($this->org_model->count_Organization($row->OrganizationID,0,'','')>0){
				$this->index+=1;
				$list = $this->org_tree($row->OrganizationID,$list);
			}
		}
	  return $list;

	}
	private function build_tree($isSAP,$parent=0,$list){  	
	  $temp = $this->org_model->get_Organization_list($parent,$isSAP,date('Y-m-d'),date('Y-m-d'));
	  foreach ($temp as $row) {
	  	$list[$this->index]['id']=$row->OrganizationID;
	  	$list[$this->index]['text']='';
	  	for($i=1;$i<=$this->depth;$i++){
	  		$list[$this->index]['text'].='--';
	  	}
	  	$list[$this->index]['text'].=$row->OrganizationName;
	  	if ($this->org_model->count_Organization($row->OrganizationID,$isSAP,date('Y-m-d'),date('Y-m-d'))>0){
				$this->index+=1;
				$depth_temp = $this->depth;
				$this->depth+=1;
				$list = $this->build_tree($isSAP,$row->OrganizationID,$list);
				$this->depth = $depth_temp;

			}
	  	$this->index+=1;
	  }
	  return $list;
	} 
}