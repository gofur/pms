<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adjust_model extends Model {

	function __construct(){
		parent::__construct();
		$this->pms = $this->load->database('default', TRUE);
		
	}

	public function count_result($nik='',$begin='',$end='')
	{
		$query = "SELECT COUNT(*) as val
							FROM core_t_adjustment
							WHERE nik='$nik' AND
								[begin_date] >= '$begin' AND
								CONVERT(VARCHAR(10),[end_date],10) <= CONVERT(VARCHAR(10),'$end',10)";
		return $this->pms->query($query)->row()->val;
	}

	public function get_result_row($nik='',$begin_date='',$end_date='')
	{
		$query = "SELECT TOP 1 *
							FROM core_t_adjustment
							WHERE nik = '$nik' AND 
								((begin_date >= '$begin_date' AND end_date <='$end_date') OR 
 								(end_date >= '$begin_date' AND end_date <= '$end_date') OR 
 								(begin_date >= '$begin_date' AND begin_date <='$end_date' ) OR
 								(begin_date <= '$begin_date' AND end_date >= '$end_date'))
							ORDER BY submit_date DESC";
		return $this->pms->query($query)->row();
	}

	public function add_result($nik='',$begin='',$end='',$after_value=0.00,$before_value=0.00,$biz=0.00,$bhv=0.00,$proj=0.00)
	{
		$query = "INSERT INTO PMS.dbo.core_t_adjustment
								(nik, begin_date, end_date, after_value,before_value, submit_date)
							VALUES 
								('$nik', '$begin' , ' $end' , $after_value,$before_value, GETDATE());";
		$this->pms->query($query);
	}

	public function edit_result($nik='',$begin='',$end='',$after_value=0.00,$before_value=0.00,$biz=0.00,$bhv=0.00,$proj=0.00)
	{
		$query = "UPDATE
								PMS.dbo.core_t_adjustment
							SET
								after_value = $after_value,
								before_value = $before_value,
								submit_date = GETDATE()
							WHERE nik = '$nik' AND 
								begin_date = '$begin' AND 
								end_date = '$end';";
		$this->pms->query($query);
	}

	public function add_before($nik='',$begin='',$end='',$before_value=0.00)
	{
		$query = "INSERT INTO PMS.dbo.core_t_adjustment
								(nik, begin_date, end_date,before_value, submit_date)
							VALUES 
								('$nik', '$begin' , ' $end' ,$before_value, GETDATE());";
		$this->pms->query($query);
	}

	public function edit_before($nik='',$begin='',$end='',$before_value=0.00)
	{
		$query = "UPDATE
								PMS.dbo.core_t_adjustment
							SET
								before_value = $before_value,
								submit_date = GETDATE()
							WHERE nik = '$nik' AND 
								begin_date = '$begin' AND 
								end_date = '$end';";
		$this->pms->query($query);
	}

	public function add_notes($nik='',$begin='',$end='',$notes='')
	{
		$query = "INSERT INTO PMS.dbo.core_t_adjustment
								(nik, begin_date, end_date,notes, submit_date)
							VALUES 
								('$nik', '$begin' , ' $end' ,'$notes', GETDATE());";
		$this->pms->query($query);
	}

	public function edit_notes($nik='',$begin='',$end='',$notes='')
	{
		$query = "UPDATE
								PMS.dbo.core_t_adjustment
							SET
								notes = '$notes',
								submit_date = GETDATE()
							WHERE nik = '$nik' AND 
								begin_date = '$begin' AND 
								end_date = '$end';";
		$this->pms->query($query);
	}

	public function count_result_by_nik($nik,$begin_date, $end_date)
	{
		$query = "SELECT COUNT(*) as val FROM core_t_adjustment WHERE nik='$nik' AND
								((begin_date >= '$begin_date' AND end_date <='$end_date') OR 
 								(end_date >= '$begin_date' AND end_date <= '$end_date') OR 
 								(begin_date >= '$begin_date' AND begin_date <='$end_date' ) OR
 								(begin_date <= '$begin_date' AND end_date >= '$end_date'))";
		return $this->pms->query($query)->row()->val;
	}



}

/* End of file adjust_model.php */
/* Location: ./application/models/adjust_model.php */