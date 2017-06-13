<?php
class Saprfc_Model extends Model{
	function Saprfc_Model(){
		parent::Model();
	}
	
	function sapAttr() {
		$this->sapConn = array (
			"ASHOST"=>"10.9.11.100",
			"SYSNR"=>"30",
			"CLIENT"=>"600",
			"USER"=>"HCM-PORTAL-1",
			"PASSWD"=>"hris2010",
			"MSGSRV"=>"",
			"R3NAME"=>"LHR",
			"CODEPAGE"=>"4110");
		return $this->sapConn;	
	}
		
	function connect() {
		return $this->rfc = saprfc_open($this->sapConn);
	}
	
	function functionDiscover($functionName) {
		$this->fce = saprfc_function_discover($this->rfc, $functionName) or die ("fungsi $functionName tidak ditemukan");

	}

	function importParameter($importParamName, $importParamValue) {
		
		for ($i=0;$i<count($importParamName);$i++) {
			saprfc_import ($this->fce,$importParamName[$i],$importParamValue[$i]);
		}
	}
		
	function setInitTable($initTableName) {
		saprfc_table_init ($this->fce,$initTableName);
	}
	
	function executeSAP() {
		$this->rfc_rc = saprfc_call_and_receive($this->fce);
		if ($this->rfc_rc != SAPRFC_OK){
			if ($this->rfc == SAPRFC_EXCEPTION )
				echo ("Exception raised: ".saprfc_exception($this->fce));
			else
				echo ("Call error: ".saprfc_error($this->fce));
		}
		return $this->rfc_rc;
	}
	function getParameter($ParamName){
		return saprfc_export ($this->fce,$ParamName);
	}
	function fetch_rows($initTableName) {

		$rows = saprfc_table_rows($this->fce,$initTableName);

		if($rows < 1){ 
			$_dataRows = NULL; 
		}
		for ($i=1; $i<=$rows; $i++){
	 		$_dataRows[$i] = saprfc_table_read ($this->fce,$initTableName,$i);
	 	}
		return $_dataRows;
	}
	function fetch_row($initTableName) {
		$_dataRows = saprfc_table_read ($this->fce,$initTableName,1);
		return $_dataRows;
	}
	
	function free() {
		saprfc_function_free($this->fce);
	}
	
	function close() {
		saprfc_close($this->rfc);
	}
	
	function insert($initTableName,$importParamValue){
		return saprfc_table_insert ($this->fce, $initTableName, $importParamValue, 1);
	}
	
	function export($initTableName){
		return saprfc_export ($this->fce,$initTableName);
	}
}
?>