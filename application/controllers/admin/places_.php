<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class places_ extends CI_Controller {
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
		$this->load->model('admin/places_model');
		$this->load->model('site_model');
		$this->load->library('pagination');	
		$this->load->library('session');
		require_once(APPPATH.'libraries/S3.php');
	}

	public function index()
	{
		// if($this->session->userdata('identity'))
		// {
			$limit = 10;
			$offset = 0;
			if($total = $this->places_model->get_custom_places(array(),true))
			{
				$config['base_url'] = site_url('admin/Places/index/');
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
				
			if(!($place_list = $this->places_model->get_custom_places(array(),false,$limit,$offset)))
			{
				$place_list['0'] = "no-data";	
			}
			$data['admin_header']=$this->load->view('admin/admin_header','',true);
			$data['galleries']= $place_list;

			// echo "<pre>";
			// print_r($data['galleries']);exit();

			$this->load->view('admin/Places_list',$data);
		// }
		// else
		// {
		// 	header('Location:'.site_url('admin/login/index') );
		// }
	}
	

	// public function index_places_kendo()
	// {
	// 	// if($this->session->userdata('identity'))
	// 	// {
	// 		$limit = 10;
	// 		$offset = 0;
	// 		if($total = $this->places_model->get_custom_places(array(),true))
	// 		{
	// 			$config['base_url'] = site_url('admin/Places/index/');
	// 			$config['total_rows'] = $total;
	// 			$config['per_page'] = $limit = 10;
	// 			$config['uri_segment'] = 4;
	// 			$config['num_links'] = 2;	
	// 			$config['first_tag_open'] = '<li>';
	// 			$config['first_tag_close'] = '</li>';
	// 			$config['last_tag_open'] = '<li>';
	// 			$config['last_tag_close'] = '</li>';
	// 			$config['next_tag_open'] = '<li class="next">';
	// 			$config['next_tag_close'] = '</li>';
	// 			$config['prev_tag_open'] = '<li class="previous">';
	// 			$config['prev_tag_close'] = '</li>';
	// 			$config['cur_tag_open'] = '<li class="active">';
	// 			$config['cur_tag_close'] = '</li>';
	// 			$config['num_tag_open'] = '<li>';
	// 			$config['num_tag_close'] = '</li>';
	// 			// Initialize
	// 			$this->pagination->initialize($config);
	// 			$pages = $this->pagination->create_links();	
	// 			$offset = $this->uri->segment(4) ? $this->uri->segment(4) : 0;
	// 			$data['pages'] = $pages;
	// 			$data['index'] = $offset;	
	// 		}
				
	// 		if(!($place_list = $this->places_model->get_custom_places(array(),false,$limit,$offset)))
	// 		{
	// 			$place_list['0'] = "no-data";	
	// 		}
	// 		$data['admin_header']=$this->load->view('admin/admin_header','',true);
	// 		$data['galleries']= $place_list;

			
	// 		echo json_encode($data['galleries']);
	// 		//$this->load->view('admin/Places_list',$data);
	// 	// }
	// 	// else
	// 	// {
	// 	// 	header('Location:'.site_url('admin/login/index') );
	// 	// }
	// }

	public function index_places_kendo()
	{
			$place_list = $this->places_model->get_places_kendo();
			$data['galleries'] = $place_list;

			$Custom_places=array();
			foreach ($data['galleries'] as $value) {
				$places_=array();
				$places_['sr_no']=$value['sr_no'];
				$places_['place_name']=base64_decode($value['place_name']);
				$places_['address']=base64_decode($value['address']);
				$places_['description']=base64_decode($value['description']);

				if(isset($value['description']))
				{
				$places_['description']=base64_decode($value['description']);
				
				}

				$places_['longitude']=base64_decode($value['longitude']);
				$places_['latitude']=base64_decode($value['latitude']);
				$places_['Category']=base64_decode($value['Category']);
				$places_['image']=$value['Image'];	
				array_push($Custom_places,$places_);
			}

			$data['admin_header']=$this->load->view('admin/admin_header','',true);
			echo json_encode($Custom_places);	
	}
	

	public function add_or_remove_custom_place()
	{
		//print_r($_POST);exit();	
	if($_POST['status'] === 'remove')
		{
			$ids = $_POST['id'];
			$this->places_model->remove_custom_place_updated($ids);		
		}
		exit;
	}

	public function getEdit_places()
	{

		$ids = explode('_',$_POST['id']);
		$expeience = $this->places_model->get_places(array('experince_id' => $ids['0']));
		if(!($placename = $this->places_model->get_places_info_for_exp()))
		{
			$arr['places']['0'] = "no-data";	
		}

		if(!($user = $this->places_model->get_user_info_for_exp()))   //not worry about 10,0 as last field i.e. all field are set to true.
		{
			$arr['user']['0'] = "no-data";	
		}


		$result['0'] = 	$placename;
		$result['1'] = 	$expeience;
		$result['2'] = 	$user;
		echo json_encode($result);
		
		exit;	
	}

	public function infoSelectBoxes()
	{
		if(!($place_ = $this->places_model->get_place_info()))
				{
					$place_['0'] = "no-data";	
				}
			
		$result['0'] = 	$place_;
		return $result;
	}

	  public function edit_custom_Places()
        {
		//print_r($_POST);exit();
                if($_POST['Id'] != '')
                {
                        $id = $_POST['Id'];
                        $place_name = $_POST['place_name'];
                        $address = $_POST['address'];
                        $description = $_POST['description'];
//                        $category = $_POST['category'];
                        $longitude = $_POST['longitude'];
                        $latitude = $_POST['latitude'];
			 $tag = $_POST['tag'];

                        $web = $_POST['web'];
                        $phone = $_POST['phone'];
                        $Image = $_POST['id_image'];

                        if(isset($_FILES['Image_']['name']) && (!empty($_FILES['Image_']['name'])))
                        {
                                $size = $_FILES['Image_']['size'];

                        if ($size < 1000000)
                        {
                                        $s3 = new S3($this->awsAccessKey, $this->awsSecretKey);

                                        $fileName = $_FILES['Image_']['name'];
                                $fileTempName = $_FILES['Image_']['tmp_name'];
                                $newfilename = round(microtime(true)) . '.jpeg';

                                if($s3->putObjectFile($fileTempName, "retail-safari", $newfilename, S3::ACL_PUBLIC_READ))
                                {
                                        $Image = $newfilename;

                                        $data = array();
                                        $data['place_name'] = base64_encode($place_name);
                                        $data['address'] = base64_encode($address);
                                        $data['description'] =base64_encode($description);
                                        $data['longitude'] = base64_encode($longitude);
                                        $data['latitude'] =base64_encode($latitude);
                                        $data['web'] = base64_encode($web);
                                        $data['Category'] = base64_encode($tag);
                                        $data['phone'] =base64_encode($phone);
                                        $data['Image'] = $Image;


                                        $save_custom_places = $this->places_model->edit_custom_place($data,$id);

                                        echo $save_custom_places;
                                }
                                else
                                {
                                        echo 'error';
                                }
                        }
                        else
                        {
                                echo 'size issue';
                        }
                        }
                        else
                        {
                                $data = array();
                        $data['place_name'] = base64_encode($place_name);
                                        $data['address'] = base64_encode($address);
                                        $data['description'] =base64_encode($description);
                                        $data['longitude'] = base64_encode($longitude);
                                        $data['latitude'] =base64_encode($latitude);
                                        $data['web'] = base64_encode($web);
                                        $data['Category'] = base64_encode($tag);
                                        $data['phone'] =base64_encode($phone);
			
		                         $data['Image'] = $Image;

                        $save_custom_places = $this->places_model->edit_custom_place($data,$id);

                        echo $save_custom_places;
                        }


                        //$data = array( 'place_name' => $place_name,'address' => $address,'description' => $description,'category' => $category,'longitude' => $longitude,'latitude' => $latitude,'web' => $web,'phone' => $phone,'Image' => $Image );
                        // echo "<pre>";
                        // print_r($data);exit();
                        //$this->places_model->edit_custom_place($id,$data);            
                }
                echo $_POST['Id'];
                redirect('/admin/places_/index');
        }




	public function edit_Place()
	{
			if(!isset($_POST))
			{
				redirect('/admin/places_/experiance');
				exit();
			}
			

			// echo "<pre>";
			// print_r($_POST);exit();
				
            	$id = $_POST['experince_id'];
				// $data['tour_name'] = $_POST['tour_name'];
				// $data['tour_description'] = $_POST['tour_description'];
				// $data['tags'] = $_POST['tags_detail'];
				// $data['public_private_group'] = $_POST['group_detail'];

				$description = $_POST['description'];
				$user_select_box = $_POST['user_select_box'];
				$place_select_box = $_POST['place_select_box'];
				$tour_name = $_POST['tour_name'];
				$tour_description = $_POST['tour_description'];
				$tags_detail = $_POST['tags_detail'];
				$group_detail = $_POST['group_detail'];

				
				$places_list_data=array();
				for ($i=0;$i<count($place_select_box);$i++) 
				{
					array_push($places_list_data,$place_select_box[$i].':'.base64_encode($description[$i]) );
				}

				$p_list = implode(",",$places_list_data);

				$data_res = $this->places_model->update_places(array(
														'tour_name'=>$tour_name,
														'tour_description'=>$tour_description,
														'tags'=>$tags_detail,
														'public_private_group'=>$group_detail,
														'places'=>$p_list),$id
													     );
				$place_detail_array=array();
				$place_detail_array['Places_List_all'] = $p_list;
				$place_detail_array['Description'] = $tour_name;

				// echo "<pre>";

				// print_r($place_detail_array);exit();
				foreach ($user_select_box as $key) 
				{
					if($key == '')
					{
						continue;
					}
					$this->places_model->update_experinces_to_user(array(
														'FbUserId'=>$key,
														'TourName'=>$tour_description,
														'Detail'=>json_encode($place_detail_array),
														'Profile'=>$group_detail,
														'Shared_id' =>$data_res),$id
													     );

				}
				
				// json_encode($jsondata, true);
				
				redirect('/admin/places_/experiance');
				exit();				

				// $this->places_model->update_places($data);
    //         	redirect('/admin/places_/experiance');
				// exit();
			
	}

	public function accept_Place()
	{

if($_POST['Id'] != '')
		{
			$id = $_POST['Id'];
			$Image = $_POST['Image'];
			 $category = $_POST['category'];	
			//print_r($category);exit();
			$LatLng='{ "H":'.$_POST['latitude'].', "L":'.$_POST['longitude'].'}';

			$e = array();
			$e['name'] = $_POST['place_name'];
		    $e['address']  = $_POST['address'];
		    $e['price'] = "";
		    $e['date'] = "";
		    $e['startTime']  = "";
		    $e['endTime'] = "";
		    $e['about'] = base64_encode($_POST['description']);
		    $e['Reviews']  = "";
		   // $e['type'] = $_POST['category'];
		    $e['Info_1'] = "";
		    $e['Info_2'] = "";
		    $e['Info_3'] =  "";
		    $e['Phone'] = $_POST['phone'];
		    $e['Website'] = $_POST['web'];
		    $e['Path'] = '';
		    $e['profileimage'] = $Image;

		    $placeId = random_string('alnum',20);
	 		$venueId = $placeId;
//			print_r($e['about']);
//exit();
  			$detail = json_encode($e);
      	
		if(($sitelist = $this->site_model->addEventsPlaces($placeId,$venueId,$LatLng,$LatLng,$category,$detail,"false")))
	  	
	{
				$this->places_model->remove_custom_place($id);	
	  		}	
	  		echo $venueId;	
		}
	}
	//public function experiance()
	// {
	// 		$limit = 10;
	// 		$offset = 0;
	// 		if($total = $this->places_model->get_custom_places(array(),true))
	// 		{
	// 			$config['base_url'] = site_url('admin/Places/index/');
	// 			$config['total_rows'] = $total;
	// 			$config['per_page'] = $limit = 10;
	// 			$config['uri_segment'] = 4;
	// 			$config['num_links'] = 2;	
				
	// 			$config['first_tag_open'] = '<li>';
	// 			$config['first_tag_close'] = '</li>';
	// 			$config['last_tag_open'] = '<li>';
	// 			$config['last_tag_close'] = '</li>';
	// 			$config['next_tag_open'] = '<li class="next">';
	// 			$config['next_tag_close'] = '</li>';
	// 			$config['prev_tag_open'] = '<li class="previous">';
	// 			$config['prev_tag_close'] = '</li>';
	// 			$config['cur_tag_open'] = '<li class="active">';
	// 			$config['cur_tag_close'] = '</li>';
	// 			$config['num_tag_open'] = '<li>';
	// 			$config['num_tag_close'] = '</li>';
	// 			// Initialize
	// 			$this->pagination->initialize($config);
	// 			$pages = $this->pagination->create_links();	
	// 			$offset = $this->uri->segment(4) ? $this->uri->segment(4) : 0;
	// 			$data['pages'] = $pages;
	// 			$data['index'] = $offset;	
	// 		}
			
	// 		// if(!($media = $this->places_model->getExperiences(array(),false,$limit,$offset)))
	// 		// {
	// 		// 	$media['0'] = "no-data";	
	// 		// }
	// 		// $data['media'] = $media;
			
		
	// 		// if(!($place_list = $this->places_model->getExperiences(array(),false,$limit,$offset)))
	// 		// {
	// 		// 	$place_list['0'] = "no-data";	
	// 		// }
	// 		 $data['admin_header']=$this->load->view('admin/admin_header','',true);
	// 		//$data['galleries']= $place_list;

	// 		//echo json_encode($data['galleries']);
			
	// 		$this->load->view('admin/Custom_experiances',$data);
	// }

	public function experiance()
	{
		$data['admin_header']=$this->load->view('admin/admin_header','',true);
		$this->load->view('admin/Custom_experiances',$data);
	}

	// public function experiance_kendo()
	// {
	// 		$limit = 10;
	// 		$offset = 0;
	// 		if($total = $this->places_model->get_custom_places(array(),true))
	// 		{
	// 			$config['base_url'] = site_url('admin/Places/index/');
	// 			$config['total_rows'] = $total;
	// 			$config['per_page'] = $limit = 10;
	// 			$config['uri_segment'] = 4;
	// 			$config['num_links'] = 2;	
				
	// 			$config['first_tag_open'] = '<li>';
	// 			$config['first_tag_close'] = '</li>';
	// 			$config['last_tag_open'] = '<li>';
	// 			$config['last_tag_close'] = '</li>';
	// 			$config['next_tag_open'] = '<li class="next">';
	// 			$config['next_tag_close'] = '</li>';
	// 			$config['prev_tag_open'] = '<li class="previous">';
	// 			$config['prev_tag_close'] = '</li>';
	// 			$config['cur_tag_open'] = '<li class="active">';
	// 			$config['cur_tag_close'] = '</li>';
	// 			$config['num_tag_open'] = '<li>';
	// 			$config['num_tag_close'] = '</li>';
	// 			// Initialize
	// 			$this->pagination->initialize($config);
	// 			$pages = $this->pagination->create_links();	
	// 			$offset = $this->uri->segment(4) ? $this->uri->segment(4) : 0;
	// 			$data['pages'] = $pages;
	// 			$data['index'] = $offset;	
	// 		}
			
	// 		// if(!($media = $this->places_model->getExperiences(array(),false,$limit,$offset)))
	// 		// {
	// 		// 	$media['0'] = "no-data";	
	// 		// }
	// 		// $data['media'] = $media;
			
		
	// 		if(!($place_list = $this->places_model->getExperiences(array(),false,$limit,$offset)))
	// 		{
	// 			$place_list['0'] = "no-data";	
	// 		}
	// 		$data['admin_header']=$this->load->view('admin/admin_header','',true);
	// 		$data['galleries']= $place_list;

	// 		echo json_encode($data['galleries']);
			
	// 		//$this->load->view('admin/Custom_experiances',$data);
	// }

	public function index_experience_kendo()
	{
			$exp_place_list = $this->places_model->get_experience_kendo();
			//$data['galleries'] = $exp_place_list;
			$data['admin_header']=$this->load->view('admin/admin_header','',true);

			$exp_places=array();
			foreach ($exp_place_list as $value) 
			{
				
				$places_=array();

				$places_["experince_id"]=$value['experince_id'];
				$places_["tour_name"]=$value['tour_name'];
				$places_["tour_description"]=$value['tour_description'];
				$places_["tags"]=$value['tags'];
				$places_["public_private_group"]=$value['public_private_group'];
				
				

				$comma_remover_place=explode(",",$value['places']);
				foreach ($comma_remover_place as $comma_remover) {
					$colon_remover_place=explode(":",$comma_remover);
				
					$places_exp=base64_decode($colon_remover_place[1]);	
				
				 	$places_["places_exp"]=$places_exp;
				}
				
				
				

				// $places_["places_exp"]=$places_exp;
				// foreach ($comma_remover_place as $value1) {
				// 	$places_exp=base64_decode($value1);	
					
				// 	echo "<pre>";
				// 	print_r($places_["places_exp"]);
				// }

				// $places_exp=base64_decode($value['places']);
				// $places_["places_exp"]=$places_exp;
				
				
				 array_push($exp_places,$places_);

			}
			// echo "<pre>";
			// print_r($exp_places);
			// exit();

			echo json_encode($exp_places);	
	}

	public function News()
	{
		$limit = 10;
		$offset = 0;
		if($total = $this->places_model->get_custom_news(array(),true))
		{
			$config['base_url'] = site_url('admin/Places/index/');
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
			
			$this->pagination->initialize($config);
			$pages = $this->pagination->create_links();	
			$offset = $this->uri->segment(4) ? $this->uri->segment(4) : 0;
			$data['pages'] = $pages;
			$data['index'] = $offset;	
		}
			
		if(!($news_list = $this->places_model->get_custom_news(array(),false,$limit,$offset)))
		{
			$news_list['0'] = "no-data";	
		}

		$data['experiances']= $news_list;
		$data['admin_header']=$this->load->view('admin/admin_header','',true);
		$this->load->view('admin/Cust_News',$data);
	}

	public function index_news_kendo()
	{
			$news_list = $this->places_model->get_news_kendo();
			$data['news'] = $news_list;
			$data['admin_header']=$this->load->view('admin/admin_header','',true);
			echo json_encode($data['news']);	
	}

	

	public function save_news()
	{

			$Title_  	= 	$_POST['Title_'];
			$Excerpt_  	= 	$_POST['Excerpt_'];
			$Link_  	= 	$_POST['Link_'];
			$date_  	= 	$_POST['date_'];

			if(isset($_FILES['Image_']['name']) && (!empty($_FILES['Image_']['name'])))
			{
				$size = $_FILES['Image_']['size'];

		        if ($size < 1000000)
		        {

					$s3 = new S3($this->awsAccessKey, $this->awsSecretKey);
					
					$fileName = $_FILES['Image_']['name'];
			        $fileTempName = $_FILES['Image_']['tmp_name'];  

			        $ext =  '';

			        if($_FILES['Image_']['type'] == "image/png")
			        {
						$ext = ".png";
			        }   
			        else
			        {
			        	$ext = ".jpeg";
			        } 

			        $newfilename = round(microtime(true)) . $ext;
			        
			        if($s3->putObjectFile($fileTempName, "retail-safari", $newfilename, S3::ACL_PUBLIC_READ))
			        {
			        	
			        	$Image = $newfilename;
		        		
		        		$data = array();
		        		$data['Title'] = $Title_;
		        		$data['Excerpt'] = $Excerpt_;
		        		$data['Link'] = $Link_; 
		        		$data['Date'] = $date_;
		        		$data['Image'] = $Image;

		        	  	$save_News = $this->places_model->save_custom_news($data);

		        	 	echo $save_News;
			         	//echo 'success';
		        	 }
		        	 else
		        	 {
		        	 	echo 'error';
		        	 }
		        }
		        else
		        {
		        	echo "size_issue";
		        }


			}
			else
			{
				$data = array();
        		$data['Title'] = $Title_;
        		$data['Excerpt'] = $Excerpt_;
        		$data['Link'] = $Link_; 
        		$data['Date'] = $date_;
        		$data['Image'] = $Image;

        		$save_News = $this->places_model->save_custom_news($data);

        		echo $save_News;
			}
			// else
			// {
			// 	echo "empty";
			// }
			
		//exit();
		redirect('/admin/places_/News');
	}

	public function edit_news()
	{
		
		if($_POST['id'] != '')
		{	
			$id 		= 	$_POST['id'];
			$Title_  	= 	$_POST['Title_'];
			$Excerpt_  	= 	$_POST['Excerpt_'];
			$Link_  	= 	$_POST['Link_'];
			$date_  	= 	$_POST['date_'];
			$Image 		= 	$_POST['id_image'];	

			if(isset($_FILES['Image_']['name']) && (!empty($_FILES['Image_']['name'])))
			{
				$size = $_FILES['Image_']['size'];

		        if ($size < 1000000)
		        {
					$s3 = new S3($this->awsAccessKey, $this->awsSecretKey);

					$fileName = $_FILES['Image_']['name'];
			        $fileTempName = $_FILES['Image_']['tmp_name'];             
			        $newfilename = round(microtime(true)) . '.jpeg';

			        if($s3->putObjectFile($fileTempName, "retail-safari", $newfilename, S3::ACL_PUBLIC_READ))
		        	{
		        		$Image = $newfilename;
		        		
		        		$data = array();
		        		$data['Title'] = $Title_;
		        		$data['Excerpt'] = $Excerpt_;
		        		$data['Link'] = $Link_; 
		        		$data['Date'] = $date_;
		        		$data['Image'] = $Image;

		        		$save_News = $this->places_model->update_custom_news($data,$id);

		        		echo $save_News;
		        	}
		        	else
		        	{
		        		echo 'error';
		        	}
		        }
		        else
		        {
		        	echo 'size issue';
		        }
			}
			else
			{
				$data = array();
        		$data['Title'] = $Title_;
        		$data['Excerpt'] = $Excerpt_;
        		$data['Link'] = $Link_; 
        		$data['Date'] = $date_;
        		$data['Image'] = $Image;

        		$save_News = $this->places_model->update_custom_news($data,$id);

        		echo $save_News;
			}	
		}
		echo $_POST['id'];
		redirect('/admin/places_/News');
	}

	public function add_or_remove_custom_news()
	{
		if($_POST['status'] === 'remove')
		{
			$ids = $_POST['id'];
			$this->places_model->remove_custom_news($ids);		
		}
		exit;
	}

	function get_news()
	{
		$data = $this->places_model->get_news();
		echo $_GET['callback'] .'('. json_encode($data) . ')';
		exit;	
	}

	public function add_places()
	{
		if(!isset($_POST))
		{
			redirect('/admin/places_/experiance');
			exit();
		}
		else if($_POST['tour_description'] == '')
		{
			redirect('/admin/places_/experiance');
			exit();
		}

		$description = $_POST['description'];
		$user_select_box = $_POST['user_select_box'];
		$place_select_box = $_POST['place_select_box'];
		$tour_name = $_POST['tour_name'];
		$tour_description = $_POST['tour_description'];
		$tags_detail = $_POST['tags_detail'];
		$group_detail = $_POST['group_detail'];

		// echo "<pre>";
		// print_r($description);
		// print_r($user_select_box);
		// print_r($place_select_box);
		// print_r($tour_description);
		// print_r($tags_detail);
		// print_r($group_detail);
		// exit();
		$places_list_data=array();
		for ($i=0;$i<count($place_select_box);$i++) 
		{
			array_push($places_list_data,$place_select_box[$i].':'.base64_encode($description[$i]) );
		}

		$p_list = implode(",",$places_list_data);

		$data_res = $this->places_model->add_experinces(array(
												'tour_name'=>$tour_name,
												'tour_description'=>$tour_description,
												'tags'=>$tags_detail,
												'public_private_group'=>$group_detail,
												'places'=>$p_list)
											     );
		$place_detail_array=array();
		$place_detail_array['Places_List_all'] = $p_list;
		$place_detail_array['Description'] = $tour_name;

		// echo "<pre>";

		// print_r($place_detail_array);exit();
		foreach ($user_select_box as $key) 
		{
			if($key == '')
			{
				continue;
			}
			$this->places_model->add_experinces_to_user(array(
												'FbUserId'=>$key,
												'TourName'=>$tour_description,
												'Detail'=>json_encode($place_detail_array),
												'Profile'=>$group_detail,
												'Shared_id' =>$data_res)
											     );

		}
		
		// json_encode($jsondata, true);
		
		redirect('/admin/places_/experiance');
		exit();
	}

	public function get_placeslist()
	{

		if(!($arr['places'] = $this->places_model->get_places_info_for_exp()))   //not worry about 10,0 as last field i.e. all field are set to true.
		{
			$arr['places']['0'] = "no-data";	
		}

		if(!($arr['user'] = $this->places_model->get_user_info_for_exp()))   //not worry about 10,0 as last field i.e. all field are set to true.
		{
			$arr['user']['0'] = "no-data";	
		}

		echo json_encode($arr);
		exit;	
	}

	public function deleteMultipleExperiences()
    {
        try
        {
            $id=$_POST['values'];
           
            //$selectedId=explode(",",$id);

            $val='';
            foreach ($id as $id_s_selected) {              
                // $data["file"] = $this->places_model->Get_OwnFileForedit_multiple_selected($this->session->userdata('user_id'),$id_s_selected);
                // print_r($id_s_selected);
                // if(count($data['file'])>0)
                // {
                    $val=$this->places_model->Multiple_remove_File($id_s_selected);
                // }
                // else
                // {
                //     $val="No-owner";
                // }
             
            }
          
            echo $val;
        }
        catch(Exception $e)
        {
            log_message('error', $e->getMessage());
            return;
        }
    }


    public function deleteMultipleNews()
    {
        try
        {
            $id=$_POST['values'];
           	
           	$val='';
            foreach ($id as $id_s_selected) {              
                    $val=$this->places_model->Multiple_remove_File_news($id_s_selected);
            }
				          
            echo $val;
        }
        catch(Exception $e)
        {
            log_message('error', $e->getMessage());
            return;
        }
    }

    public function deleteMultipleCustomPlaces()
    {
        try
        {
            $id=$_POST['values'];
           	
           	$val='';
            foreach ($id as $id_s_selected) {              
                    $val=$this->places_model->Multiple_remove_Custom_Places($id_s_selected);
            }
				          
            echo $val;
        }
        catch(Exception $e)
        {
            log_message('error', $e->getMessage());
            return;
        }
    }

    public function deleteMultipleDeals()
    {
        try
        {
            $id=$_POST['values'];
           	
           	$val='';
            foreach ($id as $id_s_selected) {              
                    $val=$this->places_model->Multiple_remove_Deals($id_s_selected);
            }
				          
            echo $val;
        }
        catch(Exception $e)
        {
            log_message('error', $e->getMessage());
            return;
        }
    }

    

    

        

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
