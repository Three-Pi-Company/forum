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
            <a class="button-logout" href="logout.php" data-translatekey="logout">Logout</a>
        </nav>
    </header>
    <main>
        <div class="panel-container">
            <div class="panel">
                <h3><span id="hello"></span><?php echo $_SESSION['user'].'!'; ?></h3>
            </div>
            <div class="categories">
                <a href= "/forum/forum.php?category=polish"><img height='80px' width='80px' alt="polish" src="../img/category-icon/polish.png"></img></a>
                <a href= "/forum/forum.php?category=english"><img height='80px' width='80px' alt="english" src="../img/category-icon/english.png"></img></a> 
                <a href= "/forum/forum.php?category=maths"><img height='80px' width='80px' alt="maths" src="../img/category-icon/maths.png"></img></a> 
                <a href= "/forum/forum.php?category=geography"><img height='80px' width='80px' alt="gegraphy" src="../img/category-icon/geography.png"></img></a> 
                <a href= "/forum/forum.php?category=history"><img height='80px' width='80px' alt="history" src="../img/category-icon/history.png"></img></a>
                <?php 
                    if(isset($_SESSION['rang'])){
                    echo '<a href= "/forum/forum.php?category=vip"><img height="80px" width="80px" alt="vip" src="../img/category-icon/vip.png"></img></a>';
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
        </div>
    </main>
    <footer>
        <div id="fb-root"></div>
        <!-- Your Chat Plugin code -->
        <div class="fb-customerchat"
            attribution=setup_tool
            page_id="113342123861721"
            theme_color="#132F40"
            logged_in_greeting="Witaj na stronie Three Pi. Masz do nas jakieś pytania? Napisz do nas teraz !"
            logged_out_greeting="Witaj na stronie Three Pi. Masz do nas jakieś pytania? Napisz do nas teraz !"
            greeting_dialog_display="hide">
        </div>
        <div data-translatekey="signature" class="signature" >Created by Three Pi Inc.<br>&copy;2020 Copyright</div>
        <div class="social-media">
            <a href="https://discord.com/invite/4sDBNYPWcC"><img width="32px" height="32px" src="img/social-media/discord.png"></a>
            <a href="https://www.facebook.com/Official.Three.Pi/"><img width="32px" height="32px" src="img/social-media/facebook.png"></a>
            <a href="https://github.com/Three-Pi-Company/Three_Pi"><img width="32px" height="32px" src="img/social-media/github.png"></a>
            <a href="https://twitter.com/The_Three_Pi"><img width="32px" height="32px" src="img/social-media/twitter.png"></a>
         </div>
        <!-- <div class="sound"></div> -->
        <div class="lang"></div>
    </footer>
</body> 
</html>
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

        // Load Facebook SDK for JavaScript
        window.fbAsyncInit = function() {
            FB.init({
                xfbml: true,
                version: 'v8.0'
            });
        };
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = 'https://connect.facebook.net/pl_PL/sdk/xfbml.customerchat.js';
            fjs.parentNode.insertBefore(js, fjs);
        }
        (document, 'script', 'facebook-jssdk'));
    });
       
</script>
