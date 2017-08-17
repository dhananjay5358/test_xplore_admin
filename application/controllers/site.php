<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//new changes
class Site extends CI_Controller 
{
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
		$this->load->model('site_model');
		$this->load->model('images_model');
		$this->load->model('admin/gallery_model');
		require_once(APPPATH.'libraries/S3.php');
		$this->load->library('session');
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

        $select='Itineraries_store.SrNo,Itineraries_store.TourName,Itineraries_store.Identifier,Itineraries_store.FbUserId,Itineraries_store.Detail,Itineraries_store.Profile,Itineraries_store.Tags,Itineraries_store.Last_Update,count(Itineraries_Likes.SrNo) as countlike';

         
        $like_in['0'] = 'Itineraries_store.Tags';
        $like_in['1'] = $AutoC;

        if(!($sitelist = $this->site_model->getTags($like_in,$select)))
	  	{
	  		$sitelist['0'] = "no-data";	
	 	}
			
	 	echo $_GET['callback'] .'('. json_encode($sitelist) . ')';
	
		exit;
    }

    public function Events_list()
    {
    	if(!($sitelist = $this->site_model->getEvents_list()))
	  	{
	  		$sitelist['0'] = "no-data";	
	 	}
			
	 	echo $_GET['callback'] .'('. json_encode($sitelist) . ')';
	
		exit;
    }

    public function Events_list_native()
    {
    	if(!($sitelist = $this->site_model->getEvents_list()))
	  	{
	  		$sitelist['0'] = "no-data";	
	 	}
		echo json_encode($sitelist);
		
    }

    public function getGuideList()
    {
    	if(!($sitelist = $this->site_model->getGuide_list()))
	  	{
	  		$sitelist['0'] = "no-data";	
	 	}
		print_r($sitelist);		
	 	echo $_GET['callback'] .'('. json_encode($sitelist) . ')';
	
		exit;
    }

    public function Events_listFeaturedProfile()
    {
    	if(!($sitelist_event = $this->site_model->getEvents_list()))
	  	{
	  		$sitelist['0'] = "no-data";	
	 	}

	 	if(!($sitelist_info = $this->site_model->getPlaces_list_identifier()))
	  	{
	  		$sitelist['1'] = "no-data";	
	 	}

	 	$sitelist['0']=$sitelist_event;
		$sitelist['1']=$sitelist_info;	
			
	 	echo $_GET['callback'] .'('. json_encode($sitelist) . ')';
	
		exit;
    }

   public function Events_FeaturedRelated_places()
    {
        $Id =$_GET['id'];
        $identifier_f=$_GET['featured'];
        $identifier_r=$_GET['related'];
        $sitelist_featured = $sitelist_related = [];
//      print_r($identifier_f);
//      print_r($identifier_r);
        if(!empty($identifier_f)){
        $sitelist_featured = $this->site_model->getEvents_FeatureRelated_List($identifier_f);
        }

        if(!empty($identifier_r)){
        $sitelist_related = $this->site_model->getEvents_FeatureRelated_List($identifier_r);
        }

        //if($sitelist_featured)
        $sitelist['0']=$sitelist_featured;
        $sitelist['1']=$sitelist_related;
//      echo "<pre>"
//      print_r($sitelist);
        //exit();
        echo $_GET['callback'] .'('. json_encode($sitelist) . ')';
    }



	public function Events_Related_places()
    {
    	$Id =$_GET['id'];
    	$Identifier =$_GET['identifier'];
    	if(!empty($_GET['identifier']))
        {
    	if(!($sitelist = $this->site_model->getEvents_Related_List($Identifier,$Id)))
	  	{
	  		$sitelist['0'] = "no-data";	
	 	}
	 	}
	 	else
	 	{
        print_r("Place tag is empty");
        }

	 	//$sitelist['0']=$sitelist_event;
		echo $_GET['callback'] .'('. json_encode($sitelist) . ')';
	}

    public function ShowEvents_list($id){
    	if(!($sitelist_event = $this->site_model->getEvents_listProfile($id)))
	  	{
	  		$sitelist['0'] = "no-data";	
	 	}

	 	echo $_GET['callback'] .'('. json_encode($sitelist_event) . ')';
	
		exit;
    }

    public function UnfeaturedEvents_list()
    {
    	if(!($sitelist = $this->site_model->get_Unfeatured_Events_list()))
	  	{
	  		$sitelist['0'] = "no-data";	
	 	}
			
		echo $_GET['callback'] .'('. json_encode($sitelist) . ')';
	
		exit;
    }

     public function News_list()
    {
    	if(!($sitelist = $this->site_model->getNews_list()))
	  	{
	  		$sitelist['0'] = "no-data";	
	 	}
		//print_r($sitelist);exit();	
	 	echo $_GET['callback'] .'('. json_encode($sitelist) . ')';
	
		exit;
    }

    public function Deals_list()
    {
    	if(!($sitelist = $this->site_model->getDeals_list()))
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

	public function getSelectedPlaceNative()
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
			echo json_encode ($sitelist);
			exit;	
		}
		elseif($sitelist['0'] == "no-data")	
		{
			//echo $_GET['callback'] .'('. json_encode($sitelist1) . ')';
			echo json_encode ($sitelist1);
			exit;
		}
		
		
		
		$arr=array_merge($sitelist , $sitelist1);
		//echo $_GET['callback'] .'('. json_encode($arr) . ')';
		echo json_encode ($arr);
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
		$identifier = $_GET['identifier'];
		
		$sitelist=$this->gallery_model->add_tour($userId,$name,$Detail,$Profile,$tag,$identifier);		
		

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
			
			exit;	
	}
	
	public function viewPlaces()
	{
		$data['admin_header']=$this->load->view('admin/admin_header','',true);
		$this->load->view('admin/view_places',$data);
	}

	public function viewEvents()
	{
		$data['admin_header']=$this->load->view('admin/admin_header','',true);
		$this->load->view('admin/view_events',$data);
	}

	public function viewDeals()
	{
		$data['admin_header']=$this->load->view('admin/admin_header','',true);
		$this->load->view('admin/view_deals',$data);
	}

	public function viewPlaces_kendo()
	{
		$select ='Place_detail.PlaceIds,Place_detail.Place_Type,Place_detail.SrNumber,Place_detail.Venueid,Place_detail.LatLng,Place_detail.Foresquare_lat_lng,Place_detail.Category,Place_detail.Detail,Place_detail.DateOfEstablishment,Place_detail.Event_Deal_name,Place_detail.place_identifier';
		
	   	$Like_in['0'] = 'Place_detail.Event_Deal_name';
	   	$Like_ins['0'] = $Like_in;
	  	// $like_in['0'] = 'Place_detail.Event_Deal_name';
	  	// $like_in['1'] = $category;
	      
	  	$place = $this->site_model->get_places_kendo($select);
	  	
	  	$data['admin_header']=$this->load->view('admin/admin_header','',true);

		$places=array();
		foreach ($place as $value) 
		{	
			// echo "<pre>";
			// print_r($value);
				
			$places_=array();

			$places_["SrNumber"]=$value['SrNumber'];
			$places_["PlaceIds"]=$value['PlaceIds'];
			$places_["Venueid"]=$value['Venueid'];
			$places_["Category"]=$value['Category'];
			$places_["Event_Deal_name"]=$value['Event_Deal_name'];
			$places_["Place_Type"]=$value['Place_Type'];
			$places_["PlaceIdentifier"]=$value['place_identifier'];
			$places_["DateOfEstablishment"]=$value['DateOfEstablishment'];
			
		//	$places_["LatLng"]=$value['LatLng'];
		//	$places_["FLatLong"]=$value['Foresquare_lat_lng'];			

			$LatLng=json_decode($value['LatLng'],true);			
		//	print_r($LatLng);
			$latlng_H="";
			if(isset($LatLng['H']))
			{
			$places_["latlng_H"]=$LatLng['H'];
			}
			$latlng_L="";	
			if(isset($LatLng['L']))
			{
			$places_["latlng_L"]=$LatLng['L'];
			}

			$Foresquare_lat_lng=json_decode($value['Foresquare_lat_lng'],true);			
			
			$Foresquare_lat_lng_H="";
			if(isset($Foresquare_lat_lng['H']))
			{
			$places_["Foresquare_lat_lng_H"]=$Foresquare_lat_lng['H'];
			}
			$Foresquare_lat_lng_L="";	
			if(isset($Foresquare_lat_lng['L']))
			{
			$places_["Foresquare_lat_lng_L"]=$Foresquare_lat_lng['L'];
			}


			$detail=json_decode($value['Detail'],true);
			//echo "<pre>";
			//print_r($detail);
			//print_r($places_['SrNumber']);
			$name="";
			if(isset($detail['name']))
			{
			$places_["name"]=$detail['name'];
			}

			// if(isset($detail['about']))
			// {
			// $places_["about"]=$detail['about'];
			// }
			
			if(isset($detail['address']))
			{
			$places_["address"]=$detail['address'];
			}
			if(isset($detail['price']))
			{
			$places_["price"]=$detail['price'];
			}
			if(isset($detail['date']))
			{
			$places_["date"]=$detail['date'];
			}
			if(isset($detail['startTime']))
			{
			$places_["startTime"]=$detail['startTime'];
			}
			if(isset($detail['endTime']))
			{
			$places_["endTime"]=$detail['endTime'];
			}
			
			if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $detail['about'])) 
			{	
				$about_decoded=base64_decode($detail['about']);
				$places_["about"]=$about_decoded;
			}
			else
			{
				$places_["about"]=$detail['about'];
			}

			if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $detail['about'])) 
			{	
				$about_decoded=base64_decode($detail['about']);
				$abt_info= strip_tags($about_decoded);  

				$abt_d= implode(' ', array_slice(explode(' ', $abt_info), 0, 10));

				$places_["abt"]=$abt_d;
			}
			else
			{
				$abt_info= strip_tags($detail['about']);
				$abt_d= implode(' ', array_slice(explode(' ', $abt_info), 0, 10));
				$places_["abt"]=$abt_d;
			}
			
			//$places_["abt"]= implode(' ', array_slice(explode(' ', $detail['about']), 0, 10));
			if(isset($detail['businessId']))
			{
			$places_["businessId"]=$detail['businessId'];
			}

			if(isset($detail['rating']))
			{
			$places_["rating"]=$detail['rating'];
			}
	
			if(isset($detail['Reviews']))
			{
			$places_["Reviews"]=$detail['Reviews'];
			}
			if(isset($detail['type']))
			{
			$places_["type"]=$detail['type'];
			}
			if(isset($detail['Info_1']))
			{
			$places_["Info_1"]=$detail['Info_1'];
			}
			if(isset($detail['Info_2']))
			{
			$places_["Info_2"]=$detail['Info_2'];
			}
			if(isset($detail['Info_3']))
			{
			$places_["Info_3"]=$detail['Info_3'];
			}
			if(isset($detail['Phone']))
			{
			$places_["Phone"]=$detail['Phone'];
			}

			if(isset($detail['Like']))
			{
			$places_["Like"]=$detail['Like'];
			}

			if(isset($detail['Website']))
			{
			$places_["Website"]=$detail['Website'];
			}
			if(isset($detail['Path']))
			{
			$places_["Path"]=$detail['Path'];
			}
			if(isset($detail['profileimage']))
			{
			$places_["profileimage"]=$detail['profileimage'];
			}
			if(isset($detail['isClosed']))
			{
			$places_["isClosed"]=$detail['isClosed'];
			}
			
			if(isset($detail['hours']))
			{
			$places_["hours"]=$detail['hours'];
			}
			array_push($places,$places_);
			
		}

			// echo "<pre>";
			// print_r($places);
			// exit();
			echo json_encode($places);
			//echo htmlentities (print_r (json_decode ($places), true));

	}

	public function remove_custom_places()
	{
		if($_POST['status'] === 'remove')
		{
			$ids = $_POST['id'];
			$this->site_model->remove_places_updated($ids);		
		}
		exit;
	}

	

	public function edit_Places()
	{
		$data=array();
		$Id = $_POST['Id'];
		$tag = $_POST['tag_places'];
	 	$placeId = $_POST['PlaceId'];
	 	$venueId = $_POST['VenueId'];
	 	$status = $_POST['Status'];
	 	$imgList = $_POST['id_image_path'];
	 	$proimage = $_POST['id_image'];
	 	$PlaceIdentifier = $_POST['PlaceIdentifier'];

	 	
 		
 		if((!empty($_POST['F_lat']))&& (!empty($_POST['F_lon']))&& (!empty($_POST['G_lat']))&&(!empty($_POST['G_lon'])))
		{	
			$f_LatLng='{ "H":'.$_POST['F_lat'].', "L":'.$_POST['F_lon'].'}';
		 	$LatLng='{ "H":'.$_POST['G_lat'].', "L":'.$_POST['G_lon'].'}';
	 	}
	 	else
	 	{
	 		
	 			$f_latitude=explode("_",$_POST['F_Lat_Long']);
	 			$f_longitude=explode("_",$_POST['G_Lat_Long']);

	 			$f_LatLng='{ "H":'.$f_latitude[0].', "L":'.$f_latitude[1].'}';
	 			$LatLng='{ "H":'.$f_longitude[0].', "L":'.$f_longitude[1].'}';
	 	}

	 	//$imgList='';

        if(isset($_FILES['fileToUpload']['name'][0]) && (!empty($_FILES['fileToUpload']['name'][0])))
		{
			for($i=0;$i<count($_FILES['fileToUpload']['name']);$i++)
		 	{
		 		$s3 = new S3($this->awsAccessKey, $this->awsSecretKey);
				$fileName = $_FILES['fileToUpload']['name'][$i];
		        $fileTempName = $_FILES['fileToUpload']['tmp_name'][$i];             
		        $newfilename = round(microtime(true)) . '.' . $fileName;

		        if($i==0)
		        {
		        	if($s3->putObjectFile($fileTempName, "retail-safari", $newfilename, S3::ACL_PUBLIC_READ))
		        	{
		        		$imgList=$newfilename;
		        	}
		        }
		    	else
		    	{
		    		if($s3->putObjectFile($fileTempName, "retail-safari", $newfilename, S3::ACL_PUBLIC_READ))
		        	{
		        		$imgList=$imgList.','.$newfilename;
		        	}
		    	}
		 	}

		 	if (isset($_POST["Path_image"]) && !empty($_POST["Path_image"])) 
		 	{
			 	for($i=0;$i<count($_POST['Path_image']);$i++)
			 	{
			 		if($_POST['Path_image'][$i] != '')
			 		{
			 			$imgList=$imgList.','.$_POST['Path_image'][$i];
			 		}
			 	}
			}
		 	
		 	
		 	if(isset($_FILES['ProfileToUpload']['name']) && (!empty($_FILES['ProfileToUpload']['name'])))
			{
				$s3 = new S3($this->awsAccessKey, $this->awsSecretKey);
				$fileName = $_FILES['ProfileToUpload']['name'];
		        $fileTempName = $_FILES['ProfileToUpload']['tmp_name'];             
		        $newfilename = round(microtime(true)) . '.' . $fileName;

		        if($s3->putObjectFile($fileTempName, "retail-safari", $newfilename, S3::ACL_PUBLIC_READ))
	        	{
	        		$proimage = $newfilename;
	        	}
			}
			else
			{
				if (isset($_POST["Profile"]) && !empty($_POST["Profile"])) 
		 		{
					$proimage = $_POST['Profile'];
				}
			}

			$e = array();
			$e['name'] = $_POST['u_name'];
		    $e['address']  = $_POST['add'];
		    $e['price'] = "";
		    $e['date'] = $_POST['bday'];
		    $e['startTime']  = $_POST['s_time'];
		    $e['endTime'] = $_POST['usr_time'];
		    $e['about'] = base64_encode($_POST['about']);
		    $e['Reviews']  = "";
		    $e['type'] = $_POST['type'];
		    $e['businessId'] =$_POST['businessId']; 
		    $e['rating'] = $_POST['rating'];
		    // $e['Info_1'] =  $_POST['Info_1'];
		    // $e['Info_2']  = $_POST['Info_2'];
		    // $e['Info_3'] =  $_POST['Info_3'];
		    $e['Phone'] = $_POST['phone'];
		   	$e['Like'] = $_POST['Like'];
		    $e['Website']  = $_POST['Website'];
		    $e['Path'] = $imgList;
		    $e['profileimage'] = $proimage;
		    $e['isClosed'] = "";
		    $e['hours'] = "";
  			$detail = json_encode($e);

  			$sitelist = $this->site_model->UpdatePlaces($Id,$tag,$detail,$LatLng,$f_LatLng,$placeId,$venueId,$status,$PlaceIdentifier);
  		// 	{
				// $sitelist = "no-data";	
  		// 	}
		}
		else
		{
			if (isset($_POST["Path_image"]) && !empty($_POST["Path_image"])) 
		 	{
				for($i=0;$i<count($_POST['Path_image']);$i++)
			 	{
			 		if($i==0)
			        {
			        	if($_POST['Path_image'][$i] != '')
				 		{
				 			$imgList=$_POST['Path_image'][$i];
				 		}
			        	
			        }
			    	else
			    	{
			    		if($_POST['Path_image'][$i] != '')
				 		{
				 			$imgList=$imgList.','.$_POST['Path_image'][$i];
				 		}
			    	}
			 	}
			}

		 	//$proimage='';
		 	if(isset($_FILES['ProfileToUpload']['name']) && (!empty($_FILES['ProfileToUpload']['name'])))
			{
				$s3 = new S3($this->awsAccessKey, $this->awsSecretKey);
				$fileName = $_FILES['ProfileToUpload']['name'];
		        $fileTempName = $_FILES['ProfileToUpload']['tmp_name'];             
		        $newfilename = round(microtime(true)) . '.' . $fileName;

		        if($s3->putObjectFile($fileTempName, "retail-safari", $newfilename, S3::ACL_PUBLIC_READ))
	        	{
	        		$proimage = $newfilename;
	        	}
			}
			else
			{
				if (isset($_POST["Profile"]) && !empty($_POST["Profile"])) 
		 		{
					$proimage = $_POST['Profile'];
				}
			}

			
			$e = array();
			$e['name'] = $_POST['u_name'];
		    $e['address']  = $_POST['add'];
		    $e['price'] = "";
		    $e['date'] = $_POST['bday'];
		    $e['startTime']  = $_POST['s_time'];
		    $e['endTime'] = $_POST['usr_time'];
		    $e['about'] = base64_encode($_POST['about']);
		    $e['Reviews']  = "";
		    $e['type'] = $_POST['type'];
		    $e['businessId'] =$_POST['businessId']; 
		    $e['rating'] = $_POST['rating'];
		    // $e['Info_1'] =  $_POST['Info_1'];
		    // $e['Info_2']  = $_POST['Info_2'];
		    // $e['Info_3'] =  $_POST['Info_3'];
		    $e['Phone'] = $_POST['phone'];
		    $e['Like'] = $_POST['Like'];
		    $e['Website']  = $_POST['Website'];
		    $e['Path'] = $imgList;
		    $e['profileimage'] = $proimage;
		    $e['isClosed'] = "";
		    $e['hours'] = "";

  			$detail = json_encode($e);
	//	print_r($f_LatLng);exit();  
    		$sitelist = $this->site_model->UpdatePlaces($Id,$tag,$detail,$LatLng,$f_LatLng,$placeId,$venueId,$status,$PlaceIdentifier);
	  	// 	{
				// $sitelist = "no-data";	
	  	// 	}
     		//echo json_encode($sitelist);	
		}

		// if($sitelist == "no-data")
		// {
		// 	$this->session->set_userdata('Upload_status', 'flase');
		// }
		// else
		// {
		// 	$this->session->set_userdata('Upload_status', 'true');
		// }
			
		redirect("site/viewPlaces");
	}

	public function edit_Events()
	{	
		$data=array();
		$Id = $_POST['Id'];
		$tag = $_POST['tag_places'];
	 	$placeId = $_POST['PlaceId'];
	 	$venueId = $_POST['VenueId'];
	 	$event_type = $_POST['feautured'];
	 	$Identifier = $_POST['Identifier'];
		$imgList = $_POST['id_image_path'];
	 	$proimage = $_POST['id_image'];

		if($placeId == '')
	 	{
	 		$placeId = $_POST['VenueId'];
	 	}

	 	if($placeId == '')
	 	{
	 		$placeId = random_string('alnum',20);
	 		$venueId = $placeId;
	 	}

	 	$status = $_POST['Status'];
 		
 		
 		
 		if($_POST['F_lat'] != "" && $_POST['F_lon'] != "")
 		{
 			if($_POST['F_Lat_Long'] != '')
 			{
 				$f_LatLng='{ "H":'.$_POST['F_lat'].', "L":'.$_POST['F_lon'].'}';
 			}
 			else
 			{
 				$f_LatLng='{ "H":0, "L":0 }';
 			}
 		}
 		else
 		{
 			$f_LatLng='{ "H":0, "L":0}';
 		}

 		$clat='';
 		$clon='';

 		if($_POST['custom_Lat_Long'] != "")
 		{
 			$latlng=explode("_", $_POST['custom_Lat_Long']);
 			if(count($latlng) == 2)
 			{
	 			$clat=$latlng[0];
	 			$clon=$latlng[1];
 			}
 		}

 		if($clat != "" && $clon != "")
 		{
 			$LatLng='{ "H":'.$clat.', "L":'.$clon.'}';
 		}
 		elseif($_POST['F_lat'] != "" && $_POST['F_lon'] != "")
 		{
 			if($_POST['F_Lat_Long'] != '')
 			{
 				$f_LatLng='{ "H":'.$_POST['F_lat'].', "L":'.$_POST['F_lon'].'}';
 			}
 			else
 			{
 				$f_LatLng='{ "H":0, "L":0 }';
 			}
 		}
 		else
 		{
 		// 	echo "hi";
 		// 	exit();
 		// 	$this->session->set_userdata('Upload_status', 'flase');
			// redirect("site/viewEvents");
			// exit();

			$f_latitude=explode("_",$_POST['F_Lat_Long']);
			$f_LatLng='{ "H":'.$f_latitude[0].', "L":'.$f_latitude[1].'}';
 		}

 		if($clat == "" && $clon == "")
 		{
 			$LatLng='{ "H":0, "L":0}';
 		}
	 	
	 	//$imgList='';

        if(isset($_FILES['fileToUpload']['name'][0]) && (!empty($_FILES['fileToUpload']['name'][0])))
		{
			for($i=0;$i<count($_FILES['fileToUpload']['name']);$i++)
		 	{
		 		$s3 = new S3($this->awsAccessKey, $this->awsSecretKey);
				$fileName = $_FILES['fileToUpload']['name'][$i];
		        $fileTempName = $_FILES['fileToUpload']['tmp_name'][$i];             
		        $newfilename = round(microtime(true)) . '.' . $fileName;

		        if($i==0)
		        {
		        	if($s3->putObjectFile($fileTempName, "retail-safari", $newfilename, S3::ACL_PUBLIC_READ))
		        	{
		        		$imgList=$newfilename;
		        	}
		        }
		    	else
		    	{
		    		if($s3->putObjectFile($fileTempName, "retail-safari", $newfilename, S3::ACL_PUBLIC_READ))
		        	{
		        		$imgList=$imgList.','.$newfilename;
		        	}
		    	}
		 	}
		 	
		 	if (isset($_POST["Path_image"]) && !empty($_POST["Path_image"])) 
		 	{
			 	for($i=0;$i<count($_POST['Path_image']);$i++)
			 	{
			 		if($_POST['Path_image'][$i] != '')
			 		{
			 			$imgList=$imgList.','.$_POST['Path_image'][$i];
			 		}
			 	}
			}

		 	//$proimage='';
		 	if(isset($_FILES['ProfileToUpload']['name']) && (!empty($_FILES['ProfileToUpload']['name'])))
			{
				$s3 = new S3($this->awsAccessKey, $this->awsSecretKey);
				$fileName = $_FILES['ProfileToUpload']['name'];
		        $fileTempName = $_FILES['ProfileToUpload']['tmp_name'];             
		        $newfilename = round(microtime(true)) . '.' . $fileName;

		        if($s3->putObjectFile($fileTempName, "retail-safari", $newfilename, S3::ACL_PUBLIC_READ))
	        	{
	        		$proimage = $newfilename;
	        	}
			}
			else
			{
				if (isset($_POST["Profile"]) && !empty($_POST["Profile"])) 
		 		{
					$proimage = $_POST['Profile'];
				}
			}

			
			$e = array();
			$e['name'] = $_POST['u_name'];
		    $e['address']  = $_POST['add'];
		    $e['price'] = $_POST['price'];
		    $e['discount'] = $_POST['Discount'];
		    $e['date'] = $_POST['startdate'];
		    $e['startTime']  = $_POST['startTime'];
		    $e['endTime'] = $_POST['endTime'];
		    $e['e_time'] = $_POST['e_time'];
		    $e['about'] = base64_encode($_POST['about']);
		    $e['Reviews']  = "";
		    $e['type'] = $_POST['type'];
		    // $e['Info_1'] =  $_POST['Info_1'];
		    // $e['Info_2']  = $_POST['Info_2'];
		    // $e['Info_3'] =  $_POST['Info_3'];
		    $e['Phone'] = $_POST['Phone'];
		    $e['Website']  = $_POST['website'];
		    $e['Path'] = $imgList;
		    $e['profileimage'] = $proimage;

		    $name = $_POST['u_name'];
		    $type = $_POST['type'];

  			$detail = json_encode($e);
  			
  			$sitelist = $this->site_model->update_events_data($Id,$tag,$detail,$LatLng,$f_LatLng,$placeId,$venueId,$status,$name,$type,$event_type,$Identifier);
  		// 	{
				// $sitelist = "no-data";	
  		// 	}
		}
		else
		{
			if (isset($_POST["Path_image"]) && !empty($_POST["Path_image"])) 
		 	{
				for($i=0;$i<count($_POST['Path_image']);$i++)
			 	{
			 		if($i==0)
			        {
			        	if($_POST['Path_image'][$i] != '')
				 		{
				 			$imgList=$_POST['Path_image'][$i];
				 		}
			        	
			        }
			    	else
			    	{
			    		if($_POST['Path_image'][$i] != '')
				 		{
				 			$imgList=$imgList.','.$_POST['Path_image'][$i];
				 		}
			    	}
			 	}
			}

		 	//$proimage='';
		 	if(isset($_FILES['ProfileToUpload']['name']) && (!empty($_FILES['ProfileToUpload']['name'])))
			{
				$s3 = new S3($this->awsAccessKey, $this->awsSecretKey);
				$fileName = $_FILES['ProfileToUpload']['name'];
		        $fileTempName = $_FILES['ProfileToUpload']['tmp_name'];             
		        $newfilename = round(microtime(true)) . '.' . $fileName;

		        if($s3->putObjectFile($fileTempName, "retail-safari", $newfilename, S3::ACL_PUBLIC_READ))
	        	{
	        		$proimage = $newfilename;
	        	}
			}
			else
			{
				if (isset($_POST["Profile"]) && !empty($_POST["Profile"])) 
		 		{
					$proimage = $_POST['Profile'];
				}
			}
			
			$e = array();
			$e['name'] = $_POST['u_name'];
		    $e['address']  = $_POST['add'];
		    $e['price'] = $_POST['price'];
		    $e['discount'] = $_POST['Discount'];
		    $e['date'] = $_POST['startdate'];
		    $e['startTime']  = $_POST['startTime'];
		    $e['endTime'] = $_POST['endTime'];
		    $e['e_time'] = $_POST['e_time'];
		    $e['about'] = base64_encode($_POST['about']);
		    $e['Reviews']  = "";
		    $e['type'] = $_POST['type'];
		    // $e['Info_1'] =  $_POST['Info_1'];
		    // $e['Info_2']  = $_POST['Info_2'];
		    // $e['Info_3'] =  $_POST['Info_3'];
		    $e['Phone'] = $_POST['Phone'];
		    $e['Website']  = $_POST['website'];
		    $e['Path'] = $imgList;
		    $e['profileimage'] = $proimage;

  			$detail = json_encode($e);
  			//print_r($detail);exit();
  			$name = $_POST['u_name'];
  			$type = $_POST['type'];
  			// echo "<pre>";
  			// print_r($feautured);exit();
      		$sitelist = $this->site_model->update_events_data($Id,$tag,$detail,$LatLng,$f_LatLng,$placeId,$venueId,$status,$name,$type,$event_type,$Identifier);
	  	// 	{
				// $sitelist = "no-data";	
	  	// 	}
     		//echo json_encode($sitelist);	
		}

		// if($sitelist == "no-data")
		// {
		// 	$this->session->set_userdata('Upload_status', 'flase');
		// }
		// else
		// {
		// 	$this->session->set_userdata('Upload_status', 'true');
		// }
			
		redirect("site/viewEvents");

	}

	public function edit_Deals()
	{
		//parse_str($_POST['formdata'], $formdata);
		$this->load->helper('string');
		$data=array();
		$Id = $_POST['Id'];
		$tag = $_POST['tag_places'];
	 	$placeId = $_POST['PlaceId'];
	 	$venueId = $_POST['VenueId'];
	 	$imgList = $_POST['id_image_path'];
	 	$proimage = $_POST['id_image'];

	 	if($placeId == '')
	 	{
	 		$placeId = $_POST['VenueId'];
	 	}

	 	if($placeId == '')
	 	{
	 		$placeId = random_string('alnum',20);
	 		$venueId = $placeId;
	 	}

	 	$status = $_POST['Status'];
 		
 		// if($_POST['F_lat'] != "" && $_POST['F_lon'] != "")
 		// {
 		// 	if($_POST['F_Lat_Long'] != '')
 		// 	{
 		// 		$f_LatLng='{ "H":'.$_POST['F_lat'].', "L":'.$_POST['F_lon'].'}';
 		// 	}
 		// 	else
 		// 	{
 		// 		$f_LatLng='{ "H":0, "L":0 }';
 		// 	}
 		// }
 		// else
 		// {
 		// 	$f_LatLng='{ "H":0, "L":0 }';
 		// }
	 // 	if((!empty($_POST['F_lat']))&& (!empty($_POST['F_lon'])))
		// {	
		// 	$f_LatLng='{ "H":'.$_POST['F_lat'].', "L":'.$_POST['F_lon'].'}';
		// }
	 // 	else
	 // 	{
	 // 		$f_latitude=explode("_",$_POST['F_Lat_Long']);
		// 	$f_LatLng='{ "H":'.$f_latitude[0].', "L":'.$f_latitude[1].'}';
	 			
	 // 	}

 		$clat='';
 		$clon='';

 		if($_POST['custom_Lat_Long'] != "")
 		{

 			$latlng=explode("_", $_POST['custom_Lat_Long']);
 			if(count($latlng) == 2)
 			{

	 			$clat=$latlng[0];
	 			$clon=$latlng[1];
 			}
 		}
	 	
	 	if($clat != "" && $clon != "")
 		{

 			$LatLng='{ "H":'.$clat.', "L":'.$clon.'}';
 			$f_latitude=explode("_",$_POST['F_Lat_Long']);
			$f_LatLng='{ "H":'.$f_latitude[0].', "L":'.$f_latitude[1].'}';
 		}
 		else if($_POST['F_lat'] != "" && $_POST['F_lon'])
 		{
 			if($_POST['F_Lat_Long'] != '')
 			{
 				$f_LatLng='{ "H":'.$_POST['F_lat'].', "L":'.$_POST['F_lon'].'}';
 			}
 			else
 			{
 				$f_LatLng='{ "H":0, "L":0 }';
 			}
 			
 		}	
 		else		
 		{		
 			$f_latitude=explode("_",$_POST['F_Lat_Long']);
			$f_LatLng='{ "H":'.$f_latitude[0].', "L":'.$f_latitude[1].'}';
 		// 	$this->session->set_userdata('Upload_status', 'flase');		
			// redirect("site/viewDeals");		
			// exit();		
 		}
 		if($clat == "" && $clon == "")
 		{
 			$LatLng='{ "H":0, "L":0 }';
 		}

	 	//$imgList='';

        if(isset($_FILES['fileToUpload']['name'][0]) && (!empty($_FILES['fileToUpload']['name'][0])))
		{
			for($i=0;$i<count($_FILES['fileToUpload']['name']);$i++)
		 	{
		 		$s3 = new S3($this->awsAccessKey, $this->awsSecretKey);
				$fileName = $_FILES['fileToUpload']['name'][$i];
		        $fileTempName = $_FILES['fileToUpload']['tmp_name'][$i];             
		        $newfilename = round(microtime(true)) . '.' . $fileName;

		        if($i==0)
		        {
		        	if($s3->putObjectFile($fileTempName, "retail-safari", $newfilename, S3::ACL_PUBLIC_READ))
		        	{
		        		$imgList=$newfilename;
		        	}
		        }
		    	else
		    	{
		    		if($s3->putObjectFile($fileTempName, "retail-safari", $newfilename, S3::ACL_PUBLIC_READ))
		        	{
		        		$imgList=$imgList.','.$newfilename;
		        	}
		    	}
		 	}

		 	if (isset($_POST["Path_image"]) && !empty($_POST["Path_image"])) 
		 	{
			 	for($i=0;$i<count($_POST['Path_image']);$i++)
			 	{
			 		if($_POST['Path_image'][$i] != '')
			 		{
			 			$imgList=$imgList.','.$_POST['Path_image'][$i];
			 		}
			 	}
			}

		 	//$proimage='';
		 	if(isset($_FILES['ProfileToUpload']['name']) && (!empty($_FILES['ProfileToUpload']['name'])))
			{
				$s3 = new S3($this->awsAccessKey, $this->awsSecretKey);
				$fileName = $_FILES['ProfileToUpload']['name'];
		        $fileTempName = $_FILES['ProfileToUpload']['tmp_name'];             
		        $newfilename = round(microtime(true)) . '.' . $fileName;

		        if($s3->putObjectFile($fileTempName, "retail-safari", $newfilename, S3::ACL_PUBLIC_READ))
	        	{
	        		$proimage = $newfilename;
	        	}
			}
			else
			{
				if (isset($_POST["Profile"]) && !empty($_POST["Profile"])) 
		 		{
					$proimage = $_POST['Profile'];
				}
			}

			
			$e = array();
			$e['name'] = $_POST['u_name'];
		    $e['address']  = $_POST['add'];
		    $e['price'] = $_POST['price'];
		    $e['discount'] = $_POST['Discount'];
		    $e['date'] = $_POST['bday'];
		    $e['startTime']  = $_POST['s_time'];
		    $e['endTime'] = $_POST['usr_time'];
		    $e['about'] = base64_encode($_POST['about']);
		    $e['Reviews']  = "";
		    $e['type'] = $_POST['type'];
		    $e['Phone'] = $_POST['phone'];
		    $e['Website']  = $_POST['website'];
		    $e['Path'] = $imgList;
		    $e['profileimage'] = $proimage;

		    $name = $_POST['u_name'];
		    $type = $_POST['type'];

  			$detail = json_encode($e);

  			$sitelist = $this->site_model->update_events($Id,$tag,$detail,$LatLng,$f_LatLng,$placeId,$venueId,$status,$name,$type);
  		// 	{
				// $sitelist = "no-data";	
  		// 	}
		}
		else
		{
			if (isset($_POST["Path_image"]) && !empty($_POST["Path_image"])) 
		 	{
				for($i=0;$i<count($_POST['Path_image']);$i++)
			 	{
			 		if($i==0)
			        {
			        	if($_POST['Path_image'][$i] != '')
				 		{
				 			$imgList=$_POST['Path_image'][$i];
				 		}
			        	
			        }
			    	else
			    	{
			    		if($_POST['Path_image'][$i] != '')
				 		{
				 			$imgList=$imgList.','.$_POST['Path_image'][$i];
				 		}
			    	}
			 	}
			}

		 	//$proimage='';
		 	if(isset($_FILES['ProfileToUpload']['name']) && (!empty($_FILES['ProfileToUpload']['name'])))
			{
				$s3 = new S3($this->awsAccessKey, $this->awsSecretKey);
				$fileName = $_FILES['ProfileToUpload']['name'];
		        $fileTempName = $_FILES['ProfileToUpload']['tmp_name'];             
		        $newfilename = round(microtime(true)) . '.' . $fileName;

		        if($s3->putObjectFile($fileTempName, "retail-safari", $newfilename, S3::ACL_PUBLIC_READ))
	        	{
	        		$proimage = $newfilename;
	        	}
			}
			else
			{
				if (isset($_POST["Profile"]) && !empty($_POST["Profile"])) 
		 		{
					$proimage = $_POST['Profile'];
				}
			}

			$e = array();
			$e['name'] = $_POST['u_name'];
		    $e['address']  = $_POST['add'];
		    $e['price'] = $_POST['price'];
		    $e['discount'] = $_POST['Discount'];
		    $e['date'] = $_POST['bday'];
		    $e['startTime']  = $_POST['s_time'];
		    $e['endTime'] = $_POST['usr_time'];
		    $e['about'] = base64_encode($_POST['about']);
		    $e['Reviews']  = "";
		    $e['type'] = $_POST['type'];
		    $e['Phone'] = $_POST['phone'];
		    $e['Website']  = $_POST['website'];
		    $e['Path'] = $imgList;
		    $e['profileimage'] = $proimage;

		    $name = $_POST['u_name'];
		    $type = $_POST['type'];

  			$detail = json_encode($e);
  			
      		$sitelist = $this->site_model->update_events($Id,$tag,$detail,$LatLng,$f_LatLng,$placeId,$venueId,$status,$name,$type);
	  	// 	{
				// $sitelist = "no-data";	
	  	// 	}
     		//echo json_encode($sitelist);	
		}

		// if($sitelist == "no-data")
		// {
		// 	$this->session->set_userdata('Upload_status', 'flase');
		// }
		// else
		// {
		// 	$this->session->set_userdata('Upload_status', 'true');
		// }
			
		redirect("site/viewDeals");
	}

	public function remove_custom_events()
	{
		if($_POST['status'] === 'remove')
		{
			$ids = $_POST['id'];
			$this->site_model->remove_events_updated($ids);		
		}
		exit;
	}

	public function Events_list_kendo()
    {
    	$events = $this->site_model->getEvents_list_kendo();
    	$data['admin_header']=$this->load->view('admin/admin_header','',true);

    	$events_data=array();
		foreach ($events as $value) 
		{		
			$events_data_=array();
			$events_data_["SrNumber"]=$value['SrNumber'];
			$events_data_["PlaceIds"]=$value['PlaceIds'];
			$events_data_["Venueid"]=$value['Venueid'];
			$events_data_["Category"]=$value['Category'];
			$events_data_["Event_Deal_name"]=$value['Event_Deal_name'];
			$events_data_["Place_Type"]=$value['Place_Type'];
			$events_data_["Event_Type"]=$value['Event_Type'];
			$events_data_["Identifier"]=$value['Identifier'];
			$events_data_["DateOfEstablishment"]=$value['DateOfEstablishment'];
			
			//$LatLng=json_decode($value['LatLng'],true);			
			//$latlng_H="";
			//if(isset($LatLng['H']))
			//{
			//$events_data_["latlng_H"]=$LatLng['H'];
		//	}
		//	$latlng_L="";	
		//	if(isset($LatLng['L']))
		//	{
		//	$events_data_["latlng_L"]=$LatLng['L'];
		//	}

		//	$Foresquare_lat_lng=json_decode($value['Foresquare_lat_lng'],true);			
			
		//	$Foresquare_lat_lng_H="";
		//	if(isset($Foresquare_lat_lng['H']))
		//	{
		//	$events_data_["Foresquare_lat_lng_H"]=$Foresquare_lat_lng['H'];
		//	}
		//	$Foresquare_lat_lng_L="";	
		//	if(isset($Foresquare_lat_lng['L']))
		//	{
		//	$events_data_["Foresquare_lat_lng_L"]=$Foresquare_lat_lng['L'];
		//	}
			$events_data_["LatLng"]=$value['LatLng'];
			 $events_data_["FLatLng"]=$value['Foresquare_lat_lng'];
			
			$detail=json_decode($value['Detail'],true);
			
			$name="";
			if(isset($detail['name']))
			{
			$events_data_["name"]=$detail['name'];
			}

			// if(isset($detail['about']))
			// {
			// $events_data_["about"]=$detail['about'];
			// }

			if(isset($detail['about']))
			{
				$about_decoded=base64_decode($detail['about']);
				$events_data_["about"]=$about_decoded;
				//print_r($events_data_["about"]);
			}
			else
			{
				$events_data_["about"]=$detail['about'];
			}

			$events_data_["abt"]= implode(' ', array_slice(explode(' ', $events_data_["about"]), 0, 10));
			
			if(isset($detail['address']))
			{
			$events_data_["address"]=$detail['address'];
			}

			if(isset($detail['price']))
			{
			$events_data_["price"]=$detail['price'];
			}
			if(isset($detail['date']))
			{
			$events_data_["date"]=$detail['date'];
			}

			if(isset($detail['startTime']))
			{
			$events_data_["startTime"]=$detail['startTime'];
			}
			if(isset($detail['endTime']))
			{
			$events_data_["endTime"]=$detail['endTime'];
			}

			if(isset($detail['endTime_event']))
			{
			$events_data_["endTime_event"]=$detail['endTime_event'];
			}

			// if (base64_decode($detail['about'], true) === false)
			// {
			//     echo 'Base64-encoded string';
			// }
			// else
			// {
			// 	$places_["about"]=$detail['about'];
			// }
			if(isset($detail['discount']))
			{
			$events_data_["discount"]=$detail['discount'];
			}

			// if(isset($detail['about']))
			// {
			// $events_data_["about"]=$detail['about'];
			// }
			if(isset($detail['Reviews']))
			{
			$events_data_["Reviews"]=$detail['Reviews'];
			}
			if(isset($detail['type']))
			{
			$events_data_["type"]=$detail['type'];
			}
			if(isset($detail['Info_1']))
			{
			$events_data_["Info_1"]=$detail['Info_1'];
			}
			if(isset($detail['Info_2']))
			{
			$events_data_["Info_2"]=$detail['Info_2'];
			}
			if(isset($detail['Info_3']))
			{
			$events_data_["Info_3"]=$detail['Info_3'];
			}
			if(isset($detail['Phone']))
			{
			$events_data_["Phone"]=$detail['Phone'];
			}
			if(isset($detail['Website']))
			{
			$events_data_["Website"]=$detail['Website'];
			}
			if(isset($detail['Path']))
			{
			$events_data_["Path"]=$detail['Path'];
			}
			if(isset($detail['profileimage']))
			{
			$events_data_["profileimage"]=$detail['profileimage'];
			}
			
			array_push($events_data,$events_data_);
			
		}
		//	exit();
			 // echo "<pre>";
			 // print_r($events_data);exit();
			echo json_encode($events_data);
	  	
    }

    public function Deals_list_kendo()
    {
    	$Deals = $this->site_model->getDeals_list();
	  	$data['admin_header']=$this->load->view('admin/admin_header','',true);
	  	// echo "<pre>";
    // 	print_r($Deals);exit();

    	$deals_data=array();
		foreach ($Deals as $value) 
		{		
			
			$deals_data_=array();

			$deals_data_["SrNumber"]=$value['SrNumber'];
			$deals_data_["PlaceIds"]=$value['PlaceIds'];
			$deals_data_["Venueid"]=$value['Venueid'];
			$deals_data_["Category"]=$value['Category'];
			$deals_data_["Event_Deal_name"]=$value['Event_Deal_name'];
			$deals_data_["Place_Type"]=$value['Place_Type'];
			$deals_data_["DateOfEstablishment"]=$value['DateOfEstablishment'];
			
			$LatLng=json_decode($value['LatLng'],true);			
			$latlng_H="";
			if(isset($LatLng['H']))
			{
			$deals_data_["latlng_H"]=$LatLng['H'];
			}
			$latlng_L="";	
			if(isset($LatLng['L']))
			{
			$deals_data_["latlng_L"]=$LatLng['L'];
			}

			$Foresquare_lat_lng=json_decode($value['Foresquare_lat_lng'],true);			
			
			$Foresquare_lat_lng_H="";
			if(isset($Foresquare_lat_lng['H']))
			{
			$deals_data_["Foresquare_lat_lng_H"]=$Foresquare_lat_lng['H'];
			}
			$Foresquare_lat_lng_L="";	
			if(isset($Foresquare_lat_lng['L']))
			{
			$deals_data_["Foresquare_lat_lng_L"]=$Foresquare_lat_lng['L'];
			}


			$detail=json_decode($value['Detail'],true);

			$name="";
			if(isset($detail['name']))
			{
			$deals_data_["name"]=$detail['name'];
			}

			
			
			if(isset($detail['address']))
			{
			$deals_data_["address"]=$detail['address'];
			}
			if(isset($detail['price']))
			{
			$deals_data_["price"]=$detail['price'];
			}
			if(isset($detail['date']))
			{
			$deals_data_["date"]=$detail['date'];
			}
			if(isset($detail['startTime']))
			{
			$deals_data_["startTime"]=$detail['startTime'];
			}
			if(isset($detail['endTime']))
			{
			$deals_data_["endTime"]=$detail['endTime'];
			}

			// if (base64_decode($detail['about'], true) === false)
			// {
			//     echo 'Base64-encoded string';
			// }
			// else
			// {
			// 	$places_["about"]=$detail['about'];
			// }
			if(isset($detail['discount']))
			{
			$deals_data_["discount"]=$detail['discount'];
			}

			if(isset($detail['about']))
			{
				$about_decoded=base64_decode($detail['about']);
				$deals_data_["about"]=$about_decoded;
				//$deals_data_["about"]=$detail['about'];
			}
			else
			{
				$deals_data_["about"]=$detail['about'];
			}

			$deals_data_["abt"]= implode(' ', array_slice(explode(' ', $deals_data_["about"]), 0, 10));			

			if(isset($detail['Reviews']))
			{
			$deals_data_["Reviews"]=$detail['Reviews'];
			}
			if(isset($detail['type']))
			{
			$deals_data_["type"]=$detail['type'];
			}
			if(isset($detail['Info_1']))
			{
			$deals_data_["Info_1"]=$detail['Info_1'];
			}
			if(isset($detail['Info_2']))
			{
			$deals_data_["Info_2"]=$detail['Info_2'];
			}
			if(isset($detail['Info_3']))
			{
			$deals_data_["Info_3"]=$detail['Info_3'];
			}
			if(isset($detail['Phone']))
			{
			$deals_data_["Phone"]=$detail['Phone'];
			}
			if(isset($detail['Website']))
			{
			$deals_data_["Website"]=$detail['Website'];
			}
			if(isset($detail['Path']))
			{
			$deals_data_["Path"]=$detail['Path'];
			}
			if(isset($detail['profileimage']))
			{
			$deals_data_["profileimage"]=$detail['profileimage'];
			}
			array_push($deals_data,$deals_data_);
			
		}
			
			echo json_encode($deals_data);
    }


	// public function viewPlaces_kendo()
	// {
	// 	$place = $this->site_model->get_places_kendo();
	// 	$data['place_admin'] = $place;

	// 	$data['admin_header']=$this->load->view('admin/admin_header','',true);
		
	// 	foreach ($data['place_admin'] as $value) {
	// 		$abc['LatLng']=json_decode($value['LatLng']);

	// 		$LatLng = json_decode(json_encode($abc['LatLng']), True);

	// 		$abc['Detail']=json_decode($value['Detail']);
	// 		$detail = json_decode(json_encode($abc['Detail']), True);
	// 		// echo "<pre>";
			
	// 		// $pla=array_merge($detail,$LatLng)
	// 		//print_r($pla);
	// 		echo json_encode($detail);
	// 	}
	// 	//exit();
	// 	/*echo "<pre>";print_r($data['place_admin']);exit();
	// 	foreach ($data['place_admin'] as $value) 
	// 	{
	// 		foreach ($value as $key => $value1) {
					
	// 				//echo $key . "\n";
	// 				$js = json_decode($value1);
	// 				print_r($js);
				
	// 			$value1['LatLng']=json_decode($value1['LatLng']);
	// 			$value1['Foresquare_lat_lng']=json_decode($value1['Foresquare_lat_lng']);
	// 		}
	// 	}
	// 	//print_r($data['place_admin']);
	// 	exit();*/
		
	// }

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
          
      $select ='Place_detail.PlaceIds,Place_detail.Venueid,Place_detail.Foresquare_lat_lng,Place_detail.LatLng,Place_detail.Category,Place_detail.Detail,Place_detail.DateOfEstablishment';
		
	  $where_in['0'] = 'Place_detail.PlaceIds';
	  $where_in['1'] = $placeId;
	  $where_ins['0'] = $where_in;
          
      if(!($sitelist = $this->site_model->getPlacesDetail($where_ins,$select)))
	  {
		$sitelist['0'] = "no-data";
	  }
	  else
	  {
	  	$sitelist[0]['Detail'] = json_decode($sitelist[0]['Detail']);
	  }

      echo json_encode($sitelist);		
	}

	public function Find_Events_deals_by_name()
	{
	  	$Type = '';
	  	
	  	if(isset($_POST['srno']))
	  		$Type =$_POST['srno'];

      	$select ='Place_detail.PlaceIds,Place_detail.Event_Deal_name';
		
	   	$Like_in['0'] = 'Place_detail.Event_Deal_name';
	   	$Like_in['1'] = $Type;
	   	$Like_ins['0'] = $Like_in;
      	// $like_in['0'] = 'Place_detail.Event_Deal_name';
      	// $like_in['1'] = $category;
          
      	if(!($sitelist = $this->site_model->getPlacesDetail_byname($Type,$select)))
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
		$data=array();
		$tag = $_POST['Tag'];
	 	$placeId = $_POST['PlaceId'];
	 	$venueId = $_POST['VenueId'];
	 	$PlaceIdentifier = $_POST['PlaceIdentifier'];
	 	//print_r($PlaceIdentifier);exit();

	 	$status = $_POST['Status'];
 		
 		$f_LatLng='{ "H":'.$_POST['F_lat'].', "L":'.$_POST['F_lon'].'}';
	 	$LatLng='{ "H":'.$_POST['G_lat'].', "L":'.$_POST['G_lon'].'}';
	 	$imgList='';

        if(isset($_FILES['fileToUpload']['name'][0]) && (!empty($_FILES['fileToUpload']['name'][0])))
		{
			for($i=0;$i<count($_FILES['fileToUpload']['name']);$i++)
		 	{
		 		$s3 = new S3($this->awsAccessKey, $this->awsSecretKey);
				$fileName = $_FILES['fileToUpload']['name'][$i];
		        $fileTempName = $_FILES['fileToUpload']['tmp_name'][$i];             
		        $newfilename = round(microtime(true)) . '.' . $fileName.'_large';
		        
		        if($i==0)
		        {
		        	if($s3->putObjectFile($fileTempName, "retail-safari", $newfilename, S3::ACL_PUBLIC_READ))
		        	{
		        		$imgList=$newfilename;
		        	}
		        }
		    	else
		    	{
		    		if($s3->putObjectFile($fileTempName, "retail-safari", $newfilename, S3::ACL_PUBLIC_READ))
		        	{
		        		$imgList=$imgList.','.$newfilename;
		        	}
		    	}
		 	}

		 	if (isset($_POST["Path_image"]) && !empty($_POST["Path_image"])) 
		 	{
			 	for($i=0;$i<count($_POST['Path_image']);$i++)
			 	{
			 		if($_POST['Path_image'][$i] != '')
			 		{
			 			$imgList=$imgList.','.$_POST['Path_image'][$i];
			 		}
			 	}
			}
		 	
		 	$proimage='';
		 	if(isset($_FILES['ProfileToUpload']['name']) && (!empty($_FILES['ProfileToUpload']['name'])))
			{
				$s3 = new S3($this->awsAccessKey, $this->awsSecretKey);
				$fileName = $_FILES['ProfileToUpload']['name'];
		        $fileTempName = $_FILES['ProfileToUpload']['tmp_name'];             
		        $newfilename = round(microtime(true)) . '.' . $fileName.'small';

		        if($s3->putObjectFile($fileTempName, "retail-safari", $newfilename, S3::ACL_PUBLIC_READ))
	        	{
	        		$proimage = $newfilename;
	        	}
			}
			else
			{
				if (isset($_POST["Profile"]) && !empty($_POST["Profile"])) 
		 		{
					$proimage = $_POST['Profile'];
				}
			}

			$e = array();
			$e['name'] = $_POST['name'];
		    $e['address']  = $_POST['add'];
		    $e['price'] = "";
		    $e['date'] = $_POST['bday'];
		    $e['startTime']  = $_POST['s_time'];
		    $e['endTime'] = $_POST['usr_time'];
		    $e['about'] = base64_encode($_POST['Description']);
		    $e['Reviews']  = "";
		    $e['type'] = $_POST['type'];
		    $e['Info_1'] =  $_POST['Info_1'];
		    $e['Info_2']  = $_POST['Info_2'];
		    $e['Info_3'] =  $_POST['Info_3'];
		    $e['Phone'] = $_POST['Phone'];
		    $e['Like'] = $_POST['Like'];
		    $e['Website']  = $_POST['Website'];
		    $e['Path'] = $imgList;
		    $e['profileimage'] = $proimage;

  			$detail = json_encode($e);
  			//print_r($PlaceIdentifier);exit();
  		  if(!($sitelist = $this->site_model->addEventsPlaces($placeId,$venueId,$LatLng,$f_LatLng,$tag,$detail,$status,$PlaceIdentifier)))

			{
				$sitelist = "no-data";	
  			}
		}
		else
		{
			if (isset($_POST["Path_image"]) && !empty($_POST["Path_image"])) 
		 	{
				for($i=0;$i<count($_POST['Path_image']);$i++)
			 	{
			 		if($i==0)
			        {
			        	if($_POST['Path_image'][$i] != '')
				 		{
				 			$imgList=$_POST['Path_image'][$i];
				 		}
			        	
			        }
			    	else
			    	{
			    		if($_POST['Path_image'][$i] != '')
				 		{
				 			$imgList=$imgList.','.$_POST['Path_image'][$i];
				 		}
			    	}
			 	}
			}

		 	$proimage='';
		 	if(isset($_FILES['ProfileToUpload']['name']) && (!empty($_FILES['ProfileToUpload']['name'])))
			{
				$s3 = new S3($this->awsAccessKey, $this->awsSecretKey);
				$fileName = $_FILES['ProfileToUpload']['name'];
		        $fileTempName = $_FILES['ProfileToUpload']['tmp_name'];             
		        $newfilename = round(microtime(true)) . '.' . $fileName;

		        if($s3->putObjectFile($fileTempName, "retail-safari", $newfilename, S3::ACL_PUBLIC_READ))
	        	{
	        		$proimage = $newfilename;
	        	}
			}
			else
			{
				if (isset($_POST["Profile"]) && !empty($_POST["Profile"])) 
		 		{
					$proimage = $_POST['Profile'];
				}
			}

			
			$e = array();
			$e['name'] = $_POST['name'];
		    $e['address']  = $_POST['add'];
		    $e['price'] = "";
		    $e['date'] = $_POST['bday'];
		    $e['startTime']  = $_POST['s_time'];
		    $e['endTime'] = $_POST['usr_time'];
		    $e['about'] = base64_encode($_POST['Description']);
		    $e['Reviews']  = "";
		    $e['type'] = $_POST['type'];
		    $e['Info_1'] =  $_POST['Info_1'];
		    $e['Info_2']  = $_POST['Info_2'];
		    $e['Info_3'] =  $_POST['Info_3'];
		    $e['Phone'] = $_POST['Phone'];
		    $e['Like'] = $_POST['Like'];
		    $e['Website']  = $_POST['Website'];
		    $e['Path'] = $imgList;
		    $e['profileimage'] = $proimage;

  			$detail = json_encode($e);
  			//print_r($PlaceIdentifier);exit();
      		if(!($sitelist = $this->site_model->addEventsPlaces($placeId,$venueId,$LatLng,$f_LatLng,$tag,$detail,$status,$PlaceIdentifier)))
	  		{
				$sitelist = "no-data";	
	  		}
     		//echo json_encode($sitelist);	
		}

		if($sitelist == "no-data")
		{
			$this->session->set_userdata('Upload_status', 'flase');
		}
		else
		{
			$this->session->set_userdata('Upload_status', 'true');
		}
			
		redirect("site/viewPlaces");
	}
	
	public function Save_Events()
	{
		$data=array();
		$tag = $_POST['Tag'];
	 	$placeId = $_POST['PlaceId'];
	 	$venueId = $_POST['VenueId'];

	 	$event_type = $_POST['feautured'];
	 	$Identifier = $_POST['Identifier'];

	 	if($placeId == '')
	 	{
	 		$placeId = $_POST['VenueId'];
	 	}

	 	if($placeId == '')
	 	{
	 		$placeId = random_string('alnum',20);
	 		$venueId = $placeId;
	 	}

	 	$status = $_POST['Status'];
 		
 		
 		if($_POST['F_lat'] != "" && $_POST['F_lon'] != "")
 		{
 			if($_POST['F_Lat_Long'] != '')
 			{
 				$f_LatLng='{ "H":'.$_POST['F_lat'].', "L":'.$_POST['F_lon'].'}';
 			}
 			else
 			{
 				$f_LatLng='{ "H":0, "L":0 }';
 			}
 		}
 		else
 		{
 			$f_LatLng='{ "H":0, "L":0}';
 		}

 		$clat='';
 		$clon='';

 		if($_POST['Custom_lat_lon'] != "")
 		{
 			$latlng=explode("_", $_POST['Custom_lat_lon']);
 			if(count($latlng) == 2)
 			{
	 			$clat=$latlng[0];
	 			$clon=$latlng[1];
 			}
 		}

 		if($clat != "" && $clon != "")
 		{
 			$LatLng='{ "H":'.$clat.', "L":'.$clon.'}';
 		}
 		elseif($_POST['F_lat'] != "" && $_POST['F_lon'] != "")
 		{
 			if($_POST['F_Lat_Long'] != '')
 			{
 				$f_LatLng='{ "H":'.$_POST['F_lat'].', "L":'.$_POST['F_lon'].'}';
 			}
 			else
 			{
 				$f_LatLng='{ "H":0, "L":0 }';
 			}
 		}
 		else
 		{
 			$this->session->set_userdata('Upload_status', 'flase');
			redirect("site/SharedPlaces");
			exit();
 		}

 		if($clat == "" && $clon == "")
 		{
 			$LatLng='{ "H":0, "L":0}';
 		}
	 	
	 	$imgList='';

        if(isset($_FILES['fileToUpload']['name'][0]) && (!empty($_FILES['fileToUpload']['name'][0])))
		{
			for($i=0;$i<count($_FILES['fileToUpload']['name']);$i++)
		 	{
		 		$s3 = new S3($this->awsAccessKey, $this->awsSecretKey);
				$fileName = $_FILES['fileToUpload']['name'][$i];
		        $fileTempName = $_FILES['fileToUpload']['tmp_name'][$i];             
		        $newfilename = round(microtime(true)) . '.' . $fileName;

		        if($i==0)
		        {
		        	if($s3->putObjectFile($fileTempName, "retail-safari", $newfilename, S3::ACL_PUBLIC_READ))
		        	{
		        		$imgList=$newfilename;
		        	}
		        }
		    	else
		    	{
		    		if($s3->putObjectFile($fileTempName, "retail-safari", $newfilename, S3::ACL_PUBLIC_READ))
		        	{
		        		$imgList=$imgList.','.$newfilename;
		        	}
		    	}
		 	}
		 	
		 	if (isset($_POST["Path_image"]) && !empty($_POST["Path_image"])) 
		 	{
			 	for($i=0;$i<count($_POST['Path_image']);$i++)
			 	{
			 		if($_POST['Path_image'][$i] != '')
			 		{
			 			$imgList=$imgList.','.$_POST['Path_image'][$i];
			 		}
			 	}
			}

		 	$proimage='';
		 	if(isset($_FILES['ProfileToUpload']['name']) && (!empty($_FILES['ProfileToUpload']['name'])))
			{
				$s3 = new S3($this->awsAccessKey, $this->awsSecretKey);
				$fileName = $_FILES['ProfileToUpload']['name'];
		        $fileTempName = $_FILES['ProfileToUpload']['tmp_name'];             
		        $newfilename = round(microtime(true)) . '.' . $fileName;

		        if($s3->putObjectFile($fileTempName, "retail-safari", $newfilename, S3::ACL_PUBLIC_READ))
	        	{
	        		$proimage = $newfilename;
	        	}
			}
			else
			{
				if (isset($_POST["Profile"]) && !empty($_POST["Profile"])) 
		 		{
					$proimage = $_POST['Profile'];
				}
			}

			
			$e = array();
			$e['name'] = $_POST['name'];
		    $e['address']  = $_POST['add'];
		    $e['price'] = $_POST['Price'];
		    $e['discount'] = $_POST['Discount'];
		    $e['date'] = $_POST['bday'];
		    $e['startTime']  = $_POST['start_time'];
		    $e['endTime'] = $_POST['usr_time'];
		    $e['endTime_event'] = $_POST['e_time'];
		    $e['about'] = base64_encode($_POST['Description']);
		    $e['Reviews']  = "";
		    $e['type'] = $_POST['type'];
		    $e['Info_1'] =  $_POST['Info_1'];
		    $e['Info_2']  = $_POST['Info_2'];
		    $e['Info_3'] =  $_POST['Info_3'];
		    $e['Phone'] = $_POST['Phone'];
		    $e['Website']  = $_POST['Website'];
		    $e['Path'] = $imgList;
		    $e['profileimage'] = $proimage;

		    $name = $_POST['name'];
		    $type = $_POST['type'];

  			$detail = json_encode($e);

  			//print_r($event_type);exit();
  			if(!($sitelist = $this->site_model->addEventsPlacesNew($placeId,$venueId,$LatLng,$f_LatLng,$tag,$detail,$status,$name,$type,$event_type,$Identifier)))
  			//if(!($sitelist = $this->site_model->addEventsPlaces($tag,$detail,$LatLng,$f_LatLng,$placeId,$venueId,$status,$name,$type)))
  			{
				$sitelist = "no-data";	
  			}
		}
		else
		{
			if (isset($_POST["Path_image"]) && !empty($_POST["Path_image"])) 
		 	{
				for($i=0;$i<count($_POST['Path_image']);$i++)
			 	{
			 		if($i==0)
			        {
			        	if($_POST['Path_image'][$i] != '')
				 		{
				 			$imgList=$_POST['Path_image'][$i];
				 		}
			        	
			        }
			    	else
			    	{
			    		if($_POST['Path_image'][$i] != '')
				 		{
				 			$imgList=$imgList.','.$_POST['Path_image'][$i];
				 		}
			    	}
			 	}
			}

		 	$proimage='';
		 	if(isset($_FILES['ProfileToUpload']['name']) && (!empty($_FILES['ProfileToUpload']['name'])))
			{
				$s3 = new S3($this->awsAccessKey, $this->awsSecretKey);
				$fileName = $_FILES['ProfileToUpload']['name'];
		        $fileTempName = $_FILES['ProfileToUpload']['tmp_name'];             
		        $newfilename = round(microtime(true)) . '.' . $fileName;

		        if($s3->putObjectFile($fileTempName, "retail-safari", $newfilename, S3::ACL_PUBLIC_READ))
	        	{
	        		$proimage = $newfilename;
	        	}
			}
			else
			{
				if (isset($_POST["Profile"]) && !empty($_POST["Profile"])) 
		 		{
					$proimage = $_POST['Profile'];
				}
			}
			
			$e = array();
			$e['name'] = $_POST['name'];
		    $e['address']  = $_POST['add'];
		    $e['price'] = $_POST['Price'];
		    $e['discount'] = $_POST['Discount'];
		    $e['date'] = $_POST['bday'];
		    $e['startTime']  = $_POST['start_time'];
		    $e['endTime'] = $_POST['usr_time'];
		    $e['endTime_event'] = $_POST['e_time'];
		    $e['about'] = base64_encode($_POST['Description']);
		    $e['Reviews']  = "";
		    $e['type'] = $_POST['type'];
		    $e['Info_1'] =  $_POST['Info_1'];
		    $e['Info_2']  = $_POST['Info_2'];
		    $e['Info_3'] =  $_POST['Info_3'];
		    $e['Phone'] = $_POST['Phone'];
		    $e['Website']  = $_POST['Website'];
		    $e['Path'] = $imgList;
		    $e['profileimage'] = $proimage;

  			$detail = json_encode($e);

  			$name = $_POST['name'];
  			$type = $_POST['type'];
			
			if(!($sitelist = $this->site_model->addEventsPlacesNew($placeId,$venueId,$LatLng,$f_LatLng,$tag,$detail,$status,$name,$type,$event_type,$Identifier)))
      		{
				$sitelist = "no-data";	
	  		}
     		
		}

		if($sitelist == "no-data")
		{
			$this->session->set_userdata('Upload_status', 'flase');
		}
		else
		{
			$this->session->set_userdata('Upload_status', 'true');
		}
			
		redirect("site/viewEvents");
	}

	public function Save_Deals()
	{
		$this->load->helper('string');
		$data=array();
		$tag = $_POST['Tag'];
	 	$placeId = $_POST['PlaceId'];
	 	$venueId = $_POST['VenueId'];

	 	if($placeId == '')
	 	{
	 		$placeId = $_POST['VenueId'];
	 	}

	 	if($placeId == '')
	 	{
	 		$placeId = random_string('alnum',20);
	 		$venueId = $placeId;
	 	}

	 	$status = $_POST['Status'];
 		
 		if($_POST['F_lat'] != "" && $_POST['F_lon'] != "")
 		{
 			if($_POST['F_Lat_Long'] != '')
 			{
 				$f_LatLng='{ "H":'.$_POST['F_lat'].', "L":'.$_POST['F_lon'].'}';
 			}
 			else
 			{
 				$f_LatLng='{ "H":0, "L":0 }';
 			}
 		}
 		else
 		{
 			$f_LatLng='{ "H":0, "L":0 }';
 		}

 		$clat='';
 		$clon='';

 		if($_POST['Custom_lat_lon'] != "")
 		{
 			$latlng=explode("_", $_POST['Custom_lat_lon']);
 			if(count($latlng) == 2)
 			{
	 			$clat=$latlng[0];
	 			$clon=$latlng[1];
 			}
 		}
	 	
	 	if($clat != "" && $clon != "")
 		{
 			$LatLng='{ "H":'.$clat.', "L":'.$clon.'}';
 		}
 		else if($_POST['F_lat'] != "" && $_POST['F_lon'])
 		{
 			if($_POST['F_Lat_Long'] != '')
 			{
 				$f_LatLng='{ "H":'.$_POST['F_lat'].', "L":'.$_POST['F_lon'].'}';
 			}
 			else
 			{
 				$f_LatLng='{ "H":0, "L":0 }';
 			}
 			
 		}	
 		else		
 		{		
 			$this->session->set_userdata('Upload_status', 'flase');		
			redirect("site/SharedPlaces");		
			exit();		
 		}

 		if($clat == "" && $clon == "")
 		{
 			$LatLng='{ "H":0, "L":0 }';
 		}

	 	$imgList='';

        if(isset($_FILES['fileToUpload']['name'][0]) && (!empty($_FILES['fileToUpload']['name'][0])))
		{
			for($i=0;$i<count($_FILES['fileToUpload']['name']);$i++)
		 	{
		 		$s3 = new S3($this->awsAccessKey, $this->awsSecretKey);
				$fileName = $_FILES['fileToUpload']['name'][$i];
		        $fileTempName = $_FILES['fileToUpload']['tmp_name'][$i];             
		        $newfilename = round(microtime(true)) . '.' . $fileName;

		        if($i==0)
		        {
		        	if($s3->putObjectFile($fileTempName, "retail-safari", $newfilename, S3::ACL_PUBLIC_READ))
		        	{
		        		$imgList=$newfilename;
		        	}
		        }
		    	else
		    	{
		    		if($s3->putObjectFile($fileTempName, "retail-safari", $newfilename, S3::ACL_PUBLIC_READ))
		        	{
		        		$imgList=$imgList.','.$newfilename;
		        	}
		    	}
		 	}

		 	if (isset($_POST["Path_image"]) && !empty($_POST["Path_image"])) 
		 	{
			 	for($i=0;$i<count($_POST['Path_image']);$i++)
			 	{
			 		if($_POST['Path_image'][$i] != '')
			 		{
			 			$imgList=$imgList.','.$_POST['Path_image'][$i];
			 		}
			 	}
			}

		 	$proimage='';
		 	if(isset($_FILES['ProfileToUpload']['name']) && (!empty($_FILES['ProfileToUpload']['name'])))
			{
				$s3 = new S3($this->awsAccessKey, $this->awsSecretKey);
				$fileName = $_FILES['ProfileToUpload']['name'];
		        $fileTempName = $_FILES['ProfileToUpload']['tmp_name'];             
		        $newfilename = round(microtime(true)) . '.' . $fileName;

		        if($s3->putObjectFile($fileTempName, "retail-safari", $newfilename, S3::ACL_PUBLIC_READ))
	        	{
	        		$proimage = $newfilename;
	        	}
			}
			else
			{
				if (isset($_POST["Profile"]) && !empty($_POST["Profile"])) 
		 		{
					$proimage = $_POST['Profile'];
				}
			}

			
			$e = array();
			$e['name'] = $_POST['name'];
		    $e['address']  = $_POST['add'];
		    $e['price'] = $_POST['Price'];
		    $e['discount'] = $_POST['Discount'];
		    $e['date'] = $_POST['bday'];
		    $e['startTime']  = $_POST['s_time'];
		    $e['endTime'] = $_POST['usr_time'];
		    $e['about'] = base64_encode($_POST['Description']);
		    $e['Reviews']  = "";
		    $e['type'] = $_POST['type'];
		    $e['Info_1'] =  $_POST['Info_1'];
		    $e['Info_2']  = $_POST['Info_2'];
		    $e['Info_3'] =  $_POST['Info_3'];
		    $e['Phone'] = $_POST['Phone'];
		    $e['Website']  = $_POST['Website'];
		    $e['Path'] = $imgList;
		    $e['profileimage'] = $proimage;

		    $name = $_POST['name'];
		    $type = $_POST['type'];

  			$detail = json_encode($e);

  			if(!($sitelist = $this->site_model->addEventsPlaces($placeId,$venueId,$LatLng,$f_LatLng,$tag,$detail,$status,$name,$type)))
  			{
				$sitelist = "no-data";	
  			}
		}
		else
		{
			if (isset($_POST["Path_image"]) && !empty($_POST["Path_image"])) 
		 	{
				for($i=0;$i<count($_POST['Path_image']);$i++)
			 	{
			 		if($i==0)
			        {
			        	if($_POST['Path_image'][$i] != '')
				 		{
				 			$imgList=$_POST['Path_image'][$i];
				 		}
			        	
			        }
			    	else
			    	{
			    		if($_POST['Path_image'][$i] != '')
				 		{
				 			$imgList=$imgList.','.$_POST['Path_image'][$i];
				 		}
			    	}
			 	}
			}

		 	$proimage='';
		 	if(isset($_FILES['ProfileToUpload']['name']) && (!empty($_FILES['ProfileToUpload']['name'])))
			{
				$s3 = new S3($this->awsAccessKey, $this->awsSecretKey);
				$fileName = $_FILES['ProfileToUpload']['name'];
		        $fileTempName = $_FILES['ProfileToUpload']['tmp_name'];             
		        $newfilename = round(microtime(true)) . '.' . $fileName;

		        if($s3->putObjectFile($fileTempName, "retail-safari", $newfilename, S3::ACL_PUBLIC_READ))
	        	{
	        		$proimage = $newfilename;
	        	}
			}
			else
			{
				if (isset($_POST["Profile"]) && !empty($_POST["Profile"])) 
		 		{
					$proimage = $_POST['Profile'];
				}
			}

			$e = array();
			$e['name'] = $_POST['name'];
		    $e['address']  = $_POST['add'];
		    $e['price'] = $_POST['Price'];
		    $e['discount'] = $_POST['Discount'];
		    $e['date'] = $_POST['bday'];
		    $e['startTime']  = $_POST['s_time'];
		    $e['endTime'] = $_POST['usr_time'];
		    $e['about'] = base64_encode($_POST['Description']);
		    $e['Reviews']  = "";
		    $e['type'] = $_POST['type'];
		    $e['Info_1'] =  $_POST['Info_1'];
		    $e['Info_2']  = $_POST['Info_2'];
		    $e['Info_3'] =  $_POST['Info_3'];
		    $e['Phone'] = $_POST['Phone'];
		    $e['Website']  = $_POST['Website'];
		    $e['Path'] = $imgList;
		    $e['profileimage'] = $proimage;

		    $name = $_POST['name'];
		    $type = $_POST['type'];

  			$detail = json_encode($e);
  			if(!($sitelist = $this->site_model->addEventsPlaces($placeId,$venueId,$LatLng,$f_LatLng,$tag,$detail,$status,$name,$type)))
      		{
				$sitelist = "no-data";	
	  		}
     		//echo json_encode($sitelist);	
		}

		if($sitelist == "no-data")
		{
			$this->session->set_userdata('Upload_status', 'flase');
		}
		else
		{
			$this->session->set_userdata('Upload_status', 'true');
		}
			
		redirect("site/viewDeals");
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

	public function TopTen()
	{
		if(!($site = $this->site_model->getTenEvent()))
		{
			$site['0'] = "no-data";	
		}

		echo $_GET['callback'] .'('. json_encode($site) . ')';
		exit;
	}	


	public function TopTenDeal()
	{
		if(!($site = $this->site_model->getTenDeals()))
		{
			$site['0'] = "no-data";	
		}

		echo $_GET['callback'] .'('. json_encode($site) . ')';
		exit;
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

	public function getPhoneNoYelpData()
	{
    	 $phone = $this->site_model->getPhoneNoInfo();   
    			
    	 print_r($sites);	
    }
	
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
// $_GET['callback'] .'('. json_encode($resp) . ')';
