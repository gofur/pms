<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Behaviour extends Controller {
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
		$data['title'] = 'Behaviour List';
		$data['notif_text']=$this->session->flashdata('notif_text');
		$data['notif_type']=$this->session->flashdata('notif_type');
		$data['behaviour']=$this->general_model->get_behaviour_list('','');
		$this->load->view('template/top_1_view');
		$this->load->view('admin/behaviour_view',$data);
		$this->load->view('template/bottom_1_view');
	}
	function add(){
		$data['process']='admin/behaviour/add_process';
		$data['title']='Add Behaviour';
		$data['do_act']='add';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('admin/behaviour_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('admin/behaviour_form_js');

	}
	function add_process(){
		$label = $this->input->post('txt_label');
		$description = $this->input->post('txt_description');
		$start_date = $this->input->post('txt_begin_date');
		$end_date = $this->input->post('txt_end_date');
		$created_by = $this->session->userdata('NIK');
		$created_date = date('m/d/Y h:i:s');
		$this->general_model->add_behaviour($label,$description,$start_date,$end_date,$created_by,$created_date);
		$data['notif_text']='Success add Behaviour';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
	function edit($id){
		$data['process']='admin/behaviour/edit_process';
		$data['title']='Edit behaviour';
		$data['do_act']='edit';
		$data['old']=$this->general_model->get_behaviour_row($id);
		$this->load->view('template/top_popup_1_view');
		$this->load->view('admin/behaviour_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('admin/behaviour_form_js');
	}
	function edit_process(){
		$behaviour_id=$this->input->post('txt_behaviour_id');
		$label =$this->input->post('txt_label');
		$description = $this->input->post('txt_description');
		$start_date = $this->input->post('txt_begin_date');
		$end_date = $this->input->post('txt_end_date');
		$updated_by = $this->session->userdata('NIK');
		$updated_date = date('m/d/Y h:i:s');
		$this->general_model->edit_behaviour($behaviour_id,$label,$description,$start_date,$end_date,$updated_by,$updated_date);
		$data['notif_text']='Success delimit Behaviour';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}

	function delimit($id){
		$data['process']='admin/behaviour/delimit_process';
		$data['title']='Delimit Behaviour';
		$data['do_act']='delimit';
		$data['old']=$this->general_model->get_behaviour_row($id);
		$this->load->view('template/top_popup_1_view');
		$this->load->view('admin/behaviour_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('admin/behaviour_form_js');
	}
	function delimit_process(){
		$behaviour_id=$this->input->post('txt_behaviour_id');
		$end_date = $this->input->post('txt_end_date');
		$updated_by = $this->session->userdata('NIK');
		$updated_date = date('m/d/Y h:i:s');
		$this->general_model->edit_delimit_behaviour($behaviour_id,$end_date,$updated_by,$updated_date);
		$data['notif_text']='Success delimit Behaviour';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
}