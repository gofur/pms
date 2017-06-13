<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Period extends Controller {
	function __construct(){
		parent::__construct();
		if ($this->session->userdata('loginFlag')==0){
			redirect('account/login');
		}
	}
	function index(){
		$this->load->model('general_model');
		$option = array(0 => '');
		$period_ls = $this->general_model->get_Period_list();
		foreach ($period_ls as $row) {
			$option[$row->PeriodePMID] = $row->Tahun;
		}


		$data['options']  = $option;
		$data['default'] = $this->session->userdata('active_period');

		$this->load->view('period_view',$data);	
		
	}

	public function change()
	{
		$this->load->model('general_model');

		$period_id = $this->input->post('slc_period');
		$array = array(
			'active_period' => $period_id
		);
		$tahun = $this->general_model->get_Period_row($period_id)->Tahun;
		$this->session->set_userdata( $array );
		$data['notif_text'] = 'Period change to '.$tahun;
		$data['notif_type'] = 'alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
}