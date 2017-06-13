<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rkk_rel extends Controller {
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

		$this->load->view('manager/rel/main_view', $data);
	}

	public function show_relation()
	{
		$nik    = $this->input->post('nik');
		$holder = $this->input->post('holder');
		$start  = $this->input->post('start') ;
		$end    = $this->input->post('end');
		if ($holder == '') {
			$is_sap  = $this->input->post('is_sap');
			$post_id = $this->input->post('post_id');
		} else {
			list($is_sap,$holder_id) = explode('.',$holder);
			$holder_dtl = $this->account_model->get_Holder_row($holder_id,$is_sap);
			$post_id    = $holder_dtl->PositionID;
		}

		$rel_ls  = $this->rkk_model3->get_rkk_rel_AB_list($nik, $post_id, $is_sap, $start,$end);
		$post_ls = array();
		foreach ($rel_ls as $row) {
			$key           = $row->isSAP .'|'. $row->PositionID;
			$post_ls[$key] = $this->org_model->get_Position_row($row->PositionID,$row->isSAP,$start,$end)->PositionName; 
		}
		$data['rel_ls']   = $rel_ls;
		$data['post_ls']  = $post_ls;
		$data['end']      = $this->input->post('end');
		$data['link_add'] = 'manager/rkk_rel/add/'.$nik.'/'.$post_id.'/'.$is_sap.'/'.$start.'/'.$end;
		$data['process']  = 'manager/rkk_rel/delimit_process';

		$this->load->view('manager/rel/list_view.php', $data, FALSE);
	}

	public function delimit_process()
	{
		$action = $this->input->post('rd_action');
		$rel_ls = $this->input->post('chk_rel');
		if (count($rel_ls)) {
			switch ($action) {
				case 'delimit':
					$this->form_validation->set_rules('dt_end', 'End', 'required');

					if ($this->form_validation->run() == TRUE) {
						$end = $this->input->post('dt_end') . ' 23:59:59.000';

						foreach ($rel_ls as $key => $rel_id) {
							$this->rkk_model3->delimit_rkk_rel($rel_id,$end);
						}
						$data['notif_text'] = 'Success delimit RKK Relation';
						$data['notif_type'] = 'alert-success';

					} else {
						$data['notif_text'] = validation_errors();
						$data['notif_type'] = 'alert-error';
					}
					break;
				case 'remove':
					
						foreach ($rel_ls as $key => $rel_id) {
							$this->rkk_model3->remove_rkk_rel($rel_id);
						}
						$data['notif_text'] = 'Success remove RKK Relation';
						$data['notif_type'] = 'alert-success';
					
					break;
			}
			
		} else {
			$data['notif_text'] = 'Select at least one relation';
			$data['notif_type'] = 'alert-error';
		}
		// $this->load->view('template/notif_view',$data);
		redirect('manager/rkk_rel');
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


			if ($count_rkk > 0) {
				$rkk = $this->rkk_model3->get_rkk_holder_last($nik,$post_id,$is_sap, $filter_start, $filter_end,'approve');
				$begin = $this->input->post('start');
				$end = $this->input->post('end');
			}
			$data['sub_ls']   = $sub_ls;

			$this->load->view('template/subordinate_view', $data, FALSE);
		}
	}

	public function add($nik,$post_id,$is_sap,$start,$end)
	{
		$hidden = array(
			'nik' => $nik,
			'post_id' => $post_id,
			'is_sap' => $is_sap
		);
		$sub_ls = $this->org_model->get_directSubordinate_list($is_sap,$post_id,$start,$end);

		$i = 0;
		$rkk_B_ls = array();

		foreach ($sub_ls as $row) {
			$c_rkk = $this->rkk_model3->count_rkk_holder($row->NIK,$row->PositionID,$row->isSAP,$start,$end);
			if ($c_rkk) {
				$rkk_B = $this->rkk_model3->get_rkk_holder_last($row->NIK,$row->PositionID,$row->isSAP, $start, $end);
				
				$rkk_B_ls[$i]['RKKID'] = $rkk_B->RKKID;
				$rkk_B_ls[$i]['emp']   = $rkk_B->NIK .' - '. $rkk_B->Fullname;
				$rkk_B_ls[$i]['post'] = $this->org_model->get_Position_row($row->PositionID,$row->isSAP,$start,$end)->PositionName; 
				$rkk_B_ls[$i]['begin'] = substr($rkk_B->BeginDate,0,10);
				$rkk_B_ls[$i]['end'] = substr($rkk_B->EndDate,0,10);
				$i++;
			}
		}

		$data['begin']    = substr($start, 0,10);
		$data['end']      = substr($end, 0,10);
		$data['rkk_B_ls'] = $rkk_B_ls;
		$data['max_i']    = $i;

		$data['process']  = 'manager/rkk_rel/add_process';
		$data['hidden']   = $hidden;
		$this->load->view('manager/rel/add_form', $data, FALSE);
	}

	public function add_process()
	{
		$begin    = $this->input->post('dt_begin');
		$end      = $this->input->post('dt_end');
		$rkk_B_id = $this->input->post('chk_rkk');
		$nik_A    = $this->input->post('nik'); 
		$post_A   = $this->input->post('post_id'); 
		$sap_A    = $this->input->post('is_sap'); 
		$this->form_validation->set_rules('dt_begin', 'Begin Date', 'trim|required|min_length[10]|max_length[10]|xss_clean');
		$this->form_validation->set_rules('dt_end', 'End Date', 'trim|required|min_length[10]|max_length[10]|xss_clean');
		
		$rkk_A      = $this->rkk_model3->get_rkk_holder_last($nik_A,$post_A,$sap_A,$begin,$end);
		$rkkA_begin = strtotime($rkk_A->BeginDate);
		$rkkA_end   = strtotime($rkk_A->EndDate);

		if (strtotime($begin) < $rkkA_begin || strtotime($end) > $rkkA_end) {
			$data['notif_text'] = 'Cannot create RKK Relation. Begin and End Date out of range';
			$data['notif_type'] = 'alert-error';
		} else {
			if (count($rkk_B_id)) {
				$c_ok   = 0;
				$c_fail = 0;
				foreach ($rkk_B_id as $key => $rkk_id) {
					$rkk_B      = $this->rkk_model3->get_rkk_row($rkk_id);
					$rkkB_begin = strtotime($rkk_B->BeginDate);
					$rkkB_end   = strtotime($rkk_B->EndDate);
					if (strtotime($begin) < $rkkB_begin || strtotime($end) > $rkkB_end) {
						$c_fail++;
					} else {
						$this->rkk_model3->add_rkk_rel($rkk_id, $nik_A,$post_A, $sap_A, $begin,$end);
						$c_ok++;
					}
				}
				$data['notif_text'] = 'Success create '.$c_ok .' RKK Relation with '. $c_fail. ' failed';
				$data['notif_type'] = 'alert-success';
			} else {
				$data['notif_text'] = 'Select at least one RKK';
				$data['notif_type'] = 'alert-error';
			}
			
		}

		$begin  = $this->input->post('dt_begin');
		$end    = $this->input->post('dt_end');
		$data['link'] = 'manager/rkk_rel/add'.$nik_A.'/'.$post_A.'/'.$sap_A.'/'.$begin.'/'.$end;
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_submit_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}


}

/* End of file rkk_rel.php */
/* Location: ./application/controllers/manager/rkk_rel.php */