<?php


session_start();
require_once "../connect.php";

$user = $_SESSION['user'];
$post_id = $_POST['post_id'];
$type = $_POST['type'];

$connection = @new mysqli($host, $db_user, $db_password, $db_name);

if ($connection->connect_errno!=0){
    echo "Error:" .$connection->connect_errno;
}
else{

    $result = @$connection->query("SELECT COUNT(*) AS cntpost FROM likes WHERE post_id='$post_id' and user='$user'");
    $fetchdata = $result->fetch_assoc();
    $count = $fetchdata['cntpost'];


    if($count == 0){
        $insertquery = @$connection->query("INSERT INTO likes(user,post_id,type) values('$user','$post_id','$type')");
    }else {
        $updatequery = @$connection->query("UPDATE likes SET type='$type' where user='$user' and post_id='$post_id'");
    }


    // count numbers of like and unlike in post
    $result = @$connection->query("SELECT COUNT(*) AS cntLike FROM likes WHERE type=1 and post_id='$post_id'");
    $fetchlikes = $result->fetch_assoc();
    $totalLikes = $fetchlikes['cntLike'];

    $result = @$connection->query("SELECT COUNT(*) AS cntUnlike FROM likes WHERE type=0 and post_id='$post_id'");
    $fetchunlikes = $result->fetch_assoc();
    $totalUnlikes = $fetchunlikes['cntUnlike'];


    $return_arr = array("likes"=>$totalLikes,"unlikes"=>$totalUnlikes);

    echo json_encode($return_arr);

}
?>