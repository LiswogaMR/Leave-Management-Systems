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
        $data['data'][$r]['name'] = $row['name'];
        $data['data'][$r]['status'] = $row['status'];
        $data['data'][$r]['updated'] = $row['updated'];
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

        $job_title_resultset = "SELECT * FROM leave_status ";

        if($dataSearch != ''){
            $job_title_resultset .= "WHERE name LIKE '%".$dataSearch."%' ";;
        };
        
        if(!$limit){
            $job_title_resultset .= "ORDER BY ".$columnName." ".$orderDirection."
            LIMIT ".$dataLength." OFFSET ".$dataStart;
        }    
        
        $job_title_stmt_resultset = mysqli_query($conn, $job_title_resultset);
        return $rows = mysqli_fetch_all($job_title_stmt_resultset, MYSQLI_ASSOC);

    }

?>