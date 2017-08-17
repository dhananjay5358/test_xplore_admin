<?php
$first = $_GET['first'];
		$last = $_GET['last'];

		$theName->firstname = $first;
		$theName->lastname = $last;
		//echo json_encode($theName);
		echo $_GET['callback'] .'('. json_encode($theName) . ')';
?>