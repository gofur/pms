<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rkk_transfer extends Controller {
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

  public function main($rkk_id=0)
  {
    $rkk_now = $this->rkk_model3->get_rkk_row($rkk_id);
    $end = date('Y-m-d', strtotime($rkk_now->BeginDate.' -1 day'));
    // $end = '2015-12-31';
    $rkk_ls = $this->rkk_model3->get_rkk_nik_list($rkk_now->NIK,'2008-01-01','9999-12-31','approve');
    $source = array();
    foreach ($rkk_ls as $row) {
      $source[$row->RKKID]['post']  = $this->org_model->get_Position_row($row->PositionID,$row->isSAP,$row->BeginDate,$row->EndDate)->PositionName;
      $source[$row->RKKID]['start'] = date('d M Y',strtotime($row->BeginDate));
      $source[$row->RKKID]['end']   = date('d M Y',strtotime($row->EndDate));
    }
    $data['source'] = $source;

    $data['process'] = 'manager/rkk_transfer/process';
    $data['hidden']  = array('target'=>$rkk_id);
    $this->load->view('manager/rkk_transfer/transfer_form',$data);
  }

  public function process()
  {
    $source = $this->input->post('rd_source');
    $target = $this->input->post('target');
    $this->form_validation->set_rules('rd_source', 'Source', 'trim|required');

    if ($this->form_validation->run()==TRUE) {
      $this->rkk_model3->transfer_rkk($source,$target);
      $data['notif_type'] = 'alert-success';
      $data['notif_text'] = 'RKK have been Transfer';
    } else {
      $data['notif_type'] = 'alert-error';
      $data['notif_text'] = validation_errors();
    }
    $this->load->view('template/top_popup_1_view', $data, FALSE);
    $this->load->view('template/notif_view', $data, FALSE);
    $this->load->view('template/bottom_popup_1_view', $data, FALSE);
  }

}

/* End of file rkk_transfer.php */
/* Location: ./application/controllers/manager/rkk_transfer.php */