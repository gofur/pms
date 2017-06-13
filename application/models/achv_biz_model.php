<?php
class Achv_biz_model extends Model{
	function __construct(){
		parent::__construct();
		$this->portal=$this->load->database('portal', TRUE);
		$this->pms = $this->load->database('default', TRUE);

		$this->load->model('rkk_model3');
		
	}
		/* Panduan penamaan fungsi
		Prefix / Awalan
		- "get_" 		: menghasilkan balikkan berupa record/ beberapa nilai
		- "count_"	:	menghasilkan balikkan berupa satu nilai
		- "add_"		: memasukkan record ke dalam tabel
		- "edit_"		:	mengedit record yang ada di tabel dengan data yang baru
		- "remove_"	: menghapus record yang ada
		Suffix / Akhiran
		-	"_list"		: hasil balikkan berupa banyak record
		- "_row"		: hasil balikkan berupa satu record

	*/


	public function count_header($rkk_id=0,$status='all')
	{
		$query = "SELECT COUNT(*) AS val
							FROM PA_T_RKKAchievement
							WHERE RKKID = $rkk_id";
		if (is_array($status)) {
			// $in = ' AND Status_Flag IN (';
			// foreach ($status as $key => $value) {
			// 	$in .= $value.', ';
			// }

			// $in_len = strlen($in) - 2;
			// $in = substr($in, 0, $in_len).')';
			// $query .= $in;
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
	/**
	 * [menghitung achievement RKK  satu bulan]
	 * @param  integer $rkk_id [description]
	 * @param  integer $month  [description]
	 * @param  string  $status [description]
	 * @return [type]          [description]
	 */
	public function count_header_month($rkk_id=0,$month=0,$status='all')
	{
		$query = "SELECT COUNT(*) AS val
							FROM PA_T_RKKAchievement
							WHERE RKKID = $rkk_id AND 
								Month = $month";
		if (is_array($status)) {
			// $in = ' AND Status_Flag IN (';
			// foreach ($status as $key => $value) {
			// 	$in .= $value.', ';
			// }

			// $in_len = strlen($in) - 2;
			// $in = substr($in, 0, $in_len).')';
			// $query .= $in;
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
		// echo $query;
		return $this->pms->query($query)->row()->val;
	}

	public function get_header_list($rkk_id=0,$status='all')
	{
		$query = "SELECT *
							FROM PA_T_RKKAchievement
							WHERE RKKID = $rkk_id";
		if (is_array($status)) {
			// $in = ' AND Status_Flag IN (';
			// foreach ($status as $key => $value) {
			// 	$in .= $value.', ';
			// }

			// $in_len = strlen($in) - 2;
			// $in = substr($in, 0, $in_len).')';
			// $query .= $in;
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

	/**
	 * [mendapatkan achievement RKK berdasarkan bulan]
	 * @param  integer $rkk_id [description]
	 * @param  integer $month  [description]
	 * @param  string  $status [description]
	 * @return [type]          [description]
	 */
	public function get_header_month_row($rkk_id=0,$month=0,$status='all')
	{
		$query = "SELECT TOP 1 *
							FROM PA_T_RKKAchievement
							WHERE RKKID = $rkk_id AND 
								Month = $month";
		if (is_array($status)) {
			// $in = ' AND Status_Flag IN (';
			// foreach ($status as $key => $value) {
			// 	$in .= $value.', ';
			// }

			// $in_len = strlen($in) - 2;
			// $in = substr($in, 0, $in_len).')';
			// $query .= $in;
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
		return $this->pms->query($query)->row();
	}

	/**
	 * [mendapatkan keterangan achievement RKK]
	 * @param  integer $achv_id [description]
	 * @return [type]           [description]
	 */
	public function get_header_row($achv_id=0)
	{
		$query = "SELECT * FROM PA_T_RKKAchievement WHERE RKKAchievementID = $achv_id";
		return $this->pms->query($query)->row();
	}

	/**
	 * [menambahkan header achievement RKK]
	 * @param integer $rkk_id [description]
	 * @param integer $month  [description]
	 */
	public function add_header($rkk_id=0,$month=0)
	{
		$query = "INSERT INTO PA_T_RKKAchievement (
								RKKID,
								Month,
								Status_Flag
							) VALUES (
								$rkk_id,
								$month,
								0
							)";
		$this->pms->query($query);
	}

	/**
	 * [mengubah status achievement RKK]
	 * @param  integer $achv_id [description]
	 * @param  integer $status  [description]
	 * @return [type]           [description]
	 */
	public function edit_header_status($achv_id=0,$status=0)
	{
		$query = "UPDATE PA_T_RKKAchievement
							SET DateSubmitted = GETDATE(),
								Status_Flag = $status
							WHERE RKKAchievementID = $achv_id";
		$this->pms->query($query);
	}

	/**
	 * [mengubah nilai tpc current dan ytd achievement bulanan RKK]
	 * @param  integer $achv_id [description]
	 * @param  float   $current [description]
	 * @param  float   $ytd     [description]
	 * @return [type]           [description]
	 */
	public function edit_header_tpc($achv_id=0,$current=0.00,$ytd=0.00)
	{
		$query = "UPDATE PA_T_RKKAchievement
							SET Cur_TPC = $current,
								YTD_TPC = $ytd
							WHERE RKKAchievementID = $achv_id";
		$this->pms->query($query);
	}

	/**
	 * [mengubah executive summary achievement RKK]
	 * @param  integer $achv_id [description]
	 * @param  string  $summary [description]
	 * @return [type]           [description]
	 */
	public function edit_header_summ($achv_id=0,$summary='')
	{
		$query = "UPDATE [PMS].[dbo].[PA_T_RKKAchievement]
							   SET [Summary] = '$summary'
							 WHERE RKKAchievementID=$achv_id";
		$this->pms->query($query);
	}

	/**
	 * [mengubah catatan achievement RKK]
	 * @param  integer $achv_id [description]
	 * @param  string  $note    [description]
	 * @return [type]           [description]
	 */
	public function edit_header_note($achv_id=0,$note='')
	{
		$query = "UPDATE [PMS].[dbo].[PA_T_RKKAchievement]
							   SET [Notes] = '$note'
							 WHERE RKKAchievementID=$achv_id";
		$this->pms->query($query);
	}

	/**
	 * [menghitung achievement KPI]
	 * @param  integer $achv_id [description]
	 * @return [type]           [description]
	 */
	public function count_detail($achv_id = 0,$kpi_id=0)
	{
		$query = "SELECT count(*) as val
							FROM PA_T_RKKAchievementDetail 
							WHERE RKKAchievementID=$achv_id AND 
								KPIID = $kpi_id";
		return $this->pms->query($query)->row()->val;
	}

	public function count_detail_month($achv_id = 0, $month = 0) 
	{
		$query = "SELECT count(*) as val
							FROM PA_T_RKKAchievementDetail 
							WHERE RKKAchievementID=$achv_id";
		return $this->pms->query($query)->row()->val;
	}

	public function count_detail_kpi_month($kpi_id=0,$month=0)
	{
		$query = "SELECT count(*) as val 
							FROM PA_T_RKKAchievementDetail d
							JOIN PA_T_RKKAchievement h 
							  ON h.RKKAchievementID = d.RKKAchievementID
							WHERE h.Month=$month AND
								d.KPIID = $kpi_id";
		return $this->pms->query($query)->row()->val;
	}

	/**
	 * [mendapatkan daftar achievement KPI]
	 * @param  integer $achv_id [description]
	 * @return [type]           [description]
	 */
	public function get_detail_list($achv_id=0)
	{
		$query = "SELECT * 
							FROM PA_T_RKKAchievementDetail 
							WHERE RKKAchievementID=$achv_id";
		return $this->pms->query($query)->result();
	}

	/**
	 * [mendapatkan daftar achievement KPI]
	 * @param  integer $kpi_id [description]
	 * @return [type]          [description]
	 */
	public function get_detail_kpi_list($kpi_id=0)
	{
		$query = "SELECT  * 
							FROM PA_T_RKKAchievementDetail 
							WHERE KPIID = $kpi_id";
		return $this->pms->query($query)->result();
	}

	/**
	 * [mendapatkan achievement KPI]
	 * @param  integer $achv_id [description]
	 * @param  integer $kpi_id  [description]
	 * @return [type]           [description]
	 */
	public function get_detail_kpi_row($achv_id=0, $kpi_id=0)
	{
		$query = "SELECT TOP 1 * 
							FROM PA_T_RKKAchievementDetail d
							JOIN PA_T_RKKAchievement h 
							  ON h.RKKAchievementID = d.RKKAchievementID
							WHERE d.RKKAchievementID=$achv_id AND
								d.KPIID = $kpi_id
							ORDER BY h.Month Desc";
		return $this->pms->query($query)->row();
	}

	/**
	 * [mendapatkan pencapian KPI pada satu bulan]
	 * @param  integer $kpi_id [description]
	 * @param  [type]  $month  [description]
	 * @return [type]          [description]
	 */
	public function get_detail_kpi_month_row($kpi_id=0,$month=0)
	{
		$query = "SELECT TOP 1 * 
							FROM PA_T_RKKAchievementDetail d
							JOIN PA_T_RKKAchievement h 
							  ON h.RKKAchievementID = d.RKKAchievementID
							WHERE h.Month=$month AND
								d.KPIID = $kpi_id
							ORDER BY h.Month Desc";
		return $this->pms->query($query)->row();
	}

	/**
	 * [menghitung pencapaian YTD sebuah KPI]
	 * @param  integer $kpi_id [description]
	 * @param  integer $month  [1-12]
	 * @return [type]          [description]
	 */
	public function calc_ytd($kpi_id=0,$month=0)
	{

		$query = "SELECT TOP 1 v.*, r.Reference 
							FROM PA_V_RKK_KPI v
							LEFT JOIN PA_M_Reference r 
							ON r.ReferenceID=v.ref_id
							WHERE KPIID = $kpi_id";
 		$kpi = $this->pms->query($query)->row();
 		if ($month>date('n',strtotime($kpi->RKK_EndDate))) {
 			$month = date('n',strtotime($kpi->RKK_EndDate));
 		}
		//echo $kpi->YTDID;
 		switch ($kpi->YTDID) {
 			case 1:
				# Akumulasi / Acumulation
 				$query=	"SELECT 
									SUM(A.Achievement) AS Achievement
								FROM
									PA_T_RKKAchievementDetail A,
									PA_T_RKKAchievement B
								WHERE
									(A.RKKAchievementID = B.RKKAchievementID AND
									A.KPIID = $kpi_id AND
									B.Month < $month AND B.Status_Flag = 3) OR 
									(A.RKKAchievementID = B.RKKAchievementID AND
									A.KPIID = $kpi_id AND B.Month <= $month)";
 				break;
 			case 2:
				# Rata - rata / Average
 				$query=	"SELECT 
								AVG(A.Achievement) AS Achievement
							FROM
								PA_T_RKKAchievementDetail A,
								PA_T_RKKAchievement B
							WHERE
								(A.RKKAchievementID = B.RKKAchievementID AND
								A.KPIID = $kpi_id AND
								B.Month < $month AND B.Status_Flag = 3) OR 
								(A.RKKAchievementID = B.RKKAchievementID AND
								A.KPIID = $kpi_id AND B.Month <= $month)";
 				break;
 			case 3:
				# Nilai terakhir / Last Value
 				$query=	"SELECT TOP 1
									A.Achievement
								FROM
									PA_T_RKKAchievementDetail A,
									PA_T_RKKAchievement B
								WHERE
									A.RKKAchievementID = B.RKKAchievementID AND
									A.KPIID = $kpi_id AND
									B.Month <= $month
								ORDER BY Month DESC";
 				break;
 		}
 		// echo '<br>'.$query;
 		$result = $this->pms->query($query)->row();
		if(count($result)) {
			if (is_null($result->Achievement)) {
				return 0;
			} else {
				return $result->Achievement;
			}
		} else {
			return 0;
		}
	}

	/**
	 * [mendapatkan detail achievement KPI]
	 * @param  integer $achv_detail_id [description]
	 * @return [type]                  [description]
	 */
	public function get_detail_row($achv_detail_id=0)
	{
		$query = "SELECT *
							FROM PA_T_RKKAchievementDetail 
							WHERE RKKAchievementDetailID = $achv_detail_id ";
		return $this->pms->query($query)->row();
	}

	public function update_evid($achv_detail_id=0,$path=NULL)
	{
		$this->pms->set('path_evidence',$path);
		$this->pms->where('RKKAchievementDetailID', $achv_detail_id);
		$this->pms->update('PA_T_RKKAchievementDetail');

	}

	/**
	 * [menambahkan achievement KPI]
	 * @param integer $achv_id [description]
	 * @param integer $kpi_id  [description]
	 * @param float   $achv    [description]
	 * @param boolean $is_skip [description]
	 * @param string  $notes   [description]
	 */
	public function add_detail($achv_id=0,$kpi_id=0,$achv=0.00,$is_skip=FALSE,$notes='')
	{
		switch ($achv) {
			case 'NULL':
				$query = "INSERT INTO [PMS].[dbo].[PA_T_RKKAchievementDetail]
					           ([RKKAchievementID]
					           ,[KPIID]
					           ,[Achievement]
					           ,[DateSubmitted]
					           ,[isSkip]
					           ,[note])
					     VALUES
					           ($achv_id
					           ,$kpi_id
					           ,NULL
					           ,GETDATE()
					           ,$is_skip
					           ,'$notes')";
				break;
			
			default:
				$query = "INSERT INTO [PMS].[dbo].[PA_T_RKKAchievementDetail]
					           ([RKKAchievementID]
					           ,[KPIID]
					           ,[Achievement]
					           ,[DateSubmitted]
					           ,[isSkip]
					           ,[note])
					     VALUES
					           ($achv_id
					           ,$kpi_id
					           ,$achv
					           ,GETDATE()
					           ,$is_skip
					           ,'$notes')";
				break;
		}
		$this->pms->query($query);
	}

	/**
	 * [mengubah achievement KPI]
	 * @param  integer $achv_detail_id [description]
	 * @param  float   $achv           [description]
	 * @param  boolean $is_skip        [description]
	 * @param  string  $notes          [description]
	 * @return [type]                  [description]
	 */
	public function edit_detail($achv_detail_id=0,$achv=0.00,$is_skip=FALSE,$notes='')
	{
		switch ($achv) {
			case 'NULL':
				$query = "UPDATE [PMS].[dbo].[PA_T_RKKAchievementDetail]
						   SET [Achievement] = NULL
						      ,[DateSubmitted] = GETDATE()
						      ,[isSkip] = $is_skip
						      ,note = '$notes'
						 WHERE RKKAchievementDetailID=$achv_detail_id";
				break;
			
			default:
				$query = "UPDATE [PMS].[dbo].[PA_T_RKKAchievementDetail]
						   SET [Achievement] = $achv
						      ,[DateSubmitted] = GETDATE()
						      ,[isSkip] = $is_skip
						      ,note = '$notes'
						 WHERE RKKAchievementDetailID=$achv_detail_id";
				break;
		}
		$this->pms->query($query);
	}
	
	/**
	 * [menghitung tpc score dari acvhiement]
	 * @param  integer $formula_id [description]
	 * @param  integer $persen     [description]
	 * @param  string  $begin      [description]
	 * @param  string  $end        [description]
	 * @return [type]              [description]
	 */
	public function count_tpc_score($formula_id=0,$persen=0,$begin='',$end='')
	{
		if ($persen > 999.99) {
			$persen = 999.99;
		} elseif ($persen < -999.99) {
			$persen = -999.99;
		}
		$query = "SELECT count(*) as val
							FROM PA_M_PCFormulaScore
							WHERE PCFormulaID = $formula_id AND
								((PCLow >= $persen AND PCHigh <= $persen ) OR 
								(PCLow <= $persen AND PCHigh >= $persen )) AND 
								((BeginDate >= '$begin' AND EndDate <='$end') OR 
 								(EndDate >= '$begin' AND EndDate <= '$end') OR 
 								(BeginDate >= '$begin' AND BeginDate <='$end' ) OR
 								(BeginDate <= '$begin' AND EndDate >= '$end'))";
		return $this->pms->query($query)->row()->val;
	}

	/**
	 * [konversi pemenuhan target dan achievement menjadi score TPC]
	 * @param  integer $formula_id [description]
	 * @param  integer $persen     [description]
	 * @param  string  $begin      [description]
	 * @param  string  $end        [description]
	 * @return [type]              [description]
	 */
	public function get_tpc_score_row($formula_id=0,$persen=0,$begin='',$end='')
	{
		if ($persen > 999.99) {
			$persen = 999.99;
		} elseif ($persen < -999.99) {
			$persen = -999.99;
		}
		$query = "SELECT TOP 1 *
							FROM PA_M_PCFormulaScore
							WHERE PCFormulaID = $formula_id AND
								((PCLow >= $persen AND PCHigh <= $persen ) OR 
								(PCLow <= $persen AND PCHigh >= $persen )) AND 
								((BeginDate >= '$begin' AND EndDate <='$end') OR 
 								(EndDate >= '$begin' AND EndDate <= '$end') OR 
 								(BeginDate >= '$begin' AND BeginDate <='$end' ) OR
 								(BeginDate <= '$begin' AND EndDate >= '$end'))";
		return $this->pms->query($query)->row();
	}

	/**
	 * [warna dari PC Score]
	 * @param  integer $score [description]
	 * @return [type]         [description]
	 */
	public function get_tpc_color_row($score=0)
	{
		/*if($score==1)
		{
			echo 'a';
		}else{
			echo 'b';
		}*/
		$query = 'SELECT TOP 1 [CodeColourID]
						      ,[Colour] as Color
						      ,[PAScore]
						  FROM [PMS].[dbo].[Core_M_CodeColour]
						  WHERE [PAScore] ='.$score.' AND [TypeFlag] = 1
						  ORDER BY CodeColourID DESC';
		return $this->pms->query($query)->row();
	}

	/**
	 * [menghitung record Achievement yang ada per perspective ]
	 * @param  integer $achv_id  [description]
	 * @param  integer $persp_id [description]
	 * @return [type]            [description]
	 */
	public function count_persp($achv_id=0,$persp_id=0)
	{
		$query = "SELECT COUNT(*) 
							FROM PA_V_Achv
							WHERE RKKAchievementID = $achv_id AND
							  PerspectiveID = $persp_id";
		return $this->pms->query($query)->result();
	}

	/**
	 * [menghitung tpc current perspectif]
	 * @param  integer $achv_id  [description]
	 * @param  integer $persp_id [description]
	 * @return [type]            [description]
	 */
	public function calc_cur_tpc_persp($achv_id=0,$persp_id=0)
	{
		$ls           = $this->get_cur_persp_list($achv_id,$persp_id);

		$total_weight = 0;
		$subtotal     = 0;
		foreach ($ls as $row) {
			if($row->CaraHitung == OPT_VAL_CARA_HITUNG_NORMALIZE)
			{
				if ($row->Target == 0){
                                        $persen = $row->Achievement;
                                } else {
                               		$persen = $row->Achievement / $row->Target * 100;
                        	}
			}else{
				if ($row->Target == 0 && $row->Achievement == 0) {
					$persen = 100;
				} else if ($row->Target == 0) {
					$persen = 0;
				}else{ 
					$persen = $row->Achievement / $row->Target * 100; 
				}
			} 
			$pc           = $this->get_tpc_score_row($row->PCFormulaID,$persen,date('Y-m-d'), date('Y-m-d'));
			
			$subtotal     += $pc->PCFormulaScore * $row->Bobot;
			$total_weight += $row->Bobot;
		}

		if ($total_weight) {
			
			return $subtotal / $total_weight;
		} else {
			return false;
		}
	}

	public function calc_ytd_tpc_persp($achv_id=0,$persp_id=0)
	{
		$head   = $this->get_header_row($achv_id);
		$rkk_id = $head->RKKID;
		$month  = $head->Month;
		$rkk    = $this->rkk_model3->get_rkk_row($rkk_id);
		$begin  = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, substr($rkk->BeginDate, 0,4)));
		$end    = date('Y-m-t H:i:s', mktime(23, 59, 59, $month, 1, substr($rkk->EndDate, 0,4)));

		$kpi_ls = $this->rkk_model3->get_kpi_persp_list($rkk_id,$persp_id,$begin,$end);

		$weight = 0;
		$temp   = 0;
		foreach ($kpi_ls as $kpi) {
			$ytd_target = $this->rkk_model3->calc_target_ytd_value($kpi->KPIID,$month);
			$ytd_achv   = $this->calc_ytd($kpi->KPIID,$month);
			if($kpi->CaraHitung == OPT_VAL_CARA_HITUNG_NORMALIZE)
                        {
                                if ($ytd_target == 0){
                                        $ytd_persen = $ytd_achv;
                                } else {
                                        $ytd_persen = $ytd_achv / $ytd_target * 100;
                                }

				$count = count($this->get_tpc_score_row($kpi->PCFormulaID,$ytd_persen,$begin,$end));
					if ($count) {
						$pc = $this->get_tpc_score_row($kpi->PCFormulaID,$ytd_persen,$begin,$end)->PCFormulaScore;
						$weight += $kpi->Bobot;
						$temp   += $pc * $kpi->Bobot;
					} else {
						$temp += 0;
					}
                        }else{
				if ($ytd_target != '-' && $ytd_achv != '-' && $ytd_target != 0 ) {
					$ytd_persen = $ytd_achv / $ytd_target * 100;
				
					$count = count($this->get_tpc_score_row($kpi->PCFormulaID,$ytd_persen,$begin,$end));
					if ($count) {
						$pc = $this->get_tpc_score_row($kpi->PCFormulaID,$ytd_persen,$begin,$end)->PCFormulaScore;
						$weight += $kpi->Bobot;
						$temp   += $pc * $kpi->Bobot;
					} else {
						$temp += 0;
					}
				} else {
					$pc =0;
				}
			}		

			

		}

		if ($weight != 0 ) {

			return $temp / $weight;
		} else {
			return false;
		}
	}

	/**
	 * [daftar Achievement yang ada per perspective pada bulan itu]
	 * @param  integer $achv_id  [description]
	 * @param  integer $persp_id [description]
	 * @return [type]            [description]
	 */
	public function get_cur_persp_list($achv_id=0,$persp_id=0)
	{
		$query = "SELECT * 
							FROM PA_V_Achv
							WHERE RKKAchievementID = $achv_id AND
							  PerspectiveID = $persp_id";
		return $this->pms->query($query)->result();
	}

	/**
	 * [mendapatkan daftar Achievement tiap KPI hingga bulan berjalan]
	 * @param  integer $achv_id  [description]
	 * @param  integer $persp_id [description]
	 * @return [type]            [description]
	 */
	public function get_ytd_kpi_list($rkk_id=0,$month=0,$kpi_id)
	{

		$query = "SELECT *
							FROM PA_V_Achv
							WHERE RKKID = $rkk_id AND
								Target_Month <= $month AND
								Achv_Month <= $month AND
								KPIID = $kpi_id";
		return $this->pms->query($query)->result();
	}

}
?>
