<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class pk_individu_report extends Controller {
	function __construct(){
		parent::__construct(); 
		if(!$this->session->userdata('loginFlag')){
			redirect('account/login');
		}

		$this->load->library('email');
		$this->load->model('rkk_model3');
		$this->load->model('achv_biz_model');
		$this->load->model('general_model');
		$this->load->model('org_model');
		$this->load->model('om_model');
		$this->load->model('account_model');
		$this->load->model('report_model');
		$this->load->model('adjust_model');
		$this->load->model('behaviour_model');
	}
	

	public function index()
	{
		$period       = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$user_id      = $this->session->userdata('userID');
		$nik          = $this->session->userdata('NIK');
		$user_dtl     = $this->account_model->get_User_byNIK($nik);
		
		$filter_start = $period->BeginDate;
		$filter_end   = $period->EndDate;
		$data['filter_start'] = substr($filter_start, 0,10);
		$data['filter_end']   = substr($filter_end, 0,10);
	

		$data['period']   = $period;

		$data['user_dtl'] = $user_dtl;

		$data['post_ls_SAP']      = $this->account_model->get_Holder_list($nik,1,$filter_start,$filter_end);
		$data['post_ls_nonSAP']   = $this->account_model->get_Holder_list($nik,0,$filter_start,$filter_end);
		$data['assign_ls_SAP']    = $this->account_model->get_Assignment_list($nik,1,$filter_start,$filter_end);
		$data['assign_ls_nonSAP'] = $this->account_model->get_Assignment_list($nik,0,$filter_start,$filter_end);

		$this->load->view('report/pk_report_individu_view', $data);
	}

	public function show_subordinate()
	{
		$sess_nik     = $this->session->userdata("NIK");
		$nik          = $this->input->post('nik');
		$holder       = $this->input->post('holder');
		$filter_start = $this->input->post('start');
		$filter_end   = $this->input->post('end');
		$filter_start .= ' 00:00:00.000';
		$filter_end   .= ' 23:59:59.999';

		$Period = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		

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

		$sub_ls       = $this->org_model->get_directSubordinate_list($is_sap,$post_id,$filter_start,$filter_end);
		if (count($sub_ls)) {
			$count_rkk = $this->rkk_model3->count_rkk_holder($nik,$post_id,$is_sap, $filter_start, $filter_end,'approve');
			$base_link = 'report/pk_individu_report/pk_view_subordinate/';
			$link = array();
			if ($count_rkk > 0) {
				$rkk = $this->rkk_model3->get_rkk_holder_last($nik,$post_id,$is_sap, $filter_start, $filter_end,'approve');
				$begin = $this->input->post('start');
				$end = $this->input->post('end');

				foreach ($sub_ls as $sub) {
					$key = $sub->NIK.'|'.$sub->isSAP.'|'.$sub->PositionID;
					$param = $sub->NIK.'/'.$sub->PositionID.'/'.$sub->isSAP.'/'.$begin.'/'.$end.'/'.$Period->Tahun;
					$link[$key] = $base_link.$param;
				}
				$data['link'] = $link;
			}
			$data['sub_ls']   = $sub_ls;


			$this->load->view('report/pk_subordinate_view', $data, FALSE);
		}
	}

	public function pk_report_self()
	{
		$link_detail = 'report/pk_individu_report/';
		$data['link_detail']=$link_detail;
		$is_sap		  =$this->session->userdata("isSAP");
		$sess_nik     = $this->session->userdata("NIK");
		$holder       = $this->input->post('holder');
		$filter_start = $this->input->post('start');
		$filter_end   = $this->input->post('end');
		$count_rkk = $this->adjust_model->count_result_by_nik($sess_nik,$filter_start, $filter_end);
		if($count_rkk!=0)
		{
			$data['list_report']=$this->report_model->get_data_individu_report($sess_nik, $is_sap, $filter_start,$filter_end);
			
			$this->load->view('report/pk_report_self_view', $data);
		}
	}

	public function pk_report_detail_self($NIK, $begin_date='', $end_date='', $period, $position_id)
	{
		$periode       = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$data['NIK'] = $NIK;
		$is_sap		  =$this->session->userdata("isSAP");
		$data_holder=$this->om_model->get_hold_byNik_last($is_sap,$NIK,$begin_date,$end_date);
		$org_name = $this->om_model->get_org_row($is_sap,$data_holder->org_id,$begin_date,$end_date)->OrganizationName;
		$org_id = $this->om_model->get_org_row($is_sap,$data_holder->org_id,$begin_date,$end_date)->OrganizationID;
		$data['data_individu']=$data_holder;
		$data['org_name']=$org_name;
		$data['periode']=$period;

		$RKKID = $this->rkk_model3->get_rkk_byNIKPosition($NIK, $position_id, $begin_date,$end_date)->RKKID; 
		$chief_nik=$this->rkk_model3->get_rkk_rel_last($RKKID, $begin_date, $end_date)->chief_nik;
		$nama_chief = $this->account_model->get_User_nik($chief_nik)->Fullname;
		$data['chief_nik']= $chief_nik;
		$data['nama_chief']= $nama_chief;
		$sess_nik = $this->session->userdata("NIK");
		$data['export_xls'] = 'report/pk_unit_report/export_xls/';
		

		/**
		 * Controller buat aspek kinerja
		 */
		
		$c_rkk = $this->rkk_model3->count_rkk_nik($NIK,$periode->BeginDate, $periode->EndDate,array(3,4,5));
		$post_ls = $this->report_model->get_data_individu_report($sess_nik, 1, $periode->BeginDate,$periode->EndDate);
		$month=12;


		$post_ls_clear = array();
		foreach ($post_ls as $post) {
			
			$self_rkk    = $this->rkk_model3->get_rkk_holder_last($NIK,$post->PositionID,$is_sap, $periode->BeginDate,$periode->EndDate);
			if (count($self_rkk)) {
				$post_ls_clear[] = $post; 
				$rkk_begin[$post->PositionID]   = $self_rkk->BeginDate;
				$rkk_end[$post->PositionID]     = $self_rkk->EndDate;
				$kpi_ls                         = $this->rkk_model3->get_kpi_list($self_rkk->RKKID,$post->Holder_BeginDate,$post->Holder_EndDate);
				$kpi_list[$post->PositionID]    = $kpi_ls;
				$total_bobot[$post->PositionID] = 0;
				$total_nxb[$post->PositionID]   = 0;
				

				foreach ($kpi_ls as $kpi) {
					$kpi_target[$kpi->KPIID] = $this->rkk_model3->calc_target_ytd_value($kpi->KPIID,$month); 
					$kpi_achv[$kpi->KPIID] = $this->achv_biz_model->calc_ytd($kpi->KPIID,$month);
					
					if($kpi->CaraHitung = OPT_VAL_CARA_HITUNG_NORMALIZE)
                                        {
                                                if($kpi_target[$kpi->KPIID]==0){
                                                        $temp_kpi = $kpi_achv[$kpi->KPIID];
                                                } else {
                                                        $temp_kpi = $kpi_achv[$kpi->KPIID] / $kpi_target[$kpi->KPIID] * 100;
                                                }
                                        }else{

						if($kpi_target[$kpi->KPIID]==0){
							$temp_kpi = 0;								
						} else {
							$temp_kpi = $kpi_achv[$kpi->KPIID] / $kpi_target[$kpi->KPIID] * 100;
						}
					}
					$pc[$kpi->KPIID] = $this->achv_biz_model->get_tpc_score_row($kpi->PCFormulaID,$temp_kpi,$periode->BeginDate,$periode->EndDate)->PCFormulaScore;         
					$nxb[$kpi->KPIID] = $pc[$kpi->KPIID] * $kpi->Bobot;
					$total_bobot[$post->PositionID] += $kpi->Bobot;
					$total_nxb[$post->PositionID] += ($pc[$kpi->KPIID] * $kpi->Bobot);
					unset($temp_kpi);        
				}
				if ($total_bobot[$post->PositionID] > 0) {
					$post_achv[$post->PositionID] = round(($total_nxb[$post->PositionID] / $total_bobot[$post->PositionID] ),2);

				} else {
					$post_achv[$post->PositionID] = 0;
				}
			} 
			
		}
		
		$data['post_ls'] = $post_ls_clear;
		$data['kpi_list']    = $kpi_list;
		$data['rkk_begin']   = $rkk_begin;
		$data['rkk_end']     = $rkk_end;
		$data['nxb']         = $nxb;
		$data['pc']          = $pc;
		$data['kpi_achv']    = $kpi_achv;
		$data['kpi_target']  = $kpi_target;
		$data['total_bobot'] = $total_bobot;
		$data['total_nxb']   = $total_nxb;
		$data['post_achv']   = $post_achv;

		/**
		 * Controller report buat aspek behaviour kompas
		 * Pertama cek di table bhv_t_header
		 */
		
		$trans_bhv = $this->behaviour_model->get_performance_id_by_year($period, $NIK);
		$data['aspect_setting'] = $trans_bhv;
		if(isset($trans_bhv))
		{
			$temp_detail_aspect_setting_array=array();
			
			foreach ($trans_bhv as $key) {

				$data_sub_aspect_setting = $this->behaviour_model->get_aspect_setting_data_list_by_id($key->aspect_setting_id);
				$temp_detail_aspect_setting_array[$key->aspect_setting_id] = $data_sub_aspect_setting;
				foreach ($data_sub_aspect_setting as $row) {
					$data_behaviour[$row->behaviour_group_id] = $this->behaviour_model->get_behaviour_group_behaviour_by_id($row->behaviour_group_id, $begin_date,$end_date);
					$data['data_behaviour']=$data_behaviour;
					$data_behaviour_scala[$row->behaviour_group_id] = $this->behaviour_model->get_behaviour_group_scala_by_id($row->behaviour_group_id, $begin_date,$end_date);
					$data['data_behaviour_scala']=$data_behaviour_scala;
				}
			}

			$data['detail_aspect_setting']=$temp_detail_aspect_setting_array;
		}

		$old_answer = $this->behaviour_model->get_answer_performance_by_year($period,$NIK); 
		$answer = array();

		foreach ($old_answer as $value) 
		{
			$answer[]=(int) $value->achievement;
		}
		$data['answer']=$answer;

		/**
		 * Controller reprot buat project assignment
		 * Munculkan nilai project assignment untuk diri sendiri
		*/
		$get_project_assignment = $this->report_model->get_project_list($NIK,$periode->BeginDate,$periode->EndDate);
		$data['project_assign'] = $get_project_assignment;


		/**
		 * Controller report buat nilai akhir
		 * Munculkan nilai akhir untuk diri sendiri
		*/

		$get_adjust_model = $this->adjust_model->get_result_row($NIK,$periode->BeginDate,$periode->EndDate);
		if (count($get_adjust_model)) {
			if($get_adjust_model->after_value==NULL)
			{
				$data['adjustment_data'] = array();
				$data['process']='';
				$data['adjust_color_text'] = '';
			}
			else{
				$data['adjustment_data'] = $get_adjust_model;
				$data['adjust_color_text'] = $this->report_model->get_color_tpc_by_value($get_adjust_model->after_value);
			}

		} else {
			$data['adjustment_data'] = array();
			$data['process']='';
			$data['adjust_color_text'] = '';
		}

		
		/*if($this->input->post('btn_submit_print')=="Print")
		{
			header("Content-Type: " ."application/vnd.ms-excel");
			header( "Content-disposition: attachment; filename=spreadsheet.xls" );
			
			//do print to excel
			
		}
*/
		$data['process']='report/pk_individu_report/agree_report/'.$NIK.'/'.$period.'/'.$chief_nik;
		$data['total_agree'] =  $this->report_model->count_report_agree($NIK, $period)->total_agree;

		$this->load->view('template/top_1_view');
		$this->load->view('report/pk_report_detail_self_view',$data);
		$this->load->view('template/bottom_1_view');
		$this->load->view('report/pk_report_detail_self_view_js',$data);

	}

	public function agree_report($nik,$periode, $chief_nik)
	{
		/**
		 * Get NIK dan periode
		 */
		
		$this->report_model->process_agree($nik,$periode);
		$this->session->set_flashdata('notif_text',"You agree with this report.");
		$this->session->set_flashdata('notif_type','alert-success');


		$nama_bawahan = $this->account_model->get_User_byNIK($nik)->Fullname;
		$nama_atasan = $this->account_model->get_User_byNIK($chief_nik)->Fullname;
		$mail_atasan = $this->account_model->get_User_byNIK($chief_nik)->Email;

		/**
		 * Send email
		 */
		$config['smtp_host']="10.10.55.10";
		$config['smtp_user']="pms@chr.kompasgramedia.com";
		$config['smtp_pass']="Abc123"; 
		$config['mailtype']='html';
		$config['priority']=1;
		$config['protocol']='smtp';
		$this->email->initialize($config);
		$this->email->from('pms@chr.kompasgramedia.com', '[PMS Online] DO NOT REPLY THIS EMAIL!');
		$this->email->to($mail_atasan);
		$this->email->subject('[PMS Online] IDP Submitted');
		$this->email->message("<h1>PMS Online</h1>
			PK Individu has been agree with ".$nama_bawahan."
			If you're not ".$nama_atasan.",please ignore this email. <br>Thank you,<br><br>PMS Online");

		if($this->email->send()){
			$this->session->set_flashdata('notif_text',$succesNote);
			$this->session->set_flashdata('notif_type','alert-success');
//			echo "berhasil";
		}else{
			$this->session->set_flashdata('notif',"Email has not sent");
			$this->session->set_flashdata('notif_type',"alert-danger");
//			echo "gagal";
		}



	
		redirect('report/pk_individu_report');


	}


	public function pk_view_subordinate($NIK, $position_id, $is_sap, $begin_date='', $end_date='', $period)
	{
		$periode       = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$begin_date  = $periode->BeginDate;
		$end_date  = $periode->EndDate;
		$data['NIK'] = $NIK;
		$data_holder=$this->om_model->get_hold_byNik_last($is_sap,$NIK,$begin_date,$end_date);
		$org_name = $this->om_model->get_org_row($is_sap,$data_holder->org_id,$begin_date,$end_date)->OrganizationName;
		$org_id = $this->om_model->get_org_row($is_sap,$data_holder->org_id,$begin_date,$end_date)->OrganizationID;
		$data['data_individu']=$data_holder;
		$data['org_name']=$org_name;
		$data['periode']=$period;

		$RKKID = $this->rkk_model3->get_rkk_byNIKPosition($NIK, $position_id, $begin_date,$end_date)->RKKID; 

		$chief_nik=$this->rkk_model3->get_rkk_rel_last($RKKID, $begin_date, $end_date)->chief_nik;
		$nama_chief = $this->account_model->get_User_nik($chief_nik)->Fullname;
		$data['chief_nik']= $chief_nik;
		$data['nama_chief']= $nama_chief;
		$sess_nik = $NIK;
		$data['export_xls'] = 'report/pk_unit_report/export_xls/';
		
		/**
		 * Controller buat aspek kinerja
		 */
		
		$c_rkk = $this->rkk_model3->count_rkk_nik($NIK,$periode->BeginDate, $periode->EndDate,array(3,4,5));
		$post_ls = $this->report_model->get_data_individu_report($sess_nik, 1, $periode->BeginDate,$periode->EndDate);
		
		$month=12;
		$post_ls_clear = array();
		foreach ($post_ls as $post) {
			
			$self_rkk    = $this->rkk_model3->get_rkk_holder_last($NIK,$post->PositionID,$is_sap, $periode->BeginDate,$periode->EndDate);
			if (count($self_rkk)) {
				$post_ls_clear[] = $post; 
				$rkk_begin[$post->PositionID]   = $self_rkk->BeginDate;
				$rkk_end[$post->PositionID]     = $self_rkk->EndDate;
				$kpi_ls                         = $this->rkk_model3->get_kpi_list($self_rkk->RKKID,$post->Holder_BeginDate,$post->Holder_EndDate);
				$kpi_list[$post->PositionID]    = $kpi_ls;
				$total_bobot[$post->PositionID] = 0;
				$total_nxb[$post->PositionID]   = 0;
				

				foreach ($kpi_ls as $kpi) {
					$kpi_target[$kpi->KPIID] = $this->rkk_model3->calc_target_ytd_value($kpi->KPIID,$month); 
					$kpi_achv[$kpi->KPIID] = $this->achv_biz_model->calc_ytd($kpi->KPIID,$month);
					
					if($kpi->CaraHitung = OPT_VAL_CARA_HITUNG_NORMALIZE)
                                        {
                                                if($kpi_target[$kpi->KPIID]==0){
                                                        $temp_kpi = $kpi_achv[$kpi->KPIID];
                                                } else {
                                                        $temp_kpi = $kpi_achv[$kpi->KPIID] / $kpi_target[$kpi->KPIID] * 100;
                                                }
                                        }else{
					
						if($kpi_target[$kpi->KPIID]==0){
							$temp_kpi = 0;								
						} else {
							$temp_kpi = $kpi_achv[$kpi->KPIID] / $kpi_target[$kpi->KPIID] * 100;
						}
					}
					$pc[$kpi->KPIID] = $this->achv_biz_model->get_tpc_score_row($kpi->PCFormulaID,$temp_kpi,$periode->BeginDate,$periode->EndDate)->PCFormulaScore;         
					$nxb[$kpi->KPIID] = $pc[$kpi->KPIID] * $kpi->Bobot;
					$total_bobot[$post->PositionID] += $kpi->Bobot;
					$total_nxb[$post->PositionID] += ($pc[$kpi->KPIID] * $kpi->Bobot);
					unset($temp_kpi);        
				}
				if ($total_bobot[$post->PositionID] > 0) {
					$post_achv[$post->PositionID] = round(($total_nxb[$post->PositionID] / $total_bobot[$post->PositionID] ),2);

				} else {
					$post_achv[$post->PositionID] = 0;
				}
			} 
			
		}
		$data['post_ls'] = $post_ls_clear;

		$data['kpi_list']    = $kpi_list;
		$data['rkk_begin']   = $rkk_begin;
		$data['rkk_end']     = $rkk_end;
		$data['nxb']         = $nxb;
		$data['pc']          = $pc;
		$data['kpi_achv']    = $kpi_achv;
		$data['kpi_target']  = $kpi_target;
		$data['total_bobot'] = $total_bobot;
		$data['total_nxb']   = $total_nxb;
		$data['post_achv']   = $post_achv;

		/**
		 * Controller report buat aspek behaviour kompas
		 * Pertama cek di table bhv_t_header
		 */
		
		$trans_bhv = $this->behaviour_model->get_performance_id_by_year($period, $NIK);
		$data['aspect_setting'] = $trans_bhv;
		if(isset($trans_bhv))
		{
			$temp_detail_aspect_setting_array=array();
			
			foreach ($trans_bhv as $key) {

				$data_sub_aspect_setting = $this->behaviour_model->get_aspect_setting_data_list_by_id($key->aspect_setting_id);
				$temp_detail_aspect_setting_array[$key->aspect_setting_id] = $data_sub_aspect_setting;
				foreach ($data_sub_aspect_setting as $row) {
					$data_behaviour[$row->behaviour_group_id] = $this->behaviour_model->get_behaviour_group_behaviour_by_id($row->behaviour_group_id, $begin_date,$end_date);
					$data['data_behaviour']=$data_behaviour;
					$data_behaviour_scala[$row->behaviour_group_id] = $this->behaviour_model->get_behaviour_group_scala_by_id($row->behaviour_group_id, $begin_date,$end_date);
					$data['data_behaviour_scala']=$data_behaviour_scala;
				}
			}

			$data['detail_aspect_setting']=$temp_detail_aspect_setting_array;
		}

		$old_answer = $this->behaviour_model->get_answer_performance_by_year($period,$NIK); 
		$answer = array();

		foreach ($old_answer as $value) 
		{
			$answer[]=(int) $value->achievement;
		}
		$data['answer']=$answer;

		/**
		 * Controller reprot buat project assignment
		 * Munculkan nilai project assignment untuk subordinate
		*/
		$get_project_assignment = $this->report_model->get_project_list($NIK,$periode->BeginDate,$periode->EndDate);
		$data['project_assign'] = $get_project_assignment;

		/**
		 * Controller report buat nilai akhir
		 * Munculkan nilai akhir untuk subordinate
		*/
		$get_adjust_model = $this->adjust_model->get_result_row($NIK,$periode->BeginDate,$periode->EndDate);
		if (count($get_adjust_model)) {
			if($get_adjust_model->after_value==NULL)
			{
				$data['adjustment_data'] = array();
				$data['process']='';
				$data['adjust_color_text'] = '';
			}
			else{
				$data['adjustment_data'] = $get_adjust_model;
				$data['process']='report/pk_individu_report/agree_report/'.$NIK.'/'.$period.'/'.$chief_nik;
				$data['adjust_color_text'] = $this->report_model->get_color_tpc_by_value($get_adjust_model->after_value);
			}

		} else {
			$data['adjustment_data'] = array();
			$data['process']='';
			$data['adjust_color_text'] = '';
		}
		$data['total_agree'] =  $this->report_model->count_report_agree($NIK, $period)->total_agree;

		$this->load->view('template/top_1_view');
		$this->load->view('report/pk_report_detail_self_view',$data);
		$this->load->view('template/bottom_1_view');
		$this->load->view('report/pk_report_detail_self_view_js',$data);


	}

	public function export_pdf()
	{
		
	}

}

