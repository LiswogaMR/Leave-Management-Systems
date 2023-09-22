<?php

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
        $data['data'][$r]['name'] = $row['name'];
        $data['data'][$r]['created'] = $row['created'];
        $data['data'][$r]['status'] = $row['status'];
        ++$r;
    };

    $rows = get_records($dataSearch, $columnName, $orderDirection, $dataLength, $dataStart,$conn, true);

    $recordsTotal = count($rows);

    if($recordsTotal == 0){
        $data['data'] = [];
    };
    $data['recordsFiltered'] = $recordsTotal;
    $data['recordsTotal'] = $recordsTotal;
    
    die(json_encode($data));

    function get_records($dataSearch, $columnName, $orderDirection, $dataLength, $dataStart,$conn, $limit = false){

        $permission_group_sql_resultset = "SELECT * FROM permission_group ";

        if($dataSearch != ''){
            $permission_group_sql_resultset .= "WHERE name LIKE '%".$dataSearch."%' ";
        };
        
        $permission_group_stmt_resultset = mysqli_query($conn, $permission_group_sql_resultset);
        return $rows = mysqli_fetch_all($permission_group_stmt_resultset, MYSQLI_ASSOC);

    }

?>