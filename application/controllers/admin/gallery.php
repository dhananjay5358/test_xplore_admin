<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gallery extends CI_Controller {

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

		$this->load->model('admin/gallery_model');
		$this->load->library('pagination');	
		$this->load->library('session');
	}

	public function index()
	{
		if($this->session->userdata('identity'))
		{
			$limit = 10;
			$offset = 0;
			if($total = $this->gallery_model->get_galleries(array(),true))
				{
					$config['base_url'] = site_url('admin/gallery/index/');
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
				
			if(!($galleries = $this->gallery_model->get_galleries(array(),false,$limit,$offset)))
				{
					$galleries['0'] = "no-data";	
				}
			$data['admin_header']=$this->load->view('admin/admin_header','',true);
			$data['galleries']= $galleries;
			$this->load->view('admin/gallary_list',$data);
		}
		else
		{
			header('Location:'.site_url('admin/login/index') );
		}
	}
	
	public function get_gallerylist()
	{
		if(!($galleries = $this->gallery_model->get_galleries(array(),false,10,0,true)))   //not worry about 10,0 as last field i.e. all field are set to true.
				{
					$galleries['0'] = "no-data";	
				}
			echo json_encode($galleries);
			exit;	
	
	}
	
	public function add_or_remove_gallery()
	{
		if($_POST['status'] === 'add')
		{
			$this->gallery_model->add_gallery($_POST['gallery']);		
		}
		elseif($_POST['status'] === 'remove')
		{
			$ids = explode('_',$_POST['id']);
			$this->gallery_model->remove_gallery($ids['1']);		
		}
		exit;
	}
	
	public function edit_gallery()
	{
		$id = $this->uri->segment(4);
		$gallery = $this->gallery_model->get_galleries(array('gallery_id' => $id));		
		$data['gallery'] = $gallery['0'];
		$limit = 10;
		$offset = 0;
		if($total = $this->gallery_model->get_images(array('gallery_id' => $id),true))
				{
					$config['base_url'] = site_url('admin/gallery/edit_gallery/'.$id.'/');
					$config['total_rows'] = $total;
					$config['per_page'] = $limit = 10;
					$config['uri_segment'] = 5;
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
					$offset = $this->uri->segment(5) ? $this->uri->segment(5) : 0;
					$data['pages'] = $pages;
					$data['index'] = $offset;	
				}
				
		if(!($images = $this->gallery_model->get_images(array('gallery_id' => $id),false,$limit,$offset)))
				{
					$images['0'] = "no-data";	
				}
		$data['images'] = $images;
		$data['admin_header']=$this->load->view('admin/admin_header','',true);
		$this->load->view('admin/edit_gallery',$data);
	
	}
	
	public function update()
	{
		$this->gallery_model->update_gallery($_POST['id'],$_POST['name']);
		exit;
	}
	
	public function add_image()
	{
			$gallery_id = $_POST['gallery_id'];
			$image_name = $_POST['image_name'];	
				  
			   //  print_r($upload_path);exit;
			if(isset($_FILES['userfile']['name']) && (!empty($_FILES['userfile']['name'])))
			{
				$config = array(
								'allowed_types' => 'png|jpg',
								'upload_path' =>"./images",
								'max_size' => 2000000000,
								'overwrite' => TRUE,
								'remove_spaces' => TRUE
								);
				
				 $this->load->library('upload', $config);
				 if (!$this->upload->do_upload('userfile'))
					{                   
						 $error = $this->upload->display_errors();	                   
					}
				 else
				  {     
						$error = 'OK';
						$path = 'images/'.$_FILES['userfile']['name'];
						$image_id = $this->gallery_model->add_image($image_name,$gallery_id,$path);						
				  }
				  
			}else
			{
					$error = 'no-file'; 
			}
			echo $error;
			  
		exit;
		
	}

	public function edit_image()
	{
		$image_id = $_POST['image_id']; 
		$image_name = $_POST['image_name'];
		$gallery_id = $_POST['gallery_id'];

		if(isset($_FILES['userfile']['name']) && (!empty($_FILES['userfile']['name'])))
		{
			$config = array(
							'allowed_types' => 'png|jpg',
							'upload_path' =>"./images",
							'max_size' => 2000000000,
							'overwrite' => TRUE,
							'remove_spaces' => TRUE
							);
				
				 $this->load->library('upload', $config);
				 
				 if (!$this->upload->do_upload('userfile'))
					{                   
						 $error = $this->upload->display_errors();	                   
					}
				 else
				  {     
						$error = 'OK';
						$path = 'images/'.$_FILES['userfile']['name'];
							
						$this->gallery_model->update_image($image_id,array('name'=>$image_name,'path'=>$path,'gallery_id'=>$gallery_id));					
				  }
		}else{		
					$error = 'OK';
					$this->gallery_model->update_image($image_id,array('name'=>$image_name,'gallery_id'=>$gallery_id));
			}
		echo $error;		
		//$this->interest_model->update_interest( $ids['1'] , $_POST['interest'] );
		exit;
	}
	
	public function delete_image()
	{
		$ids = explode('_',$_POST['id']);
		$this->gallery_model->remove_image($ids['1']);
		exit;
	}
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */