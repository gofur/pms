<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Om_model extends Model {

	function __construct()
	{
		parent::__construct();
		$this->pms = $this->load->database('default', TRUE);

 	}
	 /////////////////////
 	// Organisasi HR  //
	 /////////////////////

 	public function get_hr_org_list($is_sap=0,$pers_admin = 0,$begin='',$end='')
 	{
 		if ($is_sap) {
 			$table = "Core_M_Organization_SAP";
 		} else {
 			$table = "Core_M_Organization_nonSAP";
 		}

 		$query = "SELECT o.*
 							FROM hr_m_org hr
 							INNER JOIN $table o
 								ON hr.Org_ID = o.OrganizationID
 							WHERE hr.isActive = 1 AND
 								PersAdmin_ID = '$pers_admin' AND
 								((o.BeginDate >= '$begin' AND o.EndDate <='$end') OR
 								(o.EndDate >= '$begin' AND o.EndDate <= '$end') OR
 								(o.BeginDate >= '$begin' AND o.BeginDate <='$end' ) OR
 								(o.BeginDate <= '$begin' AND o.EndDate >= '$end'))
							ORDER BY OrganizationName ASC";
		return $this->pms->query($query)->result();

 	}
	 //////////////////
 	// Organisasi  //
	 //////////////////

 	/**
 	 * [menghitung daftar organisasi yang ada di suatu waktu]
 	 * @param  boolean $is_sap [SAP/nonSAP]
 	 * @param  integer $parent [organization ID Parent; default = 0 ]
 	 * @param  string  $begin  [tanggal mulai]
 	 * @param  string  $end    [tangal selesai]
 	 * @return [type]          [description]
 	 */
 	public function count_org_byParent($is_sap=TRUE,$parent=0,$begin='',$end='')
 	{
 		if ($is_sap) {
 			$table = "Core_M_Organization_SAP";
 		} else {
 			$table = "Core_M_Organization_nonSAP";
 		}

 		if ($begin == '') {
 			$begin = date('Y-m-d');
 		}

 		if ($end == '') {
 			$end = date('Y-m-d');
 		}

 		$query = "SELECT count(*) as val
 							FROM $table
 							WHERE OrganizationParent = $parent AND
 							 	((BeginDate >= '$begin' AND EndDate <='$end') OR
 								(EndDate >= '$begin' AND EndDate <= '$end') OR
 								(BeginDate >= '$begin' AND BeginDate <='$end' ) OR
 								(BeginDate <= '$begin' AND EndDate >= '$end'))";
		return $this->pms->query($query)->row()->val;
 	}

 	/**
 	 * [menghitung daftar history / pergerakan oragnaisasi]
 	 * @param  boolean $is_sap [description]
 	 * @param  integer $org_id [description]
 	 * @param  string  $begin  [description]
 	 * @param  string  $end    [description]
 	 * @return [type]          [description]
 	 */
 	public function count_org_byID($is_sap=TRUE,$org_id=0,$begin='',$end='')
 	{
 		if ($is_sap) {
 			$table = "Core_M_Organization_SAP";
 		} else {
 			$table = "Core_M_Organization_nonSAP";
 		}

 		if ($begin == '') {
 			$begin = date('Y-m-d');
 		}

 		if ($end == '') {
 			$end = date('Y-m-d');
 		}

 		$query = "SELECT count(*) as val
 							FROM $table
 							WHERE OrganizationID = $org_id AND
 							 	((BeginDate >= '$begin' AND EndDate <='$end') OR
 								(EndDate >= '$begin' AND EndDate <= '$end') OR
 								(BeginDate >= '$begin' AND BeginDate <='$end' ) OR
 								(BeginDate <= '$begin' AND EndDate >= '$end'))";
		return $this->pms->query($query)->row()->val;
 	}

 	/**
 	 * [mendapatkan daftar organisasi dalam suatu waktu]
 	 * @param  boolean $is_sap [description]
 	 * @param  integer $parent [description]
 	 * @param  string  $begin  [description]
 	 * @param  string  $end    [description]
 	 * @return [type]          [description]
 	 */
 	public function get_org_byParent_list($is_sap=TRUE,$parent=0,$begin='',$end='')
 	{
 		if ($is_sap) {
 			$table = "Core_M_Organization_SAP";
 		} else {
 			$table = "Core_M_Organization_nonSAP";
 		}

 		if ($begin == '') {
 			$begin = date('Y-m-d');
 		}

 		if ($end == '') {
 			$end = date('Y-m-d');
 		}
		if ($parent != 0) {
			$query = "SELECT *
	 							FROM $table
	 							WHERE OrganizationParent = $parent AND
	 							 	((BeginDate >= '$begin' AND EndDate <='$end') OR
	 								(EndDate >= '$begin' AND EndDate <= '$end') OR
	 								(BeginDate >= '$begin' AND BeginDate <='$end' ) OR
	 								(BeginDate <= '$begin' AND EndDate >= '$end'))
								ORDER BY OrganizationName ASC";
		} else {
			$query = "SELECT *
	 							FROM $table
	 							WHERE OrganizationID = '50002147' AND
	 							 	((BeginDate >= '$begin' AND EndDate <='$end') OR
	 								(EndDate >= '$begin' AND EndDate <= '$end') OR
	 								(BeginDate >= '$begin' AND BeginDate <='$end' ) OR
	 								(BeginDate <= '$begin' AND EndDate >= '$end'))
								ORDER BY OrganizationName ASC";
		}

		return $this->pms->query($query)->result();
 	}

 	/**
 	 * [mendafpatkan daftar history/pergerakan suatu organisasi]
 	 * @param  boolean $is_sap [description]
 	 * @param  integer $org_id [description]
 	 * @param  string  $begin  [description]
 	 * @param  string  $end    [description]
 	 * @return [type]          [description]
 	 */
 	public function get_org_byID_list($is_sap=TRUE,$org_id=0,$begin='',$end='')
 	{
 		if ($is_sap) {
 			$table = "Core_M_Organization_SAP";
 		} else {
 			$table = "Core_M_Organization_nonSAP";
 		}

 		if ($begin == '') {
 			$begin = date('Y-m-d');
 		}

 		if ($end == '') {
 			$end = date('Y-m-d');
 		}

 		if (! is_array($org_id)) {
	 		$query = "SELECT *
	 							FROM $table
	 							WHERE OrganizationID = $org_id AND
	 							 	((BeginDate >= '$begin' AND EndDate <='$end') OR
	 								(EndDate >= '$begin' AND EndDate <= '$end') OR
	 								(BeginDate >= '$begin' AND BeginDate <='$end' ) OR
	 								(BeginDate <= '$begin' AND EndDate >= '$end')) ORDER BY OrganizationName ASC";
 		} else {
 			$org_list = implode(',', $org_id);
 			$query = "SELECT *
	 							FROM $table
	 							WHERE OrganizationID IN ( $org_list) AND
	 							 	((BeginDate >= '$begin' AND EndDate <='$end') OR
	 								(EndDate >= '$begin' AND EndDate <= '$end') OR
	 								(BeginDate >= '$begin' AND BeginDate <='$end' ) OR
	 								(BeginDate <= '$begin' AND EndDate >= '$end')) ORDER BY OrganizationName ASC";

 		}

		return $this->pms->query($query)->result();
 	}

 	public function get_org_tree_list($is_sap=TRUE,$org_id=0,$begin='',$end='',$result=array())
 	{
 		if ($is_sap) {
 			$table = "Core_M_Organization_SAP";
 		} else {
 			$table = "Core_M_Organization_nonSAP";
 		}

 		if ($begin == '') {
 			$begin = date('Y-m-d');
 		}

 		if ($end == '') {
 			$end = date('Y-m-d');
 		}
		$org = $this->get_org_row($is_sap,$org_id,$begin,$end);
		$result[] = array(
			'id'   => $org->OrganizationID,
			'name' => $org->OrganizationName,
			'begda' => $org->BeginDate,
			'endda' => $org->EndDate,
		);
 		$c_org = $this->count_org_byParent($is_sap,$org_id,$begin,$end);
 		if ($c_org > 0) {
 			$org_list = $this->get_org_byParent_list($is_sap,$org_id,$begin,$end);
 			foreach ($org_list as $row) {
 				$result = $this->get_org_tree_list($is_sap,$row->OrganizationID,$begin,$end,$result);
 			}
 		}

 		$count_arr = count($result);
 		for ($i=0; $i < $count_arr ; $i++) {
 			$_result[$i] = (object) $result[$i];
 		}
 		return $_result;
 	}

 	/**
 	 * [mendapatkan atribut suatu organisasi]
 	 * @param  boolean $is_sap [description]
 	 * @param  integer $org_id [description]
 	 * @param  string  $begin  [description]
 	 * @param  string  $end    [description]
 	 * @return [type]          [description]
 	 */
 	public function get_org_row($is_sap=TRUE,$org_id=0,$begin='',$end='')
 	{
 		if ($is_sap) {
 			$table = "Core_M_Organization_SAP";
 		} else {
 			$table = "Core_M_Organization_nonSAP";
 		}

 		if ($begin == '') {
 			$begin = date('Y-m-d');
 		}

 		if ($end == '') {
 			$end = date('Y-m-d');
 		}

 		$query = "SELECT TOP 1 *
 							FROM $table
 							WHERE OrganizationID = $org_id AND
 							 	((BeginDate >= '$begin' AND EndDate <='$end') OR
 								(EndDate >= '$begin' AND EndDate <= '$end') OR
 								(BeginDate >= '$begin' AND BeginDate <='$end' ) OR
 								(BeginDate <= '$begin' AND EndDate >= '$end'))
							ORDER BY EndDate DESC, BeginDate DESC";
		return $this->pms->query($query)->row();
 	}

 	/**
 	 * [menambahkan organisasi]
 	 * @param boolean $is_sap   [description]
 	 * @param integer $org_id   [description]
 	 * @param string  $org_name [description]
 	 * @param integer $parent   [description]
 	 * @param string  $begin    [description]
 	 * @param string  $end      [description]
 	 */
 	public function add_org($is_sap=FALSE,$org_id=0,$org_name='',$parent=0,$begin='',$end='9999-12-31')
 	{
 		if ($is_sap) {
 			$table = "Core_M_Organization_SAP";
 		} else {
 			$table = "Core_M_Organization_nonSAP";
 		}
 		$query = "INSERT INTO $table
           ([OrganizationID]
           ,[ObjectType]
           ,[OrganizationName]
           ,[OrganizationParent]
           ,[BeginDate]
           ,[EndDate])
     VALUES
           ($org_id
           ,'O'
           ,'$org_name'
           ,$parent
           ,'$begin'
           ,'$end')";
		$this->pms->query($query);

 	}

 	/**
 	 * [mengubah data organisasi]
 	 * @param  boolean $is_sap    [description]
 	 * @param  integer $org_id    [description]
 	 * @param  string  $old_begin [description]
 	 * @param  string  $old_end   [description]
 	 * @param  string  $org_name  [description]
 	 * @param  integer $parent    [description]
 	 * @param  string  $new_begin [description]
 	 * @param  string  $new_end   [description]
 	 * @return [type]             [description]
 	 */
 	public function edit_org($is_sap=FALSE,$org_id=0,$old_begin='',$old_end='9999-12-31',$org_name='',$parent=0,$new_begin='',$new_end='9999-12-31')
 	{
 		if ($is_sap) {
 			$table = "Core_M_Organization_SAP";
 		} else {
 			$table = "Core_M_Organization_nonSAP";
 		}
 		$query = "UPDATE $table
							   SET [OrganizationName] = '$org_name'
							      ,[OrganizationParent] = $parent
							      ,[BeginDate] = '$new_begin'
							      ,[EndDate] = '$new_end'
							 WHERE [OrganizationID] = $org_id AND
								[BeginDate] = '$old_begin' AND
					      [EndDate] = '$old_end'";
		$this->pms->query($query);
 	}

 	/**
 	 * [menghapus data organisasi]
 	 * @param  boolean $is_sap    [description]
 	 * @param  integer $org_id    [description]
 	 * @param  string  $old_begin [description]
 	 * @param  string  $old_end   [description]
 	 * @return [type]             [description]
 	 */
 	public function remove_org($is_sap=FALSE,$org_id=0,$old_begin='',$old_end='')
 	{
 		if ($is_sap) {
 			$table = "Core_M_Organization_SAP";
 		} else {
 			$table = "Core_M_Organization_nonSAP";
 		}

 		$query = "DELETE FROM $table WHERE [OrganizationID] = $org_id AND
								[BeginDate] = '$old_begin' AND
					      [EndDate] = '$old_end' ";
		$this->pms->query($query);
 	}

 	/**
 	 * [mengubah tanggal akhir aktif organisasi]
 	 * @param  boolean $is_sap    [description]
 	 * @param  integer $org_id    [description]
 	 * @param  string  $old_begin [description]
 	 * @param  string  $old_end   [description]
 	 * @param  string  $new_end   [description]
 	 * @return [type]             [description]
 	 */
 	public function delimit_org($is_sap=FALSE,$org_id=0,$old_begin='',$old_end='',$new_end='')
 	{
 		if ($is_sap) {
 			$table = "Core_M_Organization_SAP";
 		} else {
 			$table = "Core_M_Organization_nonSAP";
 		}
 		$query = "UPDATE $table
							   SET [EndDate] = '$new_end'
							 WHERE [OrganizationID] = $org_id AND
								[BeginDate] = '$old_begin' AND
					      [EndDate] = '$old_end'";
		$this->pms->query($query);
 	}

	 /////////////
 	// Posisi //
	 /////////////

	/**
	 * [menghitung posisi dalam satu organisasi]
	 * @param  boolean $is_sap [description]
	 * @param  integer $org_id [description]
	 * @param  string  $begin  [description]
	 * @param  string  $end    [description]
	 * @return [type]          [description]
	 */
	public function count_post_byOrg($is_sap = TRUE, $org_id = 0, $begin='',$end='',$status='all')
	{
		if ($is_sap) {
 			$table = "Core_M_Position_SAP";
 		} else {
 			$table = "Core_M_Position_nonSAP";
 		}

 		if ($begin == '') {
 			$begin = date('Y-m-d');
 		}

 		if ($end == '') {
 			$end = date('Y-m-d');
 		}

 		$query = "SELECT COUNT(*) as val
 							FROM $table
 							WHERE OrganizationID = $org_id AND
 							 	((BeginDate >= '$begin' AND EndDate <='$end') OR
 								(EndDate >= '$begin' AND EndDate <= '$end') OR
 								(BeginDate >= '$begin' AND BeginDate <='$end' ) OR
 								(BeginDate <= '$begin' AND EndDate >= '$end'))";
		if (is_numeric($status) && ($status == 0 OR $status == 1 OR $status == 2)) {
			$query .= " AND Chief = $status";
		}
		return $this->pms->query($query)->row()->val;
	}

	/**
	 * [menghitung posisi dalam suatu waktu]
	 * @param  boolean $is_sap  [description]
	 * @param  integer $post_id [description]
	 * @param  string  $begin   [description]
	 * @param  string  $end     [description]
	 * @return [type]           [description]
	 */
	public function count_post_byID($is_sap = TRUE, $post_id = 0, $begin='',$end='', $status ='all')
	{
		if ($is_sap) {
 			$table = "Core_M_Position_SAP";
 		} else {
 			$table = "Core_M_Position_nonSAP";
 		}

 		if ($begin == '') {
 			$begin = date('Y-m-d');
 		}

 		if ($end == '') {
 			$end = date('Y-m-d');
 		}

 		$query = "SELECT COUNT(*) as val
 							FROM $table
 							WHERE PositionID = $post_id AND
 							 	((BeginDate >= '$begin' AND EndDate <='$end') OR
 								(EndDate >= '$begin' AND EndDate <= '$end') OR
 								(BeginDate >= '$begin' AND BeginDate <='$end' ) OR
 								(BeginDate <= '$begin' AND EndDate >= '$end'))";
		if (is_numeric($status) && ($status == 0 OR $status == 1 OR $status == 2)) {
			$query .= " AND Chief = $status";
		}
		return $this->pms->query($query)->row()->val;
	}

	/**
	 * [menghitung jumlah posisi sesuai dengan status fullfillmentnya]
	 * @param  boolean $is_sap [description]
	 * @param  integer $org_id [description]
	 * @param  string  $begin  [description]
	 * @param  string  $end    [description]
	 * @param  string  $status [description]
	 * @return [type]          [description]
	 */
	public function count_post_byFF($is_sap = TRUE, $org_id = 0, $begin = '', $end = '', $status='vacant')
	{
		if ($is_sap) {
 			$table_1 = "Core_M_Position_SAP";
 			$table_2 = "Core_M_Holder_SAP";
 		} else {
 			$table_1 = "Core_M_Position_nonSAP";
 			$table_2 = "Core_M_Holder_nonSAP";
 		}

 		if ($begin == '') {
 			$begin = date('Y-m-d');
 		}

 		if ($end == '') {
 			$end = date('Y-m-d');
 		}
 		$query = "SELECT COUNT(*) AS val
 							FROM $table_1
 							WHERE OrganizationID = $org_id AND
								((BeginDate >= '$begin' AND EndDate <='$end') OR
 								(EndDate >= '$begin' AND EndDate <= '$end') OR
 								(BeginDate >= '$begin' AND BeginDate <='$end' ) OR
 								(BeginDate <= '$begin' AND EndDate >= '$end'))";

		switch (strtolower($status)) {
			case 'vacant':
				$query .= " AND PositionID NOT IN (
 								SELECT PositionID
 								FROM $table_2
 								WHERE ((BeginDate >= '$begin' AND EndDate <='$end') OR
 									(EndDate >= '$begin' AND EndDate <= '$end') OR
 									(BeginDate >= '$begin' AND BeginDate <='$end' ) OR
 									(BeginDate <= '$begin' AND EndDate >= '$end')))";
				break;
			case 'fullfill':
				$query .= " AND PositionID IN (
 								SELECT PositionID
 								FROM $table_2
 								WHERE ((BeginDate >= '$begin' AND EndDate <='$end') OR
 									(EndDate >= '$begin' AND EndDate <= '$end') OR
 									(BeginDate >= '$begin' AND BeginDate <='$end' ) OR
 									(BeginDate <= '$begin' AND EndDate >= '$end')))";
				break;

		}

		return $this->pms->query($query)->row()->val;
	}

	/**
	 * [mendapatkan daftar posisi berdasarkan organisasi]
	 * @param  boolean $is_sap  [description]
	 * @param  integer $org_id  [description]
	 * @param  string  $begin   [description]
	 * @param  string  $end     [description]
	 * @return [type]           [description]
	 */
	public function get_post_byOrg_list($is_sap = TRUE, $org_id = 0, $begin='',$end='',$status='all')
	{
		if ($is_sap) {
 			$table = "Core_M_Position_SAP";
 		} else {
 			$table = "Core_M_Position_nonSAP";
 		}

 		if ($begin == '') {
 			$begin = date('Y-m-d');
 		}

 		if ($end == '') {
 			$end = date('Y-m-d');
 		}
 		$query = "SELECT *
 							FROM $table
 							WHERE OrganizationID = $org_id AND
 							 	((BeginDate >= '$begin' AND EndDate <='$end') OR
 								(EndDate >= '$begin' AND EndDate <= '$end') OR
 								(BeginDate >= '$begin' AND BeginDate <='$end' ) OR
 								(BeginDate <= '$begin' AND EndDate >= '$end'))";
		if (is_numeric($status) && ($status == 0 OR $status == 1 OR $status == 2)) {
			$query .= " AND Chief = $status";
		}
		return $this->pms->query($query)->result();
	}

	/**
	 * [mendapatkan daftar posisi berdasarkan ID]
	 * @param  boolean $is_sap  [description]
	 * @param  integer $post_id [description]
	 * @param  string  $begin   [description]
	 * @param  string  $end     [description]
	 * @return [type]           [description]
	 */
	public function get_post_byID_list($is_sap = TRUE, $post_id = 0, $begin='',$end='',$status='all')
	{
		if ($is_sap) {
 			$table = "Core_M_Position_SAP";
 		} else {
 			$table = "Core_M_Position_nonSAP";
 		}

 		if ($begin == '') {
 			$begin = date('Y-m-d');
 		}

 		if ($end == '') {
 			$end = date('Y-m-d');
 		}
 		$query = "SELECT *
 							FROM $table
 							WHERE PositionID = $post_id AND
 							 	((BeginDate >= '$begin' AND EndDate <='$end') OR
 								(EndDate >= '$begin' AND EndDate <= '$end') OR
 								(BeginDate >= '$begin' AND BeginDate <='$end' ) OR
 								(BeginDate <= '$begin' AND EndDate >= '$end'))";
		if (is_numeric($status) && ($status == 0 OR $status == 1 OR $status == 2)) {
			$query .= " AND Chief = $status";
		}
		return $this->pms->query($query)->result();
	}

	/**
	 * [mendapatkan daftar posisi sesuai status Fullfillmentnya]
	 * @param  boolean $is_sap [description]
	 * @param  integer $org_id [description]
	 * @param  string  $begin  [description]
	 * @param  string  $end    [description]
	 * @param  string  $status [description]
	 * @return [type]          [description]
	 */
	public function get_post_byFF_list($is_sap = TRUE, $org_id = 0, $begin = '', $end = '', $status='vacant')
	{
		if ($is_sap) {
 			$table_1 = "Core_M_Position_SAP";
 			$table_2 = "Core_M_Holder_SAP";
 		} else {
 			$table_1 = "Core_M_Position_nonSAP";
 			$table_2 = "Core_M_Holder_nonSAP";
 		}

 		if ($begin == '') {
 			$begin = date('Y-m-d');
 		}

 		if ($end == '') {
 			$end = date('Y-m-d');
 		}
 		$query = "SELECT *
 							FROM $table_1
 							WHERE OrganizationID = $org_id AND
								((BeginDate >= '$begin' AND EndDate <='$end') OR
 								(EndDate >= '$begin' AND EndDate <= '$end') OR
 								(BeginDate >= '$begin' AND BeginDate <='$end' ) OR
 								(BeginDate <= '$begin' AND EndDate >= '$end'))";

		switch (strtolower($status)) {
			case 'vacant':
				$query .= " AND PositionID NOT IN (
 								SELECT PositionID
 								FROM $table_2
 								WHERE ((BeginDate >= '$begin' AND EndDate <='$end') OR
 									(EndDate >= '$begin' AND EndDate <= '$end') OR
 									(BeginDate >= '$begin' AND BeginDate <='$end' ) OR
 									(BeginDate <= '$begin' AND EndDate >= '$end')))";
				break;
			case 'fullfill':
				$query .= " AND PositionID IN (
 								SELECT PositionID
 								FROM $table_2
 								WHERE ((BeginDate >= '$begin' AND EndDate <='$end') OR
 									(EndDate >= '$begin' AND EndDate <= '$end') OR
 									(BeginDate >= '$begin' AND BeginDate <='$end' ) OR
 									(BeginDate <= '$begin' AND EndDate >= '$end')))";
				break;

		}
		return $this->pms->query($query)->result();
	}

	public function get_post_tree_byOrg_list($is_sap = TRUE, $org_id = 0, $begin='',$end='',$status='all')
	{
		if ($is_sap) {
 			$table = "Core_M_Position_SAP";
 		} else {
 			$table = "Core_M_Position_nonSAP";
 		}

 		if ($begin == '') {
 			$begin = date('Y-m-d');
 		}

 		if ($end == '') {
 			$end = date('Y-m-d');
 		}

 		$org_list = $this->get_org_tree_list($is_sap,$org_id,$begin,$end);
 		$org_ls = '';
 		foreach ($org_list as $row) {
 			$org_ls .= ', '.$row->id;
 		}
 		$org_ls = substr($org_ls, 1);
 		$query = "SELECT *
 							FROM $table
 							WHERE OrganizationID IN ($org_ls) AND
 							 	((BeginDate >= '$begin' AND EndDate <='$end') OR
 								(EndDate >= '$begin' AND EndDate <= '$end') OR
 								(BeginDate >= '$begin' AND BeginDate <='$end' ) OR
 								(BeginDate <= '$begin' AND EndDate >= '$end'))";
		if (is_numeric($status) && ($status == 0 OR $status == 1 OR $status == 2)) {
			$query .= " AND Chief = $status";
		}
		return $this->pms->query($query)->result();
	}
	/**
	 * [medapatkan data posisi]
	 * @param  boolean $is_sap  [description]
	 * @param  integer $post_id [description]
	 * @param  string  $begin   [description]
	 * @param  string  $end     [description]
	 * @return [type]           [description]
	 */
	public function get_post_row($is_sap = TRUE, $post_id = 0, $begin='',$end='')
	{
		if ($is_sap) {
 			$table = "Core_M_Position_SAP";
 		} else {
 			$table = "Core_M_Position_nonSAP";
 		}

 		if ($begin == '') {
 			$begin = date('Y-m-d');
 		}

		if ($end == '') {
 			$end = date('Y-m-d');
 		}
 		$query = "SELECT TOP 1 *
 							FROM $table
 							WHERE PositionID = $post_id AND
 								((BeginDate >= '$begin' AND EndDate <='$end') OR
 								(EndDate >= '$begin' AND EndDate <= '$end') OR
 								(BeginDate >= '$begin' AND BeginDate <='$end' ) OR
 								(BeginDate <= '$begin' AND EndDate >= '$end'))
							ORDER BY EndDate DESC, BeginDate DESC";
		return $this->pms->query($query)->row();

	}
	/**
	 * [add_post description]
	 * @param boolean $is_sap     [description]
	 * @param integer $post_id    [description]
	 * @param string  $post_name  [description]
	 * @param integer $org_id     [description]
	 * @param integer $status     [description]
	 * @param string  $post_group [description]
	 * @param string  $begin      [description]
	 * @param string  $end        [description]
	 * @param integer $unit_id    [description]
	 */
	public function add_post($is_sap=FALSE, $post_id=0,$post_name='',$org_id=0,$status=0,$post_group='-',$begin='',$end='9999-12-31',$unit_id=0)
	{
		if ($is_sap) {
 			$table = "Core_M_Position_SAP";
 		} else {
 			$table = "Core_M_Position_nonSAP";
 		}

 		$query = "INSERT INTO $table
           ([PositionID]
           ,[ObjectType]
           ,[PositionName]
           ,[OrganizationID]
           ,[Chief]
           ,[PositionGroup]
           ,[BeginDate]
           ,[EndDate]
           ,[UnitID])
     VALUES
           ($post_id
           ,'P'
           ,'$post_name'
           ,$org_id
           ,'$status'
           ,'$post_group'
           ,'$begin'
           ,'$end'
           ,'$unit_id')";
		$this->pms->query($query);
	}

	/**
	 * [edit_post description]
	 * @param  boolean $is_sap     [description]
	 * @param  integer $post_id    [description]
	 * @param  string  $old_begin  [description]
	 * @param  string  $old_end    [description]
	 * @param  string  $post_name  [description]
	 * @param  integer $org_id     [description]
	 * @param  integer $status     [description]
	 * @param  string  $post_group [description]
	 * @param  string  $new_begin  [description]
	 * @param  string  $new_end    [description]
	 * @param  integer $unit_id    [description]
	 * @return [type]              [description]
	 */
	public function edit_post($is_sap=FALSE, $post_id=0,$old_begin='',$old_end='',$post_name='',$org_id=0,$status=0,$post_group='-',$new_begin='',$new_end='',$unit_id=0)
	{
		if ($is_sap) {
 			$table = "Core_M_Position_SAP";
 		} else {
 			$table = "Core_M_Position_nonSAP";
 		}
 		$query = "UPDATE [PMS].[dbo].[Core_M_Position_SAP]
						   SET [PositionName] = '$post_name'
						      ,[OrganizationID] = $org_id
						      ,[Chief] = '$status'
						      ,[PositionGroup] = '$post_group'
						      ,[BeginDate] = '$new_begin'
						      ,[EndDate] = '$new_end'
						      ,[UnitID] = '$unit_id'
						 WHERE [PositionID] = $post_id AND
						 	,[BeginDate] = '$old_begin' AND
						 	,[EndDate] = '$old_end'";
		$this->pms->query($query);
	}

	/**
	 * [remove_post description]
	 * @param  boolean $is_sap    [description]
	 * @param  integer $post_id   [description]
	 * @param  string  $old_begin [description]
	 * @param  string  $old_end   [description]
	 * @return [type]             [description]
	 */
	public function remove_post($is_sap=FALSE, $post_id=0,$old_begin='',$old_end='')
	{
		if ($is_sap) {
 			$table = "Core_M_Position_SAP";
 		} else {
 			$table = "Core_M_Position_nonSAP";
 		}
 		$query = "DELETE FROM $table
						 WHERE [PositionID] = $post_id AND
						 	,[BeginDate] = '$old_begin' AND
						 	,[EndDate] = '$old_end'";
		$this->pms->query($query);
	}

	/**
	 * [delimit_post description]
	 * @param  boolean $is_sap    [description]
	 * @param  integer $post_id   [description]
	 * @param  string  $old_begin [description]
	 * @param  string  $old_end   [description]
	 * @param  string  $new_end   [description]
	 * @return [type]             [description]
	 */
	public function delimit_post($is_sap=FALSE, $post_id=0,$old_begin='',$old_end='',$new_end='')
	{
		$query = "UPDATE [PMS].[dbo].[Core_M_Position_SAP]
						   SET [BeginDate] = '$new_begin'
						      ,[EndDate] = '$new_end'
						      ,[UnitID] = '$unit_id'
						 WHERE [PositionID] = $post_id AND
						 	,[BeginDate] = '$old_begin' AND
						 	,[EndDate] = '$old_end'";
		$this->pms->query($query);
	}

	//////////////
	// Holding //
	//////////////

	/**
	 * [count_hold_byNik description]
	 * @param  boolean $is_sap [description]
	 * @param  integer $nik    [description]
	 * @param  string  $begin  [description]
	 * @param  string  $end    [description]
	 * @return [type]          [description]
	 */
	public function count_hold_byNik($is_sap=TRUE, $nik=0,$begin='',$end='')
	{
		if ($is_sap) {
			$table   = "Core_M_Holder_SAP";
			$table_2 = "Core_M_Position_SAP";
 		} else {
			$table   = "Core_M_Holder_nonSAP";
			$table_2 = "Core_M_Position_nonSAP";
 		}
		$query = "SELECT count(h.*) AS val
							FROM $table h
							INNER JOIN Core_M_User u ON
								h.NIK = u.NIK
							INNER JOIN $table_2 p ON
								p.PositionID = h.PositionID
							WHERE h.NIK = '$nik' AND
								((h.BeginDate >= '$begin' AND h.EndDate <='$end') OR
 								(h.EndDate >= '$begin' AND h.EndDate <= '$end') OR
 								(h.BeginDate >= '$begin' AND h.BeginDate <='$end' ) OR
 								(h.BeginDate <= '$begin' AND h.EndDate >= '$end')) AND
								((p.BeginDate >= '$begin' AND p.EndDate <='$end') OR
 								(p.EndDate >= '$begin' AND p.EndDate <= '$end') OR
 								(p.BeginDate >= '$begin' AND p.BeginDate <='$end' ) OR
 								(p.BeginDate <= '$begin' AND p.EndDate >= '$end'))";
		return $this->pms->query($query)->row()->val;
	}

	/**
	 * [count_hold_byPost description]
	 * @param  boolean $is_sap  [description]
	 * @param  integer $post_id [description]
	 * @param  string  $begin   [description]
	 * @param  string  $end     [description]
	 * @return [type]           [description]
	 */
	public function count_hold_byPost($is_sap=TRUE, $post_id=0,$begin='',$end='')
	{
		if ($is_sap) {
			$table   = "Core_M_Holder_SAP";
			$table_2 = "Core_M_Position_SAP";
 		} else {
			$table   = "Core_M_Holder_nonSAP";
			$table_2 = "Core_M_Position_nonSAP";
 		}
		$query = "SELECT count(*) AS val
							FROM $table h
							INNER JOIN Core_M_User u ON
								h.NIK = u.NIK
							INNER JOIN $table_2 p ON
								p.PositionID = h.PositionID
							WHERE h.PositionID = '$post_id' AND
								((h.BeginDate >= '$begin' AND h.EndDate <='$end') OR
 								(h.EndDate >= '$begin' AND h.EndDate <= '$end') OR
 								(h.BeginDate >= '$begin' AND h.BeginDate <='$end' ) OR
 								(h.BeginDate <= '$begin' AND h.EndDate >= '$end')) AND
								((p.BeginDate >= '$begin' AND p.EndDate <='$end') OR
 								(p.EndDate >= '$begin' AND p.EndDate <= '$end') OR
 								(p.BeginDate >= '$begin' AND p.BeginDate <='$end' ) OR
 								(p.BeginDate <= '$begin' AND p.EndDate >= '$end'))";
		return $this->pms->query($query)->row()->val;
	}

	public function count_hold_byOrg($is_sap=TRUE, $org_id=0,$begin='',$end='')
	{
		if ($is_sap) {
			$table   = "Core_M_Holder_SAP";
			$table_2 = "Core_M_Position_SAP";
			$table_3 = "Core_M_Organization_SAP";
 		} else {
			$table   = "Core_M_Holder_nonSAP";
			$table_2 = "Core_M_Position_nonSAP";
			$table_3 = "Core_M_Organization_nonSAP";

 		}
		$query = "SELECT count(*) AS val
							FROM $table h
							INNER JOIN Core_M_User u ON
								h.NIK = u.NIK
							INNER JOIN $table_2 p ON
								p.PositionID = h.PositionID
							INNER JOIN $table_3 o ON
								p.OrganizationID = o.OrganizationID
							WHERE p.OrganizationID = '$org_id' AND
								((h.BeginDate >= '$begin' AND h.EndDate <='$end') OR
 								(h.EndDate >= '$begin' AND h.EndDate <= '$end') OR
 								(h.BeginDate >= '$begin' AND h.BeginDate <='$end' ) OR
 								(h.BeginDate <= '$begin' AND h.EndDate >= '$end')) AND
								((p.BeginDate >= '$begin' AND p.EndDate <='$end') OR
 								(p.EndDate >= '$begin' AND p.EndDate <= '$end') OR
 								(p.BeginDate >= '$begin' AND p.BeginDate <='$end' ) OR
 								(p.BeginDate <= '$begin' AND p.EndDate >= '$end')) AND
 								((o.BeginDate >= '$begin' AND o.EndDate <='$end') OR
 								(o.EndDate >= '$begin' AND o.EndDate <= '$end') OR
 								(o.BeginDate >= '$begin' AND o.BeginDate <='$end' ) OR
 								(o.BeginDate <= '$begin' AND o.EndDate >= '$end'))";
		return $this->pms->query($query)->row()->val;
	}

	/**
	 * [get_hold_byNik_list description]
	 * @param  boolean $is_sap [description]
	 * @param  integer $nik    [description]
	 * @param  string  $begin  [description]
	 * @param  string  $end    [description]
	 * @return [type]          [description]
	 */
	public function get_hold_byNik_list($is_sap=TRUE, $nik=0,$begin='',$end='',$is_main=2)
	{
		if ($is_sap) {
			$table   = "Core_M_Holder_SAP";
			$table_2 = "Core_M_Position_SAP";
			$table_3 = "Core_M_Organization_SAP";
 		} else {
			$table   = "Core_M_Holder_nonSAP";
			$table_2 = "Core_M_Position_nonSAP";
			$table_3 = "Core_M_Organization_nonSAP";

 		}
 		if ($is_main == 2) {
 			$main = '';
 		} else {
 			$main = " AND h.isMain = $is_main ";
 		}
		$query = "SELECT h.*,
								p.PositionName AS post_name,
								p.Chief AS post_status,
								p.OrganizationID AS org_id,
								o.OrganizationName AS org_name,
								p.BeginDate AS post_BeginDate,
								p.EndDate AS post_EndDate,
								u.Fullname,
								u.PersAdmin,
								u.SubArea,
								u.RoleID,
								u.isSAP AS user_isSAP,
								u.BeginDate AS user_BeginDate,
								u.EndDate AS user_EndDate
							FROM $table h
							INNER JOIN Core_M_User u ON
								h.NIK = u.NIK
							INNER JOIN $table_2 p ON
								p.PositionID = h.PositionID
							INNER JOIN $table_3 o ON
								p.OrganizationID = o.OrganizationID
							WHERE u.NIK = '$nik' AND
								((h.BeginDate >= '$begin' AND h.EndDate <='$end') OR
 								(h.EndDate >= '$begin' AND h.EndDate <= '$end') OR
 								(h.BeginDate >= '$begin' AND h.BeginDate <='$end' ) OR
 								(h.BeginDate <= '$begin' AND h.EndDate >= '$end')) AND
								((p.BeginDate >= '$begin' AND p.EndDate <='$end') OR
 								(p.EndDate >= '$begin' AND p.EndDate <= '$end') OR
 								(p.BeginDate >= '$begin' AND p.BeginDate <='$end' ) OR
 								(p.BeginDate <= '$begin' AND p.EndDate >= '$end')) AND
 								((o.BeginDate >= '$begin' AND o.EndDate <='$end') OR
 								(o.EndDate >= '$begin' AND o.EndDate <= '$end') OR
 								(o.BeginDate >= '$begin' AND o.BeginDate <='$end' ) OR
 								(o.BeginDate <= '$begin' AND o.EndDate >= '$end'))
								$main ";
		return $this->pms->query($query)->result();
	}

	public function get_hold_byOrg_list($is_sap=TRUE, $org_id=0,$begin='',$end='',$is_main=2)
	{
		if ($is_sap) {
			$table   = "Core_M_Holder_SAP";
			$table_2 = "Core_M_Position_SAP";
			$table_3 = "Core_M_Organization_SAP";
 		} else {
			$table   = "Core_M_Holder_nonSAP";
			$table_2 = "Core_M_Position_nonSAP";
			$table_3 = "Core_M_Organization_nonSAP";

 		}
 		if ($is_main == 2) {
 			$main = '';
 		} else {
 			$main = " AND h.isMain = $is_main ";
 		}
		$query = "SELECT h.*,
								p.PositionName AS post_name,
								p.Chief AS post_status,
								p.OrganizationID AS org_id,
								o.OrganizationName AS org_name,
								p.BeginDate AS post_BeginDate,
								p.EndDate AS post_EndDate,
								u.Fullname,
								u.PersAdmin,
								u.SubArea,
								u.RoleID,
								u.isSAP AS user_isSAP,
								u.BeginDate AS user_BeginDate,
								u.EndDate AS user_EndDate
							FROM $table h
							INNER JOIN Core_M_User u ON
								h.NIK = u.NIK
							INNER JOIN $table_2 p ON
								p.PositionID = h.PositionID
							INNER JOIN $table_3 o ON
								p.OrganizationID = o.OrganizationID
							WHERE p.OrganizationID = '$org_id' AND
								((h.BeginDate >= '$begin' AND h.EndDate <='$end') OR
 								(h.EndDate >= '$begin' AND h.EndDate <= '$end') OR
 								(h.BeginDate >= '$begin' AND h.BeginDate <='$end' ) OR
 								(h.BeginDate <= '$begin' AND h.EndDate >= '$end')) AND
								((p.BeginDate >= '$begin' AND p.EndDate <='$end') OR
 								(p.EndDate >= '$begin' AND p.EndDate <= '$end') OR
 								(p.BeginDate >= '$begin' AND p.BeginDate <='$end' ) OR
 								(p.BeginDate <= '$begin' AND p.EndDate >= '$end')) AND
 								((o.BeginDate >= '$begin' AND o.EndDate <='$end') OR
 								(o.EndDate >= '$begin' AND o.EndDate <= '$end') OR
 								(o.BeginDate >= '$begin' AND o.BeginDate <='$end' ) OR
 								(o.BeginDate <= '$begin' AND o.EndDate >= '$end'))
								$main
							ORDER BY p.Chief DESC, u.NIK ASC";
		return $this->pms->query($query)->result();
	}

	public function get_hold_tree_byOrg_list($is_sap=TRUE, $org_id=0,$begin='',$end='',$is_main=2)
	{
		if ($is_sap) {
			$table   = "Core_M_Holder_SAP";
			$table_2 = "Core_M_Position_SAP";
			$table_3 = "Core_M_Organization_SAP";
 		} else {
			$table   = "Core_M_Holder_nonSAP";
			$table_2 = "Core_M_Position_nonSAP";
			$table_3 = "Core_M_Organization_nonSAP";

 		}
 		$org_list = $this->get_org_tree_list($is_sap,$org_id,$begin,$end);
 		$org_ls = '';
 		$result = array();
 		if ($is_main == 2) {
 			$main = '';
 		} else {
 			$main = " AND h.isMain = $is_main ";
 		}
 		foreach ($org_list as $row) {
 			// $org_ls .= ', '.$row->id;
			$query = "SELECT h.*,
									p.PositionName AS post_name,
									p.Chief AS post_status,
									p.OrganizationID AS org_id,
									o.OrganizationName AS org_name,
									p.BeginDate AS post_BeginDate,
									p.EndDate AS post_EndDate,
									u.Fullname,
									u.PersAdmin,
									u.SubArea,
									u.RoleID,
									u.isSAP AS user_isSAP,
									u.BeginDate AS user_BeginDate,
									u.EndDate AS user_EndDate
								FROM $table h
								INNER JOIN Core_M_User u ON
									h.NIK = u.NIK
								INNER JOIN $table_2 p ON
									p.PositionID = h.PositionID
								INNER JOIN $table_3 o ON
									p.OrganizationID = o.OrganizationID
								WHERE p.OrganizationID = $row->id AND
									((h.BeginDate >= '$begin' AND h.EndDate <='$end') OR
	 								(h.EndDate >= '$begin' AND h.EndDate <= '$end') OR
	 								(h.BeginDate >= '$begin' AND h.BeginDate <='$end' ) OR
	 								(h.BeginDate <= '$begin' AND h.EndDate >= '$end')) AND
									((p.BeginDate >= '$begin' AND p.EndDate <='$end') OR
	 								(p.EndDate >= '$begin' AND p.EndDate <= '$end') OR
	 								(p.BeginDate >= '$begin' AND p.BeginDate <='$end' ) OR
	 								(p.BeginDate <= '$begin' AND p.EndDate >= '$end')) AND
	 								((o.BeginDate >= '$begin' AND o.EndDate <='$end') OR
	 								(o.EndDate >= '$begin' AND o.EndDate <= '$end') OR
	 								(o.BeginDate >= '$begin' AND o.BeginDate <='$end' ) OR
	 								(o.BeginDate <= '$begin' AND o.EndDate >= '$end'))
									$main
								ORDER BY p.Chief DESC, u.NIK ASC";
			$temp = $this->pms->query($query)->result();
			foreach ($temp as $row) {
				$result[] = $row;
			}
 		}

 		// $org_ls = substr($org_ls, 1);
		return $result;
	}

	/**
	 * [get_hold_byPost_list description]
	 * @param  boolean $is_sap  [description]
	 * @param  integer $post_id [description]
	 * @param  string  $begin   [description]
	 * @param  string  $end     [description]
	 * @return [type]           [description]
	 */
	public function get_hold_byPost_list($is_sap=TRUE, $post_id=0,$begin='',$end='')
	{
		if ($is_sap) {
			$table   = "Core_M_Holder_SAP";
			$table_2 = "Core_M_Position_SAP";
 		} else {
			$table   = "Core_M_Holder_nonSAP";
			$table_2 = "Core_M_Position_nonSAP";
 		}
		$query = "SELECT h.*,
								p.PositionName,
								p.Chief AS post_status,
								p.OrganizationID AS org_id,
								p.BeginDate AS post_BeginDate,
								p.EndDate AS post_EndDate,
								u.Fullname,
								u.PersAdmin,
								u.SubArea,
								u.RoleID,
								u.isSAP AS user_isSAP,
								u.BeginDate AS user_BeginDate,
								u.EndDate AS user_EndDate
							FROM $table h
							INNER JOIN Core_M_User u ON
								h.NIK = u.NIK
							INNER JOIN $table_2 p ON
								p.PositionID = h.PositionID
							WHERE u.PositionID = '$post_id' AND
								((h.BeginDate >= '$begin' AND h.EndDate <='$end') OR
 								(h.EndDate >= '$begin' AND h.EndDate <= '$end') OR
 								(h.BeginDate >= '$begin' AND h.BeginDate <='$end' ) OR
 								(h.BeginDate <= '$begin' AND h.EndDate >= '$end')) AND
								((p.BeginDate >= '$begin' AND p.EndDate <='$end') OR
 								(p.EndDate >= '$begin' AND p.EndDate <= '$end') OR
 								(p.BeginDate >= '$begin' AND p.BeginDate <='$end' ) OR
 								(p.BeginDate <= '$begin' AND p.EndDate >= '$end'))";
		return $this->pms->query($query)->result();
	}

	/**
	 * [get_hold_byNik_last description]
	 * @param  boolean $is_sap [description]
	 * @param  integer $nik    [description]
	 * @param  string  $begin  [description]
	 * @param  string  $end    [description]
	 * @return [type]          [description]
	 */
	public function get_hold_byNik_last($is_sap=TRUE, $nik=0,$begin='',$end='')
	{
		if ($is_sap) {
			$table   = "Core_M_Holder_SAP";
			$table_2 = "Core_M_Position_SAP";
 		} else {
			$table   = "Core_M_Holder_nonSAP";
			$table_2 = "Core_M_Position_nonSAP";
 		}
		$query = "SELECT TOP 1 h.*,
								p.PositionName,
								p.Chief AS post_status,
								p.OrganizationID AS org_id,
								p.BeginDate AS post_BeginDate,
								p.EndDate AS post_EndDate,
								u.Fullname,
								u.PersAdmin,
								u.SubArea,
								u.RoleID,
								u.isSAP AS user_isSAP,
								u.BeginDate AS user_BeginDate,
								u.EndDate AS user_EndDate
							FROM $table h
							INNER JOIN Core_M_User u ON
								h.NIK = u.NIK
							INNER JOIN $table_2 p ON
								p.PositionID = h.PositionID
							WHERE u.NIK = '$nik' AND
								((h.BeginDate >= '$begin' AND h.EndDate <='$end') OR
 								(h.EndDate >= '$begin' AND h.EndDate <= '$end') OR
 								(h.BeginDate >= '$begin' AND h.BeginDate <='$end' ) OR
 								(h.BeginDate <= '$begin' AND h.EndDate >= '$end')) AND
								((p.BeginDate >= '$begin' AND p.EndDate <='$end') OR
 								(p.EndDate >= '$begin' AND p.EndDate <= '$end') OR
 								(p.BeginDate >= '$begin' AND p.BeginDate <='$end' ) OR
 								(p.BeginDate <= '$begin' AND p.EndDate >= '$end'))
 							ORDER BY EndDate DESC, BeginDate DESC";

		return $this->pms->query($query)->row();
	}

	/**
	 * [get_hold_byPost_last description]
	 * @param  boolean $is_sap  [description]
	 * @param  integer $post_id [description]
	 * @param  string  $begin   [description]
	 * @param  string  $end     [description]
	 * @return [type]           [description]
	 */
	public function get_hold_byPost_last($is_sap=TRUE, $post_id=0,$begin='',$end='')
	{
		if ($is_sap) {
			$table   = "Core_M_Holder_SAP";
			$table_2 = "Core_M_Position_SAP";
 		} else {
			$table   = "Core_M_Holder_nonSAP";
			$table_2 = "Core_M_Position_nonSAP";
 		}
		$query = "SELECT TOP 1 h.*,
								p.PositionName,
								p.Chief AS post_status,
								p.OrganizationID AS org_id,
								p.BeginDate AS post_BeginDate,
								p.EndDate AS post_EndDate,
								u.Fullname,
								u.PersAdmin,
								u.SubArea,
								u.RoleID,
								u.isSAP AS user_isSAP,
								u.BeginDate AS user_BeginDate,
								u.EndDate AS user_EndDate
							FROM $table h
							INNER JOIN Core_M_User u ON
								h.NIK = u.NIK
							INNER JOIN $table_2 p ON
								p.PositionID = h.PositionID
							WHERE h.PositionID = $post_id AND
								((h.BeginDate >= '$begin' AND h.EndDate <='$end') OR
 								(h.EndDate >= '$begin' AND h.EndDate <= '$end') OR
 								(h.BeginDate >= '$begin' AND h.BeginDate <='$end' ) OR
 								(h.BeginDate <= '$begin' AND h.EndDate >= '$end')) AND
								((p.BeginDate >= '$begin' AND p.EndDate <='$end') OR
 								(p.EndDate >= '$begin' AND p.EndDate <= '$end') OR
 								(p.BeginDate >= '$begin' AND p.BeginDate <='$end' ) OR
 								(p.BeginDate <= '$begin' AND p.EndDate >= '$end'))
 							ORDER BY EndDate DESC, BeginDate DESC";

		return $this->pms->query($query)->row();
	}

	/**
	 * [get_hold_row description]
	 * @param  boolean $is_sap    [description]
	 * @param  integer $holder_id [description]
	 * @return [type]             [description]
	 */
	public function get_hold_row($is_sap=TRUE,$holder_id=0)
	{
		if ($is_sap) {
			$table   = "Core_M_Holder_SAP";
			$table_2 = "Core_M_Position_SAP";
 		} else {
			$table   = "Core_M_Holder_nonSAP";
			$table_2 = "Core_M_Position_nonSAP";
 		}
		$query = "SELECT h.*,
								p.PositionName,
								p.Chief AS post_status,
								p.OrganizationID AS org_id,
								p.BeginDate AS post_BeginDate,
								p.EndDate AS post_EndDate,
								u.Fullname,
								u.PersAdmin,
								u.SubArea,
								u.RoleID,
								u.isSAP AS user_isSAP,
								u.BeginDate AS user_BeginDate,
								u.EndDate AS user_EndDate
							FROM $table h
							INNER JOIN Core_M_User u ON
								h.NIK = u.NIK
							INNER JOIN $table_2 p ON
								p.PositionID = h.PositionID
							WHERE h.HolderID = '$holder_id' AND
								((h.BeginDate >= '$begin' AND h.EndDate <='$end') OR
 								(h.EndDate >= '$begin' AND h.EndDate <= '$end') OR
 								(h.BeginDate >= '$begin' AND h.BeginDate <='$end' ) OR
 								(h.BeginDate <= '$begin' AND h.EndDate >= '$end')) AND
								((p.BeginDate >= '$begin' AND p.EndDate <='$end') OR
 								(p.EndDate >= '$begin' AND p.EndDate <= '$end') OR
 								(p.BeginDate >= '$begin' AND p.BeginDate <='$end' ) OR
 								(p.BeginDate <= '$begin' AND p.EndDate >= '$end'))";
		return $this->pms->query($query)->row();
	}

	/**
	 * [add_hold description]
	 * @param boolean $is_sap  [description]
	 * @param string  $nik     [description]
	 * @param integer $post_id [description]
	 * @param boolean $is_main [description]
	 * @param string  $begin   [description]
	 * @param string  $end     [description]
	 */
	public function add_hold($is_sap=FALSE,$nik='',$post_id=0,$is_main=TRUE,$begin='',$end='9999-12-31')
	{
		if ($is_sap) {
 			$table = "Core_M_Holder_SAP";
 		} else {
 			$table = "Core_M_Holder_nonSAP";
 		}
		$query = "INSERT INTO $table
           ([NIK]
           ,[PositionID]
           ,[isMain]
           ,[BeginDate]
           ,[EndDate])
     VALUES
           ('$nik'
           ,$post_id
           ,$is_main
           ,'$begin'
           ,'$end')";

		$this->pms->query($query);
	}

	/**
	 * [edit_hold description]
	 * @param  boolean $is_sap    [description]
	 * @param  integer $holder_id [description]
	 * @param  string  $nik       [description]
	 * @param  integer $post_id   [description]
	 * @param  boolean $is_main   [description]
	 * @param  string  $begin     [description]
	 * @param  string  $end       [description]
	 * @return [type]             [description]
	 */
	public function edit_hold($is_sap=FALSE,$holder_id=0,$nik='',$post_id=0,$is_main=TRUE,$begin='',$end='')
	{
		if ($is_sap) {
 			$table = "Core_M_Holder_SAP";
 		} else {
 			$table = "Core_M_Holder_nonSAP";
 		}
		$query = "UPDATE $table
						   SET [NIK] = '$nik'
						      ,[PositionID] = $post_id
						      ,[isMain] = $is_main
						      ,[BeginDate] = '$begin'
						      ,[EndDate] = '$end'
						 WHERE HolderID = $holder_id";

		$this->pms->query($query);
	}

	/**
	 * [remove_hold description]
	 * @param  boolean $is_sap    [description]
	 * @param  integer $holder_id [description]
	 * @return [type]             [description]
	 */
	public function remove_hold($is_sap=TRUE,$holder_id=0)
	{
		if ($is_sap) {
 			$table = "Core_M_Holder_SAP";
 		} else {
 			$table = "Core_M_Holder_nonSAP";
 		}
		$query = "DELETE FROM $table WHERE HolderID $holder_id";

		$this->pms->query($query);
	}

	/**
	 * [delimit_hold description]
	 * @param  boolean $is_sap    [description]
	 * @param  integer $holder_id [description]
	 * @param  string  $end       [description]
	 * @return [type]             [description]
	 */
	public function delimit_hold($is_sap=TRUE,$holder_id=0,$end='')
	{
		if ($is_sap) {
 			$table = "Core_M_Holder_SAP";
 		} else {
 			$table = "Core_M_Holder_nonSAP";
 		}
		$query = "UPDATE $table
						   SET [EndDate] = '$end'
						 WHERE HolderID = $holder_id";

		$this->pms->query($query);
	}

	//////////////////////////
	// Reporting Structure //
	//////////////////////////

	public function get_superior_list($sub_postID = 0,$begin='',$end='')
	{
		if ($begin == '') {
			$begin = date('Y-m-d');
		}

		if ($end == '') {
			$end = date('Y-m-d');
		}
		$i      = 0;
		$result = array();

		$new_end    = $end;
		do {
			$temp = $this->get_superior_row($sub_postID,$begin,$new_end);
			$result[$i] = $temp;
			$new_end = date('Y-m-d', strtotime('-1'.$temp->begin));
			$i++;
		} while ($temp->begin > $begin);
		return $result;
	}


	public function get_superior_row($sub_postID = 0, $begin = '', $end = '')
	{
		if ($begin == '') {
			$begin = date('Y-m-d');
		}

		if ($end == '') {
			$end = date('Y-m-d');
		}

		$this->pms->select('count(ers.ChiefPositionID) as val');
		$this->pms->from('Core_M_Exception_Reporting_Structure ers');
		$this->pms->where('ers.PositionID', $sub_postID);
		$this->pms->where('ers.isSAP', 1);
		$this->pms->where('ers.Chief_isSAP', 1);
		$this->pms->where("((ers.BeginDate >= '$begin' AND ers.EndDate <='$end') OR (ers.EndDate >= '$begin' AND ers.EndDate <= '$end') OR (ers.BeginDate >= '$begin' AND ers.BeginDate <='$end' ) OR (ers.BeginDate <= '$begin' AND ers.EndDate >= '$end')) ");

		if ($this->pms->get()->row()->val) {
			$this->pms->select('ers.ChiefPositionID');
			$this->pms->from('Core_M_Exception_Reporting_Structure ers');
			$this->pms->where('ers.PositionID', $sub_postID);
			$this->pms->where('ers.isSAP', 1);
			$this->pms->where('ers.Chief_isSAP', 1);
			$this->pms->where("((ers.BeginDate >= '$begin' AND ers.EndDate <='$end') OR (ers.EndDate >= '$begin' AND ers.EndDate <= '$end') OR (ers.BeginDate >= '$begin' AND ers.BeginDate <='$end' ) OR (ers.BeginDate <= '$begin' AND ers.EndDate >= '$end')) ");
			$this->pms->order_by('ers.EndDate','desc');

			$chief_postID = $this->pms->get()->row()->ChiefPositionID;
			// TODO apakah ada pejabatnya
			$this->pms->select('count(*) as val');
			$this->pms->from('Core_M_Holder_SAP h');
			$this->pms->where('h.PositionID', $chief_postID);

			$this->pms->where("((h.BeginDate >= '$begin' AND h.EndDate <='$end') OR (h.EndDate >= '$begin' AND h.EndDate <= '$end') OR (h.BeginDate >= '$begin' AND h.BeginDate <='$end' ) OR (h.BeginDate <= '$begin' AND h.EndDate >= '$end')) ");
			if ($this->pms->get()->row()->val) {
				// Ada pemangkunya
				$this->pms->select('NIK');
				$this->pms->select('BeginDate');
				$this->pms->select('EndDate');
				$this->pms->from('Core_M_Holder_SAP h');
				$this->pms->where('h.PositionID', $chief_postID);

				$this->pms->where("((h.BeginDate >= '$begin' AND h.EndDate <='$end') OR (h.EndDate >= '$begin' AND h.EndDate <= '$end') OR (h.BeginDate >= '$begin' AND h.BeginDate <='$end' ) OR (h.BeginDate <= '$begin' AND h.EndDate >= '$end')) ");
				$this->pms->order_by('h.EndDate','desc');
				$chief = $this->pms->get()->row();
				$result = array(
					'nik' 		=> $chief->NIK,
					'post_id' => $chief_postID,
					'begin'   => $chief->BeginDate,
					'end'     => $chief->EndDate,
				);
				return (object) $result;
			} else {
				// Jika tidak ada pemangkunya
				return $this->get_superior_row($chief_postID,$begin,$end);
			}



		} else {
			// TODO check apakah chief atau enggak
			$this->pms->select('OrganizationID');
			$this->pms->select('Chief');
			$this->pms->from('Core_M_Position_SAP p');
			$this->pms->where('p.PositionID', $sub_postID);
			$this->pms->where("((p.BeginDate >= '$begin' AND p.EndDate <='$end') OR (p.EndDate >= '$begin' AND p.EndDate <= '$end') OR (p.BeginDate >= '$begin' AND p.BeginDate <='$end' ) OR (p.BeginDate <= '$begin' AND p.EndDate >= '$end')) ");
			$this->pms->order_by('p.EndDate','desc');

			$post = $this->pms->get()->row();
			if ($post->Chief == 2) {
				$this->pms->select('OrganizationParent');

				$this->pms->from('Core_M_Organization_SAP o');
				$this->pms->where('o.OrganizationID', $post->OrganizationID);
				$this->pms->where("((o.BeginDate >= '$begin' AND o.EndDate <='$end') OR (o.EndDate >= '$begin' AND o.EndDate <= '$end') OR (o.BeginDate >= '$begin' AND o.BeginDate <='$end' ) OR (o.BeginDate <= '$begin' AND o.EndDate >= '$end')) ");
				$this->pms->order_by('o.EndDate','desc');

				$org_id = $this->pms->get()->row()->OrganizationParent;

				$this->pms->select('PositionID');
				$this->pms->from('Core_M_Position_SAP p');
				$this->pms->where('p.OrganizationID', $org_id);
				$this->pms->where('p.Chief', 2);
				$this->pms->where("((p.BeginDate >= '$begin' AND p.EndDate <='$end') OR (p.EndDate >= '$begin' AND p.EndDate <= '$end') OR (p.BeginDate >= '$begin' AND p.BeginDate <='$end' ) OR (p.BeginDate <= '$begin' AND p.EndDate >= '$end')) ");
				$this->pms->order_by('p.EndDate','desc');

				$chief_postID = $this->pms->get()->row()->PositionID;

				$this->pms->select('count(*) as val');
				$this->pms->from('Core_M_Holder_SAP h');
				$this->pms->where('h.PositionID', $chief_postID);

				$this->pms->where("((h.BeginDate >= '$begin' AND h.EndDate <='$end') OR (h.EndDate >= '$begin' AND h.EndDate <= '$end') OR (h.BeginDate >= '$begin' AND h.BeginDate <='$end' ) OR (h.BeginDate <= '$begin' AND h.EndDate >= '$end')) ");
				if ($this->pms->get()->row()->val) {
					// Ada pemangkunya
					$this->pms->select('NIK');
					$this->pms->select('BeginDate');
					$this->pms->select('EndDate');
					$this->pms->from('Core_M_Holder_SAP h');
					$this->pms->where('h.PositionID', $chief_postID);

					$this->pms->where("((h.BeginDate >= '$begin' AND h.EndDate <='$end') OR (h.EndDate >= '$begin' AND h.EndDate <= '$end') OR (h.BeginDate >= '$begin' AND h.BeginDate <='$end' ) OR (h.BeginDate <= '$begin' AND h.EndDate >= '$end')) ");
					$this->pms->order_by('h.EndDate','desc');
					$chief = $this->pms->get()->row();
					$result = array(
						'nik' 		=> $chief->NIK,
						'post_id' => $chief_postID,
						'begin'   => $chief->BeginDate,
						'end'     => $chief->EndDate,
					);
					return (object) $result;
				} else {
					// Jika tidak ada pemangkunya
					return $this->get_superior_row($chief_postID,$begin,$end);
				}
			} else {
				$this->pms->select('PositionID');
				$this->pms->from('Core_M_Position_SAP p');
				$this->pms->where('p.OrganizationID', $post->OrganizationID);
				$this->pms->where('p.Chief', 2);
				$this->pms->where("((p.BeginDate >= '$begin' AND p.EndDate <='$end') OR (p.EndDate >= '$begin' AND p.EndDate <= '$end') OR (p.BeginDate >= '$begin' AND p.BeginDate <='$end' ) OR (p.BeginDate <= '$begin' AND p.EndDate >= '$end')) ");
				$this->pms->order_by('p.EndDate','desc');

				$chief_postID = $this->pms->get()->row()->PositionID;
				$this->pms->select('count(*) as val');
				$this->pms->from('Core_M_Holder_SAP h');
				$this->pms->where('h.PositionID', $chief_postID);

				$this->pms->where("((h.BeginDate >= '$begin' AND h.EndDate <='$end') OR (h.EndDate >= '$begin' AND h.EndDate <= '$end') OR (h.BeginDate >= '$begin' AND h.BeginDate <='$end' ) OR (h.BeginDate <= '$begin' AND h.EndDate >= '$end')) ");
				if ($this->pms->get()->row()->val) {
					// Ada pemangkunya
					$this->pms->select('NIK');
					$this->pms->select('BeginDate');
					$this->pms->select('EndDate');
					$this->pms->from('Core_M_Holder_SAP h');
					$this->pms->where('h.PositionID', $chief_postID);

					$this->pms->where("((h.BeginDate >= '$begin' AND h.EndDate <='$end') OR (h.EndDate >= '$begin' AND h.EndDate <= '$end') OR (h.BeginDate >= '$begin' AND h.BeginDate <='$end' ) OR (h.BeginDate <= '$begin' AND h.EndDate >= '$end')) ");
					$this->pms->order_by('h.EndDate','desc');
					$chief = $this->pms->get()->row();
					$result = array(
						'nik' 		=> $chief->NIK,
						'post_id' => $chief_postID,
						'begin'   => $chief->BeginDate,
						'end'     => $chief->EndDate,
					);
					return (object) $result;
				} else {
					// Jika tidak ada pemangkunya
					return $this->get_superior_row($chief_postID,$begin,$end);
				}
			}

		}
	}
	/**
	 * [mendapatkan daftar Bawahan]
	 * @param  [type] $is_sap  [description]
	 * @param  [type] $post_id [description]
	 * @param  [type] $begin   [description]
	 * @param  [type] $end     [description]
	 * @return [type]          [description]
	 */
	public function get_subord_list($is_sap=TRUE,$post_id=0,$begin='',$end='')
	{
		$query = "exec [dbo].[DirectSubordinateException] $post_id, $is_sap, '$begin', '$end'";
		return $this->pms->query($query)->result();
	}

	//////////////////////////
	// Assigment //
	//////////////////////////

	public function count_assign_byHold($nik='',$post_id='')
	{
		$query = "SELECT  COUNT(*) as val
							FROM [PA_T_Assignment]
							WHERE NIK = '$nik' AND
								PositionID = $post_id ";
		return $this->pms->query($query)->row()->val;
	}

	public function get_assign_byHold_row($nik='',$post_id='')
	{
		$query = "SELECT TOP 1 *
							FROM [PA_T_Assignment]
							WHERE NIK = '$nik' AND
								PositionID = $post_id ";
		return $this->pms->query($query)->row();
	}

	////////////////////////////////////
	// Exception Reporting Structure //
	////////////////////////////////////

	public function get_execReportStruct_list($persAdmin='')
	{
		if ($persAdmin=='') {
			// $query = "SELECT A.ExceptionReportingStructureID, A.ChiefPositionID, A.Chief_isSAP, A.BeginDate, A.EndDate, A.PositionID, A.isSAP
			// 	FROM Core_M_Exception_Reporting_Structure A
			// 	WHERE A.[BeginDate] <= '$start_date' and A.[EndDate] >= '$end_date'
			// 	GROUP BY A.ChiefPositionID, A.Chief_isSAP, A.PositionID, A.isSAP, A.ExceptionReportingStructureID, A.BeginDate, A.EndDate";
				$query = "SELECT A.ExceptionReportingStructureID, A.ChiefPositionID, A.Chief_isSAP, A.BeginDate, A.EndDate, A.PositionID, A.isSAP
					FROM Core_M_Exception_Reporting_Structure A
					GROUP BY A.ChiefPositionID, A.Chief_isSAP, A.PositionID, A.isSAP, A.ExceptionReportingStructureID, A.BeginDate, A.EndDate";
		} else {
			// $query = "SELECT A.ExceptionReportingStructureID, A.ChiefPositionID, A.Chief_isSAP, A.BeginDate, A.EndDate, A.PositionID, A.isSAP
			// 	FROM Core_M_Exception_Reporting_Structure A
			// 	WHERE A.[BeginDate] <= '$start_date' and A.[EndDate] >= '$end_date' AND PersAdmin = '$persAdmin'
			// 	GROUP BY A.ChiefPositionID, A.Chief_isSAP, A.PositionID, A.isSAP, A.ExceptionReportingStructureID, A.BeginDate, A.EndDate";

			$query = "SELECT A.ExceptionReportingStructureID, A.ChiefPositionID, A.Chief_isSAP, A.BeginDate, A.EndDate, A.PositionID, A.isSAP
				FROM Core_M_Exception_Reporting_Structure A
				WHERE PersAdmin = '$persAdmin'
				GROUP BY A.ChiefPositionID, A.Chief_isSAP, A.PositionID, A.isSAP, A.ExceptionReportingStructureID, A.BeginDate, A.EndDate";
		}
		return $this->pms->query($query)->result();

	}

	public function add_excReportStruct($ChiefPositionID,$Chief, $PositionID, $isSAP, $start_date,$end_date,$persAdmin='')
	{
		if ($persAdmin=='') {
			$query = "INSERT INTO [Core_M_Exception_Reporting_Structure]([ChiefPositionID],[Chief_isSAP],[PositionID],[isSAP],[BeginDate],[EndDate])
			VALUES('$ChiefPositionID','$Chief','$PositionID','$isSAP', '$start_date','$end_date')";
		} else {
			$query = "INSERT INTO [Core_M_Exception_Reporting_Structure]([ChiefPositionID],[Chief_isSAP],[PositionID],[isSAP],[BeginDate],[EndDate],[PersAdmin])
			VALUES('$ChiefPositionID','$Chief','$PositionID','$isSAP', '$start_date','$end_date','$persAdmin')";
		}
		$this->pms->query($query);
	}

	public function edit_excReportStruct($ExceptionReportingStructureID,$end_date)
	{
		$query = "UPDATE [Core_M_Exception_Reporting_Structure] SET [EndDate] = '$end_date' WHERE ExceptionReportingStructureID=$ExceptionReportingStructureID";
		$this->pms->query($query);
	}

	public function get_excReportStruct_row($ExceptionReportingStructureID){
		$query = "SELECT * FROM Core_M_Exception_Reporting_Structure WHERE ExceptionReportingStructureID=$ExceptionReportingStructureID";
		return $this->pms->query($query)->row();
	}

	function check_excReportStruct($PositionID,$isSAP, $BeginDate, $EndDate){
		$query = "SELECT COUNT(*) as count_value FROM Core_M_Exception_Reporting_Structure WHERE PositionID=$PositionID AND isSAP=$isSAP AND BeginDate='$BeginDate' AND EndDate >= '$EndDate'";
		if($this->pms->query($query)->row()->count_value>0){
			return true;
		}else{
			return false;
		}
	}
}

/* End of file om_model.php */
/* Location: ./application/models/om_model.php */
