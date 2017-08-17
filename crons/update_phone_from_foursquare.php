<?php

include('config.php');

$query = ' SELECT Venueid, Detail FROM Place_detail WHERE Venueid IS NOT NULL AND Venueid <> "" ';

$result = $conn->query($query);

$client_id="QHDBKQ2HEW2STOTBCXKTMHIVU25BKO1AIMLDYJK2KPI1YYBO";
$client_secret="JPWCA14UFZZNO2NRTKDWWPN1PX23SKMX4EY4ZGHEKSUFPMBX";
$arr = array();
$in_str = '';
$venue_id_str = '';
$cnt = 1;

$sql = "UPDATE Place_detail SET ";

$total_rec_count = $result->num_rows;
$batch_size = 10;

if($total_rec_count % 10 != 0)
{
	$no_of_batches = round($total_rec_count/10) + 1;	
}


if ($total_rec_count > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        	
        if(!empty($row['Venueid']))
        {
        	$host = "https://api.foursquare.com/v2/multi?requests=";
		    $ver = date('Ymd');

		    if($no_of_batches - 1 == 0)
		    {
		       	$batch_size = $total_rec_count % 10;
		    }

		    if(!empty($venue_id_str))
		    {
		    	$venue_id_str = $venue_id_str.",/venues/".$row['Venueid'];
		    	$row_data_str = $row_data_str."&xplore&".$row['Detail'];
	    	}
		    else
		    {
		    	$venue_id_str = "/venues/".$row['Venueid'];
		    	$row_data_str = $row['Detail'];
		    }			   
		    if($cnt != $batch_size)
		    {
			    $cnt++;
		    }
		    else
		    {
			    $url = $host . $venue_id_str ."&client_id=" . $client_id . "&client_secret=" . $client_secret . "&v=" . $ver;

			    $encoded_url = base64_encode($url);
			    $encoded_row_data = base64_encode($row_data_str);
				exec("php temp.php ".$encoded_url." ".$encoded_row_data." > /dev/null & ");
			   // echo "<br>";
			    //echo("<br>php temp.php ".$encoded_url." ".$encoded_row_data." > /dev/null & ");
			    //echo "<br>";
			   
			    $venue_id_str = '';
			    $row_data_str = '';
			    $cnt = 1;
			    $no_of_batches--;
		    }

		    
        }
    }
} else {
    // echo "0 results";
}


$conn->close();

