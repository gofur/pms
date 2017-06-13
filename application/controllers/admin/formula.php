<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Formula extends Controller {
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
		$data['rows']=$this->general_model->get_PCFormula_list();
				$this->load->view('template/top_1_view');
		$this->load->view('admin/formula_view',$data);
		$this->load->view('template/bottom_1_view');
	}
	function detail($id){
		$data['head']=$this->general_model->get_PCFormula_row($id);
		$data['list']=$this->general_model->get_PCFormulaScore_list($id,'','');
		$data['notif_text']=$this->session->flashdata('notif_text');
		$data['notif_type']=$this->session->flashdata('notif_type');
				$this->load->view('template/top_1_view');
		$this->load->view('admin/formulaScore_view',$data);
		$this->load->view('template/bottom_1_view');

	}
	function add(){
		$data['process']='admin/formula/add_process';
		$data['title']='Add Formula';
		$data['countType']=$this->general_model->get_CaraHitung_list(date('Y-m-d'),date('Y-m-d'));
		$this->load->view('template/top_popup_1_view');
		$this->load->view('admin/formula_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('admin/formula_form_js');

	}
	function add_process(){
		$PCFormula = $this->input->post('TxtPCFormula');
		$SkipConstancy = $this->input->post('TxtSkipConstancy');
		$Notes = $this->input->post('TxtNotes');
		$CaraHitungID = $this->input->post('SlcCaraHitungID');
		$Operator = $this->input->post('SlcOperator');
		$Perception = $this->input->post('SlcPerception');
		$start_date = $this->input->post('TxtStartDate');
		$end_date = $this->input->post('TxtEndDate');
		$this->general_model->add_PCFormula($CaraHitungID,$PCFormula,$Perception,$SkipConstancy,$Operator,$Notes,$start_date ,$end_date);
		$data['notif_text']='Success add Formula';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
	function add_formulaScore($PCFormulaID){
		$data['PCFormulaID']=$PCFormulaID;
		$data['process']='admin/formula/add_formulaScore_process';
		$data['title']='Add Formula Score';
		$data['score']=$this->general_model->get_Scale_list(1,date('Y-m-d'),date('Y-m-d'));
		$this->load->view('template/top_popup_1_view');
		$this->load->view('admin/formulaScore_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('admin/formulaScore_form_js');
	}
	function add_formulaScore_process(){
		$PCFormulaID = $this->input->post('TxtPCFormulaID');
		$PCFormulaScore = $this->input->post('SlcPCFormulaScore');
		$PCLow = $this->input->post('TxtPCLow');
		$PCHigh = $this->input->post('TxtPCHigh');
		$Percentage = $this->input->post('TxtPercentage');

		$start_date = $this->input->post('TxtStartDate');
		$end_date = $this->input->post('TxtEndDate');
		$this->general_model->add_PCFormulaScore($PCFormulaID,$PCFormulaScore,$PCLow,$PCHigh,$Percentage,$start_date,$end_date);
		$data['notif_text']='Success add Formula Score';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
	function edit($id){
		$data['process']='admin/formula/edit_process';
		$data['title']='Edit Formula';
		$data['countType']=$this->general_model->get_CaraHitung_list(date('Y-m-d'),date('Y-m-d'));
		$data['old']=$this->general_model->get_PCFormula_row($id);
		$this->load->view('template/top_popup_1_view');
		$this->load->view('admin/formula_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('admin/formula_form_js');
		
	}
	function edit_process(){
		$PCFormulaID = $this->input->post('TxtPCFormulaID');
		$PCFormula = $this->input->post('TxtPCFormula');
		$SkipConstancy = $this->input->post('TxtSkipConstancy');
		$Notes = $this->input->post('TxtNotes');
		$CaraHitungID = $this->input->post('SlcCaraHitungID');
		$Operator = $this->input->post('SlcOperator');
		$Perception = $this->input->post('SlcPerception');
		$start_date = $this->input->post('TxtStartDate');
		$end_date = $this->input->post('TxtEndDate');
		$this->general_model->edit_PCFormula($PCFormulaID,$CaraHitungID,$PCFormula,$Perception,$SkipConstancy,$Operator,$Notes,$start_date ,$end_date);
		$data['notif_text']='Success edit Formula';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
	function edit_formulaScore($id){
		$data['old']=$this->general_model->get_PCFormulaScore_row($id);
		$data['process']='admin/formula/edit_formulaScore_process';
		$data['title']='Edit Formula Score';
		$data['score']=$this->general_model->get_Scale_list(1,date('Y-m-d'),date('Y-m-d'));
		$this->load->view('template/top_popup_1_view');
		$this->load->view('admin/formulaScore_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('admin/formulaScore_form_js');
	}
	function edit_formulaScore_process(){
		$PCFormulaScoreID = $this->input->post('TxtPCFormulaScoreID');
		$PCFormulaID = $this->input->post('TxtPCFormulaID');
		$PCFormulaScore = $this->input->post('SlcPCFormulaScore');
		$PCLow = $this->input->post('TxtPCLow');
		$PCHigh = $this->input->post('TxtPCHigh');
		$Percentage = $this->input->post('TxtPercentage');
		$start_date = $this->input->post('TxtStartDate');
		$end_date = $this->input->post('TxtEndDate');
		$this->general_model->edit_PCFormulaScore($PCFormulaScoreID,$PCFormulaID,$PCFormulaScore,$PCLow,$PCHigh,$Percentage,$start_date,$end_date);
		$data['notif_text']='Success edit Formula Score';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
	function delete_process($id){
		$this->general_model->remove_PCFormula($id);
		$this->session->set_flashdata('notif_text','Success delete Formula');
		$this->session->set_flashdata('notif_type','alert-success');
		redirect('admin/formula');
	}
	function delete_formulaScore($id){
		$headID = $this->general_model->get_PCFormulaScore_row($id)->PCFormulaID;
		$this->general_model->remove_PCFormulaScore($id);
		$this->session->set_flashdata('notif_text','Success delete Formula Score');
		$this->session->set_flashdata('notif_type','alert-success');
		redirect('admin/formula/detail/'.$headID);
	}
}