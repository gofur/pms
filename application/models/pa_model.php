<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pa_model extends Model {
	function __construct()
	{
		parent::__construct();
		$this->pms = $this->load->database('default', TRUE);
		
 	}
	public function count_emp($pers_admin='',$is_sap=TRUE,$unit_id='',$begin='',$end='')
	{
		if ($is_sap) {
			$table_0 = ' Core_M_Holder_SAP h ';
			$table_1 = ' Core_M_Position_SAP p ';
		} else {
			$table_0 = ' Core_M_Holder_nonSAP h ';
			$table_1 = ' Core_M_Position_nonSAP p ';
		}
		$query = "SELECT count(*) AS val 
							FROM Core_M_User u 
								INNER JOIN $table_0 
								ON h.NIK = u.NIK 
								INNER JOIN $table_1
								ON p.PositionID = h.PositionID
							WHERE u.PersAdmin = '$pers_admin' AND 
								p.UnitID = '$unit_id' AND 
								((p.BeginDate >= '$begin' AND p.EndDate <='$end') OR 
 								(p.EndDate >= '$begin' AND p.EndDate <= '$end') OR 
 								(p.BeginDate >= '$begin' AND p.BeginDate <='$end' ) OR
 								(p.BeginDate <= '$begin' AND p.EndDate >= '$end')) AND 
								((h.BeginDate >= '$begin' AND h.EndDate <='$end') OR 
 								(h.EndDate >= '$begin' AND h.EndDate <= '$end') OR 
 								(h.BeginDate >= '$begin' AND h.BeginDate <='$end' ) OR
 								(h.BeginDate <= '$begin' AND h.EndDate >= '$end'))";

		return $this->pms->query($query)->row()->val;
	}

	public function get_emp_list($pers_admin='',$is_sap=TRUE,$unit_id='',$begin='',$end='')
	{
		if ($is_sap) {
			$table_0 = ' Core_M_Holder_SAP h ';
			$table_1 = ' Core_M_Position_SAP p ';
		} else {
			$table_0 = ' Core_M_Holder_nonSAP h ';
			$table_1 = ' Core_M_Position_nonSAP p ';
		}
		$query = "SELECT
								DISTINCT(u.NIK)
							FROM Core_M_User u 
								INNER JOIN $table_0 
								ON h.NIK = u.NIK 
								INNER JOIN $table_1
								ON p.PositionID = h.PositionID
							WHERE u.PersAdmin = '$pers_admin' AND 
								p.UnitID = '$unit_id' AND 
								((p.BeginDate >= '$begin' AND p.EndDate <='$end') OR 
 								(p.EndDate >= '$begin' AND p.EndDate <= '$end') OR 
 								(p.BeginDate >= '$begin' AND p.BeginDate <='$end' ) OR
 								(p.BeginDate <= '$begin' AND p.EndDate >= '$end')) AND 
								((h.BeginDate >= '$begin' AND h.EndDate <='$end') OR 
 								(h.EndDate >= '$begin' AND h.EndDate <= '$end') OR 
 								(h.BeginDate >= '$begin' AND h.BeginDate <='$end' ) OR
 								(h.BeginDate <= '$begin' AND h.EndDate >= '$end'))
 							ORDER BY NIK";
		return $this->pms->query($query)->result();
	}

}

/* End of file pa_model.php */
/* Location: ./application/models/pa_model.php */