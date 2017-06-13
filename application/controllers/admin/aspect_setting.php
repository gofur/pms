<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class aspect_setting extends Controller {
	function __construct(){
		parent::__construct();
		session_start();
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
		$this->load->model('org_model');
	}

	function index()
	{
		redirect('admin/aspect_setting/lists');		
	}


	function lists(){
		
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$is_sap=$this->session->userdata('isSAP');
		$data['organization_list']=$this->org_model->get_Organization_list(0,$is_sap,date('Y-m-d'),date('Y-m-d'));
		$config["base_url"] = base_url() . "index.php/admin/aspect_setting/lists";
		$config["total_rows"] = $this->general_model->get_total_row_data_aspect_setting();
		$config["per_page"] = 10;
		$config["uri_segment"] = 4;
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['next_link'] = 'Next';
		$config['prev_link'] = 'Prev';
		$this->pagination->initialize($config);
		$data["aspect_setting"] = $this->general_model->get_all_data_aspect_setting($page, $config["per_page"],$is_sap);
		$data["links"] = $this->pagination->create_links();

		$data['process']='admin/aspect_setting/search_proses';
		$data['process_add']='admin/aspect_setting/add';
		$data['title'] = 'Aspect Setting - List';
		$data['notif_text']=$this->session->flashdata('notif_text');
		$data['notif_type']=$this->session->flashdata('notif_type');

		$this->load->view('template/top_1_view');
		$this->load->view('admin/aspect_setting_view',$data);
		$this->load->view('template/bottom_1_view');
		$this->load->view('admin/aspect_setting_view_js');
	}

	function pop_up_org($parent_id='')
	{
		$is_sap=$this->session->userdata('isSAP');
		
		if($parent_id=='')
		{
			$parent_id=0;
			$data['name_org']='';
		}
		else
		{
			$name_org = '';
			$name_org = $this->fullname_org($parent_id,$is_sap);
			$data['name_org']=$name_org;

		}	

		$data['organization_list']=$this->org_model->get_Organization_list($parent_id,$is_sap,date('Y-m-d'),date('Y-m-d'));

		$this->load->view('template/top_popup_1_view');
		$this->load->view('admin/aspect_setting_org_data',$data);	
		$this->load->view('template/bottom_popup_1_view');
	}

	private function fullname_org($org_id=0,$is_sap,$result='') {
		$this_org = $this->org_model->get_Organization_start_date($org_id,$is_sap);
		if ($this_org->OrganizationParent == 0) {
			$result = $this_org->OrganizationName;
			return $result;
		} else {
			//iterasi
			return $result .= $this->fullname_org($this_org->OrganizationParent,$is_sap,$result) .' - '.$this_org->OrganizationName;

			var_dump($result);
		}
	}


	function ajax_unit(){

		$is_sap=$this->session->userdata('isSAP');
		$org_type=$this->input->post('org_type');	
		$org_parent = $this->input->post('org_parent');
		$rows = $this->org_model->get_Organization_list(0,$org_type,date('Y-m-d'),date('Y-m-d'));
		$output = '<option value="">Select One</option>';
		foreach ($rows as $row) {
			$output .= '<option value="'.$row->OrganizationID.'">'.$row->OrganizationName.'</option>';
		}
		echo $output;
	}

	function ajax_sub_unit(){
		$org_type = $this->session->userdata('isSAP');
		$org_parent = $this->input->post('org_parent');
		$element = $this->input->post('element');
		
		$output = '<option value="">Select One</option>';

		$rows = $this->org_model->get_Organization_list($org_parent,$org_type,date('Y-m-d'),date('Y-m-d'));
		foreach ($rows as $row) {
			$output .= '<option value="'.$row->OrganizationID.'">'.$row->OrganizationName.'</option>';
		}

		echo $output;

	}


	function get_start_end_date()
	{
		$is_sap=$this->session->userdata('isSAP');
		$organization_id = $this->input->post('organization_id');
		if($organization_id!='')
		{
			$data['start_date']=$this->org_model->get_Organization_start_date($organization_id,$is_sap)->BeginDate;
			$data['end_date']=$this->org_model->get_Organization_start_date($organization_id,$is_sap)->EndDate;
		}else
		{
			$data['start_date']='';
			$data['end_date']='';
		}
		$this->load->view('admin/aspect_setting_start_date_view',$data);
	}

	function search()
	{
		$is_sap=$this->session->userdata('isSAP');
		$data['organization_list']=$this->org_model->get_organization_list_all(date('Y-m-d'),date('Y-m-d'),$is_sap);
		$data['process']='admin/aspect_setting/search';
		$data['process_add']='admin/aspect_setting/add';
		$organization = $this->input->post('txt_organization_id');
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$config["base_url"] = base_url() . "index.php/admin/aspect_setting/search";
		$config["total_rows"] = $this->general_model->get_total_row_data_aspect_setting();
		$config["per_page"] = 10;
		$config["uri_segment"] = 4;
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['next_link'] = 'Next';
		$config['prev_link'] = 'Prev';
		$this->pagination->initialize($config);
		$data["aspect_setting"] = $this->general_model->get_data_search_aspect_setting($organization,$is_sap,$page, $config["per_page"]);
		$data["links"] = $this->pagination->create_links();
		$data['title'] = 'Aspect Setting List';
		$data['notif_text']=$this->session->flashdata('notif_text');
		$data['notif_type']=$this->session->flashdata('notif_type');

		$this->load->view('template/top_1_view');
		$this->load->view('admin/aspect_setting_view',$data);
		$this->load->view('template/bottom_1_view');
		$this->load->view('admin/aspect_setting_view_js');	
	}


	function add(){
		$is_sap=$this->session->userdata('isSAP');
		//$data['organization_list']=$this->org_model->get_organization_list_all(date('Y-m-d'),date('Y-m-d'),$is_sap);
		$data['aspect_list']=$this->general_model->get_aspect_list(date('Y-m-d'),date('Y-m-d'));
		$data['behaviour_group_list']=$this->general_model->get_behaviour_group_list(date('Y-m-d'),date('Y-m-d'));
		$data['layer_list']=$this->general_model->get_layer_list(date('Y-m-d'),date('Y-m-d'));
		$data['process']='admin/aspect_setting/add_process';
		$data['title']='Add Aspect Setting';
		$data['do_act']='add';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('admin/aspect_setting_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('admin/aspect_setting_form_js');
	}

	function add_process(){
		$is_sap=$this->session->userdata('isSAP');
		$organization = $this->input->post('txt_organization_id');
		$organization_name = $this->input->post('txt_org_name');
		$org_start_date=$this->org_model->get_Organization_start_date($organization,$is_sap)->BeginDate;
		$org_end_date=$this->org_model->get_Organization_start_date($organization,$is_sap)->EndDate;
		$aspect = $this->input->post('slc_aspect');
		$behaviour_group = $this->input->post('slc_behaviour_group');
		$layer = $this->input->post('slc_layer');
		$frequency = $this->input->post('txt_frequency');
		$percentage = $this->input->post('txt_percentage');
		$start_date = $this->input->post('txt_begin_date');
		$end_date = $this->input->post('txt_end_date');
		$created_by = $this->session->userdata('NIK');
		$created_date = date('m/d/Y h:i:s');

		//cek data tersimpan
		$total_cek_org_aspect_behaviour_group = $this->general_model->get_cek_organization_aspect_behaviour_group($organization,$org_start_date,$org_end_date,$aspect,$behaviour_group);

		if($total_cek_org_aspect_behaviour_group!=0)
		{
			$data['notif_text']='Failed add Aspect Setting cause organization_id, aspect and behaviour_group already exist.';
			$data['notif_type']='alert-error';
			$this->load->view('template/top_popup_1_view');
			$this->load->view('template/notif_view',$data);
			$this->load->view('template/bottom_popup_1_view');
		}
		else
		{
			$this->general_model->add_aspect_setting($organization,$org_start_date,$org_end_date,$aspect,$behaviour_group,$frequency,$percentage,$start_date,$end_date,$created_by,$created_date,$organization_name,$layer);
			$data['notif_text']='Success add Aspect Setting';
			$data['notif_type']='alert-success';
			$this->load->view('template/top_popup_1_view');
			$this->load->view('template/notif_view',$data);
			$this->load->view('template/bottom_popup_1_view');
		}
		
	}
	function edit($id){
		$is_sap=$this->session->userdata('isSAP');
		$data['aspect_list']=$this->general_model->get_aspect_list(date('Y-m-d'),date('Y-m-d'));
		$data['behaviour_group_list']=$this->general_model->get_behaviour_group_list(date('Y-m-d'),date('Y-m-d'));
		$data['layer_list']=$this->general_model->get_layer_list(date('Y-m-d'),date('Y-m-d'));
		$data['old']=$this->general_model->get_aspect_setting_row($id);
		$org_id=$this->general_model->get_aspect_setting_row($id)->organization_id;

		$data['org_id']=$org_id;

		$data['process']='admin/aspect_setting/edit_process';
		$data['title']='Edit Aspect Setting';
		$data['do_act']='edit';

		$this_org = $this->org_model->get_Organization_start_date($org_id,$is_sap)->OrganizationParent;
		$this_org_name = $this->org_model->get_Organization_start_date($org_id,$is_sap)->OrganizationName;
		if($this_org!=0)
		{			
			$name_org = $this->fullname_org($this_org,$is_sap);
			$data['name_org']=$name_org.' - '.$this_org_name;
		}else
		{
			$data['name_org']=$this_org_name;
		}

		
		$this->load->view('template/top_popup_1_view');
		$this->load->view('admin/aspect_setting_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('admin/aspect_setting_form_js',$data);
	}
	function edit_process(){
		$is_sap=$this->session->userdata('isSAP');
		$aspect_setting_id=$this->input->post('txt_aspect_setting_id');
		$organization = $this->input->post('txt_organization_id');
		$organization_name = $this->input->post('txt_org_name');
		$org_start_date=$this->org_model->get_Organization_start_date($organization,$is_sap)->BeginDate;
		$org_end_date=$this->org_model->get_Organization_start_date($organization,$is_sap)->EndDate;
		$aspect = $this->input->post('slc_aspect');
		$behaviour_group = $this->input->post('slc_behaviour_group');
		$layer = $this->input->post('slc_layer');
		$frequency = $this->input->post('txt_frequency');
		$percentage = $this->input->post('txt_percentage');
		$start_date = $this->input->post('txt_begin_date');
		$end_date = $this->input->post('txt_end_date');
		$updated_by = $this->session->userdata('NIK');
		$updated_date = date('m/d/Y h:i:s');

		//cek data tersimpan
		$total_cek_org_aspect_behaviour_group = $this->general_model->get_cek_organization_aspect_behaviour_group($organization,$org_start_date,$org_end_date,$aspect,$behaviour_group);

		if($total_cek_org_aspect_behaviour_group!=0)
		{
			$this->general_model->edit_aspect_setting_without_org($aspect_setting_id,$aspect,
				$behaviour_group,$frequency,$percentage,$start_date,$end_date,$updated_by,$updated_date,$layer);
			$data['notif_text']='Success update Aspect Setting.';
			$data['notif_type']='alert-success';
			$this->load->view('template/top_popup_1_view');
			$this->load->view('template/notif_view',$data);
			$this->load->view('template/bottom_popup_1_view');
		}
		else
		{

			$this->general_model->edit_aspect_setting($aspect_setting_id,$organization,$organization_name,$org_start_date,$org_end_date,$aspect,
				$behaviour_group,$frequency,$percentage,$start_date,$end_date,$updated_by,$updated_date,$layer);
			$data['notif_text']='Success update Aspect Setting';
			$data['notif_type']='alert-success';
			$this->load->view('template/top_popup_1_view');
			$this->load->view('template/notif_view',$data);
			$this->load->view('template/bottom_popup_1_view');
		}
		
	}

	function delimit($id){
		$data['process']='admin/aspect_setting/delimit_process';
		$data['title']='Delimit Aspect Setting';
		$data['do_act']='delimit';
		$data['old']=$this->general_model->get_aspect_setting_row($id);
		$this->load->view('template/top_popup_1_view');
		$this->load->view('admin/aspect_setting_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('admin/aspect_setting_form_js');
	}

	function delimit_process(){
		$aspect_setting_id=$this->input->post('txt_aspect_setting_id');
		$end_date = $this->input->post('txt_end_date');
		$updated_by = $this->session->userdata('NIK');
		$updated_date = date('m/d/Y h:i:s');
		$this->general_model->edit_delimit_aspect_setting($aspect_setting_id,$end_date,$updated_by,$updated_date);
		$data['notif_text']='Success delimit Aspect Setting';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
}