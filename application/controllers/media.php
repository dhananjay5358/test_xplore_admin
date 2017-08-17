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
		
		$this->load->model('media_model');
		//$this->load->model('interest_model');
		//$this->load->model('site_model');
		
		//$this->load->library('pagination');	
		//$this->load->library('session');
	}

	public function index()
	{
		
	}
	
	public function getmediaTypes()
	{				
		if(!($mediaTypes = $this->media_model->getmediaTypes()))
				{
					$mediaTypes['0'] = "no-data";	
				}

				//$messages = "Hi..."; 
			echo $_GET['callback'] .'('. json_encode($mediaTypes) . ')';
			//echo json_encode($messages);
			exit;	
	}
	
	public function getMedialist()
	{
		
		$selected_interest = explode(',',$_GET['selected_interest']);
		$selected_media_type = explode(',',$_GET['selected_media_type']);
		
		
		$mediatype_location =  'media.mediaType_id';
		$interest_location =  'interest_media.interest_id';

		$where_in['0'] = $mediatype_location;
		$where_in['1'] = $selected_media_type;
		$where_ins['0'] = $where_in;
		
		$where_ink['0'] = $interest_location;
		$where_ink['1'] = $selected_interest;
		$where_ins['1'] = $where_ink;
		
		$select = 'media.media_id,media.title,media.media_desc,media_types.mediaType_title,feature_images.path';
		
		if(!($media = $this->media_model->getmedia(array(),$where_ins,$select)))
				{
					$media['0'] = "no-data";	
				}

				//$messages = "Hi..."; 
			echo $_GET['callback'] .'('. json_encode($media) . ')';
			//echo json_encode($messages);
			exit;	
	}
	
	public function getMedia()
	{
		
		$ids = explode('_',$_GET['id']);
		$where = array('media.media_id' => $ids['1'] );
		
		$select = 'media.media_id,media.title,media.media_desc,media.mediaType_id,media_types.mediaType_title,media.url';
		
		if(!($media = $this->media_model->getmedia($where,array(),$select)))
				{
					$media['0'] = "no-data";	
				}

				//$messages = "Hi..."; 
			echo $_GET['callback'] .'('. json_encode($media) . ')';
			//echo json_encode($messages);
			exit;	
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */