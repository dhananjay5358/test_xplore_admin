<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome_message extends CI_Controller {

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
		
		$this->load->model('admin/welcomemessage_model');
		$this->load->library('pagination');	
		$this->load->library('session');
	}

	public function index()
	{
		if($this->session->userdata('identity'))
		{
			$limit = 10;
			$offset = 0;
			if($total = $this->welcomemessage_model->get_messages(array(),true))
				{
					$config['base_url'] = site_url('admin/welcome_message/index/');
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
			
			if(!($messages = $this->welcomemessage_model->get_messages(array(),false,$limit,$offset)))
				{
					$messages['0'] = "no-data";	
				}
			$data['messages'] = $messages;
			$data['admin_header']=$this->load->view('admin/admin_header','',true);
			$this->load->view('admin/message_list',$data);
		}
		else
		{
			header('Location:'.site_url('admin/login/index') );
		}
	}
	
	public function get_interestlist()
	{
		if(!($interests = $this->interest_model->get_interests()))
				{
					$interests['0'] = "no-data";	
				}
			echo json_encode($interests);
			exit;	
	
	}
	
	public function add_or_remove_message()
	{
		if($_POST['status'] === 'add')
		{
			$this->welcomemessage_model->add_message($_POST['message']);		
		}
		elseif($_POST['status'] === 'remove')
		{
			$ids = explode('_',$_POST['message_id']);
			$this->welcomemessage_model->remove_message($ids['1']);		
		}
		exit;
	}
	
	public function edit_message()
	{
		$this->welcomemessage_model->update_message( $_POST['id'] , $_POST['message'] );
		exit;
	}
	

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */