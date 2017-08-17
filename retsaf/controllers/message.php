<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Message extends CI_Controller {

	/** 
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	 public function __construct()
	{
		parent::__construct();
		
		$this->load->model('message_model');
		$this->load->model('interest_model');
		$this->load->model('site_model');
		
		//$this->load->library('pagination');	
		//$this->load->library('session');
	}

	public function index()
	{
		
	}
	
	public function getDataForHome()
	{
		if(!($messages = $this->message_model->get_messages()))
				{
					$messages['0'] = "no-data";	
				}
		if(!($interests = $this->interest_model->get_interests()))
				{
					$interests['0'] = "no-data";	
				}		
		$result['0'] = $messages;
		$result['1'] = $interests;
 		
				
			//$messages = "Hi..."; 
			echo $_GET['callback'] .'('. json_encode($result) . ')';
			//echo json_encode($messages);
			exit;	
	}
	
	
	public function getAppinstructions()
	{
		if(!($instruction = $this->message_model->get_app_instruction()))
				{
					$instruction['0'] = "no-data";	
				}
		
			//$messages = "Hi..."; 
			echo $_GET['callback'] .'('. json_encode($instruction) . ')';
			//echo json_encode($messages);
			exit;	
	}
	/*public function getSiteList()
	{
		$where_ins['0'] = 'interest_id';

		$where_in['0'] = '1';
		$where_in['1'] = '3';
		$where_in['2'] = '8';
		$where_ins['1'] = $where_in;
		
		if(!($sites = $this->site_model->get_sites(array(),$where_ins))) // 1st array for where, 2nd for where_in
				{
					$sites['0'] = "no-data";	
				}
 						
			//$messages = "Hi..."; 
			echo $_GET['callback'] .'('. json_encode($sites) . ')';
			//echo json_encode($messages);
			exit;	
	}*/
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */