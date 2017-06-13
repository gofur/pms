<?php
class Assignment_model extends Model{
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
		- "remove_"	: menghapus record yang ada
		Suffix / Akhiran
		-	"_list"		: hasil balikkan berupa banyak record
		- "_row"		: hasil balikkan berupa satu record

 	*/
 	function get_Assignment_list($AssignmentID,$BeginDate,$EndDate){
 		$query = "SELECT * FROM PA_T_Assignment WHERE AssignmentID=$AssignmentID AND BeginDate<='$BeginDate' AND EndDate>='$EndDate' ";
 		return $this->pms->query($query)->result();
 	}

 	function get_Assignment_rowAssignment($AssignmentID){
 		$query = "SELECT * FROM PA_T_Assignment WHERE AssignmentID=$AssignmentID";
 		return $this->pms->query($query)->row();
 	}

 	function get_Assignment_rownonSAP($AssignmentID){
 		$query = "SELECT * FROM PA_T_Assignment A inner join Core_M_Position_nonSAP B on A.PositionID=B.PositionID
inner join Core_M_Organization_nonSAP C on C.OrganizationID=B.OrganizationID WHERE AssignmentID=$AssignmentID";
 		return $this->pms->query($query)->row();
 	}

 

 	function get_Assignment_rowSAP($AssignmentID){
 		$query = "SELECT * FROM PA_T_Assignment A inner join Core_M_Position_SAP B on A.PositionID=B.PositionID
inner join Core_M_Organization_SAP C on C.OrganizationID=B.OrganizationID WHERE AssignmentID=$AssignmentID";
 		return $this->pms->query($query)->row();
 	}


 	
 	function check_AssignmentbyNIK_isUsed($NIK,$PositionID)
 	{
 		$query="SELECT COUNT(*) as count_value FROM PA_T_Assignment WHERE NIK='$NIK' and PositionID='$PositionID'";
 		if($this->pms->query($query)->row()->count_value>0){
			return true;
		}else{
			return false;
		}
 	}

 	function add_Assignment($NIK,$PositionID,$Bobot,$Description,$startdate,$enddate){
		$query = "INSERT INTO [PA_T_Assignment]([NIK],[PositionID],[Bobot],[Description],[AssignmentStatus],[BeginDate],[EndDate])VALUES('$NIK','$PositionID','$Bobot','$Description', 'true', '$startdate', '$enddate')";
		$this->pms->query($query);
	}

	function add_AssignmentEdit($NIK,$PositionID,$Bobot,$Description,$startdate,$enddate){
		$query = "INSERT INTO [PA_T_Assignment]([NIK],[PositionID],[Bobot],[Description],[AssignmentStatus],[BeginDate],[EndDate])VALUES('$NIK','$PositionID','$Bobot','$Description', 'false', '$startdate', '$enddate')";
		$this->pms->query($query);
	}
 	

 	function edit_Assignment($AssignmentID, $NIK,$PositionID, $Bobot, $Description,$start_date,$end_date){
		$query = "UPDATE [PA_T_Assignment] SET [NIK] = '$NIK', [PositionID]='$PositionID', [Bobot]='$Bobot', [Description]='$Description', [EndDate] = '$end_date', [BeginDate]='$start_date' WHERE AssignmentID=$AssignmentID";
		$this->pms->query($query);
	}

	function edit_AssignmentWithoutPositionID($AssignmentID, $NIK, $Bobot, $Description,$start_date,$end_date){
		$query = "UPDATE [PA_T_Assignment] SET [NIK] = '$NIK', [Bobot]='$Bobot', [Description]='$Description', [EndDate] = '$end_date', [BeginDate]='$start_date' WHERE AssignmentID=$AssignmentID";
		$this->pms->query($query);
	}

	function get_Assigment($NIK,$isSAP,$BeginDate='',$EndDate='')
	{
		if($isSAP){
			$table ='SAP';
		}else{
			$table ="nonSAP";
		}

		$query = "SELECT A.NIK, A.PositionID,B.PositionName, C.OrganizationName,C.OrganizationID, A.Bobot, A.Description,A.BeginDate, A.EndDate, A.AssignmentStatus, A.AssignmentID from PA_T_Assignment A 
			inner join Core_M_Position_$table B on A.PositionID=B.PositionID 
			inner join Core_M_Organization_$table C on C.OrganizationID=B.OrganizationID
			where A.NIK='$NIK' and A.AssignmentStatus!=0";
		if($BeginDate!='' and $EndDate !=''){
			$query .=" AND (A.BeginDate<='$BeginDate'  Or A.BeginDate is null  And A.EndDate>='$EndDate' or A.EndDate is NULL)";
		}
		return $this->pms->query($query)->result();
	}

	function get_AssigmentnonSAPtoSAP($NIK, $BeginDate='',$EndDate='')
	{
		$table ='SAP';
		$query = "SELECT A.NIK, A.PositionID,B.PositionName, C.OrganizationName,C.OrganizationID, A.Bobot, A.Description,A.BeginDate, A.EndDate, A.AssignmentStatus, A.AssignmentID from PA_T_Assignment A 
			inner join Core_M_Position_$table B on A.PositionID=B.PositionID 
			inner join Core_M_Organization_$table C on C.OrganizationID=B.OrganizationID
			where A.NIK='$NIK' and A.AssignmentStatus!=0";
		if($BeginDate!='' and $EndDate !=''){
			//$query .=" AND (B.BeginDate<='$BeginDate'  Or B.BeginDate is null  And B.EndDate>='$EndDate' or B.EndDate is NULL)";
			$query .=" AND ((B.BeginDate<='$BeginDate' and B.EndDate>='$EndDate') or (B.BeginDate <= GETDATE() and B.EndDate >='$EndDate'))";
		}
		return $this->pms->query($query)->result();
	}


 	function get_Assignment_listAllbyHolder($NIK, $isSAP,$BeginDate='',$EndDate='')
 	{
		if($isSAP){
			$table ='SAP';
		}else{
			$table ="nonSAP";
		}

		$query = "SELECT A.isMain, C.OrganizationName, C.OrganizationID, B.PositionID,B.PositionName, D.Bobot,D.[Description], A.BeginDate, A.EndDate,D.[AssignmentStatus],D.[AssignmentID] from
					Core_M_Holder_$table A left outer join Core_M_Position_$table B on A.PositionID=B.PositionID
					left outer join Core_M_Organization_$table C on C.OrganizationID=B.OrganizationID
					left outer join PA_T_Assignment D on D.PositionID=A.PositionID where A.NIK='$NIK'";
		if($BeginDate!='' and $EndDate !=''){
			$query .=" AND ((B.BeginDate<='$BeginDate' and B.EndDate>='$EndDate') or (B.BeginDate <= GETDATE() and B.EndDate >='$EndDate'))";
		}
		return $this->pms->query($query)->result();
	}



 	//get objective row
 	function get_Objective_row($ObjectiveID){
		$query = "SELECT * FROM PA_T_SasaranStrategis WHERE SasaranStrategisID=$ObjectiveID ";
		return $this->pms->query($query)->row();
	}

	// get objective list // 	
	function get_Objective_list($OrganizationID,$PerspectiveID)
	{
		$query="SELECT * FROM PA_T_SasaranStrategis WHERE OrganizationID=$OrganizationID and PerspectiveID=$PerspectiveID";
		return $this->pms->query($query)->result();
	}

	//insert to PA_T_SasaranStrategis
	function add_Objective($OrganizationID,$PerspectiveID,$Objective,$Description){
		$query = "INSERT INTO [PA_T_SasaranStrategis]([OrganizationID],[PerspectiveID],[SasaranStrategis],[Description])VALUES('$OrganizationID','$PerspectiveID','$Objective','$Description')";
		$this->pms->query($query);
	}

	//update to PA_T_SasaranStrategis
	function edit_Objective($ObjectiveID,$OrganizationID,$PerspectiveID,$Objective,$Description){
		$query = "UPDATE [PA_T_SasaranStrategis] SET [OrganizationID] = '$OrganizationID', [PerspectiveID] = '$PerspectiveID',[SasaranStrategis]='$Objective', [Description]='$Description' WHERE SasaranStrategisID=$ObjectiveID";
		$this->pms->query($query);
	}

	//remove to PA_T_SasaranStrategis
	function remove_Objective($ObjectiveID){
		$query = "DELETE FROM PA_T_SasaranStrategis WHERE SasaranStrategisID=$ObjectiveID";
		$this->pms->query($query);
	}
}
?>