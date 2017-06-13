<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class GenericKPI extends Controller {
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
		$data['process']='admin/genericKpi/';
		$PerspectiveID = $this->input->post('SlcPerspectiveID');
		$data['rows']=$this->general_model->get_GenericKPI_Search($PerspectiveID);
		$data['perspectiveType']=$this->general_model->get_Perspective_List(date('Y-m-d'),date('Y-m-d'));
		$this->load->view('template/top_1_view');
		$this->load->view('admin/genericKPI_view',$data);
		$this->load->view('template/bottom_1_view');
	}

	function add(){
		$data['process']='admin/genericKpi/add_process';
		$data['title']='Add Generic KPI';
		$data['perspectiveType']=$this->general_model->get_Perspective_List(date('Y-m-d'),date('Y-m-d'));
		$data['satuanType']=$this->general_model->get_Satuan_List(date('Y-m-d'),date('Y-m-d'));
		$data['countType']=$this->general_model->get_CaraHitung_list(date('Y-m-d'),date('Y-m-d'));
		$data['ytdType']=$this->general_model->get_YTD_list(date('Y-m-d'),date('Y-m-d'));
		$data['referenceKpiType']=$this->general_model->get_Reference_list(date('Y-m-d'),date('Y-m-d'));
		
		$this->load->view('template/top_popup_1_view');
		$this->load->view('admin/genericKPI_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('admin/genericKPI_form_js');

	}
	function ajax_formula($id='',$current=''){
		if($id!=''){
			$today=date('Y-m-d');
			$data['current']=$current;
			$data['formulaList']=$this->general_model->get_PCFormula_List($id,$current,$today,$today);
			$this->load->view('admin/genericKPI_form_formula',$data);
		}
	}
	function add_process(){
		$PerspectiveID = $this->input->post('SlcPerspectiveID');
		$KPI = $this->input->post('TxtKPI');
		$Description = $this->input->post('TxtDescription');
		$SatuanID = $this->input->post('SlcSatuanID');
		$CaraHitungID = $this->input->post('SlcCaraHitungID');
		$YTDID = $this->input->post('SlcYTDID');
		$PCFormulaID = $this->input->post('SlcFormula');
		$start_date = $this->input->post('TxtStartDate');
		$end_date = $this->input->post('TxtEndDate');
		$this->general_model->add_GenericKPI($PerspectiveID, $SatuanID,$PCFormulaID,$YTDID, $KPI, $Description,$start_date,$end_date);
		$data['notif_text']='Success add Generic KPI';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
	function edit($id){
		$data['process']='admin/genericKpi/edit_process';
		$data['title']='Edit Generic KPI';
		$today=date('Y-m-d');
		
		//tampung dari variable data lama
		$old=$this->general_model->get_GenericKPI_row($id);
		//periksa carahitung berdasarkan pc formula ambil nama field
		$data['CaraHitungID'] = $this->general_model->get_PCFormula_row($old->PCFormulaID)->CaraHitungID;
		//ditransfer ke view generic kpi
		$data['old']=$old;
		
		$data['genericKpiType']=$this->general_model->get_GenericKPI_List(date('Y-m-d'),date('Y-m-d'));
		$data['perspectiveType']=$this->general_model->get_Perspective_List(date('Y-m-d'),date('Y-m-d'));
		$data['satuanType']=$this->general_model->get_Satuan_List(date('Y-m-d'),date('Y-m-d'));
		$data['countType']=$this->general_model->get_CaraHitung_list(date('Y-m-d'),date('Y-m-d'));
		$data['ytdType']=$this->general_model->get_YTD_list(date('Y-m-d'),date('Y-m-d'));
		$data['referenceKpiType']=$this->general_model->get_Reference_list(date('Y-m-d'),date('Y-m-d'));
		$data['formulaList']=$this->general_model->get_PCFormula_list($id,$today,$today);
		$this->load->view('template/top_popup_1_view');
		$this->load->view('admin/genericKPI_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('admin/genericKPI_form_js');
		
	}
	function edit_process(){
		$PerspectiveID = $this->input->post('SlcPerspectiveID');
		$KPIGenericID = $this->input->post('TxtKPIID');
		$KPI = $this->input->post('TxtKPI');
		$Description = $this->input->post('TxtDescription');
		$SatuanID = $this->input->post('SlcSatuanID');
		$CaraHitungID = $this->input->post('SlcCaraHitungID');
		$YTDID = $this->input->post('SlcYTDID');
		$PCFormulaID = $this->input->post('SlcFormula');
		$start_date = $this->input->post('TxtStartDate');
		$end_date = $this->input->post('TxtEndDate');
		$this->general_model->edit_GenericKPI($KPIGenericID, $PerspectiveID,$SatuanID,$PCFormulaID,$YTDID, $KPI, $Description,$start_date ,$end_date);
		$data['notif_text']='Success edit Generic KPI';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
	
	function delete_process($id){
		$this->general_model->remove_GenericKPI($id);
		$this->session->set_flashdata('notif_text','Success delete Generic KPI');
		$this->session->set_flashdata('notif_type','alert-success');
		redirect('admin/genericKpi');
	}
	
}