<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class idp_report extends Controller {
	function __construct(){
		parent::__construct();
		if(!$this->session->userdata('loginFlag')){
			redirect('account/login');
		}

		$this->load->model('idp_model');
		$this->load->model('rkk_model');
		$this->load->model('general_model');
		$this->load->model('org_model');
		$this->load->model('account_model');
		$this->load->model('report_model');
	}
	function index()
	{
		$Periode = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$data_header['Periode']=$Periode;
		$Holder =$this->session->userdata('Holder');
		$Holder = $this->input->post('SlcPost');
		
		$data_header['Holder']=$Holder;
		$this->session->set_userdata('Holder',$Holder);
		$userDetail=$this->account_model->get_User_row($this->session->userdata('userID'));
		$data_header['userDetail']=$userDetail;
		$data_header['PositionList_SAP']=$this->account_model->get_Holder_list($this->session->userdata('NIK'),1,$Periode->BeginDate,$Periode->EndDate);
		$data_header['PositionList_nonSAP']=$this->account_model->get_Holder_list($this->session->userdata('NIK'),0,$Periode->BeginDate,$Periode->EndDate);
		$data_header['PositionAssignmentList_SAP']=$this->account_model->get_Assignment_list($this->session->userdata('NIK'),1,$Periode->BeginDate,$Periode->EndDate);
		$data_header['PositionAssignmentList_nonSAP']=$this->account_model->get_Assignment_list($this->session->userdata('NIK'),0,$Periode->BeginDate,$Periode->EndDate);
		
		if($Holder!=0)
		{
			$isSAP                         = substr($Holder, 0,1);
			$HolderID                      = substr($Holder, 2);
			$HolderDetail                  = $this->account_model->get_Holder_row($HolderID,$isSAP,$Periode->BeginDate,$Periode->EndDate);
			$link['view_submited_idp']     = 'report/idp_report/total_submitted_idp/'.$isSAP.'/'.$HolderDetail->PositionID;
			$link['view_not_submited_idp'] = 'report/idp_report/total_not_submitted_idp/'.$isSAP.'/'.$HolderDetail->PositionID;
			$link['view_idp_on_time']      = 'report/idp_report/total_idp_realisasi_tepat_waktu/'.$isSAP.'/'.$HolderDetail->PositionID;
			$data_header['link']           = $link;
			$subordinate                   = $this->org_model->get_directSubordinate_list_array($isSAP,$HolderDetail->PositionID,$Periode->BeginDate,$Periode->EndDate);
			

			$i= array();			

			foreach ($subordinate as $row) {
						
						$check_ada_subordinate[$row['UserID']] = $this->org_model->get_directSubordinate_list($row['isSAP'],$row['PositionID'],$Periode->BeginDate,$Periode->EndDate);
						
						//total idp
						$total_idp_1[]                         = $this->report_model->get_all_count_total_idp($row['PositionID'],$isSAP,$Periode->BeginDate,$Periode->EndDate);
						
						//total idp terealisasi
						$total_idp_terealisasi_1[]             = $this->report_model->get_all_count_total_idp_terealisasi($row['PositionID'],$isSAP,$Periode->BeginDate,$Periode->EndDate);
						//total idp not terealisasi
						$total_idp_not_terealisasi_1[]         = $this->report_model->get_all_count_total_idp_not_terealisasi($row['PositionID'],$isSAP,$Periode->BeginDate,$Periode->EndDate);

						$total_idp_terealisasi_tepat_waktu_1[]         = $this->report_model->get_total_idp_terealisasi_tepat_waktu($row['PositionID'],$isSAP,$Periode->BeginDate,$Periode->EndDate);
						
					//chek lagi dia punya sub ordinate ga
					foreach ($check_ada_subordinate[$row['UserID']] as $subordinate_2) {
						$check_ada_subordinate_2[$row['UserID']][$subordinate_2->UserID] = $this->org_model->get_directSubordinate_list($subordinate_2->isSAP,$subordinate_2->PositionID,$Periode->BeginDate,$Periode->EndDate);

						//total idp
						$total_idp_2[]  = $this->report_model->get_all_count_total_idp($subordinate_2->PositionID,$isSAP,$Periode->BeginDate,$Periode->EndDate);
						//total idp terealisasi
						$total_idp_terealisasi_2[]  = $this->report_model->get_all_count_total_idp_terealisasi($subordinate_2->PositionID,$isSAP,$Periode->BeginDate,$Periode->EndDate);
						//total idp not terealisasi
						$total_idp_not_terealisasi_2[]  = $this->report_model->get_all_count_total_idp_not_terealisasi($subordinate_2->PositionID,$isSAP,$Periode->BeginDate,$Periode->EndDate);
						$total_idp_terealisasi_tepat_waktu_2[]         = $this->report_model->get_total_idp_terealisasi_tepat_waktu($subordinate_2->PositionID,$isSAP,$Periode->BeginDate,$Periode->EndDate);
					
						foreach ($check_ada_subordinate_2[$row['UserID']][$subordinate_2->UserID] as $subordinate_3) {
							$check_ada_subordinate_3[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID] = $this->org_model->get_directSubordinate_list($subordinate_3->isSAP,$subordinate_3->PositionID,$Periode->BeginDate,$Periode->EndDate);	

							$total_idp_3[]  = $this->report_model->get_all_count_total_idp($subordinate_3->PositionID,$isSAP,$Periode->BeginDate,$Periode->EndDate);
							//total idp terealisasi
							$total_idp_terealisasi_3[]  = $this->report_model->get_all_count_total_idp_terealisasi($subordinate_3->PositionID,$isSAP,$Periode->BeginDate,$Periode->EndDate);
							//total idp not terealisasi
							$total_idp_not_terealisasi_3[]  = $this->report_model->get_all_count_total_idp_not_terealisasi($subordinate_3->PositionID,$isSAP,$Periode->BeginDate,$Periode->EndDate);
							$total_idp_terealisasi_tepat_waktu_3[]         = $this->report_model->get_total_idp_terealisasi_tepat_waktu($subordinate_3->PositionID,$isSAP,$Periode->BeginDate,$Periode->EndDate);
					
							foreach ($check_ada_subordinate_3[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID] as $subordinate_4) {
								$check_ada_subordinate_4[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID] = $this->org_model->get_directSubordinate_list($subordinate_4->isSAP,$subordinate_4->PositionID,$Periode->BeginDate,$Periode->EndDate);		

									$total_idp_4[] = $this->report_model->get_all_count_total_idp($subordinate_4->PositionID,$isSAP,$Periode->BeginDate,$Periode->EndDate);
									//total idp terealisasi
									$total_idp_terealisasi_4[] = $this->report_model->get_all_count_total_idp_terealisasi($subordinate_4->PositionID,$isSAP,$Periode->BeginDate,$Periode->EndDate);
									//total idp not terealisasi
									$total_idp_not_terealisasi_4[] = $this->report_model->get_all_count_total_idp_not_terealisasi($subordinate_4->PositionID,$isSAP,$Periode->BeginDate,$Periode->EndDate);
									$total_idp_terealisasi_tepat_waktu_4[]         = $this->report_model->get_total_idp_terealisasi_tepat_waktu($subordinate_4->PositionID,$isSAP,$Periode->BeginDate,$Periode->EndDate);


								foreach ($check_ada_subordinate_4[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID]  as $subordinate_5) {
									$check_ada_subordinate_5[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID][$subordinate_5->UserID] = $this->org_model->get_directSubordinate_list($subordinate_5->isSAP,$subordinate_5->PositionID,$Periode->BeginDate,$Periode->EndDate);	
									
										$total_idp_5[] = $this->report_model->get_all_count_total_idp($subordinate_5->PositionID,$isSAP,$Periode->BeginDate,$Periode->EndDate);
										//total idp terealisasi
										$total_idp_terealisasi_5[] = $this->report_model->get_all_count_total_idp_terealisasi($subordinate_5->PositionID,$isSAP,$Periode->BeginDate,$Periode->EndDate);
										//total idp not terealisasi
										$total_idp_not_terealisasi_5[] = $this->report_model->get_all_count_total_idp_not_terealisasi($subordinate_5->PositionID,$isSAP,$Periode->BeginDate,$Periode->EndDate);
										$total_idp_terealisasi_tepat_waktu_5[]         = $this->report_model->get_total_idp_terealisasi_tepat_waktu($subordinate_5->PositionID,$isSAP,$Periode->BeginDate,$Periode->EndDate);
							
									foreach ($check_ada_subordinate_5[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID][$subordinate_5->UserID]  as $subordinate_6) {
										$check_ada_subordinate_6[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID][$subordinate_5->UserID][$subordinate_6->UserID] = $this->org_model->get_directSubordinate_list($subordinate_6->isSAP,$subordinate_6->PositionID,$Periode->BeginDate,$Periode->EndDate);	

										$total_idp_6[] = $this->report_model->get_all_count_total_idp($subordinate_6->PositionID,$isSAP,$Periode->BeginDate,$Periode->EndDate);
										//total idp terealisasi
										$total_idp_terealisasi_6[] = $this->report_model->get_all_count_total_idp_terealisasi($subordinate_6->PositionID,$isSAP,$Periode->BeginDate,$Periode->EndDate);
										//total idp not terealisasi
										$total_idp_not_terealisasi_6[] = $this->report_model->get_all_count_total_idp_not_terealisasi($subordinate_6->PositionID,$isSAP,$Periode->BeginDate,$Periode->EndDate);
										$total_idp_terealisasi_tepat_waktu_6[]         = $this->report_model->get_total_idp_terealisasi_tepat_waktu($subordinate_6->PositionID,$isSAP,$Periode->BeginDate,$Periode->EndDate);

										$v[]=$subordinate_6->UserID;
									}

									$iv[]=$subordinate_5->UserID;
								}

								$iiii[]=$subordinate_4->UserID;
							}

							$iii[]=$subordinate_3->UserID;
						}

						$ii[]=$subordinate_2->UserID;

					}

				$i[]=$row['UserID'];
			}

			
			if(count($i)!=0)
			{
				if(isset($ii)!=0)
				{
					if(isset($iii)!=NULL)
					{
						if(isset($iiii)!=0)
						{
							if(isset($iv)!=0)
							{
					//			$total_idp = count($i)+count($ii)+count($iii)+count($iiii)+count($iv);
							}
							else
							{
					//			$total_idp = count($i)+count($ii)+count($iii)+count($iiii);	
							}
						}
						else
						{
					//		$total_idp = count($i)+count($ii)+count($iii);
						}
					}
					else
					{
					//	$total_idp = count($i)+count($ii);
					}
				}
				else
				{
					//$total_idp = count($i);
				}
			}

			if(isset($total_idp_1)!=NULL)
			{
				$total_idp = array_sum($total_idp_1);
				$total_idp_terealisasi = array_sum($total_idp_terealisasi_1);
				$total_idp_not_terealisasi = array_sum($total_idp_not_terealisasi_1);
				$total_idp_terealisasi_tepat_waktu = array_sum($total_idp_terealisasi_tepat_waktu_1);
			}

			if(isset($check_ada_subordinate))
			{
				$data_header['y']=$check_ada_subordinate;
				if(isset($total_idp_2)!=NULL)
				{
					$total_idp = array_sum($total_idp_1)+array_sum($total_idp_2);
					$total_idp_terealisasi = array_sum($total_idp_terealisasi_1)+array_sum($total_idp_terealisasi_2);
					$total_idp_not_terealisasi = array_sum($total_idp_not_terealisasi_1)+array_sum($total_idp_not_terealisasi_2);
					$total_idp_terealisasi_tepat_waktu = array_sum($total_idp_terealisasi_tepat_waktu_1)+array_sum($total_idp_terealisasi_tepat_waktu_2);
				}

					if(isset($check_ada_subordinate))
					{

						if(isset($total_idp_3)!=NULL)
						{
							$total_idp = array_sum($total_idp_1)+array_sum($total_idp_2)+array_sum($total_idp_3);
							$total_idp_terealisasi = array_sum($total_idp_terealisasi_1)+array_sum($total_idp_terealisasi_2)+array_sum($total_idp_terealisasi_3);
							$total_idp_not_terealisasi = array_sum($total_idp_not_terealisasi_1)+array_sum($total_idp_not_terealisasi_2)+array_sum($total_idp_not_terealisasi_3);
							$total_idp_terealisasi_tepat_waktu = array_sum($total_idp_terealisasi_tepat_waktu_1)+array_sum($total_idp_terealisasi_tepat_waktu_2)+array_sum($total_idp_terealisasi_tepat_waktu_3);
						}

							if(isset($check_ada_subordinate_2))
							{

								if(isset($total_idp_4)!=NULL)
								{
									$total_idp = array_sum($total_idp_1)+array_sum($total_idp_2)+array_sum($total_idp_3)+array_sum($total_idp_4);
									$total_idp_terealisasi = array_sum($total_idp_terealisasi_1)+array_sum($total_idp_terealisasi_2)+array_sum($total_idp_terealisasi_3)+array_sum($total_idp_terealisasi_4);
									$total_idp_not_terealisasi = array_sum($total_idp_not_terealisasi_1)+array_sum($total_idp_not_terealisasi_2)+array_sum($total_idp_not_terealisasi_3)+array_sum($total_idp_not_terealisasi_4);
									$total_idp_terealisasi_tepat_waktu = array_sum($total_idp_terealisasi_tepat_waktu_1)+array_sum($total_idp_terealisasi_tepat_waktu_2)+array_sum($total_idp_terealisasi_tepat_waktu_3)+array_sum($total_idp_terealisasi_tepat_waktu_4);
								}
									if(isset($check_ada_subordinate_3))
									{
										if(isset($total_idp_5)!=NULL)
										{
											$total_idp = array_sum($total_idp_1)+array_sum($total_idp_2)+array_sum($total_idp_3)+array_sum($total_idp_4)+array_sum($total_idp_5);
											$total_idp_terealisasi = array_sum($total_idp_terealisasi_1)+array_sum($total_idp_terealisasi_2)+array_sum($total_idp_terealisasi_3)+array_sum($total_idp_terealisasi_4)+array_sum($total_idp_terealisasi_5);
											$total_idp_not_terealisasi = array_sum($total_idp_not_terealisasi_1)+array_sum($total_idp_not_terealisasi_2)+array_sum($total_idp_not_terealisasi_3)+array_sum($total_idp_not_terealisasi_4)+array_sum($total_idp_not_terealisasi_5);
											$total_idp_terealisasi_tepat_waktu = array_sum($total_idp_terealisasi_tepat_waktu_1)+array_sum($total_idp_terealisasi_tepat_waktu_2)+array_sum($total_idp_terealisasi_tepat_waktu_3)+array_sum($total_idp_terealisasi_tepat_waktu_4)+array_sum($total_idp_terealisasi_tepat_waktu_5);
										}										

											if(isset($check_ada_subordinate_4))
											{
												$data_header['subordinate_4']=$check_ada_subordinate_4;
												$data_header['subordinate_3']=$check_ada_subordinate_3;
												$data_header['subordinate_2']=$check_ada_subordinate_2;	

												if(isset($total_idp_6)!=NULL)
												{
													$total_idp = array_sum($total_idp_1)+array_sum($total_idp_2)+array_sum($total_idp_3)+array_sum($total_idp_5)+array_sum($total_idp_6);
													$total_idp_terealisasi = array_sum($total_idp_terealisasi_1)+array_sum($total_idp_terealisasi_2)+array_sum($total_idp_terealisasi_3)+array_sum($total_idp_terealisasi_4)+array_sum($total_idp_terealisasi_5)+array_sum($total_idp_terealisasi_6);
													$total_idp_not_terealisasi = array_sum($total_idp_not_terealisasi_1)+array_sum($total_idp_not_terealisasi_2)+array_sum($total_idp_not_terealisasi_3)+array_sum($total_idp_not_terealisasi_4)+array_sum($total_idp_not_terealisasi_5)+array_sum($total_idp_not_terealisasi_6);
													$total_idp_terealisasi_tepat_waktu = array_sum($total_idp_terealisasi_tepat_waktu_1)+array_sum($total_idp_terealisasi_tepat_waktu_2)+array_sum($total_idp_terealisasi_tepat_waktu_3)+array_sum($total_idp_terealisasi_tepat_waktu_4)+array_sum($total_idp_terealisasi_tepat_waktu_5)+array_sum($total_idp_terealisasi_tepat_waktu_6);
												}										
											}
									}
							}
					}
			}


			$data_header['subordinate']=$subordinate;
		}
		


		if(isset($total_idp)!=NULL)
		{
			$data_header['total_idp']                         =$total_idp;	
			$data_header['total_idp_terealisasi']             =$total_idp_terealisasi;	
			$data_header['total_idp_not_terealisasi']         =$total_idp_not_terealisasi;
			$data_header['total_idp_terealisasi_tepat_waktu'] =$total_idp_terealisasi_tepat_waktu;
			$data_header['total_average_idp']                 =sprintf("%.0f%%", $total_idp_terealisasi_tepat_waktu/$total_idp * 100);
		}

		$userDetail=$this->account_model->get_User_row($this->session->userdata('userID'));
		$data_header['userDetail']=$userDetail;
		$this->load->view('template/top_1_view');
		$this->load->View('report/idp_report_header_view',$data_header);
		$this->load->View('report/idp_report_header_view_js',$data_header);
		$this->load->view('template/bottom_1_view');
	}
	
	function total_submitted_idp($isSAP,$PositionID)
	{
		$Periode = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$data['Periode']=$Periode;
		$data['Title']="Realization IDP";

		//get subordinate yang sudah submit rkk
		$subordinate=$this->org_model->get_directSubordinate_list_array($isSAP,$PositionID,$Periode->BeginDate,$Periode->EndDate);

		foreach ($subordinate as $row) {

				$check_ada_subordinate[$row['UserID']] = $this->org_model->get_directSubordinate_list($row['isSAP'],$row['PositionID'],$Periode->BeginDate,$Periode->EndDate);
			
				//chek submit rkk 1
				$idp_submitted_i[$row['UserID']]=$this->report_model->get_idp_by_submitted_row_all($row['UserID'],$row['PositionID'],$row['isSAP'],$Periode->BeginDate,$Periode->EndDate);
			
				
				//chek lagi dia punya sub ordinate ga
				foreach ($check_ada_subordinate[$row['UserID']] as $subordinate_2) {
					
					$check_ada_subordinate_2[$row['UserID']][$subordinate_2->UserID] = $this->org_model->get_directSubordinate_list($subordinate_2->isSAP,$subordinate_2->PositionID,$Periode->BeginDate,$Periode->EndDate);
			
					//chek submit rkk 2
					$idp_submitted_ii[$row['UserID']][$subordinate_2->UserID]=$this->report_model->get_idp_by_submitted_row_all($subordinate_2->UserID,$subordinate_2->PositionID,$subordinate_2->isSAP,$Periode->BeginDate,$Periode->EndDate);

					foreach ($check_ada_subordinate_2[$row['UserID']][$subordinate_2->UserID] as $subordinate_3) {
						$check_ada_subordinate_3[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID] = $this->org_model->get_directSubordinate_list($subordinate_3->isSAP,$subordinate_3->PositionID,$Periode->BeginDate,$Periode->EndDate);	

						//chek submit rkk 3
						$idp_submitted_iii[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID]=$this->report_model->get_idp_by_submitted_row_all($subordinate_3->UserID,$subordinate_3->PositionID,$subordinate_3->isSAP,$Periode->BeginDate,$Periode->EndDate);
						
						foreach ($check_ada_subordinate_3[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID] as $subordinate_4) {
							$check_ada_subordinate_4[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID] = $this->org_model->get_directSubordinate_list($subordinate_4->isSAP,$subordinate_4->PositionID,$Periode->BeginDate,$Periode->EndDate);	
						
							//chek submit rkk 4
							$idp_submitted_iv[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID]=$this->report_model->get_idp_by_submitted_row_all($subordinate_4->UserID,$subordinate_4->PositionID,$subordinate_4->isSAP,$Periode->BeginDate,$Periode->EndDate);

							foreach ($check_ada_subordinate_4[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID]  as $subordinate_5) {
								$check_ada_subordinate_5[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID][$subordinate_5->UserID] = $this->org_model->get_directSubordinate_list($subordinate_5->isSAP,$subordinate_5->PositionID,$Periode->BeginDate,$Periode->EndDate);	

								//chek submit rkk 5
								$idp_submitted_v[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID][$subordinate_5->UserID]=$this->report_model->get_idp_by_submitted_row_all($subordinate_5->UserID,$subordinate_5->PositionID,$subordinate_5->isSAP,$Periode->BeginDate,$Periode->EndDate);
								
								foreach ($check_ada_subordinate_5[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID][$subordinate_5->UserID]  as $subordinate_6) {
									$check_ada_subordinate_6[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID][$subordinate_5->UserID][$subordinate_6->UserID] = $this->org_model->get_directSubordinate_list($subordinate_6->isSAP,$subordinate_6->PositionID,$Periode->BeginDate,$Periode->EndDate);	

									//chek submit rkk 6
									$idp_submitted_vi[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID][$subordinate_5->UserID][$subordinate_6->UserID]=$this->report_model->get_idp_by_submitted_row_all($subordinate_6->UserID,$subordinate_6->PositionID,$subordinate_6->isSAP,$Periode->BeginDate,$Periode->EndDate);

									$v[]=$subordinate_6->UserID;
								}
								
								$iv[]=$subordinate_5->UserID;
							}

							$iiii[]=$subordinate_4->UserID;
						}

						$iii[]=$subordinate_3->UserID;
					}

					$ii[]=$subordinate_2->UserID;

				}
			}


		$data['subordinate']=$subordinate;
		if(isset($check_ada_subordinate))
			{
				$data['y']=$check_ada_subordinate;

					if(isset($check_ada_subordinate_2))
					{
						$data['subordinate_2']=$check_ada_subordinate_2;
						if(isset($check_ada_subordinate_3))
						{
							$data['subordinate_3']=$check_ada_subordinate_3;
							if(isset($check_ada_subordinate_4))
							{
								$data['subordinate_4']=$check_ada_subordinate_4;
										
								if(isset($check_ada_subordinate_5))
								{
									$data['subordinate_5']=$check_ada_subordinate_5;
									if(isset($check_ada_subordinate_6))
									{
										$data['subordinate_6']=$check_ada_subordinate_6;
									}
								}
							}
						}
					}
			}



		if(isset($idp_submitted_i)!=0)
		{
			$data['subordinate_submit_1']=$idp_submitted_i;

			if(isset($idp_submitted_ii)!=0)
			{
				$data['subordinate_submit_2']=$idp_submitted_ii;


				if(isset($idp_submitted_iii)!=0)
				{
					$data['subordinate_submit_3']=$idp_submitted_iii;

					if(isset($idp_submitted_iv)!=0)
					{
						$data['subordinate_submit_4']=$idp_submitted_iv;

						if(isset($idp_submitted_v)!=0)
						{
							$data['subordinate_submit_5']=$idp_submitted_v;

							if(isset($idp_submitted_vi)!=0)
							{
								$data['subordinate_submit_6']=$idp_submitted_vi;
							}
						}
					}
				}		
			}
		}
		
		$this->load->view('template/top_popup_1_view');
		$this->load->view('report/idp_report_submit_pop_up',$data);
		$this->load->view('template/bottom_popup_1_view');
	}



	function total_not_submitted_idp($isSAP,$PositionID)
	{
		$Periode = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$data['Periode']=$Periode;
		$data['Title']="Not Yet Realization IDP";

		//get subordinate yang sudah submit rkk
		$subordinate=$this->org_model->get_directSubordinate_list_array($isSAP,$PositionID,$Periode->BeginDate,$Periode->EndDate);

		foreach ($subordinate as $row) {

				$check_ada_subordinate[$row['UserID']] = $this->org_model->get_directSubordinate_list($row['isSAP'],$row['PositionID'],$Periode->BeginDate,$Periode->EndDate);
			
				//chek submit rkk 1
				$idp_not_submitted_i[$row['UserID']]=$this->report_model->get_idp_by_not_submitted_row_all($row['UserID'],$row['PositionID'],$row['isSAP'],$Periode->BeginDate,$Periode->EndDate);
				
				
				//chek lagi dia punya sub ordinate ga
				foreach ($check_ada_subordinate[$row['UserID']] as $subordinate_2) {


					$check_ada_subordinate_2[$row['UserID']][$subordinate_2->UserID] = $this->org_model->get_directSubordinate_list($subordinate_2->isSAP,$subordinate_2->PositionID,$Periode->BeginDate,$Periode->EndDate);
			
					//chek submit rkk 2
					$idp_not_submitted_ii[$row['UserID']][$subordinate_2->UserID]=$this->report_model->get_idp_by_not_submitted_row_all($subordinate_2->UserID,$subordinate_2->PositionID,$subordinate_2->isSAP,$Periode->BeginDate,$Periode->EndDate);
				
					foreach ($check_ada_subordinate_2[$row['UserID']][$subordinate_2->UserID] as $subordinate_3) {
						$check_ada_subordinate_3[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID] = $this->org_model->get_directSubordinate_list($subordinate_3->isSAP,$subordinate_3->PositionID,$Periode->BeginDate,$Periode->EndDate);	

						//chek submit rkk 3
						$idp_not_submitted_iii[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID]=$this->report_model->get_idp_by_not_submitted_row_all($subordinate_3->UserID,$subordinate_3->PositionID,$subordinate_3->isSAP,$Periode->BeginDate,$Periode->EndDate);
						
						foreach ($check_ada_subordinate_3[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID] as $subordinate_4) {
							$check_ada_subordinate_4[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID] = $this->org_model->get_directSubordinate_list($subordinate_4->isSAP,$subordinate_4->PositionID,$Periode->BeginDate,$Periode->EndDate);	
						
							//chek submit rkk 4
							$idp_not_submitted_iv[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID]=$this->report_model->get_idp_by_not_submitted_row_all($subordinate_4->UserID,$subordinate_4->PositionID,$subordinate_4->isSAP,$Periode->BeginDate,$Periode->EndDate);
				
							foreach ($check_ada_subordinate_4[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID]  as $subordinate_5) {
								$check_ada_subordinate_5[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID][$subordinate_5->UserID] = $this->org_model->get_directSubordinate_list($subordinate_5->isSAP,$subordinate_5->PositionID,$Periode->BeginDate,$Periode->EndDate);	

								//chek submit rkk 5
								$idp_not_submitted_v[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID][$subordinate_5->UserID]=$this->report_model->get_idp_by_not_submitted_row_all($subordinate_5->UserID,$subordinate_5->PositionID,$subordinate_5->isSAP,$Periode->BeginDate,$Periode->EndDate);
								
								foreach ($check_ada_subordinate_5[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID][$subordinate_5->UserID]  as $subordinate_6) {
									$check_ada_subordinate_6[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID][$subordinate_5->UserID][$subordinate_6->UserID] = $this->org_model->get_directSubordinate_list($subordinate_6->isSAP,$subordinate_6->PositionID,$Periode->BeginDate,$Periode->EndDate);	

									//chek submit rkk 6
									$idp_submitted_vi[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID][$subordinate_5->UserID][$subordinate_6->UserID]=$this->report_model->get_idp_by_submitted_row_all($subordinate_6->UserID,$subordinate_6->PositionID,$subordinate_6->isSAP,$Periode->BeginDate,$Periode->EndDate);
				
									$v[]=$subordinate_6->UserID;
								}
								
								$iv[]=$subordinate_5->UserID;
							}

							$iiii[]=$subordinate_4->UserID;
						}

						$iii[]=$subordinate_3->UserID;
					}

					$ii[]=$subordinate_2->UserID;

				}
			}

			
		$data['subordinate']=$subordinate;


		
		if(isset($check_ada_subordinate))
			{
				$data['y']=$check_ada_subordinate;

					if(isset($check_ada_subordinate_2))
					{
						$data['subordinate_2']=$check_ada_subordinate_2;
						if(isset($check_ada_subordinate_3))
						{
							$data['subordinate_3']=$check_ada_subordinate_3;
							if(isset($check_ada_subordinate_4))
							{
								$data['subordinate_4']=$check_ada_subordinate_4;
										
								if(isset($check_ada_subordinate_5))
								{
									$data['subordinate_5']=$check_ada_subordinate_5;
									if(isset($check_ada_subordinate_6))
									{
										$data['subordinate_6']=$check_ada_subordinate_6;
									}
								}
							}
						}
					}
			}




		if(isset($idp_not_submitted_i)!=0)
		{
			$data['subordinate_not_submit_1']=$idp_not_submitted_i;

			if(isset($idp_not_submitted_ii)!=0)
			{
				$data['subordinate_not_submit_2']=$idp_not_submitted_ii;

				if(isset($idp_not_submitted_iii)!=0)
				{
					$data['subordinate_not_submit_3']=$idp_not_submitted_iii;

					if(isset($idp_not_submitted_iv)!=0)
					{
						$data['subordinate_not_submit_4']=$idp_not_submitted_iv;

						if(isset($idp_not_submitted_v)!=0)
						{
							//var_dump($rkk_submitted_v);
							$data['subordinate_not_submit_5']=$idp_not_submitted_v;

							if(isset($idp_not_submitted_vi)!=0)
							{
								$data['subordinate_not_submit_6']=$idp_not_submitted_vi;
							}
						}
					}
				}		
			}
		}
		
		$this->load->view('template/top_popup_1_view');
		$this->load->view('report/idp_report_not_submit_pop_up',$data);
		$this->load->view('template/bottom_popup_1_view');
	}

	/**
	 * [Mengambil data idp yang tepat waktu]
	 * @param  [type] $isSAP      [description]
	 * @param  [type] $PositionID [description]
	 * @return [type]             [description]
	 */
	function total_idp_realisasi_tepat_waktu($isSAP,$PositionID)
	{
		$Periode = $this->general_model->get_Period_row($this->session->userdata('active_period'));
		$data['Periode']=$Periode;
		$data['Title']="Realization IDP On Time";
		//get subordinate yang sudah submit rkk
		$subordinate=$this->org_model->get_directSubordinate_list_array($isSAP,$PositionID,$Periode->BeginDate,$Periode->EndDate);

		foreach ($subordinate as $row) {

				$check_ada_subordinate[$row['UserID']] = $this->org_model->get_directSubordinate_list($row['isSAP'],$row['PositionID'],$Periode->BeginDate,$Periode->EndDate);
			
				//chek submit rkk 1
				$idp_submitted_i[$row['UserID']]=$this->report_model->get_all_idp_terealisasi_tepat_waktu($row['PositionID'],$row['isSAP'],$Periode->BeginDate,$Periode->EndDate);
			
				
				//chek lagi dia punya sub ordinate ga
				foreach ($check_ada_subordinate[$row['UserID']] as $subordinate_2) {
					
					$check_ada_subordinate_2[$row['UserID']][$subordinate_2->UserID] = $this->org_model->get_directSubordinate_list($subordinate_2->isSAP,$subordinate_2->PositionID,$Periode->BeginDate,$Periode->EndDate);
			
					//chek submit rkk 2
					$idp_submitted_ii[$row['UserID']][$subordinate_2->UserID]=$this->report_model->get_all_idp_terealisasi_tepat_waktu($subordinate_2->PositionID,$subordinate_2->isSAP,$Periode->BeginDate,$Periode->EndDate);

					foreach ($check_ada_subordinate_2[$row['UserID']][$subordinate_2->UserID] as $subordinate_3) {
						$check_ada_subordinate_3[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID] = $this->org_model->get_directSubordinate_list($subordinate_3->isSAP,$subordinate_3->PositionID,$Periode->BeginDate,$Periode->EndDate);	

						//chek submit rkk 3
						$idp_submitted_iii[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID]=$this->report_model->get_all_idp_terealisasi_tepat_waktu($subordinate_3->PositionID,$subordinate_3->isSAP,$Periode->BeginDate,$Periode->EndDate);
						
						foreach ($check_ada_subordinate_3[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID] as $subordinate_4) {
							$check_ada_subordinate_4[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID] = $this->org_model->get_directSubordinate_list($subordinate_4->isSAP,$subordinate_4->PositionID,$Periode->BeginDate,$Periode->EndDate);	
						
							//chek submit rkk 4
							$idp_submitted_iv[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID]=$this->report_model->get_all_idp_terealisasi_tepat_waktu($subordinate_4->PositionID,$subordinate_4->isSAP,$Periode->BeginDate,$Periode->EndDate);

							foreach ($check_ada_subordinate_4[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID]  as $subordinate_5) {
								$check_ada_subordinate_5[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID][$subordinate_5->UserID] = $this->org_model->get_directSubordinate_list($subordinate_5->isSAP,$subordinate_5->PositionID,$Periode->BeginDate,$Periode->EndDate);	

								//chek submit rkk 5
								$idp_submitted_v[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID][$subordinate_5->UserID]=$this->report_model->get_all_idp_terealisasi_tepat_waktu($subordinate_5->PositionID,$subordinate_5->isSAP,$Periode->BeginDate,$Periode->EndDate);
								
								foreach ($check_ada_subordinate_5[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID][$subordinate_5->UserID]  as $subordinate_6) {
									$check_ada_subordinate_6[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID][$subordinate_5->UserID][$subordinate_6->UserID] = $this->org_model->get_directSubordinate_list($subordinate_6->isSAP,$subordinate_6->PositionID,$Periode->BeginDate,$Periode->EndDate);	

									//chek submit rkk 6
									$idp_submitted_vi[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID][$subordinate_5->UserID][$subordinate_6->UserID]=$this->report_model->get_all_idp_terealisasi_tepat_waktu($subordinate_6->PositionID,$subordinate_6->isSAP,$Periode->BeginDate,$Periode->EndDate);

									$v[]=$subordinate_6->UserID;
								}
								
								$iv[]=$subordinate_5->UserID;
							}

							$iiii[]=$subordinate_4->UserID;
						}

						$iii[]=$subordinate_3->UserID;
					}

					$ii[]=$subordinate_2->UserID;

				}
			}


		$data['subordinate']=$subordinate;
		if(isset($check_ada_subordinate))
			{
				$data['y']=$check_ada_subordinate;

					if(isset($check_ada_subordinate_2))
					{
						$data['subordinate_2']=$check_ada_subordinate_2;
						if(isset($check_ada_subordinate_3))
						{
							$data['subordinate_3']=$check_ada_subordinate_3;
							if(isset($check_ada_subordinate_4))
							{
								$data['subordinate_4']=$check_ada_subordinate_4;
										
								if(isset($check_ada_subordinate_5))
								{
									$data['subordinate_5']=$check_ada_subordinate_5;
									if(isset($check_ada_subordinate_6))
									{
										$data['subordinate_6']=$check_ada_subordinate_6;
									}
								}
							}
						}
					}
			}



		if(isset($idp_submitted_i)!=0)
		{
			$data['subordinate_submit_1']=$idp_submitted_i;

			if(isset($idp_submitted_ii)!=0)
			{
				$data['subordinate_submit_2']=$idp_submitted_ii;

				if(isset($idp_submitted_iii)!=0)
				{
					$data['subordinate_submit_3']=$idp_submitted_iii;

					if(isset($idp_submitted_iv)!=0)
					{
						$data['subordinate_submit_4']=$idp_submitted_iv;

						if(isset($idp_submitted_v)!=0)
						{
							$data['subordinate_submit_5']=$idp_submitted_v;

							if(isset($idp_submitted_vi)!=0)
							{
								$data['subordinate_submit_6']=$idp_submitted_vi;
							}
						}
					}
				}		
			}
		}
		
		$this->load->view('template/top_popup_1_view');
		$this->load->view('report/idp_report_realization_on_time_pop_up',$data);
		$this->load->view('template/bottom_popup_1_view');
	}

}