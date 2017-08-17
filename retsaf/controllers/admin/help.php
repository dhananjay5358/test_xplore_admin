<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Help extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('admin/help_model');
		$this->load->library('pagination');	
		$this->load->library('session');
	}

	public function index()
	{
		if($this->session->userdata('identity'))
		{
			$limit = 10;
			$offset = 0;
			if($total = $this->help_model->get_help(array(),true))
				{
					$config['base_url'] = site_url('admin/help/index/');
					$config['total_rows'] = $total;
					$config['per_page'] = $limit = 10;
					$config['uri_segment'] = 4;
					$config['num_links'] = 2;
					
					$config['first_tag_open'] = '<li>';
					$config['first_tag_close'] = '</li>';
					$config['last_tag_open'] = '<li>';
					$config['last_tag_close'] = '</li>';
					$config['next_tag_open'] = '<li class="next">';
					$config['next_tag_close'] = '</li>';
					$config['prev_tag_open'] = '<li class="previous">';
					$config['prev_tag_close'] = '</li>';
					$config['cur_tag_open'] = '<li class="active">';
					$config['cur_tag_close'] = '</li>';
					$config['num_tag_open'] = '<li>';
					$config['num_tag_close'] = '</li>';	
					// Initialize
					$this->pagination->initialize($config);
					$pages = $this->pagination->create_links();	
					$offset = $this->uri->segment(4) ? $this->uri->segment(4) : 0;
					$data['pages'] = $pages;
					$data['index'] = $offset;	
				}
			
			if(!($help = $this->help_model->get_help(array(),false,$limit,$offset)))
				{
					$help['0'] = "no-data";	
				}
			$data['helps'] = $help;
			$data['admin_header']=$this->load->view('admin/admin_header','',true);
			$this->load->view('admin/help',$data);
		}
		else
		{
			header('Location:'.site_url('admin/login/index') );
		}
	}
	
	public function add_or_remove_help()
	{
		if($_POST['status'] === 'add')
		{
			$this->help_model->add_help($_POST['instruction']);		
		}
		elseif($_POST['status'] === 'remove')
		{
			$ids = explode('_',$_POST['instruction_id']);
			$this->help_model->remove_help($ids['1']);		
		}
		exit;
	}
	
	public function edit_help()
	{
		$this->help_model->update_help( $_POST['id'] , $_POST['instruction'] );
		exit;
	}
	

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */