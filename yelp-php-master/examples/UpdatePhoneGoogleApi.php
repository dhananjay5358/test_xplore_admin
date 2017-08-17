<?php

$start = microtime(true);

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "test-explore";

// Create connection
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//$query = ' SELECT SrNumber,Detail,PlaceIds FROM Place_detail WHERE PlaceIds IS NOT NULL AND PlaceIds <>  "" ';

$query = ' SELECT SrNumber,Detail,PlaceIds FROM Place_detail  ';

$result = $conn->query($query);

$key_="AIzaSyCgFaImUo6xWBC9qizp25fe9IWxDjFVHdM";

$arr = array();
$in_str = '';
$update_str='';
$sql = "UPDATE Place_detail SET ";
echo "<pre>";
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
       	// print_r($result->num_rows);
        // $foursq_lat_long = json_decode($row['Foresquare_lat_lng']);
  
        if(!empty($row['PlaceIds']))
        {

        	// $response = "https://api.foursquare.com/v2/venues/search?ll=".$foursq_lat_long->H.",".$foursq_lat_long->L."&limit=1&client_id=".$client_id."&client_secret=".$client_secret."&v=".date('Ymd');

	        //$host = "https://api.foursquare.com/v2/venues/";

	        
	        $host="https://maps.googleapis.com/maps/api/place/details/json?placeid=".$row['PlaceIds']."&key=".$key_."";
		    //$ver = date('Ymd');
		   
		    //$limit = 50;
		    //$url = $host . $row['Venueid'] ."?client_id=" . $client_id . "&client_secret=" . $client_secret . "&v=" . $ver;
		    // $res = exec('https://api.foursquare.com/v2/venues/4bc1fb802a89ef3b71faf288?client_id=QHDBKQ2HEW2STOTBCXKTMHIVU25BKO1AIMLDYJK2KPI1YYBO&client_secret=JPWCA14UFZZNO2NRTKDWWPN1PX23SKMX4EY4ZGHEKSUFPMBX&v=20170727');
		    // print_r($res);	exit;
		    // initiate curl
		    $ch = curl_init();
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		    curl_setopt($ch, CURLOPT_URL, $host);
		    $curl_response = curl_exec($ch);
		    curl_close($ch);
		    $curl_response = json_decode($curl_response);

		    $curl_response = $curl_response;
	        echo "<pre>";	print_r($curl_response);
	        exit();
	        $arr[] = $curl_response;

// 		    $old_detail = json_decode($row['Detail']);
		    
	        
// 		    $new_phone = (!empty($curl_response->venue->contact->phone)) ? "+1".$curl_response->venue->contact->phone : $old_detail->Phone;

// 		    $like_count = $curl_response->venue->likes->count;

// 	       // print_r($curl_response->venue->likes->count);	exit;

// 	        $detail = array();
// 			$detail['name'] = $old_detail->name;

// 		    $detail['address']  = $old_detail->address;
// 		    $detail['price'] = $old_detail->price;
// 		    $detail['date'] = $old_detail->date;
// 		    $detail['startTime']  = $old_detail->startTime;
// 		    $detail['endTime'] = $old_detail->endTime;
// 		    $detail['about'] = $old_detail->about;
// 		    $detail['Reviews']  = $old_detail->Reviews;
// 		    $detail['type'] = $old_detail->type;

// 	        // $detail['businessId'] = $old_detail->businessId;
//          //    $detail['rating'] = $old_detail->rating;

// 		    $detail['Phone'] = $new_phone;
// 		    $detail['Like'] = $like_count;
// 		    $detail['Website']  = $old_detail->Website;
// 		    $detail['Path'] = $old_detail->Path;
// 		    $detail['profileimage'] = $old_detail->profileimage;
// //		    $detail['isClosed'] = $old_detail->isClosed;
//   //          $detail['hours'] = $old_detail->hoursDay;

//   			$detail = json_encode($detail);
//   			print_r($row['SrNumber']);
// 	        //print_r($detail);
//         	$update_str .= "WHEN '".$row['Venueid']."' THEN '".$detail."' ";
        	
//         	if(empty($in_str))
// 				$in_str .= "'".$row['Venueid']."'";
// 			else
// 				$in_str .= ", '".$row['Venueid']."'";
        	}
        	
    }
} else {
    echo "0 results";
}

// $sql .= "Detail = CASE Venueid ";
// $sql .= $update_str;
// $sql .= "END";
// $sql .= " WHERE Venueid IN (".$in_str.");";

// print_r($sql);
// exit();

// //$qry_result = mysqli_query($conn, $sql);

// $end = microtime(true) - $start;

// echo "<pre>";
// print_r($end);

$conn->close();
?>