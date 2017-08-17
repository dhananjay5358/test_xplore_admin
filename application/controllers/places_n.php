<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class places_n extends CI_Controller {
	var $awsAccessKey = "AKIAJ2RVZ5W24XMUFG5A"; 
	var $awsSecretKey = "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";

	public function __construct()
	{
		parent::__construct();
		$this->load->model('site_model');
		$this->load->model('images_model');
		$this->load->model('admin/places_model');
		require_once(APPPATH.'libraries/S3.php');
		$this->load->library('session');
	}

	public function index()
	{
		
	}

//	public function places_save()
  //  {
	//	print_r($_POST);exit();
    //		if(isset($_POST['Name']) && (!empty($_POST['Name'])))
//			{
//	   			$place_name = $_POST['Name'];
//				$latitude = $_POST['Latitude'];
//				$longitude = $_POST['Longitude'];
//				$web = $_POST['Web'];
				//$category = $_POST['category'];
//				$phone = $_POST['Phone'];
//				$address = $_POST['Address'];
//				$description = $_POST['Description'];
//
//				$web = $_POST['web'];
//				$phone = $_POST['phone'];

//	            if(isset($_FILES['photoPath']['name']) && (!empty($_FILES['photoPath']['name'])))
//				{
//					$size     = $_FILES['photoPath']['size'];
//
//			        if ($size > 1000000)
//			        {
//						$s3 = new S3($this->awsAccessKey, $this->awsSecretKey);
//						$fileName = $_FILES['photoPath']['name'];
//				        $fileTempName = $_FILES['photoPath']['tmp_name'];             
//				        $newfilename = round(microtime(true)) . '.jpeg';
//
//				        if($s3->putObjectFile($fileTempName, "retail-safari", $newfilename, S3::ACL_PUBLIC_READ))
//			        	{
//			        		$Image = $newfilename;
//			        		$data = array( 'place_name' => $place_name,'address' => $address,'description' => $description,'category' => $category,'longitude' => $longitude,'latitude' => $latitude,'web' => $web,'phone' => $phone,'Image' => $Image );
//			        		$save_custom_place = $this->places_model->save_custom_place($data);
//
//			        		echo $save_custom_place;
//			        	}
//			        	else
//			        	{
//			        		echo 'error';
//			        	}
//			        }
//			        else
//			        {
//			        	echo "size_issue";
//			        }
//				}
//				else
//				{
//					echo "empty";
//				}
//			}
//			else
//			{
//				echo "empty";
//			}
  //  }
		public function places_save()
    {
    		if(isset($_POST['Name']) && (!empty($_POST['Name'])))
			{
	   			$place_name = $_POST['Name'];
				
//				$location_name = $_POST['location_name'];
								

				$latitude = $_POST['Latitude'];
				$longitude = $_POST['Longitude'];
				$web = $_POST['Web'];
				//$category = $_POST['category'];
				$phone = $_POST['Phone'];
				$address = $_POST['Address'];
				$description = $_POST['Description'];

			//	$web = $_POST['web'];
			//	$phone = $_POST['phone'];

	            if(isset($_FILES['photoPath']['name']) && (!empty($_FILES['photoPath']['name'])))
				{
					$size     = $_FILES['photoPath']['size'];

			        if ($size > 1000000)
			        {
						$s3 = new S3($this->awsAccessKey, $this->awsSecretKey);
						$fileName = $_FILES['photoPath']['name'];
				        $fileTempName = $_FILES['photoPath']['tmp_name'];             
				        $newfilename = round(microtime(true)) . '.jpeg';

				        if($s3->putObjectFile($fileTempName, "retail-safari", $newfilename, S3::ACL_PUBLIC_READ))
			        	{
			        		$Image = $newfilename;
			        		$data = array( 'place_name' => $place_name,'address' => $address,'description' => $description,'longitude' => $longitude,'latitude' => $latitude,'web' => $web,'phone' => $phone,'Image' => $Image );
			        		$save_custom_place = $this->places_model->save_custom_place($data);

			        		echo $save_custom_place;
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
					echo "empty";
				}
			}
			else
			{
				echo "empty";
			}
    }        
}


