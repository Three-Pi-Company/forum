<?php
    session_start();

    if (!isset($_SESSION['logged']) || !isset($_SESSION['user']))
    {
        header('Location: index.html');
        exit();
    }       
    require_once "connect.php";

    $connection = @new mysqli($host, $db_user, $db_password, $db_name);

    if ($connection->connect_errno!=0){
        echo "Error:" .$connection->connect_errno;
    }
    else{

        $password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $user = $_SESSION['user'];


        $result = $connection->query("SELECT password FROM users WHERE nick='$user'");
        $row = $result -> fetch_assoc();

        if(password_verify($password, $row['password'])){
            
            if($connection->query("UPDATE users SET password='$password_hash' WHERE nick='$user'")){
                unset($_SESSION['mistake-chg']); 
                header('Location: main.php');
            }
            else{
                
                echo "Error: ".$connection->error;
                
            }
            
        } else{
            $_SESSION['mistake-chg'] = '<span data-translatekey="mistake-chg_5" >Old password is not correct</span>';
            header('Location: password.php');
        }
        
        
        $connection->close();
    }
                
?>



