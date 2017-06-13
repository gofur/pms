<?php
class Report_model extends Model{
	function __construct(){
		parent::__construct();
		$this->portal=$this->load->database('portal', TRUE);
		$this->pms = $this->load->database('default', TRUE);
		
 	}

	//////////
 	// RKK //
	//////////
	
	/**
	 * [menghitung RKK Bawahan]
	 * @param  string $nik    [description]
	 * @param  string $begin  [description]
	 * @param  string $end    [description]
	 * @param  string $status [description]
	 * @return [type]         [description]
	 */
	public function count_rkk_B($nik='',$begin = '',$end = '',$status ='all')
	{
		$query = "SELECT count(*) as val
							FROM PA_R_RKK r
							JOIN PA_T_RKK t 
								ON r.RKKID = t.RKKID
							JOIN Core_M_User m 
								ON t.NIK = m.NIK 
							WHERE r.chief_nik = '$nik' AND
								((r.BeginDate >= '$begin' AND r.EndDate <='$end') OR 
 								(r.EndDate >= '$begin' AND r.EndDate <= '$end') OR 
 								(r.BeginDate >= '$begin' AND r.BeginDate <='$end' ) OR
 								(r.BeginDate <= '$begin' AND r.EndDate >= '$end'))";
		if (is_array($status)) {
			// $in = ' AND t.statusFlag IN (';
			// foreach ($status as $key => $value) {
			// 	$in .= $value.', ';
			// }

			// $in_len = strlen($in) - 2;
			// $in = substr($in, 0, $in_len).')';
			// $query .= $in;
			
			$query .= " AND t.statusFlag IN (".implode(', ', $status).') ';
		} else{
			if (is_integer($status)) {
				$query .= " AND t.statusFlag = $status ";
			} else {
				switch (strtolower($status)) {
		 			case 'open':
		 				$query .= " AND t.statusFlag IN (0,2)";
		 				break;
		 			case 'lock':
		 				$query .= " AND t.statusFlag IN (1,3,4,5)";
		 				break;
		 			case 'draft':
		 				$query .= " AND t.statusFlag = 0";
		 				break;
		 			case 'pending':
		 				$query .= " AND t.statusFlag = 1";
		 				break;
		 			case 'reject':
		 				$query .= " AND t.statusFlag = 2";
		 				break;
		 			case 'approve':
		 				$query .= " AND t.statusFlag = 3";
		 				break;
		 			case 'adjust':
		 				$query .= " AND t.statusFlag = 4";
		 				break;
		 			case 'final':
		 				$query .= " AND t.statusFlag = 5";
		 				break;
		 		}
			}
			
		}
		
		return  $this->pms->query($query)->row()->val;
	}

	/**
	 * [mendapatkan rkk bawahan]
	 * @param  string $nik    [description]
	 * @param  string $begin  [description]
	 * @param  string $end    [description]
	 * @param  string $status [description]
	 * @return [type]         [description]
	 */
	public function get_rkk_B_list($nik='',$begin = '',$end = '',$status ='all')
	{
		$query = "SELECT r.*,
								m.Fullname, 
								t.NIK, 
								t.PositionID, 
								t.isSAP,
								t.statusFlag,
								t.BeginDate AS RKK_BeginDate,
								t.EndDate AS RKK_EndDate
							FROM PA_R_RKK r
							JOIN PA_T_RKK t 
								ON r.RKKID = t.RKKID
							JOIN Core_M_User m 
								ON t.NIK = m.NIK 
							WHERE r.chief_nik = '$nik' AND
								((r.BeginDate >= '$begin' AND r.EndDate <='$end') OR 
 								(r.EndDate >= '$begin' AND r.EndDate <= '$end') OR 
 								(r.BeginDate >= '$begin' AND r.BeginDate <='$end' ) OR
 								(r.BeginDate <= '$begin' AND r.EndDate >= '$end'))";
		
		if (is_array($status)) {
			$query .= " AND t.statusFlag IN (".implode(', ', $status).') ';

		} else{
			if (is_integer($status)) {
				$query .= " AND t.statusFlag = $status ";
			} else {
				switch (strtolower($status)) {
		 			case 'open':
		 				$query .= " AND t.statusFlag IN (0,2)";
		 				break;
		 			case 'lock':
		 				$query .= " AND t.statusFlag IN (1,3,4,5)";
		 				break;
		 			case 'draft':
		 				$query .= " AND t.statusFlag = 0";
		 				break;
		 			case 'pending':
		 				$query .= " AND t.statusFlag = 1";
		 				break;
		 			case 'reject':
		 				$query .= " AND t.statusFlag = 2";
		 				break;
		 			case 'approve':
		 				$query .= " AND t.statusFlag = 3";
		 				break;
		 			case 'adjust':
		 				$query .= " AND t.statusFlag = 4";
		 				break;
		 			case 'final':
		 				$query .= " AND t.statusFlag = 5";
		 				break;
		 		}
			}
			
		}
		return  $this->pms->query($query)->result();
	}

	//////////////////
	// Achievement //
	//////////////////
	public function count_achv_rkk($rkk_id=0,$month = 0, $status = 'all')
	{
		$month  = (int) $month;
		$query = "SELECT COUNT(*) as val
							FROM PA_T_RKKAchievement
							WHERE RKKID = $rkk_id";
		if ($month > 0 && $month < 13) {
			$query .= " AND Month <= $month";
		}

		if (is_array($status)) {
			
			$query .= " AND Status_Flag IN (".implode(', ', $status).') ';

		} else {
			if (is_integer($status)) {
				$query .= " AND Status_Flag = $status ";
			} else {
				switch (strtolower($status)) {
		 			case 'open':
		 				$query .= " AND Status_Flag IN (0,2)";
		 				break;
		 			case 'lock':
		 				$query .= " AND Status_Flag IN (1,3,4,5)";
		 				break;
		 			case 'draft':
		 				$query .= " AND Status_Flag = 0";
		 				break;
		 			case 'pending':
		 				$query .= " AND Status_Flag = 1";
		 				break;
		 			case 'reject':
		 				$query .= " AND Status_Flag = 2";
		 				break;
		 			case 'approve':
		 				$query .= " AND Status_Flag = 3";
		 				break;
		 			case 'adjust':
		 				$query .= " AND Status_Flag = 4";
		 				break;
		 			case 'final':
		 				$query .= " AND Status_Flag = 5";
		 				break;
		 		}
			}
		}
		// echo $query ;
		return $this->pms->query($query)->row()->val;
	}

	public function count_achv_rkk_B($chief_nik = '', $month = 0, $begin = '', $end = '',$status = 'all')
	{
		$subquery = "SELECT RKKID 
									FROM PA_R_RKK 
									WHERE chief_nik = '$chief_nik' AND
										((BeginDate >= '$begin' AND EndDate <='$end') OR 
		 								(EndDate >= '$begin' AND EndDate <= '$end') OR 
		 								(BeginDate >= '$begin' AND BeginDate <='$end' ) OR
		 								(BeginDate <= '$begin' AND EndDate >= '$end'))";

		$query = "SELECT count(*) as val 
							FROM PA_T_RKKAchievement 
							WHERE RKKID IN
								( $subquery ) ";
		if ($month > 0 && $month < 13) {
			$query .= " AND Month = $month";
		}

		if (is_array($status)) {
			
			$query .= " AND Status_Flag IN (".implode(', ', $status).') ';
			
		} else {
			if (is_integer($status)) {
				$query .= " AND Status_Flag = $status ";
			} else {
				switch (strtolower($status)) {
		 			case 'open':
		 				$query .= " AND Status_Flag IN (0,2)";
		 				break;
		 			case 'lock':
		 				$query .= " AND Status_Flag IN (1,3,4,5)";
		 				break;
		 			case 'draft':
		 				$query .= " AND Status_Flag = 0";
		 				break;
		 			case 'pending':
		 				$query .= " AND Status_Flag = 1";
		 				break;
		 			case 'reject':
		 				$query .= " AND Status_Flag = 2";
		 				break;
		 			case 'approve':
		 				$query .= " AND Status_Flag = 3";
		 				break;
		 			case 'adjust':
		 				$query .= " AND Status_Flag = 4";
		 				break;
		 			case 'final':
		 				$query .= " AND Status_Flag = 5";
		 				break;
		 		}
			}
		}
		return $this->pms->query($query)->row()->val;
	}

	public function get_achv_rkk_B_list($chief_nik = '', $month = 0, $begin = '', $end = '',$status = 'all')
	{
		$subquery = "SELECT RKKID 
									FROM PA_R_RKK 
									WHERE chief_nik = '$chief_nik' AND
										((BeginDate >= '$begin' AND EndDate <='$end') OR 
		 								(EndDate >= '$begin' AND EndDate <= '$end') OR 
		 								(BeginDate >= '$begin' AND BeginDate <='$end' ) OR
		 								(BeginDate <= '$begin' AND EndDate >= '$end'))";

		$query = "SELECT a.*,
								u.Fullname,
								r.NIK,
								r.PositionID,
								r.isSAP
							FROM PA_T_RKKAchievement a
							INNER JOIN PA_T_RKK r 
								ON r.RKKID = a.RKKID
							INNER JOIN Core_M_User u 
								ON u.NIK = r.NIK  
							WHERE a.RKKID IN
								( $subquery ) ";
		if ($month > 0 && $month < 13) {
			$query .= " AND a.Month = $month";
		}

		if (is_array($status)) {
			$query .= " AND Status_Flag IN (".implode(', ', $status).') ';
			
		} else {
			if (is_integer($status)) {
				$query .= " AND Status_Flag = $status ";
			} else {
				switch (strtolower($status)) {
		 			case 'open':
		 				$query .= " AND Status_Flag IN (0,2)";
		 				break;
		 			case 'lock':
		 				$query .= " AND Status_Flag IN (1,3,4,5)";
		 				break;
		 			case 'draft':
		 				$query .= " AND Status_Flag = 0";
		 				break;
		 			case 'pending':
		 				$query .= " AND Status_Flag = 1";
		 				break;
		 			case 'reject':
		 				$query .= " AND Status_Flag = 2";
		 				break;
		 			case 'approve':
		 				$query .= " AND Status_Flag = 3";
		 				break;
		 			case 'adjust':
		 				$query .= " AND Status_Flag = 4";
		 				break;
		 			case 'final':
		 				$query .= " AND Status_Flag = 5";
		 				break;
		 		}
			}
		}
		return $this->pms->query($query)->result();
	}

	public function get_achv_rkk_list($rkk_id=0,$month = 12, $status = 'all')
	{
		$query = "SELECT *
							FROM PA_T_RKKAchievement
							WHERE RKKID = $rkk_id";
		if ($month > 0 && $month < 13) {
			$query .= " AND Month <= $month";
		}

		if (is_array($status)) {
			$query .= " AND Status_Flag IN (".implode(', ', $status).') ';
			
		} else {
			if (is_integer($status)) {
				$query .= " AND Status_Flag = $status ";
			} else {
				switch (strtolower($status)) {
		 			case 'open':
		 				$query .= " AND Status_Flag IN (0,2)";
		 				break;
		 			case 'lock':
		 				$query .= " AND Status_Flag IN (1,3,4,5)";
		 				break;
		 			case 'draft':
		 				$query .= " AND Status_Flag = 0";
		 				break;
		 			case 'pending':
		 				$query .= " AND Status_Flag = 1";
		 				break;
		 			case 'reject':
		 				$query .= " AND Status_Flag = 2";
		 				break;
		 			case 'approve':
		 				$query .= " AND Status_Flag = 3";
		 				break;
		 			case 'adjust':
		 				$query .= " AND Status_Flag = 4";
		 				break;
		 			case 'final':
		 				$query .= " AND Status_Flag = 5";
		 				break;
		 		}
			}
		}
 		$query .= " ORDER BY Month ASC";

		return $this->pms->query($query)->result();
	}

	public function get_achv_rkk_last($rkk_id=0,$month = 12, $status = 'all')
	{
		$month = (int) $month;
		$query = "SELECT TOP 1 *
							FROM PA_T_RKKAchievement
							WHERE RKKID = $rkk_id";
		if ($month > 0 && $month < 13) {
			$query .= " AND Month <= $month";
		}

		if (is_array($status)) {
			$query .= " AND Status_Flag IN (".implode(', ', $status).') ';
			
		} else {
			if (is_integer($status)) {
				$query .= " AND Status_Flag = $status ";
			} else {
				switch (strtolower($status)) {
		 			case 'open':
		 				$query .= " AND Status_Flag IN (0,2)";
		 				break;
		 			case 'lock':
		 				$query .= " AND Status_Flag IN (1,3,4,5)";
		 				break;
		 			case 'draft':
		 				$query .= " AND Status_Flag = 0";
		 				break;
		 			case 'pending':
		 				$query .= " AND Status_Flag = 1";
		 				break;
		 			case 'reject':
		 				$query .= " AND Status_Flag = 2";
		 				break;
		 			case 'approve':
		 				$query .= " AND Status_Flag = 3";
		 				break;
		 			case 'adjust':
		 				$query .= " AND Status_Flag = 4";
		 				break;
		 			case 'final':
		 				$query .= " AND Status_Flag = 5";
		 				break;
		 		}
			}
		}
 		$query .= " ORDER BY Month DESC";

		return $this->pms->query($query)->row();
	}

	//////////////
	// Project  //
	//////////////
	
	/**
	 * [menghitung Jumlah project yang diikuti]
	 * @param  string $nik   [description]
	 * @param  string $begin [description]
	 * @param  string $end   [description]
	 * @return [type]        [description]
	 */
	public function count_project($nik='',$begin='',$end='')
 	{
 		$query = "SELECT count(*) as val 
 							FROM proj_T_project
 							WHERE project_id IN (
 								SELECT project_id
 								FROM proj_T_member 
 								WHERE NIK = '$nik' AND
 									is_active = 1) AND
								((begin_date >= '$begin' AND end_date <='$end') OR 
 								(end_date >= '$begin' AND end_date <= '$end') OR 
 								(begin_date >= '$begin' AND begin_date <='$end' ) OR
 								(begin_date <= '$begin' AND end_date >= '$end')) AND 
								is_active = 1";
		return $this->pms->query($query)->row()->val;
 	}

 	public function get_project_list($nik='',$begin='',$end='')
 	{
 		$query = "SELECT m.nik,
 								m.kpi,
 								m.result,
 								m.role_name,
 								p.project_id,
 								p.project_name,
 								p.doc_num,
 								p.begin_date,
 								p.end_date
 							FROM proj_T_project p 
 							INNER JOIN  proj_T_member m 
 								ON p.project_id = m.project_id 
 							WHERE p.is_active = 1 and m.is_active = 1 AND
 								((p.begin_date >= '$begin' AND p.end_date <='$end') OR 
 								(p.end_date >= '$begin' AND p.end_date <= '$end') OR 
 								(p.begin_date >= '$begin' AND p.begin_date <='$end' ) OR
 								(p.begin_date <= '$begin' AND p.end_date >= '$end'))";
		if ($nik != '') {
			if (is_array($nik)) {
				$nik_ls = implode("', '", $nik);
				$query .= " AND m.nik IN ('".$nik_ls. "')";
			} else {
				$query .= " AND m.nik = '$nik'";

			}
		}
		return $this->pms->query($query)->result();

 	}

 	/**
 	 * [menjumlahkan seluruh hasil project yang diikuti]
 	 * @param  string $nik   [description]
 	 * @param  string $begin [description]
 	 * @param  string $end   [description]
 	 * @return [type]        [description]
 	 */
 	public function sum_result($nik='',$begin='',$end='')
 	{
 		$query = "SELECT SUM(result) as sum_result
 							FROM proj_T_member 
 							WHERE nik = '$nik' AND
 							  is_active = 1 AND
 							  project_id IN (
 							  	SELECT project_id 
 							  	FROM proj_T_project 
 							  	WHERE is_active = 1 AND
 							  	  ((begin_date >= '$begin' AND end_date <='$end') OR 
		 								(end_date >= '$begin' AND end_date <= '$end') OR 
		 								(begin_date >= '$begin' AND begin_date <='$end' ) OR
		 								(begin_date <= '$begin' AND end_date >= '$end')))";
		return $this->pms->query($query)->row()->sum_result;
 	}


 	/**
 	 * [get_Holder_row description]
 	 * @param  [type] $HolderID [description]
 	 * @param  [type] $isSAP    [description]
 	 * @return [type]           [description]
 	 */
 	
	public function get_Holder_row($HolderID,$isSAP)
	{
		if($isSAP)
		{
			$table ='SAP';
		}
		else
		{
			$table ="nonSAP";
		}
		$query = "SELECT * 
							FROM Core_V_Holder_$table 
							WHERE HolderID = $HolderID";
		return $this->pms->query($query)->row();
	}

	/**
	 * [get_User_row description]
	 * @param  [type] $NIK [description]
	 * @return [type]      [description]
	 */
	public function get_User_row($NIK)
	{
		
		$query = "SELECT u.*,r.Role,r.RoleID 
							FROM Core_M_User u, Core_M_Role r 
							WHERE u.NIK = '$NIK' and r.RoleID=u.RoleID";

		return $this->pms->query($query)->row();
	}

	public function lock_achv($nik='',$month = 0, $begin='',$end='')
	{
		$query = "UPDATE PA_T_RKKAchievement 
							SET Status_Flag = 5
							WHERE RKKID IN 
								(
								SELECT RKKID 
								FROM PA_T_RKK 
								WHERE NIK = '$nik' AND
									((BeginDate >= '$begin' AND EndDate <='$end') OR 
	 								(EndDate >= '$begin' AND EndDate <= '$end') OR 
	 								(BeginDate >= '$begin' AND BeginDate <='$end' ) OR
	 								(BeginDate <= '$begin' AND EndDate >= '$end'))
								) AND month <= $month AND Status_Flag IN (3,4)
 							";
 		$this->pms->query($query);
 		$period = str_pad($month,2,"0",STR_PAD_LEFT).substr($begin, 0,4);
 		
 		$query = "UPDATE bhv_t_header 
 							SET status = 5 
 							WHERE nik = '$nik' AND 
 							  periode = '$period' AND 
 							  status IN (3,4)";
 		$this->pms->query($query);

 		$query = "UPDATE proj_T_member 
 							SET status = 5
 							WHERE nik = '$nik' AND
 								project_id IN (
 									SELECT project_id
 									FROM proj_T_project
 									WHERE ((begin_date >= '$begin' AND end_date <='$end') OR 
		 								(end_date >= '$begin' AND end_date <= '$end') OR 
		 								(begin_date >= '$begin' AND begin_date <='$end' ) OR
		 								(begin_date <= '$begin' AND end_date >= '$end')) AND is_active = 1
 								)";
 		$this->pms->query($query);
		
	}

	public function get_data_individu_report($nik, $isSAP, $begin_date, $end_date)
	{

		if($isSAP)
		{
			$table ='SAP';
		}
		else
		{
			$table ="nonSAP";
		}

		$query = "SELECT DISTINCT B.nik,A.PositionName,A.PositionID, A.Holder_BeginDate, A.Holder_EndDate, DATEPART(year,B.begin_date) as 
					Periode FROM PMS.dbo.Core_V_Holder_SAP A INNER JOIN PMS.dbo.core_t_adjustment B 
				on A.NIK=B.nik WHERE ((B.begin_date >= '$begin_date' AND 
					B.end_date <='$end_date') OR (B.end_date >= '$begin_date' AND B.end_date <= '$end_date') 
				OR (B.begin_date >= '$begin_date' AND B.begin_date <='$end_date') OR B.begin_date <= '$begin_date' 
				AND B.end_date >= '$end_date') AND
				  ((A.Holder_BeginDate >= '$begin_date' AND A.Holder_EndDate <='$end_date') 
				  	OR (A.Holder_EndDate >= '$begin_date' AND A.Holder_EndDate <= '$end_date')
  					OR (A.Holder_BeginDate >= '$begin_date' AND A.Holder_BeginDate <='$end_date') OR A.Holder_BeginDate <= '$begin_date' 
  					AND A.Holder_EndDate >= '$end_date') AND B.nik = '$nik'";
		$query .= " AND
				  ((A.Post_BeginDate >= '$begin_date' AND A.Post_EndDate <='$end_date') 
				  	OR (A.Post_EndDate >= '$begin_date' AND A.Post_EndDate <= '$end_date')
  					OR (A.Post_BeginDate >= '$begin_date' AND A.Post_BeginDate <='$end_date') OR A.Post_BeginDate <= '$begin_date' 
  					AND A.Post_EndDate >= '$end_date')";
		// echo $query;
			
 		return $this->pms->query($query)->result();
	}

	public function get_bellcurve_list($nik_ls=array(),$begin,$end)
	{
		$nik = implode("', '", $nik_ls);
		$query = "SELECT c.cat_en AS long_text,
								c.cat_en_short AS short_text,
								(
									SELECT COUNT(*) 
									FROM core_t_adjustment a 
									WHERE a.before_value BETWEEN c.TPCLow AND c.TPCHigh AND 
										nik in ('$nik') AND  
										((a.begin_date >= '$begin' AND a.end_date <='$end') OR 
		 								(a.end_date >= '$begin' AND a.end_date <= '$end') OR 
		 								(a.begin_date >= '$begin' AND a.begin_date <='$end' ) OR 
		 								(a.begin_date <= '$begin' AND a.end_date >= '$end')) 
								) AS before,
								(
									SELECT COUNT(*) 
									FROM core_t_adjustment a 
									WHERE a.after_value BETWEEN c.TPCLow AND c.TPCHigh AND 
										nik in ('$nik') AND 
										((a.begin_date >= '$begin' AND a.end_date <='$end') OR 
		 								(a.end_date >= '$begin' AND a.end_date <= '$end') OR 
		 								(a.begin_date >= '$begin' AND a.begin_date <='$end' ) OR 
		 								(a.begin_date <= '$begin' AND a.end_date >= '$end')) 
								) AS after
							FROM Core_M_CodeColour c 
							WHERE c.TypeFlag = 2 AND 
								((c.BeginDate >= '$begin' AND c.EndDate <='$end') OR 
 								(c.EndDate >= '$begin' AND c.EndDate <= '$end') OR 
 								(c.BeginDate >= '$begin' AND c.BeginDate <='$end' ) OR 
 								(c.BeginDate <= '$begin' AND c.EndDate >= '$end')) 
							ORDER BY TPCLow ASC";
		return $this->pms->query($query)->result();
	}

	public function get_category_row($value=0.00,$begin='',$end='')
	{
		$query = "SELECT TOP 1 
								 cat_en_short,
								 cat_en,
								 Colour
							FROM Core_M_CodeColour 
							WHERE TypeFlag = 2 AND
								$value BETWEEN TPCLow AND TPCHigh AND
								((BeginDate >= '$begin' AND EndDate <='$end') OR 
 								(EndDate >= '$begin' AND EndDate <= '$end') OR 
 								(BeginDate >= '$begin' AND BeginDate <='$end' ) OR 
 								(BeginDate <= '$begin' AND EndDate >= '$end'))";
		return $this->pms->query($query)->row();
	}

	/**
	 * Report Agreement
	 */
	
	public function count_report_agree($nik,$period)
	{
		$query="SELECT COUNT(*) AS total_agree FROM tbl_report_individu WHERE nik =$nik and periode=$period";
		return $this->pms->query($query)->row();

	}

	public function process_agree($nik,$period)
	{
		$data = array('nik' => $nik, 'rpt_status' => 1, 'periode' =>$period);
		return $this->pms->INSERT('tbl_report_individu', $data);
	}


	/**
	 * Report IDP 
	 */
	

	function get_all_count_total_idp($PositionID,$isSAP,$BeginDate,$EndDate){
 		if($isSAP){
			$table ='SAP';
		}else{
			$table ="nonSAP";
		}

 		$query = "SELECT COUNT(*) as total_idp
 					FROM PA_T_RKK A inner join Core_M_User B on A.UserID=B.UserID 
					inner join Core_M_Position_$table C on C.PositionID=A.PositionID
					inner join IDP_T_Header D on D.RKKID=A.RKKID
					inner join IDP_T_Detail E on D.IDPID=E.IDPID
					inner join IDP_T_DevelopmentProgram F on E.IDPDetailID=F.IDPDetailID
					WHERE A.PositionID=$PositionID AND (( C.BeginDate<= '$BeginDate' AND C.EndDate>='$EndDate') 
					OR  C.BeginDate<= GETDATE() AND C.EndDate>='$EndDate')";
		return $this->pms->query($query)->row()->total_idp;
 	}

 	function get_all_count_total_idp_terealisasi($PositionID,$isSAP,$BeginDate,$EndDate){
 		if($isSAP){
			$table ='SAP';
		}else{
			$table ="nonSAP";
		}

 		$query = "SELECT COUNT(*) as total_idp_realisasi
 					FROM PA_T_RKK A inner join Core_M_User B on A.UserID=B.UserID 
					inner join Core_M_Position_$table C on C.PositionID=A.PositionID
					inner join IDP_T_Header D on D.RKKID=A.RKKID
					inner join IDP_T_Detail E on D.IDPID=E.IDPID
					inner join IDP_T_DevelopmentProgram F on E.IDPDetailID=F.IDPDetailID
					WHERE A.StatusFlag=3 AND A.PositionID=$PositionID AND Realization_BeginDate is not null AND Realization_EndDate is not NULL AND (( C.BeginDate<= '$BeginDate' AND C.EndDate>='$EndDate') 
					OR  C.BeginDate<= GETDATE() AND C.EndDate>='$EndDate')";
		return $this->pms->query($query)->row()->total_idp_realisasi;
 	}

 	function get_all_count_total_idp_not_terealisasi($PositionID,$isSAP,$BeginDate,$EndDate){
 		if($isSAP){
			$table ='SAP';
		}else{
			$table ="nonSAP";
		}

 		$query = "SELECT COUNT(*) as total_idp_not_realisasi
 					FROM PA_T_RKK A inner join Core_M_User B on A.UserID=B.UserID 
					inner join Core_M_Position_$table C on C.PositionID=A.PositionID
					inner join IDP_T_Header D on D.RKKID=A.RKKID
					inner join IDP_T_Detail E on D.IDPID=E.IDPID
					inner join IDP_T_DevelopmentProgram F on E.IDPDetailID=F.IDPDetailID
					WHERE A.StatusFlag=3 AND A.PositionID=$PositionID AND Realization_BeginDate is null AND Realization_EndDate is NULL AND (( C.BeginDate<= '$BeginDate' AND C.EndDate>='$EndDate') 
					OR  C.BeginDate<= GETDATE() AND C.EndDate>='$EndDate')";
		return $this->pms->query($query)->row()->total_idp_not_realisasi;
 	}


 	function get_total_idp_terealisasi_tepat_waktu($PositionID,$isSAP,$BeginDate,$EndDate){
 		if($isSAP){
			$table ='SAP';
		}else{
			$table ="nonSAP";
		}
		$query = "exec get_total_idp_tepat_waktu $PositionID, $isSAP, '$BeginDate', '$EndDate'";
 		return $this->pms->query($query)->row()->total_idp_realisasi_tepat_waktu;
 	}

 	function get_all_idp_terealisasi_tepat_waktu($PositionID,$isSAP,$BeginDate,$EndDate){
 		if($isSAP){
			$table ='SAP';
		}else{
			$table ="nonSAP";
		}

 		$query = "exec get_idp_tepat_waktu $PositionID, $isSAP, '$BeginDate', '$EndDate'";
 		$data_query = $this->pms->query($query)->result();
		$results = array();
		foreach ($data_query as $row){

			if($row->DevelopmentAreaType1ID==1)
			{
				$query_kompetensi = "SELECT KompetensiID, Nama  FROM TM_Kompetensi_Header A 
				INNER JOIN tm_Kompetensi B on 
				A.KompetensiHeaderID=B.KompetensiHeaderID WHERE KompetensiID=$row->DevelopmentAreaType";
				$DevelopmentAreaType = $this->portal->query($query_kompetensi)->row()->Nama;
 				$results[] = array(
                'DevelopmentAreaType' => $DevelopmentAreaType,
                'Realization_BeginDate' => $row->Realization_BeginDate,
                'Realization_EndDate' => $row->Realization_EndDate,
                'NIK' => $row->NIK,
                'Fullname' => $row->Fullname
	            );
			}
			else
			{
				$results[] = array(
                'DevelopmentAreaType' => $row->DevelopmentAreaType,
                'Realization_BeginDate' => $row->Realization_BeginDate,
                'Realization_EndDate' => $row->Realization_EndDate,
                'NIK' => $row->NIK,
                'Fullname' => $row->Fullname
	            );
			}
        }

 		return $results;
 	}

 	function get_idp_by_submitted_row_all($UserID,$PositionID,$isSAP,$BeginDate,$EndDate){
 		if($isSAP){
			$table ='SAP';
		}else{
			$table ="nonSAP";
		}

 		$query = "SELECT E.DevelopmentAreaType1ID, E.DevelopmentAreaType, A.UserID, D.StatusFlag, B.NIK,B.Fullname, F.Realization_BeginDate, F.Realization_EndDate 
 					FROM PA_T_RKK A inner join Core_M_User B on A.UserID=B.UserID 
					inner join Core_M_Position_$table C on C.PositionID=A.PositionID
					inner join IDP_T_Header D on D.RKKID=A.RKKID
					inner join IDP_T_Detail E on D.IDPID=E.IDPID
					inner join IDP_T_DevelopmentProgram F on E.IDPDetailID=F.IDPDetailID
					WHERE A.UserID=$UserID AND A.StatusFlag=3 AND A.PositionID=$PositionID AND
					F.Realization_BeginDate is not null AND F.Realization_EndDate is not null and F.Realization_Investment is not null
					AND(( C.BeginDate<= '$BeginDate' AND C.EndDate>='$EndDate') 
					OR  C.BeginDate<= GETDATE() AND C.EndDate>='$EndDate')";

		$data_query = $this->pms->query($query)->result();
		$results = array();
		foreach ($data_query as $row){

			if($row->DevelopmentAreaType1ID==1)
			{
				$query_kompetensi = "SELECT KompetensiID, Nama  FROM TM_Kompetensi_Header A 
				INNER JOIN tm_Kompetensi B on 
				A.KompetensiHeaderID=B.KompetensiHeaderID WHERE KompetensiID=$row->DevelopmentAreaType";
				$DevelopmentAreaType = $this->portal->query($query_kompetensi)->row()->Nama;
 				$results[] = array(
                'DevelopmentAreaType' => $DevelopmentAreaType,
                'Realization_BeginDate' => $row->Realization_BeginDate,
                'Realization_EndDate' => $row->Realization_EndDate,
                'NIK' => $row->NIK,
                'Fullname' => $row->Fullname
	            );
			}
			else
			{
				$results[] = array(
                'DevelopmentAreaType' => $row->DevelopmentAreaType,
                'Realization_BeginDate' => $row->Realization_BeginDate,
                'Realization_EndDate' => $row->Realization_EndDate,
                'NIK' => $row->NIK,
                'Fullname' => $row->Fullname
	            );
			}
        }

 		return $results;
 	}

 	function get_idp_by_not_submitted_row_all($UserID,$PositionID,$isSAP,$BeginDate,$EndDate){
 		if($isSAP){
			$table ='SAP';
		}else{
			$table ="nonSAP";
		}

 		$query = "SELECT E.DevelopmentAreaType1ID, E.DevelopmentAreaType, A.UserID, D.StatusFlag, B.NIK,B.Fullname, F.Realization_BeginDate, F.Realization_EndDate 
 					FROM PA_T_RKK A inner join Core_M_User B on A.UserID=B.UserID 
					inner join Core_M_Position_$table C on C.PositionID=A.PositionID
					inner join IDP_T_Header D on D.RKKID=A.RKKID
					inner join IDP_T_Detail E on D.IDPID=E.IDPID
					inner join IDP_T_DevelopmentProgram F on E.IDPDetailID=F.IDPDetailID
					WHERE A.UserID=$UserID AND A.StatusFlag=3 AND A.PositionID=$PositionID AND
					F.Realization_BeginDate is null AND F.Realization_EndDate is null and F.Realization_Investment is  null
					AND(( C.BeginDate<= '$BeginDate' AND C.EndDate>='$EndDate') 
					OR  C.BeginDate<= GETDATE() AND C.EndDate>='$EndDate')";

		$data_query = $this->pms->query($query)->result();
		$results = array();
		foreach ($data_query as $row){

			if($row->DevelopmentAreaType1ID==1)
			{
				$query_kompetensi = "SELECT KompetensiID, Nama  FROM TM_Kompetensi_Header A 
				INNER JOIN tm_Kompetensi B on 
				A.KompetensiHeaderID=B.KompetensiHeaderID WHERE KompetensiID=$row->DevelopmentAreaType";
				$DevelopmentAreaType = $this->portal->query($query_kompetensi)->row()->Nama;
 				$results[] = array(
                'DevelopmentAreaType' => $DevelopmentAreaType,
                'Realization_BeginDate' => $row->Realization_BeginDate,
                'Realization_EndDate' => $row->Realization_EndDate,
                'NIK' => $row->NIK,
                'Fullname' => $row->Fullname
	            );
			}
			else
			{
				$results[] = array(
                'DevelopmentAreaType' => $row->DevelopmentAreaType,
                'Realization_BeginDate' => $row->Realization_BeginDate,
                'Realization_EndDate' => $row->Realization_EndDate,
                'NIK' => $row->NIK,
                'Fullname' => $row->Fullname
	            );
			}
        }

 		return $results;
 	}

 	public function get_color_tpc_by_value($adjust_value)
 	{
 		$query = "SELECT * from Core_M_CodeColour where TPCHigh >= $adjust_value and TPCLow <=$adjust_value";
		return $this->pms->query($query)->row();
 	}


}
?>
