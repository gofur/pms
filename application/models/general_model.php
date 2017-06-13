<?php
class General_model extends Model{
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

	// model Exception Reporting Structure //
	function get_ExceptionReportingStructure_List($start_date='',$end_date=''){
		if($start_date=='' or $end_date==''){
			$query = "SELECT A.ExceptionReportingStructureID, A.ChiefPositionID, A.Chief_isSAP, A.BeginDate, A.EndDate, A.PositionID, A.isSAP FROM Core_M_Exception_Reporting_Structure A GROUP BY A.ChiefPositionID, A.Chief_isSAP, A.PositionID, A.isSAP, A.ExceptionReportingStructureID, A.BeginDate, A.EndDate";
		}else{
			$query = "SELECT A.ExceptionReportingStructureID, A.ChiefPositionID, A.Chief_isSAP, A.BeginDate, A.EndDate, A.PositionID, A.isSAP FROM Core_M_Exception_Reporting_Structure A WHERE A.[BeginDate] <= '$start_date' and A.[EndDate] >= '$end_date' GROUP BY A.ChiefPositionID, A.Chief_isSAP, A.PositionID, A.isSAP, A.ExceptionReportingStructureID, A.BeginDate, A.EndDate";
		}
		return $this->pms->query($query)->result();
	}

	function add_ExceptionReportingStructure($ChiefPositionID,$Chief, $PositionID, $isSAP, $start_date,$end_date){
		$query = "INSERT INTO [Core_M_Exception_Reporting_Structure]([ChiefPositionID],[Chief_isSAP],[PositionID],[isSAP],[BeginDate],[EndDate])
		VALUES('$ChiefPositionID','$Chief','$PositionID','$isSAP', '$start_date','$end_date')";
		$this->pms->query($query);
	}

	function edit_ExceptionReportingStructure($ExceptionReportingStructureID,$end_date){
		$query = "UPDATE [Core_M_Exception_Reporting_Structure] SET [EndDate] = '$end_date' WHERE ExceptionReportingStructureID=$ExceptionReportingStructureID";
		$this->pms->query($query);
	}

	public function remove_ExceptionReportingStructure($ExceptionReportingStructureID)
	{
		$query = "DELETE FROM [Core_M_Exception_Reporting_Structure] WHERE ExceptionReportingStructureID=$ExceptionReportingStructureID";
		$this->pms->query($query);
	}

	function get_ExceptionReportingStructure_row($ExceptionReportingStructureID){
		$query = "SELECT * FROM Core_M_Exception_Reporting_Structure WHERE ExceptionReportingStructureID=$ExceptionReportingStructureID";
		return $this->pms->query($query)->row();
	}

	function check_ExceptionReportingStructure($PositionID,$isSAP, $BeginDate, $EndDate){
		$query = "SELECT COUNT(*) as count_value FROM Core_M_Exception_Reporting_Structure WHERE PositionID=$PositionID AND isSAP=$isSAP AND BeginDate='$BeginDate' AND EndDate >= '$EndDate'";
		if($this->pms->query($query)->row()->count_value>0){
			return true;
		}else{
			return false;
		}
	}

	// ending model Exception Reporting Structure //


	// function master Generic KPI //
	function get_GenericKPI_List($start_date='',$end_date=''){
		if($start_date=='' or $end_date==''){
			$query = "SELECT * FROM PA_T_KPI_Generic";
		}else{
			$query = "SELECT * FROM PA_T_KPI_Generic WHERE ([BeginDate] <= '$start_date' and [EndDate] >= '$end_date') OR ([BeginDate] <= GETDATE() and [EndDate] >= '$end_date')";
		}
		return $this->pms->query($query)->result();
	}

	function get_GenericKPI_Search($PerspectiveID){
		if($PerspectiveID==''){
			$query = "SELECT A.KPI, A.Description, A.BeginDate,A.EndDate, A.KPIGenericID, A.PCFormulaID, A.PerspectiveID, A.SatuanID, A.YTDID, D.CaraHitungID, D.PCFormula, D.PCFormulaID, C.Satuan, E.YTD, F.Perspective,G.CaraHitung
					from PA_T_KPI_Generic A left outer join PA_M_Satuan C on A.SatuanID=C.SatuanID
					left outer join PA_M_PCFormula D on A.PCFormulaID=D.PCFormulaID
					left outer join PA_M_YTD E on A.YTDID=E.YTDID
					left outer join PA_M_Perspective F on A.PerspectiveID=F.PerspectiveID
					left outer join PA_M_CaraHitung G on G.CaraHitungID=D.CaraHitungID";
		}else{
			$query = "SELECT A.KPI, A.Description, A.BeginDate,A.EndDate, A.KPIGenericID, A.PCFormulaID, A.PerspectiveID, A.SatuanID, A.YTDID, D.CaraHitungID, D.PCFormula, D.PCFormulaID, C.Satuan, E.YTD, F.Perspective,G.CaraHitung
				from PA_T_KPI_Generic A left outer join PA_M_Satuan C on A.SatuanID=C.SatuanID
				left outer join PA_M_PCFormula D on A.PCFormulaID=D.PCFormulaID
				left outer join PA_M_YTD E on A.YTDID=E.YTDID
				left outer join PA_M_Perspective F on A.PerspectiveID=F.PerspectiveID
				left outer join PA_M_CaraHitung G on G.CaraHitungID=D.CaraHitungID
				where  F.PerspectiveID=$PerspectiveID";
		}
		return $this->pms->query($query)->result();
	}

	function get_GenericKPI_row($GenericKPIID){
		$query = "SELECT kpi.*,ss.PerspectiveID, kk.CaraHitungID FROM PA_T_KPI_Generic kpi, PA_M_CaraHitung kk, PA_M_Perspective ss WHERE kpi.KPIGenericID=$GenericKPIID and kpi.PerspectiveID = ss.PerspectiveID";
		return $this->pms->query($query)->row();
	}

	function check_GenericKPI_isUsed($GenericKPIID){
		$query = "SELECT COUNT(*) as count_value FROM PA_T_KPI_Generic WHERE KPIGenericID=$GenericKPIID";
		if($this->pms->query($query)->row()->count_value>0){
			return true;
		}else{
			return false;
		}
	}

	function add_GenericKPI($PerspectiveID,$SatuanID, $PCFormulaID, $YTDID, $KPI, $Description,$start_date,$end_date){
		$query = "INSERT INTO [PA_T_KPI_Generic]([PerspectiveID],[SatuanID],[PCFormulaID],[YTDID], [KPI], [Description],[BeginDate],[EndDate])VALUES('$PerspectiveID','$SatuanID','$PCFormulaID','$YTDID', '$KPI', '$Description', '$start_date','$end_date')";
		$this->pms->query($query);
		$query ="SELECT TOP 1 * FROM PA_T_KPI_Generic ORDER BY KPIGenericID Desc";
		return $this->pms->query($query)->row();
	}

	function edit_GenericKPI($GenericKPIID, $PerspectiveID,$SatuanID, $PCFormulaID, $YTDID, $KPI, $Description,$start_date,$end_date){
		$query = "UPDATE [PA_T_KPI_Generic] SET [PerspectiveID] = '$PerspectiveID', [PCFormulaID]='$PCFormulaID', [SatuanID]='$SatuanID', [YTDID]='$YTDID', [KPI]='$KPI', [Description]='$Description', [EndDate] = '$end_date' WHERE KPIGenericID=$GenericKPIID";
		$this->pms->query($query);
	}

	function remove_GenericKPI($GenericKPIID){
		$query = "DELETE FROM PA_T_KPI_Generic WHERE KPIGenericID=$GenericKPIID";
		$this->pms->query($query);
	}
	// end master Generic KPI //


	// function master YTD //
	function get_YTD_row($YTDID){
		$query = "SELECT * FROM PA_M_YTD WHERE YTDID=$YTDID ";
		return $this->pms->query($query)->row();
	}

	function check_YTD_isUsed($YTDID){
		$query = "SELECT COUNT(*) as count_value FROM PA_M_YTD WHERE YTDID=$YTDID";
		if($this->pms->query($query)->row()->count_value>0){
			return true;
		}else{
			return false;
		}
	}

	function add_YTD($label='text',$labelDesc='text',$start_date = '2013-01-01',$end_date='9999-12-31'){
		$query = "INSERT INTO [PA_M_YTD]([YTD],[Description],[BeginDate],[EndDate])VALUES('$label','$labelDesc','$start_date','$end_date')";
		$this->pms->query($query);
		$query = "SELECT TOP 1 * FROM PA_M_YTD ORDER BY YTDID DESC";
		return $this->pms->query($query)->row();
	}

	function edit_YTD($YTDID,$label='text',$labelDesc='text',$start_date = '2013-01-01',$end_date='9999-12-31'){
		$query = "UPDATE [PA_M_YTD] SET [YTD] = '$label', [Description]='$labelDesc',[EndDate] = '$end_date' WHERE YTDID=$YTDID";
		$this->pms->query($query);
	}

	function remove_YTD($YTDID){
		$query = "DELETE FROM PA_M_YTD WHERE YTDID=$YTDID";
		$this->pms->query($query);
	}

	function get_YTD_list($start_date='',$end_date=''){
		if($start_date=='' or $end_date==''){
			$query = "SELECT * FROM PA_M_YTD";
		}else{
			$query = "SELECT * FROM PA_M_YTD WHERE [BeginDate] <= '$start_date' and [EndDate] >= '$end_date'";
		}

		return $this->pms->query($query)->result();
	}

	// end master YTD //

	// function master Reference //
	function get_Reference_List($start_date='',$end_date=''){
		if($start_date=='' or $end_date==''){
			$query = "SELECT * FROM PA_M_Reference";
		}else{
			$query = "SELECT * FROM PA_M_Reference WHERE ([BeginDate] <= '$start_date' and [EndDate] >= '$end_date') OR ([BeginDate] <= GETDATE() and [EndDate] >= '$end_date')";
		}
		return $this->pms->query($query)->result();
	}

	function get_Reference_row($ReferenceID){
		$query = "SELECT * FROM PA_M_Reference WHERE ReferenceID=$ReferenceID";
		return $this->pms->query($query)->row();
	}

	function check_Reference_isUsed($YTDID){
		$query = "SELECT COUNT(*) as count_value FROM PA_M_Reference WHERE ReferenceID=$ReferenceID";
		if($this->pms->query($query)->row()->count_value>0){
			return true;
		}else{
			return false;
		}
	}

	function add_Reference($label='text',$labelDesc='text',$start_date = '2013-01-01',$end_date='9999-12-31'){
		$query = "INSERT INTO [PA_M_Reference]([Reference],[Description],[BeginDate],[EndDate])VALUES('$label','$labelDesc','$start_date','$end_date')";
		$this->pms->query($query);
		$query = "SELECT TOP 1 * FROM PA_M_Reference ORDER BY ReferenceID DESC";
		return $this->pms->query($query)->row();
	}

	function edit_Reference($ReferenceID,$label='text',$labelDesc='text',$start_date = '2013-01-01',$end_date='9999-12-31'){
		$query = "UPDATE [PA_M_Reference] SET [Reference] = '$label', [Description]='$labelDesc',[EndDate] = '$end_date' WHERE ReferenceID=$ReferenceID";
		$this->pms->query($query);
	}

	function remove_Reference($ReferenceID){
		$query = "DELETE FROM PA_M_Reference WHERE ReferenceID=$ReferenceID";
		$this->pms->query($query);
	}
	// end master Reference //

	// function master Perspective //
	function get_Perspective_List($start_date='',$end_date=''){
		if($start_date=='' or $end_date==''){
			$query = "SELECT * FROM PA_M_Perspective";
		}else{
			$query = "SELECT *
								FROM PA_M_Perspective
								WHERE ((BeginDate >= '$start_date' AND EndDate <='$end_date') OR
 								(EndDate >= '$start_date' AND EndDate <= '$end_date') OR
 								(BeginDate >= '$start_date' AND BeginDate <='$end_date' ) OR
 								(BeginDate <= '$start_date' AND EndDate >= '$end_date'))";
		}
		return $this->pms->query($query)->result();
	}

	function get_Perspective_row($PerspectiveID){
		$query = "SELECT * FROM PA_M_Perspective WHERE PerspectiveID=$PerspectiveID ";
		return $this->pms->query($query)->row();
	}

	function check_Perspective_isUsed($PerspectiveID){
		$query = "SELECT COUNT(*) as count_value FROM PA_M_Perspective WHERE PerspectiveID=$PerspectiveID";
		if($this->pms->query($query)->row()->count_value>0){
			return true;
		}else{
			return false;
		}
	}

	function add_Perspective($label='text',$labelDesc='text',$start_date = '2013-01-01',$end_date='9999-12-31'){
		$query = "INSERT INTO [PA_M_Perspective]([Perspective],[Description],[BeginDate],[EndDate])VALUES('$label','$labelDesc','$start_date','$end_date')";
		$this->pms->query($query);
		$query = "SELECT TOP 1 * FROM PA_M_Perspective ORDER BY PerspectiveID DESC";
		return $this->pms->query($query)->row();
	}

	function edit_Perspective($PerspectiveID,$label='text',$labelDesc='text',$start_date = '2013-01-01',$end_date='9999-12-31'){
		$query = "UPDATE [PA_M_Perspective] SET [Perspective] = '$label', [Description]='$labelDesc',[EndDate] = '$end_date' WHERE PerspectiveID=$PerspectiveID";
		$this->pms->query($query);
	}

	function remove_Perspective($PerspectiveID){
		$query = "DELETE FROM PA_M_Perspective WHERE PerspectiveID=$PerspectiveID";
		$this->pms->query($query);
	}
	// end master Perspective //
	// master Period
	function get_Period_list($start_date='',$end_date=''){
		if($start_date=='' or $end_date==''){
			$query = "SELECT * FROM Core_M_PeriodePM";
		}else{
			$query = "SELECT * FROM Core_M_PeriodePM WHERE [BeginDate] <= '$start_date' and [EndDate] >= '$end_date'";
		}
		return $this->pms->query($query)->result();
	}
	function get_ActivePeriode(){
		$query ="SELECT TOP 1 * FROM Core_M_PeriodePM WHERE [BeginDate] <= '".date('Y-m-d')."' and [EndDate] >= '".date('Y-m-d')."' ORDER BY EndDate DESC";
		return $this->pms->query($query)->row();
	}
	function get_Period_row($PeriodePMID){
		$query = "SELECT TOP 1 * FROM Core_M_PeriodePM WHERE PeriodePMID = $PeriodePMID";
		return $this->pms->query($query)->row();
	}
	function add_Period($Tahun,$start_date,$end_date){
		$query="INSERT INTO [Core_M_PeriodePM]([Tahun],[BeginDate],[EndDate])VALUES('$Tahun','$start_date','$end_date')";
		$this->pms->query($query);
		$query="SELECT TOP 1 * FROM Core_M_PeriodePM ORDER BY DESC";
		return $this->pms->query($query)->row();
	}
	function edit_Period($PeriodePMID,$Tahun,$start_date,$end_date){
		$query="UPDATE [Core_M_PeriodePM] SET [Tahun] = '$Tahun',[EndDate] = '$end_date' WHERE PeriodePMID=$PeriodePMID";
		$this->pms->query($query);
	}

	function remove_Period($PeriodePMID){
		$query = "DELETE FROM Core_M_PeriodePM WHERE PeriodePMID=$PeriodePMID";
		$this->pms->query($query);
	}
	//end of master Period
	//master of Scale (PA Score and TPC Score)
	function get_Scale_list($typeFlag=0,$start_date='',$end_date='',$isPA=2){
		$query = "SELECT * FROM Core_M_CodeColour";
		if($typeFlag>0){
			$query .=" WHERE typeFlag = $typeFlag ";
			if($start_date!='' and $end_date!=''){
				$query .= " AND [BeginDate] <= '$start_date' and [EndDate] >= '$end_date'";
			}
		}elseif($start_date!='' and $end_date!=''){
			$query .= " WHERE [BeginDate] <= '$start_date' and [EndDate] >= '$end_date'";
		}
		return $this->pms->query($query)->result();
	}
	function get_Scale_statistic($typeFlag=0,$start_date='',$end_date=''){
		$query = "SELECT MAX(TPCHigh) as high_max, MAX(TPCLow) as low_max,MIN(TPCHigh) as high_min, MIN(TPCLow) as low_min FROM Core_M_CodeColour";
		if($typeFlag>0){
			$query .=" WHERE typeFlag = $typeFlag ";
			if($start_date!='' and $end_date!=''){
				$query .= " AND [BeginDate] <= '$start_date' and [EndDate] >= '$end_date'";
			}
		}elseif($start_date!='' and $end_date!=''){
			$query .= " WHERE [BeginDate] <= '$start_date' and [EndDate] >= '$end_date'";
		}
		return $this->pms->query($query)->row();
	}
	function get_Scale_row($CodeColourID){
		$query = "SELECT * FROM Core_M_CodeColour WHERE CodeColourID=$CodeColourID ";
		return $this->pms->query($query)->row();
	}
	function add_PAScale($color,$score,$start_date,$end_date){
		$query = "INSERT INTO [Core_M_CodeColour]([Colour],[TypeFlag],[PAScore],[TPCLow],[TPCHigh],[BeginDate],[EndDate])VALUES('$color',1,$score,NULL,NULL,'$start_date','$end_date')";
		$this->pms->query($query);
	}
	function edit_PAScale($CodeColourID,$color,$score,$start_date,$end_date){
		$query = "UPDATE [Core_M_CodeColour] SET [Colour] = '$color', [PAScore] = $score,[EndDate] = '$end_date' WHERE CodeColourID=$CodeColourID";
		$this->pms->query($query);
	}
	function add_TPCScale($color,$low,$high,$start_date,$end_date){
		$query = "INSERT INTO [Core_M_CodeColour]([Colour],[TypeFlag],[TPCLow],[TPCHigh],[BeginDate],[EndDate])VALUES('$color',2,$low,$high,'$start_date','$end_date')";
		$this->pms->query($query);
	}
	function edit_TPCScale($CodeColourID,$color,$low,$high,$start_date,$end_date){
		$query = "UPDATE [Core_M_CodeColour] SET [Colour] = '$color', [TPCLow] = $low,[TPCHigh] = $high,[EndDate] = '$end_date' WHERE CodeColourID=$CodeColourID";
		$this->pms->query($query);
	}
	function delete_PAColor($CodeColourID){
		$query = "DELETE FROM [Core_M_CodeColour]  WHERE CodeColourID=$CodeColourID";
		$this->pms->query($query);
	}
	//end of Scale (PA Score and TPC Score)
	// master Measurment Unit
	function get_Satuan_list($start_date='',$end_date=''){
		if($start_date=='' or $end_date==''){
			$query = "SELECT * FROM PA_M_Satuan";
		}else{
			$query = "SELECT * FROM PA_M_Satuan WHERE [BeginDate] <= '$start_date' and [EndDate] >= '$end_date'";
		}
		return $this->pms->query($query)->result();
	}
	function get_Satuan_row($SatuanID){
		$query = "SELECT * FROM PA_M_Satuan WHERE SatuanID=$SatuanID ";
		return $this->pms->query($query)->row();
	}
	function check_Satuan_isUsed($SatuanID){
		$query = "SELECT COUNT(*) as count_value FROM PA_T_KPI WHERE SatuanID=$SatuanID";
		if($this->pms->query($query)->row()->count_value>0){
			return true;
		}else{
			return false;
		}
	}
	function add_Satuan($label='text',$start_date = '2013-01-01',$end_date='9999-12-31'){
		$query = "INSERT INTO [PA_M_Satuan]([Satuan],[BeginDate],[EndDate])VALUES('$label','$start_date','$end_date')";
		$this->pms->query($query);
	}
	function edit_Satuan($SatuanID,$label='text',$start_date = '2013-01-01',$end_date='9999-12-31'){
		$query = "UPDATE [PA_M_Satuan] SET [Satuan] = '$label' ,[EndDate] = '$end_date' WHERE SatuanID=$SatuanID";
		$this->pms->query($query);
	}
	function remove_Satuan($SatuanID){
		$query = "DELETE FROM PA_M_Satuan WHERE SatuanID=$SatuanID";
		$this->pms->query($query);
	}
	// end of Measurment Unit
	// master Counting Type
	function get_CaraHitung_list($start_date='',$end_date=''){
		if($start_date=='' or $end_date==''){
			$query = "SELECT * FROM PA_M_CaraHitung";
		}else{
			$query = "SELECT * FROM PA_M_CaraHitung WHERE [BeginDate] <= '$start_date' and [EndDate] >= '$end_date'";
		}
		return $this->pms->query($query)->result();
	}
	function get_CaraHitung_row($CaraHitungID){
		$query = "SELECT * FROM PA_M_CaraHitung WHERE CaraHitungID=$CaraHitungID ";
		return $this->pms->query($query)->row();
	}
	function check_CaraHitung_isUsed($CaraHitungID){
		$query = "SELECT count(*) as count_value FROM PA_M_PCFormula WHERE CaraHitungID=$CaraHitungID ";
		if($this->pms->query($query)->row()->count_value>0){
			return true;
		}else{
			return false;
		}
	}
	function add_CaraHitung($label='text',$start_date = '2013-01-01',$end_date='9999-12-31'){
		$query="INSERT INTO [PA_M_CaraHitung]([CaraHitung],[BeginDate],[EndDate])VALUES('$label','$start_date','$end_date')";
		$this->pms->query($query);
	}
	function edit_CaraHitung($CaraHitungID,$label='text',$start_date = '2013-01-01',$end_date='9999-12-31'){
		$query="UPDATE [PA_M_CaraHitung]SET [CaraHitung] = '$label',[EndDate] = '$end_date' WHERE CaraHitungID=$CaraHitungID";
		$this->pms->query($query);
	}
	function remove_CaraHitung($CaraHitungID){
		$query="DELETE FROM PA_M_CaraHitung WHERE CaraHitungID=$CaraHitungID";
		$this->pms->query($query);
	}
	//end of Counting Type

	// master of Formula
	function get_PCFormula_list($countType=0,$currentPost='',$start_date='',$end_date=''){
		$query = "SELECT f.*,ch.CaraHitung FROM PA_M_PCFormula f, PA_M_CaraHitung ch WHERE ch.CaraHitungID=f.CaraHitungID";
		if($countType!=0){
			$query.=" AND f.CaraHitungID=$countType";
		}
		if ($currentPost!=''){
			$subquery=" AND f.CaraHitungID <> $currentPost";
		}else{
			$subquery='';
		}
		if($start_date!='' and $end_date!=''){
			$query .= " and f.[BeginDate] <= '$start_date' and f.[EndDate] >= '$end_date'";
		}

		return $this->pms->query($query)->result();
	}


	function get_PCFormula_row($PCFormulaID){
		$query="SELECT f.*,ch.CaraHitung FROM PA_M_PCFormula f,PA_M_CaraHitung ch WHERE  ch.CaraHitungID=f.CaraHitungID and PCFormulaID = $PCFormulaID";
		return $this->pms->query($query)->row();
	}
	function add_PCFormula($CaraHitungID,$PCFormula,$Perception,$SkipConstancy,$Operator,$Notes,$start_date ,$end_date){
		$query = "INSERT INTO [PA_M_PCFormula]([CaraHitungID],[PCFormula],[Perception],[SkipConstancy],[Operator],[Notes],[BeginDate],[EndDate])VALUES($CaraHitungID,'$PCFormula','$Perception',$SkipConstancy,'$Operator','$Notes','$start_date','$end_date')";
		$this->pms->query($query);
	}
	function edit_PCFormula($PCFormulaID,$CaraHitungID,$PCFormula,$Perception,$SkipConstancy,$Operator,$Notes,$start_date ,$end_date){
		$query="UPDATE [PA_M_PCFormula]SET [CaraHitungID] = $CaraHitungID,[PCFormula] = '$PCFormula',[Perception] = '$Perception',[SkipConstancy] = $SkipConstancy,[Operator] = '$Operator',[Notes] = '$Notes',[EndDate] = '$end_date' WHERE PCFormulaID = $PCFormulaID";
		$this->pms->query($query);
	}
	function remove_PCFormula($PCFormulaID){
		$query="DELETE FROM PA_M_PCFormulaScore WHERE PCFormulaID = $PCFormulaID";
		$this->pms->query($query);
		$query="DELETE FROM PA_M_PCFormula WHERE PCFormulaID = $PCFormulaID";
		$this->pms->query($query);
	}
	function get_PCFormulaScore_list($PCFormulaID,$start_date='',$end_date=''){
		if($start_date=='' or $end_date==''){
			$query = "SELECT * FROM PA_M_PCFormulaScore WHERE PCFormulaID=$PCFormulaID";
		}else{
			$query = "SELECT * FROM PA_M_PCFormulaScore WHERE PCFormulaID and $PCFormulaID and [BeginDate] <= '$start_date' and [EndDate] >= '$end_date'";
		}
		return $this->pms->query($query)->result();
	}
	function get_PCFormulaScore_row($PCFormulaScoreID){
		$query="SELECT * FROM PA_M_PCFormulaScore WHERE PCFormulaScoreID = $PCFormulaScoreID";
		return $this->pms->query($query)->row();
	}

	function add_PCFormulaScore($PCFormulaID,$PCFormulaScore,$PCLow,$PCHigh,$Percentage,$start_date,$end_date){
		$query ="INSERT INTO [PA_M_PCFormulaScore]([PCFormulaID],[PCFormulaScore],[PCLow],[PCHigh],[Percentage],[BeginDate],[EndDate])VALUES($PCFormulaID,$PCFormulaScore,$PCLow,$PCHigh,";
		if($Percentage==''){
			$query .="NULL,";
		}else{
			$query .="$Percentage,";
		}
		$query .="'$start_date','$end_date')";
		$this->pms->query($query);
	}
	function edit_PCFormulaScore($PCFormulaScoreID,$PCFormulaID,$PCFormulaScore,$PCLow,$PCHigh,$Percentage,$start_date,$end_date){
		$query = "UPDATE [PA_M_PCFormulaScore]SET [PCFormulaID] = $PCFormulaID,[PCFormulaScore] = $PCFormulaScore,[PCLow] = $PCLow,[PCHigh] = $PCHigh";
		if($Percentage==''){
			$query.=",[Percentage] = NULL";
		}else{
			$query.=",[Percentage] = $Percentage";

		}
		$query .= ",[EndDate] = '$end_date' WHERE PCFormulaScoreID = $PCFormulaScoreID";
		$this->pms->query($query);
	}
	function remove_PCFormulaScore($PCFormulaScoreID){
		$query="DELETE FROM PA_M_PCFormulaScore WHERE PCFormulaScoreID = $PCFormulaScoreID";
		$this->pms->query($query);
	}
	// end of Formula


	/** INI BUAT BEHAVIOUR */
	/** dibuat oleh brente */
	// master of Aspect

	function get_aspect_list($start_date='',$end_date=''){
		if($start_date=='' or $end_date==''){
			$query = "SELECT * FROM tb_m_aspect";
		}else{
			$query = "SELECT * FROM tb_m_aspect WHERE [begin_date] <= '$start_date' and [end_date] >= '$end_date'";
		}

		return $this->pms->query($query)->result();
	}

	function add_aspect($label,$labelDesc,$start_date,$end_date, $created_by, $created_date){
		$query = "INSERT INTO [tb_m_aspect]([label],[description],[begin_date],[end_date], created_by, created_date)
				VALUES('$label','$labelDesc','$start_date','$end_date', '$created_by', '$created_date')";
		$this->pms->query($query);

		$audittrail_log=str_replace("'", "", $query);
		$query_log = "INSERT INTO [audittrail](source_ip, user_logon, event_time, audittrail_log)
					VALUES('$_SERVER[REMOTE_ADDR]','$created_by', '$created_date', '$audittrail_log')";

		$query = "SELECT TOP 1 * FROM tb_m_aspect ORDER BY aspect_id DESC";
		$this->pms->query($query_log);
		return $this->pms->query($query)->row();
	}

	function edit_aspect($aspect_id,$label,$description, $start_date,$end_date,$updated_by,$updated_date){
		$query="UPDATE [tb_m_aspect]SET [label] = '$label',[description] = '$description',begin_date='$start_date',[end_date] = '$end_date', updated_by='$updated_by', updated_date='$updated_date' WHERE aspect_id=$aspect_id";

		$audittrail_log=str_replace("'", "", $query);
		$query_log = "INSERT INTO [audittrail](source_ip, user_logon, event_time, audittrail_log)
					VALUES('$_SERVER[REMOTE_ADDR]','$updated_by', '$updated_date', '$audittrail_log')";

		$this->pms->query($query_log);
		$this->pms->query($query);
	}

	function edit_delimit_aspect($aspect_id,$end_date,$updated_by,$updated_date){
		$query="UPDATE [tb_m_aspect]SET [end_date] = '$end_date', updated_by='$updated_by', updated_date='$updated_date' WHERE aspect_id=$aspect_id";

		$audittrail_log=str_replace("'", "", $query);
		$query_log = "INSERT INTO [audittrail](source_ip, user_logon, event_time, audittrail_log)
					VALUES('$_SERVER[REMOTE_ADDR]','$updated_by', '$updated_date', '$audittrail_log')";

		$this->pms->query($query_log);
		$this->pms->query($query);
	}

	function get_aspect_row($aspect_id){
		$query = "SELECT * FROM tb_m_aspect WHERE aspect_id=$aspect_id ";
		return $this->pms->query($query)->row();
	}

	// end of master aspect

	// master of Layer

	function get_layer_list($start_date='',$end_date=''){
		if($start_date=='' or $end_date==''){
			$query = "SELECT * FROM tb_m_layer";
		}else{
			$query = "SELECT * FROM tb_m_layer WHERE [begin_date] <= '$start_date' and [end_date] >= '$end_date'";
		}

		return $this->pms->query($query)->result();
	}

	function add_layer($label,$esg,$labelDesc,$start_date,$end_date, $created_by, $created_date){
		$query = "INSERT INTO [tb_m_layer]([label],[esg],[description],[begin_date],[end_date], created_by, created_date)
				VALUES('$label','$esg','$labelDesc','$start_date','$end_date', '$created_by', '$created_date')";
		$this->pms->query($query);

		$audittrail_log=str_replace("'", "", $query);
		$query_log = "INSERT INTO [audittrail](source_ip, user_logon, event_time, audittrail_log)
					VALUES('$_SERVER[REMOTE_ADDR]','$created_by', '$created_date', '$audittrail_log')";

		$query = "SELECT TOP 1 * FROM tb_m_layer ORDER BY layer_id DESC";
		$this->pms->query($query_log);
		return $this->pms->query($query)->row();
	}

	function edit_layer($layer_id,$label,$esg,$description, $start_date,$end_date,$updated_by,$updated_date){
		$query="UPDATE [tb_m_layer]SET [label] = '$label',[esg] = '$esg',[description] = '$description',
				begin_date='$start_date',[end_date] = '$end_date', updated_by='$updated_by',
				updated_date='$updated_date' WHERE layer_id=$layer_id";

		$audittrail_log=str_replace("'", "", $query);
		$query_log = "INSERT INTO [audittrail](source_ip, user_logon, event_time, audittrail_log)
					VALUES('$_SERVER[REMOTE_ADDR]','$updated_by', '$updated_date', '$audittrail_log')";

		$this->pms->query($query_log);
		$this->pms->query($query);
	}

	function edit_delimit_layer($layer_id,$end_date,$updated_by,$updated_date){
		$query="UPDATE [tb_m_layer]SET [end_date] = '$end_date', updated_by='$updated_by',
				updated_date='$updated_date' WHERE layer_id=$layer_id";

		$audittrail_log=str_replace("'", "", $query);
		$query_log = "INSERT INTO [audittrail](source_ip, user_logon, event_time, audittrail_log)
					VALUES('$_SERVER[REMOTE_ADDR]','$updated_by', '$updated_date', '$audittrail_log')";

		$this->pms->query($query_log);
		$this->pms->query($query);
	}

	function get_layer_row($layer_id){
		$query = "SELECT * FROM tb_m_layer WHERE layer_id=$layer_id ";
		return $this->pms->query($query)->row();
	}

	// end of master layer




	// master of Scala

	function get_scala_list($start_date='',$end_date=''){
		if($start_date=='' or $end_date==''){
			$query = "SELECT * FROM tb_m_scala";
		}else{
			$query = "SELECT * FROM tb_m_scala WHERE [begin_date] <= '$start_date' and [end_date] >= '$end_date'";
		}

		return $this->pms->query($query)->result();
	}

	function add_scala($label,$value,$labelDesc,$start_date,$end_date, $created_by, $created_date){
		$query = "INSERT INTO [tb_m_scala]([label],[value],[description],[begin_date],[end_date], created_by, created_date)
				VALUES('$label','$value','$labelDesc','$start_date','$end_date', '$created_by', '$created_date')";
		$this->pms->query($query);

		$audittrail_log=str_replace("'", "", $query);
		$query_log = "INSERT INTO [audittrail](source_ip, user_logon, event_time, audittrail_log)
					VALUES('$_SERVER[REMOTE_ADDR]','$created_by', '$created_date', '$audittrail_log')";

		$query = "SELECT TOP 1 * FROM tb_m_scala ORDER BY scala_id DESC";
		$this->pms->query($query_log);
		return $this->pms->query($query)->row();
	}

	function edit_scala($scala_id,$label,$value,$description, $start_date,$end_date,$updated_by,$updated_date){
		$query="UPDATE [tb_m_scala]SET [label] = '$label',[value] = '$value',[description] = '$description',
			begin_date='$start_date',[end_date] = '$end_date', updated_by='$updated_by', updated_date='$updated_date'
			WHERE scala_id=$scala_id";

		$audittrail_log=str_replace("'", "", $query);
		$query_log = "INSERT INTO [audittrail](source_ip, user_logon, event_time, audittrail_log)
					VALUES('$_SERVER[REMOTE_ADDR]','$updated_by', '$updated_date', '$audittrail_log')";

		$this->pms->query($query_log);
		$this->pms->query($query);
	}

	function edit_delimit_scala($scala_id,$end_date,$updated_by,$updated_date){
		$query="UPDATE [tb_m_scala] SET [end_date] = '$end_date', updated_by='$updated_by',
				updated_date='$updated_date' WHERE scala_id=$scala_id";
		$audittrail_log=str_replace("'", "", $query);
		$query_log = "INSERT INTO [audittrail](source_ip, user_logon, event_time, audittrail_log)
					VALUES('$_SERVER[REMOTE_ADDR]','$updated_by', '$updated_date', '$audittrail_log')";

		$this->pms->query($query_log);
		$this->pms->query($query);
	}

	function get_scala_row($scala_id){
		$query = "SELECT * FROM tb_m_scala WHERE scala_id=$scala_id ";
		return $this->pms->query($query)->row();
	}

	// end of master Scala


	// master of Behaviour

	function get_behaviour_list($start_date='',$end_date=''){
		if($start_date=='' or $end_date==''){
			$query = "SELECT * FROM [tb_m_behaviour]";
		}else{
			$query = "SELECT * FROM [tb_m_behaviour] WHERE [begin_date] <= '$start_date' and [end_date] >= '$end_date'";
		}

		return $this->pms->query($query)->result();
	}

	function add_behaviour($label,$labelDesc,$start_date,$end_date, $created_by, $created_date){
		$query = "INSERT INTO [tb_m_behaviour]([label],[description],[begin_date],[end_date], created_by, created_date)
				VALUES('$label','$labelDesc','$start_date','$end_date', '$created_by', '$created_date')";
		$this->pms->query($query);

		$audittrail_log=str_replace("'", "", $query);
		$query_log = "INSERT INTO [audittrail](source_ip, user_logon, event_time, audittrail_log)
					VALUES('$_SERVER[REMOTE_ADDR]','$created_by', '$created_date', '$audittrail_log')";

		$query = "SELECT TOP 1 * FROM tb_m_behaviour ORDER BY behaviour_id DESC";
		$this->pms->query($query_log);
		return $this->pms->query($query)->row();
	}

	function edit_behaviour($behaviour_id,$label,$description, $start_date,$end_date,$updated_by,$updated_date){
		$query="UPDATE [tb_m_behaviour]SET [label] = '$label',[description] = '$description',
			begin_date='$start_date',[end_date] = '$end_date', updated_by='$updated_by', updated_date='$updated_date'
			WHERE behaviour_id=$behaviour_id";

		$audittrail_log=str_replace("'", "", $query);
		$query_log = "INSERT INTO [audittrail](source_ip, user_logon, event_time, audittrail_log)
					VALUES('$_SERVER[REMOTE_ADDR]','$updated_by', '$updated_date', '$audittrail_log')";

		$this->pms->query($query_log);
		$this->pms->query($query);
	}

	function edit_delimit_behaviour($behaviour_id,$end_date,$updated_by,$updated_date){
		$query="UPDATE [tb_m_behaviour] SET [end_date] = '$end_date', updated_by='$updated_by',
				updated_date='$updated_date' WHERE behaviour_id=$behaviour_id";
		$audittrail_log=str_replace("'", "", $query);
		$query_log = "INSERT INTO [audittrail](source_ip, user_logon, event_time, audittrail_log)
					VALUES('$_SERVER[REMOTE_ADDR]','$updated_by', '$updated_date', '$audittrail_log')";

		$this->pms->query($query_log);
		$this->pms->query($query);
	}

	function get_behaviour_row($behaviour_id){
		$query = "SELECT * FROM tb_m_behaviour WHERE behaviour_id	=$behaviour_id ";
		return $this->pms->query($query)->row();
	}

	// end of master Behaviour


	// master of Behaviour Group

	function get_behaviour_group_list($start_date='',$end_date=''){
		if($start_date=='' or $end_date==''){
			$query = "SELECT * FROM [tb_m_behaviour_group]";
		}else{
			$query = "SELECT * FROM [tb_m_behaviour_group] WHERE [begin_date] <= '$start_date' and [end_date] >= '$end_date'";
		}

		return $this->pms->query($query)->result();
	}

	function add_behaviour_group($label,$labelDesc,$start_date,$end_date, $created_by, $created_date){
		$query = "INSERT INTO [tb_m_behaviour_group]([label],[description],[begin_date],[end_date], created_by, created_date)
				VALUES('$label','$labelDesc','$start_date','$end_date', '$created_by', '$created_date')";
		$this->pms->query($query);

		$audittrail_log=str_replace("'", "", $query);
		$query_log = "INSERT INTO [audittrail](source_ip, user_logon, event_time, audittrail_log)
					VALUES('$_SERVER[REMOTE_ADDR]','$created_by', '$created_date', '$audittrail_log')";

		$query = "SELECT TOP 1 * FROM [tb_m_behaviour_group] ORDER BY [behaviour_group_id] DESC";
		$this->pms->query($query_log);
		return $this->pms->query($query)->row();
	}

	function edit_behaviour_group($behaviour_group_id,$label,$description, $start_date,$end_date,$updated_by,$updated_date){
		$query="UPDATE [tb_m_behaviour_group]SET [label] = '$label',[description] = '$description',
			begin_date='$start_date',[end_date] = '$end_date', updated_by='$updated_by', updated_date='$updated_date'
			WHERE behaviour_group_id=$behaviour_group_id";

		$audittrail_log=str_replace("'", "", $query);
		$query_log = "INSERT INTO [audittrail](source_ip, user_logon, event_time, audittrail_log)
					VALUES('$_SERVER[REMOTE_ADDR]','$updated_by', '$updated_date', '$audittrail_log')";

		$this->pms->query($query_log);
		$this->pms->query($query);
	}

	function edit_delimit_behaviour_group($behaviour_group_id,$end_date,$updated_by,$updated_date){
		$query="UPDATE [tb_m_behaviour_group] SET [end_date] = '$end_date', updated_by='$updated_by',
				updated_date='$updated_date' WHERE behaviour_group_id=$behaviour_group_id";
		$audittrail_log=str_replace("'", "", $query);
		$query_log = "INSERT INTO [audittrail](source_ip, user_logon, event_time, audittrail_log)
					VALUES('$_SERVER[REMOTE_ADDR]','$updated_by', '$updated_date', '$audittrail_log')";

		$this->pms->query($query_log);
		$this->pms->query($query);
	}

	function get_behaviour_group_row($behaviour_group_id){
		$query = "SELECT * FROM tb_m_behaviour_group WHERE behaviour_group_id	=$behaviour_group_id ";
		return $this->pms->query($query)->row();
	}

	// end of master Behaviour Group



	// master of Behaviour Group - Behaviour

	function get_cek_sort_number_behaviour($behaviour_group, $behaviour_id, $sort_number, $weight)
	{
		$query="SELECT count(*) as sort_number_total from tb_m_behaviour_group_behaviour
				WHERE behaviour_group_id=$behaviour_group AND behaviour_id=$behaviour_id
				AND sort_number=$sort_number AND weight=$weight";
		return $this->pms->query($query)->row()->sort_number_total;
	}


	function get_all_data_search($behaviour_group_id,$no_page, $perpage){

        if($no_page == 0){
            $first = 1;
            $last  = $perpage;
        }else{
            $first = $no_page + 1;
            $last  = $first + ($no_page -1);
        }
        $query="WITH TES AS (SELECT  a.*,ROW_NUMBER() OVER (ORDER BY behaviour_group_behaviour_id desc)  as RowNumber
        							FROM tb_m_behaviour_group_behaviour a)";
        $query.="SELECT c.label, a.sort_number, a.weight, a.description, a.begin_date,a.end_date,a.behaviour_group_behaviour_id,
        			b.behaviour_group_id, c.behaviour_id, RowNumber FROM TES a
					inner join tb_m_behaviour_group b
					on a.behaviour_group_id=b.behaviour_group_id inner join tb_m_behaviour c on c.behaviour_id=a.behaviour_id
					WHERE a.RowNumber BETWEEN $first AND $last ";
		if($behaviour_group_id!='')
		{
			$query.="and a.behaviour_group_id=$behaviour_group_id";
		}
        //echo $query;
        return $this->pms->query($query)->result();
    }

	function getAllData($no_page, $perpage){

        if($no_page == 0){
            $first = 1;
            $last  = $perpage;
        }else{
            $first = $no_page + 1;
            $last  = $first + ($no_page -1);
        }
        $query="WITH TES AS (SELECT  a.*,ROW_NUMBER() OVER (ORDER BY behaviour_group_behaviour_id desc)  as RowNumber
        							FROM tb_m_behaviour_group_behaviour a)";
        $query.="SELECT c.label, a.sort_number,a.description, a.begin_date,a.end_date,a.behaviour_group_behaviour_id,
        			b.behaviour_group_id, c.behaviour_id, RowNumber FROM TES a
					inner join tb_m_behaviour_group b
					on a.behaviour_group_id=b.behaviour_group_id inner join tb_m_behaviour c on c.behaviour_id=a.behaviour_id
					WHERE a.RowNumber BETWEEN $first AND $last";
        return $this->pms->query($query)->result();
    }

    function getTotalRowAllData(){
    	$query="SELECT count(*) as row FROM tb_m_behaviour_group_behaviour";
	 	$query_exec=$this->pms->query($query)->row_array();
	 return $query_exec['row'];
	}

	function get_behaviour_group_behaviour_list($start_date='',$end_date=''){
		if($start_date=='' or $end_date==''){
			$query = "SELECT c.label, a.sort_number, a.description, a.begin_date, a.end_date, a.behaviour_group_behaviour_id,
					b.behaviour_group_id, c.behaviour_id from tb_m_behaviour_group_behaviour a inner join tb_m_behaviour_group b
					on a.behaviour_group_id=b.behaviour_group_id inner join tb_m_behaviour c on c.behaviour_id=a.behaviour_id";
		}else{
			$query = "SELECT c.label, a.sort_number, a.description, a.begin_date, a.end_date, a.behaviour_group_behaviour_id,
					b.behaviour_group_id, c.behaviour_id from tb_m_behaviour_group_behaviour a inner join tb_m_behaviour_group b
					on a.behaviour_group_id=b.behaviour_group_id inner join tb_m_behaviour c on c.behaviour_id=a.behaviour_id WHERE
					[a.begin_date] <= '$start_date' and [a.end_date] >= '$end_date'";
		}

		return $this->pms->query($query)->result();
	}

	function add_behaviour_group_behaviour($behaviour_group,$behaviour,$sort, $weight,$labelDesc,$start_date,$end_date, $created_by, $created_date){
		$query = "INSERT INTO [tb_m_behaviour_group_behaviour]([behaviour_group_id],[behaviour_id],[sort_number],[weight],[description],[begin_date],[end_date], created_by, created_date)
				VALUES('$behaviour_group','$behaviour','$sort',$weight,'$labelDesc','$start_date','$end_date', '$created_by', '$created_date')";
		$this->pms->query($query);

		$audittrail_log=str_replace("'", "", $query);
		$query_log = "INSERT INTO [audittrail](source_ip, user_logon, event_time, audittrail_log)
					VALUES('$_SERVER[REMOTE_ADDR]','$created_by', '$created_date', '$audittrail_log')";

		$query = "SELECT TOP 1 * FROM [tb_m_behaviour_group_behaviour] ORDER BY [behaviour_group_behaviour_id] DESC";
		$this->pms->query($query_log);
		return $this->pms->query($query)->row();
	}

	function edit_behaviour_group_behaviour($behaviour_group_behaviour_id,$behaviour_group,$behaviour,$sort,$weight,$description, $start_date,$end_date,$updated_by,$updated_date){
		$query="UPDATE [tb_m_behaviour_group_behaviour]SET behaviour_group_id='$behaviour_group', behaviour_id='$behaviour', [sort_number] = '$sort',[description] = '$description',
			weight='$weight', begin_date='$start_date',[end_date] = '$end_date', updated_by='$updated_by', updated_date='$updated_date'
			WHERE behaviour_group_behaviour_id=$behaviour_group_behaviour_id";

		$audittrail_log=str_replace("'", "", $query);
		$query_log = "INSERT INTO [audittrail](source_ip, user_logon, event_time, audittrail_log)
					VALUES('$_SERVER[REMOTE_ADDR]','$updated_by', '$updated_date', '$audittrail_log')";

		$this->pms->query($query_log);
		$this->pms->query($query);
	}

	function edit_delimit_behaviour_group_behaviour($behaviour_group_behaviour_id,$end_date,$updated_by,$updated_date){
		$query="UPDATE [tb_m_behaviour_group_behaviour] SET [end_date] = '$end_date', updated_by='$updated_by',
				updated_date='$updated_date' WHERE behaviour_group_behaviour_id=$behaviour_group_behaviour_id";
		$audittrail_log=str_replace("'", "", $query);
		$query_log = "INSERT INTO [audittrail](source_ip, user_logon, event_time, audittrail_log)
					VALUES('$_SERVER[REMOTE_ADDR]','$updated_by', '$updated_date', '$audittrail_log')";

		$this->pms->query($query_log);
		$this->pms->query($query);
	}

	function get_behaviour_group_behaviour_row($behaviour_group_behaviour_id){
		$query = "SELECT * FROM tb_m_behaviour_group_behaviour WHERE behaviour_group_behaviour_id	=$behaviour_group_behaviour_id ";
		return $this->pms->query($query)->row();
	}

	// end of master Behaviour Group - Behaviour



	// master of Behaviour Group - Scala

	function get_cek_sort_number_scala($behaviour_group_scala_id,$behaviour_group, $scala_id, $sort_number)
	{
		$query="SELECT count(*) as sort_number_total from tb_m_behaviour_group_scala
				WHERE behaviour_group_id=$behaviour_group AND scala_id=$scala_id
				AND sort_number=$sort_number AND behaviour_group_scala_id=$behaviour_group_scala_id";
		return $this->pms->query($query)->row()->sort_number_total;
	}

	function get_cek_sort_number_scala_add($behaviour_group, $scala_id, $sort_number)
	{
		$query="SELECT count(*) as sort_number_total from tb_m_behaviour_group_scala
				WHERE behaviour_group_id=$behaviour_group AND scala_id=$scala_id
				AND sort_number=$sort_number ";
		return $this->pms->query($query)->row()->sort_number_total;
	}

	function get_data_search_behaviour_group_scala($behaviour_group_id,$no_page, $perpage){

        if($no_page == 0){
            $first = 1;
            $last  = $perpage;
        }else{
            $first = $no_page + 1;
            $last  = $first + ($no_page -1);
        }
        $query="WITH TES AS (SELECT  a.*,ROW_NUMBER() OVER (ORDER BY behaviour_group_scala_id desc)  as RowNumber
        							FROM tb_m_behaviour_group_scala a)";
        $query.="SELECT c.label, a.sort_number,a.description,
				a.begin_date,a.end_date,a.behaviour_group_scala_id, b.behaviour_group_id, c.scala_id, RowNumber
				FROM TES a inner join tb_m_behaviour_group b on a.behaviour_group_id=b.behaviour_group_id inner join
				tb_m_scala c on c.scala_id=a.scala_id";
		if($behaviour_group_id!='')
		{
			$query.=" WHERE a.behaviour_group_id=$behaviour_group_id";
		}
        //echo $query;
        return $this->pms->query($query)->result();
    }

	function get_all_data_behaviour_group_scala($no_page, $perpage){

        if($no_page == 0){
            $first = 1;
            $last  = $perpage;
        }else{
            $first = $no_page + 1;
            $last  = $first + ($no_page -1);
        }
        $query="WITH TES AS (SELECT  a.*,ROW_NUMBER() OVER (ORDER BY behaviour_group_scala_id desc)  as RowNumber
        							FROM tb_m_behaviour_group_scala a)";
        $query.="SELECT c.label, a.sort_number,a.description,
				a.begin_date,a.end_date,a.behaviour_group_scala_id, b.behaviour_group_id, c.scala_id, RowNumber
				FROM TES a inner join tb_m_behaviour_group b on a.behaviour_group_id=b.behaviour_group_id inner join
				tb_m_scala c on c.scala_id=a.scala_id WHERE a.RowNumber BETWEEN $first AND $last ";
        //echo $query;
        return $this->pms->query($query)->result();
    }

    function get_total_row_data_behaviour_group_scala(){
    	$query="SELECT count(*) as row FROM tb_m_behaviour_group_scala";
	 	$query_exec=$this->pms->query($query)->row_array();
	 return $query_exec['row'];
	}

	function get_behaviour_group_scala_list($start_date='',$end_date=''){
		if($start_date=='' or $end_date==''){
			$query = "SELECT c.label, a.sort_number, a.description, a.begin_date, a.end_date, a.behaviour_group_scala_id,
					b.behaviour_group_id, c.scala_id from tb_m_behaviour_group_scala a inner join tb_m_behaviour_group b
					on a.behaviour_group_id=b.behaviour_group_id inner join tb_m_scala c on c.scala_id=a.scala_id";
		}else{
			$query = "SELECT c.label, a.sort_number, a.description, a.begin_date, a.end_date, a.behaviour_group_scala_id,
					b.behaviour_group_id, c.scala_id from tb_m_behaviour_group_scala a inner join tb_m_behaviour_group b
					on a.behaviour_group_id=b.behaviour_group_id inner join tb_m_scala c on c.scala_id=a.scala_id WHERE
					a.begin_date <= '$start_date' and a.end_date >= '$end_date'";
		}

		return $this->pms->query($query)->result();
	}

	function add_behaviour_group_scala($behaviour_group,$scala,$sort,$labelDesc,$start_date,$end_date, $created_by, $created_date){
		$query = "INSERT INTO [tb_m_behaviour_group_scala]([behaviour_group_id],[scala_id],[sort_number],[description],[begin_date],[end_date], created_by, created_date)
				VALUES('$behaviour_group','$scala','$sort','$labelDesc','$start_date','$end_date', '$created_by', '$created_date')";
		$this->pms->query($query);

		$audittrail_log=str_replace("'", "", $query);
		$query_log = "INSERT INTO [audittrail](source_ip, user_logon, event_time, audittrail_log)
					VALUES('$_SERVER[REMOTE_ADDR]','$created_by', '$created_date', '$audittrail_log')";

		$query = "SELECT TOP 1 * FROM [tb_m_behaviour_group_scala] ORDER BY [behaviour_group_scala_id] DESC";
		$this->pms->query($query_log);
		return $this->pms->query($query)->row();
	}

	function edit_behaviour_group_scala($behaviour_group_scala_id,$behaviour_group,$scala,$sort,$description, $start_date,$end_date,$updated_by,$updated_date){
		$query="UPDATE [tb_m_behaviour_group_scala]SET behaviour_group_id='$behaviour_group', scala_id='$scala', [sort_number] = '$sort',[description] = '$description',
			begin_date='$start_date',[end_date] = '$end_date', updated_by='$updated_by', updated_date='$updated_date'
			WHERE behaviour_group_scala_id=$behaviour_group_scala_id";

		$audittrail_log=str_replace("'", "", $query);
		$query_log = "INSERT INTO [audittrail](source_ip, user_logon, event_time, audittrail_log)
					VALUES('$_SERVER[REMOTE_ADDR]','$updated_by', '$updated_date', '$audittrail_log')";

		$this->pms->query($query_log);
		$this->pms->query($query);
	}

	function edit_delimit_behaviour_group_scala($behaviour_group_scala_id,$end_date,$updated_by,$updated_date){
		$query="UPDATE [tb_m_behaviour_group_scala] SET [end_date] = '$end_date', updated_by='$updated_by',
				updated_date='$updated_date' WHERE behaviour_group_scala_id=$behaviour_group_scala_id";
		$audittrail_log=str_replace("'", "", $query);
		$query_log = "INSERT INTO [audittrail](source_ip, user_logon, event_time, audittrail_log)
					VALUES('$_SERVER[REMOTE_ADDR]','$updated_by', '$updated_date', '$audittrail_log')";

		$this->pms->query($query_log);
		$this->pms->query($query);
	}

	function get_behaviour_group_scala_row($behaviour_group_scala_id){
		$query = "SELECT * FROM tb_m_behaviour_group_scala WHERE behaviour_group_scala_id=$behaviour_group_scala_id ";
		return $this->pms->query($query)->row();
	}

	// end of master Behaviour Group - Scala


	// master of Aspect Setting

	function get_data_search_aspect_setting($organization_id,$is_sap,$no_page, $perpage){

        if($no_page == 0){
            $first = 1;
            $last  = $perpage;
        }else{
            $first = $no_page + 1;
            $last  = $first + ($no_page -1);
        }
        $query="WITH TES AS (SELECT  a.*,ROW_NUMBER() OVER (ORDER BY aspect_setting_id desc)  as RowNumber
        							FROM tb_m_aspect_setting a)";
        $query.="SELECT b.label as aspect, a.org_name_detail as org_name_full, a.aspect_id as aspect_id, '' as behaviour_group, a.frequency,a.percentage, a.begin_date,a.end_date,
        		a.aspect_setting_id,e.label as layer, RowNumber FROM TES a inner join tb_m_aspect b on a.aspect_id=b.aspect_id inner join Core_M_Organization_";
		if($is_sap)
		{
			$query.= "SAP";
		}
		else
		{
			$query.= "nonSAP";
		}

		$query.= " d on d.OrganizationID=a.organization_id
				inner join tb_m_layer e on e.layer_id=a.layer_id
	WHERE a.aspect_id=1 AND EndDate >=GETDATE() AND a.RowNumber BETWEEN $first AND $last ";

		$query.="UNION ALL ";

		$query.= "SELECT b.label as aspect,a.org_name_detail as org_name_full,  a.aspect_id as aspect_id, c.label as behaviour_group,
				a.frequency,a.percentage, a.begin_date,a.end_date, a.aspect_setting_id,e.label as layer, RowNumber
				FROM TES a inner join tb_m_aspect b on a.aspect_id=b.aspect_id inner join tb_m_behaviour_group c
				 on c.behaviour_group_id=a.behaviour_group_id inner join Core_M_Organization_";
		if($is_sap)
		{
			$query.= "SAP";
		}
		else
		{
			$query.= "nonSAP";
		}

		$query.= " d on d.OrganizationID=a.organization_id
					inner join tb_m_layer e on e.layer_id=a.layer_id
					WHERE EndDate >=GETDATE() AND a.RowNumber BETWEEN $first AND $last ";

		if($organization_id!='')
		{
			$query.="and a.organization_id=$organization_id";
		}
        //echo $query;
        return $this->pms->query($query)->result();
    }


	function get_all_data_aspect_setting($no_page, $perpage, $is_sap){

        if($no_page == 0){
            $first = 1;
            $last  = $perpage;
        }else{
            $first = $no_page + 1;
            $last  = $first + ($no_page -1);
        }
        $query="WITH TES AS (SELECT a.*,ROW_NUMBER() OVER (ORDER BY aspect_setting_id desc) as RowNumber FROM
					tb_m_aspect_setting a)";
        $query.="SELECT b.label as aspect,a.org_name_detail as org_name_full,a.aspect_id as aspect_id, '' as behaviour_group, a.frequency,a.percentage, a.begin_date,a.end_date,
        		a.aspect_setting_id,e.label as layer, RowNumber FROM TES a inner join tb_m_aspect b on a.aspect_id=b.aspect_id
        		inner join Core_M_Organization_";
		if($is_sap)
		{
			$query.= "SAP";
		}
		else
		{
			$query.= "nonSAP";
		}
		$query.= " d on d.OrganizationID=a.organization_id
					inner join tb_m_layer e on e.layer_id=a.layer_id
					WHERE a.aspect_id=1 AND EndDate >=GETDATE() AND a.RowNumber BETWEEN $first AND $last ";
		$query.="UNION ALL ";
		$query.="SELECT b.label as aspect,a.org_name_detail as org_name_full, a.aspect_id as aspect_id, c.label as behaviour_group,
				a.frequency,a.percentage, a.begin_date,a.end_date, a.aspect_setting_id,e.label as layer, RowNumber
				FROM TES a inner join tb_m_aspect b on a.aspect_id=b.aspect_id inner join tb_m_behaviour_group c
				 on c.behaviour_group_id=a.behaviour_group_id inner join Core_M_Organization_";
		if($is_sap)
		{
			$query.= "SAP";
		}
		else
		{
			$query.= "nonSAP";
		}
		$query.= " d on d.OrganizationID=a.organization_id
					inner join tb_m_layer e on e.layer_id=a.layer_id
		WHERE EndDate >=GETDATE() AND a.RowNumber BETWEEN $first AND $last ";
        //echo $query;
        return $this->pms->query($query)->result();
    }

    function get_total_row_data_aspect_setting(){
    	$query="SELECT count(*) as row FROM tb_m_aspect_setting";
	 	$query_exec=$this->pms->query($query)->row_array();
	 return $query_exec['row'];
	}

	function add_aspect_setting($organization_id,$begin_date_org,$end_date_org,$aspect_id,$behaviour_group_id,$frequency,$percentage,$start_date,$end_date, $created_by, $created_date,$organization_name,$layer){
		$query = "INSERT INTO [tb_m_aspect_setting]([organization_id],[org_begin_date],[org_end_date],[aspect_id],[behaviour_group_id],
				[frequency],[percentage],[begin_date],[end_date], created_by, created_date,[org_name_detail],[layer_id])
				VALUES('$organization_id','$begin_date_org','$end_date_org','$aspect_id',
					".($behaviour_group_id? "'$behaviour_group_id'": 'NULL'). ",".($frequency? "'$frequency'": 'NULL'). ",
					'$percentage','$start_date','$end_date', '$created_by', '$created_date','$organization_name',".($layer? "'$layer'": '1'). ")";
		$this->pms->query($query);

		$audittrail_log=str_replace("'", "", $query);
		$query_log = "INSERT INTO [audittrail](source_ip, user_logon, event_time, audittrail_log)
					VALUES('$_SERVER[REMOTE_ADDR]','$created_by', '$created_date', '$audittrail_log')";

		$query = "SELECT TOP 1 * FROM [tb_m_aspect_setting] ORDER BY [aspect_setting_id] DESC";
		$this->pms->query($query_log);
		return $this->pms->query($query)->row();
	}

	function edit_aspect_setting($aspect_setting_id,$organization_id,$organization_name,$begin_date_org,$end_date_org,$aspect_id,$behaviour_group_id,$frequency,$percentage,$start_date,$end_date,$updated_by,$updated_date,$layer){
		$query="UPDATE [tb_m_aspect_setting] SET organization_id='$organization_id', org_name_detail='$organization_name', org_begin_date='$begin_date_org', [org_end_date] = '$end_date_org',
				[aspect_id] = '$aspect_id',[behaviour_group_id] = ".($behaviour_group_id? "'$behaviour_group_id'": 'NULL'). ",[frequency] = ".($frequency? "'$frequency'": 'NULL'). ",[percentage] = '$percentage',begin_date='$start_date',
				[end_date] = '$end_date', updated_by='$updated_by', updated_date='$updated_date', layer_id=".($layer? "'$layer'": '1'). "
			WHERE aspect_setting_id=$aspect_setting_id";
		//echo $query;
		$audittrail_log=str_replace("'", "", $query);
		$query_log = "INSERT INTO [audittrail](source_ip, user_logon, event_time, audittrail_log)
					VALUES('$_SERVER[REMOTE_ADDR]','$updated_by', '$updated_date', '$audittrail_log')";

		$this->pms->query($query_log);
		$this->pms->query($query);
	}

	function edit_aspect_setting_without_org($aspect_setting_id,$aspect_id,$behaviour_group_id,$frequency,$percentage,$start_date,$end_date,$updated_by,$updated_date,$layer){
		$query="UPDATE [tb_m_aspect_setting] SET [aspect_id] = '$aspect_id',[behaviour_group_id] = ".($behaviour_group_id? "'$behaviour_group_id'": 'NULL'). ",
				[frequency] = ".($frequency? "'$frequency'": 'NULL'). ",[percentage] = '$percentage',begin_date='$start_date',
				[end_date] = '$end_date', updated_by='$updated_by', updated_date='$updated_date', layer_id=".($layer? "'$layer'": '1'). "
			WHERE aspect_setting_id=$aspect_setting_id";

		$audittrail_log=str_replace("'", "", $query);
		$query_log = "INSERT INTO [audittrail](source_ip, user_logon, event_time, audittrail_log)
					VALUES('$_SERVER[REMOTE_ADDR]','$updated_by', '$updated_date', '$audittrail_log')";

		$this->pms->query($query_log);
		$this->pms->query($query);
	}

	function edit_delimit_aspect_setting($aspect_setting_id,$end_date,$updated_by,$updated_date){
		$query="UPDATE [tb_m_aspect_setting] SET [end_date] = '$end_date', updated_by='$updated_by',
				updated_date='$updated_date' WHERE aspect_setting_id=$aspect_setting_id";
		$audittrail_log=str_replace("'", "", $query);
		$query_log = "INSERT INTO [audittrail](source_ip, user_logon, event_time, audittrail_log)
					VALUES('$_SERVER[REMOTE_ADDR]','$updated_by', '$updated_date', '$audittrail_log')";

		$this->pms->query($query_log);
		$this->pms->query($query);
	}

	function get_cek_organization_aspect_behaviour_group($organization_id,$org_begin_date,$org_end_date, $aspect, $behaviour_group)
	{
		$query="SELECT count(*) as total_cek_org from tb_m_aspect_setting
				WHERE organization_id=$organization_id AND org_begin_date='$org_begin_date' AND org_end_date='$org_end_date' AND aspect_id=$aspect";
		if($behaviour_group!='')
		{
				$query .="AND behaviour_group_id=$behaviour_group";
		}

		return $this->pms->query($query)->row()->total_cek_org;
	}

	function get_aspect_setting_row($aspect_setting_id){
		$query = "SELECT * FROM tb_m_aspect_setting WHERE aspect_setting_id=$aspect_setting_id ";
		return $this->pms->query($query)->row();
	}

	// end of master Aspect Setting

}
?>
