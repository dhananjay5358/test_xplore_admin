<?php

$start = microtime(true);

$dbhost = "127.0.0.1";
$dbuser = "root";
$dbpass = "";
$dbname = "new_db";

// Create connection
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

// Check connection
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
}

?>