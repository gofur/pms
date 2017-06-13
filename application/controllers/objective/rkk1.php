<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Rkk1 extends Controller {
	function __construct(){
		parent::__construct();
		if(!$this->session->userdata('loginFlag')){
			redirect('account/login');
		}
		$this->load->model('rkk_model');
		$this->load->model('general_model');
		$this->load->model('org_model');
		$this->load->model('account_model');
	}
	function index(){
		
		$Periode = $this->general_model->get_ActivePeriode();
		$data_header['Periode']=$Periode;
		$data_header['Title']='RKK';
		$Holder =$this->session->userdata('Holder');
		$Holder = $this->input->post('SlcPost');
		
		$data_header['Holder']=$Holder;
		$this->session->set_userdata('Holder',$Holder);
		$userDetail =$this->account_model->get_User_row($this->session->userdata('userID'));
		$data_header['userDetail'] = $userDetail;
		$data_header['PositionList_SAP'] = $this->account_model->get_Holder_list($this->session->userdata('NIK'),1,$Periode->BeginDate,$Periode->EndDate);
		$data_header['PositionList_nonSAP'] = $this->account_model->get_Holder_list($this->session->userdata('NIK'),0,$Periode->BeginDate,$Periode->EndDate);
		$data_header['PositionAssignmentList_SAP'] = $this->account_model->get_Assignment_list($this->session->userdata('NIK'),1,$Periode->BeginDate,$Periode->EndDate);
		$data_header['PositionAssignmentList_nonSAP'] = $this->account_model->get_Assignment_list($this->session->userdata('NIK'),0,$Periode->BeginDate,$Periode->EndDate);
		
		if($Holder!=0){
			$isSAP        = substr($Holder, 0,1);
			$HolderID     = substr($Holder, 2);
			$HolderDetail = $this->account_model->get_Holder_row($HolderID,$isSAP,$Periode->BeginDate,$Periode->EndDate);
			$RKK          = $this->rkk_model->get_rkk_byUserPosition_row($this->session->userdata('userID'),$HolderDetail->PositionID,$Periode->BeginDate,$Periode->EndDate) ;
			if(count($RKK)){
				switch ($RKK->statusFlag) {
					case 0://belum jadi
						if($userDetail->RoleID==4 and $HolderDetail->Chief==2 /*and $HolderDetail->PositionGroup=='Layer 1'*/){
							redirect('objective/rkk/create_rkk/'.$RKK->RKKID.'/'.$Holder);
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
						$subordinate_num = count($this->org_model->get_directSubordinate_list($isSAP,$HolderDetail->PositionID,$Periode->BeginDate,$Periode->EndDate)) ;
						// $final_num = $this->rkk_model->count_rkk_subordinate($HolderDetail->PositionID,3);

						if($subordinate_num!=0){
							redirect('objective/rkk/cascade/'.$RKK->RKKID);
						}else{
							redirect('objective/rkk/view/'.$RKK->RKKID);

						}
						break;
				}
			}else{
				if($userDetail->RoleID==4 and $HolderDetail->Chief==2 /*and $HolderDetail->PositionGroup=='Layer 1'*/){
					redirect('manager/rkk_add');
				}else{
					$data_header['notif_text']='RKK not available';
				}
			}
		}
		$data_header['action']='objective/rkk';
		$this->load->view('template/top_1_view');
		$this->load->View('objective/rkk_header_view',$data_header);
		$this->load->view('template/bottom_1_view');
	}

	function create_rkk($RKKID,$Holder)
	{
		$link['edit_target'] ='objective/rkk/edit_target/';
		$link['edit_kpi']    ='objective/rkk/edit_kpi/';
		$link['delimit_kpi'] ='objective/rkk/delimit_kpi/';
		
		$link['edit_so']     ='objective/rkk/edit_self_so/';
		$link['create_kpi']  ='objective/rkk/create_kpi_number/';
		$link['create_so']   ='objective/rkk/create_so/';
		$link['delimit_so']  ='objective/rkk/delimit_so/';
		$link['finish_rkk']  ='objective/rkk/finish_self_rkk/';
		$data['link']=$link;
		$Periode = $this->general_model->get_ActivePeriode();
		$data_header['PositionList_SAP']=$this->account_model->get_Holder_list($this->session->userdata('NIK'),1,$Periode->BeginDate,$Periode->EndDate);
		$data_header['PositionList_nonSAP']=$this->account_model->get_Holder_list($this->session->userdata('NIK'),0,$Periode->BeginDate,$Periode->EndDate);
		$data_header['PositionAssignmentList_SAP']=$this->account_model->get_Assignment_list($this->session->userdata('NIK'),1,$Periode->BeginDate,$Periode->EndDate);
		$data_header['PositionAssignmentList_nonSAP']=$this->account_model->get_Assignment_list($this->session->userdata('NIK'),0,$Periode->BeginDate,$Periode->EndDate);
		
		$isSAP=substr($Holder,0,1);
		$HolderID = substr($Holder, 2);
		$RKK = $this->rkk_model->get_rkk_row($RKKID) ;
		if($RKK->statusFlag!=0){
			redirect('objective/rkk');
		}
		$HolderDetail = $this->account_model->get_Holder_row($HolderID,$isSAP,$Periode->BeginDate,$Periode->EndDate);
		
		$subordinate=$this->org_model->get_directSubordinate_list($isSAP,$HolderDetail->PositionID,$Periode->BeginDate,$Periode->EndDate);
		$Perspective=$this->general_model->get_Perspective_List($Periode->BeginDate,$Periode->EndDate);
		$userDetail=$this->account_model->get_User_row($this->session->userdata('userID'));
		$data_header['userDetail'] =$userDetail;
		$data_header['Periode']    =$Periode;
		$data_header['RKK']        =$RKK;
		$data_header['Holder']     =$Holder;
		$data_header['Title']      ='Create RKK';
		foreach ($Perspective as $row_1) {
			$SO_List[$row_1->PerspectiveID]= $this->rkk_model->get_Objective_list($HolderDetail->OrganizationID,$row_1->PerspectiveID);
			foreach ($SO_List[$row_1->PerspectiveID] as $row_2) {
				$KPI_List[$row_2->SasaranStrategisID]=$this->rkk_model->get_KPI_list($RKK->RKKID,$row_2->SasaranStrategisID,$Periode->BeginDate,$Periode->EndDate);
			}
		}
		$data['SO_List']=$SO_List;
		if (isset($KPI_List)){
			$data['KPI_List']=$KPI_List;
		}
		$data['Perspective']=$Perspective;
		$data_header['action']='objective/rkk';
		$this->load->view('template/top_1_view');
		$this->load->view('objective/rkk_header_view',$data_header);
		foreach ($Perspective as $row) {
			$data['row_1']=$row;
			$this->load->view('objective/rkk_create_view',$data);
		}
		$this->load->view('template/bottom_1_view');
		$this->load->view('objective/rkk_view_js',$data);
	}
	function create_so($RKKID,$SOID)
	{
		$Periode = $this->general_model->get_ActivePeriode();
		$RKK = $this->rkk_model->get_rkk_row($RKKID);
		$data['OrgID'] = $this->org_model->get_Position_row($RKK->PositionID,$RKK->isSAP)->OrganizationID;
		$data['Perspective']=$this->general_model->get_Perspective_row($SOID);
		$data['RKKID']=$RKKID;
		$data['process']='objective/rkk/create_so_process';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('objective/so_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('objective/so_form_js');
	}
	function ajax_so($num = 1)
	{
		for ($i=1; $i <= $num ; $i++) { 
			$data['num'] = $i;
			$this->load->view('objective/so_ajax_form',$data);
		}
	}
	function create_so_process()
	{
		$RKKID=$this->input->post('TxtRKKID');
		$OrgID=$this->input->post('TxtOrgID');
		$num = $this->input->post('txt_SO_num');
		$PerspectiveID=$this->input->post('hdn_perspective');
		for ($i=1; $i <=$num ; $i++) { 
			$SO_Text=$this->input->post('TxtSO_'.$i);
			$Desc = $this->input->post('TxtDesc_'.$i);
			$this->rkk_model->add_Objective($OrgID,$PerspectiveID,$SO_Text,$Desc);
		}
		$data['notif_text']='Success create Strategic Objective';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
	function edit_self_so($SOID)
	{
		$old=$this->rkk_model->get_Objective_row($SOID);
		$data['old'] = $old;

		$data['Perspective']=$this->general_model->get_Perspective_row($old->PerspectiveID);
		$data['process']='objective/rkk/edit_self_so_process';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('objective/so_edit_form',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
	function edit_self_so_process()
	{
		$SOID = $this->input->post('TxtSOID');
		$PerspectiveID = $this->input->post('hdn_perspective');
		$Objective=$this->input->post('TxtSO');
		$Desc = $this->input->post('TxtDesc');
		$this->rkk_model->edit_Objective($SOID,$PerspectiveID,$Objective,$Desc);
		$data['notif_text']='Success edit Strategic Objective';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
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
	function create_kpi()
	{
		$rkk_id = $this->input->post('hdn_rkk_id');
		$rkk_position_id = $this->input->post('hdn_rkk_position_id');
		$so_id = $this->input->post('hdn_so_id');
		$kpi_num = $this->input->post('txt_num');

		$Periode = $this->general_model->get_ActivePeriode();
		$so = $this->rkk_model->get_Objective_row($so_id);
		$data_2['generic_kpi']= $this->general_model->get_GenericKPI_Search($so->PerspectiveID);

		$data_2['Unit_list']=$this->general_model->get_Satuan_list(date('Y-m-d'),$Periode->EndDate);
		$data_2['Formula_list']=$this->general_model->get_PCFormula_list(0,'',date('Y-m-d'),$Periode->EndDate);
		$data_2['Ytd_list']=$this->general_model->get_YTD_list(date('Y-m-d'),$Periode->EndDate);
		$data_1['Periode']=$Periode;
		$data_1['rkk_id']=$rkk_id;
		$data_1['rkk_position_id']=$rkk_position_id;
		$data_1['so_id']=$so_id;
		$data_1['kpi_num']=$kpi_num;
		$data_1['process']='objective/rkk/create_kpi_process';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('objective/kpi_detail_form',$data_1);
		for ($i=1; $i <=$kpi_num ; $i++) { 
			$data_2['num_code'] = $i;
			$this->load->view('objective/kpi_subdetail_form',$data_2);
		}
		$this->load->view('objective/kpi_end_form');
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('objective/kpi_form_js');
		for ($i=1; $i <=$kpi_num ; $i++) { 
			$data_3['num_code'] = $i;
			$this->load->view('objective/kpi_subdetail_js',$data_3);
		}
	}
	function create_kpi_process()
	{
		$RKKID=$this->input->post('hdn_rkk_id');
		$RKKPositionID=$this->input->post('hdn_rkk_position_id');
		$SOID=$this->input->post('hdn_so_id');
		$kpi_num = $this->input->post('hdn_num');
		$PerspectiveID=$this->rkk_model->get_Objective_row($SOID);
		$BeginDate=$this->input->post('TxtBeginDate');
		$EndDate=$this->input->post('TxtEndDate');

		for ($i=1; $i <= $kpi_num ; $i++) 
		{ 
			$Generic_KPI=$this->input->post('slc_generic_'.$i);

			if($Generic_KPI=='other'){
				$SatuanID=$this->input->post('slc_satuan_'.$i);
				$PCFormulaID=$this->input->post('slc_formula_'.$i);
				$YTDID=$this->input->post('slc_ytd_'.$i);
				$KPI_Name=$this->input->post('txt_kpi_'.$i);
				$KPI_Desc=$this->input->post('txt_desc_'.$i);
				$Generic_KPI=0;
			}else{
				$Generic = $this->general_model->get_GenericKPI_row($Generic_KPI);
				$SatuanID=$Generic->SatuanID;
				$PCFormulaID=$Generic->PCFormulaID;
				$YTDID=$Generic->YTDID;
				$KPI_Name=$Generic->KPI;
				$KPI_Desc=$Generic->Description;
			}
			$Weight=$this->input->post('txt_weight_'.$i);
			$Baseline=$this->input->post('txt_baseline_'.$i);
			$KPIID = $this->rkk_model->add_KPI($Generic_KPI,$SOID,$SatuanID,$PCFormulaID,$YTDID,$KPI_Name,$KPI_Desc,$Weight,$Baseline,$BeginDate,$EndDate)->KPIID;
			$this->rkk_model->add_rkkPositionDetail($RKKPositionID,$KPIID,$BeginDate,$EndDate);
			$RKKDetailID[$i] = $this->rkk_model->add_rkkDetail($RKKID,$KPIID,$BeginDate,$EndDate)->RKKDetailID;
		}
		/*membuat target dari KPI*/
		$data_head['notif_text'] 	= 'Success create KPI';
		$data_head['notif_type'] 	= 'alert-success';
		$data_head['process']  		= 'objective/rkk/create_self_target_process';
		$data_head['kpi_num'] 		= $kpi_num;
		$this->load->view('template/top_popup_1_view');
		$this->load->view('objective/target_create_header_form',$data_head);
		for ($i=1; $i <=$kpi_num ; $i++) { 
			$RKKDetail= $this->rkk_model->get_rkkDetail_row($RKKDetailID[$i]);//ambil data RKK Detail
			$data['KPI_head']=$RKKDetail;
			$data['num_code'] = $i;
			$this->load->view('objective/target_create_detail_form',$data);
		}
		$this->load->view('objective/target_create_end_form');

		$this->load->view('template/bottom_popup_1_view');
		for ($i=1; $i <=$kpi_num ; $i++) { 
			$data['num_code'] = $i;
			$this->load->view('objective/target_detail_form_js',$data);
		}
	}
	function create_self_target_process()
	{
		$Periode = $this->general_model->get_ActivePeriode();
		$kpi_num = $this->input->post('hdn_kpi_num');
		for ($i=1; $i <=$kpi_num ; $i++) { 
			$RKKDetailID= $this->input->post('hdn_rkk_detail_id_'.$i);
			$KPIID= $this->input->post('hdn_kpi_id_'.$i);
			$YTDID= $this->input->post('hdn_ytd_id_'.$i);
			$sum = 0 ;
			$count = 0 ;
			for($month=1;$month<=12;$month++){
				$CheckMonth = $this->input->post('ChkMonthlyTarget_'.$i.'_'.$month);
				if ($CheckMonth){
					$MonthlyTarget = $this->input->post('TxtMonthlyTarget_'.$i.'_'.$month);
					$this->rkk_model->add_rkkTarget($RKKDetailID,$month,$MonthlyTarget,$Periode->BeginDate,$Periode->EndDate);//create target
					$sum += $MonthlyTarget;
					$count += 1;
				}
			}
			$year_target = 0 ;
			switch ($YTDID) {//menghitung target akhir tahun berdasar target bulanan
				case 1: //akumulasi
					$year_target = $sum;
					break;
				case 2: //rata-rata
					$year_target = $sum/$count;
					break;
				case 3: //nilai di bulan terakhir
					$year_target = $MonthlyTarget;
					break;		
			}
			$this->rkk_model->edit_KPI_target($KPIID,$year_target);
			
		}
		$data['notif_text']='Success create Target';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
	function delimit_kpi($RKKDetailID)
	{
		$this->load->model('achievement_model');
		$rkk_detail = $this->rkk_model->get_rkkDetail_row($RKKDetailID);
		if ($this->achievement_model->count_achievement_detail($RKKDetailID)==0){
			#hapus karena belum ada transaksi
			$this->rkk_model->remove_rkkDetail($RKKDetailID);
			$this->rkk_model->remove_KPI($rkk_detail->KPIID);
			$data['notif_text']='Success remove KPI';
		} else {
			#geser end date karena sudah ada transaksi
			$this->rkk_model->delimit_kpi($rkk_detail->KPIID);
			$this->rkk_model->delimit_rkkTarget_byRKKDetail($rkk_detail->RKKDetailID);
			$this->rkk_model->delimit_rkkDetail($rkk_detail->KPIID);	
			$data['notif_text']='Success delimit KPI';
		}
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
	function edit_kpi($RKKDetailID)
	{
		$RKKDetail = $this->rkk_model->get_rkkDetail_row($RKKDetailID);
		
		$KPI=$this->rkk_model->get_kpi_row($RKKDetail->KPIID);
		$SO = $this->rkk_model->get_Objective_row($KPI->SasaranStrategisID);
		$Periode = $this->general_model->get_ActivePeriode();
		$data['old']=$KPI;
		$data['RKKDetailID'] = $RKKDetailID;
		$data['title']='Edit ';
		$data['genericKPI']= $this->general_model->get_GenericKPI_Search($SO->PerspectiveID);
		$data['Unit_list']=$this->general_model->get_Satuan_list(date('Y-m-d'),$Periode->EndDate);
		$data['Formula_list']=$this->general_model->get_PCFormula_list(0,'',date('Y-m-d'),$Periode->EndDate);
		$data['Ytd_list']=$this->general_model->get_YTD_list(date('Y-m-d'),$Periode->EndDate);
		$data['process']='objective/rkk/edit_kpi_process';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('objective/kpi_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('objective/kpi_form_js');
	}
	function edit_kpi_process()
	{
		$KPIID       = $this->input->post('TxtKPIID');
		$old_kpi     = $this->rkk_model->get_KPI_row($KPIID);
		$BeginDate   = $this->input->post('TxtBeginDate');
		$EndDate     = $this->input->post('TxtEndDate');
		$Generic_KPI = $this->input->post('SlcGeneric');
		$RKKDetailID = $this->input->post('hdn_RKKDetailID');
		$target_list = $this->rkk_model->get_rkkTarget_list($RKKDetailID,$BeginDate,$EndDate);
		if($Generic_KPI=='other'){
			$SatuanID=$this->input->post('SlcSatuan');
			$PCFormulaID=$this->input->post('SlcFormula');
			$YTDID=$this->input->post('SlcYTD');
			$KPI_Name=$this->input->post('TxtKPI');
			$KPI_Desc=$this->input->post('TxtDesc');
			$Generic_KPI=0;
		}else{
			$Generic = $this->general_model->get_GenericKPI_row($Generic_KPI);
			$SatuanID=$Generic->SatuanID;
			$PCFormulaID=$Generic->PCFormulaID;
			$YTDID=$Generic->YTDID;
			$KPI_Name=$Generic->KPI;
			$KPI_Desc=$Generic->Description;
		}
		$Weight=$this->input->post('TxtWeight');
		$Baseline=$this->input->post('TxtBaseline');
		// $Target=$this->input->post('TxtTarget');
		//harus update juga target akhir tahunnya
		$sum = 0;
		$count = 0;
		$last = 0;
		foreach ($target_list as $row) {
			$last = $row->Target;
			$sum += $row->Target;
			$count++;
		}
		switch ($YTDID) {
			case 1: // akumulasi
				$year_target = $sum;
				break;
			case 2: //average
				$year_target = $sum/$count;
				break;
			case 3://last target
				$year_target = $last;
				break;
		}
		$this->rkk_model->edit_KPI($KPIID,$Generic_KPI,$SatuanID,$PCFormulaID,$YTDID,$KPI_Name,$KPI_Desc,$Weight,$Baseline,$EndDate,$BeginDate);
		$this->rkk_model->edit_rkkDetail($RKKDetailID,$KPIID,$EndDate,$BeginDate);
		$this->rkk_model->edit_KPI_target($KPIID,$year_target);

		$data['notif_text']='Success edit KPI';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
	function view_subordinate($UserID,$PositionID,$Chief_RKKID)
	{
		$Periode    = $this->general_model->get_ActivePeriode();
		$Holder     = $this->session->userdata('Holder');
		$userDetail = $this->account_model->get_User_row($UserID);
		
		$link['view_subordinate'] = 'objective/rkk/view_subordinate/';
		$link['view_self']        = 'objective/rkk/cascade/'.$Chief_RKKID;

		$data_header['Title']        = "RKK - Subordinate's RKK";
		$data_header['link']         = $link;
		$data_header['Chief_RKKID']  = $Chief_RKKID;
		$data_header['Periode']      = $Periode;
		$data_header['userDetail']   = $userDetail;
		$data_header['PositionName'] = $this->org_model->get_Position_row($PositionID,$userDetail->isSAP)->PositionName;

		if($Holder!=0){
			$isSAP    = substr($Holder, 0,1);
			$HolderID = substr($Holder, 2);
			$HolderDetail = $this->account_model->get_Holder_row($HolderID,$isSAP,$Periode->BeginDate,$Periode->EndDate);
			
			//$subordinate=$this->org_model->get_directSubordinate_list($isSAP,$HolderDetail->PositionID,$Periode->BeginDate,$Periode->EndDate);
			$subordinate = $this->org_model->get_directSubordinate_list($userDetail->isSAP,$PositionID,$Periode->BeginDate,$Periode->EndDate);

			if($subordinate!=false){
				$data_header['subordinate'] = $subordinate;
			}
			$Bawahan = $this->account_model->get_User_row($UserID);
			$data['Bawahan']= $Bawahan;
			$Bawahan_RKK = $this->rkk_model->get_rkk_byUserPosition_row($UserID,$PositionID,$Periode->BeginDate,$Periode->EndDate);
			$Chief_RKK = $this->rkk_model->get_rkk_row($Chief_RKKID);
			if(count($Bawahan_RKK)){//cek rkk bawahan

				$Bawahan_RKKID         = $Bawahan_RKK->RKKID;
				$Bawahan_RKKPositionID = $Bawahan_RKK->RKKPositionID; 
			}else{//tambahkan jika tidak ada
				//cek ada rkk position 
				$bawahan_rkk_position = $this->rkk_model->get_rkkPosition_last_row($PositionID,$Periode->BeginDate,$Periode->EndDate);

				if (count($bawahan_rkk_position))
				{
					$Bawahan_RKKPositionID = $bawahan_rkk_position->RKKPositionID;
					$old_RKKID = $this->rkk_model->get_rkk_last_byPositionID_row($PositionID,$Periode->BeginDate,$Periode->EndDate)->RKKID;
					$Chief_PositionID = $this->org_model->get_superior_row($PositionID,$isSAP,$Periode->BeginDate)->PositionID;
					$Bawahan_RKK = $this->rkk_model->add_rkk($Bawahan_RKKPositionID,$UserID,$PositionID,$Chief_PositionID,0,$Bawahan->isSAP,$Chief_RKK->isSAP,date('Y-m-d'),$Periode->EndDate);
					$Bawahan_RKKID = $Bawahan_RKK->RKKID;
					$old_rkk_detail = $this->rkk_model->get_rkkDetail_list($old_RKKID,$Periode->BeginDate,$Periode->EndDate);
					foreach ($old_rkk_detail as $row) {
						if (is_null($row->ChiefRKKDetailID))
						{
							$new_rkk_detail = $this->rkk_model->add_rkkDetail($Bawahan_RKKID,$row->KPIID,date('Y-m-d'),$Periode->EndDate);
							$this->rkk_model->change_chief_rkk_detail($old_rkk_detail->RKKDetailID,$new_rkk_detail->RKKDetailID);

						}
						else
						{
							$new_rkk_detail = $this->rkk_model->add_rkkDetail($Bawahan_RKKID,$row->KPIID,date('Y-m-d'),$Periode->EndDate,$row->ChiefRKKDetailID,$row->ReferenceID,$row->Ref_weight);
							$this->rkk_model->change_chief_rkk_detail($old_rkk_detail->RKKDetailID,$new_rkk_detail->RKKDetailID);
						}
					}
					
					$this->rkk_model->delimit_rkk($old_RKKID,date('Y-m-d'));
				}
				else
				{
					redirect('manager/rkk_add');
				}
			}
			$link['edit_kpi']			= 'objective/rkk/edit_kpi/';
			$link['edit_target']	= 'objective/rkk/edit_target/';
			$link['view_target']	= 'objective/rkk/view_target/';
			$link['create_so']		= 'objective/rkk/create_so/'.$Bawahan_RKKID;
			$link['create_kpi']		= 'objective/rkk/create_kpi_number/'.$Bawahan_RKKID.'/'.$Bawahan_RKKPositionID.'/';
			$link['link_kpi'] 		= 'objective/rkk/link_kpi/';
			$link['delimit_kpi'] 	= 'objective/rkk/delimit_kpi/';
			$Bawahan_Position = $this->org_model->get_Position_row($Bawahan_RKK->PositionID,$Bawahan_RKK->isSAP);
			$Chief_Position = $this->org_model->get_Position_row($Chief_RKK->PositionID,$Chief_RKK->isSAP);

			if($Bawahan_Position->OrganizationID==$Chief_Position->OrganizationID){
				$isChief=0;
			}else{
				$isChief=1;
			}
			$Perspective=$this->general_model->get_Perspective_list($Periode->BeginDate,$Periode->EndDate);
			foreach ($Perspective as $row_1) {
				$SO_List[$row_1->PerspectiveID]= $this->rkk_model->get_SO_list($Bawahan_RKKID,$row_1->PerspectiveID,$Periode->BeginDate,$Periode->EndDate,$isChief);
				foreach ($SO_List[$row_1->PerspectiveID]as $row_2) {
					$KPI_List[$row_2->SasaranStrategisID]=$this->rkk_model->get_KPI_list($Bawahan_RKKID,$row_2->SasaranStrategisID,$Periode->BeginDate,$Periode->EndDate);
				}
			}
			$data['Perspective']=$Perspective;
			$data['SO_List']=$SO_List;
			if(isset($KPI_List)){
				$data['KPI_List']=$KPI_List;
			}
		}
		$data['link']=$link;
		$data_header['action']='objective/rkk';

		$this->load->view('template/top_1_view');
		$this->load->view('objective/rkk_header_view',$data_header);
		if (isset($Bawahan_RKK) && count($Bawahan_RKK)){
			if ($Bawahan_RKK->statusFlag==0 or $Bawahan_RKK->statusFlag==2) {
				foreach ($Perspective as $row) {
					$data['row_1'] = $row;
					$this->load->view('objective/rkk_subordinate_view',$data);
				}
				$this->load->view('template/bottom_1_view');
			} else {
				$this->load->view('objective/rkk_subordinate_view_2',$data);
				$this->load->view('objective/rkk_subordinate_view_2_js',$data);
			}
		}
		
		$this->load->view('objective/rkk_view_js');

	}
	function cascade($RKKID)
	{
		$Periode = $this->general_model->get_ActivePeriode();
		$link['cascade_kpi'] = 'objective/rkk/cascade_kpi_1/';
		$link['edit_target'] = 'objective/rkk/edit_target/';
		$link['view_target'] = 'objective/rkk/view_target/';

		$link['edit_kpi']         = 'objective/rkk/edit_kpi/';
		$link['edit_ref_weight']  = 'objective/rkk/edit_ref_weight/';
		$link['view_target']      = 'objective/rkk/view_target/';
		$link['view_subordinate'] = 'objective/rkk/view_subordinate/';
		$data['link']             = $link;
		$data_header['link']        = $link;
		$data_header['Chief_RKKID'] = $RKKID;
		$data_header['Title']       = 'RKK - Cascade';
		$data_header['Periode']     = $Periode;
		$Holder =$this->session->userdata('Holder');
		if($Holder==''){
			$Holder = $this->input->post('SlcPost');
		}
		$data_header['Holder']=$Holder;
		$this->session->set_userdata('Holder',$Holder);
		$userDetail = $this->account_model->get_User_row($this->session->userdata('userID'));
		$data_header['userDetail']                    = $userDetail;
		$data_header['PositionList_SAP']              = $this->account_model->get_Holder_list($this->session->userdata('NIK'),1,$Periode->BeginDate,$Periode->EndDate);
		$data_header['PositionList_nonSAP']           = $this->account_model->get_Holder_list($this->session->userdata('NIK'),0,$Periode->BeginDate,$Periode->EndDate);
		$data_header['PositionAssignmentList_SAP']    = $this->account_model->get_Assignment_list($this->session->userdata('NIK'),1,$Periode->BeginDate,$Periode->EndDate);
		$data_header['PositionAssignmentList_nonSAP'] = $this->account_model->get_Assignment_list($this->session->userdata('NIK'),0,$Periode->BeginDate,$Periode->EndDate);
		
		if($Holder!=0){
			$isSAP=substr($Holder, 0,1);
			$HolderID=substr($Holder, 2);
			$HolderDetail = $this->account_model->get_Holder_row($HolderID,$isSAP,$Periode->BeginDate,$Periode->EndDate);
			$subordinate=$this->org_model->get_directSubordinate_list($isSAP,$HolderDetail->PositionID,$Periode->BeginDate,$Periode->EndDate);
			if($subordinate!=false){
				$data_header['subordinate'] = $subordinate;
			}
			$Perspective=$this->rkk_model->get_Perspective_list($RKKID,$Periode->BeginDate,$Periode->EndDate);
			$i=0;
			foreach ($Perspective as $row_1) {
				$SO_List[$row_1->PerspectiveID]= $this->rkk_model->get_SO_list($RKKID,$row_1->PerspectiveID,$Periode->BeginDate,$Periode->EndDate);
				$temp_weight = 0;

				foreach ($SO_List[$row_1->PerspectiveID]as $row_2) {
					$KPI_List[$row_2->SasaranStrategisID]=$this->rkk_model->get_KPI_list($RKKID,$row_2->SasaranStrategisID,$Periode->BeginDate,$Periode->EndDate);
					foreach ($KPI_List[$row_2->SasaranStrategisID] as $row_3) {
						
						$Cascade_List[$row_3->RKKDetailID]=$this->rkk_model->get_KPI_cascade_list($row_3->RKKDetailID,$Periode->BeginDate,$Periode->EndDate);
						$temp_weight += $row_3->Bobot;
					}
				}
				$per_weight[$i] = $temp_weight;
				$i++;
			}
			$data['per_weight']   = $per_weight;
			$data['Perspective']  = $Perspective;
			$data['SO_List']      = $SO_List;
			$data['KPI_List']     = $KPI_List;
			$data['Cascade_List'] = $Cascade_List;

		}
		$data_header['action'] = 'objective/rkk';
		$this->load->view('template/top_1_view');
		$this->load->view('objective/rkk_header_view',$data_header);
		$this->load->view('objective/rkk_action');

		$this->load->view('objective/rkk_cascade_view',$data);
		$this->load->view('template/bottom_1_view');
		$this->load->view('objective/rkk_view_js');
	}
	function cascade_kpi_1($RKKDetailID)
	{
		$Periode     = $this->general_model->get_ActivePeriode(); 
		$Chief_KPI   = $this->rkk_model->get_rkkDetail_row($RKKDetailID);
		$Subordinate = $this->org_model->get_directSubordinate_list($Chief_KPI->isSAP,$Chief_KPI->PositionID,$Periode->BeginDate,$Periode->EndDate);

		$RKK_Target = $this->rkk_model->get_rkkTarget_list($RKKDetailID,$Periode->BeginDate,$Periode->EndDate);

		foreach ($RKK_Target as $row) {
			$Target[$row->Month]=$row->Target;
		}
		//$data['RKKDetailID']=$RKKDetailID;
		if(isset($Target)){
			$data['Target']=$Target;
		}

		$temp = array();
		foreach ($Subordinate as $row) {
				$c_rkk = $this->rkk_model->count_valid_rkk($row->NIK,$row->PositionID,$Chief_KPI->PositionID,$Periode->BeginDate,$Periode->EndDate,'open');
				if ($c_rkk > 0) {
					$sub[$count] = $row;
					$count++;
				}
			}

		$data['KPI_head']    = $this->rkk_model->get_KPI_row($Chief_KPI->KPIID);
		$data['Chief_KPI']   = $Chief_KPI;
		$data['process']     = 'objective/rkk/cascade_kpi_2/'.$RKKDetailID;
		$data['Subordinate'] = $Subordinate;

		
		$this->load->view('objective/kpi_cascade_subordinate_form',$data);
		
	}
	function cascade_kpi_2($RKKDetailID)
	{
		$Periode = $this->general_model->get_ActivePeriode(); 
		$Chief_KPI = $this->rkk_model->get_rkkDetail_row($RKKDetailID);
		$Subordinate_exception = $this->org_model->get_Exception_Subordinate_list($Chief_KPI->PositionID,$Periode->BeginDate,$Periode->EndDate);
		$Subordinate=$this->org_model->get_directSubordinate_list($Chief_KPI->isSAP,$Chief_KPI->PositionID,$Periode->BeginDate,$Periode->EndDate);
		$data['genericKPI']=$this->general_model->get_GenericKPI_List($Periode->BeginDate,$Periode->EndDate);
		$data['Reference_list']=$this->general_model->get_Reference_List($Periode->BeginDate,$Periode->EndDate);
		$RKK_Target = $this->rkk_model->get_rkkTarget_list($RKKDetailID,$Periode->BeginDate,$Periode->EndDate);

		foreach ($RKK_Target as $row) {
			$Target[$row->Month]=$row->Target;
		}
		//$data['RKKDetailID']=$RKKDetailID;
		if(isset($Target)){
			$data['Target']=$Target;
		}
		$data['Unit_list']=$this->general_model->get_Satuan_list(date('Y-m-d'),$Periode->EndDate);
		$data['Formula_list']=$this->general_model->get_PCFormula_list(0,'',date('Y-m-d'),$Periode->EndDate);
		$data['Ytd_list']=$this->general_model->get_YTD_list(date('Y-m-d'),$Periode->EndDate);
		$data['KPI_head']=$this->rkk_model->get_KPI_row($Chief_KPI->KPIID);
		$data['Chief_KPI']=$Chief_KPI;
		$data['process']='objective/rkk/cascade_kpi_process';
		$i=0;
		foreach ($Subordinate as $row) {
			$temp=$this->input->post('ChkSubordinate_'.$row->HolderID);
			if($temp==1){
				$subordinate_cascade[$i]['UserID']     = $row->UserID;
				$subordinate_cascade[$i]['Fullname']   = $row->Fullname;
				$subordinate_cascade[$i]['NIK']        = $row->NIK;
				$subordinate_cascade[$i]['PositionID'] = $row->PositionID;
				$subordinate_cascade[$i]['isSAP']      = $row->isSAP;

				$subordinate_cascade[$i]['KPI_Num']=$this->input->post('TxtKPI_Num_'.$row->HolderID);
				$i++;
			}
		}
		$data['subordinate_num'] = $i;
		$data['subordinate']     = $subordinate_cascade;
		$this->load->view('template/top_popup_1_view');
		$this->load->view('objective/kpi_cascade_kpi_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('objective/kpi_cascade_form_js',$data);

	}
	function cascade_kpi_process()
	{
		$Periode = $this->general_model->get_ActivePeriode();
		$Chief_RKKDetailID = $this->input->post('TxtChief_RKKDetailID');
		$Subordinate_Num = $this->input->post('TxtSubordinate_num');
		$ReferenceID=$this->input->post('SlcRef');
		$Chief = $this->rkk_model->get_rkkDetail_row($Chief_RKKDetailID);
		for ($i=0; $i < $Subordinate_Num ; $i++) { 
			$UserID     = $this->input->post('TxtUserID_'.$i);
			$PositionID = $this->input->post('TxtPositionID_'.$i);
			$isSAP      = $this->input->post('TxtisSAP_'.$i);
			$KPI_Num    = $this->input->post('TxtNum_'.$i);
			//Periksa RKK
			$RKK        = $this->rkk_model->get_rkk_byUserPosition_row($UserID,$PositionID,$Periode->BeginDate,$Periode->EndDate);
			if (count($RKK)==1){//Jika belum ada, buat RKK Baru
				$RKKID         = $RKK->RKKID;
				$RKK_Status    = $RKK->statusFlag;
				$RKKPositionID = $RKK->RKKPositionID;
				if ($RKK_Status==0 OR $RKK_Status==2) {
					for($x=0;$x<$KPI_Num;$x++) {
						if($ReferenceID==3)	{
							$RefWeight=$this->input->post('TxtRW_'.$i.'_'.$x);
						} else {
							$RefWeight=0;
						}
						$GenericKPIID=$this->input->post('SlcGenKPI_'.$i.'_'.$x);
						if($GenericKPIID=='other') {
							$KPI_Name=$this->input->post('TxtKPIName_'.$i.'_'.$x);
							$KPI_Desc=$this->input->post('TxtKPIDesc_'.$i.'_'.$x);
							$GenericKPIID=0;
						} else {
							//Ambil data KPI Generik
							$Generic   = $this->general_model->get_GenericKPI_row($GenericKPIID);
							$UnitID    = $Generic->SatuanID;
							$FormulaID = $Generic->PCFormulaID;
							$YTDID     = $Generic->YTDID;
							$KPI_Name  = $Generic->KPI;
							$KPI_Desc  = $Generic->Description;
						}
						$Weight   = $this->input->post('TxtWeight_'.$i.'_'.$x);
						$Baseline = $this->input->post('TxtBaseline_'.$i.'_'.$x);
						//create KPI 
						$KPIID    = $this->rkk_model->add_KPI($GenericKPIID,$Chief->SasaranStrategisID,$Chief->SatuanID,$Chief->PCFormulaID,$Chief->YTDID,$KPI_Name,$KPI_Desc,$Weight,$Baseline,$Periode->BeginDate,$Periode->EndDate)->KPIID;
						//create RKK Detail Position
						$this->rkk_model->add_rkkPositionDetail($RKKPositionID,$KPIID,$Periode->BeginDate,$Periode->EndDate);
						//create RKK Detail
						$RKKDetailID = $this->rkk_model->add_rkkDetail($RKKID,$KPIID,$Periode->BeginDate,$Periode->EndDate,$Chief->RKKDetailID,0,$RefWeight)->RKKDetailID;
						$sum         = 0;
						$count       = 0;
						$year_target = 0 ;
						for($z=1;$z<=12;$z++) {
							$CheckMonth = $this->input->post('ChkMonthlyTarget_'.$i.'_'.$x.'_'.$z);
							if ($CheckMonth) { // Jika Target Bulanan dicentang, maka buat RKK Detail Target 
								$MonthlyTarget = 0 ;
								$MonthlyTarget = $this->input->post('TxtMonthlyTarget_'.$i.'_'.$x.'_'.$z);
								$this->rkk_model->add_rkkTarget($RKKDetailID,$z,$MonthlyTarget,$Periode->BeginDate,$Periode->EndDate);
								$sum += $MonthlyTarget;
								$count += 1;
							}
						}
						switch ($Chief->YTDID) { // Hitung Target akhir tahun berdasarkan target bulanan
							case 1: //akumulasi
								$year_target = $sum;
								break;
							case 2: //rata-rata
								$year_target = $sum / $count;
								break;
							case 3: //nilai terakhir
								$year_target = $MonthlyTarget;
								break;
						}
						$this->rkk_model->edit_KPI_target($KPIID,$year_target);
					}
				}
			}

			
			
		}
		//update Reference ID RKK Detail Chief
		$this->rkk_model->edit_chief_rkkDetail_ref($Chief->RKKDetailID,$ReferenceID);
		$data['notif_text']='Success Cascading KPI';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
	function view_target($RKKDetailID)
	{
		$Period = $this->general_model->get_ActivePeriode();
		$RKKDetail= $this->rkk_model->get_rkkDetail_row($RKKDetailID);
		$data['KPI_head']=$RKKDetail;
		//ambil data RKK Detail

		$statusFlag = $this->rkk_model->get_rkkDetail_row($RKKDetailID)->statusFlag;
		
		$RKK_Target = $this->rkk_model->get_rkkTarget_list($RKKDetailID,$Period->BeginDate,$Period->EndDate);

		foreach ($RKK_Target as $row) {
			$Target[$row->Month]=$row->Target;
		}
		//$data['RKKDetailID']=$RKKDetailID;
		if(isset($Target)){
			$data['Target']=$Target;
		}
		$this->load->view('template/top_popup_1_view');
		$this->load->view('objective/target_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
	function edit_target($RKKDetailID)
	{
		$Period = $this->general_model->get_ActivePeriode();
		$RKKDetail= $this->rkk_model->get_rkkDetail_row($RKKDetailID);
		$data['KPI_head']=$RKKDetail;

		//ambil data RKK Detail

		$statusFlag = $this->rkk_model->get_rkkDetail_row($RKKDetailID)->statusFlag;
		if($statusFlag==1 OR $statusFlag==3)
		{
			$data['disabled']='disabled';
			$data['action']='';

		}else{
			$data['disabled']='';
			$attributes = array('id' => 'genFrom');
			$data['action']=form_open('objective/rkk/edit_target_process',$attributes);
		}
		$RKK_Target = $this->rkk_model->get_rkkTarget_list($RKKDetailID,$Period->BeginDate,$Period->EndDate);

		foreach ($RKK_Target as $row) {
			$Target[$row->Month]['RKKDetailTargetID']=$row->RKKDetailTargetID;
			$Target[$row->Month]['Target']=$row->Target;
		}
		//$data['RKKDetailID']=$RKKDetailID;
		if(isset($Target)){
			$data['Target']=$Target;
		}
		$this->load->view('template/top_popup_1_view');
		$this->load->view('objective/target_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('objective/target_form_js');

	}
	function edit_target_process()
	{
		$Period = $this->general_model->get_ActivePeriode();
		$RKKDetailID=$this->input->post('TxtRKKDetailID');
		$KPIID=$this->input->post('TxtKPIID');
		$YTDID=$this->input->post('TxtYTDID');
		$sum = 0 ;
		$count =0 ;
		$year_target = 0;
		for($month=1;$month<=12;$month++){
			$TargetID = $this->input->post('TxtTargetID_'.$month);
			$CheckMonth = $this->input->post('ChkMonthlyTarget_'.$month);

			if ($TargetID==false and $CheckMonth==false) //abaikan nilai
			{

			}
			else if($TargetID==false and $CheckMonth==true)//tambahkan target
			{
				$TargetMonth = $this->input->post('TxtTarget_'.$month);
				$this->rkk_model->add_rkkTarget($RKKDetailID,$month,$TargetMonth,$Period->BeginDate,$Period->EndDate);
				$sum += $TargetMonth;
				$count += 1;
			}
			else if ($TargetID==true and $CheckMonth==false) //nonaktifkan
			{
				$this->rkk_model->delimit_rkkTarget($TargetID);
			}
			else if($TargetID==true and $CheckMonth==true) //Update nilai
			{
				$TargetMonth = $this->input->post('TxtTarget_'.$month);
				$this->rkk_model->edit_rkkTarget($TargetID,$TargetMonth,$Period->EndDate);
				$sum += $TargetMonth;
				$count += 1;
			}
		}
		switch ($YTDID) {
			case 1:
				$year_target = $sum;
				break;
			case 2:
				$year_target = $sum/$count;
				break;
			case 3:
				$year_target = $TargetMonth;
				break;
		}
		$this->rkk_model->edit_KPI_target($KPIID,$year_target);
		$data['notif_text']='Success Update Monthly Target';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
	function link_kpi($RKKDetailID = 0)
	{
		$Period 					= $this->general_model->get_ActivePeriode();
		$RKKDetail 				= $this->rkk_model->get_rkkDetail_row($RKKDetailID);
		$data['KPI_head'] = $RKKDetail;
		$statusFlag 			= $RKKDetail->statusFlag;
		
		$spr = $this->org_model->get_Chief_Position_row($this->session->userdata('isSAP'),$RKKDetail->PositionID);
		$spr_rkk = $this->rkk_model->get_rkk_byUserPosition_row($spr->UserID,$spr->PositionID,$Period->BeginDate,$Period->EndDate);

		$per 	= $this->rkk_model->get_Perspective_list($spr_rkk->RKKID,$Period->BeginDate,$Period->EndDate);
		$kpi_opt = array();
  	$kpi_opt[''] = '';
		foreach ($per as $row_1) {
			$so = $this->rkk_model->get_SO_list2($spr_rkk->RKKID,$row_1->PerspectiveID,$Period->BeginDate,$Period->EndDate);
			foreach ($so as $row_2) {
				$kpi = $this->rkk_model->get_KPI_list($spr_rkk->RKKID,$row_2->SasaranStrategisID,$Period->BeginDate,$Period->EndDate);
				foreach ($kpi as $row_3) {
					$kpi_opt[$row_3->RKKDetailID] = $row_3->KPI;

				}
			}
		}
		$data['kpi_opt'] = $kpi_opt;
		if (is_null($RKKDetail->ChiefRKKDetailID)) {
			$data['kpi_slc'] = '';
		} else {
			$data['kpi_slc'] = $RKKDetail->ChiefRKKDetailID;

		}
		if($statusFlag==1 OR $statusFlag==3)
		{
			$data['disabled']='disabled';
			$data['action']='';

		}else{
			$data['disabled']='';
			$attributes = array('id' => 'genFrom');
			$data['action']=form_open('objective/rkk/link_kpi_process',$attributes);
		}
		$this->load->view('template/top_popup_1_view');
		$this->load->view('objective/link_form',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
	public function link_kpi_process()
	{
		$detail_id 	= $this->input->post('hdn_rkkDetail');
		$chief_id 	= $this->input->post('slc_kpi');
		if ($chief_id!='') {
		 	$this->rkk_model->link_kpi($detail_id,$chief_id);
		 	$this->rkk_model->edit_chief_rkkDetail_ref($chief_id,4);
		} else {
		 	$this->rkk_model->unlink_kpi($detail_id);
		}
		$data['notif_text']='Success Update Link KPI';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
	function edit_ref_weight($RKKDetailID)
	{
		$data['process'] = 'objective/rkk/edit_ref_weight_process';
		$data['old'] = $this->rkk_model->get_rkkDetail_row($RKKDetailID);
		$this->load->view('template/top_popup_1_view');
		$this->load->view('objective/ref_weight_form',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
	function edit_ref_weight_process()
	{
		$RKKDetailID = $this->input->post('hdn_RKKDetailID');
		$weight = $this->input->post('txt_weight');
		$this->rkk_model->edit_ref_weight($RKKDetailID,$weight);
		$data['notif_text']='Success Update Ref. Target';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
	function view($RKKID)
	{
		$this->load->model('idp_model');
		$Periode = $this->general_model->get_ActivePeriode();
		$data_header['Periode'] = $Periode;
		$data_header['Title']   = 'RKK';
		$Holder =$this->session->userdata('Holder');
		if($Holder==''){
			$Holder = $this->input->post('SlcPost');
		}
		$data_header['Holder']=$Holder;
		$this->session->set_userdata('Holder',$Holder);
		$userDetail=$this->account_model->get_User_row($this->session->userdata('userID'));
		$data_header['userDetail']                    = $userDetail;
		$data_header['PositionList_SAP']              = $this->account_model->get_Holder_list($this->session->userdata('NIK'),1,$Periode->BeginDate,$Periode->EndDate);
		$data_header['PositionList_nonSAP']           = $this->account_model->get_Holder_list($this->session->userdata('NIK'),0,$Periode->BeginDate,$Periode->EndDate);
		$data_header['PositionAssignmentList_SAP']    = $this->account_model->get_Assignment_list($this->session->userdata('NIK'),1,$Periode->BeginDate,$Periode->EndDate);
		$data_header['PositionAssignmentList_nonSAP'] = $this->account_model->get_Assignment_list($this->session->userdata('NIK'),0,$Periode->BeginDate,$Periode->EndDate);
		
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
					$temp_list = $this->rkk_model->get_KPI_list($RKKID,$row_2->SasaranStrategisID,$Periode->BeginDate,$Periode->EndDate);
					$KPI_List[$row_2->SasaranStrategisID]= $temp_list;
					foreach ($temp_list as $row_3) {
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
			foreach ($dev_area_list as $row) {
				$training_list[$row->IDPDetailID]=$this->idp_model->get_IDP_DevelopmentProgram($row->IDPDetailID);
			}
			$data['dev_area_list']=$dev_area_list;
			if(isset($training_list))
			{
				$data['training_list']=$training_list;
			}

		}
		$link['view_target']='objective/rkk/view_target/';
		$link['agree']='';
		$link['disagree']='';
		$data['link']=$link;
		$this->load->view('template/top_1_view');
		$this->load->View('objective/rkk_header_view',$data_header);
		$this->load->View('objective/rkk_view',$data);
		$this->load->view('template/bottom_1_view');
		$this->load->view('objective/rkk_view_js');
	}
	/**
	 * [untuk melihat Hubungan KPI dengan KPI Atasan]
	 * @param  [type] $RKKDetailID [ID RKK Detail ]
	 */
	function chief_kpi($RKKDetailID){
		$Period    = $this->general_model->get_ActivePeriode();
		$kpi       = $this->rkk_model->get_rkkDetail_row($RKKDetailID);
		$data['kpi']       = $kpi;
		if ($kpi->ChiefRKKDetailID != ''){
			$chief_kpi = $this->rkk_model->get_rkkDetail_row($kpi->ChiefRKKDetailID); 
			
			$data['chief_kpi'] = $chief_kpi;
			
			$RKK_Target = $this->rkk_model->get_rkkTarget_list($kpi->ChiefRKKDetailID,$Period->BeginDate,$Period->EndDate);

			foreach ($RKK_Target as $row) {
				$Target[$row->Month]=$row->Target;
			}
			//$data['RKKDetailID']=$RKKDetailID;
			if(isset($Target)){
				$data['Target']=$Target;
			}
		}
		
		$this->load->view('template/top_popup_1_view');
		$this->load->view('objective/chief_kpi_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}



}
