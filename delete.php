<?php

session_start();

require_once "connect.php";

$connection = @new mysqli($host, $db_user, $db_password, $db_name);

if ($connection->connect_errno!=0)
{

    echo "error:" .$connection->connect_errno;
}
else
{

$user = $_SESSION['user'];

if($result = @$connection->query("DELETE FROM users WHERE nick='$user';")){
    
    header('Location: logout.php');
        
}   
$connection->close();
}
?>