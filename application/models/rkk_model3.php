	<?php
class RKK_model3 extends Model{
	function __construct(){
		parent::__construct();
		$this->pms = $this->load->database('default', TRUE);
	}

	/**
	 * RKK/ Rencana Kerja Karyawan
	 */

	/**
	 * [menghitung rkk dari satu nik dalam satu retang waktu]
	 * @param  string $nik    [description]
	 * @param  string $begin  [tanggal mulai]
	 * @param  string $end    [tanggal selesai]
	 * @param  string $status [lower case]
	 * @return [int]          [description]
	 */
	public function count_rkk_nik($nik='', $begin='', $end='',$status = 'all')
	{
		$query = "SELECT COUNT(*) AS val
							FROM PA_T_RKK
							WHERE NIK='$nik' AND
								((BeginDate >= '$begin' AND EndDate <='$end') OR
								(EndDate >= '$begin' AND EndDate <= '$end') OR
								(BeginDate >= '$begin' AND BeginDate <='$end' ) OR
								(BeginDate <= '$begin' AND EndDate >= '$end'))";
		if (is_array($status)) {
			$query .= " AND statusFlag IN (". implode(', ', $status).')';

		} else {
			if (is_integer($status)) {
				$query .= " AND statusFlag = $status ";
			} else {
				switch (strtolower($status)) {
					case 'open':
						$query .= " AND statusFlag IN (0,2)";
						break;
					case 'lock':
						$query .= " AND statusFlag IN (1,3,4,5)";
						break;
					case 'draft':
						$query .= " AND statusFlag = 0";
						break;
					case 'pending':
						$query .= " AND statusFlag = 1";
						break;
					case 'reject':
						$query .= " AND statusFlag = 2";
						break;
					case 'approve':
						$query .= " AND statusFlag = 3";
						break;
					case 'adjust':
						$query .= " AND statusFlag = 4";
						break;
					case 'final':
						$query .= " AND statusFlag = 5";
						break;
				}
			}
		}

		//echo $query;
		return $this->pms->query($query)->row()->val;
	}

	/**
	 * [menghitung rkk dari satu posisi dalam satu retang waktu]
	 * @param  integer $post   [posisi ID]
	 * @param  integer $is_sap [1 = SAP; 0 = nonSAP]
	 * @param  string  $begin  [tanggal mulai]
	 * @param  string  $end    [tanggal selesai]
	 * @param  string  $status [lower case]
	 * @return [type]          [description]
	 */
	public function count_rkk_post($post=0,$is_sap=1, $begin='', $end='',$status='all')
	{
		$query = "SELECT COUNT(*) AS val
							FROM PA_T_RKK
							WHERE PositionID=$post AND
								isSAP='$is_sap' AND
								((BeginDate >= '$begin' AND EndDate <='$end') OR
								(EndDate >= '$begin' AND EndDate <= '$end') OR
								(BeginDate >= '$begin' AND BeginDate <='$end' ) OR
								(BeginDate <= '$begin' AND EndDate >= '$end'))";
		if (is_array($status)) {
			$query .= " AND statusFlag IN (". implode(', ', $status).')';

		} else {
			if (is_integer($status)) {
				$query .= " AND statusFlag = $status ";
			} else {
				switch (strtolower($status)) {
					case 'open':
						$query .= " AND statusFlag IN (0,2)";
						break;
					case 'lock':
						$query .= " AND statusFlag IN (1,3,4,5)";
						break;
					case 'draft':
						$query .= " AND statusFlag = 0";
						break;
					case 'pending':
						$query .= " AND statusFlag = 1";
						break;
					case 'reject':
						$query .= " AND statusFlag = 2";
						break;
					case 'approve':
						$query .= " AND statusFlag = 3";
						break;
					case 'adjust':
						$query .= " AND statusFlag = 4";
						break;
					case 'final':
						$query .= " AND statusFlag = 5";
						break;
				}
			}
		}
		return $this->pms->query($query)->row()->val;
	}

	/**
	 * [menghitung rkk dari satu nik dan posisi dalam satu retang waktu]
	 * @param  string  $nik    [description]
	 * @param  integer $post   [description]
	 * @param  integer $is_sap [description]
	 * @param  string  $begin  [description]
	 * @param  string  $end    [description]
	 * @param  string  $status [lower case]
	 * @return [type]          [description]
	 */
	public function count_rkk_holder($nik='',$post=0,$is_sap=1, $begin='', $end='',$status='all')
	{
		$query = "SELECT COUNT(*) AS val
							FROM PA_T_RKK
							WHERE NIK='$nik' AND
								PositionID=$post AND
								isSAP='$is_sap' AND
								((BeginDate >= '$begin' AND EndDate <='$end') OR
								(EndDate >= '$begin' AND EndDate <= '$end') OR
								(BeginDate >= '$begin' AND BeginDate <='$end' ) OR
								(BeginDate <= '$begin' AND EndDate >= '$end'))";
		if (is_array($status)) {

			$query .= " AND statusFlag IN (". implode(', ', $status).')';

		} else {
			if (is_integer($status)) {
				$query .= " AND statusFlag = $status ";
			} else {
				switch (strtolower($status)) {
					case 'open':
						$query .= " AND statusFlag IN (0,2)";
						break;
					case 'lock':
						$query .= " AND statusFlag IN (1,3,4,5)";
						break;
					case 'draft':
						$query .= " AND statusFlag = 0";
						break;
					case 'pending':
						$query .= " AND statusFlag = 1";
						break;
					case 'reject':
						$query .= " AND statusFlag = 2";
						break;
					case 'approve':
						$query .= " AND statusFlag = 3";
						break;
					case 'adjust':
						$query .= " AND statusFlag = 4";
						break;
					case 'final':
						$query .= " AND statusFlag = 5";
						break;
				}
			}
		}
		return $this->pms->query($query)->row()->val;
	}

	/**
	 * [megnhitung jumlah rkk bawahan]
	 * @param  string  $nik    [description]
	 * @param  integer $post   [description]
	 * @param  integer $is_sap [description]
	 * @param  string  $begin  [description]
	 * @param  string  $end    [description]
	 * @param  string  $status [description]
	 * @return [type]          [description]
	 */
	public function count_rkk_sub($nik='',$post=0,$is_sap=1,$begin = '',$end = '',$status = 'all')
	{
		$query = "SELECT count(*) as val
							FROM PA_T_RKK
							WHERE RKKID IN (
								SELECT RKKID
								FROM PA_R_RKK
								WHERE chief_nik = '$nik' AND
									chief_post_id = $post AND
									chief_is_sap = $is_sap AND
									((BeginDate >= '$begin' AND EndDate <='$end') OR
									(EndDate >= '$begin' AND EndDate <= '$end') OR
									(BeginDate >= '$begin' AND BeginDate <='$end' ) OR
									(BeginDate <= '$begin' AND EndDate >= '$end'))) AND
								((BeginDate >= '$begin' AND EndDate <='$end') OR
								(EndDate >= '$begin' AND EndDate <= '$end') OR
								(BeginDate >= '$begin' AND BeginDate <='$end' ) OR
								(BeginDate <= '$begin' AND EndDate >= '$end'))";
		if (is_array($status)) {

			$query .= " AND statusFlag IN (". implode(', ', $status).')';

		} else {
			if (is_integer($status)) {
				$query .= " AND statusFlag = $status ";
			} else {
				switch (strtolower($status)) {
					case 'open':
						$query .= " AND statusFlag IN (0,2)";
						break;
					case 'lock':
						$query .= " AND statusFlag IN (1,3,4,5)";
						break;
					case 'draft':
						$query .= " AND statusFlag = 0";
						break;
					case 'pending':
						$query .= " AND statusFlag = 1";
						break;
					case 'reject':
						$query .= " AND statusFlag = 2";
						break;
					case 'approve':
						$query .= " AND statusFlag = 3";
						break;
					case 'adjust':
						$query .= " AND statusFlag = 4";
						break;
					case 'final':
						$query .= " AND statusFlag = 5";
						break;
				}
			}
		}
		return  $this->pms->query($query)->row()->val;
	}

	public function count_rkk_nik_main($nik='', $begin='', $end='',$status='all')
	{
		$count = 0;
		$query = "SELECT *
							FROM PA_T_RKK
							WHERE NIK='$nik' AND
								((BeginDate >= '$begin' AND EndDate <='$end') OR
								(EndDate >= '$begin' AND EndDate <= '$end') OR
								(BeginDate >= '$begin' AND BeginDate <='$end' ) OR
								(BeginDate <= '$begin' AND EndDate >= '$end'))";
		if (is_array($status)) {
			$query .= " AND statusFlag IN (". implode(', ', $status).')';

		} else {
			if (is_integer($status)) {
				$query .= " AND statusFlag = $status ";
			} else {
				switch (strtolower($status)) {
					case 'open':
						$query .= " AND statusFlag IN (0,2)";
						break;
					case 'lock':
						$query .= " AND statusFlag IN (1,3,4,5)";
						break;
					case 'draft':
						$query .= " AND statusFlag = 0";
						break;
					case 'pending':
						$query .= " AND statusFlag = 1";
						break;
					case 'reject':
						$query .= " AND statusFlag = 2";
						break;
					case 'approve':
						$query .= " AND statusFlag = 3";
						break;
					case 'adjust':
						$query .= " AND statusFlag = 4";
						break;
					case 'final':
						$query .= " AND statusFlag = 5";
						break;
				}
			}
		}
		$temp = $this->pms->query($query)->result();

		foreach ($temp as $row) {
			if ($row->isSAP) {
				$table = " Core_M_Holder_SAP";
			} else {
				$table = " Core_M_Holder_nonSAP";

			}
			$query = "SELECT TOP 1 isMain
								FROM $table
								WHERE NIK = $row->NIK AND
									PositionID = $row->PositionID
								ORDER BY EndDate DESC, BeginDate DESC";
			if ($this->pms->query($query)->row()->isMain) {
				$count++;
			}
		}
		return $count;

	}

	/**
	 * [mendapatkan rkk dari satu nik]
	 * @param  string $nik    [description]
	 * @param  string $begin  [description]
	 * @param  string $end    [description]
	 * @param  string $status [lower case]
	 * @return [type]         [description]
	 */
	public function get_rkk_nik_list($nik='', $begin='', $end='',$status='all')
	{
		$query = "SELECT *
							FROM PA_T_RKK
							WHERE NIK='$nik' AND
								((BeginDate >= '$begin' AND EndDate <='$end') OR
								(EndDate >= '$begin' AND EndDate <= '$end') OR
								(BeginDate >= '$begin' AND BeginDate <='$end' ) OR
								(BeginDate <= '$begin' AND EndDate >= '$end'))";
		if (is_array($status)) {

			$query .= " AND statusFlag IN (". implode(', ', $status).')';

		} else {
			if (is_integer($status)) {
				$query .= " AND statusFlag = $status ";
			} else {
				switch (strtolower($status)) {
					case 'open':
						$query .= " AND statusFlag IN (0,2)";
						break;
					case 'lock':
						$query .= " AND statusFlag IN (1,3,4,5)";
						break;
					case 'draft':
						$query .= " AND statusFlag = 0";
						break;
					case 'pending':
						$query .= " AND statusFlag = 1";
						break;
					case 'reject':
						$query .= " AND statusFlag = 2";
						break;
					case 'approve':
						$query .= " AND statusFlag = 3";
						break;
					case 'adjust':
						$query .= " AND statusFlag = 4";
						break;
					case 'final':
						$query .= " AND statusFlag = 5";
						break;
				}
			}
		}
		$query .= ' ORDER BY BeginDate';
		// echo $query;
		return $this->pms->query($query)->result();
	}

	public function get_rkk_nik_main_list($nik='', $begin='', $end='',$status='all')
	{
		$query = "SELECT *
							FROM PA_T_RKK
							WHERE NIK='$nik' AND
								((BeginDate >= '$begin' AND EndDate <='$end') OR
								(EndDate >= '$begin' AND EndDate <= '$end') OR
								(BeginDate >= '$begin' AND BeginDate <='$end' ) OR
								(BeginDate <= '$begin' AND EndDate >= '$end'))";
		if (is_array($status)) {
			$query .= " AND statusFlag IN (". implode(', ', $status).')';

		} else {
			if (is_integer($status)) {
				$query .= " AND statusFlag = $status ";
			} else {
				switch (strtolower($status)) {
					case 'open':
						$query .= " AND statusFlag IN (0,2)";
						break;
					case 'lock':
						$query .= " AND statusFlag IN (1,3,4,5)";
						break;
					case 'draft':
						$query .= " AND statusFlag = 0";
						break;
					case 'pending':
						$query .= " AND statusFlag = 1";
						break;
					case 'reject':
						$query .= " AND statusFlag = 2";
						break;
					case 'approve':
						$query .= " AND statusFlag = 3";
						break;
					case 'adjust':
						$query .= " AND statusFlag = 4";
						break;
					case 'final':
						$query .= " AND statusFlag = 5";
						break;
				}
			}
		}
		$query .= ' ORDER BY BeginDate';
		$temp = $this->pms->query($query)->result();
		$result = array();
		foreach ($temp as $row) {
			if ($row->isSAP) {
				$table = " Core_M_Holder_SAP";
			} else {
				$table = " Core_M_Holder_nonSAP";

			}
			$query = "SELECT TOP 1 isMain
								FROM $table
								WHERE NIK = $row->NIK AND
									PositionID = $row->PositionID
								ORDER BY EndDate DESC, BeginDate DESC";
			if ($this->pms->query($query)->row()->isMain) {
				$result[] = $row;
			}
		}
		return $result;

	}

	/**
	 * [mendapatkan rkk dari satu posisi]
	 * @param  integer $post   [description]
	 * @param  integer $is_sap [description]
	 * @param  string  $begin  [description]
	 * @param  string  $end    [description]
	 * @param  string  $status [lower case]
	 * @return [type]          [description]
	 */
	public function get_rkk_post_list($post=0,$is_sap=1, $begin='', $end='',$status='all')
	{
		$query = "SELECT *
							FROM PA_T_RKK
							WHERE PositionID = $post AND
								isSAP = '$is_sap' AND
								((BeginDate >= '$begin' AND EndDate <='$end') OR
								(EndDate >= '$begin' AND EndDate <= '$end') OR
								(BeginDate >= '$begin' AND BeginDate <='$end' ) OR
								(BeginDate <= '$begin' AND EndDate >= '$end'))";
		if (is_array($status)) {

			$query .= " AND statusFlag IN (". implode(', ', $status).')';

		} else {
			if (is_integer($status)) {
				$query .= " AND statusFlag = $status ";
			} else {
				switch (strtolower($status)) {
					case 'open':
						$query .= " AND statusFlag IN (0,2)";
						break;
					case 'lock':
						$query .= " AND statusFlag IN (1,3,4,5)";
						break;
					case 'draft':
						$query .= " AND statusFlag = 0";
						break;
					case 'pending':
						$query .= " AND statusFlag = 1";
						break;
					case 'reject':
						$query .= " AND statusFlag = 2";
						break;
					case 'approve':
						$query .= " AND statusFlag = 3";
						break;
					case 'adjust':
						$query .= " AND statusFlag = 4";
						break;
					case 'final':
						$query .= " AND statusFlag = 5";
						break;
				}
			}
		}
		return $this->pms->query($query)->result();
	}

	/**
	 * [mendapatkan rkk dari nik dan posisi]
	 * @param  string  $nik    [description]
	 * @param  integer $post   [description]
	 * @param  integer $is_sap [description]
	 * @param  string  $begin  [description]
	 * @param  string  $end    [description]
	 * @param  string  $status [lower case]
	 * @return [type]          [description]
	 */
	public function get_rkk_holder_list($nik='',$post=0,$is_sap=1, $begin='', $end='',$status='all')
	{
		$query = "SELECT *
							FROM PA_T_RKK
							WHERE NIK='$nik' AND
								PositionID = $post AND
								isSAP = '$is_sap' AND
								((BeginDate >= '$begin' AND EndDate <='$end') OR
								(EndDate >= '$begin' AND EndDate <= '$end') OR
								(BeginDate >= '$begin' AND BeginDate <='$end' ) OR
								(BeginDate <= '$begin' AND EndDate >= '$end'))";
		if (is_array($status)) {

			$query .= " AND statusFlag IN (". implode(', ', $status).')';

		} else {
			if (is_integer($status)) {
				$query .= " AND statusFlag = $status ";
			} else {
				switch (strtolower($status)) {
					case 'open':
						$query .= " AND statusFlag IN (0,2)";
						break;
					case 'lock':
						$query .= " AND statusFlag IN (1,3,4,5)";
						break;
					case 'draft':
						$query .= " AND statusFlag = 0";
						break;
					case 'pending':
						$query .= " AND statusFlag = 1";
						break;
					case 'reject':
						$query .= " AND statusFlag = 2";
						break;
					case 'approve':
						$query .= " AND statusFlag = 3";
						break;
					case 'adjust':
						$query .= " AND statusFlag = 4";
						break;
					case 'final':
						$query .= " AND statusFlag = 5";
						break;
				}
			}
		}
		return $this->pms->query($query)->result();
	}

	public function get_rkk_nik_last($nik='', $begin='', $end='',$status='all')
	{
		$query = "SELECT Top 1 *
							FROM PA_T_RKK
							WHERE NIK='$nik' AND
								((BeginDate >= '$begin' AND EndDate <='$end') OR
								(EndDate >= '$begin' AND EndDate <= '$end') OR
								(BeginDate >= '$begin' AND BeginDate <='$end' ) OR
								(BeginDate <= '$begin' AND EndDate >= '$end'))";
		if (is_array($status)) {

			$query .= " AND statusFlag IN (". implode(', ', $status).')';

		} else {
			if (is_integer($status)) {
				$query .= " AND statusFlag = $status ";
			} else {
				switch (strtolower($status)) {
					case 'open':
						$query .= " AND statusFlag IN (0,2)";
						break;
					case 'lock':
						$query .= " AND statusFlag IN (1,3,4,5)";
						break;
					case 'draft':
						$query .= " AND statusFlag = 0";
						break;
					case 'pending':
						$query .= " AND statusFlag = 1";
						break;
					case 'reject':
						$query .= " AND statusFlag = 2";
						break;
					case 'approve':
						$query .= " AND statusFlag = 3";
						break;
					case 'adjust':
						$query .= " AND statusFlag = 4";
						break;
					case 'final':
						$query .= " AND statusFlag = 5";
						break;
				}
			}
		}
		$query .= " ORDER BY EndDate DESC";
		return $this->pms->query($query)->row();
	}

	/**
	 * [mendapatkan rkk terakhir dari nik dan posisi]
	 * @param  string  $nik    [description]
	 * @param  integer $post   [description]
	 * @param  integer $is_sap [description]
	 * @param  string  $begin  [description]
	 * @param  string  $end    [description]
	 * @param  string  $status [lower case]
	 * @return [type]          [description]
	 */
	public function get_rkk_holder_last($nik='',$post=0,$is_sap=1, $begin='', $end='',$status='all')
	{
		$query = "SELECT TOP 1 r.*, u.Fullname
							FROM PA_T_RKK r
							JOIN Core_M_User u
								ON u.NIK = r.NIK
							WHERE R.NIK='$nik' AND
								R.PositionID = $post AND
								R.isSAP = '$is_sap' AND
								((R.BeginDate >= '$begin' AND R.EndDate <='$end') OR
								(R.EndDate >= '$begin' AND R.EndDate <= '$end') OR
								(R.BeginDate >= '$begin' AND R.BeginDate <='$end' ) OR
								(R.BeginDate <= '$begin' AND R.EndDate >= '$end'))";
		if (is_array($status)) {
			$in = ' AND r.statusFlag IN (';
			foreach ($status as $key => $value) {
				$in .= $value.', ';
			}

			$in_len = strlen($in) - 2;
			$in = substr($in, 0, $in_len).')';
			$query .= $in;

		} else {
			if (is_integer($status)) {
				$query .= " AND r.statusFlag = $status ";
			} else {
				switch (strtolower($status)) {
					case 'open':
						$query .= " AND r.statusFlag IN (0,2)";
						break;
					case 'lock':
						$query .= " AND r.statusFlag IN (1,3,4,5)";
						break;
					case 'draft':
						$query .= " AND r.statusFlag = 0";
						break;
					case 'pending':
						$query .= " AND r.statusFlag = 1";
						break;
					case 'reject':
						$query .= " AND r.statusFlag = 2";
						break;
					case 'approve':
						$query .= " AND r.statusFlag = 3";
						break;
					case 'adjust':
						$query .= " AND r.statusFlag = 4";
						break;
					case 'final':
						$query .= " AND r.statusFlag = 5";
						break;
				}
			}
		}
		$query .= " ORDER BY r.EndDate DESC, r.BeginDate DESC";
		// echo '<br>'.$query;
		return $this->pms->query($query)->row();
	}

	/**
	 * [mendapatkan rkk bawahan]
	 * @param  string  $nik    [description]
	 * @param  integer $post   [description]
	 * @param  integer $is_sap [description]
	 * @param  string  $begin  [description]
	 * @param  string  $end    [description]
	 * @param  string  $status [lower case]
	 * @return [type]          [description]
	 */
	public function get_rkk_sub_list($nik='',$post=0,$is_sap=1,$begin = '',$end = '',$status='all')
	{

		$this->pms->select('tr.*');
		$this->pms->select('u.Fullname');
		$this->pms->select('p.PositionName as post_name');
		$this->pms->from('PA_T_RKK tr');
		$this->pms->join('Core_M_User u', 'tr.NIK = u.NIK');
		$this->pms->join('Core_M_Position_SAP p', 'tr.PositionID = p.PositionID');
		$this->pms->where("tr.RKKID IN (
								SELECT RKKID
								FROM PA_R_RKK
								WHERE chief_nik = '$nik' AND
									chief_post_id = $post AND
									chief_is_sap = $is_sap AND
									((BeginDate >= '$begin' AND EndDate <='$end') OR
									(EndDate >= '$begin' AND EndDate <= '$end') OR
									(BeginDate >= '$begin' AND BeginDate <='$end' ) OR
									(BeginDate <= '$begin' AND EndDate >= '$end')))");
		$this->pms->where("((tr.BeginDate >= '$begin' AND tr.EndDate <='$end') OR
								(tr.EndDate >= '$begin' AND tr.EndDate <= '$end') OR
								(tr.BeginDate >= '$begin' AND tr.BeginDate <='$end' ) OR
								(tr.BeginDate <= '$begin' AND tr.EndDate >= '$end'))");
		$this->pms->where("((p.BeginDate >= '$begin' AND p.EndDate <='$end') OR
								(p.EndDate >= '$begin' AND p.EndDate <= '$end') OR
								(p.BeginDate >= '$begin' AND p.BeginDate <='$end' ) OR
								(p.BeginDate <= '$begin' AND p.EndDate >= '$end'))");
		$this->pms->order_by('tr.NIK');

		if (is_array($status)) {
			$this->pms->where_in('tr.statusFlag', $status);


		} else {
			if (is_integer($status)) {
				$this->pms->where('tr.statusFlag', $status);

			} else {
				switch (strtolower($status)) {
					case 'open':
						$this->pms->where_in('tr.statusFlag', array(0,2));

						break;
					case 'lock':
						$this->pms->where_in('tr.statusFlag', array(1,3,4,5));

						break;
					case 'draft':
						$this->pms->where('tr.statusFlag', 0);
						break;
					case 'pending':
						$this->pms->where('tr.statusFlag', 1);
						break;
					case 'reject':
						$this->pms->where('tr.statusFlag', 2);

						break;
					case 'approve':
						$this->pms->where('tr.statusFlag', 3);

						break;
					case 'adjust':
						$this->pms->where('tr.statusFlag', 4);
						break;
					case 'final':
						$this->pms->where('tr.statusFlag', 5);
						break;
				}
			}
		}

		return $this->pms->get()->result();
	}

	/**
	 * [mendapatkan detail rkk]
	 * @param  integer $rkk_id [description]
	 * @return [type]          [description]
	 */
	public function get_rkk_row($rkk_id=0)
	{
		$query = "SELECT * FROM PA_T_RKK WHERE RKKID=$rkk_id";
		return $this->pms->query($query)->row();
	}

	/**
	 * [menambahkan rkk baru beserta relasinya]
	 * @param string  $nik          [description]
	 * @param integer $post         [description]
	 * @param integer $is_sap       [description]
	 * @param string  $chief_nik    [description]
	 * @param integer $chief_post   [description]
	 * @param integer $chief_is_sap [description]
	 * @param string  $begin        [description]
	 * @param string  $end          [description]
	 */
	public function add_rkk($nik='',$post=0,$is_sap=1,$chief_nik='',$chief_post=0, $chief_is_sap =1, $begin='',$end='')
	{
		$query = "INSERT INTO PA_T_RKK (
								NIK,
								PositionID,
								isSAP,
								BeginDate,
								EndDate,
								statusFlag
							) VALUES (
								'$nik',
								$post,
								$is_sap,
								'$begin',
								'$end',
								0
							);";
		$this->pms->query($query);

		$query = "SELECT MAX(RKKID) as RKKID FROM PA_T_RKK";
		$rkk_id = $this->pms->query($query)->row()->RKKID;
		if ($chief_nik !='' && $chief_post >0 ) {
			$this->add_rkk_rel($rkk_id,$chief_nik,$chief_post,$chief_is_sap,$begin,$end);
			# code...
		}

		return $rkk_id;
	}

	/**
	 * [edit rkk]
	 * @param  integer $rkk_id [description]
	 * @param  string  $begin  [description]
	 * @param  string  $end    [description]
	 * @return [type]          [description]
	 */
	public function edit_rkk($rkk_id=0,$begin='',$end='')
	{
		$query = "UPDATE PA_T_RKK SET
								BeginDate = '$begin',
								EndDate = '$end'
							WHERE RKKID = '$rkk_id'";
		$this->pms->query($query);

		$query = "UPDATE PA_R_RKK SET
								BeginDate = '$begin'
							WHERE RKKID = '$rkk_id' AND
								BeginDate < '$begin'";
		$this->pms->query($query);

		$query = "UPDATE PA_R_RKK SET
								EndDate = '$end'
							WHERE RKKID = '$rkk_id' AND
								EndDate > '$end'";
		$this->pms->query($query);
	}

	/**
	 * [mengubah status RKK]
	 * @param  integer $rkk_id [description]
	 * @param  [type]  $status [0 = draft, 1=assign, 2=reject, 3= agree]
	 * @return [type]          [description]
	 */
	public function edit_rkk_status($rkk_id=0,$status)
	{
		$query="UPDATE PA_T_RKK SET
						statusFlag=$status
						WHERE RKKID=$rkk_id";
		$this->pms->query($query);
	}

	/**
	 * [mendelimit RKK]
	 * @param  integer $rkk_id [description]
	 * @param  string  $end    [description]
	 * @return [type]          [description]
	 */
	public function delimit_rkk($rkk_id=0,$end='')
	{
		// DO Delimit RKK
		$query = "UPDATE PA_T_RKK SET
								EndDate = '$end'
							WHERE RKKID = '$rkk_id'";
		$this->pms->query($query);
		// DO Delimit Relasi RKK
		$query = "UPDATE PA_R_RKK SET
								EndDate = '$end'
							WHERE RKKID = $rkk_id AND
								EndDate > '$end'";
		$this->pms->query($query);

		// DO Delimit Relasi KPI
		$query = "UPDATE PA_R_KPI SET
								EndDate = '$end'
							WHERE (KPIID IN (SELECT KPIID FROM PA_T_KPI WHERE RKKID = $rkk_id AND EndDate>'$end') OR
								chief_kpi_id IN (SELECT KPIID FROM PA_T_KPI WHERE RKKID = $rkk_id AND EndDate>'$end')) AND
								EndDate > '$end'";
		$this->pms->query($query);

		// DO Delimit KPI
		$query = "UPDATE PA_T_KPI SET
								EndDate = '$end'
							WHERE RKKID = $rkk_id AND
								EndDate > '$end'";
		$this->pms->query($query);

	}

	/**
	 * [menghapus rkk]
	 * @param  integer $rkk_id [description]
	 * @return [type]          [description]
	 */
	public function remove_rkk($rkk_id=0)
	{
		$query = "DELETE FROM PA_T_RKK
							WHERE RKKID = $rkk_id";
		$this->pms->query($query);
	}

	public function transfer_rkk($source_rkk_id=0,$target_rkk_id=0)
	{
		$s_rkk = $this->get_rkk_row($source_rkk_id);
		$t_rkk = $this->get_rkk_row($target_rkk_id);
		$begin = $s_rkk->BeginDate;
		$end = $s_rkk->EndDate;

		// KOSONGKAN KPI & TARGET Tujuan
		$this->pms->where('KPIID in (SELECT KPIID FROM PA_T_KPI WHERE RKKID = '.$target_rkk_id.')');
		$this->pms->delete('PA_T_RKKDetailTarget');

		$this->pms->where('RKKID', $target_rkk_id);
		$this->pms->delete('PA_T_KPI');
		// -----------------------------------------------/


		// GET KPI List
		$this->pms->where('RKKID', $source_rkk_id);
		$this->pms->where("((BeginDate >= '$begin' AND EndDate <='$end') OR
								(EndDate >= '$begin' AND EndDate <= '$end') OR
								(BeginDate >= '$begin' AND BeginDate <='$end' ) OR
								(BeginDate <= '$begin' AND EndDate >= '$end'))");
		$this->pms->from('PA_T_KPI');
		$s_kpi = $this->pms->get()->result();

		// -----------------------------------------------/

		foreach ($s_kpi as $kpi) {

			$target  = $this->get_target_list($kpi->KPIID);
			$targets = array();
			foreach ($target as $row) {
				$targets[$row->Month] = $row->Target;
			}
			$data = array('EndDate' => $t_rkk->EndDate );
			$this->pms->where('SasaranStrategisID',$kpi->SasaranStrategisID);
			$this->pms->update('PA_T_SasaranStrategis', $data);

			$this->add_kpi(
				$target_rkk_id,
				$kpi->KPIGenericID,
				$kpi->SasaranStrategisID,
				$kpi->SatuanID ,
				$kpi->PCFormulaID ,
				$kpi->YTDID ,
				$kpi->KPI,
				$kpi->Description,
				$kpi->Bobot,
				$kpi->Baseline,
				$t_rkk->BeginDate,
				$t_rkk->EndDate,
				$targets
			);

		}
	}

	/**
	 * [mengcopy rkk seseorang kepada orang lain (nik & posisi) berserta semua KPI dan target serta hubungan dengan rkk atasannya]
	 * @param  [type] $source_rkk    [description]
	 * @param  [type] $target_nik    [description]
	 * @param  [type] $target_post   [description]
	 * @param  [type] $target_is_sap [description]
	 * @param  [type] $begin         [description]
	 * @param  [type] $end           [description]
	 * @return [type]                [description]
	 */
	public function copy_rkk($source_rkk,$target_nik,$target_post,$target_is_sap,$begin,$end)
	{
		$rkk     = $this->get_rkk_row($source_rkk);
		$rkk_rel = $this->get_rkk_rel_last($source_rkk,$begin,$end);
		$kpi_ls  = $this->get_kpi_list($source_rkk,$begin,$end);

		$c_rkk = $this->count_rkk_holder($target_nik,$target_post,$target_is_sap,$begin,$end);
		if ($c_rkk == 0) {
			$rkk_id = $this->add_rkk($target_nik,$target_post,$target_is_sap,$rkk_rel->chief_nik,$rkk_rel->chief_post, $rkk_rel->chief_is_sap, $begin,$end);
		} else {
			$rkk_id = $this->get_rkk_holder_last($target_nik,$target_post,$target_is_sap,$begin,$end)->RKKID;

			// DO Hapus semua Relasi KPI dengan KPI Atasan
			$query = "DELETE FROM PA_R_KPI WHERE KPIID IN (SELECT KPIID FROM PA_T_KPI WHERE RKKID = $rkk_id)";
			$this->pms->query($query);

			// DO Hapus semua KPI
			$query = "DELETE FROM PA_T_KPI WHERE RKKID = $rkk_id";
			$this->pms->query($query);
		}


		foreach ($kpi_ls as $kpi) {
			$c_rel   = $this->count_kpi_rel_BA($kpi->KPIID,$begin,$end);
			$target  = $this->get_target_list($kpi->KPIID);
			$targets = array();
			foreach ($target as $row) {
				$targets[$row->Month] = $row->Target;
			}

			if ($c_rel>0) {
				$kpi_rel = $this->get_kpi_rel_last($kpi->KPIID,$begin,$end);

				$this->add_kpi(
					$rkk_id,
					$kpi->KPIGenericID,
					$kpi->SasaranStrategisID,
					$kpi->SatuanID ,
					$kpi->PCFormulaID ,
					$kpi->YTDID ,
					$kpi->KPI,
					$kpi->Description,
					$kpi->Bobot,
					$kpi->Baseline,
					$kpi->KPI_BeginDate,
					$kpi->KPI_EndDate,
					$targets,
					$kpi_rel->chief_kpi_id,
					$kpi_rel->ref_weight,
					$kpi_rel->ref_id,
					$kpi_rel->BeginDate,
					$kpi_rel->EndDate
				);
			} else {
				$this->add_kpi(
					$rkk_id,
					$kpi->KPIGenericID,
					$kpi->SasaranStrategisID,
					$kpi->SatuanID ,
					$kpi->PCFormulaID ,
					$kpi->YTDID ,
					$kpi->KPI,
					$kpi->Description,
					$kpi->Bobot,
					$kpi->Baseline,
					$kpi->KPI_BeginDate,
					$kpi->KPI_EndDate,
					$targets
				);
			}
		}
	}

	/**
	 * Hubungan RKK dengan Atasan
	 */

	public function get_rkk_rel_AB_list($nik_A='',$post_id_A=0,$is_sap_A=true,$begin = '',$end = '',$status ='all')
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
							WHERE r.chief_nik = '$nik_A' AND
								r.chief_post_id = $post_id_A AND
								r.chief_is_sap = $is_sap_A AND
								((r.BeginDate >= '$begin' AND r.EndDate <='$end') OR
								(r.EndDate >= '$begin' AND r.EndDate <= '$end') OR
								(r.BeginDate >= '$begin' AND r.BeginDate <='$end' ) OR
								(r.BeginDate <= '$begin' AND r.EndDate >= '$end'))";

		if (is_array($status)) {
			$in = ' AND t.statusFlag IN (';
			foreach ($status as $key => $value) {
				$in .= $value.', ';
			}

			$in_len = strlen($in) - 2;
			$in = substr($in, 0, $in_len).')';
			$query .= $in;

		} else {
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

	/**
	 * [mendapatkan daftar relasi rkk dengan Atasan]
	 * @param  integer $rkk_id [description]
	 * @param  string  $begin  [description]
	 * @param  string  $end    [description]
	 * @return [type]          [description]
	 */
	public function get_rkk_rel_BA_list($rkk_id_B=0,$begin = '',$end = '')
	{

		$query = "SELECT r.*,
								m.fullname
							FROM PA_R_RKK r
							JOIN Core_M_User m
								ON r.chief_nik = m.NIK
							WHERE r.RKKID = $rkk_id_B AND
								((r.BeginDate >= '$begin' AND r.EndDate <='$end') OR
								(r.EndDate >= '$begin' AND r.EndDate <= '$end') OR
								(r.BeginDate >= '$begin' AND r.BeginDate <='$end' ) OR
								(r.BeginDate <= '$begin' AND r.EndDate >= '$end'))";

		return  $this->pms->query($query)->result();
	}

	/**
	 * [mendapatkan relasi rkk terakhir bawah ke atas]
	 * @param  integer $rkk_id [description]
	 * @param  string  $begin  [description]
	 * @param  string  $end    [description]
	 * @return [type]          [description]
	 */
	public function get_rkk_rel_last($rkk_id=0,$begin = '',$end = '')
	{
		$query = "SELECT TOP 1 *
							FROM PA_R_RKK
							WHERE RKKID = $rkk_id AND
								((BeginDate >= '$begin' AND EndDate <='$end') OR
								(EndDate >= '$begin' AND EndDate <= '$end') OR
								(BeginDate >= '$begin' AND BeginDate <='$end' ) OR
								(BeginDate <= '$begin' AND EndDate >= '$end'))
							ORDER BY EndDate Desc, BeginDate Desc";

		return  $this->pms->query($query)->row();
	}

	/**
	 * [mendapatkan detail relasi rkk]
	 * @param  integer $r_rrk_id [description]
	 * @return [type]            [description]
	 */
	public function get_rkk_rel_row($r_rrk_id=0)
	{
		$query = "SELECT * FROM PA_R_RKK  WHERE R_RKKID = $r_rrk_id";
		return  $this->pms->query($query)->row();

	}

	public function get_rkkRel_byRKKnSpr($rkk_id=0, $chief_nik='',$chief_post_id=0)
	{

		$query = "SELECT * FROM PA_R_RKK WHERE RKKID = $rkk_id AND chief_nik = '$chief_nik' AND chief_post_id = '$chief_post_id' AND chief_is_sap = 1 ";
		return $this->pms->query($query)->row();
	}

	public function check_rkkRel($rkk_id=0, $chief_nik='',$chief_post_id=0)
	{

		$query = "SELECT COUNT(*) as val FROM PA_R_RKK WHERE RKKID = $rkk_id AND chief_nik = '$chief_nik' AND chief_post_id = '$chief_post_id' AND chief_is_sap = 1 ";
		return $this->pms->query($query)->row()->val;
	}

	/**
	 * [menambah relasi rkk]
	 * @param integer $rkk_id       [description]
	 * @param string  $chief_nik    [description]
	 * @param integer $chief_post   [description]
	 * @param integer $chief_is_sap [description]
	 * @param string  $begin        [description]
	 * @param string  $end          [description]
	 */
	public function add_rkk_rel($rkk_id=0, $chief_nik='',$chief_post=0, $chief_is_sap =1, $begin='',$end='')
	{
		$query = "INSERT INTO PA_R_RKK (
								RKKID,
								chief_nik,
								chief_post_id,
								chief_is_sap,
								BeginDate,
								EndDate
							) VALUES (
								$rkk_id,
								'$chief_nik',
								$chief_post,
								$chief_is_sap,
								'$begin',
								'$end'
							)";

		$this->pms->query($query);
	}

	/**
	 * [edit relasi rkk]
	 * @param  integer $r_rrk_id [description]
	 * @param  string  $begin    [description]
	 * @param  string  $end      [description]
	 * @return [type]            [description]
	 */
	public function edit_rkk_rel($r_rrk_id=0, $begin='', $end='')
	{
		$query = "UPDATE PA_R_RKK SET
								BeginDate = '$begin',
								EndDate = '$end'
							WHERE R_RKKID = $r_rrk_id";
		$this->pms->query($query);
	}

	/**
	 * [Delimit Relasi RKK dengan Atasan]
	 * @param  integer $r_rrk_id [description]
	 * @param  string  $end      [description]
	 * @return [type]            [description]
	 */
	public function delimit_rkk_rel($r_rrk_id=0,$end='')
	{
		$query = "UPDATE PA_R_RKK SET
								EndDate = '$end'
							WHERE R_RKKID = $r_rrk_id";
		$this->pms->query($query);

		// $kpi_id = $this->get_rkk_rel_row($r_rkk_id)->kpi_id;

		// $query = "UPDATE PA_R_KPI SET
		// 						EndDate ='$end'
		// 					WHERE KPIID = $kpi_id";
		// $this->pms->query($query);
	}

	public function delimit_rkk_rel_byRKK($rkk_id=0,$end='')
	{
		$query = "UPDATE PA_R_RKK SET
								EndDate = '$end'
							WHERE RKKID = $rkk_id";
		$this->pms->query($query);
	}

	/**
	 * [menghapus relasi rkk]
	 * @param  integer $r_rrk_id [description]
	 * @return [type]            [description]
	 */
	public function remove_rkk_rel($r_rrk_id=0)
	{
		$query = "DELETE FROM PA_R_RKK
							WHERE R_RKKID = $r_rrk_id";
		$this->pms->query($query);
	}

	public function remove_rkk_rel_byRKK($rkk_id=0)
	{
		$query = "DELETE FROM PA_R_RKK
							WHERE RKKID = $rkk_id";
		$this->pms->query($query);
	}

	/**
	 * Sasaran Strategis / Strategic Objective
	 */

	/**
	 * [list strategic objective suatu perspetive ]
	 * @param  integer $rkk_id  [description]
	 * @param  integer $pers_id [perspetive ID]
	 * @param  string  $begin   [description]
	 * @param  string  $end     [description]
	 * @return [type]           [description]
	 */
	public function get_so_persp_list($rkk_id=0,$pers_id=0,$begin='',$end='',$is_clear=0)
	{
		$rkk = $this->get_rkk_row($rkk_id);

		if ($rkk->isSAP==1) {
			$query = "SELECT TOP 1 *
								FROM Core_M_Position_SAP
								WHERE PositionID=$rkk->PositionID AND
								((BeginDate >= '$begin' AND EndDate <= '$end') OR
								(EndDate >= '$begin' AND EndDate <= '$end') OR
								(BeginDate >= '$begin' AND BeginDate <= '$end') OR
								(BeginDate <= '$begin' AND EndDate >= '$end'))
								ORDER BY EndDate Desc, BeginDate Desc";
		} else {
			$query = "SELECT TOP 1 *
								FROM Core_M_Position_nonSAP
								WHERE PositionID=$rkk->PositionID AND
								((BeginDate >= '$begin' AND EndDate <= '$end') OR
								(EndDate >= '$begin' AND EndDate <= '$end') OR
								(BeginDate >= '$begin' AND BeginDate <= '$end') OR
								(BeginDate <= '$begin' AND EndDate >= '$end'))
								ORDER BY EndDate Desc, BeginDate Desc";
		}

		$post = $this->pms->query($query)->row();

		if ($is_clear) {
			$query="SELECT SS.*
							FROM PA_T_SasaranStrategis SS
							WHERE
								((SS.BeginDate >= '$begin' AND SS.EndDate <='$end') OR
								(SS.EndDate >= '$begin' AND SS.EndDate <='$end') OR
								(SS.BeginDate >= '$begin' AND SS.BeginDate <='$end') OR
								(SS.BeginDate <= '$begin' AND SS.EndDate >='$end'))
								AND (SS.SasaranStrategisID IN (
								SELECT SasaranStrategisID
								FROM PA_V_RKK_KPI
								WHERE RKKID=$rkk_id AND
									PerspectiveID=$pers_id AND
									((KPI_BeginDate >= '$begin' AND KPI_EndDate <='$end') OR
									(KPI_EndDate >= '$begin' AND KPI_EndDate <='$end') OR
									(KPI_BeginDate >= '$begin' AND KPI_BeginDate <='$end') OR
									(KPI_BeginDate <= '$begin' AND KPI_EndDate >='$end'))
								) )
							ORDER BY SasaranStrategis ASC";
		} else {
			$query="SELECT *
							FROM PA_T_SasaranStrategis SS
							WHERE
								((SS.BeginDate >= '$begin' AND SS.EndDate <='$end') OR
								(SS.EndDate >= '$begin' AND SS.EndDate <='$end') OR
								(SS.BeginDate >= '$begin' AND SS.BeginDate <='$end') OR
								(SS.BeginDate <= '$begin' AND SS.EndDate >='$end'))
								AND (SS.SasaranStrategisID IN (
								SELECT SasaranStrategisID
								FROM PA_V_RKK_KPI
								WHERE RKKID=$rkk_id AND
									PerspectiveID=$pers_id AND
									((KPI_BeginDate >= '$begin' AND KPI_EndDate <='$end') OR
									(KPI_EndDate >= '$begin' AND KPI_EndDate <='$end') OR
									(KPI_BeginDate >= '$begin' AND KPI_BeginDate <='$end') OR
									(KPI_BeginDate <= '$begin' AND KPI_EndDate >='$end'))
								) OR
								(PerspectiveID=$pers_id AND
								OrganizationID = $post->OrganizationID)) OR
								(SS.SasaranStrategisID IN (
									SELECT SasaranStrategisID
									FROM PA_V_RKK_KPI
									WHERE RKKID=$rkk_id AND
										PerspectiveID=$pers_id AND
										((KPI_BeginDate >= '$begin' AND KPI_EndDate <='$end') OR
										(KPI_EndDate >= '$begin' AND KPI_EndDate <='$end') OR
										(KPI_BeginDate >= '$begin' AND KPI_BeginDate <='$end') OR
										(KPI_BeginDate <= '$begin' AND KPI_EndDate >='$end'))
									) )
							ORDER BY SasaranStrategis ASC";
		}
		// echo '<br>'.$query;
		return $this->pms->query($query)->result();
	}

	/**
	 * [detail sasaran strategis]
	 * @param  integer $so_id [description]
	 * @return [type]         [description]
	 */
	public function get_so_row($so_id=0)
	{
		$query = "SELECT *
							FROM PA_T_SasaranStrategis
							WHERE SasaranStrategisID = $so_id";
		return $this->pms->query($query)->row();
	}


	/**
	 * [menambahkan strategic obejctive organisasi]
	 * @param integer $org_id  [description]
	 * @param integer $pers_id [description]
	 * @param string  $text    [description]
	 * @param string  $desc    [description]
	 * @param string  $begin   [description]
	 * @param string  $end     [description]
	 */
	public function add_so($org_id=0,$pers_id=0,$text='',$desc='',$begin='',$end='')
	{
		$query = "INSERT INTO PA_T_SasaranStrategis (
								OrganizationID,
								PerspectiveID,
								SasaranStrategis,
								Description,
								BeginDate,
								EndDate
							) VALUES (
								$org_id,
								$pers_id,
								'$text',
								'$desc',
								'$begin',
								'$end'
							)";
		$this->pms->query($query);
	}

	/**
	 * [edit Sasaran strategis]
	 * @param  integer $so_id   [description]
	 * @param  integer $pers_id [description]
	 * @param  string  $text    [description]
	 * @param  string  $desc    [description]
	 * @param  string  $begin   [description]
	 * @param  string  $end     [description]
	 * @return [type]           [description]
	 */
	public function edit_so($so_id=0,$pers_id=0,$text='',$desc='',$begin='',$end='')
	{
		$query = "UPDATE PA_T_SasaranStrategis SET
								PerspectiveID = $pers_id,
								SasaranStrategis = '$text',
								Description = '$desc',
								BeginDate = '$begin',
								EndDate = '$end'
							WHERE SasaranStrategisID = $so_id";
		$this->pms->query($query);

	}

	/**
	 * [delimit SO]
	 * @param  integer $so_id [description]
	 * @param  string  $end   [description]
	 * @return [type]         [description]
	 */
	public function delimit_so($so_id=0,$end='') {
		$query = "UPDATE PA_T_SasaranStrategis SET
								EndDate = '$end'
							WHERE SasaranStrategisID = $so_id";
		$this->pms->query($query);
	}

	/**
	 * [hapus sasaran strategis]
	 * @param  integer $so_id [description]
	 * @return [type]         [description]
	 */
	public function remove_so($so_id=0)
	{
		$query = "DELETE FROM PA_T_SasaranStrategis
							WHERE SasaranStrategisID=$so_id";
		$this->pms->query($query);
	}

	/**
	 * KPI / Key Performance Indicator - SI / Strategic Inisiatif
	 */

	/**
	 * [menghitung KPI suatu RKK]
	 * @param  integer $rkk_id [description]
	 * @param  string  $begin  [description]
	 * @param  string  $end    [description]
	 * @return [type]          [description]
	 */
	public function count_kpi($rkk_id=0,$begin='',$end='')
	{
		if ($begin == '' && $end == '') {
			$rkk   = $this->get_rkk_row($rkk_id);
			$begin = $rkk->BeginDate;
			$end   = $rkk->EndDate;
		}
		$query = "SELECT count(*) as val
							FROM PA_T_KPI
							WHERE RKKID = $rkk_id AND
								((BeginDate >= '$begin' AND EndDate <='$end') OR
								(EndDate >= '$begin' AND EndDate <='$end') OR
								(BeginDate >= '$begin' AND BeginDate <='$end') OR
								(BeginDate <= '$begin' AND EndDate >='$end')) ";
		return $this->pms->query($query)->row()->val;
	}

	/**
	 * [count_kpi_so description]
	 * @param  integer $so_id  [description]
	 * @param  string  $begin  [description]
	 * @param  string  $end    [description]
	 * @return [type]          [description]
	 */
	public function count_kpi_so($so_id=0,$begin='',$end='')
	{
		if ($begin == '' && $end == '') {
			$query = "SELECT count(*) as val
								FROM PA_T_KPI
								WHERE SasaranStrategisID = $so_id ";
		} else {
			$query = "SELECT count(*) as val
								FROM PA_T_KPI
								WHERE SasaranStrategisID = $so_id AND
									((BeginDate >= '$begin' AND EndDate <='$end') OR
									(EndDate >= '$begin' AND EndDate <='$end') OR
									(BeginDate >= '$begin' AND BeginDate <='$end') OR
									(BeginDate <= '$begin' AND EndDate >='$end')) ";
		}
		return $this->pms->query($query)->row()->val;
	}

	/**
	 * [menghitung bobot KPI dalam satu RKK]
	 * @param  integer $rkk_id [description]
	 * @param  string  $begin  [description]
	 * @param  string  $end    [description]
	 * @return [type]          [description]
	 */
	public function count_weight_rkk($rkk_id=0,$begin='',$end='')
	{
		$query = "SELECT SUM(Bobot) as weight
							FROM PA_V_RKK_KPI
							WHERE RKKID = $rkk_id AND
								((KPI_BeginDate >= '$begin' AND KPI_EndDate <='$end') OR
								(KPI_EndDate >= '$begin' AND KPI_EndDate <='$end') OR
								(KPI_BeginDate >= '$begin' AND KPI_BeginDate <='$end') OR
								(KPI_BeginDate <= '$begin' AND KPI_EndDate >='$end'))";
		$result = $this->pms->query($query)->row()->weight;
		if (is_null($result)) {
			$result = 0;
		}
		return $result;
	}

	/**
	 * [menghitung Bobot KPI per perspectif dalam satu RKK]
	 * @param  integer $rkk_id  [description]
	 * @param  integer $pers_id [description]
	 * @param  string  $begin   [description]
	 * @param  string  $end     [description]
	 * @return [type]           [description]
	 */
	public function sum_weight_persp($rkk_id=0,$pers_id=0,$begin='',$end='')
	{
		$query = "SELECT SUM(Bobot) as weight
							FROM PA_V_RKK_KPI
							WHERE RKKID = $rkk_id AND
								PerspectiveID = $pers_id AND
								((KPI_BeginDate >= '$begin' AND KPI_EndDate <='$end') OR
								(KPI_EndDate >= '$begin' AND KPI_EndDate <='$end') OR
								(KPI_BeginDate >= '$begin' AND KPI_BeginDate <='$end') OR
								(KPI_BeginDate <= '$begin' AND KPI_EndDate >='$end'))";
		$result = $this->pms->query($query)->row()->weight;
		if (is_null($result)) {
			$result =0;
		}
		return $result;
	}

	public function sum_weight_ytd_persp($rkk_id=0,$pers_id=0,$month=0,$begin='',$end='')
	{
		$query = "SELECT SUM(Bobot) as weight
							FROM PA_V_RKK_KPI v
							WHERE v.RKKID = $rkk_id AND
								v.PerspectiveID = $pers_id AND
								(
									SELECT COUNT(*)
									FROM PA_T_RKKDetailTarget t
									WHERE t.Month <= $month AND
									  t.KPIID = v.KPIID
								) > 0 AND
								((v.KPI_BeginDate >= '$begin' AND v.KPI_EndDate <='$end') OR
								(v.KPI_EndDate >= '$begin' AND v.KPI_EndDate <='$end') OR
								(v.KPI_BeginDate >= '$begin' AND v.KPI_BeginDate <='$end') OR
								(v.KPI_BeginDate <= '$begin' AND v.KPI_EndDate >='$end'))";
		$result = $this->pms->query($query)->row();
		if (count($result)) {
			return $result->weight;
		} else {
			return 0;
		}
	}

	public function sum_weight_cur_persp($rkk_id=0,$pers_id=0,$month=0,$begin='',$end='')
	{
		$query = "SELECT SUM(Bobot) as weight
							FROM PA_V_RKK_KPI v
							WHERE v.RKKID = $rkk_id AND
								v.PerspectiveID = $pers_id AND
								(
									SELECT COUNT(*)
									FROM PA_T_RKKDetailTarget t
									WHERE t.Month = $month AND
									  t.KPIID = v.KPIID
								) > 0 AND
								((v.KPI_BeginDate >= '$begin' AND v.KPI_EndDate <='$end') OR
								(v.KPI_EndDate >= '$begin' AND v.KPI_EndDate <='$end') OR
								(v.KPI_BeginDate >= '$begin' AND v.KPI_BeginDate <='$end') OR
								(v.KPI_BeginDate <= '$begin' AND v.KPI_EndDate >='$end'))";

		$result = $this->pms->query($query)->row();
		if (count($result)) {
			return $result->weight;
		} else {
			return 0;
		}
	}

	/**
	 * [menghitung Bobot KPI per SO dalam satu RKK]
	 * @param  integer $rkk_id [description]
	 * @param  integer $so_id  [description]
	 * @param  string  $begin  [description]
	 * @param  string  $end    [description]
	 * @return [type]          [description]
	 */
	public function count_weight_so($rkk_id=0,$so_id=0,$begin='',$end='')
	{
		$query = "SELECT SUM(Bobot) as weight
							FROM PA_V_RKK_KPI
							WHERE RKKID = $rkk_id AND
								SasaranStrategisID = $so_id AND
								((KPI_BeginDate >= '$begin' AND KPI_EndDate <='$end') OR
								(KPI_EndDate >= '$begin' AND KPI_EndDate <='$end') OR
								(KPI_BeginDate >= '$begin' AND KPI_BeginDate <='$end') OR
								(KPI_BeginDate <= '$begin' AND KPI_EndDate >='$end'))";
		return $this->pms->query($query)->row()->weight;
	}

	/**
	 * [menghitung jumlah KPI yang diturunkan]
	 * @param  integer $chief_kpi [description]
	 * @param  string  $begin     [description]
	 * @param  string  $end       [description]
	 * @return [type]             [description]
	 */
	public function count_kpi_casd($chief_kpi=0,$begin='',$end='')
	{
		$query = "SELECT count(*) as val
							FROM PA_V_RKK_KPI v
							WHERE ((KPI_BeginDate >= '$begin' AND KPI_EndDate <='$end') OR
								(KPI_EndDate >= '$begin' AND KPI_EndDate <='$end') OR
								(KPI_BeginDate >= '$begin' AND KPI_BeginDate <='$end') OR
								(KPI_BeginDate <= '$begin' AND KPI_EndDate >='$end')) AND
								KPIID IN (
									SELECT KPIID
									FROM PA_R_KPI
									WHERE chief_kpi_id = $chief_kpi AND
									((BeginDate >= '$begin' AND EndDate <='$end') OR
									(EndDate >= '$begin' AND EndDate <='$end') OR
									(BeginDate >= '$begin' AND BeginDate <='$end') OR
									(BeginDate <= '$begin' AND EndDate >='$end'))
								)";
		return $this->pms->query($query)->row()->val;
	}

	/**
	 * [daftar KPI dari sebuah RKK]
	 * @param  integer $rkk_id [description]
	 * @param  string  $begin  [description]
	 * @param  string  $end    [description]
	 * @return [type]          [description]
	 */
	public function get_kpi_list($rkk_id=0,$begin='',$end='')
	{
		$query = "SELECT *
							FROM PA_V_RKK_KPI
							WHERE RKKID = $rkk_id AND
								((KPI_BeginDate >= '$begin' AND KPI_EndDate <='$end') OR
								(KPI_EndDate >= '$begin' AND KPI_EndDate <='$end') OR
								(KPI_BeginDate >= '$begin' AND KPI_BeginDate <='$end') OR
								(KPI_BeginDate <= '$begin' AND KPI_EndDate >='$end')) order by SasaranStrategis asc,KPI asc ";

		return $this->pms->query($query)->result();
	}

	/**
	 * [mendapatkan daftar KPI dari sebuah RKK per perspektif]
	 * @param  integer $rkk_id   [description]
	 * @param  integer $persp_id [description]
	 * @param  string  $begin    [description]
	 * @param  string  $end      [description]
	 * @return [type]            [description]
	 */
	public function get_kpi_persp_list($rkk_id=0,$persp_id=0,$begin='',$end='')
	{
		$query = "SELECT v.*,r.Reference
							FROM PA_V_RKK_KPI v
							LEFT JOIN PA_M_Reference r
							ON r.ReferenceID=v.ref_id
							WHERE RKKID = $rkk_id AND
								PerspectiveID = $persp_id AND
								((KPI_BeginDate >= '$begin' AND KPI_EndDate <='$end') OR
								(KPI_EndDate >= '$begin' AND KPI_EndDate <='$end') OR
								(KPI_BeginDate >= '$begin' AND KPI_BeginDate <='$end') OR
								(KPI_BeginDate <= '$begin' AND KPI_EndDate >='$end'))";

		return $this->pms->query($query)->result();
	}

	/**
	 * [daftar KPI dari sebuah RKK per SO]
	 * @param  integer $rkk_id [description]
	 * @param  integer $so_id  [description]
	 * @param  string  $begin  [description]
	 * @param  string  $end    [description]
	 * @return [type]          [description]
	 */
	public function get_kpi_so_list($rkk_id=0,$so_id=0,$begin='',$end='')
	{
		$query = "SELECT v.*,r.Reference
							FROM PA_V_RKK_KPI v
							LEFT JOIN PA_M_Reference r
							ON r.ReferenceID=v.ref_id
							WHERE RKKID = $rkk_id AND
								SasaranStrategisID = $so_id AND
								((KPI_BeginDate >= '$begin' AND KPI_EndDate <='$end') OR
								(KPI_EndDate >= '$begin' AND KPI_EndDate <='$end') OR
								(KPI_BeginDate >= '$begin' AND KPI_BeginDate <='$end') OR
								(KPI_BeginDate <= '$begin' AND KPI_EndDate >='$end'))
							ORDER BY KPI ASC";
		return $this->pms->query($query)->result();
	}

	/**
	 * [mendapatkan KPI anak buah yang terhubung dengan KPI atasan]
	 * @param  integer $chief_kpi [description]
	 * @param  string  $begin     [description]
	 * @param  string  $end       [description]
	 * @return [type]             [description]
	 */
	public function get_kpi_casd_list($chief_kpi=0,$begin='',$end='')
	{
		$query = "SELECT v.*,
								u.Fullname
							FROM PA_V_RKK_KPI v
							JOIN Core_M_User u
								ON v.NIK = u.NIK
							WHERE ((KPI_BeginDate >= '$begin' AND KPI_EndDate <='$end') OR
								(KPI_EndDate >= '$begin' AND KPI_EndDate <='$end') OR
								(KPI_BeginDate >= '$begin' AND KPI_BeginDate <='$end') OR
								(KPI_BeginDate <= '$begin' AND KPI_EndDate >='$end')) AND
								KPIID IN (
									SELECT KPIID
									FROM PA_R_KPI
									WHERE chief_kpi_id = $chief_kpi AND
									((BeginDate >= '$begin' AND EndDate <='$end') OR
									(EndDate >= '$begin' AND EndDate <='$end') OR
									(BeginDate >= '$begin' AND BeginDate <='$end') OR
									(BeginDate <= '$begin' AND EndDate >='$end'))
								)";
		return $this->pms->query($query)->result();
	}

	/**
	 * [detail KPI]
	 * @param  integer $kpi_id [description]
	 * @return [type]          [description]
	 */
	public function get_kpi_row($kpi_id=0)
	{
		$query = "SELECT TOP 1 v.*, r.Reference
							FROM PA_V_RKK_KPI v
							LEFT JOIN PA_M_Reference r
							ON r.ReferenceID=v.ref_id
							WHERE KPIID = $kpi_id";

		return $this->pms->query($query)->row();
	}


	/**
	 * [menambahkan kpi beserta hubungan dengan kpi atasan bila ada]
	 * @param integer $rkk_id     [rkk id]
	 * @param integer $generic    [optional; generic KPI]
	 * @param integer $so_id      [SO]
	 * @param integer $satuan     [satuan hitung yang digunakan]
	 * @param integer $formula    [formula hitung yang digunakan]
	 * @param integer $ytd        [cara menghitung Year to Date]
	 * @param string  $kpi        [text KPI]
	 * @param string  $desc       [penjelasan]
	 * @param integer $weight     [bobot KPI]
	 * @param integer $base       [modal dasar KPI]
	 * @param string  $begin      [tanggal]
	 * @param string  $end        [tanggal]
	 * @param array   $target     [description]
	 * @param integer $chief_kpi  [optional; kpi atasan yang menjadi asal turunan]
	 * @param integer $ref_weight [optional; besar kontribusi]
	 * @param integer $ref_id     [optional; cara kpi bawahan berkontribusi bagi atasan]
	 */
	public function add_kpi($rkk_id=0, $generic=0, $so_id=0, $satuan =0 ,$formula =0 ,$ytd =0 ,$kpi = '',$desc = '', $weight=0, $base=0, $begin='' ,$end ='', $targets=array(), $chief_kpi = 0, $ref_weight=0, $ref_id=0,$ref_begin='',$ref_end='')
	{
		$query="INSERT INTO [PA_T_KPI] (
							RKKID,
							[KPIGenericID],
							[SasaranStrategisID],
							[SatuanID],
							[PCFormulaID],
							[YTDID],
							[KPI],
							[Description],
							[Bobot],
							[Baseline],
							[BeginDate],
							[EndDate]
						) VALUES (
							$rkk_id,
							$generic,
							$so_id,
							$satuan,
							$formula,
							$ytd,
							'$kpi',
							'$desc',
							$weight,
							$base,
							'$begin',
							'$end'
						)";
		// echo '<br>'.$query;
		$this->pms->query($query);
		$query = "SELECT MAX(KPIID) as KPIID FROM PA_T_KPI";
		$kpi_id = $this->pms->query($query)->row()->KPIID;

		#Target Bulanan dan Akhir tahun
		switch ($ytd) {
			case 1: // akumulasi
				$year_target = 0;
				foreach ($targets as $month => $target) {
					$this->add_target($kpi_id, $month, $target);
					$year_target += $target;
				}
				break;
			case 2: // rata rata
				$year_target = 0;
				$count = 0;
				foreach ($targets as $month => $target) {
					$this->add_target($kpi_id, $month, $target);
					$year_target += $target;
					$count++;
				}
				$year_target = $year_target/$count;
				break;
			case 3: //last value
				$year_target = 0;
				foreach ($targets as $month => $target) {
					$this->add_target($kpi_id, $month, $target);
					$year_target = $target;
				}
				break;
			default:
				$year_target = 0;
				break;
		}

		$this->edit_kpi_target($kpi_id,$year_target);

		#relasi dengan KPI Atasan
		if ($chief_kpi!=0) {
			$this->edit_kpi_contri($chief_kpi,$ref_id);
			$this->add_kpi_rel($kpi_id,$chief_kpi,$ref_id,$ref_weight,$ref_begin,$ref_end);
		}

	}

	/**
	 * [mengubah KPI berserta Targetnya]
	 * @param  integer $kpi_id  [description]
	 * @param  integer $generic [description]
	 * @param  integer $so_id   [description]
	 * @param  integer $satuan  [description]
	 * @param  integer $formula [description]
	 * @param  integer $ytd     [description]
	 * @param  string  $kpi     [description]
	 * @param  string  $desc    [description]
	 * @param  integer $weight  [description]
	 * @param  integer $base    [description]
	 * @param  string  $begin   [description]
	 * @param  string  $end     [description]
	 * @param  array   $months  [description]
	 * @param  array   $targets [description]
	 * @return [type]           [description]
	 */
	public function edit_kpi($kpi_id=0,$generic=0, $so_id=0, $satuan =0 ,$formula =0 ,$ytd =0 ,$kpi = '',$desc = '', $weight=0, $base=0, $begin='' ,$end ='',$months=array(),$targets=array())
	{
		$query = "UPDATE PA_R_KPI SET
								EndDate = '$end'
							WHERE (KPIID = $kpi_id OR chief_kpi_id = $kpi_id ) AND
								EndDate > '$end' ";
		$this->pms->query($query);

		$query = "UPDATE PA_R_KPI SET
								BeginDate = '$begin'
							WHERE (KPIID = $kpi_id OR chief_kpi_id = $kpi_id ) AND
								BeginDate < '$begin' ";
		$this->pms->query($query);

		$query = "UPDATE PA_T_KPI SET
								[KPIGenericID] = $generic,
								[SatuanID] = $satuan,
								[PCFormulaID] = $formula,
								[YTDID] = $ytd,
								[KPI] = '$kpi',
								[Description] = '$desc',
								[Bobot] = $weight,
								[Baseline] = $base,
								[BeginDate] ='$begin',
								[EndDate] = '$end'
							WHERE KPIID = $kpi_id";
		$this->pms->query($query);

		# Target Bulanan
		for ($i=1; $i <=12 ; $i++) {
			$c_target = $this->count_target_month($kpi_id,$i);
			if ($c_target == FALSE && in_array($i, $months) == TRUE ) {
				# ADD
				$this->add_target($kpi_id,$i,$targets[$i]);

			} elseif ($c_target == TRUE && in_array($i, $months) == TRUE) {
				# UPDATE
				$this->edit_target_month($kpi_id,$i,$targets[$i]);

			} elseif ($c_target == TRUE && in_array($i, $months) == FALSE) {
				# REMOVE
				$this->remove_target_month($kpi_id,$i);
			}

			$target_ls = $this->get_target_list($kpi_id);
		}
		# Target Akhir Tahun
		switch ($ytd) {
			case 1: // akumulasi
				$year_target = 0;
				foreach ($target_ls as $row) {
					$year_target += $row->Target;
				}
				break;
			case 2: // rata rata
				$year_target = 0;
				$count = 0;
				foreach ($target_ls as $row) {
					$year_target += $row->Target;
					$count++;
				}
				$year_target = $year_target/$count;
				break;
			case 3: //last value
				$year_target = 0;
				foreach ($target_ls as $row) {
					$year_target = $row->Target;
				}
				break;
			default:
				$year_target = 0;
				break;
		}
		$this->edit_kpi_target($kpi_id,$year_target);
	}

	/**
	 * [mengubah target akhir tahun KPI]
	 * @param  integer $kpi_id [description]
	 * @param  float   $target [description]
	 * @return [type]          [description]
	 */
	public function edit_kpi_target($kpi_id=0,$target=0.00)
	{
		$query = "UPDATE PA_T_KPI SET TargetAkhirTahun=$target WHERE KPIID=$kpi_id";
		$this->pms->query($query);
	}

	/**
	 * [mengubah cara kontribusi KPI bawahan ke atasan]
	 * @param  integer $kpi_id [description]
	 * @param  integer $ref_id [description]
	 * @return [type]          [description]
	 */
	public function edit_kpi_contri($kpi_id=0,$ref_id=0)
	{
		$query = "UPDATE PA_T_KPI SET ref_id=$ref_id WHERE KPIID=$kpi_id";
		$this->pms->query($query);
	}

	function delimit_KPI($KPIID, $endDate){

 		$query="UPDATE [PMS].[dbo].[PA_T_KPI]
				   SET [EndDate] = '$endDate'
				 WHERE KPIID=$KPIID";
 		$this->pms->query($query);
 	}

	/**
	 * [menghapus KPI, Target dan relasinya]
	 * @param  integer $kpi_id [description]
	 * @return [type]          [description]
	 */
	public function remove_kpi($kpi_id=0)
	{
		#Hapus Relasi dengan KPI Atasan
		$query = "DELETE FROM [PMS].[dbo].[PA_R_KPI]
							WHERE KPIID=$kpi_id";
		$this->pms->query($query);
		#Hapus Target KPI
		$query = "DELETE FROM [PMS].[dbo].[PA_T_RKKDetailTarget]
							WHERE KPIID=$kpi_id";
		$this->pms->query($query);
		#Hapus KPI
		$query = "DELETE FROM [PMS].[dbo].[PA_T_KPI]
							WHERE KPIID=$kpi_id";
		$this->pms->query($query);
	}

	public function copy_kpi($source_kpi=0,$target_rkk=0,$begin='',$end='')
	{
		$kpi     = $this->get_kpi_row($source_kpi);
		$c_rel   = $this->count_kpi_rel($source_kpi,$begin,$end);
		$target  = $this->get_target_list($source_kpi);
		$targets = array();

		foreach ($target as $row) {
			$targets[$row->Month] = $row->Target;
		}

		if ($c_rel>0) {
			$kpi_rel = $this->get_kpi_rel_last($source_kpi,$begin,$end);

			$this->add_kpi(
				$target_rkk,
				$kpi->KPIID,
				$kpi->SasaranStrategisID,
				$kpi->SatuanID ,
				$kpi->PCFormulaID ,
				$kpi->YTDID ,
				$kpi->KPI,
				$kpi->Description,
				$kpi->Bobot,
				$kpi->Baseline,
				$kpi->BeginDate,
				$kpi->EndDate,
				$targets,
				$c_rel->chief_kpi,
				$c_rel->ref_weight,
				$c_rel->ref_id,
				$c_rel->begin_date,
				$c_rel->end_date
			);
		} else {
			$this->add_kpi(
				$target_rkk,
				$kpi->KPIID,
				$kpi->SasaranStrategisID,
				$kpi->SatuanID ,
				$kpi->PCFormulaID ,
				$kpi->YTDID ,
				$kpi->KPI,
				$kpi->Description,
				$kpi->Bobot,
				$kpi->Baseline,
				$kpi->BeginDate,
				$kpi->EndDate,
				$targets
			);
		}
	}

	/**
	 * Hubungan KPI dengan KPI Atasannya
	 */

	/**
	 * [menghitung relasi KPI dengan KPI Bawahan]
	 * @param  integer $chief_kpi_id [description]
	 * @param  string  $begin        [description]
	 * @param  string  $end          [description]
	 * @return [type]                [description]
	 */
	public function count_kpi_rel_AB($chief_kpi_id=0,$begin='',$end='')
	{
		$query = "SELECT count(*) as val
							FROM PA_R_KPI
							WHERE chief_kpi_id = $chief_kpi_id AND
							((BeginDate >= '$begin' AND EndDate <='$end') OR
							(EndDate >= '$begin' AND EndDate <='$end') OR
							(BeginDate >= '$begin' AND BeginDate <='$end') OR
							(BeginDate <= '$begin' AND EndDate >='$end'))";
		return $this->pms->query($query)->row()->val;
	}

	/**
	 * [menghitung relasi KPI dengan KPI Atasan]
	 * @param  integer $kpi_id [description]
	 * @param  string  $begin  [description]
	 * @param  string  $end    [description]
	 * @return [type]          [description]
	 */
	public function count_kpi_rel_BA($kpi_id = 0,$begin='',$end='')
	{
		$query = "SELECT count(*) as val
							FROM PA_R_KPI
							WHERE KPIID = $kpi_id AND
							((BeginDate >= '$begin' AND EndDate <='$end') OR
							(EndDate >= '$begin' AND EndDate <='$end') OR
							(BeginDate >= '$begin' AND BeginDate <='$end') OR
							(BeginDate <= '$begin' AND EndDate >='$end'))";
		return $this->pms->query($query)->row()->val;
	}

	/**
	 * [menghitung achievement satu kpi]
	 * @param  string $kpi_id [description]
	 * @return [type]         [description]
	 */
	public function count_acvh_kpi($kpi_id = '')
	{
		$query = "SELECT COUNT(*) as val
							FROM PA_T_RKKAchievementDetail
							WHERE KPIID = $kpi_id";
		return $this->pms->query($query)->row()->val;
	}

	/**
	 * [daftar hubungan KPI dengan KPI Bawahan dalam suatu rentang waktu]
	 * @param  integer $kpi_id [description]
	 * @param  string  $begin  [description]
	 * @param  string  $end    [description]
	 * @return [type]          [description]
	 */
	public function get_kpi_rel_AB_list($kpi_id=0,$begin='',$end='')
	{
		echo $query = "SELECT r.*,
							  v.*,
							  u.Fullname
							FROM PA_R_KPI r
							JOIN PA_V_RKK_KPI v
								ON r.KPIID = v.KPIID
							JOIN Core_M_User u
								ON v.NIK = u.NIK
							WHERE r.chief_kpi_id = $kpi_id AND
							((r.BeginDate >= '$begin' AND r.EndDate <='$end') OR
							(r.EndDate >= '$begin' AND r.EndDate <='$end') OR
							(r.BeginDate >= '$begin' AND r.BeginDate <='$end') OR
							(r.BeginDate <= '$begin' AND r.EndDate >='$end'))";
		return $this->pms->query($query)->result();
	}

	/**
	 * [daftar hubungan KPI dengan KPI Atasan dalam suatu rentang waktu]
	 * @param  integer $kpi_id [description]
	 * @param  string  $begin  [description]
	 * @param  string  $end    [description]
	 * @return [type]          [description]
	 */
	public function get_kpi_rel_BA_list($kpi_id=0,$begin='',$end='')
	{
		$query = "SELECT *
							FROM PA_R_KPI
							WHERE KPIID = $kpi_id AND
							((BeginDate >= '$begin' AND EndDate <='$end') OR
							(EndDate >= '$begin' AND EndDate <='$end') OR
							(BeginDate >= '$begin' AND BeginDate <='$end') OR
							(BeginDate <= '$begin' AND EndDate >='$end'))";
		return $this->pms->query($query)->result();
	}

	/**
	 * [detail hubungan KPI]
	 * @param  integer $r_kpi [description]
	 * @return [type]         [description]
	 */
	public function get_kpi_rel_row($r_kpi=0)
	{
		$query = "SELECT *
							FROM PA_R_KPI
							WHERE R_KPIID = $r_kpi";
		return $this->pms->query($query)->row();
	}

	/**
	 * [mendapatkan hubungan KPI Terakhir]
	 * @param  integer $kpi_id [description]
	 * @param  string  $begin  [description]
	 * @param  string  $end    [description]
	 * @return [type]          [description]
	 */
	public function get_kpi_rel_last($kpi_id=0,$begin='',$end='')
	{
		$query = "SELECT TOP 1 r.*,
								m.Reference
							FROM PA_R_KPI r
							JOIN PA_M_Reference m
								ON m.ReferenceID = r.ref_id
							WHERE r.KPIID = $kpi_id AND
							((r.BeginDate >= '$begin' AND r.EndDate <='$end') OR
							(r.EndDate >= '$begin' AND r.EndDate <='$end') OR
							(r.BeginDate >= '$begin' AND r.BeginDate <='$end') OR
							(r.BeginDate <= '$begin' AND r.EndDate >='$end'))
							ORDER BY r.EndDate DESC, r.BeginDate Desc";
		return $this->pms->query($query)->row();
	}

	/**
	 * [menambahkan hubungan KPI dengan KPI Atasan]
	 * @param integer $kpi_id     [description]
	 * @param integer $chief_kpi  [description]
	 * @param integer $ref_id     [description]
	 * @param integer $ref_weight [description]
	 * @param string  $begin      [description]
	 * @param string  $end        [description]
	 */
	public function add_kpi_rel($kpi_id=0, $chief_kpi=0,$ref_id=0,$ref_weight=0,$begin='',$end='')
	{
		if ($ref_id == 0 OR $ref_id == 3) {
			$query = "INSERT INTO [PA_R_KPI] (
									KPIID,
									chief_kpi_id,
									ref_id,
									BeginDate,
									EndDate
								) VALUES (
									$kpi_id,
									$chief_kpi_id,
									$ref_id,
									'$begin',
									'$end'
								)";
		} else {
			$query = "INSERT INTO [PA_R_KPI] (
									KPIID,
									chief_kpi_id,
									ref_id,
									ref_weight,
									BeginDate,
									EndDate
								) VALUES (
									$kpi_id,
									$chief_kpi,
									$ref_id,
									$ref_weight,
									'$begin',
									'$end'
								)";
			$this->pms->query($query);
		}
	}

	/**
	 * [mengubah hubungan KPI dengan KPI Atasan]
	 * @param  integer $r_kpi      [description]
	 * @param  integer $kpi_id     [description]
	 * @param  integer $chief_kpi  [description]
	 * @param  integer $ref_id     [description]
	 * @param  integer $ref_weight [description]
	 * @param  string  $begin      [description]
	 * @param  string  $end        [description]
	 * @return [type]              [description]
	 */
	public function edit_kpi_rel($r_kpi=0, $kpi_id=0, $chief_kpi=0,$ref_weight=0,$begin='',$end='')
	{
		$query = "UPDATE PA_R_KPI SET
								KPIID = $kpi_id,
								chief_kpi_id = $chief_kpi,
								ref_weight = $ref_weight,
								BeginDate = '$begin',
								EndDate = '$end'
							WHERE R_KPIID = $r_kpi";
		$this->pms->query($query);
	}

	/**
	 * [delimit hubungan KPI dengan KPI Atasan]
	 * @param  integer $r_kpi [description]
	 * @param  string  $end   [description]
	 * @return [type]         [description]
	 */
	public function delimit_kpi_rel($r_kpi=0,$end='')
	{
		$query = "UPDATE PA_R_KPI SET
								EndDate = '$end'
							WHERE R_KPIID = $r_kpi";
		$this->pms->query($query);
	}

	/**
	 * [menghapus hubungan KPI]
	 * @param  integer $r_kpi [description]
	 * @return [type]         [description]
	 */
	public function remove_kpi_rel($r_kpi=0)
	{
		$query = "DELETE FROM PA_R_KPI WHERE R_KPIID = $r_kpi";
		$this->pms->query($query);
	}

	/**
	 *  Target KPI tiap Bulan
	 */

	/**
	 * [menghitung kpi yang mempunyai target dalam satu bulan]
	 * @param  integer $rkk_id [description]
	 * @param  integer $month  [description]
	 * @return [type]          [description]
	 */
	public function count_target_rkk_month($rkk_id=0,$month=0)
	{
		$rkk   = $this->get_rkk_row($rkk_id);
		$begin = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, substr($rkk->BeginDate, 0,4)));
		$end   = date('Y-m-t H:i:s', mktime(23, 59, 59, $month, 1, substr($rkk->BeginDate, 0,4)));
		$query = "SELECT COUNT(*) as val
							FROM PA_V_RKK_KPI v
							WHERE v.RKKID = $rkk_id AND
								v.KPIID IN (
									SELECT KPIID
									FROM PA_T_RKKDetailTarget
									WHERE Month = $month AND
										KPIID = v.KPIID
								) AND
								((v.KPI_BeginDate >= '$begin' AND v.KPI_EndDate <='$end') OR
								(v.KPI_EndDate >= '$begin' AND v.KPI_EndDate <='$end') OR
								(v.KPI_BeginDate >= '$begin' AND v.KPI_BeginDate <='$end') OR
								(v.KPI_BeginDate <= '$begin' AND v.KPI_EndDate >='$end')) ";

		return $this->pms->query($query)->row()->val;
	}
	/**
	 * [menghitung target KPI]
	 * @param  integer $kpi_id [description]
	 * @return [type]          [description]
	 */
	public function count_target($kpi_id=0)
	{
		$query = "SELECT count(*) as val
							FROM PA_T_RKKDetailTarget
							WHERE KPIID = $kpi_id";
		return $this->pms->query($query)->row()->val;
	}
	/**
	 * [menghitung record target bulanan KPI]
	 * @param  integer $kpi_id [description]
	 * @param  integer $month  [1-12]
	 * @return [type]          [description]
	 */
	public function count_target_month($kpi_id=0,$month=0)
	{
		$query = "SELECT COUNT(*) as val
							FROM PA_T_RKKDetailTarget
							WHERE KPIID = $kpi_id AND
								Month = $month";
		return $this->pms->query($query)->row()->val;
	}

	/**
	 * [daftar Target KPI]
	 * @param  integer $kpi_id [description]
	 * @return [type]          [description]
	 */
	public function get_target_list($kpi_id=0)
	{
		$query = "SELECT *
							FROM PA_T_RKKDetailTarget
							WHERE KPIID = $kpi_id
							ORDER BY Month ASC";
		return $this->pms->query($query)->result();
	}

	/**
	 * [detail target KPI]
	 * @param  integer $target_id [description]
	 * @return [type]             [description]
	 */
	public function get_target_row($target_id=0)
	{
		$query = "SELECT *
							FROM PA_T_RKKDetailTarget
							WHERE RKKDetailTargetID = $target_id";
		return $this->pms->query($query)->row();
	}

	/**
	 * [mendapatkan target kpi dalam satu bulan]
	 * @param  integer $kpi_id [description]
	 * @param  integer $month  [1-12]
	 * @return [type]          [description]
	 */
	public function get_target_month_row($kpi_id=0,$month=0)
	{
		$query = "SELECT TOP 1 *
							FROM PA_T_RKKDetailTarget
							WHERE KPIID = $kpi_id AND
								Month = $month";
		return $this->pms->query($query)->row();
	}

	/**
	 * [menghitung target YTD KPI]
	 * @param  integer $kpi_id [description]
	 * @param  integer $month  [description]
	 * @return [type]          [description]
	 */
	public function calc_target_ytd_value($kpi_id=0,$month=0)
	{
		$kpi = $this->get_kpi_row($kpi_id);
		if ($month>date('n',strtotime($kpi->RKK_EndDate))) {
 			$month = date('n',strtotime($kpi->RKK_EndDate));
 		}
		switch ($kpi->YTDID) {
			case 1:
				# Akumulasi / Acumulation
				$query = "SELECT
										SUM(Target) as Target
									FROM
										PA_T_RKKDetailTarget
									WHERE
										KPIID = $kpi_id	AND
										[Month]<=$month";
				break;
			case 2:
				# Rata - rata / Average
				$query = "SELECT
										AVG(Target) as Target
									FROM
										PA_T_RKKDetailTarget
									WHERE
										KPIID = $kpi_id	AND
										[Month]<=$month";
				break;
			case 3:
				# Nilai terakhir / Last Value
				$query = "SELECT TOP 1 Target
									FROM
										PA_T_RKKDetailTarget
									WHERE
										KPIID = $kpi_id	AND
										[Month]<=$month
									ORDER BY Month DESC";

				break;
		}
		if (count($this->pms->query($query)->row())) {
			$value = $this->pms->query($query)->row()->Target;
			if (is_null($value)) {
				$value = '-';
			}
			return $value;

		} else {
			return '-';
		}
	}

	/**
	 * [menambahkan target KPI]
	 * @param integer $kpi_id [description]
	 * @param integer $month  [description]
	 * @param float   $target [description]
	 */
	public function add_target($kpi_id=0, $month=0, $target=0.00)
	{
		$query = "INSERT INTO PA_T_RKKDetailTarget (
								KPIID,
								Month,
								Target
							) VALUES (
								$kpi_id,
								$month,
								$target
							);";
		$this->pms->query($query);
	}

	/**
	 * [mengubah target KPI]
	 * @param  integer $target_id [description]
	 * @param  float   $target    [description]
	 * @return [type]             [description]
	 */
	public function edit_target($target_id=0, $target=0.00)
	{
		$query = "UPDATE PA_T_RKKDetailTarget SET
								[Target] = $target
							WHERE RKKDetailTargetID = $target_id";
		$this->pms->query($query);
	}

	public function edit_target_month($kpi_id=0, $month=0, $target=0.00)
	{
		$query = "UPDATE PA_T_RKKDetailTarget SET
								[Target] = $target
							WHERE KPIID = $kpi_id AND
								Month = $month";
		$this->pms->query($query);
	}

	/**
	 * [menghapus target]
	 * @param  integer $target_id [description]
	 * @return [type]             [description]
	 */
	public function remove_target($target_id=0)
	{
		$query = "DELETE FROM PA_T_RKKDetailTarget WHERE RKKDetailTargetID = $target_id";
		$this->pms->query($query);
	}

	public function remove_target_month($kpi_id=0, $month=0)
	{
		$query = "DELETE FROM PA_T_RKKDetailTarget
							WHERE KPIID = $kpi_id AND
								Month = $month";
		$this->pms->query($query);
	}

	public function get_rkk_byNIKPosition($NIK,$PositionID,$begin_date,$end_date){
		$query = "SELECT TOP 1 * FROM PA_T_RKK WHERE NIK='$NIK' AND PositionID=$PositionID
				AND
				((BeginDate >= '$begin_date' AND EndDate <='$end_date') OR
				(EndDate >= '$begin_date' AND EndDate <='$end_date') OR
				(BeginDate >= '$begin_date' AND BeginDate <='$end_date') OR
				(BeginDate <= '$begin_date' AND EndDate >='$end_date'))";

		return $this->pms->query($query)->row();
	}

	public function count_out_target_byRKK($rkk_id=0,$begin='',$end='')
	{
		$m_begin = (int) substr($begin, 5,2);
		$m_end   = (int) substr($end, 5,2);
		$query = "SELECT COUNT(*) AS val
							FROM PA_T_KPI k
							INNER JOIN PA_T_RKKDetailTarget t
								ON k.KPIID = t.KPIID
							WHERE k.RKKID = $rkk_id AND
								((k.BeginDate >= '$begin' AND k.EndDate <='$end') OR
								(k.EndDate >= '$begin' AND k.EndDate <='$end') OR
								(k.BeginDate >= '$begin' AND k.BeginDate <='$end') OR
								(k.BeginDate <= '$begin' AND k.EndDate >='$end')) AND
								(t.Month < $m_begin OR t.Month > $m_end)";
		return $this->pms->query($query)->row()->val;
	}

	public function count_out_target_byKPI($kpi_id=0,$begin='',$end='')
	{
		$m_begin = (int) substr($begin, 5,2);
		$m_end   = (int) substr($end, 5,2);
		$query = "SELECT COUNT(*) AS val
							FROM PA_T_KPI k
							INNER JOIN PA_T_RKKDetailTarget t
								ON k.KPIID = t.KPIID
							WHERE k.KPIID = $KPIID AND
								((k.BeginDate >= '$begin' AND k.EndDate <='$end') OR
								(k.EndDate >= '$begin' AND k.EndDate <='$end') OR
								(k.BeginDate >= '$begin' AND k.BeginDate <='$end') OR
								(k.BeginDate <= '$begin' AND k.EndDate >='$end')) AND
								(t.Month < $m_begin OR t.Month > $m_end)";
		return $this->pms->query($query)->row()->val;
	}

	public function count_out_kpi_rel_AB_byKPI($chief_kpi_id=0)
	{
		$query = "SELECT count(*) as val
							FROM PA_R_KPI
							WHERE chief_kpi_id = $chief_kpi_id AND
							((k.BeginDate >= '$begin' AND k.EndDate <='$end') OR
							(k.EndDate >= '$begin' AND k.EndDate <='$end') OR
							(k.BeginDate >= '$begin' AND k.BeginDate <='$end') OR
							(k.BeginDate <= '$begin' AND k.EndDate >='$end'))";
		return $this->pms->query($query)->row()->val;
	}

}
