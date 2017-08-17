<?php
$start = microtime(true);
use TVW\Yelp;

require 'autoload.php';

//include 'config.php';

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "db_3_xplore";

$conn = new mysqli($servername, $username, $password, $dbname);

$apiToken = 'A7F4RNSZ2wTYSwJ3xmpa8RYzTmw015wl_aPEa5cz3hYGoSvA5bLuP6ruxLpkyDLZS2Y27jNo0nJAz2vulnS-DLaXaSuoY54lnY76Dz-HrqzgVTD_QQZ6YHiyUeV1WXYx';

$yelpFusion = new Yelp($apiToken);
$in_str = '';
$update_str='';
$query = "SELECT SrNumber,Venueid, Detail FROM Place_detail";
$result = $conn->query($query);
$sql = "UPDATE Place_detail SET ";
//echo "<pre>";
if ($result->num_rows > 0) 
{
    while($row = $result->fetch_assoc()) {
        $detail=json_decode($row['Detail'],true);
        // print_r($row['SrNumber']);
        $open_hrs = '';
        $is_closed = '';

        if(!empty($detail['businessId']))
        {
            $businessesInfo = $yelpFusion->getDetails("details", $detail['businessId']);
        }
        else
        {
            $businessesInfo = '';
        }
        if($businessesInfo != null)
        {
            if(isset($businessesInfo->hours[0])){
                $open_hrs = $businessesInfo->hours[0]->open;
            }
            if(isset($businessesInfo->is_closed))
            {
                $isClosed = var_dump($businessesInfo->is_closed);
                $is_closed = $isClosed ? 'true' : 'false';
            }
        }
        if(!empty($detail['isClosed'])!=$is_closed)
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
        $hoursDay = '';
        if(!empty($open_hrs))
        {
            foreach ($open_hrs as $value)
            {
                if($value->day == 0){
                  $day = "Monday";
                }else if($value->day == 1){
                  $day = "Tuesday";
                }else if($value->day == 2){
                  $day = "Wednesday";
                }else if($value->day == 3){
                  $day = "Thursday";
                }else if($value->day == 4){
                  $day = "Friday";
                }else if($value->day == 5){
                  $day = "Saturday";
                }else{
                  $day = "Sunday";
                }
                if(!empty($hoursDay))
                {
                    $hoursDay = $hoursDay.",".$day.'_'.$value->start.'_'.$value->end;    
                }
                else
                {
                    $hoursDay = $day.'_'.$value->start.'_'.$value->end;       
                }

                $detail_ = array();
                $detail_['name'] = str_replace("'", '"', $detail['name']);
                $detail_['address']  = str_replace("'", '"', $detail['address']);
                $detail_['price'] = str_replace("'", '"', $detail['price']);
                $detail_['date'] = str_replace("'", '"', $detail['date']);
                $detail_['startTime']  =str_replace("'", '"', $detail['startTime']);
                $detail_['endTime'] =str_replace("'", '"', $detail['endTime']);
                $detail_['about'] = str_replace("'", '"', $detail['about']);
                $detail_['Reviews']  = str_replace("'", '"', $detail['Reviews']);
                $detail_['type'] = str_replace("'", '"', $detail['type']);
                $detail_['businessId'] = str_replace("'", '"', $detail['businessId']);
                $detail_['rating'] = str_replace("'", '"', $detail['rating']);
                $detail_['Phone'] = str_replace("'", '"', $detail['Phone']);
                $detail_['Like'] = str_replace("'", '"', $detail['Like']);
                $detail_['Website']  = str_replace("'", '"', $detail['Website']);
                $detail_['Path'] =isset($detail['Path']) ? str_replace("'", '"', $detail['Path']) : "";
                $detail_['profileimage'] = isset($detail['profileimage']) ? str_replace("'", '"', $detail['profileimage']) : "";
                $detail_['isClosed'] =  $is_closed;
                $detail_['hours'] = $hoursDay;
                $detail_new = json_encode($detail_);

            }
            echo "<pre>";
            print_r($row['SrNumber']);
            print_r($detail_new);
            $update_str .= "WHEN '".$row['SrNumber']."' THEN '".$detail_new."' ";
            
            if(empty($in_str))
                $in_str .= "'".$row['SrNumber']."'";
            else
                $in_str .= ", '".$row['SrNumber']."'";
        }
    }
}
else
{
    echo "0 results";
}

$sql .= "Detail = CASE SrNumber ";
$sql .= $update_str;
$sql .= "END";
$sql .= " WHERE SrNumber IN (".$in_str.");";

print_r($sql);
//exit();
$qry_result = mysqli_query($conn, $sql);

$end = microtime(true) - $start;

$schools_array = explode(",", $EmailBId);
$result = count($schools_array);

if(!empty($EmailBId))
{
    $message="Hello,Cron succesfully update for Isclosed and Hours information for Business Id ".$EmailBId." , Number of record updated is ".$result.".";
}
else
{
    $message="Hello,There is no updated Isclosed and Hours information Business Id.";
}
print_r($message);

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
// $mail->addAddress('nitinhemmady@gmail.com', 'Nitin Hemmady');
// $mail->isHTML(true);
// $mail->Subject = 'Cron running status';
// $mail->Body    = '<h4>Hello,</h4>
//     <p>Cron successfully updtaed the Hours of population and isclose information in the database.</p>';
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


//echo "<pre>";
//print_r($end);

$conn->close();

?>
