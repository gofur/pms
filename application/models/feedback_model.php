<?php
class Feedback_model extends Model{
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
	
	
	function get_feedback_listbyRKKID($RKKID)
	{
		$query="SELECT * FROM PA_T_Feedback a inner join PA_T_FeedbackDetail b on a.FeedbackID=b.FeedbackID
				inner join PA_T_FeedbackPoint c on c.FeedbackDetailID=b.FeedbackDetailID
				where a.RKKID=$RKKID";
		return $this->pms->query($query)->result();
	}

	function get_HeaderFeedback_list($RKKID){
		$query="SELECT * FROM PA_T_Feedback WHERE RKKID=$RKKID";
		return $this->pms->query($query)->result();
	}

	function get_FeedbackAspect_list(){
		$query = "SELECT * FROM PA_M_FeedbackAspect";
		return $this->pms->query($query)->result();
	}

	function count_Feedback($RKKID)
	{
		$query =
		"SELECT 
			COUNT(*) as Total
		FROM 
			PA_T_Feedback H, 
			PA_T_FeedbackDetail D, 
			PA_T_FeedbackPoint T 
		WHERE 
			H.FeedbackID=D.FeedbackID AND 
			D.FeedbackDetailID=T.FeedbackDetailID AND H.RKKID = $RKKID;";
		return $this->pms->query($query)->row()->Total;
	}

	function add_Feedback($RKKID){
 		$query = "INSERT INTO [PA_T_Feedback]([RKKID],[Status])VALUES($RKKID,1)";
 		$this->pms->query($query);
 		$query = "SELECT TOP 1 * FROM PA_T_Feedback ORDER BY FeedbackID DESC";
 		return $this->pms->query($query)->row();
 	}

 	function add_Feedback_Detail($FeedbackID,$FeedbackAspectID){
 		$query = "INSERT INTO [PA_T_FeedbackDetail]([FeedbackID],[FeedbackAspectID])VALUES($FeedbackID,$FeedbackAspectID)";
 		$this->pms->query($query);
 		$query = "SELECT TOP 1 * FROM PA_T_FeedbackDetail ORDER BY FeedbackDetailID DESC";
 		return $this->pms->query($query)->row();
 	}

 	function add_Feedback_Point($FeedbackDetailID,$FeedbackPoint,$Evidence,$Cause,$AltSolution,$DueDate,$ActualDate,$Notes,$chkList)
 	{
 		$query = "INSERT INTO [PA_T_FeedbackPoint]([FeedbackDetailID],[TglCoaching],[FeedbackPoint],[Evidence],[Cause],[AlternativeSolution],[DueDate],[ActualDate],[Notes],[CheckList])VALUES($FeedbackDetailID,'".date("Y-m-d H:i:s")."','$FeedbackPoint','$Evidence','$Cause','$AltSolution','$DueDate','$ActualDate','$Notes',$chkList)";
 		$this->pms->query($query);
 		$query = "SELECT TOP 1 * FROM PA_T_FeedbackPoint ORDER BY FeedbackPointID DESC";
 		return $this->pms->query($query)->row();
 	} 	

 	function get_Header_rowbyRKKID($RKKID){
		$query="SELECT * FROM PA_T_Feedback WHERE RKKID=$RKKID ORDER BY FeedbackID DESC";
		return $this->pms->query($query)->row();
	}

	function get_Header_byRKKID_row($RKKID){
		$query="SELECT TOP 1 * FROM PA_T_Feedback WHERE RKKID=$RKKID AND Status=1 ORDER BY FeedbackID ASC";
		return $this->pms->query($query)->row();
	}

	function get_Detail_list($FeedbackID){
		$query="SELECT * FROM PA_T_FeedbackDetail A INNER JOIN PA_M_FeedbackAspect B ON 
			A.FeedbackAspectID=B.FeedbackAspectID WHERE FeedbackID=$FeedbackID";
		return $this->pms->query($query)->result();		
	}

	function get_Header_row($FeedbackID){
		$query="SELECT * FROM PA_T_Feedback WHERE FeedbackID=$FeedbackID";
		return $this->pms->query($query)->row();
	}

	function get_Feedback_AspectbyID($FeedbackAspectID)
 	{
 		$query = "SELECT * FROM PA_M_FeedbackAspect where FeedbackAspectID=$FeedbackAspectID";
		return $this->pms->query($query)->row();
 	}


 	function get_Feedback_AspectList($FeedbackDetailID)
 	{
 		$query = "SELECT * FROM PA_T_FeedbackDetail A INNER JOIN PA_M_FeedbackAspect B 
					ON A.FeedbackAspectID=B.FeedbackAspectID INNER JOIN PA_T_FeedbackPoint C 
					on C.FeedbackDetailID=A.FeedbackDetailID 
					INNER JOIN PA_T_Feedback D on D.FeedbackID=A.FeedbackID
				WHERE A.FeedbackDetailID=$FeedbackDetailID";
		return $this->pms->query($query)->result();
 	}

	function get_Feedback_Aspect($FeedbackDetailID)
 	{
 		$query = "SELECT A.*, B.FeedbackAspect FROM PA_T_FeedbackDetail A INNER JOIN 
PA_M_FeedbackAspect B ON A.FeedbackAspectID=B.FeedbackAspectID WHERE FeedbackDetailID=$FeedbackDetailID";
		return $this->pms->query($query)->result();
 	}


 	function get_Detail_row($FeedbackDetailID){
		$query="SELECT * FROM PA_T_FeedbackDetail WHERE FeedbackDetailID=$FeedbackDetailID";
		return $this->pms->query($query)->row();	
	}

	function get_DP_FeedbackPoint_row($FeedbackPointID){
		$query="SELECT * FROM PA_T_FeedbackPoint A inner join PA_T_FeedbackDetail B on A.FeedbackDetailID=B.FeedbackDetailID inner join PA_M_FeedbackAspect C on C.FeedbackAspectID=B.FeedbackAspectID WHERE FeedbackPointID=$FeedbackPointID";
		return $this->pms->query($query)->row();
	}

	function edit_DP_FeedbackPoint($FeedbackPointID,$FeedbackPoint,$Evidence,$Cause,$AltSolution,$DueDate,$ActualDate,$Notes,$chkList, $StatusPoint){
		$query="UPDATE PA_T_FeedbackPoint SET Evidence ='$Evidence', Cause='$Cause',AlternativeSolution='$AltSolution', Notes=" . ($Notes ? "'$Notes'": 'NULL') . ", DueDate='$DueDate', ActualDate=". ($ActualDate ? "'$ActualDate'": 'NULL') . ", CheckList='$chkList', StatusPoint=" . ($StatusPoint ? "'$StatusPoint'": 'NULL') . " WHERE FeedbackPointID=$FeedbackPointID";
		$this->pms->query($query);
	}

	function editStatusFlagPoint($FeedbackPointID,$FlagStatus)
	{
		$query="UPDATE PA_T_FeedbackPoint set StatusPoint=$FlagStatus where FeedbackPointID=$FeedbackPointID";
		$this->pms->query($query);
	}

	function remove_feedback_point($feedback_point_id)
	{
		$query="DELETE PA_T_FeedbackPoint where FeedbackPointID=$feedback_point_id";
		$this->pms->query($query);	
	}
}
?>