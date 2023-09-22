<?php

    // Start the session
    if(!isset($_SESSION)){ 
        session_start();
    } 

    if(!isset($_SESSION['user'])){
        session_destroy();
        header("Location: index.php");
    }
    //print_r($_SESSION);

?>