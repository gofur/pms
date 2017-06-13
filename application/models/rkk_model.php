<?php
class RKK_model extends Model{
	function __construct(){
		parent::__construct();
		$this->pms = $this->load->database('default', TRUE);
		
 	}
 	/* Panduan penamaan fungsi
 		Prefix / Awalan
 		- "get_" 		: menghasilkan balikkan berupa record/ beberapa nilai
 		- "count_"	:	menghasilkan balikkan berupa satu nilai
		- "check_"	: menghasilkan balikkan true atau false
		- "add_"		: memasukkan record ke dalam tabel
		- "edit_"		:	mengedit record yang ada di tabel dengan data yang baru
		- "delimit_":	mengedit EndDate record untuk mendelimit masa aktifnya 
		- "remove_"	: menghapus record yang ada
		Suffix / Akhiran
		-	"_list"		: hasil balikkan berupa banyak record
		- "_row"		: hasil balikkan berupa satu record

 	*/
	//Objective / Sasaran Strategis 
 	function get_Objective_row($ObjectiveID){
		$query = "SELECT * FROM PA_T_SasaranStrategis WHERE SasaranStrategisID=$ObjectiveID ";
		return $this->pms->query($query)->row();
	}
	function get_Objective_list($OrganizationID,$PerspectiveID){
		$query="SELECT * FROM PA_T_SasaranStrategis WHERE OrganizationID=$OrganizationID and PerspectiveID=$PerspectiveID";
		return $this->pms->query($query)->result();
	}
	function add_Objective($OrganizationID,$PerspectiveID,$Objective,$Description){
		$query = "INSERT INTO [PA_T_SasaranStrategis]([OrganizationID],[PerspectiveID],[SasaranStrategis],[Description])VALUES('$OrganizationID','$PerspectiveID','$Objective','$Description')";
		$this->pms->query($query);
		$query = "SELECT TOP 1 * FROM PA_T_SasaranStrategis ORDER BY SasaranStrategisID DESC";
		return $this->pms->query($query)->row();
	}
	function edit_Objective($ObjectiveID,$PerspectiveID,$Objective,$Description){
		$query = "UPDATE [PA_T_SasaranStrategis] SET [PerspectiveID] = '$PerspectiveID',[SasaranStrategis]='$Objective', [Description]='$Description' WHERE SasaranStrategisID=$ObjectiveID";
		$this->pms->query($query);
	}
	function remove_Objective($ObjectiveID){
		$query = "DELETE FROM PA_T_SasaranStrategis WHERE SasaranStrategisID=$ObjectiveID";
		$this->pms->query($query);
	}
	//end of Objective / Sasaran Strategis 
	// KPI / Key Performa Index 
 	function get_KPI_list($RKKID,$SasaranStrategisID,$BeginDate,$EndDate){
 		$query="SELECT v.*,r.Reference FROM PA_V_RKKKPI v LEFT JOIN PA_M_Reference r on r.ReferenceID=v.ReferenceID  WHERE RKKID=$RKKID AND SasaranStrategisID=$SasaranStrategisID AND RKKDetail_BeginDate>= '$BeginDate' AND RKKDetail_EndDate<='$EndDate'  AND KPI_BeginDate>= '$BeginDate' AND KPI_EndDate<='$EndDate'";
 		return $this->pms->query($query)->result();
 	}
 	function get_KPI_cascade_list($ChiefRKKDetailID,$BeginDate,$EndDate){
 		$query="SELECT v.*, m.NIK,m.RoleID,m.isSAP,m.Fullname,r.Reference FROM PA_V_RKKKPI v JOIN Core_M_User m ON m.UserID=v.UserID LEFT JOIN PA_M_Reference r ON r.ReferenceID=v.ReferenceID WHERE ChiefRKKDetailID=$ChiefRKKDetailID AND RKKDetail_BeginDate>= '$BeginDate' AND RKKDetail_EndDate<='$EndDate'  AND KPI_BeginDate>= '$BeginDate' AND KPI_EndDate<='$EndDate'";
 		return $this->pms->query($query)->result();
 	}
 	function get_KPI_row($KPIID){
 		$query = "SELECT * FROM PA_V_RKKKPI WHERE KPIID=$KPIID";
 		return $this->pms->query($query)->row();
 	}
 	function add_KPI($KPIGenericID,$SasaranStrategisID,$SatuanID,$PCFormulaID,$YTDID,$KPI,$Description,$Bobot,$Baseline,$BeginDate,$EndDate){
 		
 		$query="INSERT INTO [PA_T_KPI]([KPIGenericID],[SasaranStrategisID],[SatuanID],[PCFormulaID],[YTDID],[KPI],[Description],[Bobot],[Baseline],[BeginDate],[EndDate])VALUES($KPIGenericID,$SasaranStrategisID,$SatuanID,$PCFormulaID,$YTDID,'$KPI','$Description',$Bobot,$Baseline,'$BeginDate','$EndDate')";
		$this->pms->query($query);
		$query = "SELECT TOP 1 * FROM PA_T_KPI ORDER BY KPIID DESC";
		return $this->pms->query($query)->row();
 	}
 	function edit_KPI_target($KPIID,$Target)
 	{
 		$query = "UPDATE PA_T_KPI SET TargetAkhirTahun=$Target WHERE KPIID=$KPIID";
 		$this->pms->query($query);
 	}
 	function edit_KPI($KPIID,$KPIGenericID,$SatuanID,$PCFormulaID,$YTDID,$KPI,$Description,$Bobot,$Baseline,$EndDate,$BeginDate)
 	{
 		$query="UPDATE [PMS].[dbo].[PA_T_KPI] SET [KPIGenericID] = $KPIGenericID,[SatuanID] = $SatuanID,[PCFormulaID] = $PCFormulaID,[YTDID] = $YTDID,[KPI] = '$KPI',[Description] = '$Description',[Bobot] = $Bobot,[Baseline] = $Baseline,[EndDate] = '$EndDate',[BeginDate] ='$BeginDate' WHERE KPIID=$KPIID";
 		$this->pms->query($query);
 	}
 	function delimit_KPI($KPIID){
 		$date = date('Y-m-d 00:00:00');
 		$query="UPDATE [PMS].[dbo].[PA_T_KPI]
				   SET [EndDate] = '$date'
				 WHERE KPIID=$KPIID";
 		$this->pms->query($query);
 	}
 	public function remove_KPI($KPIID)
 	{
 		$query="DELETE FROM [PMS].[dbo].[PA_T_KPI]
				 WHERE KPIID=$KPIID";
 		$this->pms->query($query);
 	}
 	//end of KPI
 	function edit_ref_weight($RKKDetailID,$Ref_weight)
 	{
 		$query = "UPDATE [PMS].[dbo].[PA_T_RKKDetail]
   		SET [Ref_weight] = $Ref_weight
 		WHERE RKKDetailID = $RKKDetailID";
 		$this->pms->query($query);
 	}
 	//RKK - Rencana Kinerja Karyawan
 	function count_rkk_subordinate($ChiefPositionID,$status)
 	{
 		$query = "SELECT count(*) as count FROM PA_T_RKK WHERE ChiefPositionID = $ChiefPositionID AND statusFlag = $status";
 		return $this->pms->query($query)->row()->count;
 	}
 	function get_rkk_list($BeginDate,$EndDate,$UserID)
 	{
 		$query = "SELECT * FROM PA_T_RKK WHERE UserID=$UserID AND BeginDate<= '$BeginDate' AND EndDate>='$EndDate'";
 		return $this->pms->query($query)->result();
 	}
 	function get_rkk_row($RKKID){
 		$query ="SELECT * FROM PA_T_RKK WHERE RKKID=$RKKID";
 		return $this->pms->query($query)->row();
 	}
 	function get_rkk_byPositionID_list($PositionID,$BeginDate,$EndDate){
 		$query = "SELECT * FROM PA_T_RKK WHERE PositionID=$PositionID AND BeginDate<= '$BeginDate' AND EndDate>='$EndDate'";
 		return $this->pms->query($query)->result();
 	}
 	function get_rkk_last_byPositionID_row($PositionID,$BeginDate,$EndDate){
		$query = "SELECT TOP 1 * FROM PA_T_RKK WHERE PositionID=$PositionID AND BeginDate<= '$BeginDate' AND EndDate>='$EndDate' ORDER BY RKKID DESC";
 		return $this->pms->query($query)->row();
 	}
 	function get_rkk_byChiefPosition_list($ChiefPositionID,$is_sap,$BeginDate,$EndDate){

 		if ($is_sap) {
	 		$table = 'SAP';
 		} else {
	 		$table = 'nonSAP';
 			
 		}
 		$query = "SELECT TOP 1 OrganizationID as org_id
	 							FROM Core_M_Position_".$table." 
	 							WHERE PositionID = $ChiefPositionID AND
	 								((BeginDate >= '$BeginDate' AND EndDate <= '$EndDate') OR
	 								(EndDate >= '$BeginDate' AND EndDate <= '$EndDate') OR 
	 								(BeginDate >= '$BeginDate' AND BeginDate <= '$BeginDate') OR
	 								(BeginDate <= '$BeginDate' AND EndDate >='$EndDate'))
								ORDER BY BeginDate DESC, EndDate DESC";
 		$org_id = $this->pms->query($query)->row()->org_id;
 		// $query = "SELECT R.*, 
 		// 						U.NIK, 
 		// 						U.Fullname,
 		// 						P.PositionName AS post_name,
 		// 						P.PositionID AS post_id,
 		// 						P.OrganizationID AS org_id 

 		// 					FROM PA_T_RKK R, 
 		// 						Core_M_User U,
 		// 						Core_M_Position_".$table." P
 		// 					WHERE R.UserID = U.UserID AND 
 		// 						R.PositionID = P.PositionID AND
 		// 						(P.OrganizationID = $org_id OR (P.Chief = 2 ))AND
 		// 						R.ChiefPositionID = $ChiefPositionID AND 
 		// 						R.BeginDate >= '$BeginDate' AND 
 		// 						R.EndDate<='$EndDate' AND
	 	// 							((P.BeginDate >= '$BeginDate' AND P.EndDate <= '$EndDate') OR
	 	// 							(P.EndDate >= '$BeginDate' AND P.EndDate <= '$EndDate') OR 
	 	// 							(P.BeginDate >= '$BeginDate' AND P.BeginDate <= '$BeginDate') OR
	 	// 							(P.BeginDate <= '$BeginDate' AND P.EndDate >='$EndDate'))";
		$query = "SELECT R.*, 
 								U.NIK, 
 								U.Fullname,
 								P.PositionName AS post_name,
 								P.PositionID AS post_id,
 								P.OrganizationID AS org_id 

 							FROM PA_T_RKK R, 
 								Core_M_User U,
 								Core_M_Position_".$table." P
 							WHERE R.UserID = U.UserID AND 
 								R.PositionID = P.PositionID AND
 								(P.OrganizationID = $org_id OR (P.Chief = 2 ))AND
 								R.ChiefPositionID = $ChiefPositionID AND 
 								R.BeginDate >= '$BeginDate' AND 
 								R.EndDate<='$EndDate'";
		// echo $query;
 		return $this->pms->query($query)->result();
 	}
 	function get_rkk_byUser_row($UserID,$BeginDate,$EndDate){
 		$query = "SELECT * FROM PA_T_RKK WHERE UserID=$UserID AND BeginDate<= '$BeginDate' AND EndDate>='$EndDate'";
 		return $this->pms->query($query)->row();
 	}
 	function get_rkk_byUserPosition_row($NIK,$PositionID,$BeginDate,$EndDate){
 		$query = "SELECT TOP 1 * FROM PA_T_RKK WHERE NIK=$NIK AND PositionID=$PositionID AND 
 								((BeginDate >= '$BeginDate' AND EndDate <='$EndDate') OR 
 								(EndDate >= '$BeginDate' AND EndDate <= '$EndDate') OR 
 								(BeginDate >= '$BeginDate' AND BeginDate <='$EndDate' ) OR
 								(BeginDate <= '$BeginDate' AND EndDate >= '$EndDate'))";
 		return $this->pms->query($query)->row();
 	}

 	function get_rkk_byUserPosition_nik($NIK,$PositionID,$BeginDate,$EndDate){
 		$query = "SELECT TOP 1 * FROM PA_T_RKK WHERE NIK=$NIK AND PositionID=$PositionID AND(( BeginDate<= '$BeginDate' AND EndDate>='$EndDate') OR  BeginDate<= GETDATE() AND EndDate>='$EndDate')";
 		return $this->pms->query($query)->row();
 	}

 	function get_rkk_byUserPosition_list($UserID,$PositionID,$BeginDate,$EndDate){
 		$query = "SELECT TOP 1 * FROM PA_T_RKK WHERE UserID=$UserID AND PositionID=$PositionID AND(( BeginDate<= '$BeginDate' AND EndDate>='$EndDate') OR  BeginDate<= GETDATE() AND EndDate>='$EndDate')";
 		return $this->pms->query($query)->result();
 	} 

 	function get_rkk_last_row($UserID,$PositionID,$BeginDate,$EndDate){
 		$query = "SELECT TOP 1 * FROM PA_T_RKK WHERE UserID=$UserID AND PositionID=$PositionID AND BeginDate<= '$BeginDate' AND EndDate>='$EndDate' ORDER BY RKKID DESC ";
 		return $this->pms->query($query)->row();
 	}



 	function add_rkk($RKKPositionID,$UserID,$PositionID,$ChiefPositionID,$statusFlag,$isSAP,$Chief_isSAP,$BeginDate,$EndDate){
 		$query = "INSERT INTO [PMS].[dbo].[PA_T_RKK](
					 			[RKKPositionID],
					 			[UserID],
					 			[PositionID],
					 			[ChiefPositionID],
					 			[statusFlag],
					 			[isSAP],
					 			[Chief_isSAP],
					 			[BeginDate],
					 			[EndDate]
					 		) VALUES ( 
					 			$RKKPositionID,
				 				$UserID,
				 				$PositionID,
				 				$ChiefPositionID,
				 				$statusFlag,
				 				$isSAP,
				 				$ChiefPositionID,
				 				'$BeginDate',
				 				'$EndDate'
				 			)";
 		$this->pms->query($query);
 		$query = "SELECT TOP 1 * FROM PA_T_RKK ORDER BY RKKID DESC";
 		return $this->pms->query($query)->row();
 	}
 	function edit_rkk($RKKID,$EndDate){
 		$query="UPDATE PA_T_RKK SET EndDate='$EndDate' WHERE RKKID=$RKKID";
 		$this->pms->query($query);
 	}
 	function edit_rkk_status($RKKID,$statusFlag){
 		$query="UPDATE PA_T_RKK SET statusFlag=$statusFlag WHERE RKKID=$RKKID";
 		$this->pms->query($query);
 	}
 	function delimit_rkk($RKKID,$EndDate=''){
 		if ($EndDate == '') {
 			$EndDate = date('Y-m-d 23:59:59');
 		}

 		$query="UPDATE [PMS].[dbo].[PA_T_RKK] SET [EndDate] ='$EndDate' WHERE RKKID=$RKKID";
 		$this->pms->query($query);

 		$query="UPDATE [PMS].[dbo].[PA_T_RKKDetail] SET [EndDate] ='$EndDate' WHERE RKKID=$RKKID";
 		$this->pms->query($query);

 		$query="UPDATE [PMS].[dbo].[PA_T_KPI] SET [EndDate] ='$EndDate' WHERE KPIID IN (SELECT KPIID FROM PA_T_RKKDetail WHERE RKKID = $RKKID)";
 		$this->pms->query($query);

 		$query="UPDATE [PMS].[dbo].[PA_T_RKKDetailTarget] SET [EndDate] ='$EndDate' WHERE RKKDetailID IN (SELECT RKKDetailID FROM PA_T_RKKDetail WHERE RKKID = $RKKID)";
 		$this->pms->query($query);

 	}
 	function get_rkkDetail_list($RKKID,$BeginDate,$EndDate){
 		$query ="SELECT * FROM PA_V_RKKKPI WHERE RKKID=$RKKID AND RKKDetail_BeginDate<= '$BeginDate' AND RKKDetail_EndDate>='$EndDate'  AND KPI_BeginDate<= '$BeginDate' AND KPI_EndDate>='$EndDate'";
 		
 		return $this->pms->query($query)->result();
 	}

 	function get_rkkDetail_row($RKKDetailID){
 		$query ="SELECT * FROM PA_V_RKKKPI WHERE RKKDetailID=$RKKDetailID";
 		return $this->pms->query($query)->row();
 	}
 	function get_rkkDetail_byRKK_KPI_row($RKKID,$KPIID)
 	{
 		$query = "SELECT TOP 1 * FROM PA_T_RKKDetail WHERE RKKID = $RKKID AND KPIID = $KPIID ORDER BY RKKDetailID DESC";
 		return $this->pms->query($query)->row();
 	}
 	function get_rkkDetail_last_row($RKKID,$BeginDate,$EndDate){
 		$query ="SELECT TOP 1 * FROM PA_T_RKKDetail WHERE RKKID=$RKKID AND BeginDate<= '$BeginDate' AND EndDate>='$EndDate' ORDER BY RKKDetailID DESC";
 		return $this->pms->query($query)->row();
 	}
 	function add_rkkDetail($RKKID,$KPIID,$BeginDate,$EndDate,$ChiefRKKDetailID=0,$ReferenceID=0,$Ref_Weight=0){
 		if($ChiefRKKDetailID==0){
 			$query="INSERT INTO [PA_T_RKKDetail]([RKKID],[KPIID],[BeginDate],[EndDate])VALUES($RKKID,$KPIID,'$BeginDate','$EndDate')";
 		}else{
 			$query="INSERT INTO [PA_T_RKKDetail]([RKKID],[KPIID],[BeginDate],[EndDate],[ChiefRKKDetailID],Ref_weight)VALUES($RKKID,$KPIID,'$BeginDate','$EndDate',$ChiefRKKDetailID,$Ref_Weight)";
 		}
		$this->pms->query($query);
 		$query="SELECT TOP 1 * FROM PA_T_RKKDetail ORDER BY RKKDetailID DESC";
 		return $this->pms->query($query)->row();
 	}
 	function edit_chief_rkkDetail_ref($RKKDetailID,$ReferenceID)
 	{
 		$query = "UPDATE PA_T_RKKDetail SET ReferenceID = $ReferenceID WHERE RKKDetailID = $RKKDetailID";
 		$this->pms->query($query);
 	}
 	function edit_rkkDetail($RKKDetailID,$KPIID,$EndDate,$BeginDate)
 	{
 		$query ="UPDATE PA_T_RKKDetail SET KPIID=$KPIID, EndDate='$EndDate',BeginDate = '$BeginDate' WHERE RKKDetailID = $RKKDetailID";
 		$this->pms->query($query);

 	}
 	function delimit_rkkDetail($RKKDetailID,$EndDate = ''){
 		if ($EndDate == '') {
 			$EndDate = date('Y-m-d 23:59:59');
 		}
 		$query="UPDATE [PMS].[dbo].[PA_T_RKKDetail] SET[EndDate] = '$EndDate' WHERE RKKDetailID=$RKKDetailID";
		$this->pms->query($query);

 	}
 	public function remove_rkkDetail($RKKDetailID)
 	{
 		$query="DELETE FROM [PMS].[dbo].[PA_T_RKKDetailTarget]
 			WHERE RKKDetailID=$RKKDetailID";
		$this->pms->query($query);
 		$query="DELETE FROM [PMS].[dbo].[PA_T_RKKDetail] 
 			WHERE RKKDetailID=$RKKDetailID";
		$this->pms->query($query);
 	}
 	function get_rkkTarget_list($RKKDetailID,$BeginDate,$EndDate){
 		$query ="SELECT * FROM PA_T_RKKDetailTarget WHERE RKKDetailID=$RKKDetailID AND (BeginDate<= '$BeginDate' AND EndDate>='$EndDate')";
 		return $this->pms->query($query)->result();
 	}
 	function get_rkkTarget_row($RKKDetailTargetID){
 		$query ="SELECT * FROM PA_T_RKKDetailTarget WHERE RKKDetailTargetID=$RKKDetailTargetID";
 		return $this->pms->query($query)->row();
 	}
 	function add_rkkTarget($RKKDetailID,$Month,$Target,$BeginDate,$EndDate){
 		$query="INSERT INTO [PMS].[dbo].[PA_T_RKKDetailTarget]([RKKDetailID],[Month],[Target],[BeginDate],[EndDate])VALUES($RKKDetailID
           ,$Month,$Target,'$BeginDate','$EndDate')";
		$this->pms->query($query);
		$query = "SELECT TOP 1 * FROM PA_T_RKKDetailTarget ORDER BY RKKDetailTargetID DESC";
		return $this->pms->query($query)->row();

 	}
 	function edit_rkkTarget($RKKDetailTargetID,$Target,$EndDate){
 		$query="UPDATE [PMS].[dbo].[PA_T_RKKDetailTarget]
   SET [Target]=$Target, [EndDate] = '$EndDate' WHERE RKKDetailTargetID=$RKKDetailTargetID";
		$this->pms->query($query);

 	}
 	function delimit_rkkTarget($RKKDetailTargetID){
 		// $date = date('Y-m-d 00:00:00');

 		// $query="UPDATE [PMS].[dbo].[PA_T_RKKDetailTarget]
   // SET [EndDate] = '$date' WHERE RKKDetailTargetID=$RKKDetailTargetID";
 		$query = "DELETE FROM [PMS].[dbo].[PA_T_RKKDetailTarget] WHERE RKKDetailTargetID=$RKKDetailTargetID";
		$this->pms->query($query);
 	}
 	function delimit_rkkTarget_byRKKDetail($RKKDetailID)
 	{
 		$date = date('Y-m-d 00:00:00');
 		$query="UPDATE [PMS].[dbo].[PA_T_RKKDetailTarget]
   SET [EndDate] = '$date' WHERE RKKDetailID=$RKKDetailID";
		$this->pms->query($query);
 	}
 	// end of RKK
 	//RKK Position / Copy RKK untuk Posisi
 	function get_rkkPosition_list($PositionID,$BeginDate,$EndDate){
 		$query = "SELECT * FROM PA_T_RKKPosition WHERE PositionID=$PositionID AND BeginDate>='$BeginDate' AND EndDate<='$EndDate' ";
 		return $this->pms->query($query)->result();
 	}
 	function get_rkkPosition_row($RKKPositionID){
 		$query = "SELECT * FROM PA_T_RKKPosition WHERE RKKPositionID=$RKKPositionID";
 		return $this->pms->query($query)->row();
 	}
 	function get_rkkPosition_last_row($PositionID,$BeginDate,$EndDate){
 		$query = "SELECT TOP 1 * FROM PA_T_RKKPosition WHERE PositionID=$PositionID  AND BeginDate<='$BeginDate' AND EndDate>='$EndDate' ORDER BY RKKPositionID DESC";
 		return $this->pms->query($query)->row();
 	}
 	function add_rkkPosition($PositionID,$isSAP,$BeginDate,$EndDate){
 		$query="INSERT INTO [PMS].[dbo].[PA_T_RKKPosition] ([PositionID],[isSAP],[BeginDate],[EndDate]) VALUES ($PositionID,$isSAP,'$BeginDate','$EndDate')";
		$this->pms->query($query);
		$query="SELECT TOP 1 * FROM PA_T_RKKPosition ORDER BY RKKPositionID DESC";
		return $this->pms->query($query)->row();
 	}
 	function edit_rkkPosition($RKKPositionID,$EndDate){
 		$query="UPDATE [PMS].[dbo].[PA_T_RKKPosition] SET [EndDate] = '$EndDate' WHERE RKKPositionID = $RKKPositionID";
		$this->pms->query($query);
 	}
 	function delimit_rkkPosition($RKKPositionID){
 		$date = date('Y-m-d 00:00:00');
 		$query="UPDATE [PMS].[dbo].[PA_T_RKKPosition] SET [EndDate] = '$date' WHERE RKKPositionID = $RKKPositionID";
		$this->pms->query($query);
 	}
 	function get_rkkPositionDetail_list($RKKPositionID,$BeginDate,$EndDate){
 		$query ="SELECT * FROM PA_T_RKKPositionDetail WHERE RKKPositionID=$RKKPositionID AND BeginDate<='$BeginDate' AND EndDate>='$EndDate'";
 		return $this->pms->query($query)->result();
 	}
 	function get_rkkPositionDetail_row($RKKPositionDetailID){
 		$query ="SELECT * FROM PA_T_RKKPositionDetail WHERE RKKPositionDetailID=$RKKPositionDetailID";
 		return $this->pms->query($query)->row();
 	}
 	function add_rkkPositionDetail($RKKPositionID,$KPIID,$BeginDate,$EndDate){
 		$query ="INSERT INTO [PA_T_RKKPositionDetail]([RKKPositionID],[KPIID],[BeginDate],[EndDate])VALUES($RKKPositionID,$KPIID,'$BeginDate','$EndDate')";
 		$this->pms->query($query);
 		$query = "SELECT TOP 1 * FROM PA_T_RKKPositionDetail ORDER BY RKKPositionDetailID DESC";
 		return $this->pms->query($query)->row();
 	}
 	function edit_rkkPositionDetail($RKKPositionDetailID,$KPIID,$EndDate){
 		$query="UPDATE [PMS].[dbo].[PA_T_RKKPositionDetail] SET [KPIID]=$KPIID, [EndDate] = '$EndDate' WHERE RKKPositionDetailID=$RKKPositionDetailID";
 		$this->pms->query($query);
 	}
 	function delimit_rkkPositionDetail($KPIID){
 		$date = date('Y-m-d 00:00:00');
 		$query="UPDATE [PMS].[dbo].[PA_T_RKKPositionDetail] SET [EndDate] = '$date' WHERE KPIID=$KPIID";
 		$this->pms->query($query);
 	}
 	// end of RKK Position
 	// view self RKK 
 	function get_Perspective_list($RKKID,$BeginDate,$EndDate){
 		$query="SELECT Distinct(PerspectiveID),Perspective FROM PA_V_RKKKPI WHERE RKKID=$RKKID AND RKKDetail_BeginDate>= '$BeginDate' AND RKKDetail_EndDate<='$EndDate'  AND KPI_BeginDate>= '$BeginDate' AND KPI_EndDate<='$EndDate'";
 		return $this->pms->query($query)->result();
 	}
 	function get_SO_list2($RKKID,$PerspectiveID,$BeginDate,$EndDate){
 		$query="SELECT * FROM PA_T_SasaranStrategis WHERE SasaranStrategisID IN (SELECT SasaranStrategisID FROM PA_V_RKKKPI WHERE RKKID=$RKKID AND PerspectiveID=$PerspectiveID AND RKKDetail_BeginDate>= '$BeginDate' AND RKKDetail_EndDate<='$EndDate'  AND KPI_BeginDate>= '$BeginDate' AND KPI_EndDate<='$EndDate')";
 		return $this->pms->query($query)->result();
 	}
 	function get_SO_list($RKKID,$PerspectiveID,$BeginDate,$EndDate,$isChief=0){
 		$query = "SELECT * 
 										FROM PA_T_RKK 
 										WHERE RKKID=$RKKID AND 
 											((BeginDate >='$BeginDate' AND EndDate <='$EndDate'))";
 		$RKK = $this->pms->query($query)->row();
 		if ($RKK->isSAP){
 			$table = 'SAP';
 		}else{
 			$table = 'nonSAP';
 		}
 		$query = "SELECT * FROM Core_M_Position_$table WHERE PositionID=$RKK->PositionID AND ((BeginDate >= '$BeginDate' OR EndDate <= '$EndDate') OR (EndDate >= '$BeginDate' AND EndDate <= '$EndDate') OR (BeginDate >= '$BeginDate' AND BeginDate <= '$EndDate') OR (BeginDate <= '$BeginDate' OR EndDate >= '$EndDate') )";
 		$Position = $this->pms->query($query)->row();
 		if($isChief==1){
 			$query="SELECT * FROM PA_T_SasaranStrategis WHERE SasaranStrategisID IN (SELECT SasaranStrategisID FROM PA_V_RKKKPI WHERE RKKID=$RKKID AND PerspectiveID=$PerspectiveID AND RKKDetail_BeginDate>= '$BeginDate' AND RKKDetail_EndDate<='$EndDate'  AND KPI_BeginDate>= '$BeginDate' AND KPI_EndDate<='$EndDate') OR (PerspectiveID=$PerspectiveID AND OrganizationID = $Position->OrganizationID)";
 		}else{
 			
 			$query="SELECT * FROM PA_T_SasaranStrategis WHERE SasaranStrategisID IN (SELECT SasaranStrategisID FROM PA_V_RKKKPI WHERE RKKID=$RKKID AND PerspectiveID=$PerspectiveID AND RKKDetail_BeginDate>= '$BeginDate' AND RKKDetail_EndDate<='$EndDate'  AND KPI_BeginDate>= '$BeginDate' AND KPI_EndDate<='$EndDate') OR (PerspectiveID=$PerspectiveID AND OrganizationID = $Position->OrganizationID)";

 		}

 		return $this->pms->query($query)->result();
 	}
 	//end of view self RKK 
 	function count_rkk_weight($RKKID)
 	{
 		$query = "SELECT SUM(Bobot) as Total FROM [PA_V_RKKKPI] WHERE RKKID=$RKKID AND RKKDetail_EndDate>=GETDATE()";
 		$result =  $this->pms->query($query)->row()->Total;
 		if ($result == '') {
 			return 0;
 		} else {
 			return $result;
 		}
 	}
 	function count_kpi_num($RKKID)
 	{
 		$query = "SELECT count(*) as Total FROM [PA_V_RKKKPI] WHERE RKKID=$RKKID AND RKKDetail_EndDate >= GETDATE() AND KPI_EndDate >= GETDATE()";
 		$result = $this->pms->query($query)->row()->Total;
 		if ($result == '') {
 			return 0;
 		} else {
 			return $result;
 		}
 	}

 	function check_totalRKK($RKKID)
 	{
 		$query="SELECT COUNT(*) as count_value FROM PA_T_RKK WHERE RKKID='$RKKID'";
 		if($this->pms->query($query)->row()->count_value>0){
			return true;
		}else{
			return false;
		}
 	}

 	function change_chief_rkk_detail($old,$new)
 	{
 		$query ="UPDATE PA_T_RKKDetail SET ChiefRKKDetailID=$new WHERE ChiefRKKDetailID=$old";
 		$this->pms->query($query);
 	}

 	public function link_kpi($RKKDetailID,$ChiefRKKDetailID)
 	{
 		$query = "UPDATE PA_T_RKKDetail SET ChiefRKKDetailID=$ChiefRKKDetailID WHERE RKKDetailID = $RKKDetailID";
 		$this->pms->query($query);
 	}
 	public function unlink_kpi($RKKDetailID)
 	{
 		$query = "UPDATE PA_T_RKKDetail SET ChiefRKKDetailID=NULL WHERE RKKDetailID = $RKKDetailID";
 		$this->pms->query($query);
 	}

 	public function copy_rkk($source_user,$source_post,$target_user,$target_post,$start,$end)
 	{
 		$query = "EXEC copy_rkk $source_user, $source_post, $target_user, $target_post, '$start', '$end'";
 		$this->pms->query($query);

 	}

 	public function delete_rkk($rkk_id=0)
 	{
 		$query = "DELETE FROM PA_T_RKKDetail WHERE RKKID = $rkk_id";
 		$this->pms->query($query);
 	}

 	public function count_rkkPost($PositionID=0,$isSAP=1,$BeginDate='',$EndDate='')
 	{
 		$query = "SELECT COUNT(*) AS val
 							FROM PA_T_RKKPosition
 							WHERE PositionID = 0 AND
 								isSAP = $isSAP AND
 								BeginDate >= '$BeginDate' AND
 								EndDate <= '$EndDate'";
 		return $this->pms->query($query)->row()->val;

 	}

 	public function get_rkkPost_row($PositionID=0,$isSAP=1,$BeginDate='',$EndDate='')
 	{
 		$query = "SELECT TOP 1 *
 							FROM PA_T_RKKPosition
 							WHERE PositionID = 0 AND
 								isSAP = $isSAP AND
 								BeginDate >= '$BeginDate' AND
 								EndDate <= '$EndDate'
 							ORDER BY RKKPositionID DESC";
 		return $this->pms->query($query)->row()->val;
 							
 	}

 	public function count_valid_rkk($UserID=0,$PositionID=0,$ChiefPositionID=0,$BeginDate='',$EndDate='',$status='all')
 	{
 		$query = "SELECT COUNT(*) as val
 							FROM PA_T_RKK 
 							WHERE UserID = $UserID AND
 								PositionID = $PositionID AND
 								ChiefPositionID = $ChiefPositionID AND
 								((BeginDate >= '$BeginDate' AND EndDate <= '$EndDate') OR 
 								(EndDate >= '$BeginDate' AND EndDate <= '$EndDate') OR 
 								(BeginDate >= '$BeginDate' AND BeginDate <= '$EndDate') OR 
 								(BeginDate <= '$BeginDate' AND EndDate >= '$EndDate') )";

 		switch ($status) {
 			case 'open':
 				$query .= " AND statusFlag IN (0,2)";
 				break;
 			case 'lock':
 				$query .= " AND statusFlag IN (1,3)";
 				break;
 			default:
 				# code...
 				break;
 		}
 		return $this->pms->query($query)->row()->val;
 	}

 	public function get_valid_rkk_row($UserID=0,$PositionID=0,$ChiefPositionID=0,$BeginDate='',$EndDate='',$status='all')
 	{
 		$query = "SELECT TOP 1 *
 							FROM PA_T_RKK 
 							WHERE UserID = $UserID AND
 								PositionID = $PositionID AND
 								ChiefPositionID = $ChiefPositionID AND
 								((BeginDate >= '$BeginDate' AND EndDate <= '$EndDate') OR 
 								(EndDate >= '$BeginDate' AND EndDate <= '$EndDate') OR 
 								(BeginDate >= '$BeginDate' AND BeginDate <= '$EndDate') OR 
 								(BeginDate <= '$BeginDate' AND EndDate >= '$EndDate') )";
		switch ($status) {
 			case 'open':
 				$query .= " AND statusFlag IN (0,2)";
 				break;
 			case 'lock':
 				$query .= " AND statusFlag IN (1,3)";
 				break;
 			default:
 				# code...
 				break;
 		}
		$query .= "ORDER BY RKKID DESC";
 		return $this->pms->query($query)->row();
 	}
}
?>