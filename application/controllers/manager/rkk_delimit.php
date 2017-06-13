<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Rkk_delimit extends Controller {
	function __construct(){
		parent::__construct();
		if(!$this->session->userdata('loginFlag')){
			redirect('account/login');
		}
		$this->load->model('rkk_model3');
		$this->load->model('idp_model');
		$this->load->model('general_model');
		$this->load->model('org_model');
		$this->load->model('account_model');
	}
	public function index()
	{
		$period  = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$user_id = $this->session->userdata('userID');
		$nik     = $this->session->userdata('NIK');
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
		$this->load->view('manager/rkk_del/main_view',$data);


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

		$list = $this->rkk_model3->get_rkk_rel_AB_list($nik,$holder_dtl->PositionID,$is_sap,$begin,$end,'all');
		$post_ls = array();
		foreach ($list as $row) {
			$key           = $row->isSAP .'|'. $row->PositionID;
			$post_ls[$key] = $this->org_model->get_Position_row($row->PositionID,$row->isSAP,$begin,$end)->PositionName; 

		}
		$data['list'] = $list;
		$data['post_name'] = $post_ls;

		$this->load->view('manager/rkk_del/list_view',$data);

	}

	public function delimit_process()
	{
		$rkk_list  = $this->input->post('chk_sub');
		$end       = $this->input->post('dt_end');
		// $period  = $this->general_model->get_Period_row($this->session->userdata('active_period'));

		echo count($rkk_list);
		foreach ($rkk_list as $key => $rkk_id) {
			// $rel = $this->rkk_model3->get_rkk_rel_last($rkk_id,$period->BeginDate,$period->EndDate);
			$this->rkk_model3->delimit_rkk($rkk_id,$end);

			$this->rkk_model3->delimit_rkk_rel_byRKK($rkk_id,$end);

			$this->idp_model->delimit_byRKK($rkk_id,$end);
		}

		redirect('manager/rkk_delimit');
		
	}

	public function remove_process()
	{
		$rkk_list  = $this->input->post('chk_sub');
		// $period  = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		foreach ($rkk_list as $key => $rkk_id) {
			$this->idp_model->remove_byRKK($rkk_id);
			
			$this->rkk_model3->remove_rkk_rel_byRKK($rkk_id);
			$this->rkk_model3->remove_rkk($rkk_id);
		}
		redirect('manager/rkk_delimit');

	}
}