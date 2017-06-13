<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( ! function_exists('thousand_separator'))
{
	function thousand_separator($number)
	{	
		if (is_numeric($number)){
			$decimals = 2;
			return number_format($number,$decimals);	
		} elseif ($number=='-') {
			return '<i class="icon-minus"></i>';
		}	
		else {
			return $number;
		}
	}
}

if ( ! function_exists('build_menu')){
	function build_menu($parentID=0,$parentType=0,$result){
		$CI =& get_instance();
		$RoleID = $CI->session->userdata('roleID');
		$CI->load->model('system_model');
		$count_menu = $CI->system_model->count_menu($RoleID,$parentID);
		if ($count_menu>0){
			if ($parentID ==0){
				$result .='<ul class="nav">';
			}else{
				if ($parentType==1 or $parentType==2){
				$result .='<ul class="dropdown-menu">';			
					
				}
			}
			$temp = $CI->system_model->get_menu($RoleID,$parentID);
			foreach ($temp as $row) {
				switch ($row->Type) {
					case 1:
						$result .= '<li class="dropdown">';
						$result .= anchor($row->Link_Value,$row->Menu .'<b class="caret"></b>','class="dropdown-toggle" data-toggle="dropdown"');
						$result = build_menu($row->MenuID,$row->Type,$result);
						$result .= '</li>';
						break;
					case 2:
						$result .= '<li class="dropdown-submenu">';
						$result .= anchor('#',$row->Menu,'tabindex="-1"');
						$result = build_menu($row->MenuID,$row->Type,$result);
						$result .= '</li>';
					break;
					case 3;
						$result .= '<li class="divider"></li>';
					break;
					case 4;
						$result .='<li class="nav-header">'.$row->Menu.'</li>';
					default:
						$result .= '<li>';
						$result .= anchor($row->Link_Value,$row->Menu);
						$result .= '</li>';
						break;
				}					
			}
			$result .='</ul>';
		}
		return $result;
	}
}
if ( ! function_exists('full_org_text')){
	function full_org_text($childID,$isSAP,$result=''){
		$CI =& get_instance();
		$CI->load->model('org_model');
		$org=$CI->org_model->get_Organization_row($childID,$isSAP);
		if($isSAP==0 and $org->OrganizationParent==0){
			$result .= $org->OrganizationName;
		}elseif($isSAP==1 and $org->OrganizationParent==50002147){
			$result .= $org->OrganizationName;
		}else{
			$result = full_org_text($org->OrganizationParent,$isSAP,$result);
			$result .= ' - '.$org->OrganizationName;
		}
		return $result;
	}
}
if ( ! function_exists('root_org_id')){
	function root_org_id($childID,$isSAP,$result=''){
		$CI =& get_instance();
		$CI->load->model('org_model');
		$org=$CI->org_model->get_Organization_row($childID,$isSAP);
		if($isSAP==0 and $org->OrganizationParent==0){
			$result = $org->OrganizationID;
		}elseif($isSAP==1 and $org->OrganizationParent==50002147){
			$result = $org->OrganizationID;
		}else{
			$result = root_org_id($org->OrganizationParent,$isSAP,$result);
		}
		return $result;
	}
}

if ( ! function_exists('format_timedate')){
	function format_timedate($timedate,$format='Y-m-d'){
		switch ($format) {
			case 'Y-m-d':
				$result=substr($timedate,0,10);
				break;
			
			default:
				$result=$timedate;
				break;
		}
		return $result;
	}
}

if(!function_exists('employee_hierarchy')){
	function employee_hierarchy($isSAP,$OrganizationID,$link='',$list=''){
		$CI =& get_instance();
		$CI->load->model('org_model');
		$subordinate = $CI->org_model->get_subordinate_list($isSAP,$OrganizationID);
		foreach ($subordinate as $row) {
			$list .= '<li>'.anchor($link.'/'.$row->isSAP.'/'.$row->HolderID.'/'.$row->NIK,$row->Fullname.' - '.$row->NIK).'</li>';
		}
		$subOrg = $CI->org_model->get_Organization_list($OrganizationID,$isSAP,date('Y-m-d'),date('Y-m-d'));
		if(count($subOrg)>0){
			foreach ($subOrg as $row) {
				$chief= $CI->org_model->get_chief_row($isSAP,$row->OrganizationID);
				if(count($chief)>0){
					$list.= '<li>'.anchor($link.'/'.$chief->isSAP.'/'.$chief->HolderID.'/'.$chief->NIK,$chief->Fullname .' - '.$chief->NIK).'<ul>';
					$list=employee_hierarchy($isSAP,$row->OrganizationID,$link,$list);
					$list.='</ul></li>';
				}
			}

		}
		return $list;
	}
}