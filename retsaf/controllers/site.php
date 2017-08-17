<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends CI_Controller {
	var $awsAccessKey = "AKIAJ2RVZ5W24XMUFG5A"; 
	var $awsSecretKey = "7GiP5Tidb/Qff0cLn41VnlHgKcJQ4kdUTFOIm4wz";
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
		
		//$this->load->model('media_model');
		//$this->load->model('interest_model');
		$this->load->model('site_model');
		$this->load->model('images_model');
		$this->load->model('admin/gallery_model');
		require_once(APPPATH.'libraries/S3.php');
		//$this->load->library('pagination');	
		//$this->load->library('session');
	}

	public function index()
	{
		
	}
	
	public function getCityAndState()
	{				
			$where_in['0'] = 'historical_sites.interest_id';
			$where_in['1'] = explode(',',$_GET['interest']);
			$where_ins['0'] = $where_in;
			
			if(!($states = $this->site_model->getCityOrState('state',($_GET['interest']!='' ? $where_ins : array()))))
				{
					$states['0'] = "no-data";	
				}
		
			if(!($cities = $this->site_model->getCityOrState('city',($_GET['interest']!='' ? $where_ins : array()))))
				{
					$cities['0'] = "no-data";	
				}
			
			$list['0']=$states;
			$list['1']=$cities;	
		
		echo $_GET['callback'] .'('. json_encode($list) . ')';
		exit;
		
	}
	
	public function getCityAndStateForFree()
	{				
			
			$where_in1['0'] = 'historical_sites.app_type';
			$where_in1['1'] = array(2);
			$where_ins['0'] = $where_in1;
			
			//$_GET['interest']='';
			
			if(trim($_GET['interest']!=''))
			{
				$where_in['0'] = 'historical_sites.interest_id';
				$where_in['1'] = explode(',',$_GET['interest']);
				$where_ins['1'] = $where_in;
			}
			
			
			if(!($states = $this->site_model->getCityOrState('state',$where_ins)))
				{
					$states['0'] = "no-data";	
				}
		
			if(!($cities = $this->site_model->getCityOrState('city',$where_ins)))
				{
					$cities['0'] = "no-data";	
				}
			
			$list['0']=$states;
			$list['1']=$cities;	
		
		echo $_GET['callback'] .'('. json_encode($list) . ')';
		exit;
		
	}
	
	public function getSites()
	{
		$cities = explode(',',$_GET['selected_cities']);
		$interests = explode(',',$_GET['selected_interests']);
		//$starting_location = $_GET['start']; 
		$choice = $_GET['choice'];	
		
		$select='historical_sites.site_id,historical_sites.site_name,historical_sites.longitude,historical_sites.latitude,historical_sites.location,historical_sites.state,historical_sites.city,historical_sites.description,feature_images.path';
		
		$where_in['0'] = 'historical_sites.city';
		$where_in['1'] = $cities;
		$where_ins['0'] = $where_in;
		
		$where_in1['0'] = 'historical_sites.state';
		$where_in1['1'] = $cities;
		$where_ins['1'] = $where_in1;
		
		if($_GET['selected_interests']!='')
		{
			$where_in2['0'] = 'historical_sites.interest_id';
			$where_in2['1'] = $interests;
			$where_ins['2'] = $where_in2;
		}
		//$like = array('location' => $search_city , 'location' => $search_state);
		
		if(!($sitelist = $this->site_model->getSites(array(),$where_ins,array(),$select)))
				{
					$sitelist['0'] = "no-data";	
				}

				//$messages = "Hi..."; 
			echo $_GET['callback'] .'('. json_encode($sitelist) . ')';
			//echo json_encode($messages);
			exit;	
	}
	
	public function getSitesForFreeApp()
	{
		$cities = explode(',',$_GET['selected_cities']);
		$interests = explode(',',$_GET['selected_interests']);
		//$starting_location = $_GET['start']; 
		$choice = $_GET['choice'];	
		
		$select='historical_sites.site_id,historical_sites.site_name,historical_sites.longitude,historical_sites.latitude,historical_sites.location,historical_sites.state,historical_sites.city,historical_sites.description,feature_images.path';
		
		$where = array();
		/*if(isset($_GET['freeOrPaid']) && $_GET['freeOrPaid']!=NULL)
		{
			$where['historical_sites.app_type'] = $_GET['freeOrPaid'];
		}*/
		$where['historical_sites.app_type'] = 2;
		
		$where_in['0'] = 'historical_sites.city';
		$where_in['1'] = $cities;
		$where_ins['0'] = $where_in;
		
		$where_in1['0'] = 'historical_sites.state';
		$where_in1['1'] = $cities;
		$where_ins['1'] = $where_in1;
		
		if($_GET['selected_interests']!='')
		{
			$where_in2['0'] = 'historical_sites.interest_id';
			$where_in2['1'] = $interests;
			$where_ins['2'] = $where_in2;
		}
		//$like = array('location' => $search_city , 'location' => $search_state);
		
		if(!($sitelist = $this->site_model->getSitesForFreeApp($where,$where_ins,array(),$select)))
				{
					$sitelist['0'] = "no-data";	
				}

				//$messages = "Hi..."; 
			echo $_GET['callback'] .'('. json_encode($sitelist) . ')';
			//echo json_encode($messages);
			exit;	
	}
	
	
	public function getSelectedSitesForFreeApp()
	{
		$site_ids = explode(',',$_GET['selected_sites']);
		
		$select='historical_sites.site_id,historical_sites.site_name,historical_sites.longitude,historical_sites.latitude,historical_sites.location,historical_sites.state,historical_sites.city,historical_sites.description,feature_images.path';
		
		$where = array();
		/*if(isset($_GET['freeOrPaid']) && $_GET['freeOrPaid']!=NULL)
		{
			$where['historical_sites.app_type'] = $_GET['freeOrPaid'];
		}*/
		$where['historical_sites.app_type'] = 2;
		
		$where_in['0'] = 'historical_sites.site_id';
		$where_in['1'] = $site_ids;
		$where_ins['0'] = $where_in;
		
		//$like = array('location' => $search_city , 'location' => $search_state);
		
		if(!($sitelist = $this->site_model->getSitesForFreeApp($where,$where_ins,array(),$select)))
				{
					$sitelist['0'] = "no-data";	
				}

				//$messages = "Hi..."; 
			echo $_GET['callback'] .'('. json_encode($sitelist) . ')';
			//echo json_encode($messages);
			exit;	
	}
	
	public function getSelectedSites()
	{
		$site_ids = explode(',',$_GET['selected_sites']);
		
		$select='historical_sites.site_id,historical_sites.site_name,historical_sites.longitude,historical_sites.latitude,historical_sites.location,historical_sites.state,historical_sites.city,historical_sites.description,feature_images.path';
		
		$where_in['0'] = 'historical_sites.site_id';
		$where_in['1'] = $site_ids;
		$where_ins['0'] = $where_in;
		
		//$like = array('location' => $search_city , 'location' => $search_state);
		
		if(!($sitelist = $this->site_model->getSites(array(),$where_ins,array(),$select)))
				{
					$sitelist['0'] = "no-data";	
				}

				//$messages = "Hi..."; 
			echo $_GET['callback'] .'('. json_encode($sitelist) . ')';
			//echo json_encode($messages);
			exit;	
	}
	
	public function XlRead()
	{
	$this->load->view('UploadEvents');
	
	}

        public function test1()
	{
	 $this->load->view('UploadPlaces');
	}
	
	public function Autocompletetag()
    	{
         $AutoC = explode(',',$_GET['AutoC']);
         
         $select='Place_detail.Category';
         
         $like_in['0'] = 'Place_detail.Category';
         $like_in['1'] = $AutoC;

         if(!($sitelist = $this->site_model->getPlacesTags($like_in,$select)))
	 {
	  	$sitelist['0'] = "no-data";	
	 }
	 echo $_GET['callback'] .'('. json_encode($sitelist) . ')';
	
	 exit;
    	}
	
        public function AutocompleteBox()
        {
         $AutoC = explode(',',$_GET['AutoC']);

         $select='Itineraries_store.SrNo,Itineraries_store.TourName,Itineraries_store.FbUserId,Itineraries_store.Detail,Itineraries_store.Profile,Itineraries_store.Tags,count(Itineraries_Likes.SrNo) as countlike';

         
         $like_in['0'] = 'Itineraries_store.Tags';
         $like_in['1'] = $AutoC;

         if(!($sitelist = $this->site_model->getTags($like_in,$select)))
	 {
	  $sitelist['0'] = "no-data";	
	 }
			
	 echo $_GET['callback'] .'('. json_encode($sitelist) . ')';
	
	 exit;
        }
	
	public function getSelectedPlace()
	{
		$category = explode(',',$_GET['Category']);

		$select='Place_detail.PlaceIds,Place_detail.LatLng,Place_detail.Category,Place_detail.Detail';
		$select1='Events_detail.PlaceIds,Events_detail.LatLng,Events_detail.Category,Events_detail.Detail';
        
        	$like_in['0'] = 'Place_detail.Category';
        	$like_in['1'] = $category;

        	$like_in1['0'] = 'Events_detail.Category';
        	$like_in1['1'] = $category;
		//$like['0'] = $like_in;
		
		//$like = array('location' => $search_city , 'location' => $search_state);
		
		if(!($sitelist = $this->site_model->getPlaces(array(),array(),$like_in,$select,true)))
		{
				$sitelist['0'] = "no-data";	
		}

		if(!($sitelist1 = $this->site_model->getEvents(array(),array(),$like_in1,$select1,true)))
		{
			echo $_GET['callback'] .'('. json_encode($sitelist) . ')';
			exit;	
		}
		elseif($sitelist['0'] == "no-data")	
		{
			echo $_GET['callback'] .'('. json_encode($sitelist1) . ')';
			exit;
		}
		
		
		
		$arr=array_merge($sitelist , $sitelist1);
		echo $_GET['callback'] .'('. json_encode($arr) . ')';
		exit;
				
	}

	public function getSelectedPlaceDetail()
	{
		$Place_ids = $_GET['selected_places'];
		
		$select='Place_detail.PlaceIds,Place_detail.LatLng,Place_detail.Category,Place_detail.Detail,Place_detail.DateOfEstablishment';
		
		$where_in['0'] = 'Place_detail.PlaceIds';
		$where_in['1'] = $Place_ids;
		$where_ins['0'] = $where_in;
		
		if(!($sitelist = $this->site_model->getPlacesDetail($where_ins,$select)))
				{
					$sitelist['0'] = "no-data";	
				}

				
			echo $_GET['callback'] .'('. json_encode($sitelist) . ')';
			
			exit;	
	}
	
	public function add_save_tour()
	{
		$name = $_GET['Tour_name'];
		//$starting_location = $_GET['start']; 
		$userId = $_GET['userid'];	
		$Detail = $_GET['detail'];
                $Profile = $_GET['ProfileTour'];
		$tag = $_GET['tag'];
		
		$sitelist=$this->gallery_model->add_tour($name,$userId,$Detail,$Profile,$tag);		
		
                echo $_GET['callback'] .'('. json_encode($sitelist) . ')';
		exit;
	}

	public function getPublicTour()
	{	
		$Prefarance  = explode(',',$_GET['Prefarance']);
	
	 	$like_in['0'] = 'Itineraries_store.Profile';
        	$like_in['1'] = $Prefarance;
        
        	$select='Itineraries_store.SrNo,Itineraries_store.TourName,Itineraries_store.FbUserId,Itineraries_store.Detail,Itineraries_store.Profile,count(Itineraries_Likes.SrNo) as countlike';
        	if(!($sitelist = $this->site_model->getPublicPlaces(array(),array(),$like_in,$select,true)))
				{
					$sitelist['0'] = "no-data";	
				}
				
		echo $_GET['callback'] .'('. json_encode($sitelist) . ')';
			
		exit;	
	}
	
	public function set_like()
	{
		$srno  = $_GET['SRno'];
		$user  = $_GET['U_id'];
		
		$where_in['0'] = 'Itineraries_Likes.SrNo';
		$where_in['1'] = $srno;
		$where_ins['0'] = $where_in;
		
		$where_in1['0'] = 'Itineraries_Likes.FbUserId';
		$where_in1['1'] = $user;
		$where_ins['1'] = $where_in1;
		
		$select='Itineraries_Likes.SrNo';
		
		if(!($sitelist = $this->site_model->getLikeDetail($where_ins,$select,$srno,$user)))
				{
					$sitelist['0'] = "no-data";	
				}
				
		echo $_GET['callback'] .'('. json_encode($sitelist) . ')';
			
		exit;	
	} 
	
    	public function getAllTour()
	{
		$userid = $_GET['Userid'];
		$Prefarance  = $_GET['Prefarance'];

                
                $select='Itineraries_store.SrNo,Itineraries_store.TourName,Itineraries_store.FbUserId,Itineraries_store.Detail,Itineraries_store.Profile,Itineraries_store.Tags';
                
		
		
		$where_in['0'] = 'Itineraries_store.FbUserId';
		$where_in['1'] = $userid;
		$where_ins['0'] = $where_in;
		
		//$like = array('location' => $search_city , 'location' => $search_state);
		
		if(!($sitelist = $this->site_model->getTour(array(),$where_ins,array(),$select)))
				{
					$sitelist['0'] = "no-data";	
				}

			//$messages = "Hi..."; 
			echo $_GET['callback'] .'('. json_encode($sitelist) . ')';
			//echo json_encode($messages);
			exit;	
	}

	


    public function getDetailTour()
	{
		$srno = $_GET['srno'];

                $select='Itineraries_store.SrNo,Itineraries_store.FbUserId,Itineraries_store.TourName,Itineraries_store.Detail,Itineraries_store.Profile,Itineraries_store.Tags';
		
		$where_in['0'] = 'Itineraries_store.SrNo';
		$where_in['1'] = $srno;
		$where_ins['0'] = $where_in;
		
		//$like = array('location' => $search_city , 'location' => $search_state);
		
		if(!($sitelist = $this->site_model->getTour(array(),$where_ins,array(),$select)))
				{
					$sitelist['0'] = "no-data";	
				}

			//$messages = "Hi..."; 
			echo $_GET['callback'] .'('. json_encode($sitelist) . ')';
			//echo json_encode($messages);
			exit;	
	}
	
	public function getPlacesList()
	{
		$placeId = explode(',',$_GET['placeId']);

        	
		
		$where_in['0'] = 'Place_detail.PlaceIds';
		$where_in['1'] = $placeId;
		$where_ins = $where_in;
		
		//$like = array('location' => $search_city , 'location' => $search_state);
		
		if(!($sitelist = $this->site_model->getTourList($where_ins)))
		{
			$sitelist['0'] = "no-data";	
		}

		//$messages = "Hi..."; 
		echo $_GET['callback'] .'('. json_encode($sitelist) . ')';
		//echo json_encode($messages);
		exit;	
	}


    public function DeleteTour()
	{
		$srno = $_GET['srno'];
		
		if(!($sitelist = $this->site_model->Del_Tour($srno)))
				{
					$sitelist['0'] = "no-data Updated";	
				}

		echo $_GET['callback'] .'('. json_encode($sitelist) . ')';
			
		exit;	
	}
        
    public function EditTour()
	{
		$srno = $_GET['srno'];
		$name =  $_GET['Tour_name'];
		$Detail =  $_GET['detail'];
		$Profile =  $_GET['ProfileTour'];
		
		$update =array(
				'TourName' => $name,
				'Detail' => $Detail,
                'Profile' => $Profile
		);
		
		if(!($sitelist = $this->site_model->Edit_Tour($update,$srno)))
				{
					$sitelist['0'] = "no-data";	
				}

			//$messages = "Hi..."; 
			echo $_GET['callback'] .'('. json_encode($sitelist) . ')';
			//echo json_encode($messages);
			exit;	
	}
	
	public function Shared()
	{
	  $data['hello']=$_GET['Tour_Id'];
	  $this->load->view('Shared_view',$data);
	}
	
    public function SharedEvents()
	{
	  $this->load->view('UploadEvents');
	}
	
	public function SharedPlaces()
	{
	  $this->load->view('UploadPlaces');
	}
	
	public function Deals()
	{
	  $this->load->view('UploadDeals');
	}

	public function Find_Events()
	{
	  $placeId = $_POST['placeId'];
          
      $select ='Place_detail.PlaceIds,Place_detail.LatLng,Place_detail.Category,Place_detail.Detail,Place_detail.DateOfEstablishment';
		
	  $where_in['0'] = 'Place_detail.PlaceIds';
	  $where_in['1'] = $placeId;
	  $where_ins['0'] = $where_in;
          
      if(!($sitelist = $this->site_model->getPlacesDetail($where_ins,$select)))
	  {
		$sitelist['0'] = "no-data";
	  }
	  
	  
      echo json_encode($sitelist);		
	}
	
	public function Find_Events1()
	{
	  $placeId = $_POST['placeId'];
          
      $select ='Events_detail.PlaceIds,Events_detail.LatLng,Events_detail.Category,Events_detail.Detail,Events_detail.DateOfEstablishment';
		
	  $where_in['0'] = 'Events_detail.PlaceIds';
	  $where_in['1'] = $placeId;
	  $where_ins['0'] = $where_in;
          
      if(!($sitelist = $this->site_model->getEventsDetail($where_ins,$select)))
	  {
		$sitelist['0'] = "no-data";
	  }
      		echo json_encode($sitelist);
	}

    public function SEvents()
	{	
		//parse_str($_POST['formdata'], $formdata);
		
		$data=array();
		$tag = $_POST['Tag'];
	 	$placeId = $_POST['PlaceId'];
	 	$venueId = $_POST['VenueId'];

	 	$status = $_POST['Status'];
 		
 		$f_LatLng='{ "H":'.$_POST['F_lat'].', "L":'.$_POST['F_lon'].'}';
	 	$LatLng='{ "H":'.$_POST['G_lat'].', "L":'.$_POST['G_lon'].'}';

        if(isset($_FILES['fileToUpload']['name']) && (!empty($_FILES['fileToUpload']['name'])))
		{
			// $config = array(
			// 			'allowed_types' => 'png|jpg',
			// 			'upload_path' =>"./event_images",
			// 			'max_size' => 2000000000,
			// 			'overwrite' => TRUE,
			// 			'remove_spaces' => TRUE
			// 			);
			// $this->load->library('upload', $config);

			// if (!$this->upload->do_upload('fileToUpload'))
			// {                   
			// 	$error = $this->upload->display_errors();	                   
			// }
			// else
			// {   
				// $error = 'OK';
				$s3 = new S3($this->awsAccessKey, $this->awsSecretKey);
				$fileName = $_FILES['fileToUpload']['name'];
		        $fileTempName = $_FILES['fileToUpload']['tmp_name'];             
		        $newfilename = round(microtime(true)) . '.' . $fileName;
		        //we'll continue our script from here in the next step!
		        if ($s3->putObjectFile($fileTempName, "retail-safari", $newfilename, S3::ACL_PUBLIC_READ)) 
		        {
		            //echo "We successfully uploaded your file.";
		        }
		        else
		        {
		            //echo "Something went wrong while uploading your file... sorry.";
		        }


				$path = $newfilename;
      			$detail ='{ "name":"'.$_POST['name'].'","address":"'.$_POST['add'].'","price" :"","discount" :"","date":"'.$_POST['bday'].'","startTime":"'.$_POST['s_time'].'","endTime":"'.$_POST['usr_time'].'","about":"'.$_POST['Description'].'","Reviews":"expert review","type":"'.$_POST['Tag'].'","Info_1":"'.$_POST['Info_1'].'","Info_2":"'.$_POST['Info_2'].'","Info_3":"'.$_POST['Info_3'].'","Phone":"'.$_POST['Phone'].'","Website":"'.$_POST['Website'].'","Path":"'.$path.'"}';
      			if(!($sitelist = $this->site_model->addEventsPlaces($tag,$detail,$LatLng,$f_LatLng,$placeId,$venueId,$status)))
	  			{
					$sitelist['0'] = ["no-data"];	
	  			}
     			//echo json_encode($sitelist);		
     		// }
		}
		else
		{
			$path = '';
			$detail ='{ "name":"'.$_POST['name'].'","address":"'.$_POST['add'].'","price" :"","discount" :"","date":"'.$_POST['bday'].'","startTime":"'.$_POST['s_time'].'","endTime":"'.$_POST['usr_time'].'","about":"'.$_POST['Description'].'","Reviews":"expert review","type":"'.$_POST['Tag'].'","Info_1":"'.$_POST['Info_1'].'","Info_2":"'.$_POST['Info_2'].'","Info_3":"'.$_POST['Info_3'].'","Phone":"'.$_POST['Phone'].'","Website":"'.$_POST['Website'].'","Path":"'.$path.'"}';
      		if(!($sitelist = $this->site_model->addEventsPlaces($tag,$detail,$LatLng,$placeId,$status)))
	  		{
				$sitelist['0'] = "no-data";	
	  		}
     		//echo json_encode($sitelist);	
		}		
		redirect("site/SharedEvents");
	}
	
	
	public function S_Events()
	{
		parse_str($_POST['formdata'], $formdata);
		
		$data=array();
	 	$tag = $formdata['Tag'];
	 	$placeId = $formdata['PlaceId'];
	 	$status = $formdata['Status'];

	 	
        $LatLng='{ "H":'.$formdata['lat'].', "L":'.$formdata['lon'].'}';

	 	if(isset($_FILES['fileToUpload']['name']) && (!empty($_FILES['fileToUpload']['name'])))
		{
			$config = array(
						'allowed_types' => 'png|jpg',
						'upload_path' =>"./event_images",
						'max_size' => 2000000000,
						'overwrite' => TRUE,
						'remove_spaces' => TRUE
						);
			$this->load->library('upload', $config);

			if (!$this->upload->do_upload('fileToUpload'))
			{                   
				$error = $this->upload->display_errors();	                   
			}
			else
			{   
				$error = 'OK';
				$path = 'event_images/'.$_FILES['fileToUpload']['name'];
				$detail='{"name":"'.$formdata['name'].'","address":"'.$formdata['starting_location'].'","price" :"","discount" :"","date":"'.$formdata['bday'].'","startTime":"'.$formdata['s_time'].'","endTime":"'.$formdata['usr_time'].'","phone":"'.$formdata['phone'].'","about":"'.$formdata['Description'].'","Reviews":"expert review","type":"events","Info_1":"'.$formdata['Info_1'].'","Info_2":"'.$formdata['Info_2'].'","Info_3":"'.$formdata['Info_3'].'","Path":"'.$path.'"}';
      			if(!($sitelist = $this->site_model->addEvents($tag,$detail,$LatLng,$placeId,$status,$path)))
	  			{
					$sitelist['0'] = "no-data";	
	  			}
     			echo json_encode($sitelist);		
     		}
		}
		else
		{
			$sitelist['0'] = "no-data";	
			echo json_encode($sitelist);	
		}
	}
	

	public function singleSite()
	{
		$site_ids = explode('_',$_GET['id']);
		$where = array('historical_sites.site_id' => $site_ids['1']);
		$select = 'historical_sites.site_id,historical_sites.site_name,historical_sites.longitude,historical_sites.latitude,historical_sites.location,historical_sites.state,historical_sites.city,historical_sites.description,historical_sites.contact,historical_sites.price,historical_sites.hours,historical_sites.web_site,historical_sites.gallery_id,feature_images.path';
		if(!($site = $this->site_model->getSites($where,array(),array(),$select)))
				{
					$site['0'] = "no-data";	
				}
		echo $_GET['callback'] .'('. json_encode($site) . ')';
		exit;
	}
	
	public function get_images()
	{
		$gallery_ids = explode('_',$_GET['id']);
		$where = array('gallery_id' => $gallery_ids['1']);
		if(!($images = $this->images_model->get_images($where)))
				{
					$images['0'] = "no-data";	
				}
		echo $_GET['callback'] .'('. json_encode($images) . ')';
		exit; 
	}	
	
	public function get_sites_of_selected_states()
	{
		   $where_in1['0'] = 'historical_sites.state';
		   $where_in1['1'] = explode(',',$_GET['list_of_selected_states']);
			$where_in2['0'] = 'historical_sites.interest_id';
		   $where_in2['1'] = explode(',',$_GET['interest_id']);
		   $where_ins1['0'] = $where_in1;
		   $where_ins2['0'] = $where_in2;
		   if(!($cities = $this->site_model->getCityOfSelectedState('city',($_GET['list_of_selected_states']!='' ? $where_ins1 : array()),($_GET['interest_id']!='' ? $where_ins2 : array()))))
				{
						$cities['0'] = "no-data";	
				}
				
		   $list['0']=$cities;	
	
	   echo $_GET['callback'] .'('. json_encode($list) . ')';
	   exit;     
	}
	
	public function get_cities_and_sites_for_selected_state()
	{
		$states = (isset($_GET['list_of_selected_states']) && $_GET['list_of_selected_states']!='') ? explode(',',$_GET['list_of_selected_states']): array();
		$interests = (isset($_GET['interest_id']) && $_GET['interest_id']!='') ? explode(',',$_GET['interest_id']): NULL;
		
		if(!empty($states))
		{
			$select='historical_sites.site_id,historical_sites.site_name,historical_sites.location,historical_sites.state,historical_sites.city,historical_sites.description,feature_images.path';
			$where_in1['0'] = 'historical_sites.state';
			$where_in1['1'] = $states;
			$where_ins['0'] = $where_in1;
			
			if($interests!=NULL && !empty($interests))
			{
				$where_in2['0'] = 'historical_sites.interest_id';
				$where_in2['1'] = $interests;
				$where_ins['1'] = $where_in2;
			}
			
			
			
			//$site = $this->site_model->getSites(array(),$where_ins,array(),$select);
			$site_arr = array();
			if(!($sites = $this->site_model->getSites(array(),$where_ins,array(),$select)))
				{
						$site_arr['0'] = "no-data";	
				}else{
						//echo '<pre>';
						//print_r($sites);
						foreach($states as $state)
						{
							if(!isset($site_arr[$state]))
							{
								$site_arr[$state] = array();
							}
							foreach($sites as $site)
							{
								if($state==$site['state'])
								{
									if(!isset($site_arr[$state][$site['city']]))
									{
										$site_arr[$state][$site['city']] = array();
									}
									foreach($sites as $site1)
									{
										if($site['state']==$site1['state'] && $site['city'] == $site1['city'])
										{
											$site_already_exist_flag = false;
											if(!empty($site_arr[$site['state']][$site['city']]))
											{
												foreach($site_arr[$site['state']][$site['city']] as $site2)
												{
													if($site2['site_id']==$site1['site_id'])
													{
														$site_already_exist_flag=true;
													}
												}
											}
											
											if(!$site_already_exist_flag)
											{
												$site_arr[$site['state']][$site['city']][] = $site1;
												//ksort($site_arr[$site['state']][$site['city']]);
											}
										}
									}
								}
							}
							ksort($site_arr[$state]);
						}
						ksort($site_arr);
					}
			
				echo $_GET['callback'] .'('. json_encode($site_arr) . ')';
				exit;  
				
		}
	}
	
	public function get_cities_and_sites_for_selected_state_For_Free()
	{
		$states = (isset($_GET['list_of_selected_states']) && $_GET['list_of_selected_states']!='') ? explode(',',$_GET['list_of_selected_states']): array();
		$interests = (isset($_GET['interest_id']) && $_GET['interest_id']!='') ? explode(',',$_GET['interest_id']): NULL;
		//$states = array('ALABAMA','COLORADO');
		if(!empty($states))
		{
			$select='historical_sites.site_id,historical_sites.site_name,historical_sites.location,historical_sites.state,historical_sites.city,historical_sites.description,feature_images.path';
			$where_in1['0'] = 'historical_sites.state';
			$where_in1['1'] = $states;
			$where_ins['0'] = $where_in1;
			
			if($interests!=NULL && !empty($interests))
			{
				$where_in2['0'] = 'historical_sites.interest_id';
				$where_in2['1'] = $interests;
				$where_ins['1'] = $where_in2;
			}
			$where = array();
			$where['historical_sites.app_type'] = 2;
			
			
			//$site = $this->site_model->getSites(array(),$where_ins,array(),$select);
			$site_arr = array();
			if(!($sites = $this->site_model->getSitesForFreeApp($where,$where_ins,array(),$select)))
				{
						$site_arr['0'] = "no-data";	
				}else{
						//echo '<pre>';
						//print_r($sites);
						foreach($states as $state)
						{
							if(!isset($site_arr[$state]))
							{
								$site_arr[$state] = array();
							}
							foreach($sites as $site)
							{
								if($state==$site['state'])
								{
									if(!isset($site_arr[$state][$site['city']]))
									{
										$site_arr[$state][$site['city']] = array();
									}
									foreach($sites as $site1)
									{
										if($site['state']==$site1['state'] && $site['city'] == $site1['city'])
										{
											$site_already_exist_flag = false;
											if(!empty($site_arr[$site['state']][$site['city']]))
											{
												foreach($site_arr[$site['state']][$site['city']] as $site2)
												{
													if($site2['site_id']==$site1['site_id'])
													{
														$site_already_exist_flag=true;
													}
												}
											}
											
											if(!$site_already_exist_flag)
											{
												$site_arr[$site['state']][$site['city']][] = $site1;
												//ksort($site_arr[$site['state']][$site['city']]);
											}
										}
									}
								}
							}
							ksort($site_arr[$state]);
						}
						ksort($site_arr);
					}
			
				echo $_GET['callback'] .'('. json_encode($site_arr) . ')';
				exit;  
				
		}
	}
	
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
