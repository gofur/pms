<?php
class Project_model extends Model{
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
 
 	public function check_member($project_id=0,$nik=0,$is_chief=2)
 	{
		$query = "SELECT count(*) as val
							FROM proj_T_member
							WHERE project_id = $project_id AND
								nik = '$nik' ";
 		if ($is_chief == 1 OR $is_chief == 0) {
 			$query .= " AND is_chief = $is_chief";
 		} 
 		
 		$count = $this->pms->query($query)->row()->val;

 		if ($count) {
 			return true;
 		} else {
 			return false;
 		}
 	}

	public function get_list($nik= '', $is_active = 2, $begin = '', $end = '',$scope = 2, $pers_admin = '', $is_sap = 2 )
	{
		$query = "SELECT * 
							FROM proj_T_project 
							WHERE begin_date >= '$begin' AND 
								end_date <= '$end' OR
								project_id IN (SELECT project_id FROM proj_T_member WHERE nik = '$nik' AND is_active = 1)";
		switch ($scope) {
			case 2: // ALL  

				break;
			case 1: // Unit  
				 $query .= " AND scope= 1 AND pers_admin = '$pers_admin' AND is_sap = $is_sap";
				break;
			case 0: // Corporate  
				 $query .= " AND scope= 0";
				# code...
				break;
		}
		if ($is_active == 0 OR $is_active == 1) {
			$query .= " AND is_active = $is_active";
		}
		return $this->pms->query($query)->result();
	}

	public function get_assign_list($nik='',$begin_date='',$end_date='')
	{
		$query = "SELECT p.*,
								m.* 
							FROM proj_T_project p
							INNER JOIN proj_T_member m on p.project_id = m.project_id
							WHERE p.is_active = 1 AND
								p.begin_date >= '$begin_date' AND 
								p.end_date <= '$end_date' AND 
								m.nik = '$nik' AND m.is_active = 1";
		return $this->pms->query($query)->result();
		
	}

	public function get_sub_list($nik=array(),$begin_date='',$end_date='')
	{
		$nik_ls = implode("', '", $nik);
		$query = "SELECT p.*,
								m.*,
								u.Fullname 
							FROM proj_T_project p
							INNER JOIN proj_T_member m on p.project_id = m.project_id
							INNER JOIN core_m_user u on m.nik = u.nik
							WHERE p.is_active = 1 AND
								p.begin_date >= '$begin_date' AND 
								p.end_date <= '$end_date' AND 
								m.nik IN ('". $nik_ls."') AND m.is_active = 1";
		return $this->pms->query($query)->result();
	}

	public function get_row($project_id = 0)
	{
		$query = "SELECT * FROM proj_T_project WHERE project_id = $project_id";
		return $this->pms->query($query)->row();
	}

	public function add_project($title='',$doc_num='', $desc='', $begin='', $end='',$leader_nik='', $leader_name = 'Project Leader', $scope = 1, $pers_admin=0, $is_sap=0)
	{
		$by = $this->session->userdata('NIK');
		$query = "INSERT INTO [PMS].[dbo].[proj_T_project]
           ([project_name]
           ,[doc_num]
           ,[description]
           ,[scope]
           ,[pers_admin]
           ,[is_active]
           ,[begin_date]
           ,[end_date]
           ,[insert_by]
           ,[insert_on]
           ,[update_by]
           ,[update_on])
     VALUES
           ('$title'
           ,'$doc_num'
           ,'$desc'
           ,$scope
           ,'$pers_admin'
           ,1
           ,'$begin'
           ,'$end'
           ,'$by'
           ,GETDATE()
           ,'$by'
           ,GETDATE())";
		$this->pms->query($query);

		$query = "SELECT MAX(project_id) as last_id FROM proj_T_project WHERE is_active = 1";
		$last_id = $this->pms->query($query)->row()->last_id;

		// if ($scope == 1) {
		// 	$query = "UPDATE [PMS].[dbo].[proj_T_project]
		// 			   SET [org_id] = $unit_id
		// 			      ,[is_sap] = $is_sap
		// 			 WHERE project_id = $last_id";
		// 	$this->pms->query($query);
		// }

		$this->add_member($last_id,$leader_nik,'','Project Leader',1);
	}

	public function edit_project($project_id = 0 , $title='',$doc_num = '', $desc='', $begin='', $end='')
	{
		$by = $this->session->userdata('NIK');
		$query = "UPDATE [PMS].[dbo].[proj_T_project]
						   SET [project_name] = '$title'
						      ,[doc_num] = '$doc_num'
						      ,[description] = '$desc'
						      ,[begin_date] = '$begin'
						      ,[end_date] = '$end'
						      ,[update_by] = '$by'
						      ,[update_on] = GETDATE()
						 WHERE project_id = $project_id";
		$this->pms->query($query);
	}

	public function edit_project_status($project_id=0,$status=1)
	{
		$by = $this->session->userdata('NIK');
		$query = "UPDATE [PMS].[dbo].[proj_T_project]
						   SET [is_active] = $status
						      ,[update_by] = '$by'
						      ,[update_on] = GETDATE()
						 WHERE project_id = $project_id";
		$this->pms->query($query);
	}

	public function delete_project($project_id = 0 )
	{
		$query = "DELETE FROM proj_T_member WHERE project_id = $project_id";
		$this->pms->query($query);
		$query = "DELETE FROM proj_T_project WHERE project_id = $project_id";
		$this->pms->query($query);
	}

	public function get_member_list($project_id = 0, $is_active = 2, $is_chief = 2)
	{
		$query = "SELECT * FROM proj_T_member WHERE project_id = $project_id";
		
		if ($is_active == 1 OR $is_active == 0) {
			$query .= " AND is_active = $is_active";
		}

		if ($is_chief == 1 OR $is_chief == 0) {
			$query .= " AND is_chief = $is_chief";
		}
		return $this->pms->query($query)->result();
	}

	public function get_member_row($member_id = 0)
	{
		$query = "SELECT * FROM proj_T_member WHERE member_id = $member_id";
		return $this->pms->query($query)->row();

	}
	public function add_member($project_id=0,$nik='',$kpi='',$role_name='',$is_chief=0)
	{
		$by = $this->session->userdata('NIK');
		$query = "INSERT INTO [PMS].[dbo].[proj_T_member]
           ([project_id]
           ,[nik]
           ,[kpi]
           ,[role_name]
           ,[is_chief]
           ,[is_active]
           ,[insert_by]
           ,[insert_on]
           ,[update_by]
           ,[update_on])
     VALUES
           ($project_id
           ,'$nik'
           ,'$kpi'
           ,'$role_name'
           ,$is_chief
           ,1
           ,'$by'
           ,GETDATE()
           ,'$by'
           ,GETDATE())";
		$this->pms->query($query);
		$query = "SELECT TOP 1 member_id FROM [PMS].[dbo].[proj_T_member] WHERE project_id = $project_id ORDER BY member_id DESC";
		return $this->pms->query($query)->row()->member_id;
	}

	public function edit_member($member_id=0,$kpi='',$role_name='')
	{
		$by = $this->session->userdata('NIK');
		$query = "UPDATE [PMS].[dbo].[proj_T_member]
				   SET [role_name] = '$role_name'
				    	,[kpi]       = '$kpi'
				      ,[update_by] = '$by'
				      ,[update_on] = GETDATE()
				 WHERE member_id = $member_id";
		$this->pms->query($query);
	}

	public function edit_member_status($member_id=0,$is_active=1)
	{
		$by = $this->session->userdata('NIK');
		$query = "UPDATE [PMS].[dbo].[proj_T_member]
				   SET [is_active] = $is_active
				      ,[update_by] = '$by'
				      ,[update_on] = GETDATE()
				 WHERE member_id = $member_id";
		$this->pms->query($query);

	}

	public function edit_member_result($member_id=0,$result=0.00)
	{
		$by = $this->session->userdata('NIK');
		$query = "UPDATE [PMS].[dbo].[proj_T_member]
				   SET [result] = $result
				      ,[update_by] = '$by'
				      ,[update_on] = GETDATE()
				 WHERE member_id = $member_id";
		$this->pms->query($query);
	}

	public function delete_member($member_id=0)
	{
		$query = "DELETE FROM proj_T_member WHERE member_id = $member_id";
		$this->pms->query($query);
	}

	/////////////
	// Result //
	/////////////
	
	public function count_member_result($nik='',$begin='',$end='')
	{
		$query = "SELECT COUNT(*) as val 
							FROM proj_T_member m 
							JOIN proj_T_project p 
							  ON p.project_id = m.project_id 
							WHERE m.nik = '$nik' AND 
								m.is_active = 1 AND p.is_active = 1 AND 
							  ((p.begin_date >= '$begin' AND p.end_date <='$end') OR 
 								(p.end_date >= '$begin' AND p.end_date <= '$end') OR 
 								(p.begin_date >= '$begin' AND p.begin_date <='$end' ) OR
 								(p.begin_date <= '$begin' AND p.end_date >= '$end'))";
		return $this->pms->query($query)->row()->val;
	}

	public function sum_member_result($nik='',$begin='',$end='')
	{
		$query = "SELECT SUM(m.result) as val 
							FROM proj_T_member m 
							JOIN proj_T_project p 
							  ON p.project_id = m.project_id 
							WHERE m.nik = '$nik' AND 
								m.is_active = 1 AND p.is_active = 1 AND 
							  ((p.begin_date >= '$begin' AND p.end_date <='$end') OR 
 								(p.end_date >= '$begin' AND p.end_date <= '$end') OR 
 								(p.begin_date >= '$begin' AND p.begin_date <='$end' ) OR
 								(p.begin_date <= '$begin' AND p.end_date >= '$end'))";
		$result = $this->pms->query($query)->row()->val;
		if (is_null($result)) {
			$result = 0; 	
		} 
		return $result;
	}
}
?>