<?php 
// Include the database configuration file  
require_once "../connect.php";


$connection = @new mysqli($host, $db_user, $db_password, $db_name);

if ($connection->connect_errno!=0){
    echo "Error:" .$connection->connect_errno;
}

// Get image data from database 
$result = $connection->query("SELECT image FROM images"); 
?>

<?php if($result->num_rows > 0){ ?> 
    <div class="gallery"> 
        <?php while($row = $result->fetch_assoc()){ ?> 
            <img width='50%' height='auto' src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($row['image']); ?>" /> 
        <?php } ?> 
    </div> 
<?php }else{ ?> 
    <p class="status error">Image(s) not found...</p> 
<?php } ?>