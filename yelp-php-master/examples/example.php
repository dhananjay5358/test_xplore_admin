<?php

$start = microtime(true);
use TVW\Yelp;

require 'autoload.php';
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "tojacate_xplore";
$detail_new='';
$conn = new mysqli($servername, $username, $password, $dbname);

/**
 * You must specify a Yelp Fusion API token for this demo
 * see: https://www.yelp.com/developers.
 */
$apiToken = 'A7F4RNSZ2wTYSwJ3xmpa8RYzTmw015wl_aPEa5cz3hYGoSvA5bLuP6ruxLpkyDLZS2Y27jNo0nJAz2vulnS-DLaXaSuoY54lnY76Dz-HrqzgVTD_QQZ6YHiyUeV1WXYx';

// create instance of Yelp-PHP class
$yelpFusion = new Yelp($apiToken);


$query = "SELECT SrNumber,Venueid,Detail FROM Place_detail";
$result = $conn->query($query);
$update_str='';
$in_str='';
$EmailBId='';
$sql = "UPDATE Place_detail SET ";

if ($result->num_rows > 0) {

    while($row = $result->fetch_assoc()) {
        $detail=json_decode($row['Detail'],true);
        if(isset($detail['Phone'])){
            if(empty($detail['Phone']) || ($detail['Phone']=="undefined") ){
            }
            else
            {
                if (strpos($detail['Phone'], '+1') !== false) {
                    $phoneCode=$detail['Phone'];
                }
                else{
                   $phoneCode='+1'.$detail['Phone'];
                }
    
                    $PhoneInfo = $yelpFusion->searchPhone($phoneCode);
                    $businessId=$PhoneInfo->businesses[0]->id;
                    $rating=$PhoneInfo->businesses[0]->rating;
                     
                        if(!empty($PhoneInfo->businesses[0]->price)){
                            $price=$PhoneInfo->businesses[0]->price;
                        }else{
                            $price=$detail['price'];
                        }
                        
                        if(($detail['businessId'] !=$businessId) || ($detail['rating']!=$rating))
                        {
                            if(!empty($EmailBId)){
                            $EmailBId = $businessId.",".$EmailBId;
                            }else{
                            $EmailBId = $businessId;    
                            }  
                        }
                       
                        $detail_ = array();
                        $detail_['name'] = str_replace("'", "", $detail['name']);
                        $detail_['address']  = str_replace("'", "", $detail['address']);
                        $detail_['price'] = $price;
                        $detail_['date'] = str_replace("'", "", $detail['date']);
                        $detail_['startTime']  =str_replace("'", "", $detail['startTime']);
                        $detail_['endTime'] =str_replace("'", "", $detail['endTime']);
                        $detail_['about'] = str_replace("'", "", $detail['about']);
                        $detail_['Reviews']  = str_replace("'", "", $detail['Reviews']);
                        $detail_['type'] = str_replace("'", "", $detail['type']);
                        $detail_['businessId'] = $businessId;
                        $detail_['rating'] = $rating;
                        $detail_['Phone'] = str_replace("'", "", $detail['Phone']);
                        $detail_['Like'] = isset($detail['hours']) ? str_replace("'", "", $detail['Like']):"";
                        $detail_['Website']  = str_replace("'", "", $detail['Website']);
                        $detail_['Path'] =isset($detail['Path']) ? str_replace("'", "", $detail['Path']) : "";
                        $detail_['profileimage'] = isset($detail['profileimage']) ? str_replace("'", "", $detail['profileimage']) : "";
                        $detail_['isClosed'] =  isset($detail['isClosed']) ? str_replace("'", "", $detail['isClosed']):"";
                        $detail_['hours'] = isset($detail['hours']) ? str_replace("'", "", $detail['hours']):"";
                       
                        $detail_new = json_encode($detail_);
                        $update_str .= "WHEN '".$row['SrNumber']."' THEN '".$detail_new."' ";

                        if(empty($in_str))
                            $in_str .= "'".$row['SrNumber']."'";
                        else
                            $in_str .= ", '".$row['SrNumber']."'";
               
            }
        }   
    }
}else {
    echo "0 results";
}

$sql .= "Detail = CASE SrNumber ";
$sql .= $update_str;
$sql .= "END";
$sql .= " WHERE SrNumber IN (".$in_str.");";
$qry_result = mysqli_query($conn, $sql);

$end = microtime(true) - $start;

$schools_array = explode(",", $EmailBId);
$result = count($schools_array);

if(!empty($EmailBId)){
    $message="Hello,Cron succesfully update BusinessId,rating and price for Business Id ".$EmailBId." number of record updated is ".$result.".";
}
else{
    $message="Hello,There is no updated Business Id.";
}
print_r($end);
try{
require_once __DIR__ . '/PHPMailer/PHPMailerAutoload.php';
$mail = new PHPMailer;
$mail->isSMTP();
$mail->Host = 'email-smtp.us-east-1.amazonaws.com';
$mail->SMTPAuth = true;
$mail->Username = 'AKIAJB4CHCSLRDO22RXQ';
$mail->Password = 'Ap9bJVachZ6mM18mSnEPqitRlk99uVG9ghK74nU/wWFq';
$mail->SMTPSecure = 'ssl';
$mail->Port = 465;
$mail->setFrom('nitinhemmady@gmail.com', 'Nitin Hemmady');
$mail->addAddress('dhananjay@e-arth.in', 'dhananjay K');
$mail->isHTML(true);
$mail->Subject = 'Cron running status';
$mail->Body =$message;
if(!$mail->send()) {
    echo 'Email not sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Email sent!';
}
}
catch(Exception $e)
{
    log_message('error', $e->getMessage());
    return;
}
$conn->close();


?>