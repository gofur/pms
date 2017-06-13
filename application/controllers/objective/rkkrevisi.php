<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Rkkrevisi extends Controller {
	function __construct(){
		parent::__construct();
		if(!$this->session->userdata('loginFlag')){
			redirect('account/login');
		}
		$this->load->model('revision_rkk_model');
		$this->load->model('rkk_model');
		$this->load->model('rkk_model3');
		$this->load->model('general_model');
		$this->load->model('org_model');
		$this->load->model('account_model');
	}
	function index(){

		$period = $this->general_model->get_Period_row($this->session->userdata('active_period'));
	
		$user_id      = $this->session->userdata('userID');
		$nik          = $this->session->userdata('NIK');
		
		$user_dtl     = $this->account_model->get_User_byNIK($nik);
		
		$filter_start = $period->BeginDate;
		$filter_end   = $period->EndDate;
		$data['filter_start'] = substr($filter_start, 0,10);
		$data['filter_end']   = substr($filter_end, 0,10);
	

		$data['period']   = $period;

		$data['user_dtl'] = $user_dtl;

		$data['post_ls_SAP']      = $this->account_model->get_Holder_list($nik,1,$filter_start,$filter_end);
		$data['post_ls_nonSAP']   = $this->account_model->get_Holder_list($nik,0,$filter_start,$filter_end);
		$data['assign_ls_SAP']    = $this->account_model->get_Assignment_list($nik,1,$filter_start,$filter_end);
		$data['assign_ls_nonSAP'] = $this->account_model->get_Assignment_list($nik,0,$filter_start,$filter_end);

		$this->load->view('objective/rkk_revisi/main_view', $data);

	}

	function confirmation()
	{

		$Periode = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$confirmation=$this->input->post("confirm");
		//CHECK APABILA CONFIRM YES=1 MAKA FLAG STATUS SEMUA TURUNAN JUGA DIGANTI FLAG STATUSNYA MENJADI 0
		// APABILA TIDAK CONFIRM NO=0 MAKA BALIK KE HALAMAN HOME
		if($confirmation==1)
		{ 
			$Holder =$this->session->userdata('Holder');
			$data_header['Holder']=$Holder;
			$userDetail=$this->account_model->get_User_row($this->session->userdata('userID'));

			$data_header['Periode']=$Periode;
			$data_header['userDetail']=$userDetail;
			$data_header['PositionList_SAP']=$this->account_model->get_Holder_list($this->session->userdata('NIK'),1,$Periode->BeginDate,$Periode->EndDate);
			$data_header['PositionList_nonSAP']=$this->account_model->get_Holder_list($this->session->userdata('NIK'),0,$Periode->BeginDate,$Periode->EndDate);
			

			$isSAP=substr($Holder, 0,1);
			$HolderID=substr($Holder, 2);
			
			$HolderDetail = $this->account_model->get_Holder_row($HolderID,$isSAP);	

			
			if($this->session->userdata('roleID')==4){
				//GET DATA RKKID dan IDPID
				$RKK_Self= $this->revision_rkk_model->get_rkk_byUserPosition_row($userDetail->NIK,$HolderDetail->PositionID,$Periode->BeginDate,$Periode->EndDate);	
				$IDP_Self= $this->revision_rkk_model->get_idp_by_rkk($RKK_Self->RKKID,$Periode->BeginDate,$Periode->EndDate);	
				//UPDATE RKK DAN IDP
				$this->revision_rkk_model->edit_rkk_status($RKK_Self->RKKID, 0);
				$this->revision_rkk_model->edit_idp_status($IDP_Self->IDPID, 0);
			}


			$subordinate=$this->org_model->get_directSubordinate_list($isSAP,$HolderDetail->PositionID,$Periode->BeginDate,$Periode->EndDate);
			if($subordinate!=false){
				$data_header['subordinate']=$subordinate;
			}


			foreach ($subordinate as $row) 
			{
				$RKK_Bawahan = $this->revision_rkk_model->get_rkk_byUserPosition_row($row->NIK,$row->PositionID,$Periode->BeginDate,$Periode->EndDate);	
				
				if(count($RKK_Bawahan)!=0)
				{
					if($RKK_Bawahan->statusFlag!=0)
					{
						$IDP_Bawahan = $this->revision_rkk_model->get_idp_by_rkk($RKK_Bawahan->RKKID,$Periode->BeginDate,$Periode->EndDate);		
						
						//update Status Flag Anak Buah yang dibawahnya menjadi terupdate 
						$this->revision_rkk_model->edit_rkk_status($RKK_Bawahan->RKKID, 0);
						$this->revision_rkk_model->edit_idp_status($IDP_Bawahan->IDPID, 0);
					}
					else
					{
						$IDP_Bawahan = $this->revision_rkk_model->get_idp_by_rkk($RKK_Bawahan->RKKID,$Periode->BeginDate,$Periode->EndDate);	
						$this->revision_rkk_model->edit_idp_status($IDP_Bawahan->IDPID, 0);		
					}


				}
				

			}

			// link ke rkk karena sudah update status
			redirect('objective/rkk/');
			
		}
		else
		{
			redirect('home');
		}
	}


	function confirmation_single($UserID, $PositionID,$filter_start, $filter_end)
	{
		$Periode = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		if($filter_start!='')
		{
			$filter_start = substr($filter_start, 0,10);
			$filter_end = substr($filter_end, 0,10);
		}
		else
		{
			$filter_start = $Periode->BeginDate;
			$filter_end = $Periode->EndDate;
		}

		$confirmation=$this->input->post("confirm");
		//CHECK APABILA CONFIRM YES=1 MAKA FLAG STATUS SEMUA TURUNAN JUGA DIGANTI FLAG STATUSNYA MENJADI 0
		// APABILA TIDAK CONFIRM NO=0 MAKA BALIK KE HALAMAN HOME
		if($confirmation==1)
		{ 

			
			$userDetail=$this->account_model->get_User_row($UserID);
			$Holder=$this->account_model->get_Holder_row_byNIK($userDetail->NIK, $userDetail->isSAP,$filter_start,$filter_end)->HolderID;

			$data_header['Holder']=$Holder;
			$this->session->set_userdata('Holder',$Holder);
			

			$data_header['Periode']=$Periode;
			$data_header['userDetail']=$userDetail;
			$data_header['PositionList_SAP']=$this->account_model->get_Holder_list($this->session->userdata('NIK'),1,$filter_start,$filter_end);
			$data_header['PositionList_nonSAP']=$this->account_model->get_Holder_list($this->session->userdata('NIK'),0,$filter_start,$filter_end);
			
			//$isSAP=substr($Holder, 0,1);
			//$HolderID=substr($Holder, 2);
			$HolderDetail = $this->account_model->get_Holder_row($Holder,$userDetail->isSAP);	
			//if($this->session->userdata('roleID')==4){
				//GET DATA RKKID dan IDPID
				//$RKK_Self= $this->revision_rkk_model->get_rkk_byUserPosition_row($userDetail->NIK,$HolderDetail->PositionID,$Periode->BeginDate,$Periode->EndDate);	
				//$IDP_Self= $this->revision_rkk_model->get_idp_by_rkk($RKK_Self->RKKID,$Periode->BeginDate,$Periode->EndDate);	
				//UPDATE RKK DAN IDP
				//$this->revision_rkk_model->edit_rkk_status($RKK_Self->RKKID, 0);
				//$this->revision_rkk_model->edit_idp_status($IDP_Self->IDPID, 0);
			//}

			$RKK_Bawahan = $this->revision_rkk_model->get_rkk_byUserPosition_row($userDetail->NIK,$PositionID,$filter_start,$filter_end);	
			$IDP_Bawahan = $this->revision_rkk_model->get_idp_by_rkk($RKK_Bawahan->RKKID,$filter_start,$filter_end);		
						


			//update Status Flag Anak Buah yang dibawahnya menjadi terupdate 
			$this->revision_rkk_model->edit_rkk_status($RKK_Bawahan->RKKID, 0);
			$this->revision_rkk_model->edit_idp_status($IDP_Bawahan->IDPID, 0);

			// link ke rkk karena sudah update status
			redirect('objective/rkk/');
			
		}
		else
		{
			redirect('home');
		}
	}

	public function show_subordinate()
	{
		$sess_nik     = $this->session->userdata("NIK");
		$nik          = $this->input->post('nik');
		$holder       = $this->input->post('holder');
		$filter_start = $this->input->post('start');
		$filter_end   = $this->input->post('end');
		$filter_start .= ' 00:00:00.000';
		$filter_end   .= ' 23:59:59.999';

		if ($nik == '') {
			$nik = $sess_nik;
		}
		 

		if ($holder == '') {
			$is_sap  = $this->input->post('is_sap');
			$post_id = $this->input->post('post_id');
		} else {
			list($is_sap,$holder_id) = explode('.',$holder);
			$holder_dtl = $this->account_model->get_Holder_row($holder_id,$is_sap);
			$post_id    = $holder_dtl->PositionID;
		}

		$sub_ls       = $this->org_model->get_directSubordinate_list($is_sap,$post_id,$filter_start,$filter_end);
		if (count($sub_ls)) {
			$count_rkk = $this->rkk_model3->count_rkk_holder($nik,$post_id,$is_sap, $filter_start, $filter_end,'approve');
			$base_link = 'objective/rkkrevisi/view_subordinate/';
			$link = array();
			if ($count_rkk > 0) {
				$rkk = $this->rkk_model3->get_rkk_holder_last($nik,$post_id,$is_sap, $filter_start, $filter_end,'approve');
				$begin = $this->input->post('start');
				$end = $this->input->post('end');

				foreach ($sub_ls as $sub) {
					$key = $sub->NIK.'|'.$sub->isSAP.'|'.$sub->PositionID;
					$param = $rkk->RKKID.'/'.$sub->NIK.'/'.$sub->PositionID.'/'.$sub->isSAP.'/'.$begin.'/'.$end;
					$link[$key] = $base_link.$param;
				}
				$data['link'] = $link;
			}
			$data['sub_ls']   = $sub_ls;


			$this->load->view('template/subordinate_view', $data, FALSE);
		}
	}


	public function check_rkk()
	{
		$sess_nik     = $this->session->userdata("NIK");
		$nik          = $this->input->post('nik');
		$holder       = $this->input->post('holder');

		
		$filter_start = $this->input->post('start');
		$filter_end   = $this->input->post('end');
		$filter_start .= ' 00:00:00.000';
		$filter_end 	.= ' 23:59:59.999';

		if ($nik == '') {
			$nik = $sess_nik;
		}

		if ($holder == '') {
			$is_sap  = $this->input->post('is_sap');
			$post_id = $this->input->post('post_id');
		} else {
			list($is_sap,$holder_id) = explode('.',$holder);
			$holder_dtl = $this->account_model->get_Holder_row($holder_id,$is_sap);
			$post_id    = $holder_dtl->PositionID;
		}

		//set session holder
		$this->session->set_userdata('Holder',$holder);
		
		$count_rkk = $this->rkk_model3->count_rkk_holder($nik,$post_id,$is_sap, $filter_start, $filter_end,'all');
		if ($count_rkk == 0) {
			$data['notif_type'] = 'alert-error';
			$data['notif_text'] = 'RKK not available';
			$this->load->view('template/notif_view', $data, FALSE);
		} else {
			$sub_ls = $this->org_model->get_directSubordinate_list($is_sap,$post_id,$filter_start,$filter_end);
			$rkk = $this->rkk_model3->get_rkk_holder_last($nik,$post_id,$is_sap, $filter_start, $filter_end,'all');

			if (count($sub_ls)) {
				
				if ($sess_nik == $nik ) {
					$count_rkk = $this->rkk_model3->count_rkk_holder($nik,$post_id,$is_sap, $filter_start, $filter_end,'all');
					
					if ($count_rkk == 0) {
						$data['notif_type'] = 'alert-error';
						$data['notif_text'] = 'RKK not FINAL';
						$this->load->view('template/notif_view', $data, FALSE);
					} else {
						$rkk = $this->rkk_model3->get_rkk_holder_last($nik,$post_id,$is_sap, $filter_start, $filter_end,'all');

						switch ($rkk->statusFlag) {
							case 1: // Agreement
								redirect('objective/agreement/view/'.$rkk->RKKID);
								break;	
							case 3: // Final
								redirect('objective/rkkrevisi/cascade/'.$rkk->RKKID);
								break;
							default:
								$data['notif_type'] = 'alert-error';
								$data['notif_text'] = 'RKK not FINAL';
								$this->load->view('template/notif_view', $data, FALSE);
								break;
						}
					}

				} else{
					redirect('objective/rkkrevisi/cascade/'.$rkk->RKKID);

				}

			} else {
				$rkk = $this->rkk_model3->get_rkk_holder_last($nik,$post_id,$is_sap, $filter_start, $filter_end,'all'); 

				if($sess_nik != $nik) {
					redirect('objective/rkkrevisi/view/'.$rkk->RKKID);
				} else {
					switch ($rkk->statusFlag) {
						case 0:
							# DRAFT

							$data['notif_type'] = 'alert-error';
							$data['notif_text'] = 'RKK not Final';
							$this->load->view('template/notif_view', $data, FALSE);

							break;
						case 1:
							# ASSIGN
							redirect('objective/agreement/view/'.$rkk->RKKID);

							break;
						case 2:
							# REJECT

							$data['notif_type'] = 'alert-error';
							$data['notif_text'] = 'RKK in review';
							$this->load->view('template/notif_view', $data, FALSE);
							
							break;
						case 3:
							# AGREE
							redirect('objective/rkkrevisi/view/'.$rkk->RKKID.'/'.$post_id.'/'.$filter_start.'/'.$filter_end);
							break;
					}
					
				}
				
			}
			
		}
	}

	public function check_rkk_subordinate()
	{
		$sess_nik     = $this->session->userdata("NIK");
		$nik          = $this->input->post('nik');
		$holder       = $this->input->post('holder');
		$filter_start = $this->input->post('start');
		$filter_end   = $this->input->post('end');
		$filter_start .= ' 00:00:00.000';
		$filter_end 	.= ' 23:59:59.999';

		if ($nik == '') {
			$nik = $sess_nik;
		}

		if ($holder == '') {
			$is_sap  = $this->input->post('is_sap');
			$post_id = $this->input->post('post_id');
		} else {
			list($is_sap,$holder_id) = explode('.',$holder);
			$holder_dtl = $this->account_model->get_Holder_row($holder_id,$is_sap);
			$post_id    = $holder_dtl->PositionID;
		}
		
		$count_rkk = $this->rkk_model3->count_rkk_holder($nik,$post_id,$is_sap, $filter_start, $filter_end,'all');
		if ($count_rkk == 0) {
			$data['notif_type'] = 'alert-error';
			$data['notif_text'] = 'RKK not available';
			$this->load->view('template/notif_view', $data, FALSE);
		} else {
			$sub_ls = $this->org_model->get_directSubordinate_list($is_sap,$post_id,$filter_start,$filter_end);
			$rkk = $this->rkk_model3->get_rkk_holder_last($nik,$post_id,$is_sap, $filter_start, $filter_end,'all');

			if (count($sub_ls)) {
				
				if ($sess_nik == $nik ) {
					$count_rkk = $this->rkk_model3->count_rkk_holder($nik,$post_id,$is_sap, $filter_start, $filter_end,'all');
					
					if ($count_rkk == 0) {
						$data['notif_type'] = 'alert-error';
						$data['notif_text'] = 'RKK not FINAL';
						$this->load->view('template/notif_view', $data, FALSE);
					} else {
						$rkk = $this->rkk_model3->get_rkk_holder_last($nik,$post_id,$is_sap, $filter_start, $filter_end,'all');

						switch ($rkk->statusFlag) {
							case 1: // Agreement
								redirect('objective/agreement/view/'.$rkk->RKKID);
								break;	
							case 3: // Final
								redirect('objective/rkkrevisi/cascade_sub_ordinate/'.$rkk->RKKID.'/'.$nik.'/'.$post_id.'/'.$filter_start.'/'.$filter_end);
								break;
							default:
								$data['notif_type'] = 'alert-error';
								$data['notif_text'] = 'RKK not FINAL';
								$this->load->view('template/notif_view', $data, FALSE);
								break;
						}
					}

				} else{
					redirect('objective/rkkrevisi/cascade_sub_ordinate/'.$rkk->RKKID.'/'.$nik.'/'.$post_id.'/'.$filter_start.'/'.$filter_end);

				}

			} else {
				$rkk = $this->rkk_model3->get_rkk_holder_last($nik,$post_id,$is_sap, $filter_start, $filter_end,'all'); 

				if($sess_nik != $nik) {
					redirect('objective/rkkrevisi/view/'.$rkk->RKKID.'/'.$post_id.'/'.$filter_start.'/'.$filter_end);
				} else {
					switch ($rkk->statusFlag) {
						case 0:
							# DRAFT

							$data['notif_type'] = 'alert-error';
							$data['notif_text'] = 'RKK not Final';
							$this->load->view('template/notif_view', $data, FALSE);

							break;
						case 1:
							# ASSIGN
							redirect('objective/agreement/view/'.$rkk->RKKID);

							break;
						case 2:
							# REJECT

							$data['notif_type'] = 'alert-error';
							$data['notif_text'] = 'RKK in review';
							$this->load->view('template/notif_view', $data, FALSE);
							
							break;
						case 3:
							# AGREE
							redirect('objective/rkkrevisi/view/'.$rkk->RKKID.'/'.$post_id.'/'.$filter_start.'/'.$filter_end);
							break;
					}
					
				}
				
			}
			
		}
	}

	public function view($rkk_id, $post_id,$filter_start,$filter_end)
	{
		$self_rkk     = $this->rkk_model3->get_rkk_row($rkk_id);
		$rel_rkk      = $this->rkk_model3->get_rkk_rel_last($rkk_id,$self_rkk->BeginDate,$self_rkk->EndDate);
		$persp_ls     = $this->general_model->get_Perspective_List($self_rkk->BeginDate,$self_rkk->EndDate);
		$so_ls        = array();
		$persp_weight = array();
		$spr_person   = $this->account_model->get_User_byNIK($rel_rkk->chief_nik);
		$data['rkk_id']     = $rkk_id;
		$data['persp_ls']   = $persp_ls;
		$data['rkk']        = $self_rkk;
		$data['spr_person'] = $spr_person;
		$data['spr_post']   = $this->org_model->get_Position_row($rel_rkk->chief_post_id,$rel_rkk->chief_is_sap,$rel_rkk->BeginDate,$rel_rkk->EndDate);
		$user_dtl     = $this->account_model->get_User_byNIK($self_rkk->NIK);
		if ($self_rkk->NIK != $this->session->userdata('NIK') && ($self_rkk->statusFlag == 0 OR $self_rkk->statusFlag == 2) ) {
			$data['link_create']     = 'objective/rkkrevisi/create_so/'.$rkk_id.'/';
			$data['link_create_kpi'] = 'objective/rkkrevisi/create_kpi/'.$rkk_id.'/';
			$data['link_edit']    = 'objective/rkkrevisi/edit_so/';
			$data['link_remove']  = 'objective/rkkrevisi/remove_so/';
		}


		foreach ($persp_ls as $persp) {
			$so_ls[$persp->PerspectiveID] = $this->rkk_model3->get_so_persp_list($rkk_id,$persp->PerspectiveID,$self_rkk->BeginDate,$self_rkk->EndDate);

			$persp_weight[$persp->PerspectiveID] = $this->rkk_model3->sum_weight_persp($rkk_id,$persp->PerspectiveID,$self_rkk->BeginDate,$self_rkk->EndDate);		
		}
		$data['so_ls']       = $so_ls;
		$data['persp_weight'] = $persp_weight;
		$data['action_confirmation']='objective/rkkrevisi/confirmation_single/'.$user_dtl->UserID.'/'.$post_id.'/'.$filter_start.'/'.$filter_end;
		$this->load->View('objective/revision_rkk_confirmation_single',$data);
		$this->load->view('objective/rkk_revisi/rkk_revisi_subd_view',$data);
	}





	public function show_kpi()
	{
		$sess_nik = $this->session->userdata("NIK");
		$rkk_id = $this->input->post('rkk_id');
		$so_id  = $this->input->post('so_id');
		$begin  = $this->input->post('begin');
		$end    = $this->input->post('end').' 23:59:59.999';
		$kpi_ls = $this->rkk_model3->get_kpi_so_list($rkk_id,$so_id,$begin,$end);
		$rkk 		= $this->rkk_model3->get_rkk_row($rkk_id);
		if (count($kpi_ls)) {
			$data['kpi_ls'] = $kpi_ls;
			if ($sess_nik == $rkk->NIK) {
				$data['link_cascade'] = 'objective/rkk/cascade_kpi/';
				$data['link_rel']    	= 'objective/rkk/relation_kpi_AB/';
				if ($rkk->statusFlag == 0 && $this->session->userdata("roleID")==4) {
					$data['link_remove'] = 'objective/rkk/remove_kpi/';
					$data['link_edit']   = 'objective/rkk/edit_kpi/';
				}
				
			} else {
				if ($rkk->statusFlag != 1 AND $rkk->statusFlag != 3) {
					$data['link_remove'] = 'objective/rkk/remove_kpi/';
					$data['link_edit']   = 'objective/rkk/edit_kpi/';
				}
					
			}
			$data['link_detail']  = 'objective/rkk/detail_kpi/';
			$this->load->view('objective/rkk/kpi_list', $data, FALSE);
			
		} else {
			$data['notif_type'] = '';
			$data['notif_text'] = 'This SO doesn&#39;t have KPI';
			$this->load->view('template/notif_view', $data, FALSE);
		}
	}


	public function view_subordinate($chief_rkk_id,$nik,$post,$is_sap,$begin,$end)
	{
		$user_dtl     = $this->account_model->get_User_byNIK($nik);
		$filter_start = $begin;
		$filter_end   = $end;
		$post 				= $this->org_model->get_Position_row($post,$is_sap,$begin,$end);
		$data['post']					= $post; 
		$data['link_self']    = 'objective/rkkrevisi/';
		$data['filter_start'] = substr($filter_start, 0,10);
		$data['filter_end']   = substr($filter_end, 0,10);
		$data['user_dtl']     = $user_dtl;
		$this->load->view('objective/rkk_revisi/subordinate_view', $data);
	}



	public function cascade($rkk_id)
	{
		$data['action_confirmation']='objective/rkkrevisi/confirmation/';
		$self_rkk     = $this->rkk_model3->get_rkk_row($rkk_id);
		$rel_rkk			= $this->rkk_model3->get_rkk_rel_last($rkk_id,$self_rkk->BeginDate,$self_rkk->EndDate);
		$persp_ls     = $this->general_model->get_Perspective_List($self_rkk->BeginDate,$self_rkk->EndDate);
		$so_ls        = array();
		$persp_weight = array();
		if (count($rel_rkk)) {
			$spr_person   = $this->account_model->get_User_byNIK($rel_rkk->chief_nik);
			$data['spr_person'] = $spr_person;
			$data['spr_post']   = $this->org_model->get_Position_row($rel_rkk->chief_post_id,$rel_rkk->chief_is_sap,$rel_rkk->BeginDate,$rel_rkk->EndDate);
		}
		$data['rkk_id']     = $rkk_id;
		$data['persp_ls']   = $persp_ls;
		$data['rkk']        = $self_rkk;
		


		if ($self_rkk->NIK != $this->session->userdata('NIK') && ($self_rkk->statusFlag == 0 OR $self_rkk->statusFlag == 2) ) {
			$data['link_create']     = 'objective/rkk/create_so/'.$rkk_id.'/';
			$data['link_create_kpi'] = 'objective/rkk/create_kpi/'.$rkk_id.'/';
			$data['link_edit']    = 'objective/rkk/edit_so/';
			$data['link_remove']  = 'objective/rkk/remove_so/';
		} else if( $this->session->userdata("roleID") == 4 && $self_rkk->NIK == $this->session->userdata('NIK')) {
			$data['link_create']     = 'objective/rkk/create_so/'.$rkk_id.'/';
			$data['link_create_kpi'] = 'objective/rkk/create_kpi/'.$rkk_id.'/';
			$data['link_edit']    = 'objective/rkk/edit_so/';
			$data['link_remove']  = 'objective/rkk/remove_so/';
		}

		foreach ($persp_ls as $persp) {
			$so_ls[$persp->PerspectiveID] = $this->rkk_model3->get_so_persp_list($rkk_id,$persp->PerspectiveID,$self_rkk->BeginDate,$self_rkk->EndDate);

			$persp_weight[$persp->PerspectiveID] = $this->rkk_model3->sum_weight_persp($rkk_id,$persp->PerspectiveID,$self_rkk->BeginDate,$self_rkk->EndDate);		
		}
		$data['so_ls']       = $so_ls;
		$data['persp_weight'] = $persp_weight;
		$this->load->View('objective/revision_rkk_confirmation',$data);
		$this->load->view('objective/rkk/rkk_casd_view',$data);
	}


	public function cascade_sub_ordinate($rkk_id, $nik, $post_id, $filter_start, $filter_end)
	{
		$user_dtl     = $this->account_model->get_User_byNIK($nik);
		$self_rkk     = $this->rkk_model3->get_rkk_row($rkk_id);
		$rel_rkk			= $this->rkk_model3->get_rkk_rel_last($rkk_id,$self_rkk->BeginDate,$self_rkk->EndDate);
		$persp_ls     = $this->general_model->get_Perspective_List($self_rkk->BeginDate,$self_rkk->EndDate);
		$so_ls        = array();
		$persp_weight = array();
		$spr_person   = $this->account_model->get_User_byNIK($rel_rkk->chief_nik);
		$data['rkk_id']     = $rkk_id;
		$data['persp_ls']   = $persp_ls;
		$data['rkk']        = $self_rkk;
		$data['spr_person'] = $spr_person;
		$data['spr_post']   = $this->org_model->get_Position_row($rel_rkk->chief_post_id,$rel_rkk->chief_is_sap,$rel_rkk->BeginDate,$rel_rkk->EndDate);


		if ($self_rkk->NIK != $this->session->userdata('NIK') && ($self_rkk->statusFlag == 0 OR $self_rkk->statusFlag == 2) ) {
			$data['link_create']     = 'objective/rkk/create_so/'.$rkk_id.'/';
			$data['link_create_kpi'] = 'objective/rkk/create_kpi/'.$rkk_id.'/';
			$data['link_edit']    = 'objective/rkk/edit_so/';
			$data['link_remove']  = 'objective/rkk/remove_so/';
		} else if( $this->session->userdata("roleID") == 4 && $self_rkk->NIK == $this->session->userdata('NIK')) {
			$data['link_create']     = 'objective/rkk/create_so/'.$rkk_id.'/';
			$data['link_create_kpi'] = 'objective/rkk/create_kpi/'.$rkk_id.'/';
			$data['link_edit']    = 'objective/rkk/edit_so/';
			$data['link_remove']  = 'objective/rkk/remove_so/';
		}

		foreach ($persp_ls as $persp) {
			$so_ls[$persp->PerspectiveID] = $this->rkk_model3->get_so_persp_list($rkk_id,$persp->PerspectiveID,$self_rkk->BeginDate,$self_rkk->EndDate);

			$persp_weight[$persp->PerspectiveID] = $this->rkk_model3->sum_weight_persp($rkk_id,$persp->PerspectiveID,$self_rkk->BeginDate,$self_rkk->EndDate);		
		}
		$data['so_ls']       = $so_ls;
		$data['persp_weight'] = $persp_weight;

		$data['action_confirmation']='objective/rkkrevisi/confirmation_single/'.$user_dtl->UserID.'/'.$post_id.'/'.$filter_start.'/'.$filter_end;
		$this->load->View('objective/revision_rkk_confirmation_single',$data);
		$this->load->view('objective/rkk/rkk_casd_view',$data);
	}


	function cascade_kpi_1($RKKDetailID)
	{
		$Periode = $this->general_model->get_Period_row($this->session->userdata('active_period')); 
		$Chief_KPI = $this->revision_rkk_model->get_rkkDetail_row($RKKDetailID);
		$Subordinate=$this->org_model->get_directSubordinate_list($Chief_KPI->isSAP,$Chief_KPI->PositionID,$Periode->BeginDate,$Periode->EndDate);
		$data['KPI_head']=$this->revision_rkk_model->get_KPI_row($Chief_KPI->KPIID);
		$data['Chief_KPI']=$Chief_KPI;
		$data['process']='objective/rkkrevisi/cascade_kpi_2/'.$RKKDetailID;
		$data['Subordinate']=$Subordinate;
		$this->load->view('template/top_popup_1_view');
		$this->load->view('objective/kpi_cascade_subordinate_form',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
	function cascade_kpi_2($RKKDetailID)
	{
		$Periode = $this->general_model->get_Period_row($this->session->userdata('active_period')); 
		$Chief_KPI = $this->revision_rkk_model->get_rkkDetail_row($RKKDetailID);
		$Subordinate_exception = $this->org_model->get_Exception_Subordinate_list($Chief_KPI->PositionID,$Periode->BeginDate,$Periode->EndDate);
		if(count($Subordinate_exception)>0){
			
		}else{
			$Subordinate=$this->org_model->get_directSubordinate_list($Chief_KPI->isSAP,$Chief_KPI->PositionID,$Periode->BeginDate,$Periode->EndDate);
		}
		$data['genericKPI']=$this->general_model->get_GenericKPI_List($Periode->BeginDate,$Periode->EndDate);
		$data['Reference_list']=$this->general_model->get_Reference_List($Periode->BeginDate,$Periode->EndDate);
		$data['Unit_list']=$this->general_model->get_Satuan_list(date('Y-m-d'),$Periode->EndDate);
		$data['Formula_list']=$this->general_model->get_PCFormula_list(0,'',date('Y-m-d'),$Periode->EndDate);
		$data['Ytd_list']=$this->general_model->get_YTD_list(date('Y-m-d'),$Periode->EndDate);
		$data['KPI_head']=$this->revision_rkk_model->get_KPI_row($Chief_KPI->KPIID);
		$data['Chief_KPI']=$Chief_KPI;
		$data['process']='objective/rkkrevisi/cascade_kpi_process';
		$i=0;
		foreach ($Subordinate as $row) {
			$temp=$this->input->post('ChkSubordinate_'.$row->UserID);
			if($temp==1){
				$subordinate_cascade[$i]['UserID']=$row->UserID;
				$subordinate_cascade[$i]['Fullname']=$row->Fullname;
				$subordinate_cascade[$i]['NIK']=$row->NIK;
				$subordinate_cascade[$i]['PositionID']=$row->PositionID;
				$subordinate_cascade[$i]['isSAP']=$row->isSAP;

				$subordinate_cascade[$i]['KPI_Num']=$this->input->post('TxtKPI_Num_'.$row->UserID);
				$i++;
			}
		}
		$data['subordinate_num']=$i;
		$data['subordinate']=$subordinate_cascade;
		$this->load->view('template/top_popup_1_view');
		$this->load->view('objective/kpi_cascade_kpi_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('objective/kpi_cascade_form_js',$data);

	}
	function cascade_kpi_process()
	{
		$Periode = $this->general_model->get_Period_row($this->session->userdata('active_period'));

		$Chief_RKKDetailID = $this->input->post('TxtChief_RKKDetailID');
		$Subordinate_Num = $this->input->post('TxtSubordinate_num');
		$ReferenceID=$this->input->post('SlcRef');
		$Chief = $this->revision_rkk_model->get_rkkDetail_row($Chief_RKKDetailID);
		for ($i=0; $i <$Subordinate_Num ; $i++) { 
			$UserID= $this->input->post('TxtUserID_'.$i);
			$PositionID=$this->input->post('TxtPositionID_'.$i);
			$isSAP=$this->input->post('TxtisSAP_'.$i);
			$KPI_Num=$this->input->post('TxtNum_'.$i);
			$userDetail=$this->account_model->get_User_row($UserID);			
			//Periksa RKK
			$RKK=$this->revision_rkk_model->get_rkk_byUserPosition_row($userDetail->NIK,$PositionID,$Periode->BeginDate,$Periode->EndDate);

			if (count($RKK)==0){//Jika belum ada, buat RKK Baru
				$RKKPositionID = $this->revision_rkk_model->add_rkkPosition($PositionID,$isSAP,$Periode->BeginDate,$Periode->EndDate)->RKKPositionID;
				$RKK = $this->revision_rkk_model->add_rkk($RKKPositionID,$UserID,$PositionID,$Chief->PositionID,0,$isSAP,$Chief->isSAP,$Periode->BeginDate,$Periode->EndDate)->RKKID;
				$RKKID = $RKK->RKKID;
				$RKK_Status = $RKK->statusFlag;

			}else{//Jika sudah ada, Ambil RKK ID nya
				$RKKID = $RKK->RKKID;
				$RKK_Status = $RKK->statusFlag;
				$RKKPositionID = $RKK->RKKPositionID;
			}

			if ($RKK_Status==0 OR $RKK_Status==2)
			{
				for($x=0;$x<$KPI_Num;$x++)
				{
					if($ReferenceID==3)
					{
						$RefWeight=$this->input->post('TxtRW_'.$i.'_'.$x);
					}
					else
					{
						$RefWeight=0;
					}
					$GenericKPIID=$this->input->post('SlcGenKPI_'.$i.'_'.$x);
					if($GenericKPIID=='other')
					{
						$KPI_Name=$this->input->post('TxtKPIName_'.$i.'_'.$x);
						$KPI_Desc=$this->input->post('TxtKPIDesc_'.$i.'_'.$x);
						$UnitID=$this->input->post('SlcUnit_'.$i.'_'.$x);
						$FormulaID=$this->input->post('SlcFormula_'.$i.'_'.$x);
						$YTDID=$this->input->post('SlcYTD_'.$i.'_'.$x);
						$GenericKPIID=0;
					}
					else
					{
						//Ambil data KPI Generik
						$Generic = $this->general_model->get_GenericKPI_row($GenericKPIID);
						$UnitID=$Generic->SatuanID;
						$FormulaID=$Generic->PCFormulaID;
						$YTDID=$Generic->YTDID;
						$KPI_Name=$Generic->KPI;
						$KPI_Desc=$Generic->Description;
					}
					$Weight=$this->input->post('TxtWeight_'.$i.'_'.$x);
					$Baseline=$this->input->post('TxtBaseline_'.$i.'_'.$x);
					$YearTarget=$this->input->post('TxtTarget_'.$i.'_'.$x);
					//create KPI 
					$KPIID = $this->revision_rkk_model->add_KPI($GenericKPIID,$Chief->SasaranStrategisID,$UnitID,$FormulaID,$YTDID,$KPI_Name,$KPI_Desc,$Weight,$Baseline,$YearTarget,$Periode->BeginDate,$Periode->EndDate)->KPIID;
					//create RKK Detail Position
					$this->revision_rkk_model->add_rkkPositionDetail($RKKPositionID,$KPIID,$Periode->BeginDate,$Periode->EndDate);

					//create RKK Detail
					$RKKDetailID = $this->revision_rkk_model->add_rkkDetail($RKKID,$KPIID,$Periode->BeginDate,$Periode->EndDate,$Chief->RKKDetailID,$ReferenceID,$RefWeight)->RKKDetailID;
					for($z=1;$z<=12;$z++)
					{
						$CheckMonth = $this->input->post('ChkMonthlyTarget_'.$i.'_'.$x.'_'.$z);
						if ($CheckMonth){ // Jika Target Bulanan dicentang, maka buat RKK Detail Target 
							$MonthlyTarget = $this->input->post('TxtMonthlyTarget_'.$i.'_'.$x.'_'.$z);
							$this->revision_rkk_model->add_rkkTarget($RKKDetailID,$z,$MonthlyTarget,$Periode->BeginDate,$Periode->EndDate);
						}
					}
				}
			}
			else
			{

			}
			
		}
		$data['notif_text']='Success Cascading KPI';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
	function edit_target($RKKDetailID)
	{
		$Period = $this->general_model->get_ActivePeriode(); 
		$statusFlag = $this->revision_rkk_model->get_rkkDetail_row($RKKDetailID)->statusFlag;
		if($statusFlag==1 OR $statusFlag==3)
		{
			$data['disabled']='disabled';
			$data['action']='';

		}else{
			$data['disabled']='';
			$attributes = array('id' => 'genFrom');
			$data['action']=form_open('objective/rkkrevisi/edit_target_process',$attributes);
		}
		$RKK_Target = $this->revision_rkk_model->get_rkkTarget_list($RKKDetailID,$Period->BeginDate,$Period->EndDate);

		foreach ($RKK_Target as $row) {
			$Target[$row->Month]['RKKDetailTargetID']=$row->RKKDetailTargetID;
			$Target[$row->Month]['Target']=$row->Target;
		}
		$data['RKKDetailID']=$RKKDetailID;
		if(isset($Target)){
			$data['Target']=$Target;
		}
		$this->load->view('template/top_popup_1_view');
		$this->load->view('objective/target_form',$data);
		$this->load->view('template/bottom_popup_1_view');

	}
	function edit_target_process()
	{
		$Period = $this->general_model->get_ActivePeriode();
		$RKKDetailID=$this->input->post('TxtRKKDetailID');
		for($month=1;$month<=12;$month++){
			$TargetID = $this->input->post('TxtTargetID_'.$month);
			$CheckMonth = $this->input->post('ChkMonthlyTarget_'.$month);
			$TargetMonth = str_replace(',', '', $this->input->post('TxtTarget_'.$month));

			if ($TargetID==false and $CheckMonth==false){//abaikan nilai

			}elseif($TargetID==false and $CheckMonth==true){//tambahkan target
				$this->revision_rkk_model->add_rkkTarget($RKKDetailID,$month,$TargetMonth,$Period->BeginDate,$Period->EndDate);
			}elseif ($TargetID==true and $CheckMonth==false) {//nonaktifkan
				$this->revision_rkk_model->delimit_rkkTarget($TargetID);
			}elseif($TargetID==true and $CheckMonth==true){//Update nilai
				$this->revision_rkk_model->edit_rkkTarget($TargetID,$TargetMonth,$Period->EndDate);
			}
		}
		$data['notif_text']='Success Update Monthly Target';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
	
	function finish_self_rkk($RKKID)
	{
		$this->revision_rkk_model->edit_rkk_status($RKKID,3);
		$Periode = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$data_header['Periode']=$Periode;
		$data_header['Title']='RKK Revision';
		$Holder =$this->session->userdata('Holder');
		if($Holder==''){
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
		
		if($Holder!=0){
			$isSAP=substr($Holder, 0,1);
			$HolderID=substr($Holder, 2);
			$HolderDetail = $this->account_model->get_Holder_row($HolderID,$isSAP);
			$RKK = $this->revision_rkk_model->get_rkk_byUserPosition_row($userDetail->NIK,$HolderDetail->PositionID,$Periode->BeginDate,$Periode->EndDate) ;
			if(count($RKK)){
				switch ($RKK->statusFlag){
					case 0://belum jadi
						if($userDetail->RoleID==4 and $HolderDetail->Chief==2 and $HolderDetail->PositionGroup=='Layer 1'){
							redirect('objective/rkkrevisi/create_rkk/'.$RKK->RKKID.'/'.$Holder);
						}else{
							$data_header['notif_text']='RKK not available';
						}
						break;
					case 1:// sudah jadi dan menunggu persetujuan
						redirect('objective/agreement/view/'.$RKK->RKKID);
						break;
					case 2:// menolak 
						$data_header['notif_text']='RKK on editing';
						break;
					case 3://disetujui dan final
						if($HolderDetail->Chief==2){
							redirect('objective/rkkrevisi/cascade/'.$RKK->RKKID);
						}else{
							// redirect('objective/rkkrevisi/view/'.$RKK->RKKID);
							redirect('objective/rkkrevisi/cascade/'.$RKK->RKKID);
						}
						break;		
					default:
						# code...
						break;
				}
			}else{
				if($userDetail->RoleID==4 and $HolderDetail->Chief==2 and $HolderDetail->PositionGroup=='Layer 1'){
					redirect('objective/rkkrevisi//create_rkkHeader/'.$HolderID.'/'.$isSAP);
				}else{
					$data_header['notif_text']='RKK not available';
				}
			}
		}
		
		$data['notif_text']='Success Finish RKK';
		$data['notif_type']='alert-success';
		$data_header['action']='objective/rkkrevisi/';
		$this->load->view('template/top_1_view');
		$this->load->View('objective/revision_rkk_header_view',$data_header);
		$this->load->view('template/bottom_1_view');
	}
	function create_kpi_number($RKKID,$RKKPositionID,$SOID)
	{
		$data['rkk_id'] = $RKKID;
		$data['rkk_position_id'] = $RKKPositionID;
		$data['so_id'] = $SOID;
		$data['process'] = 'objective/rkk/create_kpi';

		$this->load->view('template/top_popup_1_view');
		$this->load->view('objective/kpi_num_form',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
	function finish_rkk_bawahan($RKKID)
	{
		$this->revision_rkk_model->edit_rkk_status($RKKID,3);
		$Periode = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$data_header['Periode']=$Periode;
		$data_header['Title']='RKK Revision';
		$Holder =$this->session->userdata('Holder');
		if($Holder==''){
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
		
		if($Holder!=0){
			$isSAP=substr($Holder, 0,1);
			$HolderID=substr($Holder, 2);
			$HolderDetail = $this->account_model->get_Holder_row($HolderID,$isSAP);
			$RKK = $this->revision_rkk_model->get_rkk_byUserPosition_row($userDetail->NIK,$HolderDetail->PositionID,$Periode->BeginDate,$Periode->EndDate) ;
			if(count($RKK)){
				switch ($RKK->statusFlag){
					case 0://belum jadi
						if($userDetail->RoleID==4 and $HolderDetail->Chief==2 and $HolderDetail->PositionGroup=='Layer 1'){
							redirect('objective/rkkrevisi/create_rkk/'.$RKK->RKKID.'/'.$Holder);
						}else{
							$data_header['notif_text']='RKK not available';
						}
						break;
					case 1:// sudah jadi dan menunggu persetujuan
						redirect('objective/agreement/view/'.$RKK->RKKID);
						break;
					case 2:// menolak 
						$data_header['notif_text']='RKK on editing';
						break;
					case 3://disetujui dan final
						if($HolderDetail->Chief==2){
							redirect('objective/rkkrevisi/cascade/'.$RKK->RKKID);
						}else{
							// redirect('objective/rkkrevisi/view/'.$RKK->RKKID);
							redirect('objective/rkkrevisi/cascade/'.$RKK->RKKID);
						}
						break;		
					default:
						# code...
						break;
				}
			}else{
				if($userDetail->RoleID==4 and $HolderDetail->Chief==2 and $HolderDetail->PositionGroup=='Layer 1'){
					redirect('objective/rkkrevisi//create_rkkHeader/'.$HolderID.'/'.$isSAP);
				}else{
					$data_header['notif_text']='RKK not available';
				}
			}
		}
		
		$data['notif_text']='Success Finish RKK';
		$data['notif_type']='alert-success';
		$data_header['action']='objective/rkkrevisi/';
		$this->load->view('template/top_1_view');
		$this->load->View('objective/revision_rkk_header_view',$data_header);
		$this->load->view('template/bottom_1_view');
	}


}
