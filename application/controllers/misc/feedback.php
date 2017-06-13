<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class FeedBack extends Controller {
	function __construct(){
		parent::__construct();
		if(!$this->session->userdata('loginFlag')){
			redirect('account/login');
		}
		$this->load->model('feedback_model');
		$this->load->model('rkk_model');
		$this->load->model('rkk_model3');
		$this->load->model('general_model');
		$this->load->model('org_model');
		$this->load->model('account_model');
	}
	function index(){

		$Periode = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$data_header['Periode']=$Periode;
		$Holder = $this->session->userdata('Holder');
		$Holder = $this->input->post('SlcPost');
		
		$data_header['process'] = 'objective/idp';
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
			$HolderDetail = $this->account_model->get_Holder_row($HolderID,$isSAP, $Periode->BeginDate,$Periode->EndDate);
			$RKK = $this->rkk_model3->get_rkk_holder_last($userDetail->NIK,$HolderDetail->PositionID,$isSAP, $Periode->BeginDate,$Periode->EndDate,'all') ;
			
			//check begindate dan enddate 
			if(count($RKK))
			{
				switch ($RKK->statusFlag) 
				{
					case 0://belum jadi
						if($userDetail->RoleID==4 and $HolderDetail->Chief==2){
							redirect('misc/feedback/create_feedback/'.$RKK->RKKID.'/'.$Holder);			
						}else{
							//$data_header['notif_text']='Please Create FeedBack Aspect';
							redirect('misc/feedback/create_feedback/'.$RKK->RKKID.'/'.$Holder);	
						}
						break;
					case 3://disetujui dan final
						
						if($HolderDetail->Chief==2){
							redirect('misc/feedback/create_feedback/'.$RKK->RKKID.'/'.$Holder);
							}

							$FeedbackID = $this->feedback_model->get_Header_byRKKID_row($RKK->RKKID);
						
						if(count($FeedbackID))
						{
							//cek status idp
							switch ($FeedbackID->Status) 
							{
								case 1://belum jadi
										redirect('misc/feedback/create_feedback/'.$RKK->RKKID.'/'.$Holder);
									break;
								case 0://disetujui dan final
									$data_header['notif_text']='Feedback is not active';
									break;		
								default:
									# code...
									break;
							}

						}
						break;		

					case 1://disetujui dan final
						
						if($userDetail->RoleID==4 and $HolderDetail->Chief==2){
							redirect('misc/feedback/create_feedback/'.$RKK->RKKID.'/'.$Holder);
							}

							$FeedbackID = $this->feedback_model->get_Header_byRKKID_row($RKK->RKKID);
						
						if(count($FeedbackID))
						{
							//cek status idp
							switch ($FeedbackID->Status) 
							{
								case 1://belum jadi
										redirect('misc/feedback/create_feedback/'.$RKK->RKKID.'/'.$Holder);
									break;
								case 0://disetujui dan final
									$data_header['notif_text']='Feedback is not active';
									break;		
								default:
									# code...
									break;
							}

						}
						
						break;	
						
					default:
						# code...
						break;
				}
			}else{

					$data_header['notif_text']='Please Create FeedBack Aspect';
			}			
		}
		
		$this->load->view('template/top_1_view');
		$this->load->View('misc/feedback_header_view',$data_header);
		$this->load->view('template/bottom_1_view');
	}
	
	function agreeFeedback()
	{
		$agree=$this->input->post('btnAgree');
		$disagree=$this->input->post('btnDisagree');
		$pointCheck=$this->input->post('checkPoint');
		$pointID=$this->input->post('FeedbackPointID');
		$groupPoint = count($pointCheck);
		
		if($agree=="Agree")
		{
			for ($i = 0; $i<$groupPoint; $i++) {
	    		//echo $pointCheck[$i];
	    		$this->feedback_model->editStatusFlagPoint($pointCheck[$i],1);
			}
		}
		else
		{
			for ($i = 0; $i<$groupPoint; $i++) {
	    		//echo $pointCheck[$i];
	    		$this->feedback_model->editStatusFlagPoint($pointCheck[$i],2);
			}	
		}

		$Periode = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$data_header['Periode']=$Periode;
		$Holder =$this->session->userdata('Holder');
		if($Holder==''){
			$Holder = $this->input->post('SlcPost');
		}
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
			$HolderDetail = $this->account_model->get_Holder_row($HolderID,$isSAP);
			$RKK = $this->rkk_model->get_rkk_byUserPosition_row($this->session->userdata('NIK'),$HolderDetail->PositionID,$Periode->BeginDate,$Periode->EndDate) ;

			redirect('misc/feedback/create_feedback/'.$RKK->RKKID.'/'.$Holder);	
		}
	}

	function create_feedback($RKKID, $Holder)
	{
		$link['add_feedback_aspect']='misc/feedback/create_feedback_aspek/';
		$link['view_subordinateFeedback']='misc/feedback/view_subordinateFeedback/';
		$data['process']='misc/feedback/agreeFeedback/';
		$data_header['link']=$link;
		$data_header['Chief_RKKID']=$RKKID;

		$data['link']=$link;
		$Periode = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$data_header['PositionList_SAP']=$this->account_model->get_Holder_list($this->session->userdata('NIK'),1,$Periode->BeginDate,$Periode->EndDate);
		$data_header['PositionList_nonSAP']=$this->account_model->get_Holder_list($this->session->userdata('NIK'),0,$Periode->BeginDate,$Periode->EndDate);
		

		$isSAP=substr($Holder,0,1);
		$HolderID = substr($Holder, 2);
		$RKK = $this->rkk_model->get_rkk_row($RKKID) ;
		/*if($RKK->statusFlag!=0){
			redirect('objective/idp');
		}
		*/
		$HolderDetail = $this->account_model->get_Holder_row($HolderID,$isSAP);
	
		$data_header['Periode']=$Periode;
		//$data_header['IDP']=$IDP;
		$data_header['RKK']=$RKK;
		$data_header['Holder']=$Holder;
		$this->session->set_userdata('Holder',$Holder);
		$userDetail=$this->account_model->get_User_nik($this->session->userdata('NIK'));
		$data_header['userDetail']=$userDetail;
		$data_header['PositionList_SAP']=$this->account_model->get_Holder_list($this->session->userdata('NIK'),1,$Periode->BeginDate,$Periode->EndDate);
		$data_header['PositionList_nonSAP']=$this->account_model->get_Holder_list($this->session->userdata('NIK'),0,$Periode->BeginDate,$Periode->EndDate);
		$data_header['PositionAssignmentList_SAP']=$this->account_model->get_Assignment_list($this->session->userdata('NIK'),1,$Periode->BeginDate,$Periode->EndDate);
		$data_header['PositionAssignmentList_nonSAP']=$this->account_model->get_Assignment_list($this->session->userdata('NIK'),0,$Periode->BeginDate,$Periode->EndDate);
				
		$data['RoleID']=$userDetail->RoleID;
		$data['TotalFeedBack']=$this->feedback_model->count_Feedback($RKKID);

		if($data['TotalFeedBack']!=0)
		{
			$subordinate=$this->org_model->get_directSubordinate_list($isSAP,$HolderDetail->PositionID,$Periode->BeginDate,$Periode->EndDate);
			$userDetail=$this->account_model->get_User_nik($this->session->userdata('NIK'));
			$data_header['subordinate']=$subordinate;
			$data_header['userDetail']=$userDetail;

			$FeedbackByRKKID=$this->feedback_model->get_Header_byRKKID_row($RKKID)->FeedbackID;
			$FeedbackDetail=$this->feedback_model->get_Detail_list($FeedbackByRKKID);
			$data['FeedbackDetail']=$FeedbackDetail;
			$data['statusFeedback']= $this->feedback_model->get_Header_row($FeedbackByRKKID)->Status;
			$data['TotalFeedback']=count($FeedbackDetail);
			
			if(count($FeedbackDetail)!=0)
			{
				foreach ($FeedbackDetail as $row_FeedbackDetail) 
				{
					$result2[$row_FeedbackDetail->FeedbackDetailID]=$this->feedback_model->get_Feedback_AspectbyID($row_FeedbackDetail->FeedbackAspectID)->FeedbackAspect;	
					$result[$row_FeedbackDetail->FeedbackDetailID]=$this->feedback_model->get_Feedback_AspectList($row_FeedbackDetail->FeedbackDetailID);
				}
				$data['FeedbackAspectAll']=$result2;
				$data['FeedbackDetailAll']=$result;
			}
		}
		else
		{
			$subordinate=$this->org_model->get_directSubordinate_list($isSAP,$HolderDetail->PositionID,$Periode->BeginDate,$Periode->EndDate);
			$userDetail=$this->account_model->get_User_nik($this->session->userdata('NIK'));
			$data_header['subordinate']=$subordinate;
			$data_header['userDetail']=$userDetail;
		}
		
		$this->load->view('template/top_1_view');
		$this->load->view('misc/feedback_header_view',$data_header);
	
		if($userDetail->RoleID==4 and $HolderDetail->Chief==2)
		{//nothing
		}
		else
		{ $this->load->view('misc/feedback_create_view',$data);}
		$this->load->view('template/bottom_1_view');
		$this->load->view('misc/feedback_view_js',$data);
	}
	
	function create_feedback_aspek($RKKID)
	{
		$Periode = $this->general_model->get_ActivePeriode();
		$RKK = $this->rkk_model->get_rkk_row($RKKID);
		$data['title']='Add Feedback Aspect';
		$data['OrgID'] = $this->org_model->get_Position_row($RKK->PositionID,$RKK->isSAP)->OrganizationID;
		$data['FeedbackAspectList']=$this->feedback_model->get_FeedbackAspect_list();
		$data['RKKID']=$RKKID;
		$data['process']='misc/feedback/create_feedback_process';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('misc/feedback_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('misc/feedback_form_js');
		
	}


	function create_feedback_process(){
		$RKKID=$this->input->post('TxtRKKID');
		$FeedbackAspect=$this->input->post('SlcFeedbackAspect');
		$FeedbackPoint=$this->input->post('txtFeedbackPoint');
		$Evidence=$this->input->post('txtEvidence');
		$Cause=$this->input->post('txtCause');
		$AltSolution=$this->input->post('txtAltSolution');
		$DueDate=$this->input->post('TxtDueDate');
		$ActualDate=$this->input->post('TxtActualDate');
		$chkList=$this->input->post('chkList');
		$Notes=$this->input->post('txtNotesADP');
		
		if($this->rkk_model->check_totalRKK($RKKID)==true)
		{
			if(count($this->feedback_model->get_Header_rowbyRKKID($RKKID))==0)
			{
					$FeedbackID = $this->feedback_model->add_Feedback($RKKID)->FeedbackID;
			}
			else
			{
				$FeedbackID = $this->feedback_model->get_Header_rowbyRKKID($RKKID)->FeedbackID;	
			}
		}
		
		$FeedbackDetailID = $this->feedback_model->add_Feedback_Detail($FeedbackID,$FeedbackAspect)->FeedbackDetailID;
		$this->feedback_model->add_Feedback_Point($FeedbackDetailID,$FeedbackPoint,$Evidence,$Cause,$AltSolution,$DueDate,$ActualDate,$Notes,$chkList);
		$data['notif_text']='Success create Feedback Aspect';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}

	function create_feedback_point($FeedbackDetailID)
	{
		//$data['FeedbackAspect']=$this->feedback_model->get_Development_Area_List($Periode->BeginDate,$Periode->EndDate);
		//$data['Development_Program_List']=$this->idp_model->get_DevProgram_list($Periode->BeginDate,$Periode->EndDate);
		$old=$this->feedback_model->get_Detail_row($FeedbackDetailID);
		$data['old']=$old;
		$data['FeedbackAspect'] = $this->feedback_model->get_Feedback_AspectbyID($old->FeedbackAspectID)->FeedbackAspect;
		$data['FeedbackAspectList'] = $this->feedback_model->get_FeedbackAspect_list();

		$data['title']='Add Feedback Point';
		$data['process']='misc/feedback/add_feedback_point_process';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('misc/feedback_point_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('misc/feedback_point_form_js');
		
	}

	function add_feedback_point_process(){
		$FeedbackDetailID=$this->input->post('TxtFeedbackDetailID');
		$FeedbackAspect=$this->input->post('SlcFeedbackAspect');
		$FeedbackPoint=$this->input->post('txtFeedbackPoint');
		$Evidence=$this->input->post('txtEvidence');
		$Cause=$this->input->post('txtCause');
		$AltSolution=$this->input->post('txtAltSolution');
		$DueDate=$this->input->post('TxtDueDate');
		$ActualDate=$this->input->post('TxtActualDate');
		$chkList=$this->input->post('chkList');
		$Notes=$this->input->post('txtNotesADP');
		$this->feedback_model->add_Feedback_Point($FeedbackDetailID,$FeedbackPoint,$Evidence,$Cause,$AltSolution,$DueDate,$ActualDate,$Notes,$chkList);
		$data['notif_text']='Success create feedback point';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}

	public function remove($id)
	{
		$this->feedback_model->remove_feedback_point($id);
		$data['notif_text']='Success remove feedback point';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');	
	}


	function edit($id)
	{
		$old=$this->feedback_model->get_DP_FeedbackPoint_row($id);
		$data['old']=$old;
		$data['FeedbackAspect'] = $this->feedback_model->get_Feedback_AspectbyID($old->FeedbackAspectID)->FeedbackAspect;
		$data['title']='Edit Feedback Point';
		$data['process']='misc/feedback/edit_feedback_point_process';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('misc/feedback_point_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('misc/feedback_point_form_js');
	}

	function edit_feedback_point_process(){
		$FeedbackPointID=$this->input->post('TxtFeedbackPointID');
		$FeedbackPoint=$this->input->post('txtFeedbackPoint');
		$Evidence=$this->input->post('txtEvidence');
		$Cause=$this->input->post('txtCause');
		$AltSolution=$this->input->post('txtAltSolution');
		$DueDate=$this->input->post('TxtDueDate');
		$ActualDate=$this->input->post('TxtActualDate');
		$chkList=$this->input->post('chkList');
		$Notes=$this->input->post('txtNotesADP');
		$StatusPoint=$this->input->post('TxtStatusPoint');
		if($StatusPoint==2)
		{
			$this->feedback_model->edit_DP_FeedbackPoint($FeedbackPointID,$FeedbackPoint,$Evidence,$Cause,$AltSolution,$DueDate,$ActualDate,$Notes,$chkList, NULL);
		}
		else
		{
			$this->feedback_model->edit_DP_FeedbackPoint($FeedbackPointID,$FeedbackPoint,$Evidence,$Cause,$AltSolution,$DueDate,$ActualDate,$Notes,$chkList, $StatusPoint);
		}
		$data['notif_text']='Success update feedback point';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}

	//untuk bawahan

	function view_subordinateFeedback($NIK,$PositionID,$Chief_RKKID)
	{
		$Periode = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$link['add_feedback_aspect']='misc/feedback/create_feedback_aspek/';
		$link['add_feedback_point']='misc/feedback/create_feedback_point/';
		$link['view_subordinateFeedback']='misc/feedback/view_subordinateFeedback/';
		$link['view_self']='misc/feedback/';
		
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
		$data_header['user_bawahan_detail']=$user_bawahan_detail;
		$data_header['PositionName'] = $this->org_model->get_Position_row($PositionID,$userDetail->isSAP)->PositionName;
$data_header['PositionList_SAP']=$this->account_model->get_Holder_list($this->session->userdata('NIK'),1,$Periode->BeginDate,$Periode->EndDate);
		$data_header['PositionList_nonSAP']=$this->account_model->get_Holder_list($this->session->userdata('NIK'),0,$Periode->BeginDate,$Periode->EndDate);
		$data_header['PositionAssignmentList_SAP']=$this->account_model->get_Assignment_list($this->session->userdata('NIK'),1,$Periode->BeginDate,$Periode->EndDate);
		$data_header['PositionAssignmentList_nonSAP']=$this->account_model->get_Assignment_list($this->session->userdata('NIK'),0,$Periode->BeginDate,$Periode->EndDate);		
		if($Holder!=0)
		{
			$isSAP=substr($Holder, 0,1);
			$HolderID=substr($Holder, 2);
			$HolderDetail = $this->account_model->get_Holder_row($HolderID,$isSAP);
			
			$subordinate=$this->org_model->get_directSubordinate_list($isSAP,$PositionID,$Periode->BeginDate,$Periode->EndDate);
			if($subordinate!=false){
				$data_header['subordinate']=$subordinate;
			}
			
			$Bawahan = $this->account_model->get_User_nik($NIK);
			$data['Bawahan']= $Bawahan;
			$Bawahan_RKK = $this->rkk_model->get_rkk_byUserPosition_row($NIK,$PositionID,$Periode->BeginDate,$Periode->EndDate);

			$data['TotalRKK']=count($Bawahan_RKK);
			if(count($Bawahan_RKK)!=0)
			{
				if($this->rkk_model->check_totalRKK($Bawahan_RKK->RKKID)!=true)
				{
					$data_header['notif_text']='RKK not available';
				}
				else
				{
					$Chief_RKK = $this->rkk_model->get_rkk_row($Chief_RKKID);
					$data['Bawahan_RKK']=$Bawahan_RKK;

					if($Bawahan_RKK->statusFlag==1 || $Bawahan_RKK->statusFlag==3)
					{
						$TotalFeedBack=$this->feedback_model->count_Feedback($Bawahan_RKK->RKKID);
						$data['TotalFeedBack']=$TotalFeedBack;

						if($TotalFeedBack!=0)
						{//cek rkk bawahan
							$Bawahan_RKKID = $Bawahan_RKK->RKKID;
							$Bawahan_RKKPositionID = $Bawahan_RKK->RKKPositionID; 
							$FeedbackByRKKID=$this->feedback_model->get_Header_byRKKID_row($Bawahan_RKKID)->FeedbackID;
							$FeedbackDetail=$this->feedback_model->get_Detail_list($FeedbackByRKKID);
							$data['FeedbackDetail']=$FeedbackDetail;
							$data['statusFeedback']= $this->feedback_model->get_Header_row($FeedbackByRKKID)->Status;
							$data['TotalFeedback']=count($FeedbackDetail);
							
							if(count($FeedbackDetail)!=0)
							{
								foreach ($FeedbackDetail as $row_FeedbackDetail) 
								{
									$result2[$row_FeedbackDetail->FeedbackDetailID]=$this->feedback_model->get_Feedback_AspectbyID($row_FeedbackDetail->FeedbackAspectID)->FeedbackAspect;	
									$result[$row_FeedbackDetail->FeedbackDetailID]=$this->feedback_model->get_Feedback_AspectList($row_FeedbackDetail->FeedbackDetailID);
								}
								$data['FeedbackAspectAll']=$result2;
								$data['FeedbackDetailAll']=$result;
							}
						}
					}
					elseif($Bawahan_RKK->statusFlag==0 || $Bawahan_RKK->statusFlag==2)
					{
						$TotalFeedBack=$this->feedback_model->count_Feedback($Bawahan_RKK->RKKID);
						$data['TotalFeedBack']=$TotalFeedBack;

						if($TotalFeedBack!=0)
						{//cek rkk bawahan
							$Bawahan_RKKID = $Bawahan_RKK->RKKID;
							$Bawahan_RKKPositionID = $Bawahan_RKK->RKKPositionID; 
							$FeedbackByRKKID=$this->feedback_model->get_Header_byRKKID_row($Bawahan_RKKID)->FeedbackID;
							$FeedbackDetail=$this->feedback_model->get_Detail_list($FeedbackByRKKID);
							$data['FeedbackDetail']=$FeedbackDetail;
							$data['statusFeedback']= $this->feedback_model->get_Header_row($FeedbackByRKKID)->Status;
							$data['TotalFeedback']=count($FeedbackDetail);
							
							if(count($FeedbackDetail)!=0)
							{
								foreach ($FeedbackDetail as $row_FeedbackDetail) 
								{
									$result2[$row_FeedbackDetail->FeedbackDetailID]=$this->feedback_model->get_Feedback_AspectbyID($row_FeedbackDetail->FeedbackAspectID)->FeedbackAspect;	
									$result[$row_FeedbackDetail->FeedbackDetailID]=$this->feedback_model->get_Feedback_AspectList($row_FeedbackDetail->FeedbackDetailID);
								}
								$data['FeedbackAspectAll']=$result2;
								$data['FeedbackDetailAll']=$result;
							}
						}
					}
					else
					{
					//$data_header['notif_text']='RKK not available, please add rkk...';
					}
				}
			}
			else
			{
				$data_header['notif_text']='RKK not available';
			}

		}

		$data['link']=$link;
		$data_header['action']='misc/feedback';

		$this->load->view('template/top_1_view');
		$this->load->view('misc/feedback_header_view',$data_header);
		$this->load->view('misc/feedback_subordinate_view',$data);
		$this->load->view('template/bottom_1_view');
		$this->load->view('misc/feedback_view_js');

	}

	function create_idp_bawahan($RKKID)
	{
		$Periode = $this->general_model->get_ActivePeriode();
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