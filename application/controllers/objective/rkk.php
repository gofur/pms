<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rkk extends Controller {

	function __construct(){
		parent::__construct();
		if(!$this->session->userdata('loginFlag')){
			redirect('account/login');
		}
		$this->load->model('rkk_model3');
		$this->load->model('general_model');
		$this->load->model('org_model');
		$this->load->model('account_model');

	}
	public function index()
	{
		$period       = $this->general_model->get_Period_row($this->session->userdata('active_period'));
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

		$this->load->view('objective/rkk/main_view', $data);
	}

	public function show_subordinate()
	{
		$sess_nik     = $this->session->userdata("NIK");
		$nik          = $this->input->post('nik');
		$holder       = $this->input->post('holder');
		$filter_start = $this->input->post('start');
		$filter_end   = $this->input->post('end');

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
			$base_link = 'objective/rkk/view_subordinate/';
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
								redirect('objective/rkk/cascade/'.$rkk->RKKID.'/'.$filter_start.'/'.$filter_end);
								break;
							default:
								if ($this->session->userdata("roleID") == 4) {
									redirect('objective/rkk/self_rkk/'.$rkk->RKKID);

								} else if ($this->session->userdata("roleID") == 7) {
									redirect('objective/rkk/self_rkk/'.$rkk->RKKID);

								}  else {
									$data['notif_type'] = 'alert-error';
									$data['notif_text'] = 'RKK not final';
									$this->load->view('template/notif_view', $data, FALSE);

								}
								break;
						}
					}

				} else{
					redirect('objective/rkk/cascade/'.$rkk->RKKID.'/'.$filter_start.'/'.$filter_end);

				}

			} else {
				$rkk = $this->rkk_model3->get_rkk_holder_last($nik,$post_id,$is_sap, $filter_start, $filter_end,'all');

				if($sess_nik != $nik) {
					redirect('objective/rkk/view/'.$rkk->RKKID.'/'.$filter_start.'/'.$filter_end);
				} else {
					switch ($rkk->statusFlag) {
						case 0:
							# DRAFT
							if ($this->session->userdata("roleID") == 4) {
								redirect('objective/rkk/self_rkk/'.$rkk->RKKID);

							} else if ($this->session->userdata("roleID") == 7) {
								redirect('objective/rkk/self_rkk/'.$rkk->RKKID);

							}  else {
								$data['notif_type'] = 'alert-error';
								$data['notif_text'] = 'RKK not final';
								$this->load->view('template/notif_view', $data, FALSE);

							}


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
							redirect('objective/rkk/view/'.$rkk->RKKID.'/'.$filter_start.'/'.$filter_end);
							break;
						default:
							redirect('objective/rkk/view/'.$rkk->RKKID.'/'.$filter_start.'/'.$filter_end);
							break;
					}

				}

			}

		}
	}

	public function cascade($rkk_id,$start,$end)
	{

		$self_rkk     = $this->rkk_model3->get_rkk_row($rkk_id);
		// $rel_rkk			= $this->rkk_model3->get_rkk_rel_last($rkk_id,$self_rkk->BeginDate,$self_rkk->EndDate);
		// $persp_ls     = $this->general_model->get_Perspective_List($self_rkk->BeginDate,$self_rkk->EndDate);

		$rel_rkk			= $this->rkk_model3->get_rkk_rel_last($rkk_id,$start,$end);
		$persp_ls     = $this->general_model->get_Perspective_List($start,$end);
		$so_ls        = array();
		$persp_weight = array();
		$data['rkk_id']     = $rkk_id;
		$data['persp_ls']   = $persp_ls;
		$data['rkk']        = $self_rkk;
		if (count($rel_rkk)) {
			$spr_person   = $this->account_model->get_User_byNIK($rel_rkk->chief_nik);
			$data['spr_person'] = $spr_person;
			$data['spr_post']   = $this->org_model->get_Position_row($rel_rkk->chief_post_id,$rel_rkk->chief_is_sap,$rel_rkk->BeginDate,$rel_rkk->EndDate);
		}

		if ($self_rkk->NIK != $this->session->userdata('NIK') && ($self_rkk->statusFlag == 0 OR $self_rkk->statusFlag == 2) ) {
			$data['link_create']     = 'objective/rkk/create_so/'.$rkk_id.'/';
			$data['link_create_kpi'] = 'objective/rkk/create_kpi/'.$rkk_id.'/';
			$data['link_edit']       = 'objective/rkk/edit_so/';
			$data['link_remove']     = 'objective/rkk/remove_so/';
			$data['is_sub']       = TRUE;


		} else if( $this->session->userdata("roleID") == 4 && $self_rkk->NIK == $this->session->userdata('NIK')) {
			$data['link_edit']    = 'objective/rkk/edit_so/';
			$data['link_remove']  = 'objective/rkk/remove_so/';
			$data['is_sub']       = FALSE;


		} else {
			$data['is_sub']       = FALSE;

		}

		switch ($self_rkk->statusFlag) {
			case 0:
			case 2:
				$is_clear = 0;
				break;

			default:
				$is_clear = 1;

				break;
		}

		foreach ($persp_ls as $persp) {
			$so_ls[$persp->PerspectiveID] = $this->rkk_model3->get_so_persp_list($rkk_id,$persp->PerspectiveID,$self_rkk->BeginDate,$self_rkk->EndDate,$is_clear);

			$persp_weight[$persp->PerspectiveID] = $this->rkk_model3->sum_weight_persp($rkk_id,$persp->PerspectiveID,$self_rkk->BeginDate,$self_rkk->EndDate);
		}
		$data['so_ls']       = $so_ls;
		$data['persp_weight'] = $persp_weight;
		$this->load->view('objective/rkk/rkk_casd_view',$data);
	}

	public function self_rkk($rkk_id)
	{
		$self_rkk     = $this->rkk_model3->get_rkk_row($rkk_id);
		$rel_rkk			= $this->rkk_model3->get_rkk_rel_last($rkk_id,$self_rkk->BeginDate,$self_rkk->EndDate);
		$persp_ls     = $this->general_model->get_Perspective_List($self_rkk->BeginDate,$self_rkk->EndDate);
		$so_ls        = array();
		$persp_weight = array();
		// $spr_person   = $this->account_model->get_User_byNIK($rel_rkk->chief_nik);
		$spr_person   = $this->account_model->get_User_byNIK('000390');
		$data['rkk_id']     = $rkk_id;
		$data['persp_ls']   = $persp_ls;
		$data['rkk']        = $self_rkk;
		// $data['spr_person'] = $spr_person;
		// $data['spr_post']   = $this->org_model->get_Position_row($rel_rkk->chief_post_id,$rel_rkk->chief_is_sap,$rel_rkk->BeginDate,$rel_rkk->EndDate);


		if (($this->session->userdata("roleID") == 4 OR $this->session->userdata("roleID") == 7) && $self_rkk->NIK == $this->session->userdata('NIK') ) {
			$data['link_create']     = 'objective/rkk/create_so/'.$rkk_id.'/';
			$data['link_create_kpi'] = 'objective/rkk/create_kpi/'.$rkk_id.'/';
			$data['link_edit']    = 'objective/rkk/edit_so/';
			$data['link_remove']  = 'objective/rkk/remove_so/';
		}

		foreach ($persp_ls as $persp) {
			$so_ls[$persp->PerspectiveID] = $this->rkk_model3->get_so_persp_list($rkk_id,$persp->PerspectiveID,$self_rkk->BeginDate,$self_rkk->EndDate,0);

			$persp_weight[$persp->PerspectiveID] = $this->rkk_model3->sum_weight_persp($rkk_id,$persp->PerspectiveID,$self_rkk->BeginDate,$self_rkk->EndDate);
		}
		$data['so_ls']       = $so_ls;
		$data['persp_weight'] = $persp_weight;
		$this->load->view('objective/rkk/rkk_casd_view',$data);
	}

	public function show_kpi()
	{
		$sess_nik = $this->session->userdata("NIK");
		$rkk_id   = $this->input->post('rkk_id');
		$so_id    = $this->input->post('so_id');
		$begin    = $this->input->post('begin');
		$end      = $this->input->post('end');
		$kpi_ls   = $this->rkk_model3->get_kpi_so_list($rkk_id,$so_id,$begin,$end);
		$rkk      = $this->rkk_model3->get_rkk_row($rkk_id);
		$rkk_rel  = $this->rkk_model3->get_rkk_rel_last($rkk_id,$begin,$end);
		if (count($kpi_ls)) {
			$data['kpi_ls'] = $kpi_ls;
			if ($sess_nik == $rkk->NIK ) {
				if ($rkk->statusFlag == 3) {

					$sub_count = count($this->org_model->get_directSubordinate_list(1,$rkk->PositionID,$rkk->BeginDate,$rkk->EndDate));
					if ($sub_count) {
						$data['link_cascade'] = 'objective/rkk/cascade_kpi/';
						$data['link_rel']    	= 'objective/rkk/relation_kpi_AB/';

					}

				}else if ($rkk->statusFlag == 0 && $this->session->userdata("roleID")==4) {
					$data['link_remove'] = 'objective/rkk/remove_kpi/';
					$data['link_edit']   = 'objective/rkk/edit_kpi/';
				} else if ($rkk->statusFlag == 0 && $rkk_rel->chief_nik == $sess_nik) {
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

	public function show_cascading()
	{
		$kpi_id     = $this->input->post('kpi_id');
		$begin      = $this->input->post('begin');
		$end        = $this->input->post('end');
		$casd_count = $this->rkk_model3->count_kpi_casd($kpi_id,$begin,$end);
		if ($casd_count > 0) {
			$casd_ls = $this->rkk_model3->get_kpi_casd_list($kpi_id,$begin,$end);
			$post_ls = array();
			foreach ($casd_ls as $casd) {
				$key = $casd->isSAP .'|'. $casd->PositionID;
				$c_post = count($this->org_model->get_Position_row($casd->PositionID,$casd->isSAP,$begin,$end));
				if($c_post) {
					$post_ls[$key] = $this->org_model->get_Position_row($casd->PositionID,$casd->isSAP,$begin,$end)->PositionName;
				} else {
					$post_ls[$key] = '';
				}
			}
			$data['casd_ls']       = $casd_ls;
			$data['post_ls']       = $post_ls;
			$data['kpi_id_A']      = $kpi_id;
			$data['link_edit']     = 'objective/rkk/edit_kpi/';
			$data['link_detail']   = 'objective/rkk/detail_kpi/';
			$data['link_remove']   = 'objective/rkk/remove_kpi/';

			$this->load->view('objective/rkk/kpi_casd_list', $data, FALSE);

		} else {
			$data['notif_type'] = '';
			$data['notif_text'] = 'This KPI doesn&#39;t have Cascading KPI';
			$this->load->view('template/notif_view', $data, FALSE);
		}
	}

	public function view($rkk_id,$start,$end)
	{
		$self_rkk     = $this->rkk_model3->get_rkk_row($rkk_id);
		// $rel_rkk      = $this->rkk_model3->get_rkk_rel_last($rkk_id,$self_rkk->BeginDate,$self_rkk->EndDate);
		// $persp_ls     = $this->general_model->get_Perspective_List($self_rkk->BeginDate,$self_rkk->EndDate);
		$rel_rkk      = $this->rkk_model3->get_rkk_rel_last($rkk_id,$start,$end);
		// MUST Cek Relasi RKK , Jika tidak ada kasih peringatan

		$persp_ls     = $this->general_model->get_Perspective_List($start,$end);
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
			$data['is_sub']       = TRUE;
		} else {
			$data['is_sub']       = FALSE;

		}

		foreach ($persp_ls as $persp) {
			$so_ls[$persp->PerspectiveID] = $this->rkk_model3->get_so_persp_list($rkk_id,$persp->PerspectiveID,$self_rkk->BeginDate,$self_rkk->EndDate,0);

			$persp_weight[$persp->PerspectiveID] = $this->rkk_model3->sum_weight_persp($rkk_id,$persp->PerspectiveID,$self_rkk->BeginDate,$self_rkk->EndDate);

		}
		$data['so_ls']       = $so_ls;
		$data['persp_weight'] = $persp_weight;
		$this->load->view('objective/rkk/rkk_subd_view',$data);
	}

	public function view_subordinate($chief_rkk_id,$nik,$post,$is_sap,$begin,$end)
	{
		$user_dtl     = $this->account_model->get_User_byNIK($nik);
		$filter_start = $begin;
		$filter_end   = $end;
		$post 				= $this->org_model->get_Position_row($post,$is_sap,$begin,$end);
		$data['post']					= $post;
		$data['link_self']    = 'objective/rkk/';
		$data['filter_start'] = substr($filter_start, 0,10);
		$data['filter_end']   = substr($filter_end, 0,10);
		$data['user_dtl']     = $user_dtl;

		$this->load->view('objective/rkk/subordinate_view', $data);
	}

	public function create_so($rkk_id=0,$persp_id = 0)
	{
		$rkk   = $this->rkk_model3->get_rkk_row($rkk_id);
		$org   = $this->org_model->get_Position_row($rkk->PositionID,$rkk->isSAP);
		$persp = $this->general_model->get_Perspective_row($persp_id);

		$hidden = array(
			'rkk_id'	 => $rkk_id,
			'persp_id' => $persp_id,
			'org_id'   => $org->OrganizationID,
		);

		$data['begin']   = substr($rkk->BeginDate, 0,10);
		$data['end']     = substr($rkk->EndDate,0,10);
		$data['persp']   = $persp;
		$data['process'] = 'objective/rkk/create_so_process/';
		$data['hidden']  = $hidden;
		$this->load->view('objective/rkk/so/add_form',$data);
	}

	public function ajax_so_field($num=1)
	{
		if (is_numeric($num) == TRUE && $num >0) {
			for ($i=1; $i <= $num ; $i++) {
				$data['num'] = $i;
				$this->load->view('objective/rkk/so/field_form',$data);
			}
		}
	}

	public function create_so_process()
	{
		$rkk_id   = $this->input->post('rkk_id');
		$persp_id = $this->input->post('persp_id');
		$org_id   = $this->input->post('org_id');

		$this->form_validation->set_rules('dt_begin', 'Begin', 'required');
		$this->form_validation->set_rules('dt_end', 'End', 'required');
		$this->form_validation->set_rules('nm_so', 'SO Number', 'required|greater_than[0]');

		$num      = $this->input->post('nm_so');
		$begin    = $this->input->post('dt_begin');
		$end      = $this->input->post('dt_end') ;
		if (is_numeric($num) == TRUE && $num > 0) {

			for ($i=1; $i <= $num; $i++) {
				$this->form_validation->set_rules('txt_so_'.$i, 'Strategic Objective', 'trim|required|max_length[100]|xss_clean');
				$this->form_validation->set_rules('txt_desc_'.$i, 'Description', 'trim|xss_clean');
			}

			if ($this->form_validation->run() == TRUE) {
				$counter = 0;
				for ($i=1; $i <= $num; $i++) {
					$so_text  = $this->input->post('txt_so_'.$i);
					$so_desc  = str_replace("'", '&#39;', $this->input->post('txt_desc_'.$i));
					$this->rkk_model3->add_so($org_id,$persp_id,$so_text,$so_desc,$begin,$end);
					$counter++;
					$data['notif_text'] = 'Success create '.$counter.' Strategic Objective';
					$data['notif_type'] = 'alert-success';
				}
			} else {
				$data['notif_text'] = validation_errors();
				$data['notif_type'] = 'alert-error';
			}

		} else {
			$data['notif_text'] = 'Error create Strategic Objective';
			$data['notif_type'] = 'alert-error';
		}
		$data['link'] = 'objective/rkk/create_so/'.$rkk_id.'/'.$persp_id;

		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_submit_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}

	public function edit_so($so_id=0)
	{
		$so    = $this->rkk_model3->get_so_row($so_id);
		$persp = $this->general_model->get_Perspective_row($so->PerspectiveID);

		$hidden = array(
			'so_id'	 => $so_id
		);
		$data['so_text'] = $so->SasaranStrategis;
		$data['so_desc'] = $so->Description;
		$data['begin']   = substr($so->BeginDate, 0,10);
		$data['end']     = substr($so->EndDate,0,10);
		$data['persp']   = $persp;
		$data['process'] = 'objective/rkk/edit_so_process/';
		$data['hidden']  = $hidden;
		$this->load->view('objective/rkk/so/edit_form',$data);
	}

	public function edit_so_process()
	{
		$so_id   = $this->input->post('so_id');
		$so      = $this->rkk_model3->get_so_row($so_id);

		$this->form_validation->set_rules('dt_begin', 'Begin', 'required');
		$this->form_validation->set_rules('dt_end', 'End', 'required');
		$this->form_validation->set_rules('txt_so', 'Strategic Objective', 'trim|required|max_length[100]|xss_clean');
		$this->form_validation->set_rules('txt_desc', 'Description', 'trim|xss_clean');

		if ($this->form_validation->run() == TRUE) {
			$so_text = $this->input->post('txt_so');
			$so_desc = str_replace("'", '&#39;', $this->input->post('txt_desc'));

			$begin   = $this->input->post('dt_begin');
			$end     = $this->input->post('dt_end');
			$this->rkk_model3->edit_so($so_id,$so->PerspectiveID,$so_text,$so_desc,$begin,$end);
			$data['notif_text'] = 'Success edit Strategic Objective';
			$data['notif_type'] = 'alert-success';

		} else {
			$data['notif_text'] = validation_errors();
			$data['notif_type'] = 'alert-error';
		}
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}

	public function remove_so($so_id=0)
	{
		$so    = $this->rkk_model3->get_so_row($so_id);
		$persp = $this->general_model->get_Perspective_row($so->PerspectiveID);

		$hidden = array(
			'so_id'	 => $so_id
		);
		$data['so_text'] = $so->SasaranStrategis;
		$data['so_desc'] = $so->Description;
		$data['end']     = substr($so->EndDate,0,10);
		$data['persp']   = $persp;
		$data['process'] = 'objective/rkk/remove_so_process/';
		$data['hidden']  = $hidden;
		$this->load->view('objective/rkk/so/rm_form',$data);
	}

	public function remove_so_process()
	{
		$so_id   = $this->input->post('so_id');
		$action  = $this->input->post('rd_action');
		switch ($action) {
			case 'delimit':
				$this->form_validation->set_rules('dt_end', 'End', 'required');

				if ($this->form_validation->run() == TRUE) {
					$end = $this->input->post('dt_end') ;
					$this->rkk_model3->delimit_so($so_id,$end);
					$data['notif_text'] = 'Success delimit Strategic Objective';
					$data['notif_type'] = 'alert-success';

				} else {
					$data['notif_text'] = validation_errors();
					$data['notif_type'] = 'alert-error';
				}
				break;
			case 'remove':

				$count = $this->rkk_model3->count_kpi_so($so_id);
				if ($count>0) {
					$data['notif_text'] = 'Cannot remove Strategic Objective. SO have KPI';
					$data['notif_type'] = 'alert-error';
				} else {
					$this->rkk_model3->remove_so($so_id);
					$data['notif_text'] = 'Success remove Strategic Objective';
					$data['notif_type'] = 'alert-success';
				}
				break;
		}

		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}

	public function create_kpi($rkk_id=0,$so_id=0)
	{
		$so  = $this->rkk_model3->get_so_row($so_id);
		$rkk = $this->rkk_model3->get_rkk_row($rkk_id);
		$hidden = array(
			'rkk_id' => $rkk_id,
			'so_id' => $so_id,

		);
		$data['so']					= $so;
		$data['process']    = 'objective/rkk/create_kpi_process';
		$data['hidden']     = $hidden;

		$generic_ls = $this->general_model->get_GenericKPI_Search($so->PerspectiveID);
		$satuan_ls  = $this->general_model->get_Satuan_list($rkk->BeginDate,$rkk->EndDate);
		$formula_ls = $this->general_model->get_PCFormula_list(0,'',$rkk->BeginDate,$rkk->EndDate);
		$ytd_ls     = $this->general_model->get_YTD_list($rkk->BeginDate,$rkk->EndDate);

		$generic_opt = array(''=>'','other'=>'Other');
		foreach ($generic_ls as $row) {
			$generic_opt[$row->KPIGenericID] = $row->KPI;
		}

		$satuan_opt = array(''=>'');
		foreach ($satuan_ls as $row) {
			$satuan_opt[$row->SatuanID] = $row->Satuan;
		}

		$formula_opt = array(''=>'');
		foreach ($formula_ls as $row) {
			$formula_opt[$row->PCFormulaID] = $row->PCFormula;
		}

		$ytd_opt = array(''=>'');
		foreach ($ytd_ls as $row) {
			$ytd_opt[$row->YTDID] = $row->YTD;
		}
		$targets = array();
		$months  = array();
		for ($i=1; $i <= 12 ; $i++) {
			$targets[$i] = '';
			$months[$i]  = FALSE;
		}

		$data['min_month']  = (int)substr($rkk->BeginDate, 5,2);
		$data['max_month']  = (int)substr($rkk->EndDate, 5,2);
		$data['generic_ls'] = $generic_opt;
		$data['satuan_ls']  = $satuan_opt;
		$data['formula_ls'] = $formula_opt;
		$data['ytd_ls']     = $ytd_opt;

		$data['begin']      = substr($rkk->BeginDate, 0,10);
		$data['end']        = substr($rkk->EndDate, 0,10);
		$data['generic']    = 'other';
		$data['kpi_text']   = '';
		$data['kpi_desc']   = '';
		$data['satuan']     = '';
		$data['formula']    = '';
		$data['ytd']        = '';
		$data['weight']     = 0.00;
		$data['base']       = 0.00;
		$data['targets']    = $targets;
		$data['months']     = $months;

		$this->load->view('objective/rkk/kpi/add_form',$data);
	}

	public function create_kpi_process()
	{
		$rkk_id    = $this->input->post('rkk_id');
		$so_id     = $this->input->post('so_id');
		$rkk       = $this->rkk_model3->get_rkk_row($rkk_id);
		$rkk_begin = strtotime($rkk->BeginDate);
		$rkk_end   = strtotime($rkk->EndDate);

		$this->form_validation->set_rules('dt_begin', 'Begin ', 'trim|required|xss_clean');
		$this->form_validation->set_rules('dt_end', 'End ', 'trim|required|xss_clean');
		$this->form_validation->set_rules('slc_generic', 'Generic KPI ', 'required');
		$this->form_validation->set_rules('nm_weight', 'Weight', 'trim|greater_than[0]|required|xss_clean');
		$this->form_validation->set_rules('nm_base', 'Baseline', 'trim|numeric|required|xss_clean');

		#KPI
		$begin   = $this->input->post('dt_begin');
		$end     = $this->input->post('dt_end');
		$generic = $this->input->post('slc_generic');
		$weight  = $this->input->post('nm_weight');
		$base  	 = $this->input->post('nm_base');
		if (strtotime($begin) < $rkk_begin || strtotime($end) > $rkk_end) {
			$data['notif_type'] = 'alert-error';
			$data['notif_text'] = 'Error Create KPI. KPI Begin Date and End Date must in RKK Range Date';
		} else {

			if ($generic == 'other') {
				$this->form_validation->set_rules('txt_kpi', 'KPI', 'trim|max_length[500]|required|xss_clean');
				$this->form_validation->set_rules('txt_desc', 'Description', 'trim|xss_clean');
				$this->form_validation->set_rules('slc_satuan', 'Count Unit', 'required');
				$this->form_validation->set_rules('slc_formula', 'Formula', 'required');
				$this->form_validation->set_rules('slc_ytd', 'Year to Date', 'required');

				$kpi_text   = $this->input->post('txt_kpi');
				$kpi_desc   = str_replace("'", '&#39;', $this->input->post('txt_desc'));
				$satuan     = $this->input->post('slc_satuan');
				$formula    = $this->input->post('slc_formula');
				$ytd        = $this->input->post('slc_ytd');
				$generic_id = 0;
			} elseif ($generic != '') {
				$generic_id = $generic;
				$generic 		= $this->general_model->get_GenericKPI_row($generic_id);
				$kpi_text   = $generic->KPI;
				$kpi_desc   = $generic->Description;
				$satuan     = $generic->SatuanID;
				$formula    = $generic->PCFormulaID;
				$ytd        = $generic->YTDID;
			}

			if ($this->form_validation->run() == TRUE) {
				#Target
				$months = $this->input->post('chk_months');
				$targets = array();
				if (count($months)) {

					foreach ($months as $key => $month) {
						$targets[$month] = $this->input->post('nm_target_'.$month);
					}

					$this->rkk_model3->add_kpi($rkk_id, $generic_id, $so_id, $satuan ,$formula  ,$ytd  ,$kpi_text ,$kpi_desc , $weight, $base, $begin, $end, $targets);
					$data['notif_type'] = 'alert-success';
					$data['notif_text'] = 'Success Create KPI';
				} else {
					$data['notif_type'] = 'alert-error';
					$data['notif_text'] = 'Error Create KPI. KPI must have Target';
				}

			} else {
				$data['notif_type'] = validation_errors();
				$data['notif_text'] = 'RKK not available';
			}
		}
		$data['link'] = 'objective/rkk/create_kpi/'.$rkk_id.'/'.$so_id;
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_submit_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}

	public function edit_kpi($kpi_id)
	{
		$kpi        = $this->rkk_model3->get_kpi_row($kpi_id);
		$so         = $this->rkk_model3->get_so_row($kpi->SasaranStrategisID);
		$generic_ls = $this->general_model->get_GenericKPI_Search($so->PerspectiveID);
		$satuan_ls  = $this->general_model->get_Satuan_list($kpi->KPI_BeginDate,$kpi->KPI_EndDate);
		$formula_ls = $this->general_model->get_PCFormula_list(0,'',$kpi->KPI_BeginDate,$kpi->KPI_EndDate);
		$ytd_ls     = $this->general_model->get_YTD_list($kpi->KPI_BeginDate,$kpi->KPI_EndDate);

		$generic_opt = array(''=>'','other'=>'Other');
		foreach ($generic_ls as $row) {
			$generic_opt[$row->KPIGenericID] = $row->KPI;
		}

		$satuan_opt = array(''=>'');
		foreach ($satuan_ls as $row) {
			$satuan_opt[$row->SatuanID] = $row->Satuan;
		}

		$formula_opt = array(''=>'');
		foreach ($formula_ls as $row) {
			$formula_opt[$row->PCFormulaID] = $row->PCFormula;
		}

		$ytd_opt = array(''=>'');
		foreach ($ytd_ls as $row) {
			$ytd_opt[$row->YTDID] = $row->YTD;
		}

		$targets = array();
		$months  = array();

		for ($i=1; $i <= 12 ; $i++) {
			$c_target = $this->rkk_model3->count_target_month($kpi_id,$i);
			if ($c_target) {
				$target = $this->rkk_model3->get_target_month_row($kpi_id,$i);
				$months[$i]  = TRUE;
				$targets[$i] = $target->Target;
			} else {
				$months[$i]  = FALSE;
				$targets[$i] = '';
			}
		}

		$data['so']				  = $so;
		$data['generic_ls'] = $generic_opt;
		$data['satuan_ls']  = $satuan_opt;
		$data['formula_ls'] = $formula_opt;
		$data['ytd_ls']     = $ytd_opt;

		$data['begin']      = substr($kpi->KPI_BeginDate,0,10);
		$data['end']        = substr($kpi->KPI_EndDate,0,10);
		if (is_null($kpi->KPIGenericID) OR $kpi->KPIGenericID == 0 || $kpi->KPIGenericID =='') {
			$data['generic']    = 'other';
		} else {
			$data['generic']    = $kpi->KPIGenericID;
		}
		$data['kpi_text']   = $kpi->KPI;
		$data['kpi_desc']   = $kpi->Description;
		$data['satuan']     = $kpi->SatuanID;
		$data['formula']    = $kpi->PCFormulaID;
		$data['ytd']        = $kpi->YTDID;
		$data['weight']     = $kpi->Bobot;
		$data['base']       = $kpi->Baseline;
		$data['targets']    = $targets;
		$data['months']     = $months;

		$hidden = array(
			'kpi_id' => $kpi_id
		);
		$data['process'] = 'objective/rkk/edit_kpi_process';
		$data['hidden']  = $hidden;

		$this->load->view('objective/rkk/kpi/edit_form',$data);
	}

	public function edit_kpi_process()
	{
		$kpi_id = $this->input->post('kpi_id');
		$kpi    = $this->rkk_model3->get_kpi_row($kpi_id);

		$rkk       = $this->rkk_model3->get_rkk_row($kpi->RKKID);
		$rkk_begin = strtotime($rkk->BeginDate);
		$rkk_end   = strtotime($rkk->EndDate);

		$this->form_validation->set_rules('dt_begin', 'Begin ', 'trim|required|xss_clean');
		$this->form_validation->set_rules('dt_end', 'End ', 'trim|required|xss_clean');
		$this->form_validation->set_rules('slc_generic', 'Generic KPI ', 'required');
		$this->form_validation->set_rules('nm_weight', 'Weight', 'trim|greater_than[0]|required|xss_clean');
		$this->form_validation->set_rules('nm_base', 'Baseline', 'trim|numeric|required|xss_clean');
		$begin   = $this->input->post('dt_begin');
		$end     = $this->input->post('dt_end');
		$generic = $this->input->post('slc_generic');
		$weight  = $this->input->post('nm_weight');
		$base  	 = $this->input->post('nm_base');

		if (strtotime($begin) < $rkk_begin || strtotime($end) > $rkk_end) {
			$data['notif_type'] = 'alert-error';
			$data['notif_text'] = 'Error Edit KPI. KPI Begin Date and End Date must in RKK Range Date';
		} else {

			if ($generic == 'other') {
				$this->form_validation->set_rules('txt_kpi', 'KPI', 'trim|max_length[500]|required|xss_clean');
				$this->form_validation->set_rules('txt_desc', 'Description', 'trim|xss_clean');
				$this->form_validation->set_rules('slc_satuan', 'Count Unit', 'required');
				$this->form_validation->set_rules('slc_formula', 'Formula', 'required');
				$this->form_validation->set_rules('slc_ytd', 'Year to Date', 'required');

				$kpi_text   = $this->input->post('txt_kpi');
				$kpi_desc   = str_replace("'", '&#39;', $this->input->post('txt_desc'));
				$satuan     = $this->input->post('slc_satuan');
				$formula    = $this->input->post('slc_formula');
				$ytd        = $this->input->post('slc_ytd');
				$generic_id = 0;
			} else {
				$generic_id = $generic;
				$generic 		= $this->general_model->get_GenericKPI_row($generic_id);
				$kpi_text   = $generic->KPI;
				$kpi_desc   = $generic->Description;
				$satuan     = $generic->SatuanID;
				$formula    = $generic->PCFormulaID;
				$ytd        = $generic->YTDID;
			}

			if ($this->form_validation->run() == TRUE) {
				$months  = $this->input->post('chk_months');
				if (count($months)) {
					$targets = array();
					foreach ($months as $key => $month) {
						$targets[$month] = $this->input->post('nm_target_'.$month);
					}
					$this->rkk_model3->edit_kpi($kpi_id,$generic_id, $kpi->SasaranStrategisID, $satuan,$formula,$ytd,$kpi_text,$kpi_desc, $weight, $base, $begin,$end,$months,$targets);

					$data['notif_type'] = 'alert-success';
					$data['notif_text'] = 'Success Edit KPI & Target';

				} else {
					$data['notif_type'] = 'alert-error';
					$data['notif_text'] = 'Error Edit KPI. KPI must have Target';
				}
			} else {
				$data['notif_type'] = 'alert-error';
				$data['notif_text'] = validation_errors();

			}
		}
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view', $data, FALSE);
		$this->load->view('template/bottom_popup_1_view');

	}

	public function remove_kpi($kpi_id)
	{
		$kpi = $this->rkk_model3->get_kpi_row($kpi_id);
		$hidden = array(
			'kpi_id'	 => $kpi_id
		);
		$data['kpi_text'] = $kpi->KPI;
		$data['kpi_desc'] = $kpi->Description;
		$data['end']     = substr($kpi->KPI_EndDate,0,10);
		// $data['persp']   = $persp;
		$data['process'] = 'objective/rkk/remove_kpi_process/';
		$data['hidden']  = $hidden;
		$this->load->view('objective/rkk/kpi/rm_form',$data);
	}

	public function remove_kpi_process()
	{
		$kpi_id = $this->input->post('kpi_id');
		$action  = $this->input->post('rd_action');
		switch ($action) {
			case 'delimit':
				$this->form_validation->set_rules('dt_end', 'End', 'required');

				if ($this->form_validation->run() == TRUE) {
					$end = $this->input->post('dt_end') ;
					$this->rkk_model3->delimit_kpi($kpi_id,$end);
					$data['notif_text'] = 'Success delimit KPI';
					$data['notif_type'] = 'alert-success';

				} else {
					$data['notif_text'] = validation_errors();
					$data['notif_type'] = 'alert-error';
				}
				break;
			case 'remove':
				$kpi = $this->rkk_model3->get_kpi_row($kpi_id);
				$c_casd = $this->rkk_model3->count_kpi_rel_AB($kpi_id,$kpi->KPI_BeginDate,$kpi->KPI_EndDate);
				$c_acvh = $this->rkk_model3->count_acvh_kpi($kpi_id);
				if ($c_casd>0 OR $c_acvh >0) {
					$data['notif_text'] = 'Cannot remove KPI.KPI have Cascading or Achievement';
					$data['notif_type'] = 'alert-error';
				} else {
					$this->rkk_model3->remove_kpi($kpi_id);
					$data['notif_text'] = 'Success remove KPI';
					$data['notif_type'] = 'alert-success';
				}
				break;
		}

		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}

	public function detail_kpi($kpi_id)
	{
		$kpi       = $this->rkk_model3->get_kpi_row($kpi_id);
		$target_ls = $this->rkk_model3->get_target_list($kpi_id);

		$targets = array('-');
		for ($i=1; $i <=12 ; $i++) {
			$c_target = $this->rkk_model3->count_target_month($kpi_id,$i);
			if ($c_target) {
				$target = $this->rkk_model3->get_target_month_row($kpi_id,$i);
				$targets[$i] = round($target->Target,3);
			} else {
				$targets[$i] = '-';
			}
		}

		$c_rel_BA   = $this->rkk_model3->count_kpi_rel_BA($kpi_id,$kpi->KPI_BeginDate,$kpi->KPI_EndDate);
		if ($c_rel_BA) {
			$rel_last  = $this->rkk_model3->get_kpi_rel_last($kpi_id,$kpi->KPI_BeginDate,$kpi->KPI_EndDate);
			$kpi_A     = $this->rkk_model3->get_kpi_row($rel_last->chief_kpi_id);
			$data['rel']      = $rel_last;
			$data['kpi_A']    = $kpi_A;
			$data['A_nama']   = $this->account_model->get_User_byNIK($kpi_A->NIK)->Fullname;
			$data['A_posisi'] = $this->org_model->get_Position_row($kpi_A->PositionID,$kpi_A->isSAP,$kpi_A->KPI_BeginDate,$kpi_A->KPI_EndDate)->PositionName;

		}


		$c_rel_AB = $this->rkk_model3->count_kpi_casd($kpi_id,$kpi->KPI_BeginDate,$kpi->KPI_EndDate);
		if ($c_rel_AB > 0) {
			$casd_ls = $this->rkk_model3->get_kpi_casd_list($kpi_id,$kpi->KPI_BeginDate,$kpi->KPI_EndDate);
			$post_ls = array();
			$rel_ls = array();
			foreach ($casd_ls as $casd) {
				$key = $casd->isSAP .'|'. $casd->PositionID;
				$post_ls[$key] = $this->org_model->get_Position_row($casd->PositionID,$casd->isSAP,$kpi->KPI_BeginDate,$kpi->KPI_EndDate)->PositionName;
				$rel_ls[$key]  = $this->rkk_model3->get_kpi_rel_last($casd->KPIID,$kpi->KPI_BeginDate,$kpi->KPI_EndDate);

			}
			$data['casd_ls']       = $casd_ls;
			$data['post_ls']       = $post_ls;
			$data['rel_ls']        = $rel_ls;
		}

		$data['c_rel_BA'] = $c_rel_BA;
		$data['c_rel_AB'] = $c_rel_AB;
		$data['kpi']     = $kpi;
		$data['targets'] = $targets;
		$this->load->view('objective/rkk/kpi/detail_view', $data, FALSE);
	}

	public function cascade_kpi($kpi_id)
	{
		$kpi_A = $this->rkk_model3->get_kpi_row($kpi_id); //KPI Atasan

		$targets_A = array();
		for ($i=1; $i <=12 ; $i++) {
			if ($this->rkk_model3->count_target_month($kpi_id,$i,$kpi_A->KPI_BeginDate,$kpi_A->KPI_EndDate)) {
				$target = $this->rkk_model3->get_target_month_row($kpi_id,$i,$kpi_A->KPI_BeginDate,$kpi_A->KPI_EndDate)->Target;
				$targets_A[$i] = round($target,2);

			} else {
				$targets_A[$i] = '-';
			}
		}

		$refs = $this->general_model->get_Reference_List($kpi_A->KPI_BeginDate,$kpi_A->KPI_EndDate);
		$ref_ls = array('' => '');
		foreach ($refs as $row) {
			$ref_ls[$row->ReferenceID] = $row->Reference;
		}

		$hidden = array(
			'kpi_id' => $kpi_id
		);
		$data['process']   = 'objective/rkk/cascade_kpi_process';
		$data['hidden']		 = $hidden;
		$data['kpi_A']     = $kpi_A;
		$data['targets_A'] = $targets_A;

		$data['ref_ls']    = $ref_ls;

		$this->load->view('objective/rkk/cascade_form', $data, FALSE);
	}

	public function ajax_cascade_field($kpi_id,$num=1)
	{
		$kpi_A = $this->rkk_model3->get_kpi_row($kpi_id); //KPI Atasan
		$rkk_B = $this->rkk_model3->get_rkk_rel_AB_list($kpi_A->NIK,$kpi_A->PositionID,$kpi_A->isSAP,$kpi_A->KPI_BeginDate,$kpi_A->KPI_EndDate,'open'); //RKK Bawahan
		$subd_ls = array(''=>'');
		foreach ($rkk_B as $row) {
			$post_name = $this->org_model->get_Position_row($row->PositionID,$row->isSAP,$row->BeginDate,$row->EndDate)->PositionName;

			$subd_ls[$row->RKKID] = $row->NIK .' - '. $row->Fullname .' ('.$post_name .')';

			unset($post_name);
		}

		$data['subd_ls']   = $subd_ls;

		if (is_numeric($num) == TRUE && $num >0) {
			for ($i=1; $i <= $num ; $i++) {
				$data['num'] = $i;
				$this->load->view('objective/rkk/cascade_field',$data);

			}
		}
	}

	public function ajax_cascade_date()
	{
		$rkk_id = $this->input->post('rkk_id');
		$rkk    = $this->rkk_model3->get_rkk_row($rkk_id);
		$begin  = substr($rkk->BeginDate, 0,10);
		$end    = substr($rkk->EndDate, 0,10);
		echo trim($begin.'|'.$end);
	}

	public function cascade_kpi_process()
	{
		$kpi_id   = $this->input->post('kpi_id');
		$kpi_A    = $this->rkk_model3->get_kpi_row($kpi_id);
		$casd_num = $this->input->post('nm_cascd');
		$this->form_validation->set_rules('slc_ref', 'Reference Type', 'trim|required|xss_clean');
		$month = array();
		$flag = TRUE;
		for ($num=1; $num <= $casd_num ; $num++) {
			$this->form_validation->set_rules('slc_subd_'.$num, 'Cascade to #'.$num, 'trim|required|xss_clean');
			$this->form_validation->set_rules('dt_begin_'.$num, 'Begin #'.$num, 'trim|required|xss_clean');
			$this->form_validation->set_rules('dt_end_'.$num, 'End #'.$num, 'trim|required|xss_clean');
			$this->form_validation->set_rules('txt_kpi_'.$num, 'KPI #'.$num, 'trim|required|max_length[500]|xss_clean');
			$this->form_validation->set_rules('txt_desc_'.$num, 'Description #'.$num, 'trim|xss_clean');
			$month[$num] = $this->input->post('chk_month_'.$num);
			if (count($month[$num])==0) {
				$flag = FALSE;
			}

		}

		if ($this->form_validation->run()==TRUE) {
			if ($flag == TRUE) {
				# prosess simpan
				$ref_type = $this->input->post('slc_ref');
				for ($num=1; $num <= $casd_num ; $num++) {
					$rkk_id    = $this->input->post('slc_subd_'.$num);
					$rkk_B     = $this->rkk_model3->get_rkk_row($rkk_id);
					if ($ref_type == 3) {
						$ref_weight = $this->input->post('nm_ref_weight_'.$num);
					} else {
						$ref_weight = 0;
					}
					$kpi_text  = $this->input->post('txt_kpi_'.$num);
					$begin     = $this->input->post('dt_begin_'.$num);
					$end       = $this->input->post('dt_end_'.$num);
					$desc_text = str_replace("'", '&#39;', $this->input->post('txt_desc_'.$num));
					$weight    = $this->input->post('nm_weight_'.$num);
					$base      = $this->input->post('nm_base_'.$num);
					$targets   = array();
					foreach ($month[$num] as $key => $bln) {
						$targets[$bln] = $this->input->post('nm_target_'.$num.'_'.$bln);
					}
					// $this->rkk_model3->add_kpi($rkk_id, 0, $kpi_A->SasaranStrategisID, $kpi_A->SatuanID ,$kpi_A->PCFormulaID ,$kpi_A->YTDID ,$kpi_text,$desc_text, $weight, $base, $rkk_B->BeginDate ,$rkk_B->EndDate, $targets, $kpi_id, $ref_weight, $ref_type,$rkk_B->BeginDate,$rkk_B->EndDate);
					$this->rkk_model3->add_kpi($rkk_id, 0, $kpi_A->SasaranStrategisID, $kpi_A->SatuanID ,$kpi_A->PCFormulaID ,$kpi_A->YTDID ,$kpi_text,$desc_text, $weight, $base, $begin,$end, $targets, $kpi_id, $ref_weight, $ref_type,$begin,$end);
				}

				$data['notif_text'] = 'Cascading Success';
				$data['notif_type'] = 'alert-success';
			} else {
				$data['notif_text'] = 'KPI must have Target';
				$data['notif_type'] = 'alert-error';
			}
		} else{
			$data['notif_text'] = validation_errors();
			$data['notif_type'] = 'alert-error';
		}

		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');

	}

	public function relation_kpi_AB($kpi_id)
	{
		$kpi     = $this->rkk_model3->get_kpi_row($kpi_id);
		$kpi_rel = $this->rkk_model3->get_kpi_rel_AB_list($kpi_id,$kpi->KPI_BeginDate,$kpi->KPI_EndDate);

		$post_ls = array();
		foreach ($kpi_rel as $row) {

			$post_ls[$row->R_KPIID] = $this->org_model->get_Position_row($row->PositionID,$row->isSAP,$kpi->KPI_BeginDate,$kpi->KPI_EndDate)->PositionName;
		}
		$hidden = array(
			'kpi_id' => $kpi_id
		);
		$data['kpi']      = $kpi;
		$data['kpi_rel']  = $kpi_rel;
		$data['post_ls']  = $post_ls;
		$data['end']      = date('Y-m-d');
		$data['link_add'] = 'objective/rkk/add_rel_kpi_AB/'.$kpi_id;
		$data['process']  = 'objective/rkk/remove_rel_kpi_AB';
		$data['hidden']   = $hidden;


		$this->load->view('objective/rkk/kpi/relation_view',$data);

	}
	public function remove_rel_kpi_AB()
	{
		$kpi_id  = $this->input->post('kpi_id');
		$action  = $this->input->post('rd_action');
		$rel_kpi = $this->input->post('chk_rel');
		$this->form_validation->set_rules('rd_action', 'Action', 'required');

		switch ($action) {
			case 'delimit':
				$this->form_validation->set_rules('dt_end', 'End', 'required');

				if (count($rel_kpi)) {
					if ($this->form_validation->run() == TRUE) {
						$end = $this->input->post('dt_end') ;

						foreach ($rel_kpi as $key => $r_kpi) {
							$this->rkk_model3->delimit_kpi_rel($r_kpi,$end);
						}

						$data['notif_text'] = 'Success delimit KPI Relation';
						$data['notif_type'] = 'alert-success';

					} else {
						$data['notif_text'] = validation_errors();
						$data['notif_type'] = 'alert-error';
					}
					# code...
				} else {
					$data['notif_text'] = 'Select one relation at least';
					$data['notif_type'] = 'alert-error';
				}

				break;
			case 'remove':

				if (count($rel_kpi)) {
					if ($this->form_validation->run() == TRUE) {
						foreach ($rel_kpi as $key => $r_kpi) {
							$this->rkk_model3->remove_kpi_rel($r_kpi);
						}

						$data['notif_text'] = 'Success remove KPI Relation';
						$data['notif_type'] = 'alert-success';

					} else {
						$data['notif_text'] = validation_errors();
						$data['notif_type'] = 'alert-error';
					}
					# code...
				} else {
					$data['notif_text'] = 'Select one relation at least';
					$data['notif_type'] = 'alert-error';
				}

				break;
		}
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}

	public function add_rel_kpi_AB($kpi_id)
	{
		$kpi_A     = $this->rkk_model3->get_kpi_row($kpi_id);
		$rkk_A     = $this->rkk_model3->get_rkk_row($kpi_A->RKKID);
		$rkk_AB_ls = $this->rkk_model3->get_rkk_rel_AB_list($rkk_A->NIK,$rkk_A->PositionID,$rkk_A->isSAP,$rkk_A->BeginDate,$rkk_A->EndDate);
		$kpi_B_ls  = array();
		$post_ls   = array();
		foreach ($rkk_AB_ls as $rkk_AB) {
			$kpi_B_ls[$rkk_AB->RKKID] = $this->rkk_model3->get_kpi_list($rkk_AB->RKKID,$rkk_AB->BeginDate,$rkk_AB->EndDate);
			$post_ls[$rkk_AB->RKKID]  = $this->org_model->get_Position_row($rkk_AB->PositionID,$rkk_AB->isSAP,$rkk_AB->BeginDate,$rkk_AB->EndDate)->PositionName;
		}
		$refs = $this->general_model->get_Reference_List($kpi_A->KPI_BeginDate,$kpi_A->KPI_EndDate);
		$ref_ls = array('' => '');
		foreach ($refs as $row) {
			$ref_ls[$row->ReferenceID] = $row->Reference;
		}

		$hidden = array(
			'kpi_id' => $kpi_id
		);
		$data['ref_ls']   = $ref_ls;
		$data['kpi_A']    = $kpi_A;
		$data['rkk_B']    = $rkk_AB_ls;
		$data['kpi_B_ls'] = $kpi_B_ls;
		$data['post_ls']  = $post_ls;
		$data['process']  = 'objective/rkk/add_rel_kpi_AB_process/';
		$data['hidden']   = $hidden;

		$this->load->view('objective/rkk/kpi/relation_form', $data, FALSE);
	}

	public function add_rel_kpi_AB_process()
	{
		$this->form_validation->set_rules('dt_begin', 'Begin Date', 'trim|required|min_length[10]|max_length[10]|xss_clean');
		$this->form_validation->set_rules('dt_end', 'End Date', 'trim|required|min_length[10]|max_length[10]|xss_clean');
		$this->form_validation->set_rules('slc_ref', 'Reference', 'trim|required|xss_clean');

		$kpi_id_A = $this->input->post('kpi_id');
		$kpi_id_B = $this->input->post('chk_kpi');

		$kpi_A      = $this->rkk_model3->get_kpi_row($kpi_id_A);
		$kpiA_begin = strtotime($kpi_A->KPI_BeginDate);
		$kpiA_end   = strtotime($kpi_A->KPI_EndDate);

		$c_success = 0;
		$c_failed  = 0;

		if ($this->form_validation->run() == TRUE) {
			$begin = $this->input->post('dt_begin');
			$end   = $this->input->post('dt_end');

			if (strtotime($begin) < $kpiA_begin || strtotime($end) > $kpiA_end) {
				$data['notif_type'] = 'alert-error';
				$data['notif_text'] = 'Error Create KPI Relation.  Begin Date and End Date must in KPI Range Date';
			} else {
				$ref   = $this->input->post('slc_ref');
				if (count($kpi_id_B)) {
					foreach ($kpi_id_B as $key => $kpi_id_B) {
						$kpi_B      = $this->rkk_model3->get_kpi_row($kpi_id_B);
						$kpiB_begin = strtotime($kpi_B->KPI_BeginDate);
						$kpiB_end   = strtotime($kpi_B->KPI_EndDate);
						if (strtotime($begin) < $kpiB_begin || strtotime($end) > $kpiB_end) {
							$c_failed++;
						} else {
							$ref_weight = $this->input->post('nm_ref_weight_'.$kpi_id_B);
							$this->rkk_model3->add_kpi_rel($kpi_id_B,$kpi_id_A,$ref,$ref_weight,$begin,$end);
							$c_success++;
						}
					}

				} else {
					redirect('objective/rkk/add_rel_kpi_AB/'.$kpi_id_A);

				}
				$this->rkk_model3->edit_kpi_contri($kpi_id_A,$ref);
				$data['link'] = 'objective/rkk/add_rel_kpi_AB/'.$kpi_id_A;
				$data['notif_text'] = 'Success Add '. $c_success.' KPI Relation with '. $c_failed.' Failed';
				$data['notif_type'] = 'alert-success';

			}
			$this->load->view('template/top_popup_1_view');
			$this->load->view('template/notif_submit_view',$data);
			$this->load->view('template/bottom_popup_1_view');
			# code...
		} else {
			redirect('objective/rkk/add_rel_kpi_AB/'.$kpi_id_A);
		}
	}
}

/* End of file rkk.php */
/* Location: ./application/controllers/objective/rkk.php */
