<?php

    //Session Data
    include("../connection.php");
    include('../session_data.php');
    set_time_limit(0);

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
        $data['data'][$r]['rowID'] = $r;
        $data['data'][$r]['rec'] = $row['id'];
        $data['data'][$r]['name'] = utf8_encode(ucwords($row['name'].' '.$row['surname']));
        $data['data'][$r]['email'] = $row['email'];
        $data['data'][$r]['status'] = $row['status'];
        $data['data'][$r]['permission_group_id'] = $row['permission_group_id'];
        $data['data'][$r]['permission_group_name'] = $row['permission_group_name'];
        $data['data'][$r]['created'] = $row['created'];

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

        $user_sql_resultset = "SELECT user.id, user.name, user.surname, user.email, user.status, permission_group.id AS permission_group_id, permission_group.name AS permission_group_name,user.created 
                                FROM leave_management_systems.user 
                                LEFT JOIN leave_management_systems.permission_group on permission_group.id = user.permission_group_id ";
                                        
        if($dataSearch != ''){
            $user_sql_resultset .= "WHERE user.name LIKE '%".$dataSearch."%' ";
            $user_sql_resultset .= "OR user.surname LIKE '%".$dataSearch."%' ";
            $user_sql_resultset .= "OR user.email LIKE '%".$dataSearch."%' ";
        };
        
        $user_stmt_resultset = mysqli_query($conn, $user_sql_resultset);
        if (!$user_stmt_resultset) {
            die('Query error: ' . mysqli_error($conn));
        }
        return $rows = mysqli_fetch_all($user_stmt_resultset, MYSQLI_ASSOC);

    }

?>