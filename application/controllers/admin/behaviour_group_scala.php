<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Behaviour_group_scala extends Controller {
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

	function index()
	{
		redirect('admin/behaviour_group_scala/lists');		
	}


	function lists(){
		
		$data['behaviour_group']=$this->general_model->get_behaviour_group_list(date('Y-m-d'),date('Y-m-d'));

		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

        $config["base_url"] = base_url() . "index.php/admin/behaviour_group_scala/lists";
	    $config["total_rows"] = $this->general_model->get_total_row_data_behaviour_group_scala();
	    $config["per_page"] = 10;
	    $config["uri_segment"] = 4;
	    $config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['next_link'] = 'Next';
		$config['prev_link'] = 'Prev';
	    $this->pagination->initialize($config);
	 	$data["behaviour_group_scala"] = $this->general_model->get_all_data_behaviour_group_scala($page, $config["per_page"]);
        $data["links"] = $this->pagination->create_links();

        $data['process']='admin/behaviour_group_scala/search_proses';
        $data['process_add']='admin/behaviour_group_scala/add';
		$data['title'] = 'Behaviour Group - Scala List';
		$data['notif_text']=$this->session->flashdata('notif_text');
		$data['notif_type']=$this->session->flashdata('notif_type');

		$this->load->view('template/top_1_view');
		$this->load->view('admin/behaviour_group_scala_view',$data);
		$this->load->view('template/bottom_1_view');
	}

	function search()
	{
		$data['behaviour_group']=$this->general_model->get_behaviour_group_list(date('Y-m-d'),date('Y-m-d'));
		$data['process']='admin/behaviour_group_scala/search';
		$data['process_add']='admin/behaviour_group_scala/add';
		$select_behaviour_group = $this->input->post('slc_behaviour_group');
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$config["base_url"] = base_url() . "index.php/admin/behaviour_group_scala/lists";
	    $config["total_rows"] = $this->general_model->get_total_row_data_behaviour_group_scala();
	    $config["per_page"] = 10;
	    $config["uri_segment"] = 4;
	    $config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['next_link'] = 'Next';
		$config['prev_link'] = 'Prev';
	    $this->pagination->initialize($config);
	 	$data["behaviour_group_scala"] = $this->general_model->get_data_search_behaviour_group_scala($select_behaviour_group,$page, $config["per_page"]);
        $data["links"] = $this->pagination->create_links();
		$data['title'] = 'Behaviour Group - Scala List';
		$data['notif_text']=$this->session->flashdata('notif_text');
		$data['notif_type']=$this->session->flashdata('notif_type');

		$this->load->view('template/top_1_view');
		$this->load->view('admin/behaviour_group_scala_view',$data);
		$this->load->view('template/bottom_1_view');	
	}


	function add(){
		$data['process']='admin/behaviour_group_scala/add_process';
		$data['title']='Add Behaviour Group - Scala';
		$data['do_act']='add';
		$data['behaviour_group']=$this->general_model->get_behaviour_group_list(date('Y-m-d'),date('Y-m-d'));
		$data['scala']=$this->general_model->get_scala_list(date('Y-m-d'),date('Y-m-d'));
		$this->load->view('template/top_popup_1_view');
		$this->load->view('admin/behaviour_group_scala_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('admin/behaviour_group_scala_form_js');
	}

	function add_process(){
		$behaviour_group = $this->input->post('slc_behaviour_group');
		$scala = $this->input->post('slc_scala');
		$sort = $this->input->post('txt_sort');
		$description = $this->input->post('txt_description');
		$start_date = $this->input->post('txt_begin_date');
		$end_date = $this->input->post('txt_end_date');
		$created_by = $this->session->userdata('NIK');
		$created_date = date('m/d/Y h:i:s');

		//cek sort number
		$total_cek_sort = $this->general_model->get_cek_sort_number_scala_add($behaviour_group,$scala,$sort);

		if($total_cek_sort!=0)
		{
			$data['notif_text']='Failed add Behaviour Group - Scala cause behaviour_group, scala and sort already exist.';
			$data['notif_type']='alert-error';
			$this->load->view('template/top_popup_1_view');
			$this->load->view('template/notif_view',$data);
			$this->load->view('template/bottom_popup_1_view');
		}
		else
		{
			$this->general_model->add_behaviour_group_scala($behaviour_group,$scala,$sort,$description,$start_date,$end_date,$created_by,$created_date);
			$data['notif_text']='Success add Behaviour Group - Scala';
			$data['notif_type']='alert-success';
			$this->load->view('template/top_popup_1_view');
			$this->load->view('template/notif_view',$data);
			$this->load->view('template/bottom_popup_1_view');
		}
		
	}
	function edit($id){
		$data['process']='admin/behaviour_group_scala/edit_process';
		$data['title']='Edit Behaviour Group - Scala';
		$data['do_act']='edit';
		$data['behaviour_group']=$this->general_model->get_behaviour_group_list(date('Y-m-d'),date('Y-m-d'));
		$data['scala']=$this->general_model->get_scala_list(date('Y-m-d'),date('Y-m-d'));
		$data['old']=$this->general_model->get_behaviour_group_scala_row($id);
		$this->load->view('template/top_popup_1_view');
		$this->load->view('admin/behaviour_group_scala_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('admin/behaviour_group_scala_form_js');
	}
	function edit_process(){
		$behaviour_group_scala_id=$this->input->post('txt_behaviour_group_scala_id');
		$behaviour_group = $this->input->post('slc_behaviour_group');
		$scala = $this->input->post('slc_scala');
		$sort =$this->input->post('txt_sort');
		$description = $this->input->post('txt_description');
		$start_date = $this->input->post('txt_begin_date');
		$end_date = $this->input->post('txt_end_date');
		$updated_by = $this->session->userdata('NIK');
		$updated_date = date('m/d/Y h:i:s');

		//cek sort number
		$total_cek_sort = $this->general_model->get_cek_sort_number_scala($behaviour_group_scala_id,$behaviour_group,$scala,$sort);

		if($total_cek_sort==0)
		{
			$data['notif_text']='Failed Update Behaviour Group - Scala cause behaviour_group, scala and sort already exist.';
			$data['notif_type']='alert-error';
			$this->load->view('template/top_popup_1_view');
			$this->load->view('template/notif_view',$data);
			$this->load->view('template/bottom_popup_1_view');
		}
		else
		{
			$this->general_model->edit_behaviour_group_scala($behaviour_group_scala_id,$behaviour_group,$scala,$sort,$description,$start_date,$end_date,$updated_by,$updated_date);
			$data['notif_text']='Success update Behaviour Group - Scala';
			$data['notif_type']='alert-success';
			$this->load->view('template/top_popup_1_view');
			$this->load->view('template/notif_view',$data);
			$this->load->view('template/bottom_popup_1_view');
		}
	}

	function delimit($id){
		$data['process']='admin/behaviour_group_scala/delimit_process';
		$data['title']='Delimit Behaviour Group Scala';
		$data['do_act']='delimit';
		$data['old']=$this->general_model->get_behaviour_group_scala_row($id);
		$this->load->view('template/top_popup_1_view');
		$this->load->view('admin/behaviour_group_scala_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('admin/behaviour_group_scala_form_js');
	}
	function delimit_process(){
		$behaviour_group_scala_id=$this->input->post('txt_behaviour_group_scala_id');
		$end_date = $this->input->post('txt_end_date');
		$updated_by = $this->session->userdata('NIK');
		$updated_date = date('m/d/Y h:i:s');
		$this->general_model->edit_delimit_behaviour_group_scala($behaviour_group_scala_id,$end_date,$updated_by,$updated_date);
		$data['notif_text']='Success delimit Behaviour Group Scala';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
}