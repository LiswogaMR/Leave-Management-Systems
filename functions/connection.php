<?php

    $db = 'leave_management_systems';
    $host = 'localhost';
    $username = 'root';
    $pass = 'virtual@PayDay5.2';
    $conn = mysqli_connect($host, $username, $pass, $db);
    if($conn){
    	//echo 'connected';
    }
    else {
    	//echo "failed";
    }
    
?>
