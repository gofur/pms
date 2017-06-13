<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Rkk_copy extends Controller {
	function __construct(){
		parent::__construct();
		if(!$this->session->userdata('loginFlag')){
			redirect('account/login');
		}
		$this->load->model('rkk_model3');
		$this->load->model('general_model');
		$this->load->model('org_model');
		$this->load->model('account_model');
	}

	public function index()
	{
			
	}

	public function main($rkk_id)
	{
		$period     = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$rkk_A = $this->rkk_model3->get_rkk_row($rkk_id);
		$source_lbl =  array();
		$target_lbl =  array();
		$source_ls  =  array();
		$target_ls  =  array();

		// $source_ls = $this->rkk_model3->get_rkk_rel_AB_list($rkk_A->NIK,$rkk_A->PositionID,$rkk_A->isSAP,$period->BeginDate,$period->EndDate,'all');
		// $target_ls = $this->rkk_model3->get_rkk_rel_AB_list($rkk_A->NIK,$rkk_A->PositionID,$rkk_A->isSAP,$period->BeginDate,$period->EndDate,'open');

		$source_ls = $this->rkk_model3->get_rkk_rel_AB_list($rkk_A->NIK,$rkk_A->PositionID,$rkk_A->isSAP,$rkk_A->BeginDate,$rkk_A->EndDate,'all');
		$target_ls = $this->rkk_model3->get_rkk_rel_AB_list($rkk_A->NIK,$rkk_A->PositionID,$rkk_A->isSAP,$rkk_A->BeginDate,$rkk_A->EndDate,'open');

		foreach ($source_ls as $row) {
			$post = $this->org_model->get_Position_row($row->PositionID,$row->isSAP,$rkk_A->BeginDate,$rkk_A->EndDate)->PositionName;
			$source_lbl[$row->RKKID] = $row->NIK .' - '.$row->Fullname. ' - '. $post;
			unset($post);
		}

		foreach ($target_ls as $row) {
			$post = $this->org_model->get_Position_row($row->PositionID,$row->isSAP,$rkk_A->BeginDate,$rkk_A->EndDate)->PositionName;
			$key = $row->NIK.'|'. $row->PositionID.'|'. $row->isSAP;
			$source_lbl[$key] = $row->NIK .' - '.$row->Fullname. ' - '. $post;
			unset($post);
			unset($key);
		}

		$data['source_ls']  = $source_ls; 
		$data['source_lbl'] = $source_lbl;
		$data['target_ls']  = $target_ls; 
		$data['target_lbl'] = $source_lbl;

		$data['process'] = 'manager/rkk_copy/process';
		$data['hidden']  = array();
		
		$this->load->view('manager/rkk_copy_form',$data);
	}

	public function process()
	{
		$period     = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$this->form_validation->set_rules('rd_source', 'Source', 'trim|required|xss_clean');
		$this->form_validation->set_rules('rd_target', 'Target', 'trim|required|xss_clean');

		if ($this->form_validation->run()==TRUE) {
			$source = $this->input->post('rd_source');
			$target = $this->input->post('rd_target');
			list($target_nik,$target_post_id,$target_is_sap) = explode('|', $target);
			$rkk_source = $this->rkk_model3->get_rkk_row($source);
			$this->rkk_model3->copy_rkk($source,$target_nik,$target_post_id,$target_post_id,$rkk_source->BeginDate,$rkk_source->EndDate);
			$data['notif_type'] = 'alert-success';
			$data['notif_text'] = 'RKK have been copied';
		} else {
			$data['notif_type'] = 'alert-error';
			$data['notif_text'] = validation_errors();
		}
		$this->load->view('template/top_popup_1_view', $data, FALSE);
		$this->load->view('template/notif_view', $data, FALSE);
		$this->load->view('template/bottom_popup_1_view', $data, FALSE);

	}

}