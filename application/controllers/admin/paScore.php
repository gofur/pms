<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PaScore extends Controller {
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

		$data['scale']=$this->general_model->get_scale_list(1,'','');
				$this->load->view('template/top_1_view');
		$this->load->view('admin/paScore_view',$data);
		$this->load->view('template/bottom_1_view');
	}
	function add(){
		$data['process']='admin/PaScore/add_process';
		$data['title']='Add PA Score';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('admin/paScore_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('admin/paScore_form_js');

	}
	function add_process(){
		$Colour = $this->input->post('TxtColor');
		$PAScore = $this->input->post('TxtPAScore');
		$start_date = $this->input->post('TxtStartDate');
		$end_date = $this->input->post('TxtEndDate');
		$this->general_model->add_PAScale($Colour,$PAScore,$start_date,$end_date);
		$data['notif_text']='Success add PA Score';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
	function edit($id){
		$data['process']='admin/PaScore/edit_process';
		$data['title']='Edit PA Score';
		$data['old']=$this->general_model->get_scale_row($id);
		$this->load->view('template/top_popup_1_view');
		$this->load->view('admin/paScore_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('admin/paScore_form_js');
	}
	function edit_process(){
		$CodeColourID =$this->input->post('TxtCodeColourID');
		$Colour = $this->input->post('TxtColor');
		$PAScore = $this->input->post('TxtPAScore');
		$start_date = $this->input->post('TxtStartDate');
		$end_date = $this->input->post('TxtEndDate');
		$this->general_model->edit_PAScale($CodeColourID,$Colour,$PAScore,$start_date,$end_date);
		$data['notif_text']='Success edit PA Score';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
	function delete_process($id){
		$this->general_model->delete_PAColor($id);
		$this->session->set_flashdata('notif_text','Success delete PA Score');
		$this->session->set_flashdata('notif_type','alert-success');
		redirect('admin/paScore');
	}
}