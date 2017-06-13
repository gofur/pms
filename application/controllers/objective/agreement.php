<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Agreement extends Controller {
	function __construct(){
		parent::__construct();
		if(!$this->session->userdata('loginFlag')){
			redirect('account/login');
		}
		$this->load->library('email');
		$this->load->model('rkk_model3');
		$this->load->model('idp_model');
		$this->load->model('general_model');
		$this->load->model('account_model');
		$this->load->library('email');
	}
	function index(){
		redirect('home');
	}
	function view($rkk_id){
		$rkk = $this->rkk_model3->get_rkk_row($rkk_id);
			
		//RKK
		$persp_ls = $this->general_model->get_Perspective_List($rkk->BeginDate,$rkk->EndDate);
		$i=0;
		foreach ($persp_ls as $row_1) {
			$SO_List[$row_1->PerspectiveID] = $this->rkk_model3->get_so_persp_list($rkk_id,$row_1->PerspectiveID,$rkk->BeginDate,$rkk->EndDate);
			$temp_weight = 0;

			foreach ($SO_List[$row_1->PerspectiveID] as $row_2) {
				$temp = $this->rkk_model3->get_kpi_so_list($rkk_id,$row_2->SasaranStrategisID,$rkk->BeginDate,$rkk->EndDate);
				$KPI_List[$row_2->SasaranStrategisID] = $temp;

			}
			$per_weight[$i] = $this->rkk_model3->sum_weight_persp($rkk_id,$row_1->PerspectiveID,$rkk->BeginDate,$rkk->EndDate);
			$i++;
		}
		$data['per_weight']       = $per_weight;
		$data['Perspective_List'] = $persp_ls;
		$data['SO_List']          = $SO_List;
		$data['KPI_List']         = $KPI_List;
		// IDP
		$IDPID = $this->idp_model->get_Header_byRKKID_row($rkk_id,$rkk->BeginDate,$rkk->EndDate)->IDPID;
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
	
		$data['link_detail']   = 'objective/rkk/detail_kpi/';
		$data['link_agree']    = 'objective/agreement/process/'.$rkk_id.'/3';
		$data['link_disagree'] = 'objective/agreement/process/'.$rkk_id.'/2';
	
		$this->load->View('objective/rkk/agreement_view',$data);

	}

	function process($rkk_id,$status)
	{
		$Periode = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$this->rkk_model3->edit_rkk_status($rkk_id,$status);
		$IDPID   = $this->idp_model->get_Header_byRKKID_row($rkk_id,$Periode->BeginDate,$Periode->EndDate)->IDPID;
		$this->idp_model->edit_Header($IDPID,$status);
		
		$chief = $this->rkk_model3->get_rkk_rel_last($rkk_id,$Periode->BeginDate,$Periode->EndDate);

		/**
		* Send Email to Chief
		*/
		
		$sub_name            = $this->account_model->get_User_byNIK($this->session->userdata('NIK'))->Fullname;
		$chief_name            = $this->account_model->get_User_byNIK($chief->chief_nik)->Fullname;
		$chief_email               = $this->account_model->get_User_byNIK($chief->chief_nik)->Email;
		$config['smtp_host'] ="10.10.55.10";
		$config['smtp_user'] ="pms@chr.kompasgramedia.com";
		$config['smtp_pass'] ="Abc123"; 
		$config['mailtype']  ='html';
		$config['priority']  =1;
		$config['protocol']  ='smtp';
		$this->email->initialize($config);
		$this->email->from('pms@chr.kompasgramedia.com', '[PMS Online] DO NOT REPLY THIS EMAIL!');
		$this->email->to($chief_email);
		$this->email->subject('[PMS Online] Agreement RKK & IDP');
		$this->email->message("<h2>Information</h2>RKK and IDP for ".$sub_name." has been confirm, 
			please check your PMS Online.<br> 
			If you're not ".$chief_name.",please ignore this email. <br>Thank you,<br><br>PMS Online");
		
		/*if($this->email->send()){
			$this->session->set_flashdata('notif_text',"Email has been sent.");
			$this->session->set_flashdata('notif_type','alert-success');
		}else{
			$this->session->set_flashdata('notif',"Email has not sent");
			$this->session->set_flashdata('notif_type',"alert-danger");
		}*/

		redirect('objective/rkk');
	}
}
