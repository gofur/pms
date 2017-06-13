<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Aspect_model extends Model {
	function __construct(){
		parent::__construct();
		$this->portal=$this->load->database('portal', TRUE);
		$this->pms = $this->load->database('default', TRUE);
		
	}

	function count_setting($organization_id,$begin_date,$end_date)
	{
		$query="SELECT COUNT(*) as val 
						FROM tb_m_aspect_setting 
						WHERE organization_id=$organization_id AND 
							((begin_date >= '$begin_date' AND end_date <='$end_date') OR 
 							(end_date >= '$end_date' AND end_date <= '$end_date') OR 
 							(begin_date >= '$begin_date' AND begin_date <='$end_date' ) OR
 							(begin_date <= '$begin_date' AND end_date >= '$end_date'))";
		return $this->pms->query($query)->row()->val;
	}

	function get_setting_list($is_sap,$organization_id,$begin_date,$end_date,$aspect='all')
	{

		$count_setting = $this->count_setting($organization_id,$begin_date,$end_date);
		if($count_setting==0) {
			if ($is_sap) {
 				$table = "Core_M_Organization_SAP";
	 		} else {
	 			$table = "Core_M_Organization_nonSAP";
	 		}

	 		if ($begin_date == '') {
	 			$begin_date = date('Y-m-d');
	 		}

	 		if ($end_date == '') {
	 			$end_date = date('Y-m-d');
	 		}

	 		$query = "SELECT TOP 1 *
 							FROM $table
 							WHERE OrganizationID = $organization_id AND
 							 	((BeginDate >= '$begin_date' AND EndDate <='$end_date') OR 
 								(EndDate >= '$begin_date' AND EndDate <= '$end_date') OR 
 								(BeginDate >= '$begin_date' AND BeginDate <='$end_date' ) OR
 								(BeginDate <= '$begin_date' AND EndDate >= '$end_date'))
							ORDER BY EndDate DESC, BeginDate DESC";
			$organization_parent = $this->pms->query($query)->row()->OrganizationParent;
			$result=$this->get_setting_list($is_sap,$organization_parent,$begin_date,$end_date,$aspect);
		}	else {
			switch ($aspect) {
				case 'all':
					$query="SELECT s.*,
										s.percentage as [percent], 
										a.label 
									FROM tb_m_aspect_setting s 
									JOIN tb_m_aspect a 
										ON a.aspect_id = s.aspect_id 
									WHERE s.organization_id = $organization_id AND 
										((s.begin_date >= '$begin_date' AND s.end_date <='$end_date') OR 
		 								(s.end_date >= '$end_date' AND s.end_date <= '$end_date') OR 
		 								(s.begin_date >= '$begin_date' AND s.begin_date <='$end_date' ) OR
		 								(s.begin_date <= '$begin_date' AND s.end_date >= '$end_date'))
									ORDER BY aspect_id";
					# code...
					break;
				case 'biz':
					$query="SELECT  s.*,
										s.percentage as [percent],
										a.label 
									FROM tb_m_aspect_setting s 
									JOIN tb_m_aspect a 
										ON a.aspect_id = s.aspect_id 
									WHERE s.aspect_id = 1 AND s.organization_id = $organization_id AND 
										((s.begin_date >= '$begin_date' AND s.end_date <='$end_date') OR 
		 								(s.end_date >= '$end_date' AND s.end_date <= '$end_date') OR 
		 								(s.begin_date >= '$begin_date' AND s.begin_date <='$end_date' ) OR
		 								(s.begin_date <= '$begin_date' AND s.end_date >= '$end_date'))
									ORDER BY aspect_id";
					break;
				case 'non':
					$query="SELECT s.*, 
										s.percentage as [percent], 
										a.label 
									FROM tb_m_aspect_setting s
									JOIN tb_m_aspect a 
										ON a.aspect_id = s.aspect_id 
									WHERE s.aspect_id <> 1 AND s.organization_id = $organization_id AND 
										((s.begin_date >= '$begin_date' AND s.end_date <='$end_date') OR 
		 								(s.end_date >= '$end_date' AND s.end_date <= '$end_date') OR 
		 								(s.begin_date >= '$begin_date' AND s.begin_date <='$end_date' ) OR
		 								(s.begin_date <= '$begin_date' AND s.end_date >= '$end_date'))
									ORDER BY aspect_id";
					break;
			}

			return $this->pms->query($query)->result();
		}

		return $result;
	}

}

/* End of file ascpect_model.php */
/* Location: ./application/models/ascpect_model.php */