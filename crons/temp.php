<?php

include('config.php');

$temp = $argv;
$url = $argv[1];
$url = base64_decode($url);
$row_data = $argv[2];
$row_data = base64_decode($row_data);

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_ENCODING, '');
curl_setopt($ch, CURLOPT_URL, $url);
$curl_response = curl_exec($ch);
curl_close($ch);

$curl_response = json_decode($curl_response);
$old_details = explode('&xplore&', $row_data);

$venues_id_arr = explode('/venues/', $url);

$venues_id_count = count($venues_id_arr);

$last_venue_id = explode('&client_id', $venues_id_arr[$venues_id_count - 1]);
$last_venue_id = $last_venue_id[0];
$venues_id_arr[$venues_id_count - 1] = $last_venue_id;

unset($venues_id_arr[0]);
$venues_id_arr = preg_replace("/[^a-zA-Z 0-9]+/", "", array_values($venues_id_arr));

$in_str = '';
$update_str = '';
$sql = "UPDATE Place_detail SET ";

if(!empty($curl_response))
{
	foreach ($curl_response->response->responses as $key => $response_arr) {
		
		$response = $response_arr->response;

		if(!empty($response))
		{

			$old_detail = json_decode($old_details[$key]);	
			$new_phone = (isset($response->venue->contact->phone)) ? "+1".$response->venue->contact->phone : $old_detail->Phone;

			if(isset($response->venue->likes->count))
			{
				$like_count = $response->venue->likes->count;
			}
			else
			{
				$like_count = '';
			}

			if(isset($response->venue->id))
			{
				$venue_id = $response->venue->id;
			}
			else
			{
				$venue_id = '';
			}

			$detail = array();
			$detail['name'] = str_replace("'", "", $old_detail->name);
			$detail['address'] = str_replace("'", "", $old_detail->address);
			$detail['price'] = str_replace("'", "", $old_detail->price);
			$detail['date'] = str_replace("'", "", $old_detail->date);
			$detail['startTime'] = str_replace("'", "", $old_detail->startTime);
			$detail['endTime'] = str_replace("'", "", $old_detail->endTime);
			$detail['about'] = str_replace("'", "", $old_detail->about);
			$detail['Reviews'] = str_replace("'", "", $old_detail->Reviews);
			$detail['type'] = str_replace("'", "", $old_detail->type);
			$detail['Phone'] = $new_phone;
			$detail['Likes'] = $like_count;
			$detail['Website'] = isset($old_detail->Website) ? str_replace("'", "", $old_detail->Website) : "";
			$detail['Path'] = isset($old_detail->Website) ? str_replace("'", "", $old_detail->Path) : "";
			$detail['profileimage'] = isset($old_detail->Website) ? str_replace("'", "", $old_detail->profileimage) : "";

			$detail = json_encode($detail);


			if(!empty($venue_id))
			{
				if($venue_id != $venues_id_arr[$key])
				{
					$update_qry = "UPDATE Place_detail SET Venueid = '".$venue_id."' WHERE Venueid = '".$venues_id_arr[$key]."'";
					mysqli_query($conn, $update_qry);
				}
				
				$update_str .= "WHEN '".$venue_id."' THEN '".$detail."' ";
		    	if(empty($in_str))
					$in_str .= "'".$venue_id."'";
				else
					$in_str .= ", '".$venue_id."'";
			}
				
		}
	}
}

$sql .= "Detail = CASE Venueid ";
$sql .= $update_str;
$sql .= "END";
$sql .= " WHERE Venueid IN (".$in_str.");";

// try{
// require_once __DIR__ . '/PHPMailer/PHPMailerAutoload.php';
// $mail = new PHPMailer;
// $mail->isSMTP();
// $mail->Host = 'email-smtp.us-east-1.amazonaws.com';
// $mail->SMTPAuth = true;
// $mail->Username = 'AKIAJB4CHCSLRDO22RXQ';
// $mail->Password = 'Ap9bJVachZ6mM18mSnEPqitRlk99uVG9ghK74nU/wWFq';
// $mail->SMTPSecure = 'ssl';
// $mail->Port = 465;
// $mail->setFrom('nitinhemmady@gmail.com', 'Nitin Hemmady');
// $mail->addAddress('dhananjay@e-arth.in', 'Dhananjay Kulkarni');
// $mail->isHTML(true);
// $mail->Subject = 'Cron running status';
// $mail->Body    = '<h4>Hello,</h4>
//     <p>Cron successfully updtaed the Rating,Price and Business Id the database.</p>';
// if(!$mail->send()) {
//     echo 'Email not sent.';
//     echo 'Mailer Error: ' . $mail->ErrorInfo;
// } else {
//     echo 'Email sent!';
// }
// }
// catch(Exception $e)
// {
//     log_message('error', $e->getMessage());
//     return;
// }

if(!empty($in_str))
{
	try{
		$qry_result = mysqli_query($conn, $sql);
	}
	catch(Exception $e)
	{
		print_r($e);
	}
}

$conn->close();

?>