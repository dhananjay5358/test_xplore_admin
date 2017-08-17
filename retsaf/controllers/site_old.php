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
		
		//$this->load->model('media_model');
		//$this->load->model('interest_model');
		$this->load->model('site_model');
		$this->load->model('images_model');
		
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