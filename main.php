<?php

    session_start();

    if (!isset($_SESSION['logged']))
    {
        header('Location: sign-in.php');
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
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="img/favicon.ico">
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
    <title>Three Pi</title>
</head>
<body>
<header class="main-header">
        <div class="logo">
            <a href="index.html">
                <img style=" max-width: 108px; height: auto;" alt="logo" src="img/logo.png">
                <h1>Three Pi</h1>
            </a>
        </div>
        <nav class="main-nav">
            <div class="button-container">
                <div class="nav-user"></div>
                <ul class="user-detail">
                    <li class="pass" data-translatekey="Change_password">Change password</li>
                    <li class="delete" data-translatekey="Delete_account">Delete account</li>
                </ul>
            </div>
            <a class="button-logout" href="logout.php" data-translatekey="logout">Logout</a>
        </nav>
    </header>
    <main>
        <div class="panel-container">
            <div class="panel">
                <h3><span id="hello"></span><?php echo $_SESSION['user'].'!'; ?></h3>
            </div>
            <div class="categories">
                <a href= "./forum/forum.php?category=polish"><img height='80px' width='80px' alt="polish" src="./img/category-icon/polish.png"></img></a>
                <a href= "./forum/forum.php?category=english"><img height='80px' width='80px' alt="english" src="./img/category-icon/english.png"></img></a> 
                <a href= "./forum/forum.php?category=maths"><img height='80px' width='80px' alt="maths" src="./img/category-icon/maths.png"></img></a> 
                <a href= "./forum/forum.php?category=geography"><img height='80px' width='80px' alt="gegraphy" src="./img/category-icon/geography.png"></img></a> 
                <a href= "./forum/forum.php?category=history"><img height='80px' width='80px' alt="history" src="./img/category-icon/history.png"></img></a>
                <?php 
                    if(isset($_SESSION['rang'])){
                    echo '<a href= "./forum/forum.php?category=vip"><img height="80px" width="80px" alt="vip" src="./img/category-icon/vip.png"></img></a>';
                    }
                ?>
            </div>
            <div class="clock">
                <h2 data-translatekey="thetimeisnow"> The time is now </h2>
                <div class="time">
                    <div><span id="hours"></span><span data-translatekey="hours">Hours</span></div>
                    <div><span id="minutes"></span><span data-translatekey="minutes">Minutes</span></div>
                    <div><span id="seconds"></span><span data-translatekey="seconds">Seconds</span></div>
                </div>
            </div>
            <div class="topics">
                <h2 data-translatekey="recent_topics_5">Recent 5 topics:</h2>
                <?php
                    require_once "./connect.php";

                    $connection = @new mysqli($host, $db_user, $db_password, $db_name);

                    if ($connection->connect_errno!=0){
                        echo "Error:" .$connection->connect_errno;
                    }
                    else{

                        $result = @$connection->query("SELECT title, creator, count(posts.top_id) AS replies, DATE_FORMAT(creation_date, '%a, %D %b %Y' ) AS creation, category FROM topics LEFT JOIN posts ON topics.id=posts.top_id WHERE NOT category = 'vip' GROUP BY topics.id ORDER BY coalesce(max(posts.reply_date), topics.creation_date) DESC  LIMIT 5;");
                        $num_forum = $result->num_rows;
                        if($num_forum>0){
                            while($data = $result->fetch_assoc()){
                                $dt = $data['creation'];
                            ?>
                            <a href="./forum/post.php?category=<?php echo $data['category']; ?>&title=<?php echo urlencode($data['title']); ?>">
                                <section>
                                <h3 style="margin-bottom: 20px;"><?php echo $data["title"];?></h3>
                                    <div style="display: flex;">
                                        <div>
                                            <span data-translatekey="replies">Replies:</span><?php echo $data["replies"]; ?> <br>
                                            <img style="margin-top: 10px; margin-bottom: 10px;" height="40px" width="40px" src="./img/category-icon/<?php echo $data["category"]; ?>.png" alt="category">
                                        </div>
                                        <div><span data-translatekey="created_by">Created by</span><?php echo $data["creator"]; ?><br><?php echo $dt;?></div>
                                    <div>  
                                </section>
                            </a>
                            <?php
                            }
                        } else{
                            ?>
                            <section>
                                <h3 style="margin-bottom: 20px;" data-translatekey="tanphy">There are no posts here yet.</h3>
                            </section>
                            <?php
                        }

                    
                        $connection->close();
                            
                    }
                
                ?>

                
            </div>  
        </div>
    </main>
    <footer>
        <div data-translatekey="signature" class="signature" >Created by Three Pi Team<br>&copy;2023 Copyright</div>
        <div class="social-media">
            <a href="https://discord.com/invite/4sDBNYPWcC"><img width="32px" height="32px" src="img/social-media/discord.png"></a>
            <a href="https://www.facebook.com/Official.Three.Pi/"><img width="32px" height="32px" src="img/social-media/facebook.png"></a>
            <a href="https://github.com/Three-Pi-Company/forum"><img width="32px" height="32px" src="img/social-media/github.png"></a>
            <a href="https://twitter.com/The_Three_Pi"><img width="32px" height="32px" src="img/social-media/twitter.png"></a>
         </div>
        <div class="lang"></div>
    </footer>

<script>
    window.addEventListener('DOMContentLoaded', ()=>{
        clock();
        function clock(){
            var hello = document.getElementById('hello');
            var hours = document.getElementById('hours');
            var minutes = document.getElementById('minutes');
            var seconds = document.getElementById('seconds');

            var h = new Date().getHours();
            var m = new Date().getMinutes();
            var s = new Date().getSeconds();

            h = (h < 10) ? "0" + h : h; 
            m = (m < 10) ? "0" + m : m; 
            s = (s < 10) ? "0" + s : s; 


            hours.innerHTML = h;
            minutes.innerHTML = m;
            seconds.innerHTML = s;

            if(h <= 12){
                if(readCookies('lang') == "en"){
                    hello.innerHTML = 'Good morning, '; 
                }
                else{
                    hello.innerHTML = 'Dzień dobry, ';  
                }
            }

            else if(h >= 18){
                if(readCookies('lang') == "en"){
                    hello.innerHTML = 'Good evening, '; 
                }
                else{
                    hello.innerHTML = 'Dobry wieczór, ';  
                }
            }
            else{
                if(readCookies('lang') == "en"){
                    hello.innerHTML = 'Good afternoon, '; 
                }
                else{
                    hello.innerHTML = 'Dobry dobry, ';  
                }
            }
            setTimeout(clock, 1000);
        }

        var a = 0;
        document.querySelector('.nav-user').addEventListener('click', () =>{
            if(a % 2 == 0){
                document.querySelector('.user-detail').style.display="block";
            } else{
                document.querySelector('.user-detail').style.display="none";
            }
            a++;
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
