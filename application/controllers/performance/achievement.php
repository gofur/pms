<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Achievement extends Controller 
{
	function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('loginFlag'))
		{
			redirect('account/login');
		}
		$this->load->library('email');
		$this->load->model('achv_biz_model');
		$this->load->model('rkk_model3');
		$this->load->model('account_model');
		$this->load->model('org_model');
		$this->load->model('general_model');
	}

	public function index()
	{
		$period       = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$user_id      = $this->session->userdata('userID');
		$nik          = $this->session->userdata('NIK');
		$user_dtl     = $this->account_model->get_User_byNIK($nik);
		if (date('n') == 1) {
			$month = 1;
		} else {
			$month = date('n')-1;
		}
		$data['month']            = $month;
		$data['period']           = $period;
		$data['user_dtl']         = $user_dtl;
		$data['post_ls_SAP']      = $this->account_model->get_Holder_list($nik,1,$period->BeginDate,$period->EndDate);
		$data['post_ls_nonSAP']   = $this->account_model->get_Holder_list($nik,0,$period->BeginDate,$period->EndDate);
		$data['assign_ls_SAP']    = $this->account_model->get_Assignment_list($nik,1,$period->BeginDate,$period->EndDate);
		$data['assign_ls_nonSAP'] = $this->account_model->get_Assignment_list($nik,0,$period->BeginDate,$period->EndDate);

		$this->load->view('performance/achv/main_view', $data);
	}

	public function view_subordinate($chief_rkk_id,$month,$nik,$post,$is_sap)
	{
		$this->session->unset_userdata('achv_sub');
		$param = $chief_rkk_id.'|'.$month.'|'.$nik.'|'.$post.'|'.$is_sap;
		$this->session->set_userdata('achv_sub',$param);
		if ($chief_rkk_id==0) {
			$period       = $this->general_model->get_Period_row($this->session->userdata('active_period'));
			$begin  = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, substr($period->BeginDate, 0,4)));
			$end    = date('Y-m-t H:i:s', mktime(23, 59, 59, $month, 1, substr($period->BeginDate, 0,4))); 

		} else {
			$rkk_A = $this->rkk_model3->get_rkk_row($chief_rkk_id);
			$begin = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, substr($rkk_A->BeginDate, 0,4)));
			$end   = date('Y-m-t H:i:s', mktime(23, 59, 59, $month, 1, substr($rkk_A->BeginDate, 0,4))); 
			
		}
		$user_dtl = $this->account_model->get_User_byNIK($nik);
		$post_B   = $this->org_model->get_Position_row($post,$is_sap,$begin,$end);

		$data['link_self'] = 'performance/achievement';
		$data['post']      = $post_B;
		$data['user_dtl']  = $user_dtl;
		$data['month']     = $month;

		$this->load->view('performance/achv/subordinate_view', $data);
	}

	public function agree($achv_id=0)
	{
		$head     = $this->achv_biz_model->get_header_row($achv_id);
		$rkk_B 		= $this->rkk_model3->get_rkk_row($head->RKKID);
		$begin    = date('Y-m-d H:i:s', mktime(0, 0, 0, $head->Month, 1, substr($rkk_B->BeginDate, 0,4)));
		$end      = date('Y-m-t H:i:s', mktime(23, 59, 59, $head->Month, 1, substr($rkk_B->BeginDate, 0,4)));
		$sub_name      = $this->account_model->get_User_byNIK($rkk_B->NIK)->Fullname;
		$sub_email   = $this->account_model->get_User_byNIK($rkk_B->NIK)->Email;
		$monthName = date("F", mktime(0, 0, 0, $head->Month, 10));
 		

		$this->achv_biz_model->edit_header_status($achv_id,3);

		/**
		 * Send email to chief for achievement
		 */
		
		// $config['smtp_host'] ="10.10.55.10";
		// $config['smtp_user'] ="pms@chr.kompasgramedia.com";
		// $config['smtp_pass'] ="Abc123"; 
		// $config['mailtype']  ='html';
		// $config['priority']  =1;
		// $config['protocol']  ='smtp';
		// $this->email->initialize($config);
		// $this->email->from('pms@chr.kompasgramedia.com', '[PMS Online] DO NOT REPLY THIS EMAIL!');
		// $this->email->to($sub_email);
		// $this->email->subject('[PMS Online] Achievement Information');
		// $this->email->message("<h2>Information</h2>
		// 	Achievement ".$sub_name." for ".$monthName." has been approve, 
		// 	please check your PMS Online.<br> 
		// 	Notes : ".$head->Notes."<br><br>
		// 	If you're not ".$sub_name.",please ignore this email. <br>Thank you,<br><br>PMS Online");
		
		// if($this->email->send()){
		// 	$array = array(
		// 		'notif_text' => 'Email has been sent',
		// 		'notif_type' => 'alert-success'
		// 	);
		// }else{
		// 	$array = array(
		// 		'notif_text' => 'Email has not been sent',
		// 		'notif_type' => 'alert-danger'
		// 	);
		// }
		// $this->session->set_userdata( $array );


		for ($month=1; $month < 13 ; $month++) { 
			$this->calc_tpc($head->RKKID,$month);
		}
		
		$rel = $this->rkk_model3->get_rkk_rel_last($head->RKKID,$begin,$end);
		$c_rkk = $this->rkk_model3->count_rkk_holder($rel->chief_nik,$rel->chief_post_id,$rel->chief_is_sap,$begin,$end,'all');
		if ($c_rkk) {
			$rkk_A = $this->rkk_model3->get_rkk_holder_last($rel->chief_nik,$rel->chief_post_id,$rel->chief_is_sap,$begin,$end,'all');
			redirect('performance/achievement/view_subordinate/'.$rkk_A->RKKID.'/'.$head->Month.'/'.$rkk_B->NIK.'/'.$rkk_B->PositionID.'/'.$rkk_B->isSAP);
		} else {
			redirect('performance/achievement');
			
		}
	}

	public function reject($achv_id=0)
	{
		$this->achv_biz_model->edit_header_status($achv_id,2);
		$header = $this->achv_biz_model->get_header_row($achv_id);

		$Periode       = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$sub_rkk = $this->rkk_model3->get_rkk_row($header->RKKID);
		$sub_name      = $this->account_model->get_User_byNIK($sub_rkk->NIK)->Fullname;
		$sub_email   = $this->account_model->get_User_byNIK($sub_rkk->NIK)->Email;
		$monthName = date("F", mktime(0, 0, 0, $header->Month, 10));
 		
		/**
		 * Send email to chief for achievement
		 */
	/*	
		$config['smtp_host'] ="10.10.55.10";
		$config['smtp_user'] ="pms@chr.kompasgramedia.com";
		$config['smtp_pass'] ="Abc123"; 
		$config['mailtype']  ='html';
		$config['priority']  =1;
		$config['protocol']  ='smtp';
		$this->email->initialize($config);
		$this->email->from('pms@chr.kompasgramedia.com', '[PMS Online] DO NOT REPLY THIS EMAIL!');
		$this->email->to($sub_email);
		$this->email->subject('[PMS Online] Achievement Information');
		$this->email->message("<h2>Information</h2>
			Achievement ".$sub_name." for ".$monthName." has been rejected, 
			please check your PMS Online.<br> 
			Notes : ".$header->Notes."<br><br>
			If you're not ".$sub_name.",please ignore this email. <br>Thank you,<br><br>PMS Online");
		
		if($this->email->send()){
			$array = array(
				'notif_text' => 'Email has been sent',
				'notif_type' => 'alert-success'
			);
		}else{
			$array = array(
				'notif_text' => 'Email has not been sent',
				'notif_type' => 'alert-danger'
			);
		}
	*/
		$this->session->set_userdata( $array );
		redirect('performance/achievement');
	}

	public function submit($achv_id=0)
	{
		$header = $this->achv_biz_model->get_header_row($achv_id);
		$c_detail = $this->achv_biz_model->count_detail_month($achv_id);
		$c_target = $this->rkk_model3->count_target_rkk_month($header->RKKID, $header->Month);

		if ($c_target <= $c_detail) {
			$this->achv_biz_model->edit_header_status($achv_id,1);
			$array = array(
				'notif_text' => 'Achievement Submitted',
				'notif_type' => 'alert-success'
			);
			
			$this->session->set_userdata( $array );

			/**
			 * Send email to chief for achievement
			 */
			$Periode       = $this->general_model->get_Period_row($this->session->userdata('active_period'));
			$chief 		     = $this->rkk_model3->get_rkk_rel_last($header->RKKID,$Periode->BeginDate,$Periode->EndDate);
			$sub_name      = $this->account_model->get_User_byNIK($this->session->userdata('NIK'))->Fullname;
			$chief_name    = $this->account_model->get_User_byNIK($chief->chief_nik)->Fullname;
			$chief_email   = $this->account_model->get_User_byNIK($chief->chief_nik)->Email;
			$monthName = date("F", mktime(0, 0, 0, $header->Month, 10));
			$config['smtp_host'] ="10.10.55.10";
			$config['smtp_user'] ="pms@chr.kompasgramedia.com";
			$config['smtp_pass'] ="Abc123"; 
			$config['mailtype']  ='html';
			$config['priority']  =1;
			$config['protocol']  ='smtp';
			$this->email->initialize($config);
			$this->email->from('pms@chr.kompasgramedia.com', '[PMS Online] DO NOT REPLY THIS EMAIL!');
			$this->email->to($chief_email);
			$this->email->subject('[PMS Online] Achievement Information');
			$this->email->message("<h2>Information</h2>
				".$sub_name." achievement on ".$monthName." has been submitted, 
				please check your PMS Online.<br> 
				Executive summary : ".$header->Summary."<br><br>
				If you're not ".$chief_name.",please ignore this email. <br>Thank you,<br><br>PMS Online");
			
			/*if($this->email->send()){
				$this->session->set_flashdata('notif_text',"Email has been sent.");
				$this->session->set_flashdata('notif_type','alert-success');
			}else{
				$this->session->set_flashdata('notif',"Email has not sent");
				$this->session->set_flashdata('notif_type',"alert-danger");
			}*/

		} else {
			$array = array(
				'notif_text'  => 'Achievement Not Submitted. There are achievements that have not been filled',
				// 'notif_text' => 'Target : '.$c_target .'. Real : '.$c_detail ,
				'notif_type' => 'alert-error'
			);
			
			$this->session->set_userdata( $array );
		}

		redirect('performance/achievement');
	}

	public function unlock($achv_id=0)
	{
		$this->achv_biz_model->edit_header_status($achv_id,1);
		$array = array(
			'notif_text' => 'Achievement Submitted',
			'notif_type' => 'alert-success'
		);
		$param = $this->session->userdata('achv_sub');
		$this->session->unset_userdata('achv_sub');
		$this->session->set_userdata( $array );
		redirect('performance/achievement/view_subordinate/'.str_replace('|', '/', $param));

	}

	public function show_subordinate()
	{
		$period       = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$sess_nik = $this->session->userdata("NIK");
		$nik      = $this->input->post('nik');
		$holder   = $this->input->post('holder');
		$month    = $this->input->post('month');
		$begin    = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, substr($period->BeginDate, 0,4)));
		$end      = date('Y-m-t H:i:s', mktime(0, 0, 0, $month, 1, substr($period->EndDate, 0,4)));

		if ($nik == '') {
			$nik = $sess_nik;
		}

		if ($holder == '') {
			$is_sap  = $this->input->post('is_sap');
			$post_id = $this->input->post('post_id');
		} else {
			list($is_sap,$holder_id) = explode('.',$holder);
			$holder_dtl = $this->account_model->get_Holder_row($holder_id,$is_sap);
			$post_id    = $holder_dtl->PositionID;
		}

		$sub_ls       = $this->org_model->get_directSubordinate_list($is_sap,$post_id,$begin,$end);
		if (count($sub_ls)) {
			$count_rkk = $this->rkk_model3->count_rkk_holder($nik,$post_id,$is_sap, $begin, $end,'approve');
			$base_link = 'performance/achievement/view_subordinate/';
			$link = array();
			if ($count_rkk > 0) {
				$rkk = $this->rkk_model3->get_rkk_holder_last($nik,$post_id,$is_sap, $begin, $end,'approve');
				foreach ($sub_ls as $sub) {
					$key = $sub->NIK.'|'.$sub->isSAP.'|'.$sub->PositionID;
					$param = $rkk->RKKID.'/'.$month.'/'.$sub->NIK.'/'.$sub->PositionID.'/'.$sub->isSAP;
					$link[$key] = $base_link.$param;
				}
				$data['link'] = $link;
			} else {
				$rkk = $this->rkk_model3->get_rkk_holder_last($nik,$post_id,$is_sap, $begin, $end,'approve');
				foreach ($sub_ls as $sub) {
					$key = $sub->NIK.'|'.$sub->isSAP.'|'.$sub->PositionID;
					$param = '0/'.$month.'/'.$sub->NIK.'/'.$sub->PositionID.'/'.$sub->isSAP;
					$link[$key] = $base_link.$param;
				}
				$data['link'] = $link;
			}
			$data['sub_ls']   = $sub_ls;
			$this->load->view('template/subordinate_view', $data, FALSE);
		}
	}

	public function show_dashboard()
	{
		$period       = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$sess_nik = $this->session->userdata("NIK");
		$nik      = $this->input->post('nik');
		$holder   = $this->input->post('holder');
		$month    = $this->input->post('month');
		$begin    = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, substr($period->BeginDate, 0,4)));
		$end      = date('Y-m-t H:i:s', mktime(23, 59, 59, $month, 1, substr($period->EndDate, 0,4)));

		$data['color_range'] = $this->general_model->get_Scale_list(2,date('Y-m-d'),date('Y-m-d'));
		$data['max_high']    = $this->general_model->get_Scale_statistic(2,date('Y-m-d'),date('Y-m-d'))->high_max;

		if ($holder == '') {
			$is_sap  = $this->input->post('is_sap');
			$post_id = $this->input->post('post_id');
		} else {
			list($is_sap,$holder_id) = explode('.',$holder);
			$holder_dtl = $this->account_model->get_Holder_row($holder_id,$is_sap);
			$post_id    = $holder_dtl->PositionID;
		}

		$c_rkk = $this->rkk_model3->count_rkk_holder($nik,$post_id,$is_sap, $begin, $end,'approve');
		if ($c_rkk > 0) {
			$self_rkk    = $this->rkk_model3->get_rkk_holder_last($nik,$post_id,$is_sap, $begin, $end,'approve');
			$c_achv_head = $this->achv_biz_model->count_header_month($self_rkk->RKKID,$month,'all');
			if ($c_achv_head > 0) {
				$achv_head = $this->achv_biz_model->get_header_month_row($self_rkk->RKKID,$month,'all');
				$persp_ls  = $this->general_model->get_Perspective_List($begin, $end);
				$cur_val = 0; 
				$ytd_val = 0;  
 				$persp_cur_val = array();
 				$persp_ytd_val = array();
				foreach ($persp_ls as $row) {
					$persp_cur_val[$row->PerspectiveID] = $this->achv_biz_model->calc_cur_tpc_persp($achv_head->RKKAchievementID,$row->PerspectiveID);

					$persp_ytd_val[$row->PerspectiveID] = $this->achv_biz_model->calc_ytd_tpc_persp($achv_head->RKKAchievementID,$row->PerspectiveID);
					$persp_weight_cur[$row->PerspectiveID]  = $this->rkk_model3->sum_weight_cur_persp($self_rkk->RKKID,$row->PerspectiveID,$month,$begin,$end);
					$persp_weight_ytd[$row->PerspectiveID]  = $this->rkk_model3->sum_weight_ytd_persp($self_rkk->RKKID,$row->PerspectiveID,$month,$begin,$end);

					if ($persp_cur_val[$row->PerspectiveID] == '') {
						$persp_cur_val[$row->PerspectiveID] = 0;
					}

					if ($persp_ytd_val[$row->PerspectiveID] == '') {
						$persp_ytd_val[$row->PerspectiveID] = 0;
					}

					if ($persp_weight_cur[$row->PerspectiveID]  == '') {
						$persp_weight_cur[$row->PerspectiveID]  = 0;
					}

					if ($persp_weight_ytd[$row->PerspectiveID]  == '') {
						$persp_weight_ytd[$row->PerspectiveID]  = 0;
					}

					$cur_val += $persp_cur_val[$row->PerspectiveID] * $persp_weight_ytd[$row->PerspectiveID];
					$ytd_val += $persp_ytd_val[$row->PerspectiveID] * $persp_weight_ytd[$row->PerspectiveID];
				}

				$gt_cur_weight = array_sum($persp_weight_cur);
				$gt_ytd_weight = array_sum($persp_weight_ytd);
				
				$tpc = $this->calc_tpc($self_rkk->RKKID,$month);

				$gt_cur_val = $tpc['cur'];
				$gt_ytd_val = $tpc['ytd'];

				$data['persp_ls']      = $persp_ls;
				$data['persp_cur_val'] = $persp_cur_val;
				$data['persp_ytd_val'] = $persp_ytd_val;
				$data['gt_cur_weight'] = $gt_cur_weight;
				$data['gt_ytd_weight'] = $gt_ytd_weight;
				$data['gt_cur_val']    = $gt_cur_val;
				$data['gt_ytd_val']    = $gt_ytd_val;
				$data['persp_weight_ytd']  = $persp_weight_ytd;
				$data['persp_weight_cur']  = $persp_weight_cur;

				$data['scale'] = $this->general_model->get_Scale_list(2,$begin,$end);

				$this->load->view('performance/achv/dashboard_view',$data);

			}
		}
	}

	public function check_head()
	{
		$param = $this->session->userdata('achv_sub');
		$period     = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$notif_text = $this->session->userdata('notif_text');
		$notif_type = $this->session->userdata('notif_type');
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

			$persp_ls     = $this->general_model->get_Perspective_List($begin, $end);
			$so_ls        = array();
			$persp_weight = array();
			if (count($rel_rkk_BA)) {
			$user_A         = $this->account_model->get_User_byNIK($rel_rkk_BA->chief_nik);
			$data['user_A'] = $user_A;
			$data['post_A'] = $this->org_model->get_Position_row($rel_rkk_BA->chief_post_id,$rel_rkk_BA->chief_is_sap,$begin, $end);
			}

			$data['rkk_id']        = $self_rkk->RKKID;
			$data['achv_head']     = $achv_head;
			$data['persp_ls']      = $persp_ls;
			$data['rkk']           = $self_rkk;

			if (($achv_head->Status_Flag == 0 OR $achv_head->Status_Flag == 2) && $sess_nik == $nik) {
				
				$data['link_submit'] 	= 'performance/achievement/submit/'.$achv_head->RKKAchievementID;
			} elseif ( $achv_head->Status_Flag == 1) {
				if (count($rel_rkk_BA)  && $sess_nik == $rel_rkk_BA->chief_nik) {
					$data['link_agree'] 	= 'performance/achievement/agree/'.$achv_head->RKKAchievementID;
					$data['link_reject'] 	= 'performance/achievement/reject/'.$achv_head->RKKAchievementID;
				}
			} elseif ($sess_nik != $nik && $achv_head->Status_Flag == 3) {
				$data['link_unlock'] 	= 'performance/achievement/unlock/'.$achv_head->RKKAchievementID;
			}

			foreach ($persp_ls as $persp) {
				$so_ls[$persp->PerspectiveID] = $this->rkk_model3->get_so_persp_list($self_rkk->RKKID,$persp->PerspectiveID,$begin,$end,1);

				$persp_weight[$persp->PerspectiveID] = $this->rkk_model3->sum_weight_persp($self_rkk->RKKID,$persp->PerspectiveID,$begin,$end);		
			}
			
			$data['so_ls']        = $so_ls;
			$data['persp_weight'] = $persp_weight;
			if ($notif_text != '') {
				$data['notif_type'] = $notif_type;
				$data['notif_text'] = $notif_text;
				$this->load->view('template/notif_view', $data, FALSE);
				$this->session->unset_userdata('notif_type');
				$this->session->unset_userdata('notif_text');
			}
			$this->load->view('performance/achv/achv_view',$data);

		} else {
			$data['notif_type'] = 'alert-error';
			$data['notif_text'] = 'RKK not Available';
			$this->load->view('template/notif_view', $data, FALSE);

		}
	}

	public function show_kpi()
	{
		$period      = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$sess_nik    = $this->session->userdata("NIK");
		$rkk_id      = $this->input->post('rkk_id');
		$so_id       = $this->input->post('so_id');
		$month       = $this->input->post('month');
		$begin       = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, substr($period->BeginDate, 0,4)));
		$end         = date('Y-m-t H:i:s', mktime(23, 59, 59, $month, 1, substr($period->BeginDate, 0,4)));
		$kpi_ls      = $this->rkk_model3->get_kpi_so_list($rkk_id,$so_id,$begin,$end);
		$rkk         = $this->rkk_model3->get_rkk_row($rkk_id);
		$achv_head   = $this->achv_biz_model->get_header_month_row($rkk_id,$month,'all');
	
		if ($rkk->NIK == $sess_nik) {
			$c_achv_head = $this->achv_biz_model->count_header_month($rkk_id,$month,'open');

		} else {
			$c_achv_head = $this->achv_biz_model->count_header_month($rkk_id,$month,'close');

		}
		
		$month_target = array();
		$month_achv   = array();
		$month_evid   = array();
		$month_persen = array();
		$month_pc     = array();
		$month_color  = array();
		$ytd_target   = array();
		$ytd_achv     = array();
		$ytd_persen   = array();
		$ytd_pc       = array();
		$ytd_color   	= array();

		foreach ($kpi_ls as $row) {
			$c_target = $this->rkk_model3->count_target_month($row->KPIID,$month);
			if ($c_target) {
				$m_target = $this->rkk_model3->get_target_month_row($row->KPIID,$month)->Target;
				$month_target[$row->KPIID] = round($m_target,2);

			} else {
				$month_target[$row->KPIID] = '-';
				$month_persen[$row->KPIID] = '-';
				$month_pc[$row->KPIID]     = '-';
			}

			$c_achv_detail = $this->achv_biz_model->count_detail($achv_head->RKKAchievementID,$row->KPIID);
			if ($c_achv_detail > 0) {
				$achv_kpi = $this->achv_biz_model->get_detail_kpi_row($achv_head->RKKAchievementID,$row->KPIID);
				$m_achv 	= $achv_kpi->Achievement;
				$month_achv[$row->KPIID] = round($m_achv,2);
				
				if ($c_target) {
					
					if($row->CaraHitung = OPT_VAL_CARA_HITUNG_NORMALIZE)
					{
						if($m_target==0){
                                                        $m_persen = $achv_kpi->Achievement;
                                                } else {
                                                        $m_persen = $achv_kpi->Achievement / $m_target * 100;
                                                }
					}else{
						if ($m_target==0 && $achv_kpi->Achievement == 0) {
							$m_persen = 100;
						}elseif($m_target==0 && $achv_kpi->Achievement != 0){
							$m_persen = $achv_kpi->Achievement;
						} else {
							$m_persen = $m_achv / $m_target * 100;
						}
					}

					$m_pc     = $this->achv_biz_model->get_tpc_score_row($row->PCFormulaID,$m_persen,$begin,$end)->PCFormulaScore;
					$month_persen[$row->KPIID] = round($m_persen,2);
					$month_pc[$row->KPIID]     = $m_pc;
					$month_color[$row->KPIID]  = $this->achv_biz_model->get_tpc_color_row($m_pc)->Color;
				} 

				if (is_null($achv_kpi->path_evidence)) {
					$file     = './achievement/'.$period->Tahun.'/'.$month.'/'. $achv_kpi->RKKAchievementDetailID.'.zip'; 
				} else {
					$file = $achv_kpi->path_evidence;
					
				}

				if (file_exists($file)) {
					$month_evid[$row->KPIID] = substr($file, 2); 
				} 
				
			} else {
				$month_achv[$row->KPIID]   = '-';
				$month_persen[$row->KPIID] = '-';
				$month_pc[$row->KPIID]     = '-';
			}

			$y_target = $this->rkk_model3->calc_target_ytd_value($row->KPIID,$month);
			$y_achv   = $this->achv_biz_model->calc_ytd($row->KPIID,$month);
			if ($y_target != '-' && $y_achv !='-') {

				if($row->CaraHitung = OPT_VAL_CARA_HITUNG_NORMALIZE)
                                {
                                	if($y_target==0){
                                        	$y_persen = $y_achv;
                                        } else {
                                           	$y_persen = $y_achv / $y_target * 100;
                                        }
                                }else{
					if ($y_target == 0 && $y_achv == 0) {
						$y_persen = 100;
					}elseif($y_target==0 && $y_achv != 0){
                                       		$y_persen = $y_achv;
					} else {
						$y_persen = $y_achv / $y_target * 100;
					}
				}

				$y_pc = $this->achv_biz_model->get_tpc_score_row($row->PCFormulaID,$y_persen,$begin,$end)->PCFormulaScore;
				$ytd_color[$row->KPIID]  = $this->achv_biz_model->get_tpc_color_row($y_pc)->Color;

			} else {
				$y_persen = '-';
				$y_pc     = '-';
			}

			if ($y_target !='-') {
				$ytd_target[$row->KPIID] = round($y_target,2);
			} else {
				$ytd_target[$row->KPIID] = $y_target;
			}

			if ($y_achv !='-') {
				$ytd_achv[$row->KPIID] = round($y_achv,2);
			} else {
				$ytd_achv[$row->KPIID] = $y_achv;
			}

			if ($y_persen !='-') {
				$ytd_persen[$row->KPIID] = round($y_persen,2);
			} else {
				$ytd_persen[$row->KPIID] = $y_persen;
			}

			$ytd_pc[$row->KPIID]     = $y_pc;

			unset($m_target);
			unset($m_achv);
			unset($m_persen);
			unset($m_pc);
			unset($y_target);
			unset($y_achv);
			unset($y_persen);
			unset($y_pc);
		}

		if (count($kpi_ls)) {
			$data['kpi_ls']       = $kpi_ls;
			$data['cur_month']    = $month;
			$data['month_target'] = $month_target;
			$data['month_achv']   = $month_achv;
			$data['month_evid']   = $month_evid;
			$data['month_persen'] = $month_persen;
			$data['month_pc']     = $month_pc;
			$data['month_color']  = $month_color;
			$data['ytd_target']   = $ytd_target;
			$data['ytd_achv']     = $ytd_achv;
			$data['ytd_persen']   = $ytd_persen;
			$data['ytd_pc']       = $ytd_pc;
			$data['ytd_color']    = $ytd_color;
			if ($c_achv_head>0 ) {
				$data['link_input'] = 'performance/achievement/input/'.$achv_head->RKKAchievementID.'/';

			}

			$data['link_detail']  = 'objective/rkk/detail_kpi/';
			$data['link_history'] = 'performance/achievement/history/';
			$this->load->view('performance/achv/kpi_list', $data, FALSE);
			
		} else {
			$data['notif_type'] = '';
			$data['notif_text'] = 'This Objective not have KPI';
			$this->load->view('template/notif_view', $data, FALSE);
		}
	}

	public function save_notes()
	{
		$achv_id = $this->input->post('achv_id');
		$notes   = str_replace("'", '&#39;', $this->input->post('notes'));
		$this->achv_biz_model->edit_header_note($achv_id,$notes);
	}

	public function save_summary($RKKAchievementID=0)
	{
		$achv_id = $this->input->post('achv_id'); 
		$summary = str_replace("'", '&#39;', $this->input->post('summary'));
		$this->achv_biz_model->edit_header_summ($achv_id,$summary);
	}

	public function input($achv_id, $kpi_id)
	{
		$head     = $this->achv_biz_model->get_header_row($achv_id); 
		$kpi      = $this->rkk_model3->get_kpi_row($kpi_id);

		$month    = $head->Month;
		$c_target = $this->rkk_model3->count_target_month($kpi_id,$month);

		// $today     = date('Y-m-d');
		$today     = date('Y-m-d', mktime(0,0,0,$month,1,substr($kpi->KPI_BeginDate, 0,4)));
		$c_kpi_rel = $this->rkk_model3->count_kpi_rel_AB($kpi_id,$today,$today);
		
		if ($c_kpi_rel>0) {
			$data['btn_calc'] = TRUE;
		} else {
			$data['btn_calc'] = FALSE;
		}

		if ($c_target) {
			$target = $this->rkk_model3->get_target_month_row($kpi_id,$month)->Target;
			$target = round($target,2);
		} else {
			$target = '-';
		}

		$c_achv = $this->achv_biz_model->count_detail($achv_id,$kpi_id);
		if ($c_achv) {
			$achv = $this->achv_biz_model->get_detail_kpi_row($achv_id,$kpi_id);
			
			if (is_null($achv->Achievement)) {
				$data['achv'] = '';
				
			} else {
				$data['achv'] = round($achv->Achievement,2);
				
			}

			$data['skip'] = $achv->isSkip;
			$data['evid'] = '';

			if (is_null($achv->note)) {
				$data['note'] = '';
				
			} else {
				$data['note'] = $achv->note;

			}
			$hidden = array(
				'achv_id'        => $achv_id,
				'achv_detail_id' => $achv->RKKAchievementDetailID, 
				'kpi_id'         => $kpi_id 
			);
			$data['process'] = 'performance/achievement/edit_process';
			$data['disable'] = FALSE;
		} else {
			# KPI - KPI bawahan yang berhubungan
			$temp_achv = 0;
			$hidden = array(
				'achv_id' => $achv_id, 
				'kpi_id' => $kpi_id 
			);

			if ($c_kpi_rel > 0 ) {
				$kpi_rel   = $this->rkk_model3->get_kpi_rel_AB_list($kpi_id,$today,$today);

				$temp_val  = array();
				$temp_prop = array();
				$i         = 0;
				$flag = FALSE;
				foreach ($kpi_rel as $row) {
					$kpi_B = $this->rkk_model3->get_kpi_row($row->KPIID);
					$c_head = $this->achv_biz_model->count_header_month($kpi_B->RKKID,$month,array(3));
					if ($c_head) {
						$head_B_id = $this->achv_biz_model->get_header_month_row($kpi_B->RKKID,$month,array(3))->RKKAchievementID;
						$c_detail = $this->achv_biz_model->count_detail($head_B_id ,$row->KPIID);
						if ($c_detail && $row->ref_id = $kpi->ref_id) {
							$achv_B = $this->achv_biz_model->get_detail_kpi_row($head_B_id,$row->KPIID)->Achievement;
							if ( ! is_null($achv_B)) {
								$temp_val[$i] = $achv_B;
								$temp_prop[$i] = $row->ref_weight;
								$i++; 
								$flag = TRUE;
							}
						}
					} else {
						$flag = FALSE;
						break;
					}
				}
				if ($flag == TRUE) {
					switch ($kpi->ref_id) {
						case 1: //akumulasi
							$temp_achv = array_sum($temp_val);
							break;
						case 2: //rata rata
							if (count($temp_val)) {
								$temp_achv = array_sum($temp_val) / count($temp_val);
							} 
							break;
						case 3: //proposional
							$max = ($i-1);
							
							for ($i=0; $i <= $max ; $i++) { 
								$temp_achv += $temp_val[$i] * $temp_prop[$i];
							}
							$temp_achv = $temp_achv / array_sum($temp_prop);
							break;
					}
					$data['disable'] = FALSE;
				} else {
					$data['disable'] = TRUE;

				}
			} else {
				$data['disable'] = FALSE;
			}
			$data['achv'] = round($temp_achv,2);
			$data['skip'] = FALSE;
			$data['evid'] = '';
			$data['note'] = '';
			
			$data['process'] = 'performance/achievement/add_process';
		}
		
		$data['kpi']    = $kpi;
		$data['month']  = date("M", mktime(0, 0, 0, $month, 1, 2000));
		$data['target'] = $target;
		$data['hidden'] = $hidden;
		$this->load->view('performance/achv/input_form', $data, FALSE);
	}

	public function recalc_achv()
	{
		$kpi_id 	 = $this->input->post('kpi_id');
		$today     = date('Y-m-d');
		$kpi_rel   = $this->rkk_model3->get_kpi_rel_AB_list($kpi_id,$today,$today);

		$temp_val  = array();
		$temp_prop = array();
		$i         = 0;

		foreach ($kpi_rel as $row) {
			$kpi_B  = $this->rkk_model3->get_kpi_row($row->KPIID);
			$c_head = $this->achv_biz_model->count_header_month($kpi_B->RKKID,$month,'approve');
			if ($c_head) {
				$head_B_id = $this->achv_biz_model->get_header_month_row($kpi_B->RKKID,$month,'approve')->RKKAchievementID;
				$c_detail  = $this->achv_biz_model->count_detail($head_B_id ,$row->KPIID);
				if ($c_detail && $row->ref_id = $kpi->ref_id) {
					$achv_B = $this->achv_biz_model->get_detail_kpi_row($head_B_id,$row->KPIID)->Achievement;
					if ( ! is_null($achv_B)) {
						$temp_val[$i]  = $achv_B;
						$temp_prop[$i] = $row->ref_weight;
						$i++; 
					}
				}
			}
		}
		$temp_achv = 0;
		switch ($kpi->ref_id) {
			case 1: //akumulasi
				$temp_achv = array_sum($temp_val);
				break;
			case 2: //rata rata
				if (count($temp_val)) {
					$temp_achv = array_sum($temp_val) / count($temp_val);
				} 
				break;
			case 3: //proposional
				$max = ($i-1);
				
				for ($i=0; $i <= $max ; $i++) { 
					$temp_achv += $temp_val[$i] * $temp_prop[$i];
				}
				$temp_achv = $temp_achv / array_sum($temp_prop);
				break;
		}
		return round($temp_achv,2);
	}

	public function add_process()
	{
		$achv_id = $this->input->post('achv_id');
		$kpi_id  = $this->input->post('kpi_id');
		$achv    = $this->input->post('nm_achv');
		$skip    = $this->input->post('chk_skip');
		$note    = str_replace("'", '&#39;', $this->input->post('txt_note'));
		$kpi     = $this->rkk_model3->get_kpi_row($kpi_id);
		$head    = $this->achv_biz_model->get_header_row($achv_id); 

		if ($skip) {
			$c_target = $this->rkk_model3->count_target_month($kpi_id,$head->Month); 
			if ($c_target) {
				$target  = $this->rkk_model3->get_target_month_row($kpi_id,$head->Month)->Target;
				$formula = $this->general_model-> get_PCFormula_row($kpi->PCFormulaID);
				switch ($formula->Operator) {
					case 'M': // Kali
						$achv = $formula->SkipConstancy * $target;
						break;
					case 'A': // Tambah
						$achv =  $target + $formula->SkipConstancy;
							
						break;
					case 'S': // Kurang
						$achv =  $target - $formula->SkipConstancy;

						break;
					case 'D': // Bagi
						$achv = $target / $formula->SkipConstancy ;

						break;
					default: //default di kali
						$achv = $formula->SkipConstancy * $target;
						
						break;
				}
			
			} else {
				$achv = 'NULL';
			}
		} else {
			$skip = 0;
		} 

		$this->achv_biz_model->add_detail($achv_id,$kpi_id,$achv,$skip,$note);
		$achv_detail_id = $this->achv_biz_model->get_detail_kpi_row($achv_id, $kpi_id)->RKKAchievementDetailID;
		$evid = 'fl_evidence';
		$path ='./achievement/'.substr($kpi->KPI_BeginDate, 0,4);
		
		if ( ! file_exists($path) ){
      $create = mkdir($path, 0777);
    }

    $path .='/'.$head->Month.'/';
    
    if ( ! file_exists($path) ){
      $create = mkdir($path, 0777);
    }

		$config['upload_path']   = $path;
		$config['allowed_types'] = 'zip';
		$config['max_size']      = '500';
		$config['file_name']     = $achv_detail_id.'_'.date('Ymd_Hi').'.zip';
		$config['overwrite']     = TRUE;

		$this->load->library('upload', $config);
		if ( ! $this->upload->do_upload($evid)) {
			$text = str_replace('<p>', '', $this->upload->display_errors());
			$text = str_replace('</p>', '',$text);
			if ($text == 'You did not select a file to upload.') {
				$data['notif_text'] = 'Success Add Achievement';
				$data['notif_type'] = 'alert-success';

			} else {
				$data['notif_text'] = $text;
				$data['notif_type'] = 'alert-error';
			}

		} else {
			$data['notif_text'] = 'Success Add Achievement & Evidence' ;
			$data['notif_type'] = 'alert-success';
			$this->achv_biz_model->update_evid($achv_detail_id,$path.$config['file_name']);
		}

		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}

	public function edit_process()
	{
		$achv_detail_id = $this->input->post('achv_detail_id');
		$achv           = $this->input->post('nm_achv');
		$skip           = $this->input->post('chk_skip');
		$note           = str_replace("'", '&#39;', $this->input->post('txt_note'));
		$kpi_id         = $this->input->post('kpi_id');
		$kpi            = $this->rkk_model3->get_kpi_row($kpi_id);
		$achv_id        = $this->input->post('achv_id');
		$head           = $this->achv_biz_model->get_header_row($achv_id);
		if ($skip) {
			$c_target = $this->rkk_model3->count_target_month($kpi_id,$head->Month); 
			if ($c_target) {
				$target  = $this->rkk_model3->get_target_month_row($kpi_id,$head->Month)->Target;
				$formula = $this->general_model-> get_PCFormula_row($kpi->PCFormulaID);
				switch ($formula->Operator) {
					case 'M': // Kali
						$achv = $formula->SkipConstancy * $target;
						break;
					case 'A': // Tambah
						$achv =  $target + $formula->SkipConstancy;
							
						break;
					case 'S': // Kurang
						$achv =  $target - $formula->SkipConstancy;

						break;
					case 'D': // Bagi
						$achv = $target / $formula->SkipConstancy ;

						break;
					default: //default di kali
						$achv = $formula->SkipConstancy * $target;
						
						break;
				}
			
			} else {
				$achv = 'NULL';
			}
			$this->achv_biz_model->edit_detail($achv_detail_id,$achv,$skip,$note);

		} else {
			$skip = 0;
			if ($achv != '') {
				$this->achv_biz_model->edit_detail($achv_detail_id,$achv,$skip,$note);
			}
		} 

		

		$achv_detail_id = $this->achv_biz_model->get_detail_kpi_row($achv_id, $kpi_id)->RKKAchievementDetailID;
		
		$evid = 'fl_evidence';
		$path ='./achievement/'.substr($kpi->KPI_BeginDate, 0,4);
		
		if ( ! file_exists($path) ){
      $create = mkdir($path, 0777);
    }

    $path .='/'.$head->Month.'/';
    
    if ( ! file_exists($path) ){
      $create = mkdir($path, 0777);
    }

    $config['upload_path'] = $path;
		$config['allowed_types'] = 'zip';
		$config['max_size']	= '500';
		$config['file_name']     = $achv_detail_id.'_'.date('Ymd_Hi').'.zip';
		$config['overwrite']	= TRUE;

		$this->load->library('upload', $config);
		if ( ! $this->upload->do_upload($evid)) {
			$text = str_replace('<p>', '', $this->upload->display_errors());
			$text = str_replace('</p>', '',$text);
			if ($text == 'You did not select a file to upload.') {
				$data['notif_text'] = 'Success Update Achievement & Evidence';
				$data['notif_type'] = 'alert-success';
			} else {
				$data['notif_text'] = $text;
				$data['notif_type'] ='alert-error';
			}

		} else if($skip == 0 && $achv == '') {
			$data['notif_text'] = 'Error Update Achievement & Evidence' ;
			$data['notif_type'] = 'alert-error';
		} else {
			$data['notif_text'] = 'Success Update Achievement & Evidence' ;
			$data['notif_type'] = 'alert-success';
			$this->achv_biz_model->update_evid($achv_detail_id,$path.$config['file_name']);
			
		}

		$this->load->view('template/top_popup_1_view');
		$this->load->view('template/notif_view',$data);
		$this->load->view('template/bottom_popup_1_view');
	}

	public function history($kpi_id=0,$cur_month=0)
	{
		$kpi       = $this->rkk_model3->get_kpi_row($kpi_id);
		$target_ls = array();
		$achv_ls   = array();
		for ($month=1; $month <=12 ; $month++) { 
			$c_target = $this->rkk_model3->count_target_month($kpi_id,$month);
			if ($c_target) {
				$target_ls[$month] = round($this->rkk_model3->get_target_month_row($kpi_id,$month)->Target,2);
			} else {
				$target_ls[$month] = '-';
			}

			$c_achv = $this->achv_biz_model->count_detail_kpi_month($kpi_id,$month);
			if ($c_achv) {
				$achv_ls[$month] = round($this->achv_biz_model->get_detail_kpi_month_row($kpi_id,$month)->Achievement,2);
			} else {
				$achv_ls[$month] = '-';

			}

		}

		$data['kpi']       = $kpi;
		$data['cur_month'] = $cur_month;
		$data['achv_ls']   = $achv_ls;
		$data['target_ls'] = $target_ls;
		$this->load->view('performance/achv/history_view', $data, FALSE);
	}

	private function calc_tpc($rkk_id,$month)
	{
		$c_achv = $this->achv_biz_model->count_header_month($rkk_id,$month,'all');
		if ($c_achv) {
			$head 		= $this->achv_biz_model->get_header_month_row($rkk_id,$month,'all');
			$rkk      = $this->rkk_model3->get_rkk_row($head->RKKID);
			$begin    = date('Y-m-d H:i:s', mktime(0, 0, 0, $head->Month, 1, substr($rkk->BeginDate, 0,4)));
			$end      = date('Y-m-t H:i:s', mktime(23, 59, 59, $head->Month, 1, substr($rkk->EndDate, 0,4)));
			$persp_ls = $this->general_model->get_Perspective_List($begin, $end);

			$cur_val = 0;
			$ytd_val = 0;
			$gt_cur_weight = 0;
			$gt_ytd_weight = 0;
			foreach ($persp_ls as $row) {
				$cur_pc = $this->achv_biz_model->calc_cur_tpc_persp($head->RKKAchievementID,$row->PerspectiveID);
				$ytd_pc = $this->achv_biz_model->calc_ytd_tpc_persp($head->RKKAchievementID,$row->PerspectiveID);
				$ytd_weight  = $this->rkk_model3->sum_weight_ytd_persp($head->RKKID,$row->PerspectiveID,$head->Month,$begin,$end);
				$cur_weight  = $this->rkk_model3->sum_weight_cur_persp($head->RKKID,$row->PerspectiveID,$head->Month,$begin,$end);

				if ($cur_pc == '') {
					$cur_pc = 0;
				}

				if ($ytd_pc == '') {
					$ytd_pc = 0;
				}

				if ($cur_weight  == '') {
					$cur_weight  = 0;
				}

				if ($ytd_weight  == '') {
					$ytd_weight  = 0;
				}

				$gt_cur_weight += $cur_weight;
				$gt_ytd_weight += $ytd_weight;
				$cur_val       += $cur_pc * $cur_weight;
				$ytd_val       += $ytd_pc * $ytd_weight;
			}
			if ($gt_cur_weight) {
				$gt_cur_val    = round($cur_val / $gt_cur_weight,2);
			} else {
				$gt_cur_val    = 0;
			}

			if ($gt_ytd_weight) {
				$gt_ytd_val    = round($ytd_val / $gt_ytd_weight,2);
			} else {
				$gt_ytd_val    = 0;
			}
			$this->achv_biz_model->edit_header_tpc($head->RKKAchievementID,$gt_cur_val,$gt_ytd_val);
			$result['cur'] = $gt_cur_val;
			$result['ytd'] = $gt_ytd_val;

			return $result;
		}
	}
	
}
