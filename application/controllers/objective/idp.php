<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class IDP extends Controller {
	function __construct(){
		parent::__construct();
		if(!$this->session->userdata('loginFlag')){
			redirect('account/login');
		}
		$this->load->library('email');
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
		$data_header['process']='objective/idp/';

		$filter_start = $this->input->post('dt_filter_start');
		$filter_end   = $this->input->post('dt_filter_end');
		if($filter_start=='')
		{
			$filter_start = $Periode->BeginDate;
		}

		if($filter_end=='')
		{
			$filter_end   = $Periode->EndDate;
		}

		$filter_start = substr($filter_start, 0,10);
		$filter_end = substr($filter_end, 0,10);
		$data_header['filter_start'] = $filter_start;
		$data_header['filter_end']   = $filter_end;
	
		$data_header['process'] = 'objective/idp';
		$data_header['Holder']=$Holder;
		$this->session->set_userdata('Holder',$Holder);
		$userDetail=$this->account_model->get_User_nik($this->session->userdata('NIK'));
		$data_header['userDetail']=$userDetail;
		$data_header['PositionList_SAP']=$this->account_model->get_Holder_list($this->session->userdata('NIK'),1,$filter_start,$filter_end);
		$data_header['PositionList_nonSAP']=$this->account_model->get_Holder_list($this->session->userdata('NIK'),0,$filter_start,$filter_end);
		$data_header['PositionAssignmentList_SAP']=$this->account_model->get_Assignment_list($this->session->userdata('NIK'),1,$filter_start,$filter_end);
		$data_header['PositionAssignmentList_nonSAP']=$this->account_model->get_Assignment_list($this->session->userdata('NIK'),0,$filter_start,$filter_end);
		$link['finish_idp']								= 'objective/idp/finish_idp/';
		$link['view_subordinateIDP']			= 'objective/idp/view_subordinateIDP/';
		$data_header['process']='objective/idp/';


		$data_header['link']        = $link;
		$data_header['action']      = 'objective/idp/';
		$data['link']               = $link;


		
		if($Holder!=0)
		{
			$isSAP=substr($Holder, 0,1);
			$HolderID=substr($Holder,2);
			$HolderDetail = $this->account_model->get_Holder_row($HolderID,$isSAP,$filter_start,$filter_end);
			$RKK = $this->rkk_model3->get_rkk_holder_last($userDetail->NIK,$HolderDetail->PositionID,$isSAP, $filter_start,$filter_end,'all') ;

			$subordinate=$this->org_model->get_directSubordinate_list($isSAP,$HolderDetail->PositionID,$filter_start,$filter_end);

			if($subordinate!=false){
				$data_header['subordinate']=$subordinate;
			}

			if(count($RKK))
			{
				$data_header['Chief_RKKID'] = $RKK->RKKID;
				switch ($RKK->statusFlag) 
				{
					case 0://belum jadi
						if($userDetail->RoleID==4 and $HolderDetail->Chief==2 )
						{
							redirect('objective/idp/create_idp/'.$RKK->RKKID.'/'.$Holder.'/'.$filter_start.'/'.$filter_end);
											
						}else{
							$data_header['notif_text']='IDP not available';
						}
						break;
					case 3://disetujui dan final
						$IDP = $this->idp_model->get_Header_byRKKID_row($RKK->RKKID, $filter_start, $filter_end);
						if(count($IDP))
						{
							//cek status idp
							switch ($IDP->StatusFlag) 
							{

								case 0://belum jadi
									if($userDetail->RoleID==4 and $HolderDetail->Chief==2 ){
										redirect('objective/idp/create_idp/'.$RKK->RKKID.'/'.$Holder.'/'.$filter_start.'/'.$filter_end);
										
									}else{
										$data_header['notif_text']='IDP not available';
									}
									break;
								case 3://disetujui dan final
									if($HolderDetail->Chief==2){
										redirect('objective/idp/create_idp/'.$RKK->RKKID.'/'.$Holder.'/'.$filter_start.'/'.$filter_end);
									}else{
										redirect('objective/idp/create_idp/'.$RKK->RKKID.'/'.$Holder.'/'.$filter_start.'/'.$filter_end);

									}
									break;		
								default:
									# code...
									break;
							}
						}else{
							if($userDetail->RoleID==4 and $HolderDetail->Chief==2){
								redirect('objective/idp/create_idp/'.$RKK->RKKID.'/'.$Holder);
								// redirect('manager/rkk_add');
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
				// $data_header['notif_text']='Please Create RKK';
				// redirect('manager/rkk_add');
			}			
		}

		
		$this->load->view('template/top_1_view');
		$this->load->View('objective/idp_header_view',$data_header);
		$this->load->view('template/bottom_1_view');
		$this->load->View('objective/idp_header_view_js',$data_header);
	}
	
	function finish_idp($RKKID)
	{
		//cek dulu apakah RKKID ada di table HeaderIDP kalau tidak ada maka ada pesan tidak bisa Finish karena belum isi idp
		//apabila sudah ada IDP di table HeaderIDP update status flag di HeaderIDP
		$IDPID = $this->idp_model->get_Header_rowbyRKKID($RKKID)->IDPID;
		if(count($IDPID)!=0)
		{
		
			//update status flag RKK & IDP
			$this->rkk_model->edit_rkk_status($RKKID,3);
			$FinishStatusFlag=$this->idp_model->edit_Header($IDPID,3);
		
			/*$this->session->set_flashdata('notiftext','Success Finish IDP');
			$this->session->set_flashdata('notiftype','alert-success');*/
			
	
			echo '<div class="row "><div class="alert alert-success"><button data-dismiss="alert" class="close" type="button">×</button>Success Finish IDP</div></div></div>';
		}
		else
		{
			// $notif_text=$this->session->set_flashdata('notif_text','Please add IDP First..');
			// $notif_type=$this->session->set_flashdata('notif_type','alert-error');
			echo '<div class="row "><div class="alert alert-error"><button data-dismiss="alert" class="close" type="button">×</button>Please add IDP First..</div></div></div>';
		}
		

		$this->load->view('template/top_popup_1_view');
		
		//redirect('objective/idp/');
	}
	function lock_idp($RKKID,$PositionID,$Chief_RKKID)
	{
	
		$IDPID = $this->idp_model->get_Header_rowbyRKKID($RKKID)->IDPID;
		$Fullname = $this->account_model->get_User_byNIK($this->session->userdata('NIK'))->Fullname;
		$Email = $this->account_model->get_User_byNIK($this->session->userdata('NIK'))->Email;
		$FinishStatusFlag=$this->idp_model->edit_Header($IDPID,1);
		$succesNote='Finish IDP for '.$Fullname;
		redirect('objective/idp/view_subordinateIDP/'.$this->session->userdata('NIK').'/'.$PositionID.'/'.$Chief_RKKID);
	}

	function create_idp($RKKID,$Holder, $filter_start, $filter_end){

		$link['add_development_plan']			= 'objective/idp/create_self_idp/';
		$link['add_development_program']	= 'objective/idp/create_development_program/';
		$link['finish_idp']								= 'objective/idp/finish_idp/';
		$link['view_subordinateIDP']			= 'objective/idp/view_subordinateIDP/';
		$data_header['process']='objective/idp/';


		$data_header['link']        = $link;
		$data_header['Chief_RKKID'] = $RKKID;
		$data_header['action']      = 'objective/idp/';
		$data['link']               = $link;
		$Periode                    = $this->general_model->get_Period_row($this->session->userdata('active_period'));
				
		$data_header['filter_start'] = $filter_start;
		$data_header['filter_end']   = $filter_end;

		$self_rkk     = $this->rkk_model3->get_rkk_row($RKKID);
		$rel_rkk      = $this->rkk_model3->get_rkk_rel_last($RKKID,$self_rkk->BeginDate,$self_rkk->EndDate);
		if (count($rel_rkk)) {
		$spr_person   = $this->account_model->get_User_byNIK($rel_rkk->chief_nik);
		$data['spr_person'] = $spr_person;
		$data['spr_post']   = $this->org_model->get_Position_row($rel_rkk->chief_post_id,$rel_rkk->chief_is_sap,$rel_rkk->BeginDate,$rel_rkk->EndDate);
		}



		$data_header['PositionList_SAP'] 	= $this->account_model->get_Holder_list(
			$this->session->userdata('NIK'),
			1,
			$filter_start,
			$filter_end
		);

		$data_header['PositionList_nonSAP'] 					= $this->account_model->get_Holder_list(
			$this->session->userdata('NIK'),
			0,
			$filter_start,
			$filter_end
		);

		$data_header['PositionAssignmentList_SAP'] 		= $this->account_model->get_Assignment_list(
			$this->session->userdata('NIK'),
			1,
			$filter_start,
			$filter_end
		);

		$data_header['PositionAssignmentList_nonSAP'] = $this->account_model->get_Assignment_list(
			$this->session->userdata('NIK'),
			0,
			$filter_start,
			$filter_end
		);
		$isSAP 				= substr($Holder,0,1);
		$HolderID 		= substr($Holder, 2);
		$RKK 					= $this->rkk_model->get_rkk_row($RKKID) ;
		$HolderDetail = $this->account_model->get_Holder_row(
			$HolderID,
			$isSAP,
			$filter_start,
			$filter_end
		);

		$data_header['Periode'] = $Periode;
		$data_header['Title'] 	= 'Individual Development Plan';
		$data_header['RKK']			= $RKK;
		$data_header['Holder']	= $Holder;
		$this->session->set_userdata('Holder',$Holder);
		
		$userDetail = $this->account_model->get_User_nik($this->session->userdata('NIK'));
		$data_header['userDetail'] 				= $userDetail;
		$data_header['PositionList_SAP'] 	= $this->account_model->get_Holder_list(
			$this->session->userdata('NIK'),
			1,
			$filter_start,
			$filter_end
		);
		$data_header['PositionList_nonSAP'] = $this->account_model->get_Holder_list(
			$this->session->userdata('NIK'),
			0,
			$filter_start,
			$filter_end
		);
		$data_header['PositionAssignmentList_SAP'] = $this->account_model->get_Assignment_list(
			$this->session->userdata('NIK'),
			1,
			$filter_start,
			$filter_end
		);
		$data_header['PositionAssignmentList_nonSAP'] = $this->account_model->get_Assignment_list(
			$this->session->userdata('NIK'),
			0,
			$filter_start,
			$filter_end
		);
		$TotalIDPHeaderByRKKID 				= $this->idp_model->get_Header_rowbyRKKID($RKKID);
		$data['countHeaderIDP'] 			= count($TotalIDPHeaderByRKKID);
		$data['RoleID']								= $userDetail->RoleID;
		$data['TotalIDPHeaderByRKKID']= count($TotalIDPHeaderByRKKID);

		if($this->rkk_model->check_totalRKK($RKKID) == TRUE){
			if(count($this->idp_model->get_Header_rowbyRKKID($RKKID)) == 0){
				$IDPID = $this->idp_model->add_IDP($RKKID,0,$filter_start,$filter_end);
			}
		}
		
		if(count($TotalIDPHeaderByRKKID)!=0){
			if($TotalIDPHeaderByRKKID->StatusFlag==3){
				$subordinate=$this->org_model->get_directSubordinate_list($isSAP,$HolderDetail->PositionID,$filter_start,$filter_end);
				$userDetail=$this->account_model->get_User_row($this->session->userdata('userID'));
				$data_header['subordinate']=$subordinate;
				$data_header['userDetail']=$userDetail;
			}

			$total_idp=$this->idp_model->get_Count_Header_byRKKID_row($RKKID,$filter_start,$filter_end);


			if(empty($total_idp))
			{
				redirect('objective/idp/');
			}
			else
			{
				$IDPHeaderByRKKID=$this->idp_model->get_Header_byRKKID_row($RKKID,$filter_start,$filter_end)->IDPID;
				$IDPDetailArea=$this->idp_model->get_Detail_list($IDPHeaderByRKKID);
				$data['IDPDetailArea']=$IDPDetailArea;
				$data['statusFlagIDP']= $this->idp_model->get_Header_row($IDPHeaderByRKKID)->StatusFlag;
				$data['idp']= $this->idp_model->get_Header_row($IDPHeaderByRKKID);
				$data['TotalIDPDetailArea']=count($IDPDetailArea);



				if(count($IDPDetailArea)!=0)
				{
					foreach ($IDPDetailArea as $row_IDPDetailArea) {
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
			}
		}

		$this->load->view('template/top_1_view');
		$this->load->view('objective/idp_header_view',$data_header);
		$this->load->view('objective/idp_create_view',$data);
		$this->load->view('template/bottom_1_view');
		$this->load->view('objective/idp_view_js',$data);
	}
	
	function create_self_idp($RKKID){
		$Periode = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$RKK = $this->rkk_model->get_rkk_row($RKKID);
		$data['OrgID'] = $this->org_model->get_Position_row($RKK->PositionID,$RKK->isSAP)->OrganizationID;
		$data['Development_Area_List']=$this->idp_model->get_Development_Area_List($Periode->BeginDate,$Periode->EndDate);
		$data['Development_Program_List']=$this->idp_model->get_DevProgram_list($Periode->BeginDate,$Periode->EndDate);
		$data['RKKID']=$RKKID;
		$data['process']='objective/idp/create_idp_process';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('objective/idp_adp_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('objective/idp_adp_form_js');
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
		$data['process']='objective/idp/add_dev_prog_process';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('objective/idp_add_dev_prog_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('objective/idp_add_dev_prog_form_js');
		
	}

	function add_dev_prog_process(){
		$IDPDetailID=$this->input->post('TxtIDPDetailID');
		$BeginDate=$this->input->post('TxtBeginDate');
		$EndDate=$this->input->post('TxtEndDate');
		$DevProgramID=$this->input->post('SlcDevProgram');
		$DescriptionDevProgam=$this->input->post('txtDevProgram');
		$PlanInvestment=str_replace(',', '', $this->input->post('TxtPlanInvestment'));
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
			$this->load->view('objective/idp_adp_form_type',$data);
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
		$PlanInvestment=str_replace(',', '', $this->input->post('TxtPlanInvestment'));
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

	function add_realization($id)
	{
		$Periode = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$data['Development_Area_List']=$this->idp_model->get_Development_Area_List($Periode->BeginDate,$Periode->EndDate);
		$data['Development_Program_List']=$this->idp_model->get_DevProgram_list($Periode->BeginDate,$Periode->EndDate);
		$old=$this->idp_model->get_DP_DevelopmentProgram_row($id);
		$data['old']=$old;

		$oldDetail=$this->idp_model->get_Detail_row($old->IDPDetailID);
		$data['oldDetail'] = $oldDetail;
			
		$data['DevelopmentAreaType1'] = $this->idp_model->get_DevTypeArea_row($oldDetail->DevelopmentAreaType1ID)->DevelopmentAreaType1;

		if($oldDetail->DevelopmentAreaType1ID==2)
		{
			$data['DevelopmentAreaType'] = $oldDetail->DevelopmentAreaType;
		}
		elseif ($oldDetail->DevelopmentAreaType1ID==3) {
			$data['DevelopmentAreaType']=$this->idp_model->get_CV_ValuesbyID($oldDetail->DevelopmentAreaType)->value_name;
		}
		else
		{
			$data['DevelopmentAreaType']=$this->idp_model->get_Kompetensi_NamaByID($oldDetail->DevelopmentAreaType)->Nama;	
		}
		
		
		$data['title']='Add Realization Development Program';
		$data['process']='objective/idp/add_realization_dev_prog_process';
		$data['add_realization']=1;
		$this->load->view('template/top_popup_1_view');
		$this->load->view('objective/idp_add_dev_prog_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('objective/idp_add_dev_prog_form_js',$data);
	}


	function add_realization_dev_prog_process(){
		$IDPDevelopmentProgramID=$this->input->post('TxtIDPDevelopmentProgramID');
		$begin_date_realization=$this->input->post('txt_begindate_realization');
		$end_date_realization=$this->input->post('txt_enddate_realization');
		$realization_investment=str_replace(',', '', $this->input->post('txt_realization_investment'));
		$Notes=$this->input->post('txtNotesADP');
		$IDPDetailID=$this->input->post('TxtIDPDetailID');


		//edit development program
		$this->idp_model->add_realization($IDPDevelopmentProgramID,$begin_date_realization,$end_date_realization,$realization_investment,$Notes);
		$data['notif_text']='Success update development program';
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
		$data['oldDetail'] = $oldDetail;
			
		$data['DevelopmentAreaType1'] = $this->idp_model->get_DevTypeArea_row($oldDetail->DevelopmentAreaType1ID)->DevelopmentAreaType1;

		if($oldDetail->DevelopmentAreaType1ID==2)
		{
			$data['DevelopmentAreaType'] = $oldDetail->DevelopmentAreaType;
		}
		elseif ($oldDetail->DevelopmentAreaType1ID==3) {
			$data['DevelopmentAreaType']=$this->idp_model->get_CV_ValuesbyID($oldDetail->DevelopmentAreaType)->value_name;
		}
		else
		{
			$data['DevelopmentAreaType']=$this->idp_model->get_Kompetensi_NamaByID($oldDetail->DevelopmentAreaType)->Nama;	
		}
		
		
		$data['title']='Edit Development Program';
		$data['process']='objective/idp/edit_dev_prog_process';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('objective/idp_add_dev_prog_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('objective/idp_add_dev_prog_form_js');
	}

	function edit_dev_prog_process(){
		$IDPDevelopmentProgramID=$this->input->post('TxtIDPDevelopmentProgramID');
		$BeginDate=$this->input->post('TxtBeginDate');
		$EndDate=$this->input->post('TxtEndDate');
		$DevProgramID=$this->input->post('SlcDevProgram');
		$DescriptionDevProgam=$this->input->post('txtDevProgram');
		$PlanInvestment=str_replace(',', '', $this->input->post('TxtPlanInvestment'));
		$Notes=$this->input->post('txtNotesADP');
		$IDPDetailID=$this->input->post('TxtIDPDetailID');



		//edit development plan
		$DevAreaType= $this->input->post('SlcDevArea');
		$old_dev_areatype = $this->input->post('TxtIDPDevAreaTypeID');

		if($DevAreaType!=$old_dev_areatype)
		{
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

			//save edit area type
			$this->idp_model->edit_IDP_Detail($IDPDetailID,$DevAreaType,$DetailDevAreaType);
		}

		
		//edit development program
		$this->idp_model->edit_DP_Trans($IDPDevelopmentProgramID,$DevProgramID,$BeginDate,$EndDate,$PlanInvestment,$DescriptionDevProgam,$Notes);
		$data['notif_text']='Success update development program';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}


	function delete($id_dev_prog){

		//get IDP Detail ID
		$get_idp_detail=$this->idp_model->get_DP_DevelopmentProgram_row($id_dev_prog);
		
		//delete IDP Header
		$this->idp_model->delete_idp_header($get_idp_detail->IDPDetailID);

		//delete IDP Program Detail
		$this->idp_model->delete_dev_program($id_dev_prog);
		$data['notif_text']='Success delete development program';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');

		// $this->idp_model->delete_dev_program($id_dev_prog);
		// $this->session->set_flashdata('notif_text','Success delete');
		// $this->session->set_flashdata('notif_type','alert-success');
	}

	//untuk bawahan

	function transfer_idp($NIK,$RKKID,$IDPDetailID)
	{
		$Periode = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$Chief_KPI = $this->rkk_model->get_rkk_row($RKKID);
		$Subordinate=$this->org_model->get_directSubordinate_list($Chief_KPI->isSAP,$Chief_KPI->PositionID,$Periode->BeginDate,$Periode->EndDate);
		$Detail_IDP = $this->idp_model->get_DP_DevelopmentProgram_join_row($IDPDetailID);

		$data['DevelopmentAreaType']=$this->idp_model->get_DevTypeArea_row($Detail_IDP->DevelopmentAreaType1ID)->DevelopmentAreaType1;

		if($Detail_IDP->DevelopmentAreaType1ID==1)
		{
			$development_area_type_desc=$this->idp_model->get_Kompetensi_NamaByID($Detail_IDP->DevelopmentAreaType)->Nama;

		}
		elseif ($Detail_IDP->DevelopmentAreaType1ID==2)
		{
			$development_area_type_desc=$Detail_IDP->DevelopmentAreaType;
		}
		else
		{
			$development_area_type_desc=$this->idp_model->get_CV_ValuesbyID($Detail_IDP->DevelopmentAreaType)->value_name;		
		}

		
		//get RKKID sub ordinate & IDP Sub ordinate check apakah StatusFlagnya tidak sama dengan 3
		foreach ($Subordinate as $row) {
			$status_array[$row->UserID]=$this->rkk_model->get_rkk_byUserPosition_list($row->UserID,$row->PositionID,$Periode->BeginDate,$Periode->EndDate);						
		}



		$data['development_area_type_desc']=$development_area_type_desc;
		$data['my_user_id']=$NIK;
		$data['status_array']=$status_array;
		$data['Detail_IDP'] = $Detail_IDP;
		$data['Chief_KPI']=$Chief_KPI;
		$data['process']='objective/idp/transfer_idp_process/'.$RKKID;
		$data['Subordinate']=$Subordinate;
		$this->load->view('template/top_popup_1_view');
		$this->load->view('objective/idp_transfer_form',$data);
		$this->load->view('template/bottom_popup_1_view');
	}

	function transfer_idp_process($RKKID)
	{
		$Periode = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$Chief_KPI = $this->rkk_model->get_rkk_row($RKKID);
		$Subordinate=$this->org_model->get_directSubordinate_list($Chief_KPI->isSAP,$Chief_KPI->PositionID,$Periode->BeginDate,$Periode->EndDate);

		//check dlu apakah checkbox tercentang
		foreach ($Subordinate as $row) {
			//apabila tercentang maka save ke IDP_T_Detail dan IDP_T_DevelopmentProgram
			$checkbox=$this->input->post('ChkSubordinate_'.$row->UserID);
			
			if($checkbox=='1')
			{
				$Bawahan_RKK = $this->rkk_model->get_rkk_byUserPosition_row($row->NIK,$row->PositionID,$Periode->BeginDate,$Periode->EndDate);


				//check IDP Header kalau belum ada maka add IDP Header
				if(count($this->idp_model->get_Header_rowbyRKKID($Bawahan_RKK->RKKID))==0)
				{
					//$IDPID = $this->idp_model->add_IDP($Bawahan_RKK->RKKID,0,$Periode->BeginDate,$Periode->EndDate)->IDPID;
				}
				
				$IDPID = $this->idp_model->get_Header_rowbyRKKID($Bawahan_RKK->RKKID)->IDPID;	

				//insert ke table IDP_Detail
				$DevAreaType = $this->input->post('txt_dev_area_id');
				$DetailDevAreaType = $this->input->post('txt_dev_area');
				$DevProgramID = $this->input->post('txt_dev_program_id');
				$DescriptionDevProgam = $this->input->post('txt_dev_program');
				$BeginDate = $this->input->post('txt_planned_begindate');
				$EndDate = $this->input->post('txt_planned_enddate');
				$PlanInvestment = $this->input->post('txt_planned_investment');
				$Notes = $this->input->post('txt_notes');

				$IDPDetailID = $this->idp_model->add_IDP_Detail($IDPID,$DevAreaType,$DetailDevAreaType)->IDPDetailID;
				//insert ke table IDP_T_DevelopmentProgram
				$this->idp_model->add_DevelopmentProgramTrans($IDPDetailID,$DevProgramID,$DescriptionDevProgam,$BeginDate,$EndDate,$PlanInvestment,$Notes);

			}
		}

		$data['notif_text']='Success Transfered IDP';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
			
	}

	function view_subordinateIDP($NIK,$PositionID,$Chief_RKKID, $filter_start, $filter_end)
	{
		$Periode                         = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$link['add_development_plan']    ='objective/idp/create_idp_bawahan/';
		$link['add_development_program'] ='objective/idp/create_development_program/';
		$link['view_subordinateIDP']     ='objective/idp/view_subordinateIDP/';
		$link['view_self']               ='objective/idp/';
		$link['edit_kpi']                ='objective/idp/edit_kpi/';
		$link['edit_target']             ='objective/idp/edit_target/';
		$link['transfer_idp']            ='objective/idp/transfer_idp/';
		$data_header['Title']            ="IDP - Subordinate's IDP";
		$data_header['notif_text']       =$this->session->flashdata('notif_text');
		$data_header['notif_type']       =$this->session->flashdata('notif_type');
		$data_header['filter_start']     = $filter_start;
		$data_header['filter_end']       = $filter_end;

		if($this->input->post('dt_filter_start')!='')
		{
			$filter_start = $this->input->post('dt_filter_start');
			$filter_end   = $this->input->post('dt_filter_end');
		}
		// if($filter_start=='')
		// {
		// 	$filter_start = $Periode->BeginDate;
		// }

		// if($filter_end=='')
		// {
		// 	$filter_end   = $Periode->EndDate;
		// }

		$filter_start = substr($filter_start, 0,10);
		$filter_end = substr($filter_end, 0,10);

		$data_header['process']='objective/idp/view_subordinateIDP/'.$NIK.'/'.$PositionID.'/'.$Chief_RKKID.'/'.$filter_start.'/'.$filter_end;

		$Holder =$this->session->userdata('Holder');
		if($Holder==''){
			$Holder = $this->input->post('SlcPost');
		}

		$data_header['Holder']=$Holder;
		$this->session->set_userdata('Holder',$Holder);
		$userDetail=$this->account_model->get_User_nik($NIK);
		
		$data_header['Chief_RKKID'] = $Chief_RKKID;
		$data_header['Periode']     = $Periode;
		$data_header['userDetail']  = $userDetail;
		$total_position = $this->org_model->get_count_position_row($PositionID,$userDetail->isSAP)->total_position;
		if($total_position==0)
		{
			$data_header['PositionName'] = $this->org_model->get_Position_row_old($PositionID,$userDetail->isSAP)->PositionName;
		}
		else
		{
			$data_header['PositionName'] = $this->org_model->get_Position_row($PositionID,$userDetail->isSAP)->PositionName;
		}
		//$data_header['user_bawahan_detail']=$user_bawahan_detail;
		$data_header['PositionList_SAP']    =$this->account_model->get_Holder_list($this->session->userdata('NIK'),1,$filter_start,$filter_end);
		$data_header['PositionList_nonSAP'] =$this->account_model->get_Holder_list($this->session->userdata('NIK'),0,$filter_start,$filter_end);
		
		if($Holder!=0)
		{
			$isSAP=substr($Holder, 0,1);
			$HolderID=substr($Holder, 2);
			$HolderDetail = $this->account_model->get_Holder_row($HolderID,$isSAP);

			
			$subordinate=$this->org_model->get_directSubordinate_list($isSAP,$PositionID,$filter_start,$filter_end);
			if($subordinate!=false){
				$data_header['subordinate']=$subordinate;
			}
			
			$Bawahan = $this->account_model->get_User_nik($NIK);
			$data['Bawahan']= $Bawahan;
			$Bawahan_RKK = $this->rkk_model->get_rkk_byUserPosition_row($Bawahan->NIK,$PositionID,$filter_start,$filter_end);

			$data['totalBawahanRKK']= $Bawahan_RKK;

			if(count($Bawahan_RKK)!=0)
			{
				if($this->rkk_model->check_totalRKK($Bawahan_RKK->RKKID)==true)
				{
					if(count($this->idp_model->get_Header_rowbyRKKID($Bawahan_RKK->RKKID))==0)
					{
						$IDPID = $this->idp_model->add_IDP($Bawahan_RKK->RKKID,0,$filter_start,$filter_end)->IDPID;
					}
				
				
					$Chief_RKK = $this->rkk_model->get_rkk_row($Chief_RKKID);
					$TotalIDPHeaderByRKKID=$this->idp_model->get_Header_rowbyRKKID($Bawahan_RKK->RKKID);
					$data['countHeaderIDP']=count($TotalIDPHeaderByRKKID);
					$data['Bawahan_RKK']=$Bawahan_RKK;


					if($Bawahan_RKK->statusFlag==1 || $Bawahan_RKK->statusFlag==3)
					{
						$IDPHeaderByRKKID=$this->idp_model->get_Header_byRKKID_row($Bawahan_RKK->RKKID,$filter_start,$filter_end)->IDPID;
						$IDPDetailArea=$this->idp_model->get_Detail_list($IDPHeaderByRKKID);
						$data['IDPDetailArea']=$IDPDetailArea;
						$data['totalIDPDetail']=count($IDPDetailArea);
						$data['statusFlagIDP']= $this->idp_model->get_Header_row($IDPHeaderByRKKID)->StatusFlag;

						if(count($Bawahan_RKK))
						{//cek rkk bawahan
							$Bawahan_RKKID = $Bawahan_RKK->RKKID;
							$Bawahan_RKKPositionID = $Bawahan_RKK->RKKPositionID; 

							$IDPHeaderByRKKID=$this->idp_model->get_Header_byRKKID_row($Bawahan_RKKID,$filter_start,$filter_end)->IDPID;
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
						
							$Bawahan_RKKPositionID = $this->rkk_model->add_rkkPosition($PositionID,$Bawahan->isSAP,$filter_start,$filter_end)->RKKPositionID;
							$Bawahan_RKK = $this->rkk_model->add_rkk($Bawahan_RKKPositionID,$UserID,$PositionID,$Chief_RKK->PositionID,0,$Bawahan->isSAP,$Chief_RKK->isSAP,$filter_start,$filter_end);
							$Bawahan_RKKID = $Bawahan_RKK->RKKID; 
						}
					}
					elseif($Bawahan_RKK->statusFlag==0 || $Bawahan_RKK->statusFlag==2)
					{
						$IDPHeaderByRKKID=$this->idp_model->get_Header_byRKKID_row($Bawahan_RKK->RKKID,$filter_start,$filter_end)->IDPID;
						$IDPDetailArea=$this->idp_model->get_Detail_list($IDPHeaderByRKKID);
						$data['IDPDetailArea']=$IDPDetailArea;
						$data['totalIDPDetail']=count($IDPDetailArea);
						$data['statusFlagIDP']= $this->idp_model->get_Header_row($IDPHeaderByRKKID)->StatusFlag;


						if(count($Bawahan_RKK))
						{//cek rkk bawahan
							$Bawahan_RKKID = $Bawahan_RKK->RKKID;
							$Bawahan_RKKPositionID = $Bawahan_RKK->RKKPositionID; 

							$IDPHeaderByRKKID=$this->idp_model->get_Header_byRKKID_row($Bawahan_RKKID,$filter_start,$filter_end)->IDPID;
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
							
							$Bawahan_RKKPositionID = $this->rkk_model->add_rkkPosition($PositionID,$Bawahan->isSAP,$filter_start,$filter_end)->RKKPositionID;
							$Bawahan_RKK = $this->rkk_model->add_rkk($Bawahan_RKKPositionID,$UserID,$PositionID,$Chief_RKK->PositionID,0,$Bawahan->isSAP,$Chief_RKK->isSAP,$filter_start,$filter_end);
							$Bawahan_RKKID = $Bawahan_RKK->RKKID; 
						}
						
						
					}
					else
					{
						redirect('manager/subordinate');
					}

				}
			}
			else
			{
				redirect('manager/subordinate');
			}

		}

		$link['finish_idp']='objective/idp/lock_idp/';
		
		$data_header['link']=$link;
		$data['link']=$link;
		$data_header['action']='objective/idp';

		$this->load->view('template/top_1_view');
		$this->load->view('objective/idp_header_view',$data_header);
		
		$this->load->view('objective/idp_subordinate_view',$data);
		$this->load->view('template/bottom_1_view');
		$this->load->view('objective/idp_view_js');

	}

	function create_idp_bawahan($RKKID)
	{
		$Periode = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$RKK = $this->rkk_model->get_rkk_row($RKKID);
		$data['OrgID'] = $this->org_model->get_Position_row($RKK->PositionID,$RKK->isSAP)->OrganizationID;
		$data['Development_Area_List']=$this->idp_model->get_Development_Area_List($Periode->BeginDate,$Periode->EndDate);
		$data['Development_Program_List']=$this->idp_model->get_DevProgram_list($Periode->BeginDate,$Periode->EndDate);
		$data['RKKID']=$RKKID;
		$data['process']='objective/idp/create_idp_bawahan_process';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('objective/idp_adp_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('objective/idp_adp_form_js');
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
		$PlanInvestment=str_replace(',', '', $this->input->post('TxtPlanInvestment'));
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