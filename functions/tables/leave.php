<?php

    //Session Data
    include("../connection.php");
    include('../session_data.php');
    set_time_limit(0);

    $loggedInUser = '';
    if(isset($_SESSION['user']['id']))
        $loggedInUser = $_SESSION['user']['id'];

    $permission_group_name = $_SESSION['user']['permission_group_name'];
  
    $dataDraw = (int)($_POST['draw']);
    $dataStart = (int)($_POST['start']);
    $dataLength = (int)($_POST['length']);
    $dataSearch = $_POST['search']['value'];
    $orderColumn = (int)($_POST['order'][0]['column']);
    $orderDirection = $_POST['order'][0]['dir'];
    $columnName = $_POST['columns'][$orderColumn]["name"];

    $data = array();

    $recordsTotal = 0;
    $r = 0;

    $rows = get_records($dataSearch, $columnName, $orderDirection, $dataLength, $dataStart,$conn, false);
    foreach ($rows AS $row) {
        $permission_group_name = $_SESSION['user']['permission_group_name'];
        $actions = " ";

        if($permission_group_name == 'Administrator'){
            $actions = "<a class='exampleModal2' data-toggle='modal' data-target='#exampleModal2' 
                            data-whatever='@mdo' rec='$row[id]' data-name='$row[name]' data-surname='$row[surname]' data-email='$row[email]' 
                            data-user-comments='$row[user_comment_convert]' data-manager-comments='$row[manager_comments_convert]' 
                            data-status='$row[status]' data-created='$row[start_date]' data-updated='$row[end_date]' 
                            data-no-of-days='$row[no_of_days]'><span class='glyphicon glyphicon-pencil'></span></a>

                        ";
                            
            $data['data'][$r]['rowID'] = $r;
            $data['data'][$r]['rec'] = $row['id'];
            $data['data'][$r]['name'] = utf8_encode(ucwords($row['name'].' '.$row['surname']));
            $data['data'][$r]['email'] = $row['email'];
            $data['data'][$r]['user_comments'] = $row['user_comment_convert'];
            $data['data'][$r]['manager_comments'] = $row['manager_comments_convert'];
            $data['data'][$r]['email'] = $row['email'];
            $data['data'][$r]['status'] = $row['status'];
            $data['data'][$r]['created'] = $row['start_date'];
            $data['data'][$r]['updated'] = $row['end_date'];
            $data['data'][$r]['no_of_days'] = $row['no_of_days'];
            $data['data'][$r]['actions'] = $actions;

        }else{
    
            $data['data'][$r]['rowID'] = $r;
            $data['data'][$r]['rec'] = $row['id'];
            $data['data'][$r]['name'] = utf8_encode(ucwords($row['name'].' '.$row['surname']));
            $data['data'][$r]['email'] = $row['email'];
            $data['data'][$r]['user_comments'] = $row['user_comment_convert'];
            $data['data'][$r]['manager_comments'] = $row['manager_comments_convert'];
            $data['data'][$r]['email'] = $row['email'];
            $data['data'][$r]['status'] = $row['status'];
            $data['data'][$r]['created'] = $row['start_date'];
            $data['data'][$r]['updated'] = $row['end_date'];
            $data['data'][$r]['no_of_days'] = $row['no_of_days'];
            $data['data'][$r]['actions'] = $actions;
                    
        }

        ++$r;

    };

    $rows = get_records($dataSearch, $columnName, $orderDirection, $dataLength, $dataStart,$conn, true);

    $recordsTotal = count($rows);

    if($recordsTotal == 0){
        $data['data'] = [];
    };
    // $data['draw'] = $dataDraw;
    $data['recordsFiltered'] = $recordsTotal;
    $data['recordsTotal'] = $recordsTotal;
    // $data['selectedGrid'] = $selectedGrid;
    die(json_encode($data));

    function get_records($dataSearch, $columnName, $orderDirection, $dataLength, $dataStart,$conn, $limit = false){
        $permission_group_name = $_SESSION['user']['permission_group_name'];
        $loggedInUser = $_SESSION['user']['id'];

        if($permission_group_name == 'Administrator'){
             $user_sql_resultset = "SELECT `leave`.*, user.name,user.surname,user.email, CONVERT( `leave`.`user_comments` using utf8) AS user_comment_convert, 
                                        CONVERT( `leave`.`manager_comments` using utf8) AS manager_comments_convert
                                FROM leave_management_systems.`leave`
                                INNER JOIN leave_management_systems.user ON `leave`.user_id = user.id ";
            
            if($dataSearch != ''){
                $user_sql_resultset .= "WHERE user.name LIKE '%".$dataSearch."%' ";
                $user_sql_resultset .= "OR user.surname LIKE '%".$dataSearch."%' ";
                $user_sql_resultset .= "OR user.email LIKE '%".$dataSearch."%' ";
            };

        }elseif($permission_group_name == 'Employee'){

            $user_sql_resultset = "SELECT `leave`.*, user.name,user.surname,user.email, CONVERT( `leave`.`user_comments` using utf8) AS user_comment_convert, 
                                            CONVERT( `leave`.`manager_comments` using utf8) AS manager_comments_convert
                                    FROM leave_management_systems.`leave`
                                    INNER JOIN leave_management_systems.user ON `leave`.user_id = user.id 
                                    WHERE user.id = $loggedInUser ";

            if($dataSearch != ''){
                $user_sql_resultset .= "OR user.name LIKE '%".$dataSearch."%' ";
                $user_sql_resultset .= "OR user.surname LIKE '%".$dataSearch."%' ";
                $user_sql_resultset .= "OR user.email LIKE '%".$dataSearch."%' ";
            };

        }
       
        $user_stmt_resultset = mysqli_query($conn, $user_sql_resultset);

        if (!$user_stmt_resultset) {
            die('Query error: ' . mysqli_error($conn));
        }

        return $rows = mysqli_fetch_all($user_stmt_resultset, MYSQLI_ASSOC);

    }

?>