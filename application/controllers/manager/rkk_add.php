<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rkk_add extends Controller {
	function __construct()
	{
		parent::__construct();
		if(! $this->session->userdata('loginFlag'))
		{
			redirect('account/login');
		}
		$this->load->model('general_model');
		$this->load->model('account_model');
		$this->load->model('rkk_model3');
		$this->load->model('org_model');
		$this->load->model('idp_model');

	}
	public function index()
	{
		$period       = $this->general_model->get_ActivePeriode();
		$holder       = $this->session->userdata('Holder');
		$user_id      = $this->session->userdata('userID');
		$nik          = $this->session->userdata('NIK');
		
		$holder       = $this->input->post('SlcPost');
		
		$user_dtl     = $this->account_model->get_User_byNIK($nik);
		$filter_start = $this->input->post('dt_filter_start');
		$filter_end   = $this->input->post('dt_filter_end');
		if ($filter_start == '' && $filter_end == '') {
			$filter_start = $period->BeginDate;
			$filter_end   = $period->EndDate;
			$data['filter_start'] = substr($filter_start, 0,10);
			$data['filter_end']   = substr($filter_end, 0,10);
		} else {
			$data['filter_start'] = $filter_start;
			$data['filter_end']   = $filter_end;

			$filter_start .= ' 00:00:00.000';
			$filter_end 	.= ' 23:59:59.999';
		}
		$data['period']   = $period;
		$data['holder']   = $holder;
		$data['user_dtl'] = $user_dtl;

		$data['post_ls_SAP']      = $this->account_model->get_Holder_list($nik,1,$filter_start,$filter_end);
		$data['post_ls_nonSAP']   = $this->account_model->get_Holder_list($nik,0,$filter_start,$filter_end);
		$data['assign_ls_SAP']    = $this->account_model->get_Assignment_list($nik,1,$filter_start,$filter_end);
		$data['assign_ls_nonSAP'] = $this->account_model->get_Assignment_list($nik,0,$filter_start,$filter_end);
		$this->session->set_userdata('Holder',$holder);

		if ($holder != 0 ) {
			list($is_sap,$holder_id) = explode('.',$holder);
			$holder_dtl = $this->account_model->get_Holder_row($holder_id,$is_sap);
			$sub_ls     = $this->org_model->get_directSubordinate_list($is_sap,$holder_dtl->PositionID,$filter_start,$filter_end);

			if ($this->session->userdata('roleID') == 4 ) {
				if ($nik == '000001') {
					$self_rkk = $this->rkk_model3->count_rkk_holder($nik,$holder_dtl->PositionID,$is_sap,$filter_start,$filter_end);

				} else {
					$CEO = $this->account_model->get_Holder_byNIK($this->account_model->get_User_byRole_row(2)->NIK,1);
					$self_rkk = $this->rkk_model3->count_rkk_holder($nik,$holder_dtl->PositionID,$is_sap,$filter_start,$filter_end);
				}

				if ($self_rkk == 0) {
					redirect('manager/rkk_add/self');
				}
				
			}

			if (count($sub_ls)) {
				$data['sub_ls'] = $sub_ls;
				foreach ($sub_ls as $row) {
					$sub_rkk = $this->rkk_model3->get_rkk_holder_last($row->NIK,$row->PositionID,$row->isSAP,$filter_start,$filter_end);

					if (count($sub_rkk)) {
						#RKK
						
						$sub_data[$row->HolderID]['rkk_id']     = $sub_rkk->RKKID;
						$sub_data[$row->HolderID]['rkk_start']  = $sub_rkk->BeginDate;
						$sub_data[$row->HolderID]['rkk_end']    = $sub_rkk->EndDate;
						$sub_data[$row->HolderID]['rkk_weight'] = $this->rkk_model3->count_weight_rkk($sub_rkk->RKKID,$filter_start,$filter_end);
						$sub_data[$row->HolderID]['kpi_num']    = $this->rkk_model3->count_kpi($sub_rkk->RKKID,$filter_start,$filter_end);
						switch ($sub_rkk->statusFlag) {
							case 0: 
								# DRAFT
								$sub_data[$row->HolderID]['rkk_stat'] = '<span class="label">Draft</span>';
								break;
							case 1:
								# ASSIGNED
								$sub_data[$row->HolderID]['rkk_stat'] = '<span class="label label-warning">Assigned</span>';
								
								break;
							case 2:
								# REJECT
								$sub_data[$row->HolderID]['rkk_stat'] = '<span class="label label-important">Rejected</span>';
								break;
							case 3:
								# AGREE
								$sub_data[$row->HolderID]['rkk_stat'] = '<span class="label label-success">Agreed</span>';
								break;

						}
						#IDP
						$sub_idp = $this->idp_model->get_Header_byRKKID_row($sub_rkk->RKKID,$filter_start,$filter_end);
						if (count($sub_idp)) {
							$sub_data[$row->HolderID]['chk']     = form_checkbox('chk_sub[]',$row->HolderID.'.'.$row->isSAP, FALSE,'disabled="disabled"');
							$sub_data[$row->HolderID]['idp_id']  = $sub_idp->IDPID;
							$sub_data[$row->HolderID]['idp_num'] = $this->idp_model->count_DP($sub_rkk->RKKID,$filter_start,$filter_end);
							switch ($sub_idp->StatusFlag) {
								case 0: 
								# DRAFT
								$sub_data[$row->HolderID]['idp_stat'] = '<span class="label">Draft</span>';
								break;
							case 1:
								# ASSIGNED
								$sub_data[$row->HolderID]['idp_stat'] = '<span class="label label-warning">Assigned</span>';
								
								break;
							case 2:
								# REJECT
								$sub_data[$row->HolderID]['idp_stat'] = '<span class="label label-important">Rejected</span>';
								break;
							case 3:
								# AGREE
								$sub_data[$row->HolderID]['idp_stat'] = '<span class="label label-success">Agreed</span>';
								break;
							}


						} else {
							$sub_data[$row->HolderID]['chk']        = form_checkbox('chk_sub[]',$row->HolderID.'.'.$row->isSAP, TRUE);

							$sub_data[$row->HolderID]['idp_id']   = 0;
							$sub_data[$row->HolderID]['idp_num']  = 0;
							$sub_data[$row->HolderID]['idp_stat'] = '<span class="label">Not Created</span>';


						}

					} else {
						$sub_data[$row->HolderID]['chk']        = form_checkbox('chk_sub[]',$row->HolderID.'.'.$row->isSAP, TRUE);
						#RKK
						$sub_data[$row->HolderID]['rkk_id']     = 0;
						$sub_data[$row->HolderID]['rkk_weight'] = 0;
						$sub_data[$row->HolderID]['kpi_num']    = 0;
						$sub_data[$row->HolderID]['rkk_stat']   = '<span class="label">Not Created</span>';
						$sub_data[$row->HolderID]['rkk_start']  = '-';
						$sub_data[$row->HolderID]['rkk_end']    = '-';
						#IDP
						$sub_data[$row->HolderID]['idp_id']     = 0;
						$sub_data[$row->HolderID]['idp_num']    = 0;
						$sub_data[$row->HolderID]['idp_stat']   = '<span class="label">Not Created</span>';
					}
				}

				if (isset($sub_data)) {
					$data['sub_data'] = $sub_data;
				}

			}
			$data['hidden']					= array(
				'chief_is_sap'    => $is_sap,
				'chief_holder_id' => $holder_id
			);
		} else {
			$data['hidden'] = array();
		}
		$data['notif_text']     = $this->session->userdata('notif_text');
		$data['notif_type']     = $this->session->userdata('notif_type');
		$this->session->unset_userdata('notif_text');
		$this->session->unset_userdata('notif_type');

		
		$data['action_filter']  = 'manager/rkk_add';
		$data['action_process'] = 'manager/rkk_add/add_process';
		$link['view_sub']       = '';
		$data['link']           = $link;

		$this->load->view('manager/rkk_add_view',$data);

	}

	public function add_process()
	{
		$period          = $this->general_model->get_ActivePeriode();
		$self_is_sap    = $this->input->post('chief_is_sap');
		$self_holder_id = $this->input->post('chief_holder_id');
		$self           = $this->account_model->get_Holder_row($self_holder_id,$self_is_sap,$period->BeginDate,$period->EndDate);
		$sub             = $this->input->post('chk_sub');
		$start           = $this->input->post('dt_start');
		$end             = $this->input->post('dt_end'). ' 23:59:59';
		$count_rkk 	     = 0;
		$count_idp 	     = 0;
		foreach ($sub as $key => $holder) {
			list($holder_id,$is_sap) = explode('.', $holder);
			$temp    = $this->account_model->get_Holder_row($holder_id,$is_sap,$period->BeginDate,$period->EndDate);
			$user_id = $this->account_model->get_User_byNIK($temp->NIK)->UserID;
			
			#RKK
			$c_rkk = $this->rkk_model3->count_rkk_holder($temp->NIK,$temp->PositionID,$is_sap,$period->BeginDate,$period->EndDate);
			if ($c_rkk == 0) {
				$rkk_id = $this->rkk_model3->add_rkk($temp->NIK,$temp->PositionID,$is_sap,$self->NIK,$self->PositionID, $self_is_sap, $start,$end);
				$count_rkk++;
			} else {
				$rkk = $this->rkk_model3->get_rkk_holder_last($temp->NIK,$temp->PositionID,$is_sap,$period->BeginDate,$period->EndDate);
				$rkk_id = $rkk->RKKID;
			}
			#IDP
			$c_idp = $this->idp_model->count_DP($rkk_id,$start,$end);
			if ($c_idp == 0) {
				$this->idp_model->add_Header($rkk_id,$start,$end);
				$count_idp++;
			}

		}
		$this->session->set_userdata('notif_type','alert-success');
		$this->session->set_userdata('notif_text',$count_rkk.' RKK & '.$count_idp.' IDP Created.');
		redirect('manager/rkk_add');
	}

	public function self()
	{
		$period  = $this->general_model->get_ActivePeriode();
		$holder  = $this->session->userdata('Holder');
		$user_id = $this->session->userdata('userID');
		$nik 		 = $this->session->userdata('NIK');
		if($holder==''){
			$holder = $this->input->post('SlcPost');
		}
		$user_dtl = $this->account_model->get_User_byNIK($nik);
		$data['period']   = $period;
		$data['holder']   = $holder;
		$data['user_dtl'] = $user_dtl;

		$data['post_ls_SAP']      = $this->account_model->get_Holder_list($nik,1,$period->BeginDate,$period->EndDate);
		$data['post_ls_nonSAP']   = $this->account_model->get_Holder_list($nik,0,$period->BeginDate,$period->EndDate);
		$data['assign_ls_SAP']    = $this->account_model->get_Assignment_list($nik,1,$period->BeginDate,$period->EndDate);
		$data['assign_ls_nonSAP'] = $this->account_model->get_Assignment_list($nik,0,$period->BeginDate,$period->EndDate);
		$this->session->set_userdata('Holder',$holder);

		if ($holder != 0) {
			list($is_sap,$holder_id) = explode('.',$holder);
			$holder_dtl = $this->account_model->get_Holder_row($holder_id,$is_sap);
			$sub_ls     = $this->org_model->get_directSubordinate_list($is_sap,$holder_dtl->PositionID,$period->BeginDate,$period->EndDate);

			if (count($sub_ls)) {
				$data['sub_ls'] = $sub_ls;
			}
			
		}
		$data['notif_text']     = $this->session->userdata('notif_text');
		$data['notif_type']     = $this->session->userdata('notif_type');
		$this->session->unset_userdata('notif_text');
		$this->session->unset_userdata('notif_type');
		$data['hidden']					= array(
			'chief_is_sap'    => $is_sap,
			'chief_holder_id' => $holder_id
		);
		
		$filter_start = $this->input->post('dt_filter_start');
		$filter_end   = $this->input->post('dt_filter_end');
		if ($filter_start == '' && $filter_end == '') {
			$filter_start = $period->BeginDate;
			$filter_end   = $period->EndDate;
			$data['filter_start'] = substr($filter_start, 0,10);
			$data['filter_end']   = substr($filter_end, 0,10);
		} else {
			$data['filter_start'] = $filter_start;
			$data['filter_end']   = $filter_end;

			$filter_start .= ' 00:00:00.000';
			$filter_end 	.= ' 23:59:59.999';
		}
		$data['action_filter']  = 'manager/rkk_add';
		$data['action_process'] = 'manager/rkk_add/self_process';
		$link['view_sub']       = '';
		$data['link']           = $link;

		$this->load->view('manager/rkk_add_self_form',$data);

	}

	public function self_process()
	{
		$period          = $this->general_model->get_ActivePeriode();
		$self_is_sap    = $this->input->post('chief_is_sap');
		$self_holder_id = $this->input->post('chief_holder_id');
		$self           = $this->account_model->get_Holder_row($self_holder_id,$self_is_sap,$period->BeginDate,$period->EndDate);
		$start           = $this->input->post('dt_start');
		$end             = $this->input->post('dt_end'). ' 23:59:59';
		$count_rkk       = 0;
		$count_idp       = 0;
		$nik             = $this->session->userdata('NIK');
		$user_id = $this->session->userdata('userID');
		if ($this->session->userdata('roleID') == 4 ) {
			if ($nik == '000001') {
				$chief = $this->account_model->get_Holder_byNIK($nik,1);
				$chief_is_sap = 1;
			} else {
				$chief = $this->account_model->get_Holder_byNIK($this->account_model->get_User_byRole_row(2)->NIK,1);
				$chief_is_sap = $this->account_model->get_User_byRole_row(2)->isSAP;

			}
			
			#RKK
			$self_rkk = $this->rkk_model3->count_rkk_holder($nik,$self->PositionID,$self_is_sap,$period->BeginDate,$period->EndDate);
			if ($self_rkk == 0) {
				$rkk_id = $this->rkk_model3->add_rkk($self->NIK,$self->PositionID,$self_is_sap,$chief->NIK,$chief->PositionID, $chief_is_sap, $start,$end);
			} else {
				$rkk = $this->rkk_model3->get_rkk_holder_last($nik,$self->PositionID,$self_is_sap,$period->BeginDate,$period->EndDate);
				$rkk_id = $rkk->RKKID;
			}

			#IDP
			$count_idp = $this->idp_model->count_DP($rkk_id,$start,$end);
			if ($count_idp == 0) {
				$this->idp_model->add_Header($rkk_id,$start,$end);

			}
			
		}

		$this->session->set_userdata('notif_type','alert-success');
		$this->session->set_userdata('notif_text','Self RKK & Self IDP Created.');
		redirect('manager/rkk_add');
	}

}

/* End of file rkk_add.php */
/* Location: ./application/controllers/manager/rkk_add.php */