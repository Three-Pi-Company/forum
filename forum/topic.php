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
    <script src="jquery-3.3.1.js" type="text/javascript"></script>
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
            <a href="forum.php?category=<?php echo $category; ?>"><div class="nav-back"></div></a>
            <a href="../main.php"><div class="nav-home"></div></a>
            <a class="button-logout" href="../logout.php" data-translatekey="logout">Logout</a>
        </nav>
    </header>
    <main>
        <form class="topic-form" name="topic-form">
            <input data-translatekey="topic_form_title" data-translateattribute="placeholder" placeholder="Title" type="text" maxlength="30" name="title">
            <textarea data-translatekey="topic_form_desc" data-translateattribute="placeholder" placeholder="Description" maxlength="255" name="desc"></textarea>
            <p style="color: red; letter-spacing: 2px;"id="topic-error"></p>
            <button data-translatekey="topic_form_button_create" onclick="sendtopic()" type="button">Create</button>
        </form>
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
        <div data-translatekey="signature" class="signature" >Created by Three Pi Team<br>&copy;2023 Copyright</div>
        <div class="social-media">
            <a href="https://discord.com/invite/4sDBNYPWcC"><img width="32px" height="32px" src="../img/social-media/discord.png"></a>
            <a href="https://www.facebook.com/Official.Three.Pi/"><img width="32px" height="32px" src="../img/social-media/facebook.png"></a>
            <a href="https://github.com/Three-Pi-Company/forum"><img width="32px" height="32px" src="../img/social-media/github.png"></a>
            <a href="https://twitter.com/The_Three_Pi"><img width="32px" height="32px" src="../img/social-media/twitter.png"></a>
        </div>
        <!-- <div class="sound"></div> -->
        <div class="lang"></div>
    </footer>
</body> 
</html>
<script src="../script.js"></script>
<script>
    function sendtopic(){ 
        var title = document.forms['topic-form']['title'];
        var desc = document.forms['topic-form']['desc'];
        var category = '<?php echo $category;?>';
        var topic_error = document.getElementById('topic-error');
            
        if(title.value.length > 30 || desc.value.length > 255){
            if(readCookies('lang') == "en"){
                topic_error.innerHTML = "Too long data!";
            } else{
                topic_error.innerHTML = "Za dużo danych!"; 
            }
        } else if(title.value == "" || desc.value == ""){
            if(readCookies('lang') == "en"){
                topic_error.innerHTML = "Form is not complete!";
            } else{
                 topic_error.innerHTML = "Formularz jest niewypełniony!"; 
            } 
        } else if(title.value != "" && desc.value != ""){
            topic_error.innerHTML = "";
            $.ajax({
                url: 'create.php',
                type: 'post',
                data: {title:title.value,desc:desc.value,category:category},
                dataType: 'json',
                success: function(data){
                    var status = data['status'];

                    if(status == "pass"){
                        title.value = "";
                        desc.value = "";
                        window.location.href = 'forum.php?category=<?php echo $category;?>';
                    } else if(status == "exist" ){
                        if(readCookies('lang') == "en"){
                            topic_error.innerHTML = "That title already exist!";
                        } else{
                            topic_error.innerHTML = "Ten tytuł już istnieje!"; 
                        } 
                    } else if(status == "fail" || "undefined"){
                        if(readCookies('lang') == "en"){
                            topic_error.innerHTML = "Ops, something wrong!";
                        } else{
                            topic_error.innerHTML = "Ups, zepsuło się!";
                        } 
                    }
                }
            });
        }
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
</script>