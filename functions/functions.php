<?php

    error_reporting(E_ALL);
    ini_set("display_errors", 1);


    $actionType = '';
    if(isset($_POST['actionType']))
        $actionType = $_POST['actionType'];


    include("connection.php");
    if($actionType != 'login' && $actionType != 'forgotPassword' && $actionType != 'changePassword')
        include('session_data.php');
    else{ 
        session_start();
    }

    $loggedInUser = '';
    $loggedInUserEmail = '';

    if(isset($_SESSION['user']['id']))
        $loggedInUser = $_SESSION['user']['id'];
    if(isset($_SESSION['user']['email']))
        $loggedInUserEmail = $_SESSION['user']['email'];

    $dateTime = date("Y/m/d H:i");


    switch($actionType){
        case 'login':
            if(isset($_POST['email']) && isset($_POST['password'])){
                $email = mysqli_real_escape_string($conn, $_POST['email']);
                $password = mysqli_real_escape_string($conn, $_POST['password']);
                
                $query = "SELECT user.id, user.name, user.surname, user.email, user.password, user.status, permission_group.name AS permission_group_name
                            from user 
                            LEFT JOIN permission_group on permission_group.id = user.permission_group_id 
                            WHERE user.email = '$email' 
                            AND user.status = 'Active'";
                            
                $result = mysqli_query($conn, $query);

                if(mysqli_num_rows($result) > 0){
                    $row = mysqli_fetch_assoc($result);
                    
                    $verify = password_verify($password,$row['password']);

                    if($verify){
                        $_SESSION['user'] = $row;
                        unset($_SESSION['user']['password']);
                        unset($_SESSION['user']['status']);

                        $_SESSION['msg'] = "Logged in.";
                        
                        die(header("Location: ../employee.php"));
                        
                    }else{
                        $_SESSION['msg'] = "Wrong password Entered or Email";
                        die(header("Location: ../index.php"));
                    }
                }else{
                    $_SESSION['msg'] = "User Doesn't exists!!";
                    die(header("Location: ../index.php"));
                }
            }
        break;
        case 'forgotPassword':
            if(isset($_POST['email'])){
                $email = mysqli_real_escape_string($conn, $_POST['email']);
                $email = strtolower($email);
    
                $query = "  SELECT user.id, user.name, user.surname, user.email, user.password, user.status 
                            from user 
                            WHERE user.email = '$email' 
                            AND user.status = 'Active' ";
                            
                $result = mysqli_query($conn, $query);
    
                if(mysqli_num_rows($result) > 0){
                    $row = mysqli_fetch_assoc($result);
                    
                    $password_clear = random_str(7);
                    
                    $password = password_hash($password_clear, PASSWORD_DEFAULT);
                    $dateTime = date('Y-m-d H:i:s');
    
                    $sql = "UPDATE user SET password = '".$password
                            ."', created = '".$dateTime
                            ."' WHERE id = '".$row['id']."' AND status = 'Active'";
    
                    if (mysqli_query($conn, $sql)) {

                        echo $password_clear;
                        echo "The knowledg here is to send the password using WIFI";
                        
                       $_SESSION['msg'] = "Success";
                       die(header("Location: ../index.php"));
                    } else {
                        $_SESSION['msg'] = "Action prohibited";
                        die(header("Location: ../forgot-password.php"));
                    }
                }else{echo 
                    $_SESSION['msg'] = "Unable to reset credentials";
                    die(header("Location: ../forgot-password.php"));
                }
            }
        break;
        case 'changePassword':
            if(isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirmpassword']) && isset($_POST['newpassword'])){
                $email = mysqli_real_escape_string($conn, $_POST['email']);
                $oldpassword = mysqli_real_escape_string($conn, $_POST['password']);
                $newpassword = mysqli_real_escape_string($conn, $_POST['newpassword']);
                $confirmpassword = mysqli_real_escape_string($conn, $_POST['confirmpassword']);
    
                $loggedInUserEmail = $_SESSION['user']['email'];
                if($email == $loggedInUserEmail){
                    //you are changing the correct things you can continue.
                }else{
                    session_destroy();
                    header("Location: ../index.php");
                }

                if($newpassword === $confirmpassword){
    
                    $query = "SELECT user.id, user.name, user.surname, user.email, user.password, user.status 
                            from user 
                            WHERE user.email = '$email' 
                            AND user.status = 'Active' ";
                            
                    $result = mysqli_query($conn, $query);

                    if(mysqli_num_rows($result) > 0){
                        $row = mysqli_fetch_assoc($result);
                        
                        $verify = password_verify($oldpassword,$row['password']);
                        $user_id = $row['id'];
                        
                        if($verify){
                            $newpassword = password_hash($newpassword, PASSWORD_DEFAULT);
                            $dateTime = date('Y-m-d H:i:s');
            
                            $sql = "UPDATE user SET password = '".$newpassword
                                    ."', created = '".$dateTime
                                    ."' WHERE id = '".$row['id']."' AND status = 'Active'";
            
                            if (mysqli_query($conn, $sql)) {
                                $subject = "Change Of Password On The System";
                                $message = "Your user details have been Changed on Leave Management System";
            
                                $_SESSION['msg'] = "Success";
                                die(header("Location: ../employee.php"));
                            } else {
                                $_SESSION['msg'] = "Action prohibited";
                                die(header("Location: ../changePassword.php"));
                            }
                        }else{
                            $_SESSION['msg'] = "Email and password does not match";
                            die(header("Location: ../changePassword.php"));
                        }
                    }else{
                        $_SESSION['msg'] = "No user with that credititials was Found. Please contact Admin";
                        die(header("Location: ../changePassword.php"));
                    }
    
                }else{
                    $_SESSION['msg'] = "The Two password does not match";
                    die(header("Location: ../changePassword.php"));
                }
            }
        break;
        case 'addUser':
            //make code to make sure you cannot manage yourself
            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $surname = mysqli_real_escape_string($conn, $_POST['surname']);
            $password_clear = $password = mysqli_real_escape_string($conn, $_POST['password']);
            $status = mysqli_real_escape_string($conn, $_POST['status']);
            $permission_group_id = mysqli_real_escape_string($conn, $_POST['permission_group_id']);
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $status = 'Active';
            
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $_SESSION['msg'] = "Please enter a valid email";
                die(header("Location: ../admin-users.php"));
            }

            if($name == '' || $surname == '' || $password == '' || $permission_group_id == '' || $email == '' ){
                $_SESSION['msg'] = "All Input field are required";
                die(header("Location: ../admin-users.php"));
            }
            
            $password = password_hash($password, PASSWORD_DEFAULT);
            $result = get_record_assoc('*', 'user', '', "email = '$email'", '', false, $conn);
            if(count($result) > 0){
                $_SESSION['msg'] = "This user is already added.";
                die(header("Location: ../admin-users.php"));
            }else{
                $sql = "INSERT INTO user (name, surname, password, email, permission_group_id, created,status)
                VALUES ('".$name."', '".$surname."','".$password."','".$email."','".$permission_group_id."','". $dateTime."','". $status."')";
                if (mysqli_query($conn,$sql )) {
                    //The logic would be to send an email to the user who has been added with their creaditials
                    $_SESSION['msg'] = "Successfully added a new user";
                    die(header("Location: ../admin-users.php"));
                }else{
                    $_SESSION['msg'] = "Error updating data, kindly try again later";
                }

                die(header("Location: ../admin-users.php"));

            }
                        
        break;
        case 'applyLeave':
            //make code to make sure you cannot manage yourself
            $leaveType = mysqli_real_escape_string($conn, $_POST['leaveType']);
            $comment = mysqli_real_escape_string($conn, $_POST['comment']);
            $status = '3';

            
            //Retrieve and sanitize the input values
            $startDate = mysqli_real_escape_string($conn, $_POST['startDate']);
            $endDate = mysqli_real_escape_string($conn, $_POST['endDate']);

            //Convert the input dates to DateTime objects for comparison
            $startDateTime = new DateTime($startDate);
            $endDateTime = new DateTime($endDate);
            $today = new DateTime();
        
            //Check if the start date is not in the past
            if($leaveType = '2'){
                //sick leave can be in the past it is okay to continue
            }elseif($startDateTime < $today->format('Y-m-d')){
                $_SESSION['msg'] = "Error!!! Start date cannot be in the past.";
                die(header("Location: ../employee.php"));
            }elseif ($endDateTime < $startDateTime){
                $_SESSION['msg'] = "Error!!! End date must be greater than the start date.";
                die(header("Location: ../employee.php"));
            }
    
            $currentYear = date('Y');
            // Calculate the difference between the two dates
            $startDateconvert = DateTime::createFromFormat('Y-m-d', $startDate);
            $endDateconvert = DateTime::createFromFormat('Y-m-d', $endDate);
            $interval = $startDateconvert->diff($endDateconvert);
            // Get the number of days from the difference
            $numberOfDays = $interval->days + 1;

            $sql = "INSERT INTO leave_management_systems.leave (user_id, year, status_id, created, start_date, end_date,no_of_days,user_comments,leave_type_id)
                    VALUES ('".$loggedInUser."', '".$currentYear."','".$status."',NOW(),'".$startDate."','". $endDate."','". $numberOfDays."','". $comment."','". $leaveType."')";
       
            if (mysqli_query($conn,$sql )) {
                //The logic would be to send an email to the user who has been added with their creaditials
                $_SESSION['msg'] = "Successfully Submitted your Leave Request";
                die(header("Location: ../employee.php"));
            }else{
                $_SESSION['msg'] = "Error updating data, kindly try again later";
            }

            die(header("Location: ../employee.php"));
                
        break;
           case 'applyLeave':
            //make code to make sure you cannot manage yourself
            $leaveType = mysqli_real_escape_string($conn, $_POST['leaveType']);
            $comment = mysqli_real_escape_string($conn, $_POST['comment']);
            $status = '3';

            
            //Retrieve and sanitize the input values
            $startDate = mysqli_real_escape_string($conn, $_POST['startDate']);
            $endDate = mysqli_real_escape_string($conn, $_POST['endDate']);

            //Convert the input dates to DateTime objects for comparison
            $startDateTime = new DateTime($startDate);
            $endDateTime = new DateTime($endDate);
            $today = new DateTime();
        
            //Check if the start date is not in the past
            if($leaveType = '2'){
                //sick leave can be in the past it is okay to continue
            }elseif($startDateTime < $today->format('Y-m-d')){
                $_SESSION['msg'] = "Error!!! Start date cannot be in the past.";
                die(header("Location: ../employee.php"));
            }elseif ($endDateTime < $startDateTime){
                $_SESSION['msg'] = "Error!!! End date must be greater than the start date.";
                die(header("Location: ../employee.php"));
            }
    
            $currentYear = date('Y');
            // Calculate the difference between the two dates
            $startDateconvert = DateTime::createFromFormat('Y-m-d', $startDate);
            $endDateconvert = DateTime::createFromFormat('Y-m-d', $endDate);
            $interval = $startDateconvert->diff($endDateconvert);
            // Get the number of days from the difference
            $numberOfDays = $interval->days + 1;

            $sql = "INSERT INTO leave_management_systems.leave (user_id, year, status_id, created, start_date, end_date,no_of_days,user_comments,leave_type_id)
                    VALUES ('".$loggedInUser."', '".$currentYear."','".$status."',NOW(),'".$startDate."','". $endDate."','". $numberOfDays."','". $comment."','". $leaveType."')";
       
            if (mysqli_query($conn,$sql )) {
                //The logic would be to send an email to the user who has been added with their creaditials
                $_SESSION['msg'] = "Successfully Submitted your Leave Request";
                die(header("Location: ../employee.php"));
            }else{
                $_SESSION['msg'] = "Error updating data, kindly try again later";
            }

            die(header("Location: ../employee.php"));
                
        break;
        case 'addJobTitle':
            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $status = 'Active';
            $result = get_record_assoc('*', 'leave_status', '', "name = '$name'", '', false, $conn);
            if(count($result) > 0){
                $_SESSION['msg'] = "This Leave status is already added.";
                die(header("Location: ../admin-leave-status.php"));
            }else{
                $sql = "INSERT INTO leave_management_systems.leave_status (name, status, updated) values ('".$name."','".$status."','". $dateTime."')";
                // echo $sql;exit;
                if (mysqli_query($conn, $sql)) {
                    $_SESSION['msg'] = "Record inserted successfully";
                } else {
                    $_SESSION['msg'] = "Error inserting data, kindly try again later";
                }
                die(header("Location: ../admin-leave-status.php"));
            }
            
        break;
        case 'updateJobTitle':
            $name = mysqli_real_escape_string($conn, $_POST['name']);
            
            $id = is_numeric($_POST['rec']) ? $_POST['rec'] : null;
    
            $status = 'Active';
            if($name == 'Awaiting'){
                $_SESSION['msg'] = "This Leave name Cannot be manipulated. If you want to continue Contact IT team";
                die(header("Location: ../admin-leave-status.php"));
            }
    
            $sql = "UPDATE leave_status SET name = '".$name."', status = '".$status."', updated = '".$dateTime."' WHERE id = '".$id."'";
            if (mysqli_query($conn, $sql)) {
                $_SESSION['msg'] = "Record updated successfully";
            } else {
                //echo "Error updating record: " . mysqli_error($conn);
                $_SESSION['msg'] = "Error updating record";
            }
            die(header("Location: ../admin-leave-status.php"));
        break;
        case 'deleteJobTitle':
            $status = mysqli_real_escape_string($conn, $_POST['status']);
            if(mysqli_real_escape_string($conn, $_POST['parameter']) == 'statusUpdate'){
                if($status == 'Active')
                    $status = 'Inactive';
                else if($status == 'Inactive')
                    $status = 'Active';
            }else{
                $status = 'Inactive';
            }
    
            $id = is_numeric($_POST['rec']) ? $_POST['rec'] : null;
            $result = get_record_assoc('*', 'leave_status', '', "id = '".$id."'", '', false, $conn);
            if(count($result) > 0){
                $_SESSION['msg'] = "This Leave name Cannot be manipulated. If you want to continue Contact IT team.";
                die(header("Location: ../admin-leave-status.php"));
            }

            $sql = "UPDATE leave_status SET status = '".$status."', updated = '".$dateTime."' WHERE id = '".$id."'";
            if (mysqli_query($conn, $sql)) {
                $_SESSION['msg'] = "Record updated successfully";
            } else {
                $_SESSION['msg'] = "Error updating data, kindly try again later";
            }
            die(header("Location: ../admin-leave-status.php"));
        break;
        case 'getPermissionGroup':
            $id = mysqli_real_escape_string($conn, $_POST['rec']);
            $result = get_record_assoc('id, name, created, status',
                                        'permission_group',
                                        '',
                                        "id = '$id'",
                                        '',
                                        true,
                                        $conn);
            die($result);
        break;
        case 'EditLeave':
            $id = mysqli_real_escape_string($conn, $_POST['rec']);
            $Status = mysqli_real_escape_string($conn, $_POST['Status']);
            $manager_comments = mysqli_real_escape_string($conn, $_POST['manager_comments_convert']);
        
            $sql = "UPDATE leave_management_systems.leave SET status_id = '".$Status."', manager_comments = '".$manager_comments."', updated = NOW(), modified_by_id = '".$loggedInUser."' WHERE id = '".$id."' ";
            if (mysqli_query($conn,$sql )) {
                //The logic would be to send an email to the user who has been added with their creaditials
                $_SESSION['msg'] = "Successfully Updated the leave status";
                // The aim would be to send an email to the person who logged in the leave to notify them about these changes
                die(header("Location: ../employee.php"));
            }else{
                $_SESSION['msg'] = "Error updating data, kindly try again later";
            }
            
            die(header("Location: ../employee.php"));
        break;
        case 'getUser':
            $id = mysqli_real_escape_string($conn, $_POST['rec']);
            $result = get_record_assoc('user.id, user.name, user.surname, user.email, user.status, permission_group.id AS permission_group_id, permission_group.name AS permission_group_name, user.created',
                                        'user',
                                        'LEFT JOIN permission_group on permission_group.id = user.permission_group_id',
                                        "user.id = '$id'",
                                        '',
                                        true,
                                        $conn);

            die($result);
        break;
        case 'getPermissionGroupAll':
            $result = get_record_assoc('id, name, created, status',
                                        'permission_group',
                                        '',
                                        "",
                                        "",
                                        true,
                                        $conn);
                                        die($result);
        break;
        case 'updateUser':
            //make code to make sure you cannot manage yourself
            $id = mysqli_real_escape_string($conn, $_POST['rec']);
            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $surname = mysqli_real_escape_string($conn, $_POST['surname']);
            $password = mysqli_real_escape_string($conn, $_POST['password']);
            $status = mysqli_real_escape_string($conn, $_POST['status']);
            $permission_group_id = mysqli_real_escape_string($conn, $_POST['permission_group_id']);
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            
            if($status == 'Inactive')
                $status = 'Active';
            
            if($password != ''){
                $password = password_hash($password, PASSWORD_DEFAULT);
                $password = "', password = '".$password;
            }else{
                $password = '';
            }

            $sql = "UPDATE user SET name = '".$name
            ."', surname = '".$surname
            .$password
            ."', email = '".$email
            ."', permission_group_id = '".$permission_group_id
            ."', status = '".$status
            ."', created = '".$dateTime
            ."' WHERE id = '".$id."'";

            if (mysqli_query($conn, $sql)) {
                $_SESSION['msg'] = "Record updated successfully";
            } else {
                //echo "Error updating record: " . mysqli_error($conn);
                $_SESSION['msg'] = "Error updating record";
            }
            die(header("Location: ../admin-users.php"));
        break;
        case 'deleteUser':
            //make code to make sure you cannot manage yourself
            $id = mysqli_real_escape_string($conn, $_POST['rec']);
            $status = 'Inactive';

            $sql = "UPDATE user SET status = '".$status
            ."', created = '".$dateTime
            ."' WHERE id = '".$id."'";

            if (mysqli_query($conn, $sql)) {
                $_SESSION['msg'] = "Record updated successfully";
            } else {
                //echo "Error updating record: " . mysqli_error($conn);
                $_SESSION['msg'] = "Error updating record";
            }
            die(header("Location: ../admin-users.php"));
        break;
        case 'addLeaveTitle':
            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $status = 'Active';
            $result = get_record_assoc('*', 'leave_type', '', "name LIKE '%".$name."%' ", '', false, $conn);
            if(count($result) > 0){
                $_SESSION['msg'] = "This Leave name is already added.";
                die(header("Location: ../admin-leave-type.php"));
            }else{
                $sql = "INSERT INTO leave_management_systems.leave_type (name, status, created) values ('".$name."','".$status."','". $dateTime."')";
                // echo $sql;exit;
                if (mysqli_query($conn, $sql)) {
                    $_SESSION['msg'] = "Record inserted successfully";
                } else {
                    $_SESSION['msg'] = "Error inserting data, kindly try again later";
                }
                die(header("Location: ../admin-leave-type.php"));
            }
            
        break;
        case 'updateLeaveTitle':
            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $id = is_numeric($_POST['rec']) ? $_POST['rec'] : null;
            $status = 'Active';

            if($id = '2'){
                $_SESSION['msg'] = "This Leave status Cannot be manipulated. If you want to continue Contact IT team";
                die(header("Location: ../admin-leave-type.php"));

            }

            $sql = "UPDATE leave_type SET name = '".$name."', status = '".$status."', created = '".$dateTime."' WHERE id = '".$id."'";
            if (mysqli_query($conn, $sql)) {
                $_SESSION['msg'] = "Record updated successfully";
            } else {
                //echo "Error updating record: " . mysqli_error($conn);
                $_SESSION['msg'] = "Error updating record";
            }
            die(header("Location: ../admin-leave-type.php"));
        break;
        case 'deleteLeaveTitle':
            $status = mysqli_real_escape_string($conn, $_POST['status']);
            if(mysqli_real_escape_string($conn, $_POST['parameter']) == 'statusUpdate'){
                if($status == 'Active')
                    $status = 'Inactive';
                else if($status == 'Inactive')
                    $status = 'Active';
            }else{
                $status = 'Inactive';
            }
    
            
            $id = is_numeric($_POST['rec']) ? $_POST['rec'] : null;
            if($id = '2'){
                $_SESSION['msg'] = "This Leave status Cannot be manipulated. If you want to continue Contact IT team";
                die(header("Location: ../admin-leave-type.php"));
            }
            $sql = "UPDATE leave_type SET status = '".$status."', created = '".$dateTime."' WHERE id = '".$id."'";
            if (mysqli_query($conn, $sql)) {
                $_SESSION['msg'] = "Record updated successfully";
            } else {
                $_SESSION['msg'] = "Error updating data, kindly try again later";
            }
            die(header("Location: ../admin-leave-type.php"));
        break;
        
        default:
        break;
    }

    function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;
        if ($max < 1) {
            throw new Exception('$keyspace must be at least two characters long');
        }
        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[random_int(0, $max)];
        }
        return $str;
    }    
    
    function get_record_assoc($select, $tableName, $join = '', $where = '', $orderBy = '', $json = false, $conn){
        try{
            if(strlen($where))
                $where = ' WHERE ' . $where;

            if(strlen($orderBy))
                $orderBy = ' Order by ' . $orderBy;

            $query = "SELECT $select FROM $tableName $join $where $orderBy";
            if(!$result = mysqli_query($conn, $query))
            {
                echo("Error description: " . mysqli_error($conn));
            };

            $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
            
            // echo 'number ' . mysqli_num_rows($result);exit;
            
            if($json == true){
                return json_encode($data);
            }else{
                return $data;
            }
        }
        catch (exception $e)
        {
            $errorMessage = "A Error was encountered while selecting from Database. Error = " .  $e->getMessage();

            $file = '../error_log.txt';
            file_put_contents($file, $errorMessage, FILE_APPEND | LOCK_EX);
            
            return false;
        }
    }

?>