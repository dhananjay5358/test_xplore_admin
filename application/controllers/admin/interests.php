<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Interests extends CI_Controller {

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
		
		$this->load->model('admin/interest_model');
		$this->load->model('admin/gallery_model');
		$this->load->model('admin/site_model');
		$this->load->library('pagination');	
		$this->load->library('session');
	}

	public function index()
	{
		// if($this->session->userdata('identity'))
		// {
			$limit = 10;
			$offset = 0;
			if($total = $this->interest_model->get_interests(array(),true))
				{
					$config['base_url'] = site_url('admin/interests/index/');
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
				
			if(!($interests = $this->interest_model->get_interests(array(),false,$limit,$offset)))
				{
					$interests['0'] = "no-data";	
				}
			$data['interests'] = $interests;
			$data['admin_header']=$this->load->view('admin/admin_header','',true);
			$this->load->view('admin/interest_list',$data);
		// }
		// else
		// {
		// 	header('Location:'.site_url('admin/login/index') );
		// }
	}
	
	public function index_kendo()
	{
		// if($this->session->userdata('identity'))
		// {
			// $limit = 10;
			// $offset = 0;
			// if($total = $this->interest_model->get_interests(array(),true))
			// 	{
			// 		$config['base_url'] = site_url('admin/interests/index/');
			// 		$config['total_rows'] = $total;
			// 		$config['per_page'] = $limit = 10;
			// 		$config['uri_segment'] = 4;
			// 		$config['num_links'] = 2;	
					
			// 		$config['first_tag_open'] = '<li>';
			// 		$config['first_tag_close'] = '</li>';
			// 		$config['last_tag_open'] = '<li>';
			// 		$config['last_tag_close'] = '</li>';
			// 		$config['next_tag_open'] = '<li class="next">';
			// 		$config['next_tag_close'] = '</li>';
			// 		$config['prev_tag_open'] = '<li class="previous">';
			// 		$config['prev_tag_close'] = '</li>';
			// 		$config['cur_tag_open'] = '<li class="active">';
			// 		$config['cur_tag_close'] = '</li>';
			// 		$config['num_tag_open'] = '<li>';
			// 		$config['num_tag_close'] = '</li>';
			// 		// Initialize
			// 		$this->pagination->initialize($config);
			// 		$pages = $this->pagination->create_links();	
			// 		$offset = $this->uri->segment(4) ? $this->uri->segment(4) : 0;
			// 		$data['pages'] = $pages;
			// 		$data['index'] = $offset;	
			// 	}
				
			$interests = $this->interest_model->get_interests__();
			$data['interests'] = $interests;
			$data['admin_header']=$this->load->view('admin/admin_header','',true);
			echo json_encode($data['interests']);
			//$this->load->view('admin/interest_list',$data);
		// }
		// else
		// {
		// 	header('Location:'.site_url('admin/login/index') );
		// }
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
	
	public function getEdit_interest()
	{
		$ids = explode('_',$_POST['id']);
		$interests = $this->interest_model->get_interests(array('interest_id' => $ids['1']));
		echo $interests['0']['title'];
		exit;
	}
	
	public function add_or_remove_interest()
	{
		if($_POST['status'] === 'add')
		{
			if($_POST['capitalise']=='All')
			{
				$this->interest_model->add_interest(strtoupper(trim($_POST['interest'])),1);
			}elseif($_POST['capitalise']=='First')
			{
				$this->interest_model->add_interest(ucwords(trim($_POST['interest'])),2);
			}else{
			$this->interest_model->add_interest(trim($_POST['interest']),0);
			}			
		}
		elseif($_POST['status'] === 'remove')
		{
			$ids = explode('_',$_POST['interest_id']);
			
			if($sites = $this->site_model->get_sites(array('interest_id' => $ids['1'])))
			{
				foreach($sites as $site)
				{
					$image_id = $site['feature_image_id'];
					$gallery_id = $site['gallery_id'];
					$site_id = $site['site_id'];
					
					$this->gallery_model->remove_gallery($gallery_id);
					$this->site_model->remove_feature_image($image_id);	
					$this->site_model->remove_site($site_id);				
				}
			
			}
			
			
			$this->interest_model->remove_interest($ids['1']);		
		}
		exit;
	}
	
	public function edit_interest()
	{
		$ids = explode('_',$_POST['interest_id']);
		$this->interest_model->update_interest( $ids['1'] , $_POST['interest'] );
		exit;
	}
	

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */