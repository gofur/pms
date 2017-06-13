<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rkk extends Controller {
	function __construct(){
		parent::__construct();
		if(!$this->session->userdata('loginFlag')){
			redirect('account/login');
		}
		$this->load->model('general_model');
		$this->load->model('RKK_model');
		$this->load->model('account_model');

	}
	function index(){
		$data['notif_text']=$this->session->flashdata('notif_text');
		$data['notif_type']=$this->session->flashdata('notif_type');
		$userDetail=$this->account_model->get_User_row($this->session->userdata('userID'));
		$data['userDetail']=$userDetail;
		//get data periode yang sedang aktif
		$data['periode']=$this->general_model->get_ActivePeriode();
		//get data dari Perspective
		$listRKK= array();
		$listperspective=$this->general_model->get_Perspective_list(date("Y-m-d"),date("Y-m-d"));
		foreach ($listperspective as $row) 
		{
			$listRKK[$row->PerspectiveID]=$this->RKK_model->get_RKK_list(1,$row->PerspectiveID);
		}
		$data['listperspective']=$listperspective;
		$data['listRKK']=$listRKK;
		$this->load->view('template/top_1_view');
		$this->load->view('objective/rkk_view',$data);
		$this->load->view('template/bottom_1_view');
	}

	// Transaksi Objective //

	function add_objective($idPerspective){
		$data['process']='objective/rkk/add_objective_process';
		$data['title']='Add New Objective';
		$data['idPerspective']=$idPerspective;
		$data['OrganizationID']=$this->account_model->get_User_row($this->session->userdata('userID'), $this->session->userdata('isSAP'))->OrganizationID;
		$this->load->view('template/top_popup_1_view');
		$this->load->view('objective/objective_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('objective/objective_form_js');
	}

	function add_objective_process(){
		$Objective = $this->input->post('TxtObjective');
		$Description = $this->input->post('TxtDescription');
		$OrganizationID = $this->input->post('TxtOrganizationID');
		$PerspectiveID = $this->input->post('TxtPerspectiveID');
		$this->RKK_model->add_Objective($OrganizationID,$PerspectiveID,$Objective,$Description);
		$data['notif_text']='Success add objective';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}

	function edit_objective($id){
		$data['process']='objective/rkk/edit_objective_process';
		$data['title']='Edit Objective';
		$data['oldObjective']=$this->RKK_model->get_RKK_row($id);
		$this->load->view('template/top_popup_1_view');
		$this->load->view('objective/objective_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('objective/objective_form_js');
	}

	function edit_objective_process(){
		$ObjectiveID = $this->input->post('TxtObjectiveID');
		$Description = $this->input->post('TxtDescription');
		$Objective = $this->input->post('TxtObjective');
		$OrganizationID = $this->input->post('TxtOrganizationID');
		$PerspectiveID = $this->input->post('TxtPerspectiveID');
		$this->RKK_model->edit_Objective($ObjectiveID,$OrganizationID,$PerspectiveID,$Objective ,$Description);
		$data['notif_text']='Success edit objective';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}

	function delete_process($id){
		$this->RKK_model->remove_Objective($id);
		$this->session->set_flashdata('notif_text','Success delete objective');
		$this->session->set_flashdata('notif_type','alert-success');
		redirect('objective/rkk');
	}


	// Transaksi KPI //
	function add_kpi($ObjectiveID){
		$data['process']='objective/rkk/add_kpi_process';
		$data['title']='Add New KPI';
		$data['ObjectiveID']=$ObjectiveID;
		$data['satuanType']=$this->general_model->get_Satuan_list(date('Y-m-d'),date('Y-m-d'));
		$data['countType']=$this->general_model->get_CaraHitung_list(date('Y-m-d'),date('Y-m-d'));
		$data['ytdType']=$this->general_model->get_YTD_list(date('Y-m-d'),date('Y-m-d'));
		$data['OrganizationID']=$this->account_model->get_User_row($this->session->userdata('userID'), $this->session->userdata('isSAP'))->OrganizationID;
		$this->load->view('template/top_popup_1_view');
		$this->load->view('objective/kpi_form',$data);
		$this->load->view('template/bottom_popup_1_view');
		$this->load->view('objective/kpi_form_js');
	}

	function add_kpi_process(){
		$Objective = $this->input->post('TxtObjective');
		$Description = $this->input->post('TxtDescription');
		$OrganizationID = $this->input->post('TxtOrganizationID');
		$PerspectiveID = $this->input->post('TxtPerspectiveID');
		$this->RKK_model->add_Objective($OrganizationID,$PerspectiveID,$Objective,$Description);
		$data['notif_text']='Success add kpi';
		$data['notif_type']='alert-success';
		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}

	//tree table di rkk view
	private function build_tree($parent=0,$list)
	{  	
	  $temp = $this->org_model->get_Organization_nonSAP_list($parent,'','');
	  foreach ($temp as $row) {
	  	$list[$this->index]['id']=$row->OrganizationID;
			$list[$this->index]['type']='<span class="label label-info">'.$row->ObjectType.'</span>';
			$list[$this->index]['node_id']='O'.$row->OrganizationID;
			$list[$this->index]['description']=$row->OrganizationName;
			$list[$this->index]['parent']=$row->OrganizationParent;
			$list[$this->index]['headOf']='';
			$list[$this->index]['post']='';
			$list[$this->index]['edit_link']='objective/rkk/edit_org/'.$row->OrganizationID;
			$list[$this->index]['addChild_link']='admin/org/add_org/'.$row->OrganizationID;
			$list[$this->index]['addPost_link']='admin/org/add_post/'.$row->OrganizationID;
			$list[$this->index]['remove_link']='admin/org/remove_org/'.$row->OrganizationID;
			$list[$this->index]['begin']=$row->BeginDate;
			$list[$this->index]['end']=$row->EndDate;
			if ($this->org_model->count_Organization_nonSAP($row->OrganizationID,'','')>0){
				$this->index+=1;
				$list = $this->build_tree($row->OrganizationID,$list);
			}
	  	$this->index+=1;
	  	
	  	$temp_2 = $this->org_model->get_Position_nonSAP_list($row->OrganizationID,'','');
	  	foreach ($temp_2 as $row_2) {
				$list[$this->index]['id']=$row_2->PositionID;
				$list[$this->index]['node_id']='P'.$row_2->PositionID;
				$list[$this->index]['type']='<span class="label label-important">'.$row_2->ObjectType.'</span>';
				$list[$this->index]['description']=$row_2->PositionName;
				$list[$this->index]['edit_link']='admin/org/edit_post/'.$row_2->PositionID;
				$list[$this->index]['remove_link']='admin/org/remove_post/'.$row_2->PositionID;

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
