<?php
class Org_model extends Model
{
	function __construct()
	{
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
		- "remove_"	: menghapus record yang ada
		Suffix / Akhiran
		-	"_list"		: hasil balikkan berupa banyak record
		- "_row"		: hasil balikkan berupa satu record

 	*/

	function get_organization_list_all($begin_date='', $end_date='', $is_sap)
	{
		
		if($begin_date=='' or $end_date=='')
		{
			$query = "SELECT * FROM Core_M_Organization_";
			if($is_sap)
			{
				$query.="SAP";
			}
			else
			{
				$query.="nonSAP";
			}
		}
		else
		{
			$query = "SELECT * FROM Core_M_Organization_";
			if($is_sap)
			{
				$query.="SAP";
			}
			else
			{
				$query.="nonSAP";
			}
			$query.=" WHERE (([BeginDate] <= '$begin_date' and [EndDate] >= '$end_date')OR([BeginDate] <= GETDATE() and [EndDate] >= '$end_date'))";

			//echo $query;
		}

		return $this->pms->query($query)->result();	
	}

	function get_Organization_listSAP($BeginDate='',$EndDate='')
	{
		$query="SELECT * 
						FROM Core_M_Organization_SAP";
		if($BeginDate=='' or $EndDate=='')
		{
			$query = "SELECT * FROM Core_M_Organization_SAP";
		}
		else
		{
			$query = "SELECT * FROM Core_M_Organization_SAP WHERE (([BeginDate] <= '$BeginDate' and [EndDate] >= '$EndDate')OR([BeginDate] <= GETDATE() and [EndDate] >= '$EndDate'))";
		}
		return $this->pms->query($query)->result();
	}

	function get_Organization_listnonSAP($BeginDate='',$EndDate='')
	{
		$query="SELECT * FROM Core_M_Organization_nonSAP";
		if($BeginDate=='' or $EndDate=='')
		{
			$query = "SELECT * FROM Core_M_Organization_nonSAP";
		}
		else
		{
			$query = "SELECT * FROM Core_M_Organization_nonSAP WHERE (([BeginDate] <= '$BeginDate' and [EndDate] >= '$EndDate')OR([BeginDate] <= GETDATE() and [EndDate] >= '$EndDate'))";
		}
		return $this->pms->query($query)->result();
	}

	function get_Organization_list($OrganizationParent='',$isSAP,$BeginDate='',$EndDate='')
	{
		$query="SELECT * FROM Core_M_Organization_";
		if($isSAP)
		{
			$query.="SAP";
		}
		else
		{
			$query.="nonSAP";
		}
		$query.=" WHERE OrganizationParent=$OrganizationParent";
		if ($BeginDate!='' and $EndDate !='')
		{
			$query .= " AND (([BeginDate] <= '$BeginDate' and [EndDate] >= '$EndDate')OR([BeginDate] <= GETDATE() and [EndDate] >= '$EndDate'))";
		}
		else
		{
			$query .= " AND ([BeginDate] <= GETDATE() and [EndDate] >= GETDATE())";
		}
		/*if($isSAP)
		{
			$query.=" AND (OrganizationParent<>0 OR OrganizationID=50002147)";
		}*/
		
		return $this->pms->query($query)->result();
	}
	function count_Organization($OrganizationParent='',$isSAP,$BeginDate='',$EndDate='')
	{
		$query="SELECT count(*) as count_value FROM Core_M_Organization_";
		if($isSAP){
			$query.="SAP";
		}else{
			$query.="nonSAP";
		}
		$query .=" WHERE OrganizationParent=$OrganizationParent";
		if ($BeginDate!='' and $EndDate !='')
		{
			$query .= " AND (([BeginDate] <= '$BeginDate' and [EndDate] >= '$EndDate')OR([BeginDate] <= GETDATE() and [EndDate] >= '$EndDate'))";
		}
		return $this->pms->query($query)->row()->count_value;
	}

	function get_Organization_Parent_row($ParentID,$isSAP, $BeginDate, $EndDate)
	{
		$query ="SELECT * FROM Core_M_Organization_";
		if($isSAP)
		{
			$query.="SAP";
		}else{
			$query.="nonSAP";
		}
		$query.=" WHERE OrganizationParent = $ParentID and BeginDate='$BeginDate' and EndDate='$EndDate'";
		return $this->pms->query($query)->row();
	}
	function get_Organization_row($OrganizationID,$isSAP=0, $BeginDate='', $EndDate='')
	{
		$query="SELECT * FROM Core_M_Organization_";
		if($isSAP)
		{
			$query.= "SAP";
		}
		else
		{
			$query.= "nonSAP";
		}
		$query .=" WHERE OrganizationID=$OrganizationID ";
		if ($BeginDate!='' && $EndDate!='')
		{
			$query .=" and ((BeginDate='$BeginDate' and EndDate='$EndDate') OR (BeginDate=GETDATE() and EndDate='$EndDate')) ";
		}
		return $this->pms->query($query)->row();
	}

	function get_Organization_start_date($OrganizationID, $is_sap)
	{
		$query="SELECT * FROM Core_M_Organization_";
		if($is_sap)
		{
			$query.= "SAP";
		}
		else
		{
			$query.= "nonSAP";
		}
		$query .=" WHERE OrganizationID=$OrganizationID AND  EndDate >=GETDATE() ";
		return $this->pms->query($query)->row();
	}

	function add_Organization($isSAP=0,$OrganizationName,$OrganizationParent=NULL,$BeginDate,$EndDate='9999-12-31')
	{
		if($isSAP)
		{
			$query="INSERT INTO [PMS].[dbo].[Core_M_Organization_SAP] ([ObjectType] ,[OrganizationName] ,[OrganizationParent] ,[BeginDate] ,[EndDate]) VALUES ('O','$OrganizationName',$OrganizationParent ,'$BeginDate','$EndDate')"; 
		}
		else
		{
			$query="INSERT INTO [PMS].[dbo].[Core_M_Organization_nonSAP] ([ObjectType] ,[OrganizationName] ,[OrganizationParent] ,[BeginDate] ,[EndDate]) VALUES ('O','$OrganizationName',$OrganizationParent ,'$BeginDate','$EndDate')"; 
		}

		$this->pms->query($query);
	}
	function edit_Organization($OrganizationID,$isSAP=0,$OrganizationName,$EndDate)
	{
		if($isSAP)
		{
			$query="UPDATE [PMS].[dbo].[Core_M_Organization_SAP]
						   SET [OrganizationName] = '$OrganizationName'
						      ,[EndDate] = '$EndDate'
						 WHERE OrganizationID=$OrganizationID";
		}
		else
		{
			$query="UPDATE [PMS].[dbo].[Core_M_Organization_nonSAP]
   SET [OrganizationName] = '$OrganizationName'
      ,[EndDate] = '$EndDate'
 WHERE OrganizationID=$OrganizationID";
		}

		$this->pms->query($query);
	}
	function delete_Organization($OrganizationID,$isSAP=0){
		if($isSAP){
			$query="DELETE FROM Core_M_Organization_SAP WHERE OrganizationID=$OrganizationID";
		}else{
			$query="DELETE FROM Core_M_Organization_nonSAP WHERE OrganizationID=$OrganizationID";
		}
		$this->pms->query($query);
	}
	function check_Organization_isUsed($OrganizationID){
		$query="SELECT COUNT(*) as count_value FROM Core_M_Position_nonSAP WHERE OrganizationID = $OrganizationID";
		$post_nonSAP = $this->pms->query($query)->row()->count_value;
		$query="SELECT COUNT(*) as count_value FROM Core_M_Position_SAP WHERE OrganizationID = $OrganizationID";
		$post_SAP = $this->pms->query($query)->row()->count_value;
		$query="SELECT COUNT(*) as count_value FROM PA_T_SasaranStrategis WHERE OrganizationID = $OrganizationID";
		$SS = $this->pms->query($query)->row()->count_value;
		if($post_nonSAP>0 or $post_SAP> 0 or $SS>0){
			return true;
		}else{
			return false;
		}
	}

	function get_Position_list($OrganizationID,$isSAP,$BeginDate='',$EndDate=''){
		$query="SELECT DISTINCT * FROM Core_M_Position_";
		if($isSAP){
			$query.="SAP";
		}else{
			$query.="nonSAP";
		}
		$query .=" WHERE OrganizationID=$OrganizationID";
		if ($BeginDate!='' and $EndDate !=''){
			$query .= " AND (([BeginDate] <= '$BeginDate' and [EndDate] >= '$EndDate') OR ([BeginDate] <= GETDATE() and [EndDate] >= '$EndDate'))";
		}
		$query .=" ORDER BY Chief DESC";
		return $this->pms->query($query)->result();
	}

	function get_Position_listAssignment($OrganizationID,$isSAP,$BeginDate='',$EndDate='',$PositionID){
		$query="SELECT * FROM Core_M_Position_";
		if($OrganizationID >= '50002147'){
			$query.="SAP";
		}else{
			$query.="nonSAP";
		}
		$query .=" WHERE OrganizationID=$OrganizationID";
		if($PositionID!='')
		{
			$query .=" AND PositionID=$PositionID";
		}
		if ($BeginDate!='' and $EndDate !=''){
			$query .= " AND (([BeginDate] <= '$BeginDate' and [EndDate] >= '$EndDate')OR([BeginDate] <= GETDATE() and [EndDate] >= '$EndDate'))";
		}
		$query .=" ORDER BY Chief DESC";
		return $this->pms->query($query)->result();
	}


	function get_position_assignment($OrganizationID,$currentPost='',$isSAP,$BeginDate,$EndDate){
		if($isSAP){
			$table="SAP";
		}else{
			$table="nonSAP";
		}
		if ($currentPost!=''){
			$subquery=" AND PositionID <> $currentPost";
			$subquery2=" AND PositionID=$currentPost";
		}else{
			$subquery='';
			$subquery2='';
		}
		$query ="SELECT * FROM Core_M_Position_$table WHERE PositionID NOT IN (SELECT PositionID FROM Core_M_Holder_nonSAP WHERE (([BeginDate] <= '$BeginDate' and [EndDate] >= '$EndDate')OR([BeginDate] <= GETDATE() and [EndDate] >= '$EndDate')) $subquery) 
				AND OrganizationID=$OrganizationID $subquery2";

		return $this->pms->query($query)->result();
	}

	function get_position_available($OrganizationID,$currentPost='',$isSAP,$BeginDate,$EndDate){
		if($isSAP){
			$table="SAP";
		}else{
			$table="nonSAP";
		}
		if ($currentPost!=''){
			$subquery=" AND PositionID <> $currentPost";
		}else{
			$subquery='';
		}
		$query ="SELECT * FROM Core_M_Position_$table WHERE PositionID NOT IN (SELECT PositionID FROM Core_M_Holder_$table WHERE (([BeginDate] <= '$BeginDate' and [EndDate] > '$EndDate') OR ([BeginDate] <= GETDATE() and [EndDate] > '$EndDate')) $subquery) AND OrganizationID=$OrganizationID";

		return $this->pms->query($query)->result();
	}

	function get_position_now($OrganizationID,$currentPosition='',$isSAP,$BeginDate,$EndDate){
		if($isSAP){
			$table="SAP";
		}else{
			$table="nonSAP";
		}
		if ($currentPosition!=''){
			$subquery=" AND PositionID = $currentPosition";
		}else{
			$subquery='';
		}
		$query ="SELECT * FROM Core_M_Position_$table WHERE [BeginDate] <= '$BeginDate' and [EndDate] > '$EndDate' AND OrganizationID=$OrganizationID $subquery";

		return $this->pms->query($query)->result();
	}
	

	function get_Position_row($PositionID,$isSAP,$BeginDate='',$EndDate=''){
		if($isSAP){
			$table="SAP";
		}else{
			$table="nonSAP";
		}
		if ($BeginDate == '') {
			$BeginDate = date('Y-m-d');
		}

		if ($EndDate == '') {
			$EndDate = date('Y-m-d H:i:s');
		}
		$query="SELECT P.*, 
							O.OrganizationName,
							O.OrganizationParent,
							O.OrganizationName AS org_name,
							O.OrganizationParent AS org_parent
						FROM Core_M_Position_$table P, 
							Core_M_Organization_$table O 
						WHERE O.OrganizationID = P.OrganizationID AND 
							P.PositionID = $PositionID AND 
							((P.BeginDate<='$BeginDate' AND P.EndDate>='$EndDate') OR 
							(P.EndDate>='$BeginDate' AND P.EndDate<='$EndDate') OR 
							(P.BeginDate>='$BeginDate' AND P.BeginDate<='$EndDate') OR 
							(P.BeginDate <= '$BeginDate' AND P.EndDate >= '$EndDate'))";
		//echo $query;
		return $this->pms->query($query)->row();
	}


	function get_count_position_row($PositionID,$isSAP,$BeginDate='',$EndDate=''){
		if($isSAP){
			$table="SAP";
		}else{
			$table="nonSAP";
		}
		if ($BeginDate == '') {
			$BeginDate = date('Y-m-d');
		}

		if ($EndDate == '') {
			$EndDate = date('Y-m-d H:i:s');
		}
		$query="SELECT count(*) as total_position
						FROM Core_M_Position_$table P, 
							Core_M_Organization_$table O 
						WHERE O.OrganizationID = P.OrganizationID AND 
							P.PositionID = $PositionID AND 
							((P.BeginDate<='$BeginDate' AND P.EndDate>='$EndDate') OR 
							(P.EndDate>='$BeginDate' AND P.EndDate<='$EndDate') OR 
							(P.BeginDate>='$BeginDate' AND P.BeginDate<='$EndDate') OR 
							(P.BeginDate <= '$BeginDate' AND P.EndDate >= '$EndDate'))";
		//echo $query;
		return $this->pms->query($query)->row();
	}

	function get_Position_row_old($PositionID,$isSAP,$BeginDate='',$EndDate=''){
		if($isSAP){
			$table="SAP";
		}else{
			$table="nonSAP";
		}
		if ($BeginDate == '') {
			$BeginDate = date('Y-m-d');
		}

		if ($EndDate == '') {
			$EndDate = date('Y-m-d H:i:s');
		}
		$query="SELECT top 1 P.*, 
							O.OrganizationName,
							O.OrganizationParent,
							O.OrganizationName AS org_name,
							O.OrganizationParent AS org_parent
						FROM Core_M_Position_$table P, 
							Core_M_Organization_$table O 
						WHERE O.OrganizationID = P.OrganizationID AND 
							P.PositionID = $PositionID ORDER BY EndDate desc";
		//echo $query;
		return $this->pms->query($query)->row();
	}

	function add_Position($OrganizationID,$isSAP,$PositionName,$Chief,$PositionGroup,$BeginDate,$EndDate='9999-12-31'){
		if($isSAP){
			$query="INSERT INTO [PMS].[dbo].[Core_M_Position_SAP] ([ObjectType] ,[PositionName] ,[OrganizationID] ,[Chief] ,[PositionGroup] ,[BeginDate] ,[EndDate]) VALUES ('S','$PositionName',$OrganizationID ,'$Chief','$PositionGroup','$BeginDate','$EndDate')"; 
		}else{
			$query="INSERT INTO [PMS].[dbo].[Core_M_Position_nonSAP] ([ObjectType] ,[PositionName] ,[OrganizationID] ,[Chief] ,[PositionGroup] ,[BeginDate] ,[EndDate]) VALUES ('S','$PositionName',$OrganizationID ,'$Chief','$PositionGroup','$BeginDate','$EndDate')"; 
		}

		$this->pms->query($query);
	}
	function get_Position_chief_row($isSAP,$NIK){
		if($isSAP){
			$table='SAP';
		}else{
			$table='nonSAP';
		}
		$query="SELECT TOP 1 * FROM Core_V_Holder_$table WHERE NIK='$NIK' and Chief=1 AND Holder_BeginDate<=getdate() and Holder_EndDate >=GETDATE() and Org_BeginDate<=getdate() and Org_EndDate >=GETDATE() and Post_BeginDate<=getdate() and Post_EndDate >=GETDATE() ORDER BY HolderID Desc";
		$result=$this->pms->query($query)->row();
		if(count($result)){
			return $result;
		}else{
			$query="SELECT TOP 1 * FROM Core_V_Holder_$table WHERE NIK='$NIK' and Chief=2 AND Holder_BeginDate<=getdate() and Holder_EndDate >=GETDATE() and Org_BeginDate<=getdate() and Org_EndDate >=GETDATE() and Post_BeginDate<=getdate() and Post_EndDate >=GETDATE() ORDER BY HolderID Desc";
			return $this->pms->query($query)->row();
		}
	}
	function get_Chief_Position_row($isSAP,$PositionID){
		if($isSAP){
			$table='SAP';
		}else{
			$table='nonSAP';
		}
		$query = "SELECT TOP 1 * FROM Core_M_Position_$table WHERE PositionID=$PositionID AND BeginDate<=GETDATE() AND EndDate>=GETDATE() ORDER BY PositionID DESC";
		$self = $this->pms->query($query)->row();
		if($self->Chief==2){
			$query = "SELECT TOP 1 * FROM Core_M_Organization_$table WHERE BeginDate<=GETDATE() AND EndDate>=GETDATE() AND OrganizationID=".$self->OrganizationID ." ORDER BY OrganizationID DESC";
			$orgParentID = $this->pms->query($query)->row()->OrganizationParent; 
			$query ="SELECT TOP 1 U.UserID, U.NIK, U.Fullname,U.isSAP ,P.* FROM Core_M_Position_$table P, Core_M_Holder_$table H, Core_M_User U WHERE P.OrganizationID=$orgParentID AND P.BeginDate<=GETDATE() AND P.EndDate>=GETDATE() AND P.Chief=2 AND H.PositionID=P.PositionID AND U.NIK=H.NIK";
		}elseif($self->Chief==1){
			$query ="SELECT TOP 1 U.UserID, U.NIK, U.Fullname,U.isSAP ,P.* FROM Core_M_Position_$table P, Core_M_Holder_$table H, Core_M_User U WHERE P.OrganizationID=". $self->OrganizationID." AND P.BeginDate<=GETDATE() AND P.EndDate>=GETDATE() AND P.Chief=2 AND H.PositionID=P.PositionID AND U.NIK=H.NIK";
		}else{
			$query ="SELECT TOP 1 U.UserID, U.NIK, U.Fullname,U.isSAP ,P.* FROM Core_M_Position_$table P, Core_M_Holder_$table H, Core_M_User U WHERE P.OrganizationID=". $self->OrganizationID." AND P.BeginDate<=GETDATE() AND P.EndDate>=GETDATE() AND P.Chief=2 AND H.PositionID=P.PositionID AND U.NIK=H.NIK";
			$result=$this->pms->query($query)->row();
			if(count($result)){
				return $result;
			}else{
				$query ="SELECT TOP 1 U.UserID, U.NIK, U.Fullname,U.isSAP ,P.* FROM Core_M_Position_$table P, Core_M_Holder_$table H, Core_M_User U WHERE P.OrganizationID=". $self->OrganizationID." AND P.BeginDate<=GETDATE() AND P.EndDate>=GETDATE() AND P.Chief=2 AND H.PositionID=P.PositionID AND U.NIK=H.NIK";
			}

		}

		return $this->pms->query($query)->row();

	}
	function edit_Position($PositionID,$isSAP,$PositionName,$Chief,$PositionGroup,$EndDate='9999-12-31'){
		if($isSAP){
			$query="UPDATE [PMS].[dbo].[Core_M_Position_SAP] SET [PositionName] = '$PositionName',[Chief] = '$Chief',[PositionGroup] = '$PositionGroup',[EndDate] = '$EndDate'WHERE PositionID=$PositionID"; }else{$query="UPDATE [PMS].[dbo].[Core_M_Position_nonSAP] SET [PositionName] = '$PositionName',[Chief] = '$Chief',[PositionGroup] = '$PositionGroup',[EndDate] = '$EndDate'WHERE PositionID=$PositionID"; }
		
		$this->pms->query($query);

	}
	function delete_Position($PositionID,$isSAP){
		if($isSAP){
			$query = "DELETE FROM Core_M_Position_SAP WHERE PositionID = $PositionID";
		}else{
			$query = "DELETE FROM Core_M_Position_nonSAP WHERE PositionID = $PositionID";
		}
		$this->pms->query($query);
	}
	function check_Position_isUsed($PositionID){
		$query="SELECT COUNT(*) as count_value FROM Core_M_Holder_nonSAP WHERE PositionID = $PositionID";
		$Holder_nonSAP = $this->pms->query($query)->row()->count_value;

		$query="SELECT COUNT(*) as count_value FROM Core_M_Holder_SAP WHERE PositionID = $PositionID";
		$Holder_SAP = $this->pms->query($query)->row()->count_value;
		$query="SELECT COUNT(*) as count_value FROM PA_T_RKK WHERE PositionID = $PositionID";
		$RKK = $this->pms->query($query)->row()->count_value;
		$query="SELECT COUNT(*) as count_value FROM PA_T_RKKPosition WHERE PositionID = $PositionID";
		$RKK_position = $this->pms->query($query)->row()->count_value;
		if($Holder_nonSAP>0 or $Holder_SAP> 0 or $RKK>0 or $RKK_position>0){
			return true;
		}else{
			return false;
		}

	}
	function check_PositionHolder($isSAP,$Position){
		$today = date('Y-m-d');
		$query = "SELECT Count(*) FROM Core_M_Holder_";
		if($isSAP){
			$query .="SAP";
		}else{
			$query .="nonSAP";
		}
		$query .= " WHERE PositionID=$Position AND BeginDate <='$today' AND EndDate>='$today'";
		if($this->pms->query($query)->num_rows>0){
			return true;
		}else{
			return false;
		}
	}
	function add_Holder($isSAP,$NIK,$Position,$BeginDate,$EndDate,$isMain=0){
		if($isSAP){
			$table="SAP";
		}else{
			$table="nonSAP";
		}
		if($isMain==''){
			$isMain=0;
		}
		$query="INSERT INTO [PMS].[dbo].[Core_M_Holder_$table] ([NIK] ,[PositionID] ,[isMain] ,[BeginDate] ,[EndDate]) VALUES ('$NIK',$Position ,$isMain ,'$BeginDate','$EndDate')"; $this->pms->query($query);
	}
	function edit_Holder($isSAP,$HolderID,$EndDate,$isMain=0){
		if($isSAP){
			$table="SAP";
		}else{
			$table="nonSAP";
		}
		if($isMain==''){
			$isMain=0;
		}
		$query = "UPDATE [PMS].[dbo].[Core_M_Holder_$table] SET [EndDate] = '$EndDate',[isMain]=$isMain WHERE HolderID = $HolderID"; 
		$this->pms->query($query);
	}
	function get_chief_row($isSAP,$OrganizationID){
		if($isSAP){
			$table="SAP";
		}else{
			$table="nonSAP";
		}
		$query="SELECT * 
						FROM Core_V_User_$table 
						WHERE OrganizationID = $OrganizationID and 
							Chief=2";

		return $this->pms->query($query)->row();
	}
	function get_directSubordinate_list($isSAP,$PositionID,$Begindate,$Enddate)
	{
		$query = "exec [dbo].[DirectSubordinateException] $PositionID, $isSAP, '$Begindate', '$Enddate'";
		$prev = '0';
		$temp = $this->pms->query($query)->result();
		$array = array();
		foreach ($temp as $row) {
			if ($row->HolderID != $prev) {
				$array[] = $row;
			}
			$prev = $row->HolderID;
		}
		return $array;
	}

	function get_directSubordinate_list_array($isSAP,$PositionID,$Begindate,$Enddate)
	{
		$query = "exec [dbo].[DirectSubordinateException] $PositionID, $isSAP, '$Begindate', '$Enddate'";
		return $this->pms->query($query)->result_array();
	}

	function get_subordinate_list($isSAP,$OrganizationID)
	{
		if($isSAP)
		{
			$table="SAP";
		}
		else
		{
			$table="nonSAP";
		}
		$query="SELECT * 
						FROM Core_V_User_$table 
						WHERE OrganizationID = $OrganizationID and 
							Chief<>2 ORDER BY Chief AND PositionGroup AND NIK";
		return $this->pms->query($query)->result();
	}

	function get_subordinate_exception_list($ChiefPositionID,$Chief_isSAP,$BeginDate,$EndDate)
	{
		$query ="EXEC SubordinateException $ChiefPositionID,$Chief_isSAP,'$BeginDate','$EndDate'";
		return $this->pms->query($query)->result();
	}
	function get_allSubordinate_list($OrgID_List, $BeginDate,$EndDate,$isSAP=0){
		if($isSAP){
			$table='SAP';
		}else{
			$table='nonSAP';
		}
		$query = "SELECT * FROM Core_V_User_$table WHERE OrganizationID IN (";
			$flag=0;
		foreach ($OrgID_List as $key => $value) {
			if($flag==0){
				$query .= $value;
				$flag=1;
			}else{
				$query .= ", ".$value;
			}
		}
		$query .= ")";
		if ($BeginDate!='' And $EndDate!=''){
			$query.=" AND BeginDate<='$BeginDate' And EndDate>='$EndDate'";
		}
		
		return $this->pms->query($query)->result();
	}
	function get_allAssignment_list($isSAP,$OrgID_List, $BeginDate,$EndDate){
		if($isSAP){
			$table="SAP";
		}else{
			$table="nonSAP";
		}
		$query="SELECT U.UserID,U.Fullname,A.*, P.PositionName, P.Chief, P.PositionGroup, O.OrganizationName,O.OrganizationParent FROM Core_M_User U, PA_T_Assignment A, Core_M_Position_$table P, Core_M_Organization_$table O  WHERE ((U.BeginDate<='$BeginDate' AND U.EndDate>='$EndDate') OR (U.BeginDate<=GETDATE() AND U.EndDate>='$EndDate')) AND ((A.BeginDate<='$BeginDate' AND A.EndDate>='$EndDate') OR (A.BeginDate<=GETDATE() AND A.EndDate>='$EndDate')) AND ((P.BeginDate<='$BeginDate' AND P.EndDate>='$EndDate') OR (P.BeginDate<=GETDATE() AND P.EndDate>='$EndDate')) AND ((O.BeginDate<='$BeginDate' AND O.EndDate>='$EndDate') OR (O.BeginDate<=GETDATE() AND O.EndDate>='$EndDate')) AND A.AssignmentStatus=1 AND A.PositionID=P.PositionID AND P.OrganizationID=O.OrganizationID AND A.NIK=U.NIK AND O.OrganizationID IN(";
		$flag=0;
		foreach ($OrgID_List as $key => $value) {
			if($flag==0){
				$query .= $value;
				$flag=1;
			}else{
				$query .= ", ".$value;
			}
		}
		$query .= ")";	
		return $this->pms->query($query)->result();
	}
	function get_Assignment_row($AssignmentID){
		$query="SELECT U.UserID,U.Fullname,A.* FROM Core_M_User U, PA_T_Assignment A  WHERE A.AssignmentID=$AssignmentID AND A.NIK=U.NIK";
		return $this->pms->query($query)->row();
	}

	function get_superior_row($PositionID,$isSAP,$key_date='')
	{
		if ($isSAP) {
			$table = 'SAP';
		}	else {
			$table = 'nonSAP';
		}
		if ($key_date=='')
		{
			$key_date = date('Y-m-d');
		}
		#cari chief dari exception;
		$query = "SELECT * FROM [Core_M_Exception_Reporting_Structure] WHERE PositionID = $PositionID AND BeginDate <= '$key_date' AND EndDate>= '$key_date')";
		$exc = $this->pms->query($query);

		if (count($exc)){
			$query = "SELECT TOP 1 * FROM Core_V_Holder_$table WHERE Chief = 2 AND PositionID = $exc->PositionID AND ( Post_BeginDate <= '$key_date' AND Post_EndDate>= '$key_date') AND (Holder_BeginDate <= '$key_date' AND Holder_EndDate>= '$key_date') AND (Org_BeginDate <= '$key_date' AND Org_EndDate>= '$key_date')";
		}else{
			$query = "SELECT TOP 1 * FROM Core_V_Holder_$table WHERE PositionID = $PositionID AND ( Post_BeginDate <= '$key_date' AND Post_EndDate>= '$key_date') AND (Holder_BeginDate <= '$key_date' AND Holder_EndDate>= '$key_date') AND (Org_BeginDate <= '$key_date' AND Org_EndDate>= '$key_date')";
			$temp_result = $this->pms->query($query)->row();
			if ($temp_result->Chief ==2 ){
				#cari chief dari organisasi yang ada diatasnya
				$query = "SELECT TOP 1 * FROM Core_V_Holder_$table WHERE Chief = 2 AND OrganizationID = $temp_result->OrganizationParent AND ( Post_BeginDate <= '$key_date' AND Post_EndDate>= '$key_date') AND (Holder_BeginDate <= '$key_date' AND Holder_EndDate>= '$key_date') AND (Org_BeginDate <= '$key_date' AND Org_EndDate>= '$key_date')";
			}else{
				#cari chief dari organisasi yang sama
				$query = "SELECT TOP 1 * FROM Core_V_Holder_$table WHERE Chief = 2 AND OrganizationID = $temp_result->OrganizationID AND ( Post_BeginDate <= '$key_date' AND Post_EndDate>= '$key_date') AND (Holder_BeginDate <= '$key_date' AND Holder_EndDate>= '$key_date') AND (Org_BeginDate <= '$key_date' AND Org_EndDate>= '$key_date')";
			}
		}
		return $this->pms->query($query)->row();

	}
// model Exception Reporting Structure //
	function get_ExceptionReportingStructure_List($start_date='',$end_date=''){
		if($start_date=='' or $end_date==''){
			$query = "SELECT A.ExceptionReportingStructureID, A.ChiefPositionID, A.Chief_isSAP, A.BeginDate, A.EndDate, A.PositionID, A.isSAP FROM Core_M_Exception_Reporting_Structure A GROUP BY A.ChiefPositionID, A.Chief_isSAP, A.PositionID, A.isSAP, A.ExceptionReportingStructureID, A.BeginDate, A.EndDate";
		}else{
			$query = "SELECT A.ExceptionReportingStructureID, A.ChiefPositionID, A.Chief_isSAP, A.BeginDate, A.EndDate, A.PositionID, A.isSAP FROM Core_M_Exception_Reporting_Structure A WHERE ((A.[BeginDate] <= '$start_date' and A.[EndDate] >= '$end_date') OR (A.[BeginDate] <= GETDATE() and A.[EndDate] >= '$end_date')) GROUP BY A.ChiefPositionID, A.Chief_isSAP, A.PositionID, A.isSAP, A.ExceptionReportingStructureID, A.BeginDate, A.EndDate";
		}
		return $this->pms->query($query)->result();
	}
	function get_Exception_Subordinate_list($ChiefPositionID,$BeginDate,$EndDate){
		$query = "SELECT * FROM Core_M_Exception_Reporting_Structure E WHERE E.ChiefPositionID=$ChiefPositionID AND ((E.BeginDate<='$BeginDate' AND E.EndDate>='$EndDate') OR (E.BeginDate<=GETDATE() AND E.EndDate>='$EndDate'))";
		return $this->pms->query($query)->result();
	}
	function get_Exception_ReportingChief_row($PositionID,$BeginDate,$EndDate){
		$query = "SELECT * FROM Core_M_Exception_Reporting_Structure E WHERE E.PositionID=$PositionID AND ((E.BeginDate<='$BeginDate' AND E.EndDate>='$EndDate') OR (E.BeginDate<=GETDATE() AND E.EndDate>='$EndDate'))";
		return $this->pms->query($query)->row();
	}
	function get_ExceptionReportingStructure_row($ExceptionReportingStructureID){
		$query = "SELECT * FROM Core_M_Exception_Reporting_Structure WHERE ExceptionReportingStructureID=$ExceptionReportingStructureID";
		return $this->pms->query($query)->row();
	}
	function add_ExceptionReportingStructure($ChiefPositionID,$Chief, $PositionID, $isSAP, $start_date,$end_date){
		$query = "INSERT INTO [Core_M_Exception_Reporting_Structure]([ChiefPositionID],[Chief_isSAP],[PositionID],[isSAP],[BeginDate],[EndDate])
		VALUES('$ChiefPositionID','$Chief','$PositionID','$isSAP', '$start_date','$end_date')";
		$this->pms->query($query);
	}

	function edit_ExceptionReportingStructure($ExceptionReportingStructureID, $ChiefPositionID,$Chief, $PositionID, $isSAP,$start_date,$end_date){
		$query = "UPDATE [Core_M_Exception_Reporting_Structure] SET [ChiefPositionID] = '$ChiefPositionID', [Chief_isSAP]='$Chief', [PositionID]='$PositionID', [isSAP]='$isSAP', [EndDate] = '$end_date' WHERE ExceptionReportingStructureID=$ExceptionReportingStructureID";
		$this->pms->query($query);
	}

	function check_ExceptionReportingStructure($PositionID,$isSAP, $BeginDate, $EndDate){
		$query = "SELECT COUNT(*) as count_value FROM Core_M_Exception_Reporting_Structure WHERE PositionID=$PositionID AND isSAP=$isSAP AND BeginDate=$BeginDate AND EndDate >= $EndDate";
		if($this->pms->query($query)->row()->count_value>0){
			return true;
		}else{
			return false;
		}
	}

	public function get_org_root_row($org_id='',$isSAP = 1,$start='',$end='')
	{
		if ($isSAP == 1) {
			$table = "Core_M_Organization_SAP";
		} else {
			$table = "Core_M_Organization_nonSAP";

		}
		$query = "SELECT TOP 1 * 
							FROM $table 
							WHERE OrganizationID = $org_id AND
								((BeginDate >= '$start' AND EndDate <= '$end') OR 
								(EndDate >= '$start' AND EndDate <= '$end') OR 
								(BeginDate >= '$start' AND BeginDate <= '$end') OR
								(BeginDate <= '$start' AND EndDate >= '$end'))
							ORDER BY EndDate DESC, BeginDate DESC";
		$temp = $this->db->query($query)->row();
		
		if ($isSAP == 1) {
			if ($temp->OrganizationParent == 50002147) {
				return $temp;
			} else{
				return $this->get_org_root_row($temp->OrganizationParent,$isSAP,$start,$end);
			}
		} else {
			if ($temp->OrganizationParent == 0) {
				return $temp;
			} else {
				return $this->get_org_root_row($temp->OrganizationParent,$isSAP,$start,$end);
			}
		}
		
	}
	// ending model Exception Reporting Structure //
}
