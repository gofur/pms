<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Achv_rev extends Controller 
{
	function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('loginFlag'))
		{
			redirect('account/login');
		}
		$this->load->model('achv_biz_model');
		$this->load->model('rkk_model3');
		$this->load->model('account_model');
		$this->load->model('om_model');
		$this->load->model('general_model');
	}

	public function index()
	{
		$period       = $this->general_model->get_ActivePeriode();
		$pers_admin   = $this->session->userdata('PersAdmin');
		$nik          = $this->session->userdata('NIK');
		$user_dtl     = $this->account_model->get_User_byNIK($nik);
		if (date('n') == 1) {
			$month = 1;
		} else {
			$month = date('n')-1;
		}
		$data['month']    = $month;
		$data['period']   = $period;
		$data['user_dtl'] = $user_dtl;
		

		$this->load->view('hr/achv_rev/main_view', $data);
	}

	public function show_root_org()
	{
		$pers_admin = $this->session->userdata('PersAdmin');
		$is_sap     = $this->session->userdata('isSAP');
		$month      = $this->input->post('month');
		$period     = $this->general_model->get_ActivePeriode();
		$begin      = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, substr($period->BeginDate, 0,4)));
		$end        = date('Y-m-t H:i:s', mktime(23, 59, 59, $month, 1, substr($period->BeginDate, 0,4))); 
		$org_ls     = $this->om_model->get_hr_org_list($is_sap,$pers_admin,$begin,$end);
		$org_opt    = array(''=>'');
		foreach ($org_ls as $row) {
			$org_opt[$row->OrganizationID] = $row->OrganizationName;
		}
		$data['org_ls'] = $org_opt;
		$data['num'] 		= 0;

		$this->load->view('hr/achv_rev/org_opt', $data);

	}

	public function show_child_org()
	{
		$month     = $this->input->post('month');
		$is_sap    = $this->session->userdata('isSAP');
		$period    = $this->general_model->get_ActivePeriode();
		$begin     = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, substr($period->BeginDate, 0,4)));
		$end       = date('Y-m-t H:i:s', mktime(23, 59, 59, $month, 1, substr($period->BeginDate, 0,4)));
		$parent_id = $this->input->post('parent');
		$num       = $this->input->post('num');
		$c_org     = $this->om_model->count_org_byParent($is_sap,$parent_id,$begin,$end);
		if ($c_org) {
			$org_ls = $this->om_model->get_org_byParent_list($is_sap,$parent_id,$begin,$end);
			$org_opt    = array(''=>'');
			foreach ($org_ls as $row) {
				$org_opt[$row->OrganizationID] = $row->OrganizationName;

			}
			$data['org_ls'] = $org_opt;
			$data['num']    = $num + 1;
			$this->load->view('hr/achv_rev/org_opt', $data);
		}
	}

	public function show_post()
	{
		$org_id = $this->input->post('org');
		$month  = $this->input->post('month');
		$is_sap    = $this->session->userdata('isSAP');
		
		$period = $this->general_model->get_ActivePeriode();
		$begin  = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, substr($period->BeginDate, 0,4)));
		$end    = date('Y-m-t H:i:s', mktime(23, 59, 59, $month, 1, substr($period->BeginDate, 0,4)));
		$c_post = $this->om_model->count_post_byOrg($is_sap,$org_id,$begin,$end);
		if ($c_post) {
			$post_ls = $this->om_model->get_post_byOrg_list($is_sap,$org_id,$begin,$end);
			$emp_ls = array();
			$i = 0;
			$base_link = 'hr/achv_rev/view/';
			foreach ($post_ls as $row) {
				$c_holder = $this->om_model->count_hold_byPost($is_sap,$row->PositionID,$begin,$end);
				if ($c_holder) {
					$holder = $this->om_model->get_hold_byPost_last($is_sap,$row->PositionID,$begin,$end);
					$c_rkk = $this->rkk_model3->count_rkk_holder($holder->NIK,$row->PositionID,$is_sap, $begin, $end,$status='final');
					if ($c_rkk) {
						$emp_ls[$i]['text'] = $holder->NIK . ' - '.$holder->Fullname .' - '.$row->PositionName;
						$emp_ls[$i]['link'] = $base_link.$month.'/'.$holder->NIK.'/'.$row->PositionID.'/'.$is_sap;
					} else {
						$emp_ls[$i]['text'] = $holder->NIK . ' - '.$holder->Fullname .' - '.$row->PositionName .' - Don&#39;t have RKK';
						$emp_ls[$i]['link'] = '';

					}
					$i++;
				} 

			}

			$data['emp_ls'] = $emp_ls;
			$data['max'] = $i;
			$this->load->view('template/emp_view', $data, FALSE);
		}

	}

	public function view($month,$nik,$post,$is_sap)
	{
		$period = $this->general_model->get_ActivePeriode();
		$begin  = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, substr($period->BeginDate, 0,4)));
		$end    = date('Y-m-t H:i:s', mktime(23, 59, 59, $month, 1, substr($period->BeginDate, 0,4)));

		$user_dtl = $this->account_model->get_User_byNIK($nik);
		$post_B   = $this->om_model->get_post_row($is_sap,$post,$begin,$end);
		

		$data['post']      = $post_B;
		$data['user_dtl']  = $user_dtl;
		$data['month']     = $month;

		$this->load->view('hr/achv_rev/emp_view', $data);

	}

	public function check_head()
	{
		$period   = $this->general_model->get_ActivePeriode();
		$sess_nik = $this->session->userdata("NIK");
		$nik      = $this->input->post('nik');
		$holder   = $this->input->post('holder');
		$month    = $this->input->post('month');
		$begin    = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, substr($period->BeginDate, 0,4)));
		$end      = date('Y-m-t H:i:s', mktime(23, 59, 59, $month, 1, substr($period->BeginDate, 0,4)));

		if ($holder == '') {
			$is_sap  = $this->input->post('is_sap');
			$post_id = $this->input->post('post_id');
		} else {
			list($is_sap,$holder_id) = explode('.',$holder);
			$holder_dtl = $this->account_model->get_Holder_row($holder_id,$is_sap);
			$post_id    = $holder_dtl->PositionID;
		} 

		$c_rkk = $this->rkk_model3->count_rkk_holder($nik,$post_id,$is_sap, $begin, $end,'approve');

		if ($c_rkk > 0 ) {
			$self_rkk    = $this->rkk_model3->get_rkk_holder_last($nik,$post_id,$is_sap, $begin, $end,'approve');
			$rel_rkk_BA  = $this->rkk_model3->get_rkk_rel_last($self_rkk->RKKID,$begin, $end);
			$c_achv_head = $this->achv_biz_model->count_header_month($self_rkk->RKKID,$month,'all');

			if ($c_achv_head > 0 ) {
				$achv_head = $this->achv_biz_model->get_header_month_row($self_rkk->RKKID,$month,'all');
			} else {
				$this->achv_biz_model->add_header($self_rkk->RKKID,$month);
				$achv_head = $this->achv_biz_model->get_header_month_row($self_rkk->RKKID,$month,'all');

			}

			$persp_ls         = $this->general_model->get_Perspective_List($begin, $end);
			$so_ls            = array();
			$persp_weight     = array();
			$user_A           = $this->account_model->get_User_byNIK($rel_rkk_BA->chief_nik);

			$data['rkk_id']        = $self_rkk->RKKID;
			$data['achv_head']     = $achv_head;
			$data['persp_ls']      = $persp_ls;
			$data['rkk']           = $self_rkk;
			$data['user_A']        = $user_A;
			$data['post_A']        = $this->om_model->get_post_row($rel_rkk_BA->chief_is_sap,$rel_rkk_BA->chief_post_id,$begin, $end);

			if (($achv_head->Status_Flag == 0 OR $achv_head->Status_Flag == 2) && $sess_nik == $nik) {
				
				$data['link_submit'] 	= 'performance/achievement/submit/'.$achv_head->RKKAchievementID;
			} elseif ( $sess_nik != $nik && $achv_head->Status_Flag == 1) {
				$data['link_agree'] 	= 'performance/achievement/agree/'.$achv_head->RKKAchievementID;
				$data['link_reject'] 	= 'performance/achievement/reject/'.$achv_head->RKKAchievementID;
			} elseif ($sess_nik != $nik && $achv_head->Status_Flag == 3) {
				$data['link_unlock'] 	= 'performance/achievement/submit/'.$achv_head->RKKAchievementID;
			}

			foreach ($persp_ls as $persp) {
				$so_ls[$persp->PerspectiveID] = $this->rkk_model3->get_so_persp_list($self_rkk->RKKID,$persp->PerspectiveID,$begin,$end);

				$persp_weight[$persp->PerspectiveID] = $this->rkk_model3->sum_weight_persp($self_rkk->RKKID,$persp->PerspectiveID,$begin,$end);		
			}
			$data['so_ls']        = $so_ls;
			$data['persp_weight'] = $persp_weight;
			$this->load->view('performance/achv/achv_view',$data);

		} else {
			$data['notif_type'] = 'alert-error';
			$data['notif_text'] = 'RKK not Available';
			$this->load->view('template/notif_view', $data, FALSE);

		}
	}

	
	
}