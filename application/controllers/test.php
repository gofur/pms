<?php
class Test extends Controller {

	function __construct()
	{
		parent::__construct();
	}
	public function index()
	{
    echo 'AAAA';
		$this->load->view('test');
	}
}