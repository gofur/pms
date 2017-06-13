<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Counting_type extends Controller {
	function __construct(){
		parent::__construct();
		if(!$this->session->userdata('loginFlag')){
			redirect('account/login');
		}
		//check validasi akses
		$url_value = $this->uri->segment(1, 0);
		if($this->uri->segment(2, 0)!=''){
			$url_value .='/'.$this->uri->segment(2, 0);
		}
		if($this->system_model->check_roleAccess($this->session->userdata('roleID'),$url_value)==0){
			redirect('home');
		}
		$this->load->model('general_model');
	}
	function index(){
		$data['notif_text']=$this->session->flashdata('notif_text');
		$data['notif_type']=$this->session->flashdata('notif_type');
		$data['rows']=$this->general_model->get_CaraHitung_list();
				$this->load->view('template/top_1_view');
		$this->load->view('admin/countingType_view',$data);
		$this->load->view('template/bottom_1_view');
	}
	function add(){
		$data['process']='admin/counting_type/add_process';
		$data['title']='Add Counting Type';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('admin/countingType_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('admin/countingType_form_js');

	}
	function add_process(){
		$label = $this->input->post('TxtCaraHitung');
		$start_date = $this->input->post('TxtStartDate');
		$end_date = $this->input->post('TxtEndDate');
		$this->general_model->add_CaraHitung($label,$start_date ,$end_date);
		$data['notif_text']='Success add Counting Type';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
	function edit($id){
		$data['process']='admin/counting_type/edit_process';
		$data['title']='Edit Counting Type';
		$data['old']=$this->general_model->get_CaraHitung_row($id);
		$this->load->view('template/top_popup_1_view');
		$this->load->view('admin/countingType_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('admin/countingType_form_js');
		
	}
	function edit_process(){
		$CaraHitungID = $this->input->post('TxtCaraHitungID');
		$label = $this->input->post('TxtCaraHitung');
		$start_date = $this->input->post('TxtStartDate');
		$end_date = $this->input->post('TxtEndDate');
		$this->general_model->edit_CaraHitung($CaraHitungID,$label,$start_date ,$end_date);
		$data['notif_text']='Success edit Counting Type';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}

	function delete_process($id){
		if ($this->general_model->check_CaraHitung_isUsed($id)){
			$this->session->set_flashdata('notif_text','Other data using this Counting Type');
			$this->session->set_flashdata('notif_type','alert-error');
		}else{
			$this->general_model->remove_CaraHitung($id);
			$this->session->set_flashdata('notif_text','Success delete Counting Type');
			$this->session->set_flashdata('notif_type','alert-success');
		}
		redirect('admin/counting_type');
	}
}