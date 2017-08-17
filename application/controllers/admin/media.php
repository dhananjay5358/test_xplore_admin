<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Media extends CI_Controller {

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
		
		$this->load->model('admin/media_model');
		$this->load->model('admin/site_model');
		$this->load->model('admin/interest_model');
		$this->load->library('pagination');	
		$this->load->library('session');
	}

	public function index()
	{
		if($this->session->userdata('identity'))
		{
			$limit = 10;
			$offset = 0;
			if($total = $this->media_model->get_media(array(),true))
				{
					$config['base_url'] = site_url('admin/media/index/');
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
				
			if(!($media = $this->media_model->get_media(array(),false,$limit,$offset)))
				{
					$media['0'] = "no-data";	
				}
				$data['media'] = $media;
			$data['admin_header'] = $this->load->view('admin/admin_header','',true);
			$this->load->view('admin/media_list',$data);
		}
		else
		{
			header('Location:'.site_url('admin/login/index') );
		}
	}
	
	public function getInfoForSelectBox()
	{		
		$result = $this->infoSelectBoxes();
		echo json_encode($result);
			exit;	
	
	}
	
	public function infoSelectBoxes()
	{
		if(!($interests = $this->interest_model->get_interests()))
				{
					$interests['0'] = "no-data";	
				}
		if(!($mediaTypes = $this->media_model->get_mediaTypes()))
				{
					$mediaTypes['0'] = "no-data";	
				}		
		$result['0'] = 	$mediaTypes;
		$result['1'] = 	$interests;
		return $result;
	}
	
	public function add_media()
	{
			$media_name = $_POST['media_name'];
			$media_type_id = $_POST['media_type_id'];	
			$site_id = $_POST['site_id'];		
			$source_url = $_POST['source_url'];	
			$media_desc = $_POST['media_desc'];	 
                  
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
						$path = 'feature_images/'.$_FILES['userfile']['name'];
						$image_id = $this->site_model->add_feature_image($path);
						$media_id = $this->media_model->add_media(array('title'=>$media_name,'feature_image_id'=>$image_id,'mediaType_id'=>$media_type_id,'url'=>$source_url,'media_desc'=>$media_desc));		
						$this->media_model->add_interest_media(array('interest_id'=>$site_id,'media_id'=>$media_id));
						$error = 'OK';
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
		$rows = $this->media_model->get_media(array('media.media_id' => $ids['1']));
		
		$result = $this->infoSelectBoxes();
		$result['2'] = $rows['0'];
		
		echo json_encode($result);
		exit;	
	}
	
	public function edit_media()
	{
		$media_id = $_POST['media_id'];
		$media_name = $_POST['media_name'];
		$media_type_id = $_POST['media_type_id'];
		$source_url = $_POST['source_url'];	
		$media_desc = $_POST['media_desc'];	
		$site_id = $_POST['site_id']; 
		
		//image_id = image_id 
		$image_id = isset($_POST['image_id']) ? $_POST['image_id'] : '0' ;
		
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
		
		$this->media_model->update_media($media_id ,array(
															'title'=>$media_name,
															'feature_image_id'=>$image_id,
															'mediaType_id'=>$media_type_id,
															'media_desc'=>$media_desc,
															'url'=>$source_url
														)
									    );
		$this->media_model->update_interest_media($media_id ,array(
																'media_id' => $media_id ,
																'interest_id' => $site_id
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
	
	public function remove_media()
	{
			$ids = explode('_',$_POST['id']);
			$rows = $this->media_model->get_media(array('media.media_id' => $ids['1']));
			$image_id = $rows['0']['feature_image_id']; 
			$this->media_model->remove_media($ids['1']);
			$this->site_model->remove_feature_image($image_id);	 
			exit;
	}
	

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */