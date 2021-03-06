<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reference extends Controller {
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
		$data['rows']=$this->general_model->get_Reference_list();
				$this->load->view('template/top_1_view');
		$this->load->view('admin/reference_view',$data);
		$this->load->view('template/bottom_1_view');
	}
	function add(){
		$data['process']='admin/reference/add_process';
		$data['title']='Add Reference';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('admin/reference_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('admin/reference_form_js');

	}
	function add_process(){
		$label = $this->input->post('TxtReference');
		$labelDesc = $this->input->post('TxtDescription');
		$start_date = $this->input->post('TxtStartDate');
		$end_date = $this->input->post('TxtEndDate');
		$this->general_model->add_Reference($label,$labelDesc,$start_date ,$end_date);
		$data['notif_text']='Success add Reference';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
	function edit($id){
		$data['process']='admin/reference/edit_process';
		$data['title']='Edit Reference';
		$data['old']=$this->general_model->get_Reference_row($id);
		$this->load->view('template/top_popup_1_view');
		$this->load->view('admin/reference_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('admin/reference_form_js');
		
	}
	function edit_process(){
		$ReferenceID = $this->input->post('TxtReferenceID');
		$Description = $this->input->post('TxtDescription');
		$label = $this->input->post('TxtReference');
		$start_date = $this->input->post('TxtStartDate');
		$end_date = $this->input->post('TxtEndDate');
		$this->general_model->edit_Reference($ReferenceID,$label,$Description,$start_date ,$end_date);
		$data['notif_text']='Success edit Reference';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}

	function delete_process($id){
		$this->general_model->remove_Reference($id);
		$this->session->set_flashdata('notif_text','Success delete Reference');
		$this->session->set_flashdata('notif_type','alert-success');
		redirect('admin/reference');
	}
}