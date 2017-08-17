<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Newtest extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
	}

	public function index(){
		print_r("hi");
	}
}