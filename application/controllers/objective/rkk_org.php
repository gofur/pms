<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rkk_org extends Controller {
	function __construct(){
		parent::__construct();
		if(!$this->session->userdata('loginFlag')){
			redirect('account/login');
		}
		$this->load->model('rkk_model');
		$this->load->model('org_model');
	}
	public function index()
	{
		$Periode = $this->general_model->get_ActivePeriode();
		
		$Holder = $this->session->userdata('Holder');
		$Holder = $this->input->post('SlcPost');

		$data_header['Periode'] = $Periode;
		$data_header['Title']   = 'Rencana Kerja Karyawan - Organisasi';
		$data_header['Holder']  = $Holder;

		$this->session->set_userdata('Holder',$Holder);
		$userDetail = $this->account_model->get_User_row($this->session->userdata('userID'));
		$data_header['userDetail']                    = $userDetail;
		$data_header['PositionList_SAP']              = $this->account_model->get_Holder_list(
			$this->session->userdata('NIK'),
			1,
			$Periode->BeginDate,
			$Periode->EndDate
		);
		$data_header['PositionList_nonSAP']           = $this->account_model->get_Holder_list(
			$this->session->userdata('NIK'),
			0,
			$Periode->BeginDate,
			$Periode->EndDate
		);
		$data_header['PositionAssignmentList_SAP']    = $this->account_model->get_Assignment_list($this->session->userdata('NIK'),
			1,
			$Periode->BeginDate,
			$Periode->EndDate
		);
		$data_header['PositionAssignmentList_nonSAP'] = $this->account_model->get_Assignment_list(
			$this->session->userdata('NIK'),
			0,
			$Periode->BeginDate,
			$Periode->EndDate
		);
		if ($Holder != 0 ) {
			$isSAP=substr($Holder, 0,1);
			$HolderID=substr($Holder, 2);
			$HolderDetail = $this->account_model->get_Holder_row(
				$HolderID,
				$isSAP,
				$Periode->BeginDate,
				$Periode->EndDate
			);
			$RKK = $this->rkk_model->get_rkk_byUserPosition_row(
				$this->session->userdata('userID'),
				$HolderDetail->PositionID,
				$Periode->BeginDate,
				$Periode->EndDate
			);
			if (count($RKK)) {
				switch ($RKK->statusFlag) {
					case 0://
						if($userDetail->RoleID==4 and $HolderDetail->Chief==2){
							redirect('objective/rkk/create_rkk/'.$RKK->RKKID.'/'.$Holder);
						}else{
							$data_header['notif_text']='RKK not available';
						}
						break;
					
					default:
						# code...
						break;
				}
			}
		}
		$this->load->view('template/top_1_view');
		
		$this->load->view('template/bottom_1_view');
	}

	public function view($RKKID='')
	{
		# code...
	}

}

/* End of file rkk_org.php */
/* Location: ./application/controllers/objective/rkk_org.php */