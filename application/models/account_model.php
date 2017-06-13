<?php
class Account_model extends Model{
	function __construct(){
		parent::__construct();
		$this->portal = $this->load->database('portal', TRUE);
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
	function get_portal_user_list()
	{
		$query = "SELECT 
								l.userLogin as NIK,m.Nama ,
								convert(varchar(16),decryptbypassphrase(l.userLogin,password)) as [password], 
								l.email , 
								m.Telp as mobile
							FROM [PORTAL].[dbo].tr_login l, [PORTAL].[dbo].ms_niktelp m 
							where m.NIK= l.userLogin";
		return $this->portal->query($query)->result();
	}
	
	function get_temp_user_list()
	{
		$query = "SELECT * FROM temp_user";
 		return $this->pms->query($query)->result();
	} 
	function delete_temp_user($nik)
	{
		$query = "DELETE FROM temp_user WHERE nik = '$nik'";
		$this->pms->query($query);
	}

 	function get_Role_list($BeginDate='',$EndDate='',$RoleID=1){
 		$query='SELECT * FROM Core_M_Role WHERE RoleID >='.$RoleID;
 		if($BeginDate!='' and $EndDate!=''){
 			$query.=" AND BeginDate<='$BeginDate' And EndDate>='$EndDate'";
 		}
 		return $this->pms->query($query)->result();
 	}
	function get_User_list($BeginDate='',$EndDate=''){
		$query = "SELECT * FROM Core_M_User";
		if ($BeginDate!='' and $EndDate !=''){
			$query .= " WHERE BeginDate<='$BeginDate' And EndDate>='$EndDate'";
		}
		return $this->pms->query($query)->result();
	}
	function get_User_row($UserID){
		
		$query = "SELECT u.*,r.Role,r.RoleID FROM Core_M_User u, Core_M_Role r WHERE u.UserID = $UserID and r.RoleID=u.RoleID";
		return $this->pms->query($query)->row();
	}

	function get_User_nik($NIK){
		
		$query = "SELECT u.*,r.Role,r.RoleID FROM Core_M_User u, Core_M_Role r WHERE u.NIK = '$NIK' and r.RoleID=u.RoleID";
		
		return $this->pms->query($query)->row();
	}
	function get_UserUnit_list($OrgID_List, $BeginDate,$EndDate,$isSAP=0){
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
	function get_User_byNIK($NIK){
		$query = "SELECT TOP 1 * FROM Core_M_User WHERE NIK = '$NIK' ORDER BY UserID DESC";
		return $this->pms->query($query)->row();
	}
	function get_User_byUserID($UserID){
		$query = "SELECT * FROM Core_M_User WHERE UserID = '$UserID' ORDER BY UserID DESC";
		return $this->pms->query($query)->row();
	}
	function get_User_byRole_list($RoleID){
		$query = "SELECT  u.*,
								hr.hr_name 
							FROM Core_M_User u
							INNER JOIN hr_m_hr hr ON u.PersAdmin = hr.persadmin_id 
							WHERE RoleID = $RoleID 
							ORDER BY UserID DESC";
		return $this->pms->query($query)->result();
	}

	function get_User_byHr_list($PersAdmin){
		$query = "SELECT * FROM Core_M_User WHERE PersAdmin = '$PersAdmin' ORDER BY NIK ASC";
		return $this->pms->query($query)->result();
	}

	function get_User_byRole_row($RoleID){
		$query = "SELECT Top 1 * FROM Core_M_User WHERE RoleID = $RoleID";
		return $this->pms->query($query)->row();
	}
	function add_User($NIK,$Fullname,$Photo='',$Email,$Mobile,$RoleID,$statusFlag,$isSAP=0,$BeginDate,$EndDate='9999-12-31',$password = ''){
		if($password=='')
		{
			$md5_pass=md5('123456');
			
		}
		else
		{
			$md5_pass = md5($password);
		}
		$query="INSERT INTO [PMS].[dbo].[Core_M_User]
           ([NIK]
           ,[Fullname]
           ,[Password]
           ,[Photo]
           ,[Email]
           ,[Mobile]
           ,[RoleID]
           ,[statusFlag]
           ,[isSAP]
           ,[BeginDate]
           ,[EndDate])
     VALUES
           ('$NIK'
           ,'$Fullname'
           ,'$md5_pass'
           ,'$Photo'
           ,'$Email'
           ,'$Mobile'
           ,$RoleID
           ,$statusFlag
           ,$isSAP
           ,'$BeginDate'
           ,'$EndDate')";
		$this->pms->query($query);
	}
	function edit_User($UserID,$Fullname,$Photo,$Email,$Mobile,$RoleID,$EndDate,$password=''){
		if($password=='')
		{
			$md5_pass=md5('abc123');
		}
		else
		{
			$md5_pass = md5($password);
		}
		$query="UPDATE [PMS].[dbo].[Core_M_User]
   SET [Fullname] = '$Fullname'
      ,[Photo] = '$Photo'
      ,[Email] = '$Email'
      ,[Mobile] = '$Mobile'
      ,[RoleID] = $RoleID
      ,[EndDate] = '$EndDate'
      ,[Password] = '$md5_pass'
 WHERE UserID=$UserID";
 		$this->pms->query($query);
	}
	public function edit_user_role($UserID,$RoleID)
	{
		$query="UPDATE [PMS].[dbo].[Core_M_User]
		   SET [RoleID] = $RoleID
		 WHERE UserID=$UserID";
 		$this->pms->query($query);

	}
	function change_password($nik,$Password){
		$md5_pass=md5($Password);
		$query="UPDATE [PMS].[dbo].[Core_M_User]
		   SET [Password] = '$md5_pass'
		 WHERE NIK='$nik'";
		 //AND isSAP=0";
 		$this->pms->query($query);
	}
	function check_NIK_isUsed($NIK){
		$query = "SELECT count(*) as count_value FROM Core_M_User WHERE NIK='$NIK'";
		if($this->pms->query($query)->row()->count_value>0){
			return true;
		}else{
			return false;
		}
	}

	function get_Holder_listIsMain($NIK,$isSAP,$BeginDate='',$EndDate=''){
		if($isSAP){
			$table ='SAP';
		}else{
			$table ="nonSAP";
		}
		$query = "SELECT * FROM Core_V_Holder_$table WHERE NIK = '$NIK' and isMain=1";
		if($BeginDate!='' and $EndDate !=''){
			$query .=" AND ((Holder_BeginDate<='$BeginDate' And Holder_EndDate>='$EndDate') OR (Holder_BeginDate<=GETDATE() And Holder_EndDate>='$EndDate'))";
			$query .=" AND ((Post_BeginDate<='$BeginDate' And Post_EndDate>='$EndDate') OR (Post_BeginDate<=GETDATE() And Post_EndDate>='$EndDate'))";
			$query .=" AND ((Org_BeginDate<='$BeginDate' And Org_EndDate>='$EndDate') OR (Org_BeginDate<=GETDATE() And Org_EndDate>='$EndDate'))";
		}
		return $this->pms->query($query)->result();
	}

	function get_Holder_list($NIK,$isSAP,$BeginDate='',$EndDate=''){
		if($isSAP){
			$table ='SAP';
		}else{
			$table ="nonSAP";
		}
		$query = "SELECT * FROM Core_V_Holder_$table WHERE NIK = '$NIK'";

		if ($BeginDate != '' AND $EndDate != '') {
			$query .= " AND ( (Holder_BeginDate >= '$BeginDate' AND Holder_EndDate <= '$EndDate') OR (Holder_EndDate >= '$BeginDate' AND Holder_EndDate <= '$EndDate') OR (Holder_BeginDate >= '$BeginDate' AND Holder_BeginDate <= '$EndDate') OR (Holder_BeginDate <= '$BeginDate' AND Holder_EndDate >= '$EndDate'))";
			$query .= " AND ( (Post_BeginDate >= '$BeginDate' AND Post_EndDate <= '$EndDate') OR (Post_EndDate >= '$BeginDate' AND Post_EndDate <= '$EndDate') OR (Post_BeginDate >= '$BeginDate' AND Post_BeginDate <= '$EndDate') OR (Post_BeginDate <= '$BeginDate' AND Post_EndDate >= '$EndDate'))";

			$query .= " AND ( (Org_BeginDate >= '$BeginDate' AND Org_EndDate <= '$EndDate') OR (Org_EndDate >= '$BeginDate' AND Org_EndDate <= '$EndDate') OR (Org_BeginDate >= '$BeginDate' AND Org_BeginDate <= '$EndDate') OR (Org_BeginDate <= '$BeginDate' AND Org_EndDate >= '$EndDate'))";
			$query .= "Order by Holder_EndDate desc";
		}
		return $this->pms->query($query)->result();
	}

	function get_Holder_row_byNIK($NIK,$isSAP,$BeginDate='',$EndDate='',$is_main=2){
		if($isSAP){
			$table ='SAP';
		}else{
			$table ="nonSAP";
		}
		$query = "SELECT * FROM Core_V_Holder_$table WHERE NIK = '$NIK'";

		if ($is_main == 0 OR $is_main==1) {
			$query .= " AND isMain = $is_main ";
		}

		if ($BeginDate != '' AND $EndDate != '') {
			$query .= " AND ( (Holder_BeginDate >= '$BeginDate' AND Holder_EndDate <= '$EndDate') OR (Holder_EndDate >= '$BeginDate' AND Holder_EndDate <= '$EndDate') OR (Holder_BeginDate >= '$BeginDate' AND Holder_BeginDate <= '$EndDate') OR (Holder_BeginDate <= '$BeginDate' AND Holder_EndDate >= '$EndDate'))";
			$query .= " AND ( (Post_BeginDate >= '$BeginDate' AND Post_EndDate <= '$EndDate') OR (Post_EndDate >= '$BeginDate' AND Post_EndDate <= '$EndDate') OR (Post_BeginDate >= '$BeginDate' AND Post_BeginDate <= '$EndDate') OR (Post_BeginDate <= '$BeginDate' AND Post_EndDate >= '$EndDate'))";

			$query .= " AND ( (Org_BeginDate >= '$BeginDate' AND Org_EndDate <= '$EndDate') OR (Org_EndDate >= '$BeginDate' AND Org_EndDate <= '$EndDate') OR (Org_BeginDate >= '$BeginDate' AND Org_BeginDate <= '$EndDate') OR (Org_BeginDate <= '$BeginDate' AND Org_EndDate >= '$EndDate'))";
			$query .= "Order by Holder_EndDate desc";
		}
		//echo $query;
		return $this->pms->query($query)->row();
	}



	function count_Holder_list($NIK,$isSAP,$BeginDate='',$EndDate=''){
		if($isSAP){
			$table ='SAP';
		}else{
			$table ="nonSAP";
		}
		$query = "SELECT count(*) FROM Core_V_Holder_$table WHERE NIK = '$NIK'";

		if ($BeginDate != '' AND $EndDate != '') {
			$query .= " AND ( (Holder_BeginDate >= '$BeginDate' AND Holder_EndDate <= '$EndDate') OR (Holder_EndDate >= '$BeginDate' AND Holder_EndDate <= '$EndDate') OR (Holder_BeginDate >= '$BeginDate' AND Holder_BeginDate <= '$EndDate') OR (Holder_BeginDate <= '$BeginDate' AND Holder_EndDate >= '$EndDate'))";
			$query .= " AND ( (Post_BeginDate >= '$BeginDate' AND Post_EndDate <= '$EndDate') OR (Post_EndDate >= '$BeginDate' AND Post_EndDate <= '$EndDate') OR (Post_BeginDate >= '$BeginDate' AND Post_BeginDate <= '$EndDate') OR (Post_BeginDate <= '$BeginDate' AND Post_EndDate >= '$EndDate'))";

			$query .= " AND ( (Org_BeginDate >= '$BeginDate' AND Org_EndDate <= '$EndDate') OR (Org_EndDate >= '$BeginDate' AND Org_EndDate <= '$EndDate') OR (Org_BeginDate >= '$BeginDate' AND Org_BeginDate <= '$EndDate') OR (Org_BeginDate <= '$BeginDate' AND Org_EndDate >= '$EndDate'))";
		}
		return $this->pms->query($query)->result();
	}

	function get_Assignment_list($NIK,$isSAP,$BeginDate='',$EndDate=''){
		if($isSAP){
			$table ='SAP';
		}else{
			$table ="nonSAP";
		}
		$query = "SELECT * FROM Core_V_Add_Assignment_$table WHERE NIK = '$NIK'";
		if($BeginDate!='' and $EndDate !=''){
			$query .=" AND ((Expr2<='$BeginDate' And Expr3>='$EndDate') OR (Expr2<=GETDATE() And Expr3>='$EndDate'))";
		}
		return $this->pms->query($query)->result();
	}

	function get_Holder_byNIK($NIK,$isSAP){
		if($isSAP){
			$table='SAP';
		}else{
			$table='nonSAP';
		}
		$query = "SELECT TOP 1 * 
							FROM Core_V_Holder_$table 
							WHERE NIK = '$NIK'  
							ORDER BY Holder_EndDate DESC,
								Post_EndDate DESC,
								Org_EndDate DESC";
		// echo $query;
		return $this->pms->query($query)->row();
	}
	function get_Holder_row($HolderID,$isSAP,$BeginDate='',$EndDate=''){
		if($isSAP){
			$table ='SAP';
		}else{
			$table ="nonSAP";
		}
		$query = "SELECT * FROM Core_V_Holder_$table WHERE HolderID = $HolderID ";
		if ($BeginDate != '' AND $EndDate !='') {
			$query .= " AND ( (Holder_BeginDate >= '$BeginDate' AND Holder_EndDate <= '$EndDate') OR (Holder_EndDate >= '$BeginDate' AND Holder_EndDate <= '$EndDate') OR (Holder_BeginDate >= '$BeginDate' AND Holder_BeginDate <= '$EndDate') OR (Holder_BeginDate <= '$BeginDate' AND Holder_EndDate >= '$EndDate'))";
			$query .= " AND ( (Post_BeginDate >= '$BeginDate' AND Post_EndDate <= '$EndDate') OR (Post_EndDate >= '$BeginDate' AND Post_EndDate <= '$EndDate') OR (Post_BeginDate >= '$BeginDate' AND Post_BeginDate <= '$EndDate') OR (Post_BeginDate <= '$BeginDate' AND Post_EndDate >= '$EndDate'))";
			$query .= " AND ( (Org_BeginDate >= '$BeginDate' AND Org_EndDate <= '$EndDate') OR (Org_EndDate >= '$BeginDate' AND Org_EndDate <= '$EndDate') OR (Org_BeginDate >= '$BeginDate' AND Org_BeginDate <= '$EndDate') OR (Org_BeginDate <= '$BeginDate' AND Org_EndDate >= '$EndDate'))";
			$query .= " ORDER BY Org_BeginDate DESC, Org_EndDate DESC, Post_EndDate DESC, Post_BeginDate DESC";
		} else {
			$query .= " ORDER BY Org_BeginDate DESC, Org_EndDate DESC, Post_EndDate DESC, Post_BeginDate DESC";
		}
		//echo $query;
		return $this->pms->query($query)->row();
	}

	public function get_post_list($nik='',$begin='',$end='')
	{
		$this->pms->select('h.BeginDate as hold_begin');
		$this->pms->select('h.EndDate as hold_end');
		$this->pms->select('p.BeginDate as post_begin');
		$this->pms->select('p.EndDate as post_end');
		$this->pms->select('p.PositionID as post_id');
		$this->pms->select('p.PositionName as post_name');
		$this->pms->select('p.OrganizationID as org_id');
		$this->pms->select('p.Chief as chief');
		$this->pms->select('p.PositionGroup as post_group');
		$this->pms->from('Core_M_Holder_SAP h');
		$this->pms->where('h.NIK', $nik);
		$this->pms->where("((h.BeginDate >= '$begin' AND h.EndDate <= '$end') OR (h.EndDate >= '$begin' AND h.EndDate <= '$end') OR (h.BeginDate >= '$begin' AND h.BeginDate <= '$end') OR (h.BeginDate <= '$begin' AND h.EndDate >= '$end'))");
		$this->pms->join('Core_M_Position_SAP p', 'h.PositionID = p.PositionID', 'left');
		$this->pms->where("((p.BeginDate >= '$begin' AND p.EndDate <= '$end') OR (p.EndDate >= '$begin' AND p.EndDate <= '$end') OR (p.BeginDate >= '$begin' AND p.BeginDate <= '$end') OR (p.BeginDate <= '$begin' AND p.EndDate >= '$end'))");

		return $this->pms->get()->result();
	}
	
	
}
?>
