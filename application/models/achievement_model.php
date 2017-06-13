<?php
class Achievement_model extends Model{
	function __construct(){
		parent::__construct();
		$this->portal=$this->load->database('portal', TRUE);
		$this->pms = $this->load->database('default', TRUE);
		
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
	function get_Position_row($PositionID,$isSAP){
		if($isSAP){
			$table="SAP";
		}else{
			$table="nonSAP";
		}
		$query="SELECT P.*, O.OrganizationName,O.OrganizationParent FROM Core_M_Position_$table P, Core_M_Organization_$table O WHERE O.OrganizationID=P.OrganizationID AND P.PositionID=$PositionID";
		return $this->pms->query($query)->row();
	}
	function get_header_row($RKKAchievementID)
	{
		$query = "SELECT * FROM PA_T_RKKAchievement WHERE RKKAchievementID = $RKKAchievementID";
		return $this->pms->query($query)->row();
	}	
	function get_Achievement_list($UserID,$PositionID,$PerspectiveID,$month)
	{
		$query = "SELECT * 
							FROM PA_V_Achievement
							WHERE 
							UserID = $UserID AND 
							PositionID = $PositionID AND
							[PerspectiveID] = $PerspectiveID AND
							Month = $month AND
							Target_month = $month";
		return $this->pms->query($query)->result();
	}
	function count_achievement_detail($RKKDetailID=0)
	{
		$query = "SELECT COUNT(*) AS val
		  FROM [PMS].[dbo].[PA_T_RKKAchievementDetail]
		  WHERE RKKDetailID = $RKKDetailID";
		return $this->pms->query($query)->row()->val;
	}
	function get_ActivePeriode_row()
	{
		$query ="SELECT TOP 1 * 
						FROM Core_M_PeriodePM 
						WHERE [BeginDate] <= '".date('Y-m-d')."' and [EndDate] >= '".date('Y-m-d')."' ORDER BY PeriodePMID DESC";
		return $this->pms->query($query)->row();
	}
	function get_User_row($UserID)
	{
		
		$query = "SELECT u.*,r.Role,r.RoleID 
							FROM Core_M_User u, Core_M_Role r 
							WHERE u.UserID = $UserID and r.RoleID=u.RoleID";
		return $this->pms->query($query)->row();
	}

	function get_Holder_list($NIK,$isSAP,$BeginDate='',$EndDate='')
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
							WHERE NIK = '$NIK'";

		if($BeginDate!='' && $EndDate !='')
		{
			$query .=" AND ((Holder_BeginDate<='$BeginDate' And Holder_EndDate>='$EndDate') OR (Holder_BeginDate<=GETDATE() And Holder_EndDate>='$EndDate'))";
		}
		
		return $this->pms->query($query)->result();
	}

	function get_Holder_row($HolderID,$isSAP)
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

	function get_rkk_byUserPosition_row($UserID,$PositionID,$BeginDate,$EndDate)
	{
		$query = "SELECT TOP 1 * 
						FROM PA_T_RKK 
						WHERE UserID=$UserID AND PositionID=$PositionID AND
							(( BeginDate<= '$BeginDate' AND EndDate>='$EndDate') OR  
							BeginDate<= GETDATE() AND EndDate>='$EndDate')";
		return $this->pms->query($query)->row();
	}

	function get_Perspective_list($RKKID,$BeginDate,$EndDate)
	{
		$query="SELECT Distinct(PerspectiveID),Perspective 
					FROM PA_V_RKKKPI 
					WHERE RKKID=$RKKID AND 
						RKKDetail_BeginDate<= '$BeginDate' AND 
						RKKDetail_EndDate>='$EndDate'  AND 
						KPI_BeginDate<= '$BeginDate' AND 
						KPI_EndDate>='$EndDate'";
		return $this->pms->query($query)->result();
	}

	function get_kpi_list($RKKID,$PerspectiveID,$end='')
	{
		$query=	"SELECT 
							A.RKKDetailID,
							A.ReferenceID,
							A.Ref_weight,  
							A.ChiefRKKDetailID, 
							C.SasaranStrategis, 
							B.* , 
							D.Satuan,
							E.PCFormula,
							E.Operator,
							E.Perception
						FROM 
							PA_T_RKKDetail A, 
							PA_T_KPI B, 
							PA_T_SasaranStrategis C, 
							PA_M_Satuan D, 
							PA_M_PCFormula E
						WHERE
							A.KPIID = B.KPIID AND
							B.SasaranStrategisID = C.SasaranStrategisID AND 
							B.SatuanID = D.SatuanID AND
							B.PCFormulaID = E.PCFormulaID AND
							A.RKKID=$RKKID AND ";
		if($end !=''){
			$query .="B.EndDate<= '$end' AND "; 
		}
		$query .= " C.PerspectiveID = $PerspectiveID";
		
		return $this->pms->query($query)->result();
	}

	function get_cascade_kpi_list($ChiefRKKDetailID)
	{
		$query=	"SELECT 
							A.*
						FROM 
							PA_T_RKKDetail A
						WHERE
							A.ChiefRKKDetailID=$ChiefRKKDetailID";
		return $this->pms->query($query)->result();
	}
	function get_cascade_achivement_list($ChiefRKKDetailID,$month)
	{
		$query=	"SELECT 
							A.*
						FROM 
							PA_V_Achievement A
						WHERE
							A.ChiefRKKDetailID=$ChiefRKKDetailID AND 
							A.month=$month";
		return $this->pms->query($query)->result();
	}
	function check_header_achv($RKKID, $Month)//mencek keberadaan header achivement, jika belum ada tambahkan terlebih dahulu
	{
		$query_1 = "SELECT TOP 1 * FROM PA_T_RKKAchievement WHERE RKKID=$RKKID AND Month=$Month ORDER BY RKKAchievementID DESC";
		$result = $this->pms->query($query_1)->row();
		if (count($result))
		{
			return $result;
		}
		else
		{
			$query_2 = "INSERT INTO [PMS].[dbo].[PA_T_RKKAchievement]
						 ([RKKID]
						 ,[Month]
						 ,Status_Flag)
				VALUES
						 ($RKKID
						 ,$Month
						 ,0)";
			$this->pms->query($query_2);
			return $this->pms->query($query_1)->row();
		}
	}
	function edit_header_status($RKKAchievementID,$status_flag=1)
	{
		$query = "UPDATE [PMS].[dbo].[PA_T_RKKAchievement]
							   SET [DateSubmitted] = GETDATE(),
							   			[Status_Flag] = $status_flag
							 WHERE RKKAchievementID=$RKKAchievementID";
		$this->pms->query($query);
	}
	public function edit_summary($RKKAchievementID,$summary='')
	{
		$query = "UPDATE [PMS].[dbo].[PA_T_RKKAchievement]
							   SET [Summary] = '$summary'
							 WHERE RKKAchievementID=$RKKAchievementID";
		$this->pms->query($query);
	}

	public function edit_note($RKKAchievementID,$notes = '')
	{
		$query = "UPDATE [PMS].[dbo].[PA_T_RKKAchievement]
							   SET [Notes] = '$notes'
							 WHERE RKKAchievementID=$RKKAchievementID";
		$this->pms->query($query);
	}
	function check_achv($RKKDetailID,$RKKAchievementID)
	{
		$query_check = "SELECT 
											TOP 1 * 
										FROM 
											PA_T_RKKAchievementDetail 
										WHERE 
											RKKDetailID = $RKKDetailID AND 
											RKKAchievementID = $RKKAchievementID 
										ORDER BY 
											RKKAchievementDetailID DESC";
		return $this->pms->query($query_check)->row();
		
	}
	function add_achv($RKKDetailID,$RKKAchievementID,$Achievement,$isSkip=0,$note='')
	{
		if ($Achievement!='NULL')
		{
			$query = "INSERT INTO [PMS].[dbo].[PA_T_RKKAchievementDetail]
					           ([RKKAchievementID]
					           ,[RKKDetailID]
					           ,[Achievement]
					           ,[DateSubmitted]
					           ,[isSkip]
					           ,[note])
					     VALUES
					           ($RKKAchievementID
					           ,$RKKDetailID
					           ,$Achievement
					           ,GETDATE()
					           ,$isSkip
					           ,'$note')";
		}
		else
		{
			$query = "INSERT INTO [PMS].[dbo].[PA_T_RKKAchievementDetail]
					           ([RKKAchievementID]
					           ,[RKKDetailID]
					           ,[Achievement]
					           ,[DateSubmitted]
					           ,[isSkip]
					           ,[note])
					     VALUES
					           ($RKKAchievementID
					           ,$RKKDetailID
					           ,NULL
					           ,GETDATE()
					           ,$isSkip
					           ,'$note')";
		}
		$this->pms->query($query);
		$query = "SELECT 
								TOP 1 * 
							FROM 
								PA_T_RKKAchievementDetail 
							ORDER BY 
								RKKAchievementDetailID DESC";
		return $this->pms->query($query)->row();
	}

	function edit_achv($RKKAchievementDetailID,$Achievement,$isSkip=0,$note='')
	{	
		if($Achievement!='NULL')
		{
			$query = "UPDATE [PMS].[dbo].[PA_T_RKKAchievementDetail]
						   SET [Achievement] = $Achievement
						      ,[DateSubmitted] = GETDATE()
						      ,[isSkip] = $isSkip
						      ,[note] = '$note'
						 WHERE RKKAchievementDetailID=$RKKAchievementDetailID";
		}
		else
		{
			$query = "UPDATE [PMS].[dbo].[PA_T_RKKAchievementDetail]
						   SET [Achievement] = NULL
						      ,[DateSubmitted] = GETDATE()
						      ,[isSkip] = $isSkip
						      ,note = '$note'
						 WHERE RKKAchievementDetailID=$RKKAchievementDetailID";
		}
		
		$this->pms->query($query);
	}
	function get_monthly_achv_list($RKKAchievementID)
	{
		$query = "SELECT * FROM PA_T_RKKAchievementDetail WHERE RKKAchievementID=$RKKAchievementID";
		return $this->pms->query($query)->result();
	}

	function get_ytd_achv_list($RKKDetailID)
	{
		$query = "SELECT * FROM PA_T_RKKAchievementDetail WHERE RKKDetailID=$RKKDetailID";
		return $this->pms->query($query)->result();
	}
	function get_kpi_row($RKKDetailID)
	{
		$query = "SELECT 
								TOP 1 *
							FROM 
								PA_V_RKKKPI A
							WHERE 
								RKKDetailID = $RKKDetailID
							ORDER BY KPIID DESC";
		return $this->pms->query($query)->row();
	}

	function get_monthly_target_row($RKKDetailID,$Month,$BeginDate,$EndDate)
	{
		$query=	"SELECT 
							TOP 1 * 
						FROM 
							PA_T_RKKDetailTarget 
						WHERE 
							RKKDetailID = $RKKDetailID AND
							Month = $Month AND 
							(([BeginDate] <= '$BeginDate' AND [EndDate] >= '$EndDate' ) OR 
								([BeginDate] <= GETDATE() AND [EndDate] >= '$EndDate' ))
						ORDER BY 
							RKKDetailTargetID DESC ";

		return $this->pms->query($query)->row();

	}
	function get_monthly_achv_row($RKKDetailID,$Month)
	{
		$query=	"SELECT 
							TOP 1 B.*
						FROM
							PA_T_RKKAchievement A,
							PA_T_RKKAchievementDetail B
						WHERE
							A.RKKAchievementID=B.RKKAchievementID AND
							A.[Month] = $Month AND
							RKKDetailID = $RKKDetailID
						ORDER BY RKKAchievementDetailID DESC";
		return $this->pms->query($query)->row();
	}
	function count_ytd_target($RKKDetailID,$Month, $BeginDate,$EndDate,$Type)
	{
		switch ($Type) {
			case 1 :
				$query = "SELECT 
										SUM(Target) as Target
									FROM
										PA_T_RKKDetailTarget
									WHERE
										RKKDetailID = $RKKDetailID	AND
										[Month]<=$Month AND
										(([BeginDate] <= '$BeginDate' AND [EndDate] >= '$EndDate' ) OR 
											([BeginDate] <= GETDATE() AND [EndDate] >= '$EndDate' ))";
				break;
			case 2:
				$query = "SELECT 
										AVG(Target) as Target
									FROM
										PA_T_RKKDetailTarget
									WHERE
										RKKDetailID = $RKKDetailID	AND
										[Month]<=$Month AND
										(([BeginDate] <= '$BeginDate' AND [EndDate] >= '$EndDate' ) OR 
											([BeginDate] <= GETDATE() AND [EndDate] >= '$EndDate' ))";
				break;
			case 3:
				$query = "SELECT 
										Target
									FROM
										PA_T_RKKDetailTarget
									WHERE
										RKKDetailID = $RKKDetailID	AND
										[Month]=$Month AND
										(([BeginDate] <= '$BeginDate' AND [EndDate] >= '$EndDate' ) OR 
											([BeginDate] <= GETDATE() AND [EndDate] >= '$EndDate' ))";
				break;
		}

		if (count($this->pms->query($query)->row()))
		{
			$value = $this->pms->query($query)->row()->Target;
			if (is_null($value))
			{
				$value = '-';
			}
			return $value;

		}
		else
		{
			return '-';
			
		}
	}
	function get_subordinate_list($isSAP,$PositionID,$Begindate,$Enddate)
	{
		$query = "exec [dbo].[DirectSubordinateException] $PositionID, $isSAP, '$Begindate', '$Enddate'";
		return $this->pms->query($query)->result();
	}
	function count_ytd_achv($RKKDetailID,$Month,$Type)
	{
		switch ($Type) {
			case 1:
				$query=	"SELECT 
									SUM(A.Achievement) AS Achievement
								FROM
									PA_T_RKKAchievementDetail A,
									PA_T_RKKAchievement B
								WHERE
									A.RKKAchievementID = B.RKKAchievementID AND
									A.RKKDetailID = $RKKDetailID AND
									B.Month <= $Month";
				break;
			case 2:
				$query=	"SELECT 
								AVG(A.Achievement) AS Achievement
							FROM
								PA_T_RKKAchievementDetail A,
								PA_T_RKKAchievement B
							WHERE
								A.RKKAchievementID = B.RKKAchievementID AND
								A.RKKDetailID = $RKKDetailID AND
								B.Month <= $Month";
				break;
			case 3:
				$query=	"SELECT TOP 1
									A.Achievement
								FROM
									PA_T_RKKAchievementDetail A,
									PA_T_RKKAchievement B
								WHERE
									A.RKKAchievementID = B.RKKAchievementID AND
									A.RKKDetailID = $RKKDetailID AND
									B.Month <= $Month
								ORDER BY RKKAchievementDetailID DESC";
				break;
		}

		$result = $this->pms->query($query)->row();
		if(count($result))
		{
			if (is_null($result->Achievement))
			{
				return '-';
			}
			else
			{
				return $result->Achievement;
			}
		}
		else
		{
			return '-';
		}

	}
	function get_formula_row($PCFormulaID)
	{
		$query = "SELECT * FROM PA_M_PCFormula WHERE PCFormulaID=$PCFormulaID";
		return $this->pms->query($query)->row(); 
	}
	function get_PCFormula_score_row($PCFormulaID,$score,$BeginDate,$EndDate)
	{
		$query ="SELECT 
							*
						FROM
							PA_M_PCFormulaScore
						WHERE 
							PCFormulaID=$PCFormulaID AND
							PCLow >= $score AND
							PCHigh <= $score AND 
							(([BeginDate] <= '$BeginDate' AND [EndDate] >= '$EndDate' ) OR 
								([BeginDate] <= GETDATE() AND [EndDate] >= '$EndDate' ))";
		$result = $this->pms->query($query)->row();
		if(! count($result))
		{
			$query ="SELECT 
							*
						FROM
							PA_M_PCFormulaScore
						WHERE 
							PCFormulaID=$PCFormulaID AND
							PCLow <= $score AND
							PCHigh >= $score AND 
							(([BeginDate] <= '$BeginDate' AND [EndDate] >= '$EndDate' ) OR 
								([BeginDate] <= GETDATE() AND [EndDate] >= '$EndDate' ))";
			$result = $this->pms->query($query)->row();
			
		}
		return $result;

	}

	function get_color_PC_row($PCScore)
	{
		$query = "SELECT TOP 1 [CodeColourID]
						      ,[Colour]
						      ,[PAScore]
						  FROM [PMS].[dbo].[Core_M_CodeColour]
						  WHERE [PAScore]=$PCScore AND [TypeFlag] = 1
						  ORDER BY CodeColourID DESC";
		return $this->pms->query($query)->row();
	}

	function get_month_achv($month, $RKKID){
		$query ="SELECT [RKKAchievementDetailID]
      ,[Achievement]
      ,[input_date]
      ,[RKKAchievementID]
      ,[Month]
      ,[Target_month]
      ,[DateSubmitted]
      ,[Status_Flag]
      ,[RKKDetailTargetID]
      ,[Target]
      ,[RKKDetailID]
      ,[RKKDetail_BeginDate]
      ,[RKKDetail_EndDate]
      ,[RKKPositionID]
      ,[UserID]
      ,[PositionID]
      ,[Chief_isSAP]
      ,[isSAP]
      ,[statusFlag]
      ,[ChiefPositionID]
      ,[RKK_BeginDate]
      ,[RKK_EndDate]
      ,[RKKID]
      ,[KPIID]
      ,[KPIGenericID]
      ,[SasaranStrategisID]
      ,[SatuanID]
      ,[PCFormulaID]
      ,[YTDID]
      ,[KPI]
      ,[Bobot]
      ,[Description]
      ,[Baseline]
      ,[TargetAkhirTahun]
      ,[KPI_BeginDate]
      ,[KPI_EndDate]
      ,[Satuan]
      ,[YTD]
      ,[PCFormula]
      ,[Perception]
      ,[OrganizationID]
      ,[CaraHitung]
      ,[SasaranStrategis]
      ,[Perspective]
      ,[PerspectiveID]
      ,[SO_Desc]
      ,[ChiefRKKDetailID]
      ,[ReferenceID]
      ,[Ref_weight]
  FROM [PMS].[dbo].[PA_V_Achievement]
  WHERE Month=$month
  	AND RKKID=$RKKID";
  	return $this->pms->query($query)->result();
	}

	public function get_month_header($rkk_id = 0,$month=1)
	{
		$this->pms->select('a.RKKAchievementID as achv_id');
		$this->pms->select('a.Month as achv_month');
		$this->pms->select('a.Cur_TPC as cur_tpc');
		$this->pms->select('a.YTD_TPC as ytd_tpc');
		$this->pms->select('a.Status_Flag as status');
		$this->pms->from('PA_T_RKKAchievement a');
		$this->pms->where("RKKID",$rkk_id);
		$this->pms->where("Month",$month);
		$this->pms->order_by('RKKAchievementID');
		$result = $this->pms->get()->row();

		if (count($result)) {
			return $result;
		} else {
			return false;
		}
	}

}
?>