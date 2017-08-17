<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends CI_Controller {

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
		
		$this->load->model('admin/site_model');
		$this->load->model('admin/gallery_model');
		$this->load->model('admin/interest_model');	
		$this->load->library('pagination');		
		$this->load->library('session');
	}

	public function index()
	{
		if($this->session->userdata('identity'))
		{
			//$limit = 5;
			$offset = 0;
			if($total = $this->site_model->get_sites(array(),true))
				{
					$config['base_url'] = site_url('admin/site/index/');
					$config['total_rows'] = $total;
					$config['per_page'] = $limit = 10;
					$config['uri_segment'] = 4;
					$config['num_links'] = 1;
					
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
			if(!($sites = $this->site_model->get_sites(array(),false,$limit,$offset)))
						{
							$sites['0'] = "no-data";	
						}		
			$data['admin_header']=$this->load->view('admin/admin_header','',true);
			$data['sites'] = $sites;
			$this->load->view('admin/site_list',$data);
		}
		else
		{
			header('Location:'.site_url('admin/login/index') );
		}
	}
	
	public function remove_site()
	{
		$ids = explode('_',$_POST['id']);
		$rows = $this->site_model->get_sites(array('site_id' => $ids['1']));
		
		$image_id = $rows['0']['feature_image_id'];
		$gallery_id = $rows['0']['gallery_id'];
		
		$this->gallery_model->remove_gallery($gallery_id);
		$this->site_model->remove_feature_image($image_id);	
		$this->site_model->remove_site($ids['1']);
	
	}
	public function add_site()
	{
		
			$site_name = $_POST['site_name'];
			$free_or_paid = $_POST['free_or_paid'];
			$gallery_name = $_POST['gallery_name'];	
			$site_desc = $_POST['site_desc'];	
			$site_location = $_POST['site_location'];	
			$site_city = strtoupper($_POST['city']);	
			$site_state = strtoupper($_POST['state']);
			$longitude = $_POST['longitude'];	
			$latitude = $_POST['latitude'];				
			$interest_id = $_POST['interest'];	
			$site_contact = $_POST['contact'];
			$site_price = $_POST['price'];
			$site_hours = $_POST['hours'];
			$site_web = $_POST['web_site'];
                  
               //  print_r($upload_path);exit;
			if(isset($_FILES['userfile']['name']) && (!empty($_FILES['userfile']['name'])))
			{
				$config = array(
								'allowed_types' => 'png|jpg',
								'upload_path' =>"./feature_images",
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
						$path = 'feature_images/'.$_FILES['userfile']['name'];
						$image_id = $this->site_model->add_feature_image($path);
						$this->site_model->add_site(array('site_name'=>$site_name,'app_type'=>$free_or_paid,'feature_image_id'=>$image_id,'gallery_id'=>$gallery_name,'location'=>$site_location,'city'=>$site_city,'longitude'=>$longitude,'latitude'=>$latitude,'state'=>$site_state,'description'=>$site_desc,'interest_id'=>$interest_id,'contact'=>$site_contact,'price'=>$site_price,'hours'=>$site_hours,'web_site'=>$site_web));		
				  }
				  
			}else
			{
					$error = 'no-file'; 
			}
			echo $error;
			  
        exit;        
				
	}
	
	public function get_edit()
	{
		$ids = explode('_',$_POST['id']);
		$rows = $this->site_model->get_sites(array('site_id' => $ids['1']));
		
		if($all_interests = $this->interest_model->get_interests())
		{
			$rows['1'] = $all_interests;
		}else
		{
			$rows['1'] = 'no-data';
		}
		if($all_galleries = $this->gallery_model->get_galleries(array(),false,10,0,true))    //not worry about 10,0 as last field i.e. all field are set to true.
		{
			$rows['2'] = $all_galleries;
		}else
		{
			$rows['2'] = 'no-data';
		}
		
		echo json_encode($rows);
		exit;	
	}
	
	public function edit_site()
	{
		$site_name = $_POST['site_name']; 
		$free_or_paid = $_POST['free_or_paid'];
		$gallery_name = $_POST['gallery_name'];
		$site_desc = $_POST['site_desc'];
		$site_location = $_POST['site_location'];
		$longitude = $_POST['longitude'];
		$latitude = $_POST['latitude'];
		$site_city = strtoupper($_POST['city']);	
		$site_state = strtoupper($_POST['state']);
		$interest = $_POST['interest'];
		$site_id = $_POST['site_id']; 
		$image_id = isset($_POST['image_id']) ? $_POST['image_id'] : '0' ;
		$site_contact = $_POST['contact'];
		$site_price = $_POST['price'];
		$site_hours = $_POST['hours'];
		$site_web = $_POST['web_site'];
		if(isset($_FILES['userfile']['name']) && (!empty($_FILES['userfile']['name'])))
		{
			$config = array(
							'allowed_types' => 'png|jpg',
							'upload_path' =>"./feature_images",
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
						$path = 'feature_images/'.$_FILES['userfile']['name'];
						
						if($image_id == '0')
						{
							$image_id = $this->site_model->add_feature_image($path);
						}else
						{
							$this->site_model->update_feature_image($image_id,$path);
						}
					$error = 'OK';		
				  }
		}else{
			$error = 'no-file';		
		}
		
		$this->site_model->update_site($site_id ,array(
														'site_name'=>$site_name,
														'app_type' => $free_or_paid,
														'feature_image_id'=>$image_id,
														'gallery_id'=>$gallery_name,
														'location'=>$site_location,
														'city'=>$site_city,
														'state'=>$site_state,
														'longitude'=>$longitude,
														'latitude'=>$latitude,
														'description'=>$site_desc,
														'interest_id'=>$interest,
														'contact'=>$site_contact,
														'price'=>$site_price,
														'hours'=>$site_hours,
														'web_site'=>$site_web,
													  )
									  );
		if($error == 'no-file' OR $error == 'OK')
			{
				echo 'OK';
			}else
			{
				echo $error;
			}
		//$this->interest_model->update_interest( $ids['1'] , $_POST['interest'] );
		exit;
	}
	

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */