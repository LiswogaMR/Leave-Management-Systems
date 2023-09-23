<?php

    $db = 'leave_management_systems';
    $host = 'localhost';
    $username = 'root';
    $pass = '';
    $conn = mysqli_connect($host, $username, $pass, $db);
    if($conn){
    	//echo 'connected';
    }
    else {
    	//echo "failed";
    }
    
?>
