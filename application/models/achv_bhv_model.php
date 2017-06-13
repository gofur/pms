<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Achv_bhv_model extends Model {
	function __construct(){
		parent::__construct();
		$this->portal=$this->load->database('portal', TRUE);
		$this->pms = $this->load->database('default', TRUE);
		
	}
	public function count_header($month_year='',$nik='',$aspect_set_id = 0 ,$is_exact=FALSE, $status='all')
	{
		$query = "SELECT COUNT(*) AS val 
							FROM [PMS].[dbo].[bhv_t_header] 
							WHERE nik = '$nik'";

		if ($is_exact) {
			$query .= " AND periode = '$month_year'";
		} else {
			$query .= " AND periode <= '$month_year'";
		}

		if ($aspect_set_id != 0) {
			$query .= " AND aspect_setting_id = $aspect_set_id";
		}

		if (is_array($status)) {
			$query .= " AND status IN (".implode(', ', $status).') ';
			
		} else {
			if (is_integer($status)) {
				$query .= " AND status = $status ";
			} else {
				switch (strtolower($status)) {
		 			case 'open':
		 				$query .= " AND status IN (0,2)";
		 				break;
		 			case 'lock':
		 				$query .= " AND status IN (1,3,4,5)";
		 				break;
		 			case 'draft':
		 				$query .= " AND status = 0";
		 				break;
		 			case 'pending':
		 				$query .= " AND status = 1";
		 				break;
		 			case 'reject':
		 				$query .= " AND status = 2";
		 				break;
		 			case 'approve':
		 				$query .= " AND status = 3";
		 				break;
		 			case 'adjust':
		 				$query .= " AND status = 4";
		 				break;
		 			case 'final':
		 				$query .= " AND status = 5";
		 				break;
		 		}
			}
		}

		return $this->pms->query($query)->row()->val;
	}

	public function get_header_list($month_year='',$nik='',$aspect_set_id = 0 ,$is_exact=FALSE, $status='all')
	{
		$query = "SELECT *,
								total_achievement as total_achv 
							FROM [PMS].[dbo].[bhv_t_header] 
							WHERE nik = '$nik'";

		if ($is_exact) {
			$query .= " AND periode = '$month_year'";
		} else {
			$query .= " AND periode <= '$month_year'";
		}

		if ($aspect_id != 0) {
			$query .= " AND aspect_setting_id = $aspect_set_id";
		}

		if (is_array($status)) {
			// $in = ' AND status IN (';
			// foreach ($status as $key => $value) {
			// 	$in .= $value.', ';
			// }

			// $in_len = strlen($in) - 2;
			// $in = substr($in, 0, $in_len).')';
			// $query .= $in;
			$query .= " AND status IN (".implode(', ', $status).') ';
			
		} else {
			if (is_integer($status)) {
				$query .= " AND status = $status ";
			} else {
				switch (strtolower($status)) {
		 			case 'open':
		 				$query .= " AND status IN (0,2)";
		 				break;
		 			case 'lock':
		 				$query .= " AND status IN (1,3,4,5)";
		 				break;
		 			case 'draft':
		 				$query .= " AND status = 0";
		 				break;
		 			case 'pending':
		 				$query .= " AND status = 1";
		 				break;
		 			case 'reject':
		 				$query .= " AND status = 2";
		 				break;
		 			case 'approve':
		 				$query .= " AND status = 3";
		 				break;
		 			case 'adjust':
		 				$query .= " AND status = 4";
		 				break;
		 			case 'final':
		 				$query .= " AND status = 5";
		 				break;
		 		}
			}
		}
		return $this->pms->query($query)->result();
	}

	public function get_header_byAspect_row($month_year='',$nik='',$aspect_set_id = 0 ,$is_exact=FALSE, $status='all')
	{
		$query = "SELECT TOP 1 *, total_achievement AS total_achv 
							FROM [PMS].[dbo].[bhv_t_header] 
							WHERE nik = '$nik'";

		if ($is_exact) {
			$query .= " AND periode = '$month_year'";
		} else {
			$query .= " AND periode <= '$month_year'";
		}

		if ($aspect_set_id != 0) {
			$query .= " AND aspect_setting_id = $aspect_set_id";
		}

		if (is_array($status)) {
			// $in = ' AND status IN (';
			// foreach ($status as $key => $value) {
			// 	$in .= $value.', ';
			// }

			// $in_len = strlen($in) - 2;
			// $in = substr($in, 0, $in_len).')';
			// $query .= $in;
			$query .= " AND status IN (".implode(', ', $status).') ';
			
		} else {
			if (is_integer($status)) {
				$query .= " AND status = $status ";
			} else {
				switch (strtolower($status)) {
		 			case 'open':
		 				$query .= " AND status IN (0,2)";
		 				break;
		 			case 'lock':
		 				$query .= " AND status IN (1,3,4,5)";
		 				break;
		 			case 'draft':
		 				$query .= " AND status = 0";
		 				break;
		 			case 'pending':
		 				$query .= " AND status = 1";
		 				break;
		 			case 'reject':
		 				$query .= " AND status = 2";
		 				break;
		 			case 'approve':
		 				$query .= " AND status = 3";
		 				break;
		 			case 'adjust':
		 				$query .= " AND status = 4";
		 				break;
		 			case 'final':
		 				$query .= " AND status = 5";
		 				break;
		 		}
			}
		}
		$query .= " ORDER BY periode DESC";
		return $this->pms->query($query)->row();

	}

	public function get_header_byID_row($header_id)
	{
		$query = "SELECT TOP 1 *,total_achievement AS total_achv 
							FROM [PMS].[dbo].[bhv_t_header] 
							WHERE header_id = $header_id";
		return $this->pms->query($query)->row();
	}

	public function edit_header_status($header_id=0,$status=0)
	{
		$query = "UPDATE
								PMS.dbo.bhv_t_header
							SET
								status = $status
							WHERE header_id = $header_id;";
		$this->pms->query($query);
	}

}

/* End of file achv_bhv_model.php */
/* Location: ./application/models/achv_bhv_model.php */