<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class IDR extends Controller {
	function __construct(){
		parent::__construct();
		if(!$this->session->userdata('loginFlag')){
			redirect('account/login');
		}
		$this->load->model('rkk_model');
		$this->load->model('rkk_model3');
		$this->load->model('idp_model');
		$this->load->model('general_model');
		$this->load->model('org_model');
		$this->load->model('account_model');
	}

	function index(){
		$Periode = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$data_header['Periode']=$Periode;
		$Holder = $this->session->userdata('Holder');
		$Holder = $this->input->post('SlcPost');
		
		$data_header['process'] = 'performance/idr';
		$data_header['Holder']=$Holder;
		$this->session->set_userdata('Holder',$Holder);
		$userDetail=$this->account_model->get_User_nik($this->session->userdata('NIK'));
		$data_header['userDetail']=$userDetail;
		$data_header['PositionList_SAP']=$this->account_model->get_Holder_list($this->session->userdata('NIK'),1,$Periode->BeginDate,$Periode->EndDate);
		$data_header['PositionList_nonSAP']=$this->account_model->get_Holder_list($this->session->userdata('NIK'),0,$Periode->BeginDate,$Periode->EndDate);
		$data_header['PositionAssignmentList_SAP']=$this->account_model->get_Assignment_list($this->session->userdata('NIK'),1,$Periode->BeginDate,$Periode->EndDate);
		$data_header['PositionAssignmentList_nonSAP']=$this->account_model->get_Assignment_list($this->session->userdata('NIK'),0,$Periode->BeginDate,$Periode->EndDate);
		
		
		if($Holder!=0)
		{
			$isSAP=substr($Holder, 0,1);
			$HolderID=substr($Holder, 2);
			$HolderDetail = $this->account_model->get_Holder_row($HolderID,$isSAP,$Periode->BeginDate,$Periode->EndDate);
			$RKK = $this->rkk_model3->get_rkk_holder_last($userDetail->NIK,$HolderDetail->PositionID,$isSAP, $Periode->BeginDate,$Periode->EndDate,'all') ;

			if(count($RKK))
			{
				switch ($RKK->statusFlag) 
				{
					case 0://belum jadi
						if($userDetail->RoleID==4 and $HolderDetail->Chief==2 )
						{
							redirect('performance/idr/create_idr/'.$RKK->RKKID.'/'.$Holder);
											
						}else{
							$data_header['notif_text']='IDR not available';
						}
						break;
					case 3://disetujui dan final
						$IDP = $this->idp_model->get_Header_byRKKID_row($RKK->RKKID, $Periode->BeginDate, $Periode->EndDate);
						if(count($IDP))
						{
							//cek status idp
							switch ($IDP->StatusFlag) 
							{
								case 0://belum jadi
									if($userDetail->RoleID==4 and $HolderDetail->Chief==2){
										redirect('performance/idr/create_idr/'.$RKK->RKKID.'/'.$Holder);
										
									}else{
										$data_header['notif_text']='IDP not available';
									}
									break;
								case 3://disetujui dan final
									if($HolderDetail->Chief==2){
										redirect('performance/idr/create_idr/'.$RKK->RKKID.'/'.$Holder);
									}else{
										redirect('performance/idr/create_idr/'.$RKK->RKKID.'/'.$Holder);

									}
									break;		
								default:
									# code...
									break;
							}
						}else{
							if($userDetail->RoleID==4 and $HolderDetail->Chief==2){
								redirect('performance/idr/create_idr/'.$RKK->RKKID.'/'.$Holder);
							}else{
								$data_header['notif_text']='IDP not available';
							}
						}
						break;	
						
					default:
						# code...
						break;
				}
			}else{
					//$data_header['notif_text']='Please Create RKK';
			}			
		}

		$this->load->view('template/top_1_view');
		$this->load->View('performance/idr_header_view',$data_header);
		$this->load->view('template/bottom_1_view');
	}
	
	function finish_idp($RKKID)
	{
		//cek dulu apakah RKKID ada di table HeaderIDP kalau tidak ada maka ada pesan tidak bisa Finish karena belum isi idp
		//apabila sudah ada IDP di table HeaderIDP update status flag di HeaderIDP
		$IDPID = $this->idp_model->get_Header_rowbyRKKID($RKKID)->IDPID;
		if(count($IDPID)!=0)
		{
			//update status flag
			$FinishStatusFlag=$this->idp_model->edit_Header($IDPID,3);

			$data['notif_text']='Success Finish IDP';
			$data['notif_type']='alert-success';
			$this->load->view('template/top_popup_1_view');
			$this->load->view('template/notif_view',$data);
			$this->load->view('template/bottom_popup_1_view');
		}
		else
		{
			$data['notif_text']='Please add IDP First..';
			$data['notif_type']='alert-error';
			$this->load->view('template/top_popup_1_view');
			$this->load->view('template/notif_view',$data);
			$this->load->view('template/bottom_popup_1_view');
		}

	}


	function create_idr($RKKID,$Holder){
		
		$link['add_development_plan']='performance/idr/create_self_idp/';
		$link['add_development_program']='performance/idr/create_development_program/';
		$link['finish_idp']='performance/idr/finish_idp/';
		$link['view_subordinateIDR']='performance/idr/view_subordinateIDR/';
		$data_header['link']=$link;
		$data_header['Chief_RKKID']=$RKKID;

		$data['link']=$link;
		$Periode                    = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$data_header['PositionList_SAP']=$this->account_model->get_Holder_list($this->session->userdata('NIK'),1,$Periode->BeginDate,$Periode->EndDate);
		$data_header['PositionList_nonSAP']=$this->account_model->get_Holder_list($this->session->userdata('NIK'),0,$Periode->BeginDate,$Periode->EndDate);
		$isSAP=substr($Holder,0,1);
		$HolderID = substr($Holder, 2);
		$RKK = $this->rkk_model->get_rkk_row($RKKID) ;
		$HolderDetail = $this->account_model->get_Holder_row($HolderID,$isSAP,$Periode->BeginDate,$Periode->EndDate);
	
		$data_header['Periode']=$Periode;
		$data_header['RKK']=$RKK;
		$data_header['Holder']=$Holder;
		$this->session->set_userdata('Holder',$Holder);
		$userDetail=$this->account_model->get_User_nik($this->session->userdata('NIK'));
		$data_header['userDetail']=$userDetail;
		$data_header['PositionList_SAP']=$this->account_model->get_Holder_list($this->session->userdata('NIK'),1,$Periode->BeginDate,$Periode->EndDate);
		$data_header['PositionList_nonSAP']=$this->account_model->get_Holder_list($this->session->userdata('NIK'),0,$Periode->BeginDate,$Periode->EndDate);
		$data_header['PositionAssignmentList_SAP']=$this->account_model->get_Assignment_list($this->session->userdata('NIK'),1,$Periode->BeginDate,$Periode->EndDate);
		$data_header['PositionAssignmentList_nonSAP']=$this->account_model->get_Assignment_list($this->session->userdata('NIK'),0,$Periode->BeginDate,$Periode->EndDate);
		
		$TotalIDPHeaderByRKKID=$this->idp_model->get_Header_rowbyRKKID($RKKID);
		$data['countHeaderIDP']=count($TotalIDPHeaderByRKKID);
		if(count($TotalIDPHeaderByRKKID)!=0)
		{
			if($TotalIDPHeaderByRKKID->StatusFlag==3)
			{
				$subordinate=$this->org_model->get_directSubordinate_list($isSAP,$HolderDetail->PositionID,$Periode->BeginDate,$Periode->EndDate);
				$userDetail=$this->account_model->get_User_nik($this->session->userdata('NIK'));
				$data_header['subordinate']=$subordinate;
				$data_header['userDetail']=$userDetail;
			}

			$total_idp=$this->idp_model->get_Count_Header_byRKKID_row($RKKID,$Periode->BeginDate,$Periode->EndDate);

			if(empty($total_idp))
			{
				redirect('performance/idr/');
			}
			else
			{

				$IDPHeaderByRKKID=$this->idp_model->get_Header_byRKKID_row($RKKID,$Periode->BeginDate,$Periode->EndDate)->IDPID;
				$IDPDetailArea=$this->idp_model->get_Detail_list($IDPHeaderByRKKID);
				$data['IDPDetailArea']=$IDPDetailArea;
				$data['statusFlagIDP']= $this->idp_model->get_Header_row($IDPHeaderByRKKID)->StatusFlag;

				if(count($IDPDetailArea)!=0)
				{

					foreach ($IDPDetailArea as $row_IDPDetailArea) {
						//echo $row_IDPDetailArea->IDPDetailID;
						
						if($row_IDPDetailArea->DevelopmentAreaType1ID==1)
						{
							$result2[$row_IDPDetailArea->IDPDetailID]=$this->idp_model->get_Kompetensi_NamaByID($row_IDPDetailArea->DevelopmentAreaType)->Nama;
						}
						elseif ($row_IDPDetailArea->DevelopmentAreaType1ID==2) {
							$result2[$row_IDPDetailArea->IDPDetailID] = $row_IDPDetailArea->DevelopmentAreaType;
						}
						else
						{
							$result2[$row_IDPDetailArea->IDPDetailID]=$this->idp_model->get_CV_ValuesbyID($row_IDPDetailArea->DevelopmentAreaType)->Value_Name;	
						}

						$DevelopmentAreaType1 = $row_IDPDetailArea->DevelopmentAreaType1;
						$result[$row_IDPDetailArea->IDPDetailID]=$this->idp_model->get_IDP_DevelopmentProgram($row_IDPDetailArea->IDPDetailID);		
					}
					$data['DevelopmentAreaType']=$result2;
					$data['IDPDetailProgram']=$result;
				}
			}
		}

		$this->load->view('template/top_1_view');
		$this->load->view('performance/idr_header_view',$data_header);
		$this->load->view('performance/idr_create_view',$data);
		$this->load->view('template/bottom_1_view');
		$this->load->view('performance/idr_view_js',$data);
	}
	
	function create_self_idp($RKKID){
		$Periode = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$RKK = $this->rkk_model->get_rkk_row($RKKID);
		$data['OrgID'] = $this->org_model->get_Position_row($RKK->PositionID,$RKK->isSAP)->OrganizationID;
		$data['Development_Area_List']=$this->idp_model->get_Development_Area_List($Periode->BeginDate,$Periode->EndDate);
		$data['Development_Program_List']=$this->idp_model->get_DevProgram_list($Periode->BeginDate,$Periode->EndDate);
		$data['RKKID']=$RKKID;
		$data['process']='performance/idr/create_idp_process';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('performance/idr_adp_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('performance/idr_adp_form_js');
	}

	function create_development_program($IDPDetailID)
	{
		$Periode = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$data['Development_Area_List']=$this->idp_model->get_Development_Area_List($Periode->BeginDate,$Periode->EndDate);
		$data['Development_Program_List']=$this->idp_model->get_DevProgram_list($Periode->BeginDate,$Periode->EndDate);
		$old=$this->idp_model->get_Detail_row($IDPDetailID);
		$data['old']=$old;
		$data['DevelopmentAreaType1'] = $this->idp_model->get_DevTypeArea_row($old->DevelopmentAreaType1ID)->DevelopmentAreaType1;

		if($old->DevelopmentAreaType1ID==2)
		{
			$data['DevelopmentAreaType'] = $old->DevelopmentAreaType;
		}
		elseif ($old->DevelopmentAreaType1ID==3) {
			$data['DevelopmentAreaType']=$this->idp_model->get_CV_ValuesbyID($old->DevelopmentAreaType)->Value_Name;
		}
		else
		{
			$data['DevelopmentAreaType']=$this->idp_model->get_Kompetensi_NamaByID($old->DevelopmentAreaType)->Nama;	
		}

		$data['title']='Add Development Program';
		$data['process']='performance/idr/add_dev_prog_process';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('performance/idr_add_dev_prog_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('performance/idr_add_dev_prog_form_js');
		
	}

	function add_dev_prog_process(){
		$IDPDetailID=$this->input->post('TxtIDPDetailID');
		$BeginDate=$this->input->post('TxtBeginDate');
		$EndDate=$this->input->post('TxtEndDate');
		$DevProgramID=$this->input->post('SlcDevProgram');
		$DescriptionDevProgam=$this->input->post('txtDevProgram');
		$PlanInvestment=$this->input->post('TxtPlanInvestment');
		$Notes=$this->input->post('txtNotesADP');
		$this->idp_model->add_DevelopmentProgramTrans($IDPDetailID,$DevProgramID,$DescriptionDevProgam,$BeginDate,$EndDate,$PlanInvestment,$Notes);
		//redirect('objective/rkk/create_self_target/'.$RKKDetailID);
		$data['notif_text']='Success create Development Program';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}

	//get ajax position 
	function ajax_devAreaType($devAreaType='')
	{
			$data['devAreaType']=$devAreaType;
			$data['Kompetensi_List']=$this->idp_model->get_Kompetensi_TM_Portal();
			$data['CV_Value_List']=$this->idp_model->get_CV_Values();			
			$this->load->view('performance/idr_adp_form_type',$data);
	}

	function create_idp_process(){
		$Periode = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$RKKID=$this->input->post('TxtRKKID');
		$DevAreaType=$this->input->post('SlcDevArea');
		if($DevAreaType=='1')
		{
			$DetailDevAreaType=$this->input->post('SlcSoftComp');
		}
		elseif ($DevAreaType=='2') {
			$DetailDevAreaType=$this->input->post('txtHardComp');
		}
		else{
			$DetailDevAreaType=$this->input->post('SlcValuesComp');
		}
		
		$BeginDate=$this->input->post('TxtBeginDate');
		$EndDate=$this->input->post('TxtEndDate');
		$DevProgramID=$this->input->post('SlcDevProgram');
		$DescriptionDevProgam=$this->input->post('txtDevProgram');
		$PlanInvestment=$this->input->post('TxtPlanInvestment');
		$Notes=$this->input->post('txtNotesADP');
		
		if($this->rkk_model->check_totalRKK($RKKID)==true)
		{
			$IDPID = $this->idp_model->add_IDP($RKKID,0,$BeginDate,$EndDate)->IDPID;
		}
		else
		{
			$IDPID = $this->idp_model->get_Header_rowbyRKKID($RKKID)->IDPID;	
		}
		
		$IDPDetailID = $this->idp_model->add_IDP_Detail($IDPID,$DevAreaType,$DetailDevAreaType)->IDPDetailID;
		$this->idp_model->add_DevelopmentProgramTrans($IDPDetailID,$DevProgramID,$DescriptionDevProgam,$BeginDate,$EndDate,$PlanInvestment,$Notes);
		//redirect('objective/rkk/create_self_target/'.$RKKDetailID);
		$data['notif_text']='Success create IDP';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}

	function edit($id)
	{
		$Periode = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$data['Development_Area_List']=$this->idp_model->get_Development_Area_List($Periode->BeginDate,$Periode->EndDate);
		$data['Development_Program_List']=$this->idp_model->get_DevProgram_list($Periode->BeginDate,$Periode->EndDate);
		$old=$this->idp_model->get_DP_DevelopmentProgram_row($id);
		$data['old']=$old;
		$oldDetail=$this->idp_model->get_Detail_row($old->IDPDetailID);
		$data['DevelopmentProgram']=$this->idp_model->get_DevProg_row($old->DevelopmentProgramID)->DevelopmentProgram;
		$data['DevelopmentAreaType1'] =$this->idp_model->get_DevTypeArea_row($oldDetail->DevelopmentAreaType1ID)->DevelopmentAreaType1;

		if($oldDetail->DevelopmentAreaType1ID==2)
		{
			$data['DevelopmentAreaType'] = $oldDetail->DevelopmentAreaType;
		}
		elseif ($oldDetail->DevelopmentAreaType1ID==3) {
			$data['DevelopmentAreaType']=$this->idp_model->get_CV_ValuesbyID($oldDetail->DevelopmentAreaType)->Value_Name;
		}
		else
		{
			$data['DevelopmentAreaType']=$this->idp_model->get_Kompetensi_NamaByID($oldDetail->DevelopmentAreaType)->Nama;	
		}
		
		$data['title']='Edit Development Program';
		$data['process']='performance/idr/edit_dev_prog_process';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('performance/idr_add_dev_prog_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('performance/idr_add_dev_prog_form_js');
	}

	function edit_dev_prog_process(){
		$IDPDevelopmentProgramID=$this->input->post('TxtIDPDevelopmentProgramID');
		$RealizationBeginDate=$this->input->post('TxtRealizationBeginDate');
		$RealizationEndDate=$this->input->post('TxtRealizationEndDate');
		$RealizationInvestment=$this->input->post('TxtRealizationInvestment');
		$Notes=$this->input->post('txtNotesADP');
		$this->idp_model->edit_DP_Realization($IDPDevelopmentProgramID,$RealizationBeginDate,$RealizationEndDate,$RealizationInvestment,$Notes);
		$data['notif_text']='Success update development program realization';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}

	//untuk bawahan

	function view_subordinateIDR($NIK,$PositionID,$Chief_RKKID)
	{
		$Periode = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$link['add_development_plan']='performance/idr/create_idp_bawahan/';
		$link['add_development_program']='performance/idr/create_development_program/';
		$link['finish_idp']='performance/idr/finish_idp/';
		$link['view_subordinateIDR']='performance/idr/view_subordinateIDR/';
		$link['view_self']='performance/idr/';
		$link['edit_kpi']='performance/idr/edit_kpi/';
		$link['edit_target']='performance/idr/edit_target/';
		$data['link']=$link;
		$data_header['action']='performance/idr';
		$data_header['Title']="IDP - Subordinate's IDP";
		
		$Holder =$this->session->userdata('Holder');
		if($Holder==''){
			$Holder = $this->input->post('SlcPost');
		}
		$data_header['Holder']=$Holder;
		$this->session->set_userdata('Holder',$Holder);
		$userDetail=$this->account_model->get_User_nik($NIK);
		$user_bawahan_detail=$this->account_model->get_User_nik($NIK);
		$data_header['link']=$link;
		$data_header['Chief_RKKID']=$Chief_RKKID;
		$data_header['Periode']=$Periode;
		$data_header['userDetail']=$userDetail;
		$data_header['PositionName'] = $this->org_model->get_Position_row($PositionID,$userDetail->isSAP)->PositionName;
		$data_header['user_bawahan_detail']=$user_bawahan_detail;
		$data_header['PositionList_SAP']=$this->account_model->get_Holder_list($this->session->userdata('NIK'),1,$Periode->BeginDate,$Periode->EndDate);
		$data_header['PositionList_nonSAP']=$this->account_model->get_Holder_list($this->session->userdata('NIK'),0,$Periode->BeginDate,$Periode->EndDate);
		
		if($Holder!=0)
		{
			$isSAP=substr($Holder, 0,1);
			$HolderID=substr($Holder, 2);
			$HolderDetail = $this->account_model->get_Holder_row($HolderID,$isSAP);
			
			$subordinate=$this->org_model->get_directSubordinate_list($isSAP,$PositionID,$Periode->BeginDate,$Periode->EndDate);
			if($subordinate!=false){
				$data_header['subordinate']=$subordinate;
			}
			$Bawahan = $this->account_model->get_User_row($UserID);
			$data['Bawahan']= $Bawahan;
			$Bawahan_RKK = $this->rkk_model->get_rkk_byUserPosition_row($Bawahan->NIK,$PositionID,$Periode->BeginDate,$Periode->EndDate);
			$data['totalBawahanRKK']= $Bawahan_RKK;

				if(count($Bawahan_RKK)!=0)
				{
					if($this->rkk_model->check_totalRKK($Bawahan_RKK->RKKID)==true)
					{
						if(count($this->idp_model->get_Header_rowbyRKKID($Bawahan_RKK->RKKID))==0)
						{
							$IDPID = $this->idp_model->add_IDP($Bawahan_RKK->RKKID,0,$Periode->BeginDate,$Periode->EndDate)->IDPID;
						}
					}

					$Chief_RKK = $this->rkk_model->get_rkk_row($Chief_RKKID);
					$TotalIDPHeaderByRKKID=$this->idp_model->get_Header_rowbyRKKID($Bawahan_RKK->RKKID);
					$data['countHeaderIDP']=count($TotalIDPHeaderByRKKID);
					$data['HeaderIDP']=$TotalIDPHeaderByRKKID;
					$data['Bawahan_RKK']=$Bawahan_RKK;

					if($Bawahan_RKK->statusFlag==1 || $Bawahan_RKK->statusFlag==3)
					{
						$IDPHeaderByRKKID=$this->idp_model->get_Header_byRKKID_row($Bawahan_RKK->RKKID,$Periode->BeginDate,$Periode->EndDate)->IDPID;
						$IDPDetailArea=$this->idp_model->get_Detail_list($IDPHeaderByRKKID);
						$data['IDPDetailArea']=$IDPDetailArea;
						$data['totalIDPDetail']=count($IDPDetailArea);
						$data['statusFlagIDP']= $this->idp_model->get_Header_row($IDPHeaderByRKKID)->StatusFlag;

						if(count($Bawahan_RKK))
						{//cek rkk bawahan
							$Bawahan_RKKID = $Bawahan_RKK->RKKID;
							$Bawahan_RKKPositionID = $Bawahan_RKK->RKKPositionID; 

							$IDPHeaderByRKKID=$this->idp_model->get_Header_byRKKID_row($Bawahan_RKKID,$Periode->BeginDate,$Periode->EndDate)->IDPID;
							$IDPDetailArea=$this->idp_model->get_Detail_list($IDPHeaderByRKKID);
							$data['IDPDetailArea']=$IDPDetailArea;
							$data['statusFlagIDP']= $this->idp_model->get_Header_row($IDPHeaderByRKKID)->StatusFlag;

							if(count($IDPDetailArea)!=0)
							{
								foreach ($IDPDetailArea as $row_IDPDetailArea) {
									//echo $row_IDPDetailArea->IDPDetailID;
									
									if($row_IDPDetailArea->DevelopmentAreaType1ID==1)
									{
										$result2[$row_IDPDetailArea->IDPDetailID]=$this->idp_model->get_Kompetensi_NamaByID($row_IDPDetailArea->DevelopmentAreaType)->Nama;
									}
									elseif ($row_IDPDetailArea->DevelopmentAreaType1ID==2) {
										$result2[$row_IDPDetailArea->IDPDetailID] = $row_IDPDetailArea->DevelopmentAreaType;
									}
									else
									{
										$result2[$row_IDPDetailArea->IDPDetailID]=$this->idp_model->get_CV_ValuesbyID($row_IDPDetailArea->DevelopmentAreaType)->Value_Name;	
									}

									$DevelopmentAreaType1 = $row_IDPDetailArea->DevelopmentAreaType1;
									$result[$row_IDPDetailArea->IDPDetailID]=$this->idp_model->get_IDP_DevelopmentProgram($row_IDPDetailArea->IDPDetailID);		
								}
								$data['DevelopmentAreaType']=$result2;
								$data['IDPDetailProgram']=$result;
							}
						}else{//tambahkan jika tidak ada
						
							$Bawahan_RKKPositionID = $this->rkk_model->add_rkkPosition($PositionID,$Bawahan->isSAP,$Periode->BeginDate,$Periode->EndDate)->RKKPositionID;
							$Bawahan_RKK = $this->rkk_model->add_rkk($Bawahan_RKKPositionID,$UserID,$PositionID,$Chief_RKK->PositionID,0,$Bawahan->isSAP,$Chief_RKK->isSAP,$Periode->BeginDate,$Periode->EndDate);
							$Bawahan_RKKID = $Bawahan_RKK->RKKID; 
						}
					}
					elseif($Bawahan_RKK->statusFlag==0 || $Bawahan_RKK->statusFlag==2)
					{
						$IDPHeaderByRKKID=$this->idp_model->get_Header_byRKKID_row($Bawahan_RKK->RKKID,$Periode->BeginDate,$Periode->EndDate)->IDPID;
						$IDPDetailArea=$this->idp_model->get_Detail_list($IDPHeaderByRKKID);
						$data['IDPDetailArea']=$IDPDetailArea;
						$data['totalIDPDetail']=count($IDPDetailArea);
						$data['statusFlagIDP']= $this->idp_model->get_Header_row($IDPHeaderByRKKID)->StatusFlag;

						if(count($Bawahan_RKK))
						{//cek rkk bawahan
							$Bawahan_RKKID = $Bawahan_RKK->RKKID;
							$Bawahan_RKKPositionID = $Bawahan_RKK->RKKPositionID; 

							$IDPHeaderByRKKID=$this->idp_model->get_Header_byRKKID_row($Bawahan_RKKID,$Periode->BeginDate,$Periode->EndDate)->IDPID;
							$IDPDetailArea=$this->idp_model->get_Detail_list($IDPHeaderByRKKID);
							$data['IDPDetailArea']=$IDPDetailArea;
							$data['statusFlagIDP']= $this->idp_model->get_Header_row($IDPHeaderByRKKID)->StatusFlag;
							$data['TotalIDPDetailArea']=count($IDPDetailArea);
							if(count($IDPDetailArea)!=0)
							{

								foreach ($IDPDetailArea as $row_IDPDetailArea) {
									//echo $row_IDPDetailArea->IDPDetailID;
									
									if($row_IDPDetailArea->DevelopmentAreaType1ID==1)
									{
										$result2[$row_IDPDetailArea->IDPDetailID]=$this->idp_model->get_Kompetensi_NamaByID($row_IDPDetailArea->DevelopmentAreaType)->Nama;
									}
									elseif ($row_IDPDetailArea->DevelopmentAreaType1ID==2) {
										$result2[$row_IDPDetailArea->IDPDetailID] = $row_IDPDetailArea->DevelopmentAreaType;
									}
									else
									{
										$result2[$row_IDPDetailArea->IDPDetailID]=$this->idp_model->get_CV_ValuesbyID($row_IDPDetailArea->DevelopmentAreaType)->value_name;	
									}

									$DevelopmentAreaType1 = $row_IDPDetailArea->DevelopmentAreaType1;
									$result[$row_IDPDetailArea->IDPDetailID]=$this->idp_model->get_IDP_DevelopmentProgram($row_IDPDetailArea->IDPDetailID);		
								}

								$data['DevelopmentAreaType']=$result2;
								$data['IDPDetailProgram']=$result;
							}
						}else{//tambahkan jika tidak ada
						
							$Bawahan_RKKPositionID = $this->rkk_model->add_rkkPosition($PositionID,$Bawahan->isSAP,$Periode->BeginDate,$Periode->EndDate)->RKKPositionID;
							$Bawahan_RKK = $this->rkk_model->add_rkk($Bawahan_RKKPositionID,$UserID,$PositionID,$Chief_RKK->PositionID,0,$Bawahan->isSAP,$Chief_RKK->isSAP,$Periode->BeginDate,$Periode->EndDate);
							$Bawahan_RKKID = $Bawahan_RKK->RKKID; 
						}
						
						$Bawahan_Position = $this->org_model->get_Position_row($Bawahan_RKK->PositionID,$Bawahan_RKK->isSAP);
						$Chief_Position = $this->org_model->get_Position_row($Chief_RKK->PositionID,$Chief_RKK->isSAP);

						if($Bawahan_Position->OrganizationID==$Chief_Position->OrganizationID){
							$isChief=0;
						}else{
							$isChief=1;
						}
					}
					else
					{
						$data_header['notif_text']='RKK not available, please add rkk...';
					}
				}
				
			

		}

		

		$this->load->view('template/top_1_view');
		$this->load->view('performance/idr_header_view',$data_header);
		
		$this->load->view('performance/idr_subordinate_view',$data);
		$this->load->view('template/bottom_1_view');
		$this->load->view('performance/idr_view_js');

	}

	function create_idp_bawahan($RKKID)
	{
		$Periode = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$RKK = $this->rkk_model->get_rkk_row($RKKID);
		$data['OrgID'] = $this->org_model->get_Position_row($RKK->PositionID,$RKK->isSAP)->OrganizationID;
		$data['Development_Area_List']=$this->idp_model->get_Development_Area_List($Periode->BeginDate,$Periode->EndDate);
		$data['Development_Program_List']=$this->idp_model->get_DevProgram_list($Periode->BeginDate,$Periode->EndDate);
		$data['RKKID']=$RKKID;
		$data['process']='performance/idr/create_idp_bawahan_process';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('performance/idr_adp_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('performance/idr_adp_form_js');
	}

	function create_idp_bawahan_process(){
		$RKKID=$this->input->post('TxtRKKID');
		$DevAreaType=$this->input->post('SlcDevArea');
		if($DevAreaType=='1')
		{
			$DetailDevAreaType=$this->input->post('SlcSoftComp');
		}
		elseif ($DevAreaType=='2') {
			$DetailDevAreaType=$this->input->post('txtHardComp');
		}
		else{
			$DetailDevAreaType=$this->input->post('SlcValuesComp');
		}
		
		$BeginDate=$this->input->post('TxtBeginDate');
		$EndDate=$this->input->post('TxtEndDate');
		$DevProgramID=$this->input->post('SlcDevProgram');
		$DescriptionDevProgam=$this->input->post('txtDevProgram');
		$PlanInvestment=$this->input->post('TxtPlanInvestment');
		$Notes=$this->input->post('txtNotesADP');
		$IDPID = $this->idp_model->get_Header_rowbyRKKID($RKKID)->IDPID;	
		
		$IDPDetailID = $this->idp_model->add_IDP_Detail($IDPID,$DevAreaType,$DetailDevAreaType)->IDPDetailID;
		$this->idp_model->add_DevelopmentProgramTrans($IDPDetailID,$DevProgramID,$DescriptionDevProgam,$BeginDate,$EndDate,$PlanInvestment,$Notes);
		//redirect('objective/rkk/create_self_target/'.$RKKDetailID);
		$data['notif_text']='Success create IDP';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
}