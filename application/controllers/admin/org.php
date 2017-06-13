<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Org extends Controller {
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
		$this->load->model('org_model');
	}
	var $index;	
	function index(){
		$data['notif_text']=$this->session->flashdata('notif_text');
		$data['notif_type']=$this->session->flashdata('notif_type');
		$this->index=0;
		$temp_list = array();
		$list_SAP = $this->build_tree(1,0,$temp_list);
		$list_SAP = '';
		
		$temp_list = array();
		$list = $this->build_tree(0,0,$temp_list);

		$data['list']=$list;
		$data['list_SAP']=$list_SAP;

		$this->load->view('template/top_1_view');
		$this->load->view('admin/org_view',$data);
		$this->load->view('template/bottom_1_view');
		$this->load->view('admin/org_view_js');

	}
	function add_org($parentID){
		if ($parentID!=0)
		{
			$data['head']=$this->org_model->get_Organization_row($parentID,$this->session->userdata('isSAP'));
		}
		else
		{
			
		}
		$data['title']='Add Organization';
		$data['process']='admin/org/add_org_process';

		$this->load->view('template/top_popup_1_view');
		$this->load->view('admin/org_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('admin/org_form_js');
	}
	function add_org_process(){
		$OrganizationParent = $this->input->post('TxtOrganizationParent');
		$OrganizationName = $this->input->post('TxtOrganizationName');
		$BeginDate = $this->input->post('TxtStartDate');
		$EndDate = $this->input->post('TxtEndDate');
		$this->org_model->add_Organization(0,$OrganizationName,$OrganizationParent,$BeginDate,$EndDate);
		$data['notif_text']='Success add Organization';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
	function edit_org($OrganizationID){
		$old = $this->org_model->get_Organization_row($OrganizationID,0);
		if ($old->OrganizationParent!=0){
			$data['head']=$this->org_model->get_Organization_row($old->OrganizationParent,0);
		}
		$data['old']=$old;
		$data['title']='Edit Organization';
		$data['process']='admin/org/edit_org_process';

		$this->load->view('template/top_popup_1_view');
		$this->load->view('admin/org_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('admin/org_form_js');

	}
	function edit_org_process(){
		$OrganizationID = $this->input->post('TxtOrganizationID');
		$OrganizationName = $this->input->post('TxtOrganizationName');
		$EndDate = $this->input->post('TxtEndDate');
		$this->org_model->edit_Organization($OrganizationID,0,$OrganizationName,$EndDate);
		$data['notif_text']='Success edit Organization';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
	function remove_org($OrganizationID){
		if($this->org_model->check_Organization_isUsed($OrganizationID)){
			$this->session->set_flashdata('notif_text','Other data using this Organization');
			$this->session->set_flashdata('notif_type','alert-error');
		}else{
			$this->org_model->delete_Organization($OrganizationID);
			$this->session->set_flashdata('notif_text','Success delete Organization');
			$this->session->set_flashdata('notif_type','alert-success');
		}
		redirect('admin/org');

	}
	function add_post($parentID){
		$data['head']=$this->org_model->get_Organization_row($parentID,$this->session->userdata('isSAP'));
		$data['title']='Add Position';
		$data['process']='admin/org/add_post_process';

		$this->load->view('template/top_popup_1_view');
		$this->load->view('admin/post_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('admin/post_form_js');
	}
	function add_post_process(){
		$OrganizationID = $this->input->post('TxtOrganizationID');
		$PositionName = $this->input->post('TxtPositionName');
		$PositionGroup = $this->input->post('SlcPositionGroup');
		$Chief = $this->input->post('RdChief');
		$BeginDate = $this->input->post('TxtStartDate');
		$EndDate = $this->input->post('TxtEndDate');
		$this->org_model->add_Position($OrganizationID,0,$PositionName,$Chief,$PositionGroup,$BeginDate,$EndDate);
		$data['notif_text']='Success add Position';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
	function edit_post($PositionID){
		$old = $this->org_model->get_Position_row($PositionID,0);
		$data['old']=$old;
		$data['head']=$this->org_model->get_Organization_row($old->OrganizationID,0);
		$data['title']='Edit Position';
		$data['process']='admin/org/edit_post_process';

		$this->load->view('template/top_popup_1_view');
		$this->load->view('admin/post_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('admin/post_form_js');
	}
	function edit_post_process(){
		$PositionID = $this->input->post('TxtPositionID');
		$PositionName = $this->input->post('TxtPositionName');
		$PositionGroup = $this->input->post('SlcPositionGroup');
		$Chief = $this->input->post('RdChief');
		$EndDate = $this->input->post('TxtEndDate');
		$this->org_model->edit_Position($PositionID,0,$PositionName,$Chief,$PositionGroup,$EndDate);
		$data['notif_text']='Success edit Position';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}
	function remove_post($PositionID){
		if($this->org_model->check_Position_isUsed($PositionID)){
			$this->session->set_flashdata('notif_text','Other data using this Position');
			$this->session->set_flashdata('notif_type','alert-error');
		}else{
			$this->org_model->delete_Position($PositionID,0);
			$this->session->set_flashdata('notif_text','Success delete Position');
			$this->session->set_flashdata('notif_type','alert-success');
		}
		redirect('admin/org');
	}

	function build_tree($isSAP,$parent=0,$list)
	{  	
	  $temp = $this->org_model->get_Organization_list($parent,$isSAP,'2009-01-01','9999-12-31');
	  foreach ($temp as $row) {
	  	$list[$this->index]['id']=$row->OrganizationID;
			$list[$this->index]['type']='<span class="label label-info">'.$row->ObjectType.'</span>';
			$list[$this->index]['node_id']='O'.$row->OrganizationID;
			$list[$this->index]['description']=$row->OrganizationName;
			$list[$this->index]['parent']=$row->OrganizationParent;
			$list[$this->index]['headOf']='';
			$list[$this->index]['post']='';
			if($isSAP){
				$list[$this->index]['edit_link']='#';
				$list[$this->index]['addChild_link']='#';
				$list[$this->index]['addPost_link']='#';
				$list[$this->index]['remove_link']='#';
			}else{
				$list[$this->index]['edit_link']='admin/org/edit_org/'.$row->OrganizationID;
				$list[$this->index]['addChild_link']='admin/org/add_org/'.$row->OrganizationID;
				$list[$this->index]['addPost_link']='admin/org/add_post/'.$row->OrganizationID;
				$list[$this->index]['remove_link']='admin/org/remove_org/'.$row->OrganizationID;
			}
			
			$list[$this->index]['begin']=$row->BeginDate;
			$list[$this->index]['end']=$row->EndDate;
			if ($this->org_model->count_Organization($row->OrganizationID,$isSAP,'','')>0){
				$this->index+=1;
				$list = $this->build_tree($isSAP,$row->OrganizationID,$list);
			}
	  	$this->index+=1;
	  	
	  	$temp_2 = $this->org_model->get_Position_list($row->OrganizationID,$isSAP,'2009-01-01','9999-12-31');
	  	
	  	foreach ($temp_2 as $row_2) {
				$list[$this->index]['id']=$row_2->PositionID;
				$list[$this->index]['node_id']='P'.$row_2->PositionID;
				$list[$this->index]['type']='<span class="label label-important">'.$row_2->ObjectType.'</span>';
				$list[$this->index]['description']=$row_2->PositionName;
				if($isSAP){
					$list[$this->index]['edit_link']='#';
					$list[$this->index]['remove_link']='#';
				}else{
					$list[$this->index]['edit_link']='admin/org/edit_post/'.$row_2->PositionID;
					$list[$this->index]['remove_link']='admin/org/remove_post/'.$row_2->PositionID;
					
				}

				$list[$this->index]['parent']=$row_2->OrganizationID;
				if($row_2->Chief==2){
					$image_properties = array(
          'src' => 'img/glyphicons/glyphicons_361_crown.png',
          'class' => 'icon');
					$list[$this->index]['headOf']=img($image_properties);
				}elseif($row_2->Chief==1){
					$image_properties = array(
          'src' => 'img/glyphicons/glyphicons_049_star.png',
          'class' => 'icon');
					$list[$this->index]['headOf']=img($image_properties);
				}else{
					$list[$this->index]['headOf']='';
				}
				$list[$this->index]['post']=$row_2->PositionGroup;
				$list[$this->index]['begin']=$row_2->BeginDate;
				$list[$this->index]['end']=$row_2->EndDate;
	  		$this->index+=1;
			}			

	  	$this->index+=1;
	  }
	  return $list;
	} 

}