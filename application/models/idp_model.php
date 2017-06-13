<?php
class Idp_model extends Model{
	function __construct(){
		parent::__construct();
		$this->portal=$this->load->database('portal', TRUE);
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
	function get_DevTypeArea_list($BeginDate,$EndDate){
		$query="SELECT * FROM IDP_M_DevelopmentAreaType1 WHERE BeginDate<='$BeginDate' AND EndDate>='$EndDate'";
		return $this->pms->query($query)->result();
	}
	function get_DevProgram_list($BeginDate,$EndDate){
		$query="SELECT * FROM IDP_M_DevelopmentProgram WHERE BeginDate<='$BeginDate' AND EndDate>='$EndDate'";
		return $this->pms->query($query)->result();
	}
	function get_DevTypeArea_row($DevelopmentAreaType1ID){
		$query="SELECT * FROM IDP_M_DevelopmentAreaType1 WHERE DevelopmentAreaType1ID=$DevelopmentAreaType1ID";
		return $this->pms->query($query)->row();
	}
	function get_DevProg_list($BeginDate,$EndDate){
		$query="SELECT * FROM IDP_M_DevelopmentProgram WHERE BeginDate<='$BeginDate' AND EndDate>='$EndDate'";
		return $this->pms->query($query)->result();
	}
	function get_DevProg_row($DevelopmentProgramID){
		$query="SELECT * FROM IDP_M_DevelopmentProgram WHERE DevelopmentProgramID=$DevelopmentProgramID";
		return $this->pms->query($query)->row();
	}
	function get_Header_list($RKKID,$BeginDate,$EndDate){
		$query="SELECT * FROM IDP_T_Header WHERE RKKID=$RKKID AND BeginDate<='$BeginDate' AND EndDate>='$EndDate'";
		return $this->pms->query($query)->result();
	}
	function get_Header_row($IDPID){
		$query="SELECT * FROM IDP_T_Header WHERE IDPID=$IDPID";
		return $this->pms->query($query)->row();
	}

	function get_Header_byRKKID_row($RKKID,$BeginDate,$EndDate){
		$query="SELECT TOP 1 * 
						FROM IDP_T_Header 
						WHERE RKKID=$RKKID AND
							 ((BeginDate >= '$BeginDate' AND EndDate <= '$EndDate') OR (EndDate >= '$BeginDate' AND EndDate <= '$EndDate') OR (BeginDate >= '$BeginDate' AND BeginDate <= '$EndDate') OR (BeginDate <= '$BeginDate' AND EndDate >= '$EndDate')) 
						ORDER BY IDPID DESC";
		return $this->pms->query($query)->row();
	}


	function get_Count_Header_byRKKID_row($RKKID,$BeginDate,$EndDate){
		$query="SELECT COUNT(*) as Total
						FROM IDP_T_Header 
						WHERE RKKID=$RKKID AND
							 ((BeginDate >= '$BeginDate' AND EndDate <= '$EndDate') 
							 	OR (EndDate >= '$BeginDate' AND EndDate <= '$EndDate') OR (BeginDate >= '$BeginDate' 
							 		AND BeginDate <= '$EndDate') OR (BeginDate <= '$BeginDate' AND EndDate >= '$EndDate'))";
		return $this->pms->query($query)->row()->Total;
	}

	function get_Header_rowbyRKKID($RKKID){
		$query="SELECT * FROM IDP_T_Header WHERE RKKID=$RKKID ORDER BY IDPID DESC";
		return $this->pms->query($query)->row();
	}

	function get_Development_Area_List($BeginDate='',$EndDate=''){
		if($BeginDate=='' or $EndDate==''){
			$query = "SELECT * FROM IDP_M_DevelopmentAreaType1";
		}else{
			$query = "SELECT * FROM IDP_M_DevelopmentAreaType1 WHERE (([BeginDate] <= '$BeginDate' and [EndDate] >= '$EndDate') OR ([BeginDate] <= GETDATE() and [EndDate] >= '$EndDate'))";
		}
		return $this->pms->query($query)->result();
	}

	function add_Header($RKKID,$BeginDate,$EndDate){
		$query="INSERT INTO [PMS].[dbo].[IDP_T_Header]([RKKID],[StatusFlag],[BeginDate],[EndDate])VALUES($RKKID,0,'$BeginDate','$EndDate')";
		$this->pms->query($query);
		$query ="SELECT TOP 1 * FROM IDP_T_Header ORDER BY IDPID DESC";
		return $this->pms->query($query)->row();
	}
	function edit_Header($IDPID,$StatusFlag){
		$query="UPDATE [IDP_T_Header] SET [StatusFlag] = $StatusFlag WHERE IDPID=$IDPID";
		$this->pms->query($query);
	}
	function delimit_Header($IDPID){
		$query="UPDATE [PMS].[dbo].[IDP_T_Header] SET [EndDate] = GETDATE() WHERE IDPID=$IDPID";
		$this->pms->query($query);
	}

	public function delimit_byRKK($RKKID=0,$Enddate='')
	{
		$query="UPDATE [PMS].[dbo].[IDP_T_Header] SET [EndDate] = Enddate WHERE RKKID=$RKKID";
		$this->pms->query($query);
	}

	public function remove_byRKK($rkk_id=0)
	{
		$query="DELETE FROM [PMS].[dbo].[IDP_T_Header] WHERE RKKID=$rkk_id";
		$this->pms->query($query);
	}
	function get_Detail_list($IDPID){
		$query="SELECT * FROM IDP_T_Detail A INNER JOIN IDP_M_DevelopmentAreaType1 B ON 
			A.DevelopmentAreaType1ID=B.DevelopmentAreaType1ID 
			WHERE IDPID=$IDPID";
		return $this->pms->query($query)->result();		
	}
	function get_Detail_row($IDPDetailID){
		$query="SELECT * FROM IDP_T_Detail WHERE IDPDetailID=$IDPDetailID";
		return $this->pms->query($query)->row();	
	}
	
	function add_Detail($IDPID,$DevelopmentAreaType1ID,$DevelopmentAreaType=''){
		$query="INSERT INTO [PMS].[dbo].[IDP_T_Detail]([IDPID],[DevelopmentAreaType1ID],[DevelopmentAreaType])VALUES($IDPID,$DevelopmentAreaType1ID,'$DevelopmentAreaType')";
		$this->pms->query($query);
		$query = "SELECT TOP 1 * FROM IDP_T_Detail ORDER BY IDPDetailID DESC";
		return $this->pms->query($query)->row();
	}
	
	function edit_Detail($IDPDetailID,$DevelopmentAreaType1ID,$DevelopmentAreaType){
		$query="UPDATE [PMS].[dbo].[IDP_T_Detail] SET [DevelopmentAreaType1ID] = $DevelopmentAreaType1ID,[DevelopmentAreaType] = '$DevelopmentAreaType' WHERE IDPDetailID=$IDPDetailID";
		$this->pms->query($query);
	}
	
	function get_DP_DevelopmentProgram_row($IDPDevelopmentProgramID){
		$query="SELECT * FROM IDP_T_DevelopmentProgram WHERE IDPDevelopmentProgramID=$IDPDevelopmentProgramID";
		return $this->pms->query($query)->row();
	}

	function get_DP_DevelopmentProgram_join_row($IDPDevelopmentProgramID){
		$query="SELECT *, A.Description as desc_prog FROM IDP_T_DevelopmentProgram A inner join IDP_T_Detail B on A.IDPDetailID=B.IDPDetailID
			inner join IDP_M_DevelopmentProgram C on A.DevelopmentProgramID=C.DevelopmentProgramID
			WHERE IDPDevelopmentProgramID=$IDPDevelopmentProgramID";
		return $this->pms->query($query)->row();
	}


	function delete_dev_program($idp_dev_program)
	{
		$query="DELETE FROM IDP_T_DevelopmentProgram WHERE IDPDevelopmentProgramID=$idp_dev_program";
		return $this->pms->query($query);
	}

	function delete_idp_header($idp_detail)
	{
		$query="DELETE FROM IDP_T_Detail WHERE IDPDetailID=$idp_detail";
		return $this->pms->query($query);
	}

	function count_DP($RKKID,$BeginDate,$EndDate)
	{
		$query =
		"SELECT 
			COUNT(*) as Total
		FROM 
			IDP_T_Header H, 
			IDP_T_Detail D, 
			IDP_T_DevelopmentProgram T 
		WHERE 
			H.IDPID=D.IDPID AND 
			D.IDPDetailID=T.IDPDetailID AND 
		 	((H.BeginDate >= '$BeginDate' AND H.EndDate <= '$EndDate') OR (H.EndDate >= '$BeginDate' AND H.EndDate <= '$EndDate') OR (H.BeginDate >= '$BeginDate' AND H.BeginDate <= '$EndDate') OR (H.BeginDate <= '$BeginDate' AND H.EndDate >= '$EndDate')) AND
			H.RKKID = $RKKID;";
			//echo $query;
		return $this->pms->query($query)->row()->Total;
	}

	function add_DP_Trans($IDPDetailID,$DevelopmentProgramID,$Planned_BeginDate,$Planned_EndDate,$Planned_Investment,$Description='',$Notes=''){
		$query="INSERT INTO [PMS].[dbo].[IDP_T_DevelomentProgram]([IDPDetailID],[DevelopmentProgramID],[Description],[Planned_BeginDate],[Planned_EndDate],[Planned_Investment],[Notes])VALUES($IDPDetailID,$DevelopmentProgramID,'$Description','$Planned_BeginDate','$Planned_EndDate'," . ($Planned_Investment ? "'$Planned_Investment'": 'NULL') . ", " . ($Notes ? "'$Notes'": 'NULL') . ")";
		$this->pms->query($query);
		$query = "SELECT TOP 1 * FROM IDP_T_DevelomentProgram ORDER BY IDPDevelopmentProgramID DESC";
		return $this->pms->query($query)->row();

	}

	function edit_DP_Trans($IDPDevelopmentProgramID,$DevelopmentProgramID,$Planned_BeginDate,$Planned_EndDate,$Planned_Investment,$Description='',$Notes=''){
		$query="UPDATE [IDP_T_DevelopmentProgram] SET [DevelopmentProgramID] = $DevelopmentProgramID ,[Description] = '$Description',[Planned_BeginDate] = '$Planned_BeginDate',[Planned_EndDate] = '$Planned_EndDate',[Planned_Investment] = ".($Planned_Investment ? "'$Planned_Investment'": 'NULL').",[Notes] = " . ($Notes ? "'$Notes'": 'NULL') . " WHERE IDPDevelopmentProgramID=$IDPDevelopmentProgramID"; 
		$this->pms->query($query);
	}

	function add_realization($IDPDevelopmentProgramID,$Realization_BeginDate,$Realization_EndDate,$Realization_Investment,$Notes=''){
		$query="UPDATE [IDP_T_DevelopmentProgram] SET [Realization_BeginDate] = '$Realization_BeginDate',[Realization_EndDate] = '$Realization_EndDate',[Realization_Investment] = ".($Realization_Investment ? "'$Realization_Investment'": 'NULL').", [Notes] = " . ($Notes ? "'$Notes'": 'NULL'). " WHERE IDPDevelopmentProgramID=$IDPDevelopmentProgramID"; 
		$this->pms->query($query);
	}

	function edit_DP_Realization($IDPDevelopmentProgramID,$BeginDate,$EndDate,$Investment,$Notes=''){
		$query="UPDATE [PMS].[dbo].[IDP_T_DevelopmentProgram] SET Realization_BeginDate ='$BeginDate', Realization_EndDate='$EndDate',Realization_Investment=" . (!$Investment ? 'NULL' : (int)$Investment) . ", Notes='$Notes' WHERE IDPDevelopmentProgramID=$IDPDevelopmentProgramID";
		$this->pms->query($query);
	}

	function get_idp_byRKKID($RKKID,$BeginDate,$EndDate){
 		$query = "SELECT * FROM IDP_T_Header WHERE RKKID=$RKKID AND BeginDate<= '$BeginDate' AND EndDate>='$EndDate'";
 		return $this->pms->query($query)->row();
 	}	

 	function get_Kompetensi_TM_Portal()
 	{
 		$query = "SELECT KompetensiID, Nama, B.isActive  FROM TM_Kompetensi_Header A 
				INNER JOIN tm_Kompetensi B on 
				A.KompetensiHeaderID=B.KompetensiHeaderID where B.isActive=1";
		return $this->portal->query($query)->result();
 	}

 	function get_Kompetensi_NamaByID($KompetensiID)
 	{
 		$query = "SELECT KompetensiID, Nama  FROM TM_Kompetensi_Header A 
				INNER JOIN tm_Kompetensi B on 
				A.KompetensiHeaderID=B.KompetensiHeaderID WHERE KompetensiID=$KompetensiID";
		return $this->portal->query($query)->row();
 	}

 	function get_CV_Values()
 	{
 		$query = "SELECT * FROM CV_M_Values";
		return $this->pms->query($query)->result();
 	}

 	function get_CV_ValuesbyID($ValuesID)
 	{
 		$query = "SELECT * FROM CV_M_Values where values_id=$ValuesID";
		return $this->pms->query($query)->row();
 	}

 	function get_IDP_DevelopmentProgram($IDPDetailID)
 	{
 		$query = "SELECT A.*, B.DevelopmentProgram FROM IDP_T_DevelopmentProgram A INNER JOIN IDP_M_DevelopmentProgram B ON A.DevelopmentProgramID=B.DevelopmentProgramID WHERE IDPDetailID=$IDPDetailID";
		return $this->pms->query($query)->result();
 	}

 	function add_IDP($RKKID,$statusFlag,$BeginDate,$EndDate){
 		$query = "INSERT INTO [IDP_T_Header]([RKKID],[StatusFlag],[BeginDate],[EndDate])VALUES($RKKID,$statusFlag,'$BeginDate','$EndDate')";
 		$this->pms->query($query);
 		$query = "SELECT TOP 1 * FROM IDP_T_Header ORDER BY IDPID DESC";
 		return $this->pms->query($query)->row();
 	}

 	function get_idp_byUserPosition_row($UserID,$PositionID,$BeginDate,$EndDate){
 		$query = "SELECT TOP 1 * FROM PA_T_RKK WHERE UserID=$UserID AND PositionID=$PositionID AND(( BeginDate<= '$BeginDate' AND EndDate>='$EndDate') OR  BeginDate<= GETDATE() AND EndDate>='$EndDate')";
 		return $this->pms->query($query)->row();
 	}


 	function add_IDP_Detail($IDPID,$DevAreaType,$DetailDevAreaType){
 		$query = "INSERT INTO [IDP_T_Detail]([IDPID],[DevelopmentAreaType1ID],[DevelopmentAreaType])VALUES($IDPID,$DevAreaType,'$DetailDevAreaType')";
 		$this->pms->query($query);
 		$query = "SELECT TOP 1 * FROM IDP_T_Detail ORDER BY IDPDetailID DESC";
 		return $this->pms->query($query)->row();
 	}

 	function edit_IDP_Detail($IDPDetailID,$DevAreaType,$DetailDevAreaType){
 		$query = "UPDATE [IDP_T_Detail] SET DevelopmentAreaType1ID='$DevAreaType', DevelopmentAreaType='$DetailDevAreaType' WHERE IDPDetailID=$IDPDetailID";
 		$this->pms->query($query);
 		$query = "SELECT TOP 1 * FROM IDP_T_Detail ORDER BY IDPDetailID DESC";
 		return $this->pms->query($query)->row();
 	}

 	function add_DevelopmentProgramTrans($IDPDetailID,$DevProgramID,$DescriptionDevProgam,$BeginDate,$EndDate,$PlanInvestment,$Notes){
 		$query = "INSERT INTO [IDP_T_DevelopmentProgram]([IDPDetailID],[DevelopmentProgramID],[Description],[Planned_BeginDate],[Planned_EndDate],[Planned_Investment],[Notes])VALUES($IDPDetailID,$DevProgramID,'$DescriptionDevProgam','$BeginDate','$EndDate'," . ($PlanInvestment ? "'$PlanInvestment'": 'NULL') . ", " . ($Notes ? "'$Notes'": 'NULL') . ")";
 		$this->pms->query($query);
 	}
}
?>