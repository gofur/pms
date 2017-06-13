<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Behaviour extends Controller {
	function __construct(){
		parent::__construct();
		if(!$this->session->userdata('loginFlag')){
			redirect('account/login');
		}
		$this->load->library('email');
		$this->load->model('rkk_model');
		$this->load->model('idp_model');
		$this->load->model('report_model');
		$this->load->model('general_model');
		$this->load->model('org_model');
		$this->load->model('account_model');
		$this->load->model('account_model2');
		$this->load->model('behaviour_model');
	}

	function index()
	{
		$data_header['notif_text']=$this->session->flashdata('notif_text');
		$data_header['notif_type']=$this->session->flashdata('notif_type');

		//$Period = $this->behaviour_model->get_ActivePeriode_row();
		$Period = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$data_header['Periode'] = $Period;
		$data_header['Title']   = 'Behaviour';
		$data_header['action']  = 'performance/behaviour/';
		
		//dapatkan holder id
		$Holder =$this->session->userdata('Holder');
		$Holder = $this->input->post('SlcPost');

		$data_header['Holder']=$Holder;
		$this->session->set_userdata('Holder',$Holder);

		$month = $this->session->userdata('slcMonth');
		$month = $this->input->post('slcMonth');
		
		if($month=='')
		{
			$month = date('n');
			if($month==0)
			{
				$month = 12;
			}
		}
		
		if($Holder=='')
		{
			$HolderID = $this->account_model->get_Holder_row_byNIK($this->session->userdata('NIK'),1,$Period->BeginDate,$Period->EndDate)->HolderID;
		}
		else
		{
			$HolderID     =substr($Holder, 2);
		}

		$data_header['month']=$month;
		$this->session->set_userdata('slcMonth',$month);
		
		$userDetail = $this->behaviour_model->get_User_row($this->session->userdata('NIK'));
		$data_header['userDetail']                    = $userDetail;
		$data_header['PositionList_SAP']              =$this->account_model->get_Holder_list($this->session->userdata('NIK'),1,$Period->BeginDate,$Period->EndDate);
		$data_header['PositionList_nonSAP']           = $this->account_model->get_Holder_list($this->session->userdata('NIK'),0,$Period->BeginDate,$Period->EndDate);
		$data_header['PositionAssignmentList_SAP']    = $this->account_model->get_Assignment_list($this->session->userdata('NIK'),1,$Period->BeginDate,$Period->EndDate);
		$data_header['PositionAssignmentList_nonSAP'] = $this->account_model->get_Assignment_list($this->session->userdata('NIK'),0,$Period->BeginDate,$Period->EndDate);
		
		$isSAP        =$this->session->userdata('isSAP');
		$HolderDetail = $this->account_model->get_Holder_row($HolderID,$isSAP);
		
		if (strtotime($Period->BeginDate) >= strtotime($HolderDetail->Holder_BeginDate)) {
			$BeginDate = $Period->BeginDate;
		} else {
			$BeginDate = $HolderDetail->Holder_BeginDate;

		}

		if (strtotime($Period->EndDate) <= strtotime($HolderDetail->Holder_EndDate)) {
			$EndDate = $Period->EndDate;
		} else {
			$EndDate = $HolderDetail->Holder_EndDate;

		}
		$data_header['begda'] = $BeginDate;
		$data_header['endda'] = $EndDate;
		$subordinate = $this->org_model->get_directSubordinate_list($isSAP,$HolderDetail->PositionID,$BeginDate,$EndDate);

		if (count($subordinate))
		{
			$i=0;
			foreach ($subordinate as $row) {
				$subordinate_list[$i]=anchor('performance/behaviour/view_subordinate/'.$row->NIK.'/'.$row->PositionID.'/'.$month,$row->PositionName.' ('.$row->NIK . ' - '. $row->Fullname .')');
				$i++;
			}
			$data_header['subordinate'] = ul($subordinate_list);
			
		}
		else
		{
			$data_header['subordinate'] = '';

		}

		$this->load->view('template/top_1_view');

		//Chek organisasi parent
		
		$data_org =$this->behaviour_model->get_setting($isSAP,$HolderDetail->OrganizationID, $Period->BeginDate,$Period->EndDate);

		if($data_org)
		{
			foreach ($data_org as $row) {
				$organisasi_setting= $row->organization_id;
			}		
			$get_aspect_setting_data_count = $this->behaviour_model->get_aspect_setting_data_count($organisasi_setting,$Period->BeginDate,$Period->EndDate)->total_aspect_setting;
		}
		else{

			$get_aspect_setting_data_count = $this->behaviour_model->get_aspect_setting_data_count($HolderDetail->OrganizationID,$Period->BeginDate,$Period->EndDate)->total_aspect_setting;
		}



		if($get_aspect_setting_data_count!=0)
		{

			//Mendapatkan bulan terkecil
			//$frequency = $this->behaviour_model->get_aspect_setting_data_list($HolderDetail->OrganizationID,$Period->BeginDate,$Period->EndDate);
			$frequency = $this->behaviour_model->get_aspect_setting_data_list($organisasi_setting,$Period->BeginDate,$Period->EndDate);
			$bulan_array=array();
			$bulan_terkecil_1=13;
			foreach ($frequency as $row_1) 
			{
				//Jika ESG layer tidak sesuai dengan data di aspect setting maka behaviour yang sesuai dengan esg layernya
				if (strpos($row_1->esg, $this->session->userdata('esg')) !== false)
				{
					$frequency_bulan_1 = $row_1->frequency;
					//pertama ukur panjang frequency
					$panjang_frequency_1=strlen($frequency_bulan_1);
					//titik koma ada berapa
					$titik_koma_frequency_1=substr_count($frequency_bulan_1,';');
					$total_loop_freq_1 = ($panjang_frequency_1-$titik_koma_frequency_1)/2;
					//$aspect_setting[]=$row_1->aspect_setting_id;
					//loop berdasarkan frequency
					$j=0;
					for($i=1;$i<=$total_loop_freq_1;$i++)
					{
						$frequency_bersih_1=str_replace(';', '', $frequency_bulan_1).'<br>';
						$choose_month_1 = substr($frequency_bersih_1,$j,2);
						$choose_month_int_1 = (int) $choose_month_1;
						//$bulan_array[$row_1->aspect_setting_id][]=$choose_month_1;
						$bulan_array[]=$choose_month_1;
						
						if($choose_month_int_1!='')
						{
							if($bulan_terkecil_1>$choose_month_int_1)
							{
								$bulan_terkecil_1=$choose_month_1;	
							}		
						}
						$j=$j+2;
					}
				}
				else
				{
					if($row_1->esg==0)
					{
						$frequency_bulan_1 = $row_1->frequency;
						//pertama ukur panjang frequency
						$panjang_frequency_1=strlen($frequency_bulan_1);
						//titik koma ada berapa
						$titik_koma_frequency_1=substr_count($frequency_bulan_1,';');
						$total_loop_freq_1 = ($panjang_frequency_1-$titik_koma_frequency_1)/2;
						//$aspect_setting[]=$row_1->aspect_setting_id;
						//loop berdasarkan frequency
						$j=0;
						for($i=1;$i<=$total_loop_freq_1;$i++)
						{
							$frequency_bersih_1=str_replace(';', '', $frequency_bulan_1).'<br>';
							$choose_month_1 = substr($frequency_bersih_1,$j,2);
							$choose_month_int_1 = (int) $choose_month_1;
							//$bulan_array[$row_1->aspect_setting_id][]=$choose_month_1;
							$bulan_array[]=$choose_month_1;
							
							if($choose_month_int_1!='')
							{
								if($bulan_terkecil_1>$choose_month_int_1)
								{
									$bulan_terkecil_1=$choose_month_1;	
								}		
							}
							$j=$j+2;
						}
					}else
					{
					/*	$data['notif_text']='Your layer not have behaviour';
						$data['notif_type']='alert-error';*/
					}
				}
			}	

			//looping array bulan dan di sort
			/*	$distinct_bulan = array_unique($bulan_array);
			sort($distinct_bulan);
			foreach ($distinct_bulan as $key => $val) {
			    echo $val.'<br>';
			}*/

			/**
			 * Bulan terkecil tapi masih blm ada transaksi
			 */
			/*foreach ($frequency as $row_bulan_frequency) {
				$pilihan_bulan=$bulan_terkecil_1.date("Y");
				$total_aspect_non_performance = $this->behaviour_model->get_count_non_performance($row_bulan_frequency->aspect_setting_id, $pilihan_bulan, $HolderID)->total_non_performance;
				if($total_aspect_non_performance==0)
				{
					$data['periode_aspect']=$pilihan_bulan;
					$filter_month_check=$bulan_terkecil_1;
				}
			}*/

			
			//Behaviour Diri Sendiri
			foreach ($frequency as $row) 
			{
				$pilihan_bulan=$bulan_terkecil_1.$Period->Tahun;
				
				//Jika ESG layer tidak sesuai dengan data di aspect setting maka behaviour yang sesuai dengan esg layernya
				if (strpos($row->esg, $this->session->userdata('esg')) !== false)
				{
					/**
					 * Bulan terkecil tapi masih blm ada transaksi
					 */
					$aspect_setting[]=$row->aspect_setting_id;	
					$total_aspect_non_performance = $this->behaviour_model->get_count_non_performance($row->aspect_setting_id, $pilihan_bulan, $this->session->userdata('NIK'))->total_t_header;
					if($total_aspect_non_performance==0)
					{
						$data['periode_aspect']=$pilihan_bulan;
						$filter_month_check=$bulan_terkecil_1;
					}

					/* END bulan terkecil */
					$frequency_bulan = $row->frequency;
					//pertama ukur panjang frequency
					$panjang_frequency=strlen($frequency_bulan);
					//titik koma ada berapa
					$titik_koma_frequency=substr_count($frequency_bulan,';');
					$total_loop_freq = ($panjang_frequency-$titik_koma_frequency)/2;
					//loop berdasarkan frequency
					$j=0;

					for($i=0;$i<=$total_loop_freq;$i++)
					{
						$frequency_bersih=str_replace(';', '', $frequency_bulan);
						$choose_month = substr($frequency_bersih,$j,2);
						if($choose_month!='')
						{
							$pilihan_bulan=$choose_month.$Period->Tahun;
							$total_aspect_non_performance = $this->behaviour_model->get_count_non_performance($row->aspect_setting_id, $pilihan_bulan, $this->session->userdata('NIK'))->total_t_header;
							//cek apabila 0 di table non performance maka dia blm diisi
							if($total_aspect_non_performance==0)
							{
								$data['periode_aspect']=$pilihan_bulan;
								$choose_month_int = (int) $choose_month;
								
								if($choose_month_int<=$month)
								{
									$filter_month_check=$choose_month_int;	
									break;		
								}
							}
							else
							{
								$choose_month_int = (int) $choose_month;
								if($choose_month_int==$month)
								{
									$flag="edit";
									$data['periode_aspect']=$pilihan_bulan;
									$filter_month_check=$choose_month_int;	
									break;
								}
							}
						}
						$j=$j+2;
					}
					$data['aspect_setting'] = $aspect_setting;
				}
				elseif($row->esg==0)
				{
					/**
					 * Bulan terkecil tapi masih blm ada transaksi
					 */
					$aspect_setting[]=$row->aspect_setting_id;	
					$total_aspect_non_performance = $this->behaviour_model->get_count_non_performance($row->aspect_setting_id, $pilihan_bulan, $this->session->userdata('NIK'))->total_t_header;
					if($total_aspect_non_performance==0)
					{
						$data['periode_aspect']=$pilihan_bulan;
						$filter_month_check=$bulan_terkecil_1;
					}
					/* END bulan terkecil */
					$frequency_bulan = $row->frequency;
					//pertama ukur panjang frequency
					$panjang_frequency=strlen($frequency_bulan);
					//titik koma ada berapa
					$titik_koma_frequency=substr_count($frequency_bulan,';');
					$total_loop_freq = ($panjang_frequency-$titik_koma_frequency)/2;
					//loop berdasarkan frequency
					$j=0;

					for($i=0;$i<=$total_loop_freq;$i++)
					{
						$frequency_bersih=str_replace(';', '', $frequency_bulan);
						$choose_month = substr($frequency_bersih,$j,2);
						if($choose_month!='')
						{
							$pilihan_bulan=$choose_month.$Period->Tahun;
							$total_aspect_non_performance = $this->behaviour_model->get_count_non_performance($row->aspect_setting_id, $pilihan_bulan, $this->session->userdata('NIK'))->total_t_header;
							//cek apabila 0 di table non performance maka dia blm diisi
							if($total_aspect_non_performance==0)
							{
								$data['periode_aspect']=$pilihan_bulan;
								$choose_month_int = (int) $choose_month;
								
								if($choose_month_int<=$month)
								{
									$filter_month_check=$choose_month_int;	
									break;		
								}
							}
							else
							{
								$choose_month_int = (int) $choose_month;
								if($choose_month_int==$month)
								{
									$flag="edit";
									$data['periode_aspect']=$pilihan_bulan;
									$filter_month_check=$choose_month_int;	
									break;
								}
							}
						}
						$j=$j+2;
					}
					$data['aspect_setting'] = $aspect_setting;
				}	
				
				$data['pilihan_bulan']=$pilihan_bulan;	
				
			}
		}

		$flag='';
		if(isset($filter_month_check)!='')
		{
			//Apakah sudah diisi atau blm kalau 1 sudah kalau 0 belum
			if($total_aspect_non_performance!=1)
			{
				if($filter_month_check <= $month) 
				{
					$data_header['process']  = 'performance/behaviour/process_behaviour/'.$this->session->userdata('NIK');
					$this->load->View('performance/behaviour_header_view',$data_header);
					$data['bulan_terpilih']=date('F', mktime(0, 0, 0, $filter_month_check, 10));
					//buat array untuk menampung query
					$temp_detail_aspect_setting_array=array();
					//$temp_sub_aspect_setting_array=array();
					
					foreach ($aspect_setting as $key) {
						$data_sub_aspect_setting = $this->behaviour_model->get_aspect_setting_data_list_by_id_and_periode($key,$bulan_terkecil_1);
						$temp_detail_aspect_setting_array[$key] = $data_sub_aspect_setting;
						foreach ($data_sub_aspect_setting as $row) {
							$data_behaviour[$row->behaviour_group_id] = $this->behaviour_model->get_behaviour_group_behaviour_by_id($row->behaviour_group_id, $Period->BeginDate,$Period->EndDate);
							$data['data_behaviour']=$data_behaviour;
							$data_behaviour_scala[$row->behaviour_group_id] = $this->behaviour_model->get_behaviour_group_scala_by_id($row->behaviour_group_id, $Period->BeginDate,$Period->EndDate);
							$data['data_behaviour_scala']=$data_behaviour_scala;
						}
					}
					
					$data['detail_aspect_setting']=$temp_detail_aspect_setting_array;
				}
				else
				{
					$this->load->View('performance/behaviour_header_view',$data_header);
					$data['notif_text']='This month do not have behaviour';
					$data['notif_type']='alert-error';
				}

				$data['answer']='';
			}
			else
			{
				/**
				 * BUAT EDIT : Pertama dapatkan semua data  yang di master
				 */
				$data_header['process']  = 'performance/behaviour/update_process_behaviour/'.$this->session->userdata('NIK');
				//munculkan data sesuai bulan
				$this->load->View('performance/behaviour_header_view',$data_header);
				$data['bulan_terpilih']=date('F', mktime(0, 0, 0, $filter_month_check, 10));
				//buat array untuk menampung query
				$temp_detail_aspect_setting_array=array();
				
				foreach ($aspect_setting as $key) {
					$data_sub_aspect_setting = $this->behaviour_model->get_aspect_setting_data_list_by_id_and_periode($key,$bulan_terkecil_1);
					$temp_detail_aspect_setting_array[$key] = $data_sub_aspect_setting;
					foreach ($data_sub_aspect_setting as $row) {
						//get non performance_id
						$data_behaviour[$row->behaviour_group_id] = $this->behaviour_model->get_behaviour_group_behaviour_by_id($row->behaviour_group_id, $Period->BeginDate,$Period->EndDate);
						$data['data_behaviour']=$data_behaviour;
						$data_behaviour_scala[$row->behaviour_group_id] = $this->behaviour_model->get_behaviour_group_scala_by_id($row->behaviour_group_id, $Period->BeginDate,$Period->EndDate);
						$data['data_behaviour_scala']=$data_behaviour_scala;
					}
				}

				$data['detail_aspect_setting']=$temp_detail_aspect_setting_array;
				$old_answer = $this->behaviour_model->get_answer_performance($pilihan_bulan,$this->session->userdata('NIK')); 
			
				$answer = array();
				$approve = array();
				$sum_achieve=0;

				///////////////
				// Project //
				///////////////

				$c_proj = $this->report_model->count_project($this->session->userdata('NIK'),$Period->BeginDate,$Period->EndDate); 
				
				$proj_result = $this->report_model->sum_result($this->session->userdata('NIK'),$Period->BeginDate,$Period->EndDate);
				if ($c_proj == 1) {
					if ($proj_result > 0.30) {
						$proj_result = 0.30;
					}
				} elseif ($c_proj == 2) {
					if ($proj_result > 0.60) {
						$proj_result = 0.60;
					}
				} 

				$data['proj_result'] = round($proj_result,2);

				// end of Project
				
				/////////////////////
				// Gauge Visual  //
				/////////////////////
				
				$data['color_range'] = $this->general_model->get_Scale_list(2,date('Y-m-d'),date('Y-m-d'));
				$data['max_high']    = $this->general_model->get_Scale_statistic(2,date('Y-m-d'),date('Y-m-d'))->high_max;

				// end of Gauge Visual

				$ba_cur = 0;
				$ba_ytd = 0;

				foreach ($old_answer as $value) 
				{
					$header_id = $value->header_id;
					$weight= $this->behaviour_model->get_weight_by_behaviour_id($value->behaviour_id)->weight;
					$notes[]= $value->notes;
					$answer[]=$value->periode.'-'.$value->behaviour_id.'-'.(int) $value->achievement;
					$approve[$value->periode.'-'.$value->behaviour_id]=$value->approved_by;
					$nilai_achievement = $weight/100*$value->achievement;
					$sum_achieve+=$nilai_achievement;
				}
				$be_cur = $sum_achieve;
				$be_ytd = $sum_achieve;
				$gt_cur = (0.7 * $ba_cur) + (0.3 * $be_cur) + $proj_result;
				$gt_ytd = (0.7 * $ba_ytd) + (0.3 * $be_ytd) + $proj_result;

				$data['ba_cur'] = $ba_cur * 10;
				$data['ba_ytd'] = $ba_ytd * 10;

				$data['be_cur'] = $be_cur * 10;
				$data['be_ytd'] = $be_ytd * 10;

				$data['gt_cur'] = round($gt_cur * 10 ,2);
				$data['gt_ytd'] = round($gt_ytd * 10 ,2);	

				$data['total_behaviour'] = $sum_achieve;

				if(isset($notes)=='')
				{
					$notes='';
				}

				$data['note_eviden']=$notes;
				$data['answer']=$answer;
				$data['approve']=$approve;
				$data['status_flag'] = $this->behaviour_model->get_status_flag($header_id)->status;
			}
		}
		else
		{
			$this->load->View('performance/behaviour_header_view',$data_header);
			$data['notif_text']='This month do not have behaviour';
			$data['notif_type']='alert-error';
		}



		$this->load->View('performance/behaviour_view',$data);
		$this->load->view('template/bottom_1_view');
		$this->load->View('performance/behaviour_view_js');
	}
	
	
	//Behaviour Bawahan
	function view_subordinate($NIK,$PositionID,$month_parent)
	{
		$data_header['notif_text']=$this->session->flashdata('notif_text');
		$data_header['notif_type']=$this->session->flashdata('notif_type');

		$Period = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$data_header['Periode'] = $Period;
		$data_header['Title']   = 'Behaviour - Subordinate';

		//dapatkan holder id
		$Holder =$this->session->userdata('Holder');
		$Holder = $this->input->post('SlcPost');

		$data_header['Holder']=$Holder;
		$this->session->set_userdata('Holder',$Holder);

		if($this->input->post('slcMonth')=='')
		{
			$month = $month_parent;
		}else{
			$month = $this->input->post('slcMonth');
		}

		if ($month=='')
		{
			$month = date('n');
			if($month==0)
			{
				$month = 12;	
			}
		}

		if($Holder=='')
		{
			$HolderID = $this->account_model->get_Holder_row_byNIK($NIK,1,$Period->BeginDate,$Period->EndDate)->HolderID;
		}
		else
		{
			$HolderID     =substr($Holder, 2);
		}


		$data_header['month']=$month;
		$data_header['action']  = 'performance/behaviour/view_subordinate/'.$NIK.'/'.$PositionID.'/'.$Period->Tahun.'';

		$this->session->set_userdata('slcMonth',$month);
		$userDetail = $this->behaviour_model->get_User_row($NIK);
		$data_header['userDetail']   = $userDetail;
		$data_header['PositionName'] = $this->behaviour_model->get_Position_row($PositionID,$userDetail->isSAP)->PositionName;
		$isSAP        =$this->session->userdata('isSAP');
		$HolderDetail = $this->account_model->get_Holder_row($HolderID,$isSAP);
		if (strtotime($Period->BeginDate) >= strtotime($HolderDetail->Holder_BeginDate)) {
			$BeginDate = $Period->BeginDate;
		} else {
			$BeginDate = $HolderDetail->Holder_BeginDate;

		}

		if (strtotime($Period->EndDate) <= strtotime($HolderDetail->Holder_EndDate)) {
			$EndDate = $Period->EndDate;
		} else {
			$EndDate = $HolderDetail->Holder_EndDate;

		}
		
		$subordinate  = $this->behaviour_model->get_subordinate_list($isSAP,$PositionID,$BeginDate,$EndDate);
		//$subordinate  = $this->behaviour_model->get_subordinate_list($isSAP,$PositionID,$Period->BeginDate,$Period->EndDate);

		if (count($subordinate))
		{
			$i=0;
			foreach ($subordinate as $row) {
				$subordinate_list[$i]=anchor('performance/behaviour/view_subordinate/'.$row->NIK.'/'.$row->PositionID.'/'.$Period->Tahun.'/'.$month,$row->PositionName.' ('.$row->NIK . ' - '. $row->Fullname .')');
				$i++;
			}
			$data_header['subordinate'] = ul($subordinate_list);
			
		}
		else
		{
			$data_header['subordinate'] = '';

		}

		$link['chief']     = 'performance/behaviour';
		$data_header['link']      = $link;
		$data_header['person']    = $userDetail->Fullname .' - '. $userDetail->NIK;

		$this->load->view('template/top_1_view');
		//Chek organisasi parent
		$data_org =$this->behaviour_model->get_setting($isSAP,$HolderDetail->OrganizationID, $Period->BeginDate,$Period->EndDate);
		foreach ($data_org as $row) {
			$organisasi_setting= $row->organization_id;
		}

		$get_aspect_setting_data_count = $this->behaviour_model->get_aspect_setting_data_count($organisasi_setting,$Period->BeginDate,$Period->EndDate)->total_aspect_setting;
		$get_layer = $this->account_model->get_User_nik($NIK)->esg;

		if($get_aspect_setting_data_count!=0)
		{
			//Mendapatkan bulan terkecil
			//$frequency = $this->behaviour_model->get_aspect_setting_data_list($HolderDetail->OrganizationID,$Period->BeginDate,$Period->EndDate);
			$frequency = $this->behaviour_model->get_aspect_setting_data_list($organisasi_setting,$Period->BeginDate,$Period->EndDate);
			$bulan_array=array();
			$bulan_terkecil_1=13;
			foreach ($frequency as $row_1) 
			{
				
				//Jika ESG layer tidak sesuai dengan data di aspect setting maka behaviour yang sesuai dengan esg layernya
				if (strpos($row_1->esg, $get_layer) !== false)
				{
					$frequency_bulan_1 = $row_1->frequency;
					//pertama ukur panjang frequency
					$panjang_frequency_1=strlen($frequency_bulan_1);
					//titik koma ada berapa
					$titik_koma_frequency_1=substr_count($frequency_bulan_1,';');
					$total_loop_freq_1 = ($panjang_frequency_1-$titik_koma_frequency_1)/2;
					//$aspect_setting[]=$row_1->aspect_setting_id;
					//loop berdasarkan frequency
					$j=0;
					for($i=1;$i<=$total_loop_freq_1;$i++)
					{
						$frequency_bersih_1=str_replace(';', '', $frequency_bulan_1).'<br>';
						$choose_month_1 = substr($frequency_bersih_1,$j,2);
						$choose_month_int_1 = (int) $choose_month_1;
						//$bulan_array[$row_1->aspect_setting_id][]=$choose_month_1;
						$bulan_array[]=$choose_month_1;
						
						if($choose_month_int_1!='')
						{
							if($bulan_terkecil_1>$choose_month_int_1)
							{
								$bulan_terkecil_1=$choose_month_1;	
							}		
						}
						$j=$j+2;
					}
				}
				elseif ($row_1->esg==0)
				{
					$frequency_bulan_1 = $row_1->frequency;
					//pertama ukur panjang frequency
					$panjang_frequency_1=strlen($frequency_bulan_1);
					//titik koma ada berapa
					$titik_koma_frequency_1=substr_count($frequency_bulan_1,';');
					$total_loop_freq_1 = ($panjang_frequency_1-$titik_koma_frequency_1)/2;
					//$aspect_setting[]=$row_1->aspect_setting_id;
					//loop berdasarkan frequency
					$j=0;
					for($i=1;$i<=$total_loop_freq_1;$i++)
					{
						$frequency_bersih_1=str_replace(';', '', $frequency_bulan_1).'<br>';
						$choose_month_1 = substr($frequency_bersih_1,$j,2);
						$choose_month_int_1 = (int) $choose_month_1;
						//$bulan_array[$row_1->aspect_setting_id][]=$choose_month_1;
						$bulan_array[]=$choose_month_1;
						
						if($choose_month_int_1!='')
						{
							if($bulan_terkecil_1>$choose_month_int_1)
							{
								$bulan_terkecil_1=$choose_month_1;	
							}		
						}
						$j=$j+2;
					}
				}
			}	

			//Behaviour Subordinate
			foreach ($frequency as $row) 
			{
				/**
				 * Bulan terkecil tapi masih blm ada transaksi
				 */
				//$pilihan_bulan=$bulan_terkecil_1.date("Y");
				$pilihan_bulan=$bulan_terkecil_1.$Period->Tahun;
				
				//Jika ESG layer tidak sesuai dengan data di aspect setting maka behaviour yang sesuai dengan esg layernya
				if (strpos($row->esg, $get_layer) !== false)
				{
					$total_aspect_non_performance = $this->behaviour_model->get_count_non_performance($row->aspect_setting_id, $pilihan_bulan, $NIK)->total_t_header;
					if($total_aspect_non_performance==0)
					{
						$data['periode_aspect']=$pilihan_bulan;
						$filter_month_check=$bulan_terkecil_1;
					}

					/* END bulan terkecil */

					$aspect_setting[]=$row->aspect_setting_id;

					$frequency_bulan = $row->frequency;

					//pertama ukur panjang frequency
					$panjang_frequency=strlen($frequency_bulan);
					//titik koma ada berapa
					$titik_koma_frequency=substr_count($frequency_bulan,';');
					$total_loop_freq = ($panjang_frequency-$titik_koma_frequency)/2;
					//loop berdasarkan frequency
					$j=0;

					for($i=0;$i<=$total_loop_freq;$i++)
					{
						$frequency_bersih=str_replace(';', '', $frequency_bulan);
						$choose_month = substr($frequency_bersih,$j,2);
						if($choose_month!='')
						{
							$pilihan_bulan=$choose_month.$Period->Tahun;
							$total_aspect_non_performance = $this->behaviour_model->get_count_non_performance($row->aspect_setting_id, $pilihan_bulan, $NIK)->total_t_header;
							//cek apabila 0 di table non performance maka dia blm diisi
							if($total_aspect_non_performance==0)
							{
								$data['periode_aspect']=$pilihan_bulan;
								$choose_month_int = (int) $choose_month;
								
								if($choose_month_int<=$month)
								{
									$filter_month_check=$choose_month_int;	
									break;		
								}
							}
							else
							{
								$choose_month_int = (int) $choose_month;
								if($choose_month_int==$month)
								{
									$flag="edit";
									$data['periode_aspect']=$pilihan_bulan;
									$filter_month_check=$choose_month_int;	
									break;
								}
							}
						}
						$j=$j+2;
					}
					$data['aspect_setting'] = $aspect_setting;
				}
				elseif($row->esg==0)
				{
					$total_aspect_non_performance = $this->behaviour_model->get_count_non_performance($row->aspect_setting_id, $pilihan_bulan, $NIK)->total_t_header;
					if($total_aspect_non_performance==0)
					{
						$data['periode_aspect']=$pilihan_bulan;
						$filter_month_check=$bulan_terkecil_1;
					}

					/* END bulan terkecil */

					$aspect_setting[]=$row->aspect_setting_id;

					$frequency_bulan = $row->frequency;

					//pertama ukur panjang frequency
					$panjang_frequency=strlen($frequency_bulan);
					//titik koma ada berapa
					$titik_koma_frequency=substr_count($frequency_bulan,';');
					$total_loop_freq = ($panjang_frequency-$titik_koma_frequency)/2;
					//loop berdasarkan frequency
					$j=0;

					for($i=0;$i<=$total_loop_freq;$i++)
					{
						$frequency_bersih=str_replace(';', '', $frequency_bulan);
						$choose_month = substr($frequency_bersih,$j,2);
						if($choose_month!='')
						{
							$pilihan_bulan=$choose_month.$Period->Tahun;
							$total_aspect_non_performance = $this->behaviour_model->get_count_non_performance($row->aspect_setting_id, $pilihan_bulan, $NIK)->total_t_header;
							//cek apabila 0 di table non performance maka dia blm diisi
							if($total_aspect_non_performance==0)
							{
								$data['periode_aspect']=$pilihan_bulan;
								$choose_month_int = (int) $choose_month;
								
								if($choose_month_int<=$month)
								{
									$filter_month_check=$choose_month_int;	
									break;		
								}
							}
							else
							{
								$choose_month_int = (int) $choose_month;
								if($choose_month_int==$month)
								{
									$flag="edit";
									$data['periode_aspect']=$pilihan_bulan;
									$filter_month_check=$choose_month_int;	
									break;
								}
							}
						}
						$j=$j+2;
					}
					$data['aspect_setting'] = $aspect_setting;
				}
				$data['pilihan_bulan']=$pilihan_bulan;	
			}
		}

		$flag='';
		if(isset($filter_month_check)!='')
		{

			//Apakah sudah diisi atau blm kalau 1 sudah kalau 0 belum
			if($total_aspect_non_performance!=1)
			{
				if($filter_month_check <= $month) 
				{
					$data_header['process']  = 'performance/behaviour/process_behaviour/'.$NIK;
					$this->load->View('performance/behaviour_header_view',$data_header);
					$data['bulan_terpilih']=date('F', mktime(0, 0, 0, $filter_month_check, 10));
					//buat array untuk menampung query
					$temp_detail_aspect_setting_array=array();
					//$temp_sub_aspect_setting_array=array();
					
					foreach ($aspect_setting as $key) {
						$data_sub_aspect_setting = $this->behaviour_model->get_aspect_setting_data_list_by_id_and_periode($key,$bulan_terkecil_1);
						$temp_detail_aspect_setting_array[$key] = $data_sub_aspect_setting;
						foreach ($data_sub_aspect_setting as $row) {
							$data_behaviour[$row->behaviour_group_id] = $this->behaviour_model->get_behaviour_group_behaviour_by_id($row->behaviour_group_id, $Period->BeginDate,$Period->EndDate);
							$data['data_behaviour']=$data_behaviour;
							$data_behaviour_scala[$row->behaviour_group_id] = $this->behaviour_model->get_behaviour_group_scala_by_id($row->behaviour_group_id, $Period->BeginDate,$Period->EndDate);
							$data['data_behaviour_scala']=$data_behaviour_scala;
						}
					}

					$data['detail_aspect_setting']=$temp_detail_aspect_setting_array;
				}
				else
				{
					$data['notif_text']='This month do not have behaviour';
					$data['notif_type']='alert-error';
				}

				$data['answer']='';
				$data['status_header']='';

			}
			else
			{
				/**
				 * BUAT EDIT
				 */
				$data_header['process']  = 'performance/behaviour/update_process_behaviour/'.$NIK;

				/**
				 * Approval
				 */
				
				$status_header = $this->behaviour_model->get_header_row($aspect_setting[0], $pilihan_bulan,$NIK)->status;
				$data['status_header']=$status_header;

				//munculkan data sesuai bulan
				$data['bulan_terpilih']=date('F', mktime(0, 0, 0, $filter_month_check, 10));

				$link['approve']='performance/behaviour/approve/'.$pilihan_bulan.'/'.$NIK;
				$link['reject']='performance/behaviour/reject/'.$pilihan_bulan.'/'.$NIK;
				$data_approval['link']=$link;
				$this->load->View('performance/behaviour_header_view',$data_header);

				if($status_header==1 OR $status_header==2 OR $status_header==3)
				{
					$this->load->view('performance/behaviour_approval_view',$data_approval);
				}

				//buat array untuk menampung query
				$temp_detail_aspect_setting_array=array();
				
				foreach ($aspect_setting as $key) {
					$data_sub_aspect_setting = $this->behaviour_model->get_aspect_setting_data_list_by_id_and_periode($key,$bulan_terkecil_1);
					$temp_detail_aspect_setting_array[$key] = $data_sub_aspect_setting;
					foreach ($data_sub_aspect_setting as $row) {
						//get non performance_id
						$data_behaviour[$row->behaviour_group_id] = $this->behaviour_model->get_behaviour_group_behaviour_by_id($row->behaviour_group_id, $Period->BeginDate,$Period->EndDate);
						$data['data_behaviour']=$data_behaviour;
						$data_behaviour_scala[$row->behaviour_group_id] = $this->behaviour_model->get_behaviour_group_scala_by_id($row->behaviour_group_id, $Period->BeginDate,$Period->EndDate);
						$data['data_behaviour_scala']=$data_behaviour_scala;
					}
				}

				$data['detail_aspect_setting']=$temp_detail_aspect_setting_array;
				$old_answer = $this->behaviour_model->get_answer_performance($pilihan_bulan,$NIK); 


				///////////////
				// Project //
				///////////////

				$c_proj = $this->report_model->count_project($this->session->userdata('NIK'),$Period->BeginDate,$Period->EndDate); 
				
				$proj_result = $this->report_model->sum_result($this->session->userdata('NIK'),$Period->BeginDate,$Period->EndDate);
				if ($c_proj == 1) {
					if ($proj_result > 0.30) {
						$proj_result = 0.30;
					}
				} elseif ($c_proj == 2) {
					if ($proj_result > 0.60) {
						$proj_result = 0.60;
					}
				} 

				$data['proj_result'] = round($proj_result,2);

				// end of Project
				
				/////////////////////
				// Gauge Visual  //
				/////////////////////
				
				$data['color_range'] = $this->general_model->get_Scale_list(2,date('Y-m-d'),date('Y-m-d'));
				$data['max_high']    = $this->general_model->get_Scale_statistic(2,date('Y-m-d'),date('Y-m-d'))->high_max;

				// end of Gauge Visual

				$ba_cur = 0;
				$ba_ytd = 0;
				
 			$get_performance = $this->behaviour_model->get_performance_id_row($pilihan_bulan,$NIK);

                /**
                 * Get data Aspect Setting
                 */
                $get_data_aspect_setting_row = $this->behaviour_model->get_aspect_setting_data_list_by_row($get_performance->aspect_setting_id);
                $get_accumulate_achievement = $this->behaviour_model->get_accumulate_achievement($get_performance->aspect_setting_id,$get_data_aspect_setting_row->behaviour_group_id, $NIK, $pilihan_bulan);
				
				$answer = array();
				$approve = array();
				$sum_achieve=0;
				foreach ($old_answer as $value) 
				{
					$weight= $this->behaviour_model->get_weight_by_behaviour_id_group($get_data_aspect_setting_row->behaviour_group_id,$value->behaviour_id)->weight;
					$notes[]= $value->notes;
					$answer[]=$value->periode.'-'.$value->behaviour_id.'-'.(int) $value->achievement;
					$approve[$value->periode.'-'.$value->behaviour_id]=$value->approved_by;
					$nilai_achievement = $weight/100*$value->achievement;
					$sum_achieve+=$nilai_achievement;
				}

				$be_cur = $sum_achieve;
				$be_ytd = $sum_achieve;
				$gt_cur = (0.7 * $ba_cur) + (0.3 * $be_cur) + $proj_result;
				$gt_ytd = (0.7 * $ba_ytd) + (0.3 * $be_ytd) + $proj_result;

				$data['ba_cur'] = $ba_cur * 10;
				$data['ba_ytd'] = $ba_ytd * 10;

				$data['be_cur'] = $be_cur * 10;
				$data['be_ytd'] = $be_ytd * 10;

				$data['gt_cur'] = round($gt_cur * 10 ,2);
				$data['gt_ytd'] = round($gt_ytd * 10 ,2);	

				$data['total_behaviour'] = $sum_achieve;

				
				$data['note_eviden']=$notes;
				$data['answer']=$answer;
				$data['approve']=$approve;
			}
		}
		else
		{
			$data['status_header']=NULL;
			$total_aspect_non_performance=0;
			$this->load->View('performance/behaviour_header_view',$data_header);
			$data['notif_text']='This month do not have behaviour';
			$data['notif_type']='alert-error';
		}


			
		$data['total_aspect_non_performance']=$total_aspect_non_performance;
		$this->load->View('performance/behaviour_subordinate_view',$data);
		$this->load->view('template/bottom_1_view');
		$this->load->View('performance/behaviour_subordinate_view_js');

	}


	function process_behaviour($nik)
	{
		/**
		 * HEADER
		 */		
		$data_header['notif_text']=$this->session->flashdata('notif_text');
		$data_header['notif_type']=$this->session->flashdata('notif_type');
		$Period = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$data_header['Periode'] = $Period;
		$data_header['Title']   = 'Behaviour';
		$data_header['action']  = 'performance/behaviour/';
		
		//dapatkan holder id
		$Holder =$this->session->userdata('Holder');
		$Holder = $this->input->post('SlcPost');

		$data_header['Holder']=$Holder;
		$this->session->set_userdata('Holder',$Holder);

		$month = $this->session->userdata('slcMonth');
		$month = $this->input->post('slcMonth');
		
		if($month=='')
		{
			$month = date('n');
			if($month==0)
			{
				$month = 12;
			}
		}
		
		if($Holder=='')
		{
			$HolderID = $this->account_model->get_Holder_row_byNIK($this->session->userdata('NIK'),1,$Period->BeginDate,$Period->EndDate)->HolderID;
		}
		else
		{
			$HolderID     =substr($Holder, 2);
		}

		$data_header['month']=$month;
		$this->session->set_userdata('slcMonth',$month);
		$userDetail = $this->behaviour_model->get_User_row($this->session->userdata('NIK'));
		$data_header['userDetail']                    = $userDetail;
		$data_header['PositionList_SAP']              =$this->account_model->get_Holder_list($this->session->userdata('NIK'),1,$Period->BeginDate,$Period->EndDate);
		$data_header['PositionList_nonSAP']           = $this->account_model->get_Holder_list($this->session->userdata('NIK'),0,$Period->BeginDate,$Period->EndDate);
		$data_header['PositionAssignmentList_SAP']    =$this->account_model->get_Assignment_list($this->session->userdata('NIK'),1,$Period->BeginDate,$Period->EndDate);
		$data_header['PositionAssignmentList_nonSAP'] =$this->account_model->get_Assignment_list($this->session->userdata('NIK'),0,$Period->BeginDate,$Period->EndDate);
		
		$isSAP        =$this->session->userdata('isSAP');
		$HolderDetail = $this->behaviour_model->get_Holder_row($HolderID,$isSAP);
		$subordinate  = $this->behaviour_model->get_subordinate_list($isSAP,$HolderDetail->PositionID,$Period->BeginDate,$Period->EndDate);
		if (count($subordinate))
		{
			$i=0;
			foreach ($subordinate as $row) {
				$subordinate_list[$i]=anchor('performance/behaviour/view_subordinate/'.$row->NIK.'/'.$row->PositionID.'/'.$Period->Tahun.'/'.$month,$row->PositionName.' ('.$row->NIK . ' - '. $row->Fullname .')');
				$i++;
			}
			$data_header['subordinate'] = ul($subordinate_list);
			
		}
		else
		{
			$data_header['subordinate'] = '';

		}

		$data_header['process']  = 'performance/behaviour/process_behaviour/'.$nik;

		/**
		 * pertama simpan ke table tb_non_performance
		 * setting variable buat disave ke tb_non_performance
		 * dengan value(holder_id, aspect_setting_id, periode, date_submitted)
		 */
		$periode=$this->input->post('txt_periode_aspect');
		$aspect_setting_id=$this->input->post('txt_aspect_setting_id');
		$value_group = $this->input->post('group');
		$notes_eviden = $this->input->post('txt_notes_eviden');

	

		$get_total_bhv_header = $this->behaviour_model->get_count_non_performance($aspect_setting_id,$periode, $nik)->total_t_header;

		if($get_total_bhv_header==0)
		{
			if($value_group!='')
			{
				

				if(count($aspect_setting_id)==1)
				{
						$submited_date = date('Y-m-d H:i:s');

						
						/**
						 * save data non performance id
						 */
						$this->behaviour_model->save_data_non_performance($this->session->userdata('NIK'),$aspect_setting_id,$periode,$submited_date, 1);
						$data['notif_text']='Success add non performance';
						$data['notif_type']='alert-success';
				}
				else
				{
					foreach ($aspect_setting_id as $row) {
						
						$total_aspect_non_performance = $this->behaviour_model->get_count_non_performance($row[0], $periode, $this->session->userdata('NIK'))->total_t_header;
						if($total_aspect_non_performance==0)
						{
							$aspect_setting_id=$row[0];
							$submited_date = date('Y-m-d H:i:s');
							/**
							 * save data non performance id
							 */
							$this->behaviour_model->save_data_non_performance($this->session->userdata('NIK'),$aspect_setting_id,$periode,$submited_date, 1);
							
							$data['notif_text']='Success add non performance';
							$data['notif_type']='alert-success';
						}
						else
						{
							//redirect
							redirect('performance/behaviour/');
						}
						
					}

				}

				$i=0;
				foreach ($value_group as $key =>$value) 
				{
					$non_performance_id=$this->behaviour_model->get_non_performance_holder_aspect_setting_behaviour_row_performance_id($this->session->userdata('NIK'),$periode)->header_id;
					$this->behaviour_model->save_data_non_performance_achievement($non_performance_id,$key,$value,$submited_date, $notes_eviden[$i]);			
					$i++;
				}

				/**
				 * kedua set variable yang akan di save
				 * ke table tb_non_performance_achievement
				 * dengan value non_performance_id, behaviour_id, achievement, submitted_date
				 */
				
				

				$get_Chief =$this->org_model->get_Chief_Position_row($isSAP, $HolderDetail->PositionID);
				/**
				* Send Email to Chief
				*/
				
				$sub_name            = $this->account_model->get_User_byNIK($nik)->Fullname;
				$chief_name            = $this->account_model->get_User_byNIK($get_Chief->NIK)->Fullname;
				$chief_email               = $this->account_model->get_User_byNIK($get_Chief->NIK)->Email;
				$config['smtp_host'] ="10.10.55.10";
				$config['smtp_user'] ="pms@chr.kompasgramedia.com";
				$config['smtp_pass'] ="Abc123"; 
				$config['mailtype']  ='html';
				$config['priority']  =1;
				$config['protocol']  ='smtp';
				$this->email->initialize($config);
				$this->email->from('pms@chr.kompasgramedia.com', '[PMS Online] DO NOT REPLY THIS EMAIL!');
				$this->email->to($chief_email);
				$this->email->subject('[PMS Online] Behaviour Information');
				$this->email->message("<h2>Information</h2>Behaviour for ".$sub_name." has been submit, 
					please check your PMS Online.<br> 
					If you're not ".$chief_name.",please ignore this email. <br>Thank you,<br><br>PMS Online");
				
				/*if($this->email->send()){
					$this->session->set_flashdata('notif_text',"Email has been sent.");
					$this->session->set_flashdata('notif_type','alert-success');
				}else{
					$this->session->set_flashdata('notif',"Email has not sent");
					$this->session->set_flashdata('notif_type',"alert-danger");
				}*/


			}else
			{
				$data['notif_text']='Failed add non performance';
				$data['notif_type']='alert-error';
			}

			

		}
		else{
			redirect('performance/behaviour/');
		}				
		
		$this->load->view('template/top_1_view');
		$this->load->View('performance/behaviour_header_view',$data_header);
		$this->load->View('performance/behaviour_view',$data);
		$this->load->view('template/bottom_1_view');
	}

	function update_process_behaviour($nik)
	{

		/**
		 * HEADER
		 */		
		$data_header['notif_text']=$this->session->flashdata('notif_text');
		$data_header['notif_type']=$this->session->flashdata('notif_type');
		$Period = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$data_header['Periode'] = $Period;
		$data_header['Title']   = 'Behaviour';
		$data_header['action']  = 'performance/behaviour/';
		
		//dapatkan holder id
		$Holder =$this->session->userdata('Holder');
		$Holder = $this->input->post('SlcPost');

		$data_header['Holder']=$Holder;
		$this->session->set_userdata('Holder',$Holder);

		$month = $this->session->userdata('slcMonth');
		$month = $this->input->post('slcMonth');
		
		if($month=='')
		{
			$month = date('n');
			if($month==0)
			{
				$month = 12;
			}
		}
		
		if($Holder=='')
		{
			$HolderID = $this->account_model->get_Holder_row_byNIK($this->session->userdata('NIK'),1,$Period->BeginDate,$Period->EndDate)->HolderID;
		}
		else
		{
			$HolderID = substr($Holder, 2);
		}

		$data_header['month']=$month;
		$this->session->set_userdata('slcMonth',$month);
		$userDetail = $this->behaviour_model->get_User_row($this->session->userdata('NIK'));
		$data_header['userDetail']                    = $userDetail;
		$data_header['PositionList_SAP']              =$this->account_model->get_Holder_list($this->session->userdata('NIK'),1,$Period->BeginDate,$Period->EndDate);
		$data_header['PositionList_nonSAP']           = $this->account_model->get_Holder_list($this->session->userdata('NIK'),0,$Period->BeginDate,$Period->EndDate);
		$data_header['PositionAssignmentList_SAP']    =$this->account_model->get_Assignment_list($this->session->userdata('NIK'),1,$Period->BeginDate,$Period->EndDate);
		$data_header['PositionAssignmentList_nonSAP'] =$this->account_model->get_Assignment_list($this->session->userdata('NIK'),0,$Period->BeginDate,$Period->EndDate);
		
		$isSAP        =$this->session->userdata('isSAP');
		$HolderDetail = $this->behaviour_model->get_Holder_row($HolderID,$isSAP);
		$subordinate  = $this->behaviour_model->get_subordinate_list($isSAP,$HolderDetail->PositionID,$Period->BeginDate,$Period->EndDate);
		if (count($subordinate))
		{
			$i=0;
			foreach ($subordinate as $row) {
				$subordinate_list[$i]=anchor('performance/behaviour/view_subordinate/'.$row->NIK.'/'.$row->PositionID.'/'.$Period->Tahun.'/'.$month,$row->PositionName.' ('.$row->NIK . ' - '. $row->Fullname .')');
				$i++;
			}
			$data_header['subordinate'] = ul($subordinate_list);
			
		}
		else
		{
			$data_header['subordinate'] = '';

		}

		$data_header['process']  = 'performance/behaviour/process_behaviour/'.$nik;

		$periode=$this->input->post('txt_periode_aspect');
		$aspect_setting_id=$this->input->post('txt_aspect_setting_id');
		$value_group = $this->input->post('group');
		$submited_date = date('Y-m-d H:i:s');
		$notes_eviden = $this->input->post('txt_notes_eviden');

		//update status
		$header_id= $this->behaviour_model->get_non_performance_holder_aspect_setting_behaviour_row_performance_id($nik, $periode)->header_id;
		$this->behaviour_model->update_data_non_performance($header_id,1);


		$i=0;
		foreach ($value_group as $key =>$value) 
		{
				$total_performance_answer=$this->behaviour_model->get_total_non_performance_id_achieve($nik,$periode,$key);
				
				if($total_performance_answer==0)
				 {
				 	$non_performance_id=$this->behaviour_model->get_non_performance_holder_aspect_setting_behaviour_row_performance_id($this->session->userdata('NIK'),$periode)->header_id;
				 	$this->behaviour_model->save_data_non_performance_achievement($non_performance_id,$key,$value,$submited_date, $notes_eviden[$i]);			
				 }else
				 {
				 	$non_performance_id_achievement=$this->behaviour_model->get_non_performance_id_achieve($nik,$periode,$key)->achievement_id;
				 	$this->behaviour_model->update_data_non_performance_achievement($non_performance_id_achievement,$key,$value,$submited_date, $notes_eviden[$i]);	
				 }
				
				$i++;
		}				

		$data['notif_text']='Success update behaviour';
		$data['notif_type']='alert-success';
		
		$this->load->view('template/top_1_view');
		$this->load->View('performance/behaviour_header_view',$data_header);
		$this->load->View('performance/behaviour_view',$data);
		$this->load->view('template/bottom_1_view');
	}


	function approve($bulan_terpilih,$NIK)
	{
		$get_performance = $this->behaviour_model->get_performance_id_row($bulan_terpilih,$NIK);
		
		/**
		 * Get data Aspect Setting
		 */
		$get_data_aspect_setting_row = $this->behaviour_model->get_aspect_setting_data_list_by_row($get_performance->aspect_setting_id);

		/**
		 * GET Calculate Achievement
		 */
		
		$percentage= $get_data_aspect_setting_row->percentage;

		$get_accumulate_achievement = $this->behaviour_model->get_accumulate_achievement($get_performance->aspect_setting_id,$get_data_aspect_setting_row->behaviour_group_id, $NIK, $bulan_terpilih);	
		$sum_achieve=0;
		foreach ($get_accumulate_achievement as $items) {
			$weight= $this->behaviour_model->get_weight_by_behaviour_id_group($get_data_aspect_setting_row->behaviour_group_id,$items->behaviour_id)->weight;
			$nilai_achievement = $weight/100*$items->achievement;
			$sum_achieve+=$nilai_achievement;
		}

		$total_acc_achievement = $sum_achieve;


		

		/**
		 * Approval 
		 */
		$this->behaviour_model->approval_non_performance($get_performance->header_id,$this->session->userdata('NIK'), date('Y-m-d H:i:s'), $total_acc_achievement, 3);
		$this->session->set_flashdata('notif_text','Subordinate '.$NIK.' Behaviour has been approved!');
		$this->session->set_flashdata('notif_typet','alert-success');

		/**
		* Send Email to Chief
		*/
		
		$sub_name            = $this->account_model->get_User_byNIK($NIK)->Fullname;
		$sub_email               = $this->account_model->get_User_byNIK($NIK)->Email;
		$config['smtp_host'] ="10.10.55.10";
		$config['smtp_user'] ="pms@chr.kompasgramedia.com";
		$config['smtp_pass'] ="Abc123"; 
		$config['mailtype']  ='html';
		$config['priority']  =1;
		$config['protocol']  ='smtp';
		$this->email->initialize($config);
		$this->email->from('pms@chr.kompasgramedia.com', '[PMS Online] DO NOT REPLY THIS EMAIL!');
		$this->email->to($sub_email);
		$this->email->subject('[PMS Online] Behaviour Information');
		$this->email->message("<h2>Information</h2>Your Behaviour for has been approved, 
			please check your PMS Online.<br> 
			If you're not ".$sub_name.",please ignore this email. <br>Thank you,<br><br>PMS Online");
		
		/*if($this->email->send()){
			$this->session->set_flashdata('notif_text',"Email has been sent.");
			$this->session->set_flashdata('notif_type','alert-success');
		}else{
			$this->session->set_flashdata('notif',"Email has not sent");
			$this->session->set_flashdata('notif_type',"alert-danger");
		}*/


		redirect('performance/behaviour/');
	}


	function reject($bulan_terpilih,$NIK)
	{
		$get_performance = $this->behaviour_model->get_performance_id($bulan_terpilih,$NIK);
		foreach ($get_performance as $items) {
			$this->behaviour_model->reject_non_performance($items->header_id,2);
		}
		$this->session->set_flashdata('notif_text','Subordinate '.$NIK.' Behaviour has been rejected!');
		$this->session->set_flashdata('notif_typet','alert-success');


		/**
		* Send Email to Chief
		*/
		
		$sub_name            = $this->account_model->get_User_byNIK($NIK)->Fullname;
		$sub_email               = $this->account_model->get_User_byNIK($NIK)->Email;
		$config['smtp_host'] ="10.10.55.10";
		$config['smtp_user'] ="pms@chr.kompasgramedia.com";
		$config['smtp_pass'] ="Abc123"; 
		$config['mailtype']  ='html';
		$config['priority']  =1;
		$config['protocol']  ='smtp';
		$this->email->initialize($config);
		$this->email->from('pms@chr.kompasgramedia.com', '[PMS Online] DO NOT REPLY THIS EMAIL!');
		$this->email->to($sub_email);
		$this->email->subject('[PMS Online] Behaviour Information');
		$this->email->message("<h2>Information</h2>Your Behaviour for has been rejected, 
			please check your PMS Online.<br> 
			If you're not ".$sub_name.",please ignore this email. <br>Thank you,<br><br>PMS Online");
		
		/*if($this->email->send()){
			$this->session->set_flashdata('notif_text',"Email has been sent.");
			$this->session->set_flashdata('notif_type','alert-success');
		}else{
			$this->session->set_flashdata('notif',"Email has not sent");
			$this->session->set_flashdata('notif_type',"alert-danger");
		}*/

		redirect('performance/behaviour/');
	}
	
}
