<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class rkk_report extends Controller {
	function __construct(){
		parent::__construct();
		if(!$this->session->userdata('loginFlag')){
			redirect('account/login');
		}

		$this->load->model('rkk_model');
		$this->load->model('general_model');
		$this->load->model('org_model');
		$this->load->model('account_model');
		$this->load->model('report_model');
	}
	function index()
	{
		$Periode = $this->general_model->get_ActivePeriode();
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
			$isSAP=substr($Holder, 0,1);
			$HolderID=substr($Holder, 2);
			$HolderDetail = $this->account_model->get_Holder_row($HolderID,$isSAP,$Periode->BeginDate,$Periode->EndDate);
			$link['view_submited_rkk']='report/rkk_report/total_submitted_rkk/'.$isSAP.'/'.$HolderDetail->PositionID;
			$link['view_not_submited_rkk']='report/rkk_report/total_not_submitted_rkk/'.$isSAP.'/'.$HolderDetail->PositionID;
			$data_header['link']=$link;

			$RKK = $this->rkk_model->get_rkk_byUserPosition_row($this->session->userdata('userID'),$HolderDetail->PositionID,$Periode->BeginDate,$Periode->EndDate) ;
			$subordinate=$this->org_model->get_directSubordinate_list_array($isSAP,$HolderDetail->PositionID,$Periode->BeginDate,$Periode->EndDate);

			$i= array();
			
			foreach ($subordinate as $row) {

				$check_ada_subordinate[$row['UserID']] = $this->org_model->get_directSubordinate_list($row['isSAP'],$row['PositionID'],$Periode->BeginDate,$Periode->EndDate);
			
				//chek submit rkk 1
				$rkk_submitted_i[$row['UserID']]=$this->report_model->get_rkk_by_submitted_row_all($row['UserID'],$row['PositionID'],$row['isSAP'],$Periode->BeginDate,$Periode->EndDate);
			
				foreach ($rkk_submitted_i[$row['UserID']] as $value) {
						$total_submit_1[] = $value->NIK;
				}


				//chek lagi dia punya sub ordinate ga
				foreach ($check_ada_subordinate[$row['UserID']] as $subordinate_2) {
					$check_ada_subordinate_2[$row['UserID']][$subordinate_2->UserID] = $this->org_model->get_directSubordinate_list($subordinate_2->isSAP,$subordinate_2->PositionID,$Periode->BeginDate,$Periode->EndDate);
				
					//chek submit rkk 2
					$rkk_submitted_ii[$row['UserID']][$subordinate_2->UserID]=$this->report_model->get_rkk_by_submitted_row_all($subordinate_2->UserID,$subordinate_2->PositionID,$subordinate_2->isSAP,$Periode->BeginDate,$Periode->EndDate);
					

					foreach ($rkk_submitted_ii[$row['UserID']][$subordinate_2->UserID] as $value) {

						$total_submit_2[] = $value->NIK;
						
					}

				
					foreach ($check_ada_subordinate_2[$row['UserID']][$subordinate_2->UserID] as $subordinate_3) {
						$check_ada_subordinate_3[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID] = $this->org_model->get_directSubordinate_list($subordinate_3->isSAP,$subordinate_3->PositionID,$Periode->BeginDate,$Periode->EndDate);	

						//chek submit rkk 3
						$rkk_submitted_iii[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID]=$this->report_model->get_rkk_by_submitted_row_all($subordinate_3->UserID,$subordinate_3->PositionID,$subordinate_3->isSAP,$Periode->BeginDate,$Periode->EndDate);
					
						foreach ($rkk_submitted_iii[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID] as $value) {

							$total_submit_3[] = $value->NIK;
							
						}

				
						foreach ($check_ada_subordinate_3[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID] as $subordinate_4) {
							$check_ada_subordinate_4[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID] = $this->org_model->get_directSubordinate_list($subordinate_4->isSAP,$subordinate_4->PositionID,$Periode->BeginDate,$Periode->EndDate);	
					
							//chek submit rkk 4
							$rkk_submitted_iv[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID]=$this->report_model->get_rkk_by_submitted_row_all($subordinate_4->UserID,$subordinate_4->PositionID,$subordinate_4->isSAP,$Periode->BeginDate,$Periode->EndDate);
					
							foreach ($rkk_submitted_iv[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID] as $value) {

								$total_submit_4[] = $value->NIK;
								
							}

							foreach ($check_ada_subordinate_4[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID]  as $subordinate_5) {
								$check_ada_subordinate_5[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID][$subordinate_5->UserID] = $this->org_model->get_directSubordinate_list($subordinate_5->isSAP,$subordinate_5->PositionID,$Periode->BeginDate,$Periode->EndDate);	
								//chek submit rkk 5
								$rkk_submitted_v[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID][$subordinate_5->UserID]=$this->report_model->get_rkk_by_submitted_row_all($subordinate_5->UserID,$subordinate_5->PositionID,$subordinate_5->isSAP,$Periode->BeginDate,$Periode->EndDate);
					
								foreach ($rkk_submitted_v[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID][$subordinate_5->UserID] as $value) {

									$total_submit_5[] = $value->NIK;
									
								}

						
								foreach ($check_ada_subordinate_5[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID][$subordinate_5->UserID]  as $subordinate_6) {
									$check_ada_subordinate_6[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID][$subordinate_5->UserID][$subordinate_6->UserID] = $this->org_model->get_directSubordinate_list($subordinate_6->isSAP,$subordinate_6->PositionID,$Periode->BeginDate,$Periode->EndDate);	

									//chek submit rkk 6
									$rkk_submitted_vi[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID][$subordinate_5->UserID][$subordinate_6->UserID]=$this->report_model->get_rkk_by_submitted_row_all($subordinate_6->UserID,$subordinate_6->PositionID,$subordinate_6->isSAP,$Periode->BeginDate,$Periode->EndDate);
				
									foreach ($rkk_submitted_v[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID][$subordinate_5->UserID][$subordinate_6->UserID] as $value) {

										$total_submit_6[] = $value->NIK;
										
									}

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
								$total_rkk = count($i)+count($ii)+count($iii)+count($iiii)+count($iv);
							}
							else
							{
								$total_rkk = count($i)+count($ii)+count($iii)+count($iiii);	
							}
						}
						else
						{
							$total_rkk = count($i)+count($ii)+count($iii);
						}
					}
					else
					{
						$total_rkk = count($i)+count($ii);
					}
				}
				else
				{
					$total_rkk = count($i);	
				}

					$data_header['total_rkk']=$total_rkk;	

					if(isset($rkk_submitted_ii)!=0)
					{
						if(isset($total_submit_1)==NULL)
						{
							$total_all_submit=count($total_submit_2);
						}
						else
						{
							$total_all_submit=count($total_submit_1)+count($total_submit_2);	
						}
						$total_average_rkk=$total_all_submit/$total_rkk;
						$data_header['total_rkk_submitted']=$total_all_submit;
						$data_header['total_rkk_not_submitted']=$total_rkk-$total_all_submit;
						$data_header['total_average_rkk']=sprintf("%.0f%%", $total_average_rkk * 100);
						
						if(isset($rkk_submitted_iii)!=0)
						{
							if(isset($total_submit_1)==NULL AND isset($total_submit_2)==NULL)
							{
								$total_all_submit=count($total_submit_3);
							}else
							{
								$total_all_submit=count($total_submit_1)+count($total_submit_2)+count($total_submit_3);
							}
							$total_average_rkk=$total_all_submit/$total_rkk;
							$data_header['total_rkk_submitted']=$total_all_submit;
							$data_header['total_rkk_not_submitted']=$total_rkk-$total_all_submit;
							$data_header['total_average_rkk']=sprintf("%.0f%%", $total_average_rkk * 100);


							if(isset($rkk_submitted_iv)!=0)
							{
								if(isset($total_submit_1)==NULL AND isset($total_submit_2)==NULL AND isset($total_submit_3)==NULL)
								{
									$total_all_submit=count($total_submit_4);
								}else
								{
									$total_all_submit=count($total_submit_1)+count($total_submit_2)+count($total_submit_3)+count($total_submit_4);	
								}
								
								$total_average_rkk=$total_all_submit/$total_rkk;
								$data_header['total_rkk_submitted']=$total_all_submit;
								$data_header['total_rkk_not_submitted']=$total_rkk-$total_all_submit;
								$data_header['total_average_rkk']=sprintf("%.0f%%", $total_average_rkk * 100);

								if(isset($rkk_submitted_v)!=0)
								{
									if(isset($total_submit_1)==NULL AND isset($total_submit_2)==NULL AND isset($total_submit_3)==NULL AND isset($total_submit_4)==NULL)
									{
										$total_all_submit=count($total_submit_5);
									}else
									{
										$total_all_submit=count($total_submit_1)+count($total_submit_2)+count($total_submit_3)+count($total_submit_4)+count($total_submit_5);
									}
									
									$total_average_rkk=$total_all_submit/$total_rkk;
									$data_header['total_rkk_submitted']=$total_all_submit;
									$data_header['total_rkk_not_submitted']=$total_rkk-$total_all_submit;
									$data_header['total_average_rkk']=sprintf("%.0f%%", $total_average_rkk * 100);

									if(isset($rkk_submitted_vi)!=0)
									{
										$data_header['total_rkk_submitted']=count($total_submit_1)+count($total_submit_2)+count($total_submit_3)+count($total_submit_4)+count($total_submit_5)+count($total_submit_6);
										$data_header['total_rkk_not_submitted']=$total_rkk-count($total_submit_2)+count($total_submit_3)+count($total_submit_4)+count($total_submit_5)+count($total_submit_6);
									}
								}
							}
						}		
					}
					else
					{
						$total_all_submit=count($total_submit_1);
						$total_average_rkk=$total_all_submit/$total_rkk;
						$data_header['total_rkk_submitted']=count($total_submit_1);
						$data_header['total_rkk_not_submitted']=$total_rkk-count($total_submit_1);
						$data_header['total_average_rkk']=sprintf("%.0f%%", $total_average_rkk * 100);
					}
			}

			if(isset($check_ada_subordinate))
			{
				$data_header['y']=$check_ada_subordinate;

				if(isset($check_ada_subordinate))
				{
					if(isset($check_ada_subordinate_2))
					{
						if(isset($check_ada_subordinate_3))
						{
							if(isset($check_ada_subordinate_4))
							{
								$data_header['subordinate_4']=$check_ada_subordinate_4;
								$data_header['subordinate_3']=$check_ada_subordinate_3;
								$data_header['subordinate_2']=$check_ada_subordinate_2;	
							}
						}
					}
				}
			}
			$data_header['subordinate']=$subordinate;
		}
		
		
		$userDetail=$this->account_model->get_User_row($this->session->userdata('userID'));
		$data_header['userDetail']=$userDetail;
		$this->load->view('template/top_1_view');
		$this->load->View('report/rkk_report_header_view',$data_header);
		$this->load->View('report/rkk_report_header_view_js',$data_header);
		$this->load->view('template/bottom_1_view');
	}
	
	function total_submitted_rkk($isSAP,$PositionID)
	{
		$Periode = $this->general_model->get_ActivePeriode();
		$data['Periode']=$Periode;
		$data['Title']="RKK already submit";

		//get subordinate yang sudah submit rkk
		$subordinate=$this->org_model->get_directSubordinate_list_array($isSAP,$PositionID,$Periode->BeginDate,$Periode->EndDate);

		foreach ($subordinate as $row) {

				$check_ada_subordinate[$row['UserID']] = $this->org_model->get_directSubordinate_list($row['isSAP'],$row['PositionID'],$Periode->BeginDate,$Periode->EndDate);
			
				//chek submit rkk 1
				$rkk_submitted_i[$row['UserID']]=$this->report_model->get_rkk_by_submitted_row_all($row['UserID'],$row['PositionID'],$row['isSAP'],$Periode->BeginDate,$Periode->EndDate);
			
				
				//chek lagi dia punya sub ordinate ga
				foreach ($check_ada_subordinate[$row['UserID']] as $subordinate_2) {
					
					$check_ada_subordinate_2[$row['UserID']][$subordinate_2->UserID] = $this->org_model->get_directSubordinate_list($subordinate_2->isSAP,$subordinate_2->PositionID,$Periode->BeginDate,$Periode->EndDate);
			
					//chek submit rkk 2
					$rkk_submitted_ii[$row['UserID']][$subordinate_2->UserID]=$this->report_model->get_rkk_by_submitted_row_all($subordinate_2->UserID,$subordinate_2->PositionID,$subordinate_2->isSAP,$Periode->BeginDate,$Periode->EndDate);

					foreach ($check_ada_subordinate_2[$row['UserID']][$subordinate_2->UserID] as $subordinate_3) {
						$check_ada_subordinate_3[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID] = $this->org_model->get_directSubordinate_list($subordinate_3->isSAP,$subordinate_3->PositionID,$Periode->BeginDate,$Periode->EndDate);	

						//chek submit rkk 3
						$rkk_submitted_iii[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID]=$this->report_model->get_rkk_by_submitted_row_all($subordinate_3->UserID,$subordinate_3->PositionID,$subordinate_3->isSAP,$Periode->BeginDate,$Periode->EndDate);
						
						foreach ($check_ada_subordinate_3[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID] as $subordinate_4) {
							$check_ada_subordinate_4[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID] = $this->org_model->get_directSubordinate_list($subordinate_4->isSAP,$subordinate_4->PositionID,$Periode->BeginDate,$Periode->EndDate);	
						
							//chek submit rkk 4
							$rkk_submitted_iv[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID]=$this->report_model->get_rkk_by_submitted_row_all($subordinate_4->UserID,$subordinate_4->PositionID,$subordinate_4->isSAP,$Periode->BeginDate,$Periode->EndDate);

							foreach ($check_ada_subordinate_4[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID]  as $subordinate_5) {
								$check_ada_subordinate_5[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID][$subordinate_5->UserID] = $this->org_model->get_directSubordinate_list($subordinate_5->isSAP,$subordinate_5->PositionID,$Periode->BeginDate,$Periode->EndDate);	

								//chek submit rkk 5
								$rkk_submitted_v[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID][$subordinate_5->UserID]=$this->report_model->get_rkk_by_submitted_row_all($subordinate_5->UserID,$subordinate_5->PositionID,$subordinate_5->isSAP,$Periode->BeginDate,$Periode->EndDate);
								
								foreach ($check_ada_subordinate_5[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID][$subordinate_5->UserID]  as $subordinate_6) {
									$check_ada_subordinate_6[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID][$subordinate_5->UserID][$subordinate_6->UserID] = $this->org_model->get_directSubordinate_list($subordinate_6->isSAP,$subordinate_6->PositionID,$Periode->BeginDate,$Periode->EndDate);	

									//chek submit rkk 6
									$rkk_submitted_vi[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID][$subordinate_5->UserID][$subordinate_6->UserID]=$this->report_model->get_rkk_by_submitted_row_all($subordinate_6->UserID,$subordinate_6->PositionID,$subordinate_6->isSAP,$Periode->BeginDate,$Periode->EndDate);

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



		if(isset($rkk_submitted_i)!=0)
		{
			$data['subordinate_submit_1']=$rkk_submitted_i;

			if(isset($rkk_submitted_ii)!=0)
			{
				$data['subordinate_submit_2']=$rkk_submitted_ii;
				
				if(isset($rkk_submitted_iii)!=0)
				{
					$data['subordinate_submit_3']=$rkk_submitted_iii;

					if(isset($rkk_submitted_iv)!=0)
					{
						$data['subordinate_submit_4']=$rkk_submitted_iv;

						if(isset($rkk_submitted_v)!=0)
						{
							$data['subordinate_submit_5']=$rkk_submitted_v;

							if(isset($rkk_submitted_vi)!=0)
							{
								$data['subordinate_submit_6']=$rkk_submitted_vi;
							}
						}
					}
				}		
			}
		}
		
		$this->load->view('template/top_popup_1_view');
		$this->load->view('report/rkk_report_submit_pop_up',$data);
		$this->load->view('template/bottom_popup_1_view');
	}



	function total_not_submitted_rkk($isSAP,$PositionID)
	{
		$Periode = $this->general_model->get_ActivePeriode();
		$data['Periode']=$Periode;
		$data['Title']="Not Yet Submit RKK";

		//get subordinate yang sudah submit rkk
		$subordinate=$this->org_model->get_directSubordinate_list_array($isSAP,$PositionID,$Periode->BeginDate,$Periode->EndDate);

		foreach ($subordinate as $row) {

				$check_ada_subordinate[$row['UserID']] = $this->org_model->get_directSubordinate_list($row['isSAP'],$row['PositionID'],$Periode->BeginDate,$Periode->EndDate);
			
				//chek submit rkk 1
				$rkk_not_submitted_i[$row['UserID']]=$this->report_model->get_rkk_by_not_submitted_row_all($row['UserID'],$row['PositionID'],$row['isSAP'],$Periode->BeginDate,$Periode->EndDate);
				$rkk_submitted_i[$row['UserID']]=$this->report_model->get_rkk_by_submitted_row_all($row['UserID'],$row['PositionID'],$row['isSAP'],$Periode->BeginDate,$Periode->EndDate);
			
				
				//chek lagi dia punya sub ordinate ga
				foreach ($check_ada_subordinate[$row['UserID']] as $subordinate_2) {


					$check_ada_subordinate_2[$row['UserID']][$subordinate_2->UserID] = $this->org_model->get_directSubordinate_list($subordinate_2->isSAP,$subordinate_2->PositionID,$Periode->BeginDate,$Periode->EndDate);
			
					//chek submit rkk 2
					$rkk_not_submitted_ii[$row['UserID']][$subordinate_2->UserID]=$this->report_model->get_rkk_by_not_submitted_row_all($subordinate_2->UserID,$subordinate_2->PositionID,$subordinate_2->isSAP,$Periode->BeginDate,$Periode->EndDate);
					$rkk_submitted_ii[$row['UserID']][$subordinate_2->UserID]=$this->report_model->get_rkk_by_submitted_row_all($subordinate_2->UserID,$subordinate_2->PositionID,$subordinate_2->isSAP,$Periode->BeginDate,$Periode->EndDate);

					foreach ($check_ada_subordinate_2[$row['UserID']][$subordinate_2->UserID] as $subordinate_3) {
						$check_ada_subordinate_3[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID] = $this->org_model->get_directSubordinate_list($subordinate_3->isSAP,$subordinate_3->PositionID,$Periode->BeginDate,$Periode->EndDate);	

						//chek submit rkk 3
						$rkk_not_submitted_iii[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID]=$this->report_model->get_rkk_by_not_submitted_row_all($subordinate_3->UserID,$subordinate_3->PositionID,$subordinate_3->isSAP,$Periode->BeginDate,$Periode->EndDate);
						$rkk_submitted_iii[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID]=$this->report_model->get_rkk_by_submitted_row_all($subordinate_3->UserID,$subordinate_3->PositionID,$subordinate_3->isSAP,$Periode->BeginDate,$Periode->EndDate);
						
						foreach ($check_ada_subordinate_3[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID] as $subordinate_4) {
							$check_ada_subordinate_4[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID] = $this->org_model->get_directSubordinate_list($subordinate_4->isSAP,$subordinate_4->PositionID,$Periode->BeginDate,$Periode->EndDate);	
						
							//chek submit rkk 4
							$rkk_not_submitted_iv[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID]=$this->report_model->get_rkk_by_not_submitted_row_all($subordinate_4->UserID,$subordinate_4->PositionID,$subordinate_4->isSAP,$Periode->BeginDate,$Periode->EndDate);
							$rkk_submitted_iv[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID]=$this->report_model->get_rkk_by_submitted_row_all($subordinate_4->UserID,$subordinate_4->PositionID,$subordinate_4->isSAP,$Periode->BeginDate,$Periode->EndDate);

							foreach ($check_ada_subordinate_4[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID]  as $subordinate_5) {
								$check_ada_subordinate_5[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID][$subordinate_5->UserID] = $this->org_model->get_directSubordinate_list($subordinate_5->isSAP,$subordinate_5->PositionID,$Periode->BeginDate,$Periode->EndDate);	

								//chek submit rkk 5
								$rkk_not_submitted_v[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID][$subordinate_5->UserID]=$this->report_model->get_rkk_by_not_submitted_row_all($subordinate_5->UserID,$subordinate_5->PositionID,$subordinate_5->isSAP,$Periode->BeginDate,$Periode->EndDate);
								$rkk_submitted_v[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID][$subordinate_5->UserID]=$this->report_model->get_rkk_by_submitted_row_all($subordinate_5->UserID,$subordinate_5->PositionID,$subordinate_5->isSAP,$Periode->BeginDate,$Periode->EndDate);
								
								foreach ($check_ada_subordinate_5[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID][$subordinate_5->UserID]  as $subordinate_6) {
									$check_ada_subordinate_6[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID][$subordinate_5->UserID][$subordinate_6->UserID] = $this->org_model->get_directSubordinate_list($subordinate_6->isSAP,$subordinate_6->PositionID,$Periode->BeginDate,$Periode->EndDate);	

									//chek submit rkk 6
									$rkk_submitted_vi[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID][$subordinate_5->UserID][$subordinate_6->UserID]=$this->report_model->get_rkk_by_submitted_row_all($subordinate_6->UserID,$subordinate_6->PositionID,$subordinate_6->isSAP,$Periode->BeginDate,$Periode->EndDate);
									$rkk_not_submitted_vi[$row['UserID']][$subordinate_2->UserID][$subordinate_3->UserID][$subordinate_4->UserID][$subordinate_5->UserID][$subordinate_6->UserID]=$this->report_model->get_rkk_by_not_submitted_row_all($subordinate_6->UserID,$subordinate_6->PositionID,$subordinate_6->isSAP,$Periode->BeginDate,$Periode->EndDate);

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




		if(isset($rkk_submitted_i)!=0)
		{
			$data['subordinate_submit_1']=$rkk_submitted_i;
			$data['subordinate_not_submit_1']=$rkk_not_submitted_i;



			if(isset($rkk_submitted_ii)!=0)
			{
				$data['subordinate_submit_2']=$rkk_submitted_ii;
				$data['subordinate_not_submit_2']=$rkk_not_submitted_ii;
				

				if(isset($rkk_submitted_iii)!=0)
				{
					$data['subordinate_submit_3']=$rkk_submitted_iii;
					$data['subordinate_not_submit_3']=$rkk_not_submitted_iii;

					if(isset($rkk_submitted_iv)!=0)
					{
						$data['subordinate_submit_4']=$rkk_submitted_iv;
						$data['subordinate_not_submit_4']=$rkk_not_submitted_iv;

						if(isset($rkk_submitted_v)!=0)
						{
							//var_dump($rkk_submitted_v);
							$data['subordinate_submit_5']=$rkk_submitted_v;
							$data['subordinate_not_submit_5']=$rkk_not_submitted_v;

							if(isset($rkk_submitted_vi)!=0)
							{
								$data['subordinate_submit_6']=$rkk_submitted_vi;
								$data['subordinate_not_submit_6']=$rkk_not_submitted_vi;
							}
						}
					}
				}		
			}
		}
		
		$this->load->view('template/top_popup_1_view');
		$this->load->view('report/rkk_report_not_submit_pop_up',$data);
		$this->load->view('template/bottom_popup_1_view');
	}

}