<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Agreement1 extends Controller {
	function __construct(){
		parent::__construct();
		if(!$this->session->userdata('loginFlag')){
			redirect('account/login');
		}
		$this->load->library('email');
		$this->load->model('rkk_model');
		$this->load->model('idp_model');
		$this->load->model('general_model');
		$this->load->model('account_model');
		$this->load->library('email');
	}
	function index(){
		redirect('home');
	}
	function view($RKKID){
		$Periode = $this->general_model->get_ActivePeriode();
		$data_header['Periode']=$Periode;
		$data_header['Title']='RKK & IDP Agreement';
		$data_header['Agreement']=true;

		$Holder =$this->session->userdata('Holder');
		if($Holder=='')
		{
			$Holder = $this->input->post('SlcPost');
		}
		$data_header['Holder']=$Holder;
		$this->session->set_userdata('Holder',$Holder);
		$userDetail=$this->account_model->get_User_row($this->session->userdata('userID'));
		$data_header['userDetail']=$userDetail;
		$data_header['PositionList_SAP']=$this->account_model->get_Holder_list($this->session->userdata('NIK'),1,$Periode->BeginDate,$Periode->EndDate);
		$data_header['PositionList_nonSAP']=$this->account_model->get_Holder_list($this->session->userdata('NIK'),0,$Periode->BeginDate,$Periode->EndDate);
		$data_header['PositionAssignmentList_SAP']=$this->account_model->get_Assignment_list($this->session->userdata('NIK'),1,$Periode->BeginDate,$Periode->EndDate);
		$data_header['PositionAssignmentList_nonSAP']=$this->account_model->get_Assignment_list($this->session->userdata('NIK'),0,$Periode->BeginDate,$Periode->EndDate);
		
		$data_header['action']='objective/rkk/';
		if ($Holder != 0)
		{
			$isSAP=substr($Holder, 0,1);
			$HolderID=substr($Holder, 2);
			$HolderDetail = $this->account_model->get_Holder_row($HolderID,$isSAP);
			//RKK
			$Perspective=$this->rkk_model->get_Perspective_list($RKKID,$Periode->BeginDate,$Periode->EndDate);
			$i=0;
			foreach ($Perspective as $row_1) {
				$SO_List[$row_1->PerspectiveID]= $this->rkk_model->get_SO_list($RKKID,$row_1->PerspectiveID,$Periode->BeginDate,$Periode->EndDate);
				$temp_weight = 0;
				foreach ($SO_List[$row_1->PerspectiveID]as $row_2) {
					$temp = $this->rkk_model->get_KPI_list($RKKID,$row_2->SasaranStrategisID,$Periode->BeginDate,$Periode->EndDate);
					$KPI_List[$row_2->SasaranStrategisID]= $temp;
					foreach ($temp as $row_3) {
						$temp_weight += $row_3->Bobot;
					}
				}
				$per_weight[$i] = $temp_weight;
				$i++;
			}
			$data['per_weight'] = $per_weight;
			$data['Perspective_List']=$Perspective;
			$data['SO_List']=$SO_List;
			$data['KPI_List']=$KPI_List;
			// IDP
			$IDPID = $this->idp_model->get_Header_byRKKID_row($RKKID,$Periode->BeginDate,$Periode->EndDate)->IDPID;
			$dev_area_list=$this->idp_model->get_Detail_list($IDPID);
			$i=0;
			foreach ($dev_area_list as $row) {
				$training_list[$row->IDPDetailID]=$this->idp_model->get_IDP_DevelopmentProgram($row->IDPDetailID);
				if($row->DevelopmentAreaType1ID =='1')			
				{
					$result2[$i] = $this->idp_model->get_Kompetensi_NamaByID($row->DevelopmentAreaType)->Nama;	
				}
				$i++;
			}
			if(isset($result2))
			{
				$data['nama_kompetensi']=$result2;
			}
			$data['dev_area_list']=$dev_area_list;
			if(isset($training_list))
			{
				$data['training_list']=$training_list;
			}

		}
		$link['view_target'] = 'objective/rkk/view_target/';
		$link['agree']='objective/agreement/process/'.$RKKID.'/3';
		$link['disagree']='objective/agreement/process/'.$RKKID.'/2';
		$data['link']=$link;
		$this->load->view('template/top_1_view');
		$this->load->View('objective/rkk_header_view',$data_header);
		$this->load->View('objective/agreement_view',$data);
		$this->load->view('template/bottom_1_view');
		$this->load->view('objective/rkk_view_js');
	}

	function process($RKKID,$status)
	{
		$Periode = $this->general_model->get_ActivePeriode();
		$this->rkk_model->edit_rkk_status($RKKID,$status);
		$IDPID = $this->idp_model->get_Header_byRKKID_row($RKKID,$Periode->BeginDate,$Periode->EndDate)->IDPID;
		$this->idp_model->edit_Header($IDPID,$status);

		/**
		* Send Email to Chief
		*/
		//$Fullname            = $this->account_model->get_User_byUserID($row->UserID)->Fullname;
		//$Email               = $this->account_model->get_User_byUserID($row->UserID)->Email;
		/*$config['smtp_host'] ="10.10.55.10";
		$config['smtp_user'] ="pms@chr.kompasgramedia.com";
		$config['smtp_pass'] ="Abc123"; 
		$config['mailtype']  ='html';
		$config['priority']  =1;
		$config['protocol']  ='smtp';
		$this->email->initialize($config);
		$this->email->from('pms@chr.kompasgramedia.com', '[PMS Online] DO NOT REPLY THIS EMAIL!');
		$this->email->to($Email);
		$this->email->subject('[PMS Online] Agreement RKK & IDP');
		$this->email->message("<h1>PMS Online</h1>RKK dan IDP  anda sudah di submit please login PMS Online for confirmation.If you're not ".$Fullname.",please ignore this email. <br>Thank you,<br><br>PMS Online");
		
		if($this->email->send()){
			$this->session->set_flashdata('notif_text',"Email has been sent.");
			$this->session->set_flashdata('notif_type','alert-success');
		}else{
			$this->session->set_flashdata('notif',"Email has not sent");
			$this->session->set_flashdata('notif_type',"alert-danger");
		}
*/
		redirect('objective/rkk');
	}
}