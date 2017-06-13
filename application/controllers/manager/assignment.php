<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Assignment extends Controller {
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
		$this->load->model('Assignment_model');
		$this->load->model('account_model');
		$this->load->model('org_model');
	}

	function index()
	{
		redirect('manager/assignment/view');
	}

	function subordinate($chiefHolder,$isSAP,$HolderID, $NIK)
	{
		$holder = $this->account_model->get_Holder_byNIK($this->session->userdata('NIK'),$this->session->userdata('isSAP'));
		$link['view_subordinateAssign']='manager/assignment/subordinate/';
		$data['link']=$link;
		$data['holder']=$chiefHolder;
		$data['notif_text']=$this->session->flashdata('notif_text');
		$data['notif_type']=$this->session->flashdata('notif_type');
		$data['rows']=$this->general_model->get_CaraHitung_list();
		//get position list
		$data['positionList']=$this->account_model->get_Holder_list($this->session->userdata('NIK'),$this->session->userdata('isSAP'),date('Y-m-d'),date('Y-m-d'));
		$userDetail=$this->account_model->get_User_row($this->session->userdata('userID'));
		$data['userDetail']=$userDetail;
		$data['periode']=$this->general_model->get_ActivePeriode();
		//get data dari Perspective
		$listRKK= array();
		$listperspective=$this->general_model->get_Perspective_list(date("Y-m-d"),date("Y-m-d"));
	
		$userDetailBawahan=$this->account_model->get_User_byNIK($NIK);
		$data['userDetailBawahan']=$userDetailBawahan;

		$assignmentDetailbyHolder=$this->Assignment_model->get_Assignment_listAllbyHolder($NIK,$isSAP,date("Y-m-d"),date("Y-m-d"));
		$data['assignmentDetailbyHolder']=$assignmentDetailbyHolder;

		$assignmentDetail=$this->Assignment_model->get_Assigment($NIK,$isSAP,date("Y-m-d"),date("Y-m-d"));
		$data['assignmentDetail']=$assignmentDetail;

		$assignmentDetailnonSAPtoSAP=$this->Assignment_model->get_AssigmentnonSAPtoSAP($NIK, date("Y-m-d"),date("Y-m-d"));
		$data['assignmentDetailnonSAPtoSAP']=$assignmentDetailnonSAPtoSAP;

		if($chiefHolder!=0){
			$HolderDetail = $this->account_model->get_Holder_row($chiefHolder,$this->session->userdata('isSAP'),date("Y-m-d"),date("Y-m-d"));
			$data['subordinate']=$this->org_model->get_directSubordinate_list($this->session->userdata('isSAP'),$HolderDetail->PositionID,date("Y-m-d"),date("Y-m-d"));
			$data['OrgID']=$HolderDetail->OrganizationID;
			$data['Chief']=$HolderDetail->Chief;
			foreach ($listperspective as $row) {
				$listRKK[$row->PerspectiveID]=$this->Assignment_model->get_Objective_list($HolderDetail->HolderID,$row->PerspectiveID);
			}
		}

		$this->load->view('template/top_1_view');
		$this->load->view('manager/assignment_view',$data);
		$this->load->view('template/bottom_1_view');
	}

	function view(){
		$holder = $this->input->post('SlcPost');
		//$data['subordinate']='manager/assignment/subordinate/'.$holder;
		$link['view_subordinateAssign']='manager/assignment/subordinate/';
		$data['link']=$link;
		$data['Chief_RKKID']='';
		$data['holder']=$holder;
		$data['notif_text']=$this->session->flashdata('notif_text');
		$data['periode']=$this->general_model->get_ActivePeriode();
		if($holder!=0){
			$holder_detail=$this->account_model->get_Holder_row($holder,$this->session->userdata('isSAP'));
			$data['subordinate']=$this->org_model->get_directSubordinate_list($this->session->userdata('isSAP'),$holder_detail->PositionID,date('Y-m-d'),date('Y-m-d'));
		}
		$data['notif_type']=$this->session->flashdata('notif_type');
		$data['rows']=$this->general_model->get_CaraHitung_list();
		//get position list
		$data['positionList']=$this->account_model->get_Holder_listIsMain($this->session->userdata('NIK'),$this->session->userdata('isSAP'),date('Y-m-d'),date('Y-m-d'));
		$userDetail=$this->account_model->get_User_row($this->session->userdata('userID'));
		$data['userDetail']=$userDetail;
		$data['periode']=$this->general_model->get_ActivePeriode();
		//get data dari Perspective
		$listRKK= array();
		$listperspective=$this->general_model->get_Perspective_list(date("Y-m-d"),date("Y-m-d"));
		if($holder!=0){
			$HolderDetail = $this->account_model->get_Holder_row($holder,$this->session->userdata('isSAP'));
			$data['OrgID']=$HolderDetail->OrganizationID;
			$data['Chief']=$HolderDetail->Chief;
			foreach ($listperspective as $row) {
				$listRKK[$row->PerspectiveID]=$this->Assignment_model->get_Objective_list($HolderDetail->HolderID,$row->PerspectiveID);
			}
		}

		$this->load->view('template/top_1_view');
		$this->load->view('manager/assignment_view',$data);
		$this->load->view('template/bottom_1_view');
	}

	function add($PositionID, $isSAP, $NIK){
		$data['NIKBawahan']=$NIK;
		$data['PositionIDBawahan']=$PositionID;
		$data['organizationTypeSAP']=$this->org_model->get_Organization_listSAP(date('Y-m-d'),date('Y-m-d'));
		$data['organizationTypenonSAP']=$this->org_model->get_Organization_listnonSAP(date('Y-m-d'),date('Y-m-d'));
		$data['process']='manager/assignment/add_process';
		$data['title']='Add Additional Assignment';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('manager/assignment_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('manager/assignment_form_js');

	}

	function add_process(){
		$OrganizationID = $this->input->post('SlcOrgID');
		$PositionID = $this->input->post('SlcPosition');
		$Bobot = $this->input->post('TxtBobot');
		$Description = $this->input->post('TxtKeterangan');
		$start_date = $this->input->post('TxtStartDate');
		$end_date = $this->input->post('TxtEndDate');
		$NIKBawahan = $this->input->post('TxtNIKBawahan');
		$PositionIDBawahan = $this->input->post('TxtPositionIDBawahan');
		//cek dulu nik dan position yang bersangkutan sudah ada blm di table assignment
		if($this->Assignment_model->check_AssignmentbyNIK_isUsed($NIKBawahan,$PositionID)==true){
			//kalau ada tidak bisa menambahkan
			$data['notif_text']='Gagal add Assignment because NIK and Position already exist.';
			$data['notif_type']='alert-error';
		}else{
			//kalau tidak ada bisa menambahkan
			$this->Assignment_model->add_Assignment($NIKBawahan,$PositionID,$Bobot,$Description,$start_date,$end_date);
			$data['notif_text']='add Assignment Success.';
			$data['notif_type']='alert-success';
		}

		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
	function edit($id, $PositionID, $NIK,$isSAP, $AssignmentStatus=''){
		$data['NIKBawahan']=$NIK;
		$data['PositionIDBawahan']=$PositionID;
		$data['organizationTypeSAP']=$this->org_model->get_Organization_listSAP(date('Y-m-d'),date('Y-m-d'));
		$data['organizationTypenonSAP']=$this->org_model->get_Organization_listnonSAP(date('Y-m-d'),date('Y-m-d'));
		$data['process']='manager/assignment/edit_process';
		$data['title']='Edit Additional Assignment';
		if($id!=0)
		{
			
			$oldOrg=$this->Assignment_model->get_Assignment_rowAssignment($id)->PositionID;
			if($oldOrg >= '50000000')
			{
				$data['OrganizationID'] = $this->Assignment_model->get_Assignment_rowSAP($id)->OrganizationID;
				$old=$this->Assignment_model->get_Assignment_rowSAP($id);
			}
			else
			{
				$data['OrganizationID'] = $this->Assignment_model->get_Assignment_rowNonSAP($id)->OrganizationID;
				$old=$this->Assignment_model->get_Assignment_rowNonSAP($id);
			}
			$data['old']=$old;

			if($AssignmentStatus==0)
			{
				$data['disabled']='disabled="disabled"';
			}
		}
		else
		{
			$old=$this->org_model->get_Position_row($PositionID,$isSAP);			
			$data['OrganizationID'] = $this->org_model->get_Position_row($PositionID,$isSAP)->OrganizationID;
			$data['old']=$old;
			$data['disabled']='disabled="disabled"';
		}

		$this->load->view('template/top_popup_1_view');
		$this->load->view('manager/assignment_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('manager/assignment_form_js',$data);
		
	}

	//get ajax position 
	function ajax_position($Orgid='',$isSAP='', $PositionID='', $AssignmentStatus='', $editData=''){

		if($Orgid!=''){
			$today=date('Y-m-d');
			if($AssignmentStatus!='')
			{
				if($AssignmentStatus!=1)
				{
					$data['disabled']='disabled="disabled"';
				}
			}



			if($Orgid >= '50000000')
			{
				if($editData!='')
				{
					$data['PositionList']=$this->org_model->get_position_now($Orgid,$PositionID,1,$today,$today);
				}
				else
				{
					$data['PositionList']=$this->org_model->get_position_available($Orgid,$PositionID,1,$today,$today);
				}
			}
			else
			{
				$data['PositionList']=$this->org_model->get_position_assignment($Orgid,$PositionID,0,$today,$today);
				//$data['PositionList']=$this->org_model->get_position_available($Orgid,$PositionID,0,$today,$today);
			}

			$this->load->view('manager/assignment_form_position',$data);
		}
	}

	//edit additional assigment
	function edit_process(){
		$OrganizationID = $this->input->post('SlcOrgID');
		$PositionID = $this->input->post('SlcPosition');
		$TxtPositionID = $this->input->post('TxtPositionID');
		$TxtPositionIDOld = $this->input->post('TxtPositionIDOld');
		$TxtOrganizationIDOld = $this->input->post('TxtOrganizationIDOld');
		$Bobot = $this->input->post('TxtBobot');
		$Description = $this->input->post('TxtKeterangan');
		$start_date = $this->input->post('TxtStartDate');
		$end_date = $this->input->post('TxtEndDate');
		$AssignmentID = $this->input->post('TxtAssignmentID');
		$NIKBawahan = $this->input->post('TxtNIKBawahan');
		$AssignmentStatus = $this->input->post('TxtAssignmentStatus');

		//jika AssignmentID ada isinya maka lakukan edit
		//jika tidak maka ini data holder maka insert to table assignment
		if($AssignmentID!='')
		{
			if($AssignmentStatus==0)
			{
				if($TxtOrganizationIDOld==$OrganizationID AND $TxtPositionIDOld=$TxtPositionID)
				{
						$this->Assignment_model->edit_AssignmentWithoutPositionID($AssignmentID,$NIKBawahan,$Bobot,$Description,$start_date,$end_date);		
						$data['notif_text']='Success edit Additional Assignment';
						$data['notif_type']='alert-success';			
				}
				else
				{
					if($this->Assignment_model->check_AssignmentbyNIK_isUsed($NIKBawahan,$PositionID)==true){
					//kalau ada tidak bisa menambahkan
					$data['notif_text']='Gagal add Assignment because NIK and Position already exist1.';
					$data['notif_type']='alert-error';
					}
					else
					{
						$this->Assignment_model->edit_Assignment($AssignmentID,$NIKBawahan,$TxtPositionID,$Bobot,$Description,$start_date,$end_date);		
						$data['notif_text']='Success edit Additional Assignment';
						$data['notif_type']='alert-success';			
					}
				}
			}
			else
			{
				//jika orgid dan positionid sama maka update data yg lainnya
				//jika berbeda maka update data semuanya tapi cek dulu orgid dan positionid ada atau tidak
				if($TxtOrganizationIDOld==$OrganizationID AND $TxtPositionIDOld=$PositionID)
				{
						$this->Assignment_model->edit_AssignmentWithoutPositionID($AssignmentID,$NIKBawahan,$Bobot,$Description,$start_date,$end_date);		
						$data['notif_text']='Success edit Additional Assignment';
						$data['notif_type']='alert-success';			
				}
				else
				{
					if($this->Assignment_model->check_AssignmentbyNIK_isUsed($NIKBawahan,$PositionID)==true){
					//kalau ada tidak bisa menambahkan
					$data['notif_text']='Gagal add Assignment because NIK and Position already exist1.';
					$data['notif_type']='alert-error';
					}
					else
					{
						$this->Assignment_model->edit_Assignment($AssignmentID,$NIKBawahan,$PositionID,$Bobot,$Description,$start_date,$end_date);		
						$data['notif_text']='Success edit Additional Assignment';
						$data['notif_type']='alert-success';			
					}
				}
			}
		}
		else
		{
			if($this->Assignment_model->check_AssignmentbyNIK_isUsed($NIKBawahan,$TxtPositionID)==true){
			//kalau ada tidak bisa menambahkan
				$data['notif_text']='Gagal add Assignment because NIK and Position already exist2.';
				$data['notif_type']='alert-error';
			}else{
				$this->Assignment_model->add_AssignmentEdit($NIKBawahan,$TxtPositionID,$Bobot,$Description,$start_date,$end_date);
				$data['notif_text']='Success edit Additional Assignment';
				$data['notif_type']='alert-success';			
			}
		}
		
		
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}

}