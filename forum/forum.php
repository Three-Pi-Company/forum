<?php

    session_start();

    if (!isset($_SESSION['logged']))
    {
        header('Location: ../sign-in.php');
        exit();
    }
    
    $allow_category = array('polish', 'english', 'maths', 'geography', 'history', 'vip');
    if(!isset($_GET['category']) || !in_array($_GET['category'], $allow_category)){
        header('Location: ../main.php');
        exit();
    }
    $category = $_GET['category'];
    if(!isset($_SESSION['rang']) && $category == "vip"){
        header('Location: ../main.php');
        exit(); 
    }    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Jędrzej Pawłowski, Maciej Palenica, Piotr Postrożny">
    <meta name="robots" content="index"> 
    <meta name="google" content="notranslate">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../img/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="../img/favicon.ico">
    <link rel="stylesheet" href="../style.css">
    <title>Three Pi</title>
</head>
<body>
    <header class="main-header">
        <div class="logo">
            <a href="../index.html">
                <img style=" max-width: 108px; height: auto;" alt="logo" src="../img/logo.png">
                <h1>Three Pi</h1>
            </a>
        </div>
        <nav class="main-nav">
            <div class="button-container">
                <a href="topic.php?category=<?php echo $category;?>"><div class="nav-create"></div></a>
                <a href="../main.php"><div class="nav-home"></div></a>
                <div class="nav-user"></div>
                <ul class="user-detail">
                    <li class="pass" data-translatekey="Change_password">Change password</li>
                    <li class="delete" data-translatekey="Delete_account">Delete account</li>
                </ul>
            </div>
            <a class="button-logout" href="../logout.php" data-translatekey="logout">Logout</a>
        </nav>
    </header>
    <main>
        <div class="topics">
        <?php
            require_once "../connect.php";

            $connection = @new mysqli($host, $db_user, $db_password, $db_name);

            if ($connection->connect_errno!=0){
                echo "Error:" .$connection->connect_errno;
            }
            else{

                $result = @$connection->query("SELECT title, creator, count(posts.top_id) AS replies, DATE_FORMAT(creation_date, '%a, %D %b %Y' ) AS creation FROM topics LEFT JOIN posts ON topics.id=posts.top_id WHERE category='$category' GROUP BY topics.id ORDER BY coalesce(max(posts.reply_date), topics.creation_date) DESC;");
                $num_forum = $result->num_rows;
                echo '<img height="80px" width="80px" alt="maths" src="../img/category-icon/'.$category.'.png"></img><h2 data-translatekey="recent_topics">Recent topics:</h2>';
                if($num_forum>0){
                    $recent = 0;
                    while($data = $result->fetch_assoc()){
                        $dt = $data['creation'];
                        $recent++;
                    ?>
                    <?php if($recent == 6){ echo '<h2 data-translatekey="other_topics">Other topics:</h2>';}?>
                    <a href="post.php?category=<?php echo $category; ?>&title=<?php echo urlencode($data['title']); ?>">
                        <section>
                           <h3 style="margin-bottom: 20px;"><?php echo $data["title"];?></h3>
                            <div style="display: flex;">
                                <div><span data-translatekey="replies">Replies:</span><?php echo $data["replies"]; ?></div>
                                <div><span data-translatekey="created_by">Created by</span><?php echo $data["creator"]; ?><br><?php echo $dt;?></div>
                            <div>  
                        </section>
                    </a>
                    <?php
                    }
                } else{
                    ?>
                    <section>
                           <h3 style="margin-bottom: 20px;" data-translatekey="tanphy">There are no topics here yet.</h3>
                    </section>
                    <?php
                }

            
                $connection->close();
                    
            }
        
        ?>
        </div>
    </main>
    <footer>
        <div data-translatekey="signature" class="signature" >Created by Three Pi Team<br>&copy;2023 Copyright</div>
        <div class="social-media">
            <a href="https://discord.com/invite/4sDBNYPWcC"><img width="32px" height="32px" src="../img/social-media/discord.png"></a>
            <a href="https://www.facebook.com/Official.Three.Pi/"><img width="32px" height="32px" src="../img/social-media/facebook.png"></a>
            <a href="https://github.com/Three-Pi-Company/forum"><img width="32px" height="32px" src="../img/social-media/github.png"></a>
            <a href="https://twitter.com/The_Three_Pi"><img width="32px" height="32px" src="../img/social-media/twitter.png"></a>
        </div>
        <div class="lang"></div>
    </footer>

<script src="../script.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', ()=>{
        var a = 0;
        document.querySelector('.nav-user').addEventListener('click', () =>{
            if(a % 2 == 0){
                document.querySelector('.user-detail').style.display="block";
            } else{
                document.querySelector('.user-detail').style.display="none";
            }
            a++;
        });

        document.querySelector('.pass').addEventListener('click', ()=>{
            alert('No to też, nie bądź taki!');
        });
        document.querySelector('.delete').addEventListener('click', ()=>{
            var boolean = confirm("Are you sure?")
            if(boolean)
            window.location.pathname = '/~s13815/~forum/delete.php';
        });

    });

</script>

</body> 
</html>