<?php

    // Start the session
    if (!isset($_SESSION)) { 
        session_start();
    } 

    // Check if the user is logged in
    if (!isset($_SESSION['user'])) {
        session_destroy();
        header("Location: index.php");
    }

    // Check for user inactivity
    // $inactive = 10; // 60 seconds (1 minute)
    // if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactive)) {
    //     // User has been inactive for more than a minute, log them out
    //     session_destroy();
    //     header("Location: index.php");
    // } else {
    //     // Update the last activity timestamp
    //     $_SESSION['last_activity'] = time();
    // }
    
?>