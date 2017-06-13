<?php
class Account_model2 extends Model{
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

 	function get_Role_list($BeginDate='',$EndDate='',$RoleID=1){
 		$query='SELECT * FROM Core_M_Role WHERE RoleID >='.$RoleID;
 		if($BeginDate!='' and $EndDate!=''){
 			$query.=" AND BeginDate<='$BeginDate' And EndDate>='$EndDate'";
 		}
 		return $this->pms->query($query)->result();
 	}

	function get_user_list($statusFlag = 'all'){
		$query = "SELECT * FROM Core_M_User";
		if (strtolower($statusFlag) != 'all') {
			$query .= " WHERE statusFlag = $statusFlag";
		}
		return $this->pms->query($query)->result();
	}

	public function get_user_hr_list($persadmin='')
	{
		$query = "SELECT * FROM Core_M_User WHERE PersAdmin = '$persadmin' WHERE statusFlag = 1";
		return $this->pms->query($query)->result();
	}

	function get_user_row($nik = ''){
		
		$query = "SELECT TOP 1 u.*,r.Role FROM Core_M_User2 u, Core_M_Role r WHERE u.NIK = '$nik' and r.RoleID=u.RoleID";
		return $this->pms->query($query)->row();
	}

	function add_User($NIK,$Fullname,$Photo='',$Email,$Mobile,$RoleID,$statusFlag,$isSAP=0,$password = '',$PersAdmin = '',$SubArea = ''){
		if($password=='')
		{
			$md5_pass=md5('abc123');
			
		}
		else
		{
			$md5_pass = md5($password);
		}
		$query="INSERT INTO [PMS].[dbo].[Core_M_User2]
           ([NIK]
           ,[Fullname]
           ,[Password]
           ,[Photo]
           ,[Email]
           ,[Mobile]
           ,[RoleID]
           ,[statusFlag]
           ,[isSAP]
           ,[PersAdmin]
           ,[SubArea])
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
           ,'$PersAdmin'
           ,'$SubArea')";
		$this->pms->query($query);
	}
	function edit_User($NIK, $Fullname, $Photo, $Email, $Mobile, $RoleID, $EndDate, $password='', $PersAdmin='', $SubArea=''){
		if($password=='')
		{
			$md5_pass=md5('abc123');
		}
		else
		{
			$md5_pass = md5($password);
		}
		$query="UPDATE [PMS].[dbo].[Core_M_User2]
		   SET [Fullname] = '$Fullname'
					,[Photo]     = '$Photo'
					,[Email]     = '$Email'
					,[Mobile]    = '$Mobile'
					,[RoleID]    = $RoleID
					,[EndDate]   = '$EndDate'
					,[Password]  = '$md5_pass'
					,[PersAdmin] = '$PersAdmin'
					,[SubArea]   = '$SubArea'
		 WHERE NIK='$NIK'";
 		$this->pms->query($query);
	}
	public function edit_user_role($NIK,$RoleID)
	{
		echo $query="UPDATE [PMS].[dbo].[Core_M_User2]
		   SET [RoleID] = $RoleID
		 WHERE NIK='$NIK'";
 		$this->pms->query($query);

	}
	function change_password($NIK,$Password){
		$md5_pass=md5($Password);
		$query="UPDATE [PMS].[dbo].[Core_M_User]
		   SET [Password] = '$md5_pass'
		 WHERE NIK='$NIK'";
		 //AND isSAP=0";
 		$this->pms->query($query);
	}
	function check_NIK_isUsed($NIK){
		$query = "SELECT count(*) as count_value FROM Core_M_User2 WHERE NIK='$NIK'";
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
		}
		return $this->pms->query($query)->result();
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
		$query = "SELECT TOP 1 * FROM Core_V_Holder_$table WHERE NIK = '$NIK' AND (Holder_BeginDate<=GETDATE() AND Holder_EndDate>=GETDATE()) AND (Post_BeginDate<=GETDATE() AND Post_EndDate>=GETDATE()) AND
			(Org_BeginDate<=GETDATE() AND Org_EndDate>=GETDATE()) ORDER BY HolderID DESC";
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
		} else {
			$query .= " AND Holder_BeginDate<= GETDATE() AND Holder_EndDate>= GETDATE() AND Post_BeginDate<= GETDATE() AND Post_EndDate>= GETDATE()";
		}

		return $this->pms->query($query)->row();
	}


	
	
	
}
?>