<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

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
		
		$this->load->model('admin/auth_model');
		$this->load->library('session');
	}

	public function index()
	{	
		if($this->session->userdata('identity'))
		{
			$this->home();
		}
		else
		{
			$this->load->view('admin/index');
		}
	}
	public function home()
	{
		if($this->session->userdata('identity'))
		{
			$data['admin_header']=$this->load->view('admin/admin_header','',true);
			$this->load->view('admin/home',$data);
		}
		else
		{
			header('Location:'.site_url('admin/login/index') );
		}	
	}
	
	public function auth()
	{		
		if(!empty($_POST))
		{	
			if($row = $this->auth_model->isAuth($_POST['username'],$_POST['pass']))
			{
				//start session
				$userInfo = array(
							'identity' => $row['admin_id'],
							'name' => $row['name']
						);			
				$this->session->set_userdata($userInfo);
				$this->home();	
			}
			else
			{
				//$data['wrong']=$this->load->view('admin/wrong_auth','',true);
				$data['wrong']="<strong>You entered Wrong Username or Password</strong>";
				$this->load->view('admin/index',$data);
			}
		}
		else
		{
			$this->load->view('admin/index');
		}		
	}
	
	public function logout()
	{
		$this->session->sess_destroy();
		//$this->load->view('admin/index');
		$data['log_out']="<strong>You have successfully logged out.</strong>";
		$this->load->view('admin/index',$data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */