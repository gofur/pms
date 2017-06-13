<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subordinate extends Controller {
	function __construct()
	{
		parent::__construct();
		if(! $this->session->userdata('loginFlag'))
		{
			redirect('account/login');
		}
		$this->load->library('email');
		$this->load->model('general_model');
		$this->load->model('account_model');
		$this->load->model('rkk_model3');
		$this->load->model('org_model');
		$this->load->model('idp_model');

	}
	public function index()
	{
		$period   = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$user_id  = $this->session->userdata('userID');
		$nik      = $this->session->userdata('NIK');
		
		$user_dtl = $this->account_model->get_User_byNIK($nik);
		
		$begin    = $period->BeginDate;
		$end      = $period->EndDate;
		$data['filter_start'] = substr($begin, 0,10);
		$data['filter_end']   = substr($end, 0,10);

		$data['period']   = $period;
		$data['user_dtl'] = $user_dtl;

		$data['post_ls_SAP']      = $this->account_model->get_Holder_list($nik,1,$begin,$end);
		$data['post_ls_nonSAP']   = $this->account_model->get_Holder_list($nik,0,$begin,$end);
		$data['assign_ls_SAP']    = $this->account_model->get_Assignment_list($nik,1,$begin,$end);
		$data['assign_ls_nonSAP'] = $this->account_model->get_Assignment_list($nik,0,$begin,$end);

		$this->load->view('manager/assign/main_view',$data);

	}

	public function show_subordinate()
	{
		$sess_nik     = $this->session->userdata("NIK");
		$nik          = $this->input->post('nik');
		$holder       = $this->input->post('holder');
		$begin = $this->input->post('start');
		$end   = $this->input->post('end');

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
		$sub_ls       = $this->org_model->get_directSubordinate_list($is_sap,$post_id,$begin,$end);
		if (count($sub_ls)) {
			$data['sub_ls']   = $sub_ls;
			$base_link = 'manager/subordinate/monitoring/';
			$link = array();

			$begin = $this->input->post('start');
			$end = $this->input->post('end');

			foreach ($sub_ls as $sub) {
				$key = $sub->NIK.'|'.$sub->isSAP.'|'.$sub->PositionID;
				$param = $sub->NIK.'/'.$sub->PositionID.'/'.$begin.'/'.$end;
				$link[$key] = $base_link.$param;
			}
			$data['link'] = $link;

			$this->load->view('template/subordinate_view', $data, FALSE);
		}
	}

	public function show_list()
	{
		$sess_nik = $this->session->userdata("NIK");
		$nik      = $this->input->post('nik');
		$holder   = $this->input->post('holder');
		$begin    = $this->input->post('start');
		$end      = $this->input->post('end');
		$data['holder_A'] = $holder;
		$data['begin'] = $begin;
		$data['end']   = $end;

		if ($holder == '') {
			$is_sap  = $this->input->post('is_sap');
			$post_id = $this->input->post('post_id');
		} else {
			list($is_sap,$holder_id) = explode('.',$holder);
			$holder_dtl = $this->account_model->get_Holder_row($holder_id,$is_sap);
			$post_id    = $holder_dtl->PositionID;
		}

		$sub_ls     = $this->org_model->get_directSubordinate_list($is_sap,$holder_dtl->PositionID,$begin,$end);

		if ($this->session->userdata('roleID') == 4 ) {

			$self_rkk = $this->rkk_model3->count_rkk_holder($nik,$holder_dtl->PositionID,$is_sap,$begin,$end);

			if ($self_rkk == 0) {
				$p_begin    = $this->input->post('start');
				$p_end      = $this->input->post('end');
				redirect('manager/subordinate/self/'.$holder.'/'.$p_begin.'/'.$p_end);
			}
		}
		if (count($sub_ls)) {
			$data['sub_ls'] = $sub_ls;
			foreach ($sub_ls as $row) {
				$sub_rkk = $this->rkk_model3->get_rkk_holder_last($row->NIK,$row->PositionID,$row->isSAP,$begin,$end);
				$sub_data[$row->HolderID]['stat'] = '';
				if (count($sub_rkk)) {
					#RKK
					
					$sub_data[$row->HolderID]['rkk_id']     = $sub_rkk->RKKID;
					$sub_data[$row->HolderID]['rkk_start']  = $sub_rkk->BeginDate;
					$sub_data[$row->HolderID]['rkk_end']    = $sub_rkk->EndDate;
					$sub_data[$row->HolderID]['rkk_weight'] = $this->rkk_model3->count_weight_rkk($sub_rkk->RKKID,$begin,$end);
					$sub_data[$row->HolderID]['kpi_num']    = $this->rkk_model3->count_kpi($sub_rkk->RKKID,$begin,$end);
					switch ($sub_rkk->statusFlag) {
						case 0: 
							# DRAFT
							$sub_data[$row->HolderID]['rkk_stat'] = '<span class="label">Draft</span>';
							break;
						case 1:
							# ASSIGNED
							$sub_data[$row->HolderID]['rkk_stat'] = '<span class="label">Draft</span>';
							
							break;
						case 2:
							# REJECT
							$sub_data[$row->HolderID]['rkk_stat'] = '<span class="label label-important">Rejected</span>';
							break;
						case 3:
							# AGREE
							$sub_data[$row->HolderID]['rkk_stat'] = '<span class="label label-success">Agreed</span>';
							break;
						case 4:
							# LOCK
							$sub_data[$row->HolderID]['rkk_stat'] = '<span class="label label-success">Lock</span>';
							break;
						case 5:
							# Final
							$sub_data[$row->HolderID]['rkk_stat'] = '<span class="label label-success">Final</span>';
							break;

					}
					#IDP
					$sub_idp = $this->idp_model->get_Header_byRKKID_row($sub_rkk->RKKID,$begin,$end);
					if (count($sub_idp)) {
						$sub_data[$row->HolderID]['chk']     = form_checkbox('chk_sub[]',$row->HolderID.'.'.$row->isSAP, FALSE,'disabled="disabled"');
						$sub_data[$row->HolderID]['idp_id']  = $sub_idp->IDPID;
						$sub_data[$row->HolderID]['idp_num'] = $this->idp_model->count_DP($sub_rkk->RKKID,$begin,$end);
						switch ($sub_idp->StatusFlag) {
							case 0: 
							# DRAFT
							$sub_data[$row->HolderID]['idp_stat'] = '<span class="label">Draft</span>';
							break;
						case 1:
							# ASSIGNED
							$sub_data[$row->HolderID]['idp_stat'] = '<span class="label ">Draft</span>';
							
							break;
						case 2:
							# REJECT
							$sub_data[$row->HolderID]['idp_stat'] = '<span class="label label-important">Rejected</span>';
							break;
						case 3:
							# AGREE
							$sub_data[$row->HolderID]['idp_stat'] = '<span class="label label-success">Agreed</span>';
							break;
						case 4:
							# LOCK
							$sub_data[$row->HolderID]['idp_stat'] = '<span class="label label-success">Lock</span>';
							break;
						case 5:
							# Final
							$sub_data[$row->HolderID]['idp_stat'] = '<span class="label label-success">Final</span>';
							break;
						}

						if (($sub_rkk->statusFlag == 0 OR $sub_rkk->statusFlag == 2 ) && $sub_idp->StatusFlag == 1 ) {
							$sub_data[$row->HolderID]['stat'] = '<span class="label label-info">Not Assign</span>';

							if (round($sub_data[$row->HolderID]['rkk_weight']) == 100) {
								$sub_data[$row->HolderID]['chk']  = form_checkbox('chk_sub[]',$row->HolderID.'.'.$row->isSAP, FALSE, 'disabled="disabled" class="chk_assign"');
							}
						} elseif ($sub_rkk->statusFlag == 1 && $sub_idp->StatusFlag == 1) {
							$sub_data[$row->HolderID]['stat'] = '<span class="label label-warning">Assigned</span>';
						} else {
							$sub_data[$row->HolderID]['stat'] = '';
						}


					} else {
						$sub_data[$row->HolderID]['chk']        = form_checkbox('chk_sub[]',$row->HolderID.'.'.$row->isSAP, FALSE,'disabled="disabled" class="chk_create"');

						$sub_data[$row->HolderID]['idp_id']   = 0;
						$sub_data[$row->HolderID]['idp_num']  = 0;
						$sub_data[$row->HolderID]['idp_stat'] = '<span class="label">Not Created</span>';

					}

				} else {
					$sub_data[$row->HolderID]['chk']        = form_checkbox('chk_sub[]',$row->HolderID.'.'.$row->isSAP, FALSE,'disabled="disabled" class="chk_create"');
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
					$sub_data[$row->HolderID]['stat'] = '';
				}
			}

			if (isset($sub_data)) {
				$data['sub_data'] = $sub_data;
			}

		}
		
		$this->load->view('manager/assign/list_view',$data);
	}

	public function self($holder,$begin,$end)
	{
		$sess_nik       = $this->session->userdata("NIK");
		$nik            = $this->input->post('nik');
		$data['begin']  = $begin;
		$data['end']    = $end;
		$data['action'] = 'manager/subordinate/self_process';
		$begin          .= ' 00:00:00.000';
		$end            .= ' 23:59:59.999';

		if ($holder == '') {
			$is_sap  = $this->input->post('is_sap');
			$post_id = $this->input->post('post_id');
		} else {
			list($is_sap,$holder_id) = explode('.',$holder);
			$holder_dtl = $this->account_model->get_Holder_row($holder_id,$is_sap);
			$post_id    = $holder_dtl->PositionID;
		}
		$data['user'] = $this->account_model->get_User_byNIK($sess_nik);
		$data['post'] = $this->org_model->get_Position_row($post_id,$is_sap,$begin,$end)->PositionName;

		$this->load->view('manager/assign/self_form',$data);


	}

	public function assign_process()
	{
		$sub 		= $this->input->post('chk_sub');
		$period       = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$count_rkk = 0;
		foreach ($sub as $key => $holder) {
			list($holder_id,$is_sap) = explode('.', $holder);
			$temp    = $this->account_model->get_Holder_row($holder_id,$is_sap,$period->BeginDate,$period->EndDate);
			$user_id = $this->account_model->get_User_byNIK($temp->NIK)->UserID;
			
			#RKK
			$c_rkk = $this->rkk_model3->count_rkk_holder($temp->NIK,$temp->PositionID,$is_sap,$period->BeginDate,$period->EndDate);
			if ($c_rkk) {
				$rkk = $this->rkk_model3->get_rkk_holder_last($temp->NIK,$temp->PositionID,$is_sap,$period->BeginDate,$period->EndDate);
				$rkk_id = $rkk->RKKID;
				$this->rkk_model3->edit_rkk_status($rkk_id,1);
				$count_rkk++;

				$Fullname = $this->account_model->get_User_byNIK($temp->NIK)->Fullname;
				$Email = $this->account_model->get_User_byNIK($temp->NIK)->Email;
				$succesNote='Assigned RKK & IDP for '.$temp->NIK.' - '.$Fullname;

				/**
				 * Send email
				 */
				$config['smtp_host']="10.10.55.10";
				$config['smtp_user']="pms@chr.kompasgramedia.com";
				$config['smtp_pass']="Abc123"; 
				$config['mailtype']='html';
				$config['priority']=1;
				$config['protocol']='smtp';
				$this->email->initialize($config);
				$this->email->from('pms@chr.kompasgramedia.com', '[PMS Online] DO NOT REPLY THIS EMAIL!');
				$this->email->to($Email);
				$this->email->subject('[PMS Online] Assigned RKK & IDP');
				$this->email->message("<h2>Information</h2>
					Your RKK and IDP has been assigned, please check your PMS Online system.
					If you're not ".$Fullname.",please ignore this email. <br><br><br>Thank you,<br><br>PM Online");

				/*if($this->email->send())
				{
					$this->session->set_userdata('notif_text',$succesNote);
					$this->session->set_userdata('notif_type','alert-success');
				}else{
					$this->session->set_userdata('notif',"Email has not sent");
					$this->session->set_userdata('notif_type',"alert-danger");
				}*/
			}
		}

		$this->session->set_userdata('notif_type','alert-success');
		$this->session->set_userdata('notif_text',$count_rkk.' RKK & IDP Assigned.');
		redirect('manager/subordinate');
	}

	public function add_process()
	{
		$period = $this->general_model->get_Period_row($this->session->userdata('active_period'));

		list($self_is_sap,$self_holder_id) = explode('.', $this->input->post('holder_A'));
		$self         = $this->account_model->get_Holder_row($self_holder_id,$self_is_sap,$period->BeginDate,$period->EndDate);
		$sub          = $this->input->post('chk_sub');
		$start        = $this->input->post('dt_start');
		$end          = $this->input->post('dt_end');
		$count_rkk    = 0;
		$count_idp    = 0;
		$period_begin = strtotime($period->BeginDate);
		$period_end   = strtotime($period->EndDate);

		if (strtotime($start) < $period_begin || strtotime($end) > $period_end) {
			$this->session->set_userdata('notif_type','alert-error');
			$this->session->set_userdata('notif_text','Cannot create RKK and IDP with Begin Date and End Date out of Period range date');
		} else {
			foreach ($sub as $key => $holder) {
				list($holder_id,$is_sap) = explode('.', $holder);
				$temp    = $this->account_model->get_Holder_row($holder_id,$is_sap,$start,$end);
				$user_id = $this->account_model->get_User_byNIK($temp->NIK)->UserID;
				
				#RKK
				$c_rkk = $this->rkk_model3->count_rkk_holder($temp->NIK,$temp->PositionID,$is_sap,$start,$end);
				if ($c_rkk == 0) {
					$rkk_id = $this->rkk_model3->add_rkk($temp->NIK,$temp->PositionID,$is_sap,$self->NIK,$self->PositionID, $self_is_sap, $start,$end);
					$count_rkk++;
				} else {
					$rkk = $this->rkk_model3->get_rkk_holder_last($temp->NIK,$temp->PositionID,$is_sap,$start,$end);
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
			
		}

		redirect('manager/subordinate');
	}

	public function self_process()
	{
		$period       = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		list($self_is_sap,$self_holder_id) = explode('.', $this->input->post('holder_A'));

		$self         = $this->account_model->get_Holder_row($self_holder_id,$self_is_sap,$period->BeginDate,$period->EndDate);
		$start        = $this->input->post('dt_start');
		$end          = $this->input->post('dt_end');
		$count_rkk    = 0;
		$count_idp    = 0;
		$period_begin = strtotime($period->BeginDate);
		$period_end   = strtotime($period->EndDate);
		$nik          = $this->session->userdata('NIK');
		$user_id      = $this->session->userdata('userID');

		
		if ($this->session->userdata('roleID') == 4 ) {

			if (strtotime($start) < $period_begin || strtotime($end) > $period_end) {
				$this->session->set_userdata('notif_type','alert-error');
				$this->session->set_userdata('notif_text','Cannot create RKK and IDP with Begin Date and End Date out of Period range date');
			} else {
				if ($nik == '000001') {
					$chief = $this->account_model->get_Holder_byNIK($nik,1);
					$chief_is_sap = 1;
				} else {
					$chief        = $this->account_model->get_Holder_byNIK($this->account_model->get_User_byRole_row(2)->NIK,1);
					if (count($chief)) {
						$chief_is_sap = $this->account_model->get_User_byRole_row(2)->isSAP;
					} 

				}
				
				#RKK
				echo $self_rkk = $this->rkk_model3->count_rkk_holder($nik,$self->PositionID,$self_is_sap,$start,$end);
				if ($self_rkk == 0) {
					if (count($chief)) {
						$rkk_id = $this->rkk_model3->add_rkk($self->NIK,$self->PositionID,$self_is_sap,$chief->NIK,$chief->PositionID, $chief_is_sap, $start,$end);
					} else {
						$rkk_id = $this->rkk_model3->add_rkk($self->NIK,$self->PositionID,$self_is_sap,'',0, '', $start,$end);

					}
				} else {
					$rkk = $this->rkk_model3->get_rkk_holder_last($nik,$self->PositionID,$self_is_sap,$start,$end);
					$rkk_id = $rkk->RKKID;
				}

				#IDP
				$count_idp = $this->idp_model->count_DP($rkk_id,$start,$end);
				if ($count_idp == 0) {
					$this->idp_model->add_Header($rkk_id,$start,$end);

				}
				$this->session->set_userdata('notif_type','alert-success');
				$this->session->set_userdata('notif_text','Self RKK & Self IDP Created.');

			}
	
		}

		redirect('objective/rkk');
	}

	
	

}

/* End of file subordinate.php */
/* Location: ./application/controllers/manager/subordinate.php */
