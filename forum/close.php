<?php

    session_start();

    if (!isset($_SESSION['logged']))
    {
        header('Location: ../sign-in.php');
        exit();
    }

    
    $closeid = $_POST['close_id'];
        
    require_once "../connect.php";

    $connection = @new mysqli($host, $db_user, $db_password, $db_name);
    $status = "pass";

    if ($connection->connect_errno!=0){
        $status = "fail";
    }
    else{

        $query = @$connection->query("UPDATE topics SET title=CONCAT(title, ' 【Closed】') WHERE id='$closeid';");

        if(!$query)
            $status = "fail";

        $connection->close();
    }

    $return_arr = array("status"=>$status);
    echo json_encode($return_arr);
?>