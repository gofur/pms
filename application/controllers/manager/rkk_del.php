<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Rkk_del extends Controller {
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
		$period   = $this->general_model->get_ActivePeriode();
		// echo $holder   = $this->session->userdata('Holder');
		$nik 			= $this->session->userdata('NIK');
		$this->session->unset_userdata('rkk_list');
		$this->session->unset_userdata('end_date');

		$user_dtl = $this->account_model->get_User_row($this->session->userdata('userID'));
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
		if (isset($holder)==false or is_null($holder) or $holder == 0 or $holder == '') {
			$holder  = $this->input->post('SlcPost');
		}
		$this->session->set_userdata('Holder',$holder);

		$data['holder']        = $holder;
		$data['period']        = $period;
		$data['user_dtl']      = $user_dtl;
		$data['action_filter'] = 'manager/rkk_delimit';
		$data['post_ls_SAP']      = $this->account_model->get_Holder_list($nik,1,$filter_start,$filter_end);
		$data['post_ls_nonSAP']   = $this->account_model->get_Holder_list($nik,0,$filter_start,$filter_end);
		$data['assign_ls_SAP']    = $this->account_model->get_Assignment_list($nik,1,$filter_start,$filter_end);
		$data['assign_ls_nonSAP'] = $this->account_model->get_Assignment_list($nik,0,$filter_start,$filter_end);

		if ($holder != '' ) {
			list($is_sap,$holder_id) = explode('.', $holder);
			$hold_dtl = $this->account_model->get_Holder_row($holder_id,$is_sap,$filter_start,$filter_end);
			$list = $this->rkk_model3->get_rkk_rel_AB_list($nik,$hold_dtl->PositionID,$is_sap,$filter_start,$filter_end,'all');
			$post_ls = array();
			foreach ($list as $row) {
				$key           = $row->isSAP .'|'. $row->PositionID;
				$post_ls[$key] = $this->org_model->get_Position_row($row->PositionID,$row->isSAP,$filter_start,$filter_end)->PositionName; 

			}
			$data['post_name'] = $post_ls;
			$data['rows'] = $list;
			$data['hidden'] = array(
				'holder_id' => $holder_id,
				'is_sap'    => $is_sap
			);
		} else {
			$data['rows']   = array();
			$data['hidden'] = array();
		}
		$data['action'] = 'manager/rkk_delimit/confirm';


		$this->load->view('manager/rkk_delimit_view',$data);

	}

	public function confirm()
	{

		$period    = $this->general_model->get_ActivePeriode();
		$filter_start = $this->input->post('dt_filter_start');
		$filter_end   = $this->input->post('dt_filter_end');
		$end       = $this->input->post('dt_end'). ' 23:59:59';
		$rkk_list  = $this->input->post('chk_sub');
		$holder_id = $this->input->post('holder_id');
		$is_sap    = $this->input->post('is_sap');

		$post      = $this->account_model->get_Holder_row($holder_id,$is_sap,$filter_start,$filter_end);
		$user_dtl  = $this->account_model->get_User_row($this->session->userdata('userID'));

		$this->session->set_userdata('rkk_list',$rkk_list);
		$sub 						= array();
		foreach ($rkk_list as $key => $value) {
			$sub_rkk  = $this->rkk_model3->get_rkk_row($value);
			$sub_user = $this->account_model->get_User_byNIK($sub_rkk->NIK);
			$sub_post = $this->org_model->get_Position_row($sub_rkk->PositionID,$sub_rkk->isSAP,$filter_start,$filter_end);

			$sub[$value]['nik']  = $sub_user->NIK; 		
			$sub[$value]['name'] = $sub_user->Fullname;
			$sub[$value]['post'] = $sub_post->PositionName; 		
		}
		$data['sub']      = $sub;
		$data['end_date'] = $end;
		$data['user']     = $user_dtl;
		$data['post']     = $post;
		$data['action']   = 'manager/rkk_delimit/process';
		$data['hidden'] = array(
			'holder_id' => $holder_id,
			'is_sap'    => $is_sap
		);
		$this->session->set_userdata('rkk_list',$rkk_list);
		$this->session->set_userdata('end_date',$end);
		$this->load->view('template/top_1_view');
		$this->load->view('manager/rkk_delimit_form',$data);
		$this->load->view('template/bottom_1_view');


	}

	public function process()
	{
		$this->load->model('idp_model');
		$period     = $this->general_model->get_ActivePeriode();
		$rkk_list   = $this->session->userdata('rkk_list');
		$end        = $this->session->userdata('end_date');

		foreach ($rkk_list as $key => $rkk_id) {
			$rel = $this->rkk_model3->get_rkk_rel_last($rkk_id,$period->BeginDate,$period->EndDate);
			$this->rkk_model3->delimit_rkk($rkk_id,$end);
			$this->rkk_model3->delimit_rkk_rel($rel->R_RKKID,$end);

			$this->idp_model->delimit_byRKK($rkk_id,$end);
		}

		redirect('manager/rkk_delimit');
	}
}