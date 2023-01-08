<?php

    session_start();

    if ((!isset($_POST['email'])) || (!isset($_POST['password'])))
    {

        header('Location: sign-in.php');
        exit();

    }

    require_once "connect.php";

    $connection = @new mysqli($host, $db_user, $db_password, $db_name);

    if ($connection->connect_errno!=0)
    {

        echo "error:" .$connection->connect_errno;
    }
    else
    {

    $login = $_POST['email'];
    $password = $_POST['password'];

    $login = htmlentities($login, ENT_QUOTES, "UTF-8");



    if($result = @$connection->query(sprintf("SELECT * FROM users WHERE email='%s'",mysqli_real_escape_string($connection,$login))))
    {

        $how_many_users = $result->num_rows;
        if($how_many_users>0){

            $row = $result->fetch_assoc();

            if(password_verify($password, $row['password']))
            {
                $_SESSION['logged'] = true;
                $_SESSION['user'] = $row['nick'];
                if($row['user_rang'] == "Admin"){$_SESSION['rang'] = true;};
                $_SESSION['email'] = $row['email'];

                unset($_SESSION['mistake-log']);
                $result->close();
                header('Location: main.php');
            }
            else{
                $_SESSION['mistake-log'] = '<span data-translatekey="mistake-log">Incorrect login or password!</span>';
                header('Location: sign-in.php');
            }

        }   
        else{

            $_SESSION['mistake-log'] = '<span data-translatekey="mistake-log">Incorrect login or password!</span>';
            header('Location: sign-in.php');

        }

    }

    $connection->close();
    }

?>