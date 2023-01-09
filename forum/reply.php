<?php
session_start();
require_once "../connect.php";

$user = $_SESSION['user'];
$top_id = $_POST['top_id'];
$message = htmlentities($_POST['message'], ENT_QUOTES, "UTF-8");
$date = date('Y-m-d H:i:s'); 

$connection = @new mysqli($host, $db_user, $db_password, $db_name);

if ($connection->connect_errno!=0){
    echo "Error:" .$connection->connect_errno;
}
else{
    
    $insert = @$connection->query("INSERT INTO posts VALUES (NULL, '$top_id', '$message', '$user', '$date')");
    $request = @$connection->query("SELECT max(id) AS max FROM posts");
    $assoc = $request->fetch_assoc();
    $id = $assoc['max'];

    // Get file info 
    $fileName = basename($_FILES["image"]["name"]); 
    $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
     
    // Allow certain file formats 
    $allowTypes = array('jpg','png','jpeg','gif'); 
    if(in_array($fileType, $allowTypes)){ 
         
        $image = $_FILES['image']['tmp_name']; 
        $imgContent = addslashes(file_get_contents($image)); 
     
        // Insert image content into database 
        $image_insert = @$connection->query("INSERT into images (image, post_id) VALUES ('$imgContent', '$id');"); 
    }


    $connection->close();
}

$return_arr = array("post_id"=>$id);
echo json_encode($return_arr);

?>



  
  