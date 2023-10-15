<?php

    include('functions/session_data.php');
    session_destroy();
    header("Location: index.php");

?>