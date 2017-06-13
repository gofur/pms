<?php
class System_model extends Model{
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
	function get_menu($RoleID,$ParentID=0){//memanggil menu yang aktif sesuai dengan rolenya
		$query="SELECT * FROM Core_V_MenuRole WHERE [RoleID] = $RoleID AND Menu_Parent = $ParentID AND [BeginDate] <= '".date('Y-m-d')."' and [EndDate] >= '".date('Y-m-d')."' AND [Menu_BeginDate] <= '".date('Y-m-d')."' and [Menu_EndDate] >= '".date('Y-m-d')."' AND [Role_BeginDate] <= '".date('Y-m-d')."' and [Role_EndDate] >= '".date('Y-m-d')."' AND RoleAccess =1 order by Order_Value";
		return $this->pms->query($query)->result();
	}
	function count_menu($RoleID,$ParentID=0){//menghitung menu yang aktif sesuai dengan rolenya
		$query="SELECT count(*) as count_value FROM Core_V_MenuRole WHERE [RoleID] = $RoleID AND Menu_Parent = $ParentID AND [BeginDate] <= '".date('Y-m-d')."' and [EndDate] >= '".date('Y-m-d')."' AND [Menu_BeginDate] <= '".date('Y-m-d')."' and [Menu_EndDate] >= '".date('Y-m-d')."' AND [Role_BeginDate] <= '".date('Y-m-d')."' and [Role_EndDate] >= '".date('Y-m-d')."' AND RoleAccess =1 ";
		return $this->pms->query($query)->row()->count_value;
	}
	function get_role_list($BeginDate='',$EndDate=''){
		$query="SELECT * FROM Core_M_Role WHERE BeginDate>='$BeginDate' AND EndDate<='$EndDate'";
		return $this->pms->query($query)->result();
	}
	function check_roleAccess($RoleID,$url_value){
		$query = "SELECT TOP 1 RoleAccess FROM Core_V_MenuRole WHERE RoleID =$RoleID AND Link_Value = '$url_value' AND BeginDate <=GETDATE() AND EndDate>= GETDATE() AND Role_BeginDate <=GETDATE() AND Role_EndDate>=GETDATE() AND Menu_BeginDate<=GETDATE() and Menu_EndDate >=GETDATE() ORDER BY RolePrivillageID DESC";
		return $this->pms->query($query)->row()->RoleAccess;
	}

}
?>