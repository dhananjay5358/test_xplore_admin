<?php
$start = microtime(true);
use TVW\Yelp;

require '../vendor/autoload.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tojacate_xplore";

$conn = new mysqli($servername, $username, $password, $dbname);

$apiToken = 'A7F4RNSZ2wTYSwJ3xmpa8RYzTmw015wl_aPEa5cz3hYGoSvA5bLuP6ruxLpkyDLZS2Y27jNo0nJAz2vulnS-DLaXaSuoY54lnY76Dz-HrqzgVTD_QQZ6YHiyUeV1WXYx';

$yelpFusion = new Yelp($apiToken);

$in_str = '';
$update_str='';
$query = "SELECT SrNumber,Reviews,Detail FROM Place_detail";
$result = $conn->query($query);
$EmailBId='';
$sql = "UPDATE Place_detail SET ";

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
         $detail=json_decode($row['Detail'],true);
        if(isset($detail['businessId'])){
            if($detail['businessId']==""){
                echo "Business Id is empty";
            }
            else{
                $businessesReviews = $yelpFusion->getDetails("reviews", $detail['businessId']);
                $reviewsInfo = '';
                foreach ($businessesReviews->reviews as $value) {
                    if(!empty($reviewsInfo))
                    {
                        $reviewsInfo = $reviewsInfo."/".$value->text;    
                    }
                    else
                    {
                        $reviewsInfo = $value->text;       
                    }
                    
                    $text = str_replace("'", '', $reviewsInfo);

                }
                if($row['Reviews']!=$text)
                {
                    if(!empty($EmailBId))
                    {
                        $EmailBId = $detail['businessId'].",".$EmailBId;
                    }
                    else
                    {
                        $EmailBId = $detail['businessId'];    
                    }  
                }
                $update_str .= "WHEN '".$row['SrNumber']."' THEN '".$text."' ";
                    
                    if(empty($in_str))
                        $in_str .= "'".$row['SrNumber']."'";
                    else
                        $in_str .= ", '".$row['SrNumber']."'";
                    }

            }   
        }
        
    }
    else {
        echo "0 results";
    }


$sql .= "Reviews = CASE SrNumber ";
$sql .= $update_str;
$sql .= "END";
$sql .= " WHERE SrNumber IN (".$in_str.");";
$qry_result = mysqli_query($conn, $sql);

$end = microtime(true) - $start;

print_r($end);
$schools_array = explode(",", $EmailBId);
$result = count($schools_array);

if(!empty($EmailBId)){
    $message="Hello,Cron succesfully update Reviews for Business Id ".$EmailBId." and number of record updated is ".$result.".";
}
else{
    $message="Hello,There is no updated reviews.";
}
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