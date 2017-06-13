<?php
class Behaviour_model extends Model{
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

	function count_setting($organization_id,$begin_date,$end_date)
	{
		$query="SELECT COUNT(*) as val FROM tb_m_aspect_setting WHERE organization_id=$organization_id and aspect_id!=1 AND  ((begin_date >= '$begin_date' AND end_date <='$end_date') OR 
 								(end_date >= '$end_date' AND end_date <= '$end_date') OR 
 								(begin_date >= '$begin_date' AND begin_date <='$end_date' ) OR
 								(begin_date <= '$begin_date' AND end_date >= '$end_date'))";
		
		return $this->pms->query($query)->row()->val;
	}

	function get_setting($is_sap,$organization_id,$begin_date,$end_date)
	{
		$count_setting = $this->count_setting($organization_id,$begin_date,$end_date);
		if($count_setting==0)
		{
			if ($is_sap) {
 				$table = "Core_M_Organization_SAP";
	 		} else {
	 			$table = "Core_M_Organization_nonSAP";
	 		}

	 		if ($begin_date == '') {
	 			$begin_date = date('Y-m-d');
	 		}

	 		if ($end_date == '') {
	 			$end_date = date('Y-m-d');
	 		}
			$query = "SELECT TOP 1 *
 							FROM $table
 							WHERE OrganizationID = $organization_id  AND
 							 	((BeginDate >= '$begin_date' AND EndDate <='$end_date') OR 
 								(EndDate >= '$begin_date' AND EndDate <= '$end_date') OR 
 								(BeginDate >= '$begin_date' AND BeginDate <='$end_date' ) OR
 								(BeginDate <= '$begin_date' AND EndDate >= '$end_date'))
							ORDER BY EndDate DESC, BeginDate DESC";
			//echo $query;
			$organization_parent = $this->pms->query($query)->row()->OrganizationParent;
			$result=$this->get_setting($is_sap,$organization_parent,$begin_date,$end_date);
		}
		else
		{
			$query="SELECT * FROM tb_m_aspect_setting WHERE organization_id=$organization_id and aspect_id!=1 AND ((begin_date >= '$begin_date' AND end_date <='$end_date') OR 
 								(end_date >= '$end_date' AND end_date <= '$end_date') OR 
 								(begin_date >= '$begin_date' AND begin_date <='$end_date' ) OR
 								(begin_date <= '$begin_date' AND end_date >= '$end_date'))";
			
			return $this->pms->query($query)->result();
		}

		return $result;
	}

	function get_Position_row($PositionID,$isSAP){
		if($isSAP){
			$table="SAP";
		}else{
			$table="nonSAP";
		}
		$query="SELECT P.*, O.OrganizationName,O.OrganizationParent FROM Core_M_Position_$table P, Core_M_Organization_$table O WHERE O.OrganizationID=P.OrganizationID AND P.PositionID=$PositionID";
		return $this->pms->query($query)->row();
	}

	function get_ActivePeriode_row()
	{
		$query ="SELECT TOP 1 * 
						FROM Core_M_PeriodePM 
						WHERE [BeginDate] <= '".date('Y-m-d')."' and [EndDate] >= '".date('Y-m-d')."' ORDER BY PeriodePMID DESC";
		return $this->pms->query($query)->row();
	}
	function get_User_row($NIK)
	{
		
		$query = "SELECT u.*,r.Role,r.RoleID 
							FROM Core_M_User u, Core_M_Role r 
							WHERE u.NIK = '$NIK' and r.RoleID=u.RoleID";

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
		$query = "SELECT * FROM Core_V_Holder_$table 
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

	
	function get_subordinate_list($isSAP,$PositionID,$Begindate,$Enddate)
	{
		$query = "exec [dbo].[DirectSubordinateException] $PositionID, $isSAP, '$Begindate', '$Enddate'";
		return $this->pms->query($query)->result();
	}


	//ASPECT SETTING DIRI SENDIRI

	function get_aspect_setting_data_row($organization_id, $org_begin_date, $org_end_date)
	{
		$query = "select * from tb_m_aspect_setting A inner join tb_m_aspect B
		on A.aspect_id=B.aspect_id where A.organization_id=$organization_id  AND A.aspect_id!=1 
		and (( A.org_begin_date<= '$org_begin_date' AND A.org_end_date>='$org_end_date') OR  
			A.org_begin_date<= GETDATE() AND A.org_end_date>='$org_end_date')";
		return $this->pms->query($query)->row();
	}

	function get_aspect_setting_data_list_by_id($aspect_setting_id)
	{
		$query = "select * from tb_m_aspect_setting A inner join tb_m_aspect B
		on A.aspect_id=B.aspect_id 
		inner join tb_m_layer C on C.layer_id=A.layer_id";
		$query.= " where A.aspect_setting_id=$aspect_setting_id AND A.aspect_id!=1";
		return $this->pms->query($query)->result();
	}

	function get_aspect_setting_data_list_by_row($aspect_setting_id)
	{
		$query = "select * from tb_m_aspect_setting A inner join tb_m_aspect B
		on A.aspect_id=B.aspect_id 
		inner join tb_m_layer C on C.layer_id=A.layer_id";
		$query.= " where A.aspect_setting_id=$aspect_setting_id AND A.aspect_id!=1";

		return $this->pms->query($query)->row();
	}

	function get_aspect_setting_data_list_by_id_and_periode($aspect_setting_id,$bulan_terkecil)
	{
		$query = "select * from tb_m_aspect_setting A inner join tb_m_aspect B
		on A.aspect_id=B.aspect_id 
		inner join tb_m_layer C on C.layer_id=A.layer_id";
		$query.= " where A.aspect_setting_id=$aspect_setting_id AND A.aspect_id!=1";
		$query.= " AND A.frequency like '%$bulan_terkecil%'";
		return $this->pms->query($query)->result();
	}



	function get_aspect_setting_data_list($organization_id, $begin_date, $end_date)
	{
		$query = "select * from tb_m_aspect_setting A inner join tb_m_aspect B
		on A.aspect_id=B.aspect_id 
		inner join tb_m_layer C on C.layer_id=A.layer_id";
		$query.= " where A.organization_id=$organization_id AND A.aspect_id!=1 
		and (( A.begin_date<= '$begin_date' AND A.end_date>='$end_date') OR  A.begin_date<= GETDATE() AND A.end_date>='$end_date')
		order by A.aspect_setting_id asc";
		//echo $query;
		return $this->pms->query($query)->result();
	}

	function get_aspect_setting_data_count($organization_id, $begin_date, $end_date)
	{
		$query = "select COUNT(*) as total_aspect_setting from tb_m_aspect_setting A inner join tb_m_aspect B
		on A.aspect_id=B.aspect_id";
		//$query .= " LEFT OUTER JOIN tb_non_performance C on A.aspect_setting_id=C.aspect_setting_id";
		$query .= " where A.organization_id=$organization_id  AND A.aspect_id!=1 
		and (( A.begin_date<= '$begin_date' AND A.end_date>='$end_date') OR  A.begin_date<= GETDATE() AND A.end_date>='$end_date')";
		//echo $query;
		return $this->pms->query($query)->row();
	}

	function get_behaviour_group_behaviour_by_id($behaviour_group_id, $begin_date, $end_date)
	{
		$query ="select * from tb_m_behaviour_group_behaviour A
				inner join tb_m_behaviour B on A.behaviour_id=B.behaviour_id
				where behaviour_group_id=$behaviour_group_id 
				AND (( A.begin_date<= '$begin_date' AND A.end_date>='$end_date') OR  
				A.begin_date<= GETDATE() AND A.end_date>='$end_date')
				order by sort_number";
		return $this->pms->query($query)->result();
	}

	function get_behaviour_group_scala_by_id($behaviour_group_id, $begin_date, $end_date)
	{
		$query ="select * from tb_m_behaviour_group_scala A
				inner join tb_m_scala B on A.scala_id=B.scala_id
				where behaviour_group_id=$behaviour_group_id 
				AND (( A.begin_date<= '$begin_date' AND A.end_date>='$end_date') OR  
				A.begin_date<= GETDATE() AND A.end_date>='$end_date')
				order by sort_number";
		return $this->pms->query($query)->result();
	}

	function get_answer_performance($periode,$nik)
	{
		$query="select A.header_id, A.achievement_id, B.periode, A.achievement, A.behaviour_id, B.nik,B.approved_by, A.notes
				from bhv_t_achv A 
 				left join bhv_t_header B on B.header_id=A.header_id
 				WHERE B.periode='$periode' AND B.nik='$nik'";
 		return $this->pms->query($query)->result();
	}

	function get_answer_performance_by_year($periode,$nik)
	{
		$query="select A.achievement_id, B.periode, A.achievement, A.behaviour_id, B.nik,B.approved_by, A.notes
				from bhv_t_achv A 
 				left join bhv_t_header B on B.header_id=A.header_id
 				WHERE RIGHT(B.periode,4)='$periode' AND B.nik='$nik'  order by B.header_id desc";
 		return $this->pms->query($query)->result();
	}

	function get_behaviour_group_behaviour_by_non_performance_id($header_id,$behaviour_group_id)
	{
		$query ="SELECT * from tb_m_behaviour_group_behaviour A inner join tb_m_behaviour B on A.behaviour_id=B.behaviour_id 
				inner join bhv_t_achv C on A.behaviour_id=C.behaviour_id
				inner join bhv_t_header D on D.header_id=C.header_id
				inner join tb_m_behaviour_group_scala E on E.behaviour_group_id=A.behaviour_group_id
				where A.behaviour_group_id=$behaviour_group_id AND C.header_id=$header_id
				order by A.sort_number";
		//echo $query;
		return $this->pms->query($query)->result();
	}

	function get_count_non_performance($aspect_setting_id,$pilih_bulan, $nik)
	{
		$query="select COUNT(*) as total_t_header from bhv_t_header 
				where aspect_setting_id = $aspect_setting_id AND periode='$pilih_bulan' AND nik='$nik' ";
				
		return $this->pms->query($query)->row();
	}

	function get_non_performance_id($aspect_setting_id,$pilih_bulan, $nik)
	{
		$query="select header_id from bhv_t_header 
				where aspect_setting_id = $aspect_setting_id AND periode='$pilih_bulan' AND nik='$nik' ";
		return $this->pms->query($query)->row();
	}

	function get_header_row($aspect_setting_id,$pilih_bulan, $nik)
	{
		$query="select * from bhv_t_header 
				where aspect_setting_id = $aspect_setting_id AND periode='$pilih_bulan' AND nik='$nik' ";
		return $this->pms->query($query)->row();
	}

	function get_total_non_performance_holder_aspect_setting($aspect_setting_id,$nik,$pilih_bulan)
	{
		$query="select COUNT(*) as total_holder_aspect_setting from bhv_t_header A inner join tb_m_aspect_setting B on A.aspect_setting_id=B.aspect_setting_id
where A.aspect_setting_id= $aspect_setting_id AND nik='$nik' AND SUBSTRING(periode,1,6)='$pilih_bulan'";
		return $this->pms->query($query)->row();	
	}

	function get_non_performance_holder_aspect_setting($aspect_setting_id,$nik,$pilih_bulan)
	{
		$query="select * from bhv_t_header A inner join tb_m_aspect_setting B on A.aspect_setting_id=B.aspect_setting_id
where A.aspect_setting_id= $aspect_setting_id AND nik='$nik' AND SUBSTRING(periode,1,6)='$pilih_bulan'";
		return $this->pms->query($query)->result();	
	}

	function get_non_performance_holder_aspect_setting_row($aspect_setting_id,$nik,$pilih_bulan)
	{
		$query="select * from bhv_t_header A inner join tb_m_aspect_setting B on A.aspect_setting_id=B.aspect_setting_id
where A.aspect_setting_id= $aspect_setting_id AND nik='$nik' AND SUBSTRING(periode,1,6)='$pilih_bulan'";
		return $this->pms->query($query)->row();	
	}


	function get_non_performance_holder_aspect_setting_behaviour_list($aspect_setting_id,$nik,$pilih_bulan,$behaviour_id)
	{
		$query="select * from bhv_t_header A inner join tb_m_aspect_setting B on A.aspect_setting_id=B.aspect_setting_id 
				inner join tb_m_behaviour_group_behaviour C on B.behaviour_group_id=C.behaviour_group_id
				where A.aspect_setting_id=$aspect_setting_id AND nik='$nik' AND SUBSTRING(periode,1,6)='$pilih_bulan'
				and C.behaviour_id=$behaviour_id";
		return $this->pms->query($query)->result();	
	}

	function get_non_performance_holder_aspect_setting_behaviour_row_performance_id($nik,$pilih_bulan)
	{
		$query="select header_id from bhv_t_header 
				where  nik='$nik' AND periode='$pilih_bulan'";
		//echo $query;
		return $this->pms->query($query)->row();	
	}
	
	function get_status_flag($header_id)
	{
		$query="select status from bhv_t_header
                                where  header_id=$header_id";
                //echo $query;
                return $this->pms->query($query)->row();
	}

	function get_non_performance_id_achieve($nik,$pilih_bulan,$behaviour_id)
	{
		$query="select achievement_id from bhv_t_achv A inner join bhv_t_header B
				on A.header_id=B.header_id where nik='$nik' AND periode='$pilih_bulan' AND
				A.behaviour_id=$behaviour_id";
		return $this->pms->query($query)->row();
	}

	function get_total_non_performance_id_achieve($nik,$pilih_bulan,$behaviour_id)
	{
		$query="select count(achievement_id) as val from bhv_t_achv A inner join bhv_t_header B
				on A.header_id=B.header_id where nik='$nik' AND periode='$pilih_bulan' AND
				A.behaviour_id=$behaviour_id";
		return $this->pms->query($query)->row()->val;
	}


	function save_data_non_performance($nik,$aspect_setting_id,$periode,$date_submitted, $status)
	{
		$query="INSERT INTO [PMS].[dbo].[bhv_t_header]([nik],[aspect_setting_id],[periode],[submitted_date], [status])
     			VALUES('$nik','$aspect_setting_id','$periode','$date_submitted', '$status')";
     	
     	return $this->pms->query($query);
	}

	function update_data_non_performance($header_id, $status)
	{
		$query="UPDATE [PMS].[dbo].[bhv_t_header] SET [status]=$status where [header_id]=$header_id";
     	
     	return $this->pms->query($query);
	}

	function save_data_non_performance_achievement($header_id,$behaviour_id,$achievement,$date_submitted, $note)
	{ 
		$query="INSERT INTO [PMS].[dbo].[bhv_t_achv]([header_id],[behaviour_id],[achievement],[submitted_date],[notes])
     			VALUES('$header_id','$behaviour_id','$achievement','$date_submitted',".($note? "'$note'": 'NULL'). ")";
     	return $this->pms->query($query);
	}

	function update_data_non_performance_achievement($achievement_id,$behaviour_id,$achievement,$date_submitted, $note)
	{
		$query="UPDATE bhv_t_achv SET behaviour_id='$behaviour_id', achievement='$achievement',submitted_date='$date_submitted',
				notes=".($note? "'$note'": 'NULL'). "
				WHERE achievement_id=$achievement_id";	

		return $this->pms->query($query);
	}


	function approval_non_performance($header_id, $NIK, $approved_date, $total_achievement,$status)
	{
		$query="UPDATE bhv_t_header SET approved_by='$NIK', approved_date='$approved_date', total_achievement='$total_achievement', status=$status WHERE header_id=$header_id";
		return $this->pms->query($query);
	}

	function reject_non_performance($header_id, $status)
	{
		$query="UPDATE bhv_t_header SET approved_by=NULL, approved_date=NULL, total_achievement=NULL, status=$status WHERE header_id=$header_id";
		return $this->pms->query($query);
	}


	function get_performance_id_by_year($periode, $nik)
	{
		$query="SELECT *
			  FROM [PMS].[dbo].[bhv_t_header]
			  where nik='$nik' and right(periode,4)='$periode' order by header_id asc";

		return $this->pms->query($query)->result();

	}




	function get_performance_id($bulan_terpilih, $nik)
	{
		$query="SELECT *
			  FROM [PMS].[dbo].[bhv_t_header]
			  where nik='$nik' and periode='$bulan_terpilih' order by header_id asc";
		return $this->pms->query($query)->result();

	}

	function get_performance_id_row($bulan_terpilih, $nik)
	{
		$query="SELECT *
			  FROM [PMS].[dbo].[bhv_t_header]
			  where nik='$nik' and periode='$bulan_terpilih' order by header_id asc";
		return $this->pms->query($query)->row();
	}


	function get_bhv_header_last_data($bulan_terpilih, $nik)
	{
		$year = substr($bulan_terpilih, 2,4);
		$query="SELECT TOP 1 *
			  FROM [PMS].[dbo].[bhv_t_header]
			  where nik='$nik' and periode<='$bulan_terpilih' and SUBSTRING(periode,3,4) = '$year' order by header_id desc";
			  
		return $this->pms->query($query)->row();
	}

	function get_count_bhv_header_last_data($bulan_terpilih, $nik)
	{
		$year = substr($bulan_terpilih, 2,4);

		$query="SELECT count(*) as val
			  FROM [PMS].[dbo].[bhv_t_header]
			  where nik='$nik' and periode<='$bulan_terpilih' and SUBSTRING(periode,3,4) = '$year'";
		
		return $this->pms->query($query)->row()->val;
	}


	function get_behaviour_group_all($behaviour_group_id, $begin_date, $end_date)
	{
		$query ="SELECT *, A.sort_number as behaviour_sort, C.sort_number as scala_sort, B.label as behaviour_label, D.label as scala_label,
				B.description as behaviour_desc, D.description as scala_desc, A.behaviour_id, D.value as scala_value
				FROM tb_m_behaviour_group_behaviour A inner join tb_m_behaviour B on A.behaviour_id=B.behaviour_id 
				inner join tb_m_behaviour_group_scala C on A.behaviour_group_id=C.behaviour_group_id
				inner join tb_m_scala D on C.scala_id=D.scala_id
				where A.behaviour_group_id=$behaviour_group_id 
				AND (( A.begin_date<= '$begin_date' AND A.end_date>='$end_date') OR  
				A.begin_date<= GETDATE() AND A.end_date>='$end_date')
				order by A.sort_number";
		return $this->pms->query($query)->result();
	}

	function get_accumulate_achievement($aspect_setting_id,$behaviour_group_id, $NIK, $bulan_terpilih)
	{
		
		$query="SELECT distinct A.aspect_setting_id, A.percentage,D.achievement, C.header_id,C.nik, D.behaviour_id, 
				C.periode FROM tb_m_aspect_setting A INNER JOIN tb_m_behaviour_group_behaviour B ON 
				A.behaviour_group_id=B.behaviour_group_id INNER JOIN bhv_t_header C on C.aspect_setting_id=A.aspect_setting_id
				INNER JOIN bhv_t_achv D on D.header_id=C.header_id WHERE A.behaviour_group_id=$behaviour_group_id AND 
				A.aspect_setting_id=$aspect_setting_id AND C.nik ='$NIK' AND periode='$bulan_terpilih'";

		return $this->pms->query($query)->result();
	}

	function get_weight_by_behaviour_id($behaviour_id)
	{
		$query="SELECT weight from tb_m_behaviour_group_behaviour WHERE behaviour_id=$behaviour_id";
		return $this->pms->query($query)->row();
	}	

	function get_weight_by_behaviour_id_group($behaviour_group_id,$behaviour_id)
	{
		$query="SELECT weight from tb_m_behaviour_group_behaviour WHERE behaviour_id=$behaviour_id AND behaviour_group_id=$behaviour_group_id";
		return $this->pms->query($query)->row();
	}	

}
?>
