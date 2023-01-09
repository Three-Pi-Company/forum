<?php

    session_start();

    if (!isset($_SESSION['logged']))
    {
        header('Location: ../sign-in.php');
        exit();
    }

    $user = $_SESSION['user'];
    $title = htmlspecialchars($_POST['title'], ENT_QUOTES, "UTF-8");
    $desc = htmlentities($_POST['desc'], ENT_QUOTES, "UTF-8");
    $category = $_POST['category'];
    $date = date('Y-m-d H:i:s');
        
    require_once "../connect.php";

    $connection = @new mysqli($host, $db_user, $db_password, $db_name);
    $status = "pass";

    if ($connection->connect_errno!=0){
        $status = "fail";
    }
    else{

        $query = @$connection->query("SELECT * FROM topics WHERE title='$title' AND category='$category';");
        $data = $query->num_rows;

        if($data>0){
            $status = "exist";
        } else{

            $result = @$connection->query("INSERT INTO topics (title, description, creator, category, creation_date) VALUES ('$title', '$desc', '$user', '$category', '$date');");
            if(!$result)
                $status = "fail";
        }

        $connection->close();
    }

    $return_arr = array("status"=>$status);
    echo json_encode($return_arr);
?>