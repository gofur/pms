<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends Controller{


  public function org_breadcrumb()
  {
    $this->load->model('general_model');
    $this->load->model('om_model');

    $period = $this->general_model->get_Period_row($this->session->userdata('active_period'));

    $org_id = $this->input->post('org_id');
    $temp    = array();
    $rest    = array();
    $i       = 0;
    if ($org_id == 'ROOT') {

    } else {
      do {
        $org = $this->om_model->get_org_row(TRUE,$org_id,$period->BeginDate,$period->EndDate);
        if ($org->OrganizationID!='50002147') {
          $temp[$i] = array('id' => $org->OrganizationID, 'label' => $org->OrganizationName);
          $i++;
        }


        $org_id = $org->OrganizationParent;
      // } while ($org->OrganizationParent > 50002147);
      } while ($org->OrganizationParent > 0);

    }
    $temp[$i] = array('id' => 'ROOT', 'label' => 'ROOT');
    for ($x=$i; $x >= 0 ; $x--) {
      $rest[$i-$x] = $temp[$x];
    }

    echo json_encode($rest);
  }

  public function org_branch()
  {
    $this->load->model('general_model');
    $this->load->model('om_model');
    $period = $this->general_model->get_Period_row($this->session->userdata('active_period'));

    $org_id = $this->input->post('org_id');

    if ($org_id == 'ROOT') {
      switch ($this->session->userdata('roleID')) {
        case 1: // Super Admin
  			case 3: // CHR Admin
          $ls = $this->om_model->get_org_byParent_list(TRUE,0,$period->BeginDate,$period->EndDate);
          break;
        case 5: // HR Manger
  			case 6: // HR Admin
  			case 9: // SMO
          $persAdmin = $this->session->userdata('PersAdmin');

          $org_ls = $this->om_model->get_hr_org_list(TRUE,$persAdmin,$period->BeginDate,$period->EndDate);

          $org_temp     = array();
  				foreach ($org_ls as $row) {
  					$org_temp[] = $row->OrganizationID;
  				}
  				$ls = $this->om_model->get_org_byID_list(TRUE,$org_temp,$period->BeginDate,$period->EndDate);
          break;
        default:
          $ls = array();
          break;
      }
    } else {
      $ls = $this->om_model->get_org_byParent_list(TRUE,$org_id,$period->BeginDate,$period->EndDate);
    }

    echo json_encode($ls);
  }

  public function org_holder()
  {
    $this->load->model('general_model');
    $this->load->model('om_model');
    $period = $this->general_model->get_Period_row($this->session->userdata('active_period'));

    $org_id = $this->input->post('org_id');
    $result = array();
    if ($org_id == 'ROOT') {
      switch ($this->session->userdata('roleID')) {
        case 1: // Super Admin
  			case 3: // CHR Admin
          $org_ls = $this->om_model->get_org_byParent_list(TRUE,0,$period->BeginDate,$period->EndDate);
          break;
        case 5: // HR Manger
  			case 6: // HR Admin
  			case 9: // SMO
          $persAdmin = $this->session->userdata('PersAdmin');

          $ls = $this->om_model->get_hr_org_list(TRUE,$persAdmin,$period->BeginDate,$period->EndDate);
          $org_temp     = array();
  				foreach ($ls as $row) {
  					$org_temp[] = $row->OrganizationID;
  				}
  				$org_ls = $this->om_model->get_org_byID_list(TRUE,$org_temp,$period->BeginDate,$period->EndDate);
          break;
        default:
          $org_ls = array();
          break;
      }
      $hold_ls = array();
    } else {
      $org_ls = $this->om_model->get_org_byParent_list(TRUE,$org_id,$period->BeginDate,$period->EndDate);


      $hold_ls = $this->om_model->get_hold_byOrg_list(TRUE, $org_id,$period->BeginDate,$period->EndDate);
    }
    $i = 0;
    foreach ($org_ls as $row) {
      $result[$i] = array(
        'objType' => 'org',
        'orgId'   => $row->OrganizationID,
        'orgName' => $row->OrganizationName,
      );
      $i++;
    }

    foreach ($hold_ls as $row) {
      $result[$i] = array(
        'objType'   => 'post',
        'orgId'     => $row->org_id,
        'orgName'   => $row->org_name,
        'postId'    => $row->PositionID,
        'postName'  => $row->post_name,
        'nik'       => $row->NIK,
        'empName'   => $row->Fullname,
        'holdBegin' => $row->user_BeginDate,
        'holdEnd'   => $row->user_EndDate,
      );
      $i++;
    }

    echo json_encode($result);

  }


}
