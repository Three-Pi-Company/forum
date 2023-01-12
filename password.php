<?php

    session_start();

    if (!isset($_SESSION['logged']) || !isset($_SESSION['user']))
    {
        header('Location: ./index.html');
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
    <link rel="icon" href="./img/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="img/favicon.ico">
    <link rel="stylesheet" href="style.css">
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
            <a href="./main.php"><div class="nav-home"></div></a>
            <a class="button-logout" href="./logout.php" data-translatekey="logout">Logout</a>
        </nav>
    </header>
    <main>
        <div class="form-container">
            <h2 data-translatekey="change_form">Password change Form</h2>
            <form method="POST" action="change.php" onsubmit="return Validate()" name="change-form" class="sign-form">
                <input data-translatekey="old-form-password" data-translateattribute="placeholder" type="password" name="old_password" placeholder="Type old password" autocomplete="off"> 
                    <div class="form-text" id="password_attention"></div>
                <input data-translatekey="new-form-password" data-translateattribute="placeholder" type="password" name="new_password" placeholder="Type new password" autocomplete="off">
                    <div class="form-text" id="password_new_attention"></div>
                <input data-translatekey="form-button-change" data-translateattribute="value" type="submit" name="submit" value="Change password">   
            </form>
            <span class="db_error">
                <?php
                    if(isset($_SESSION['mistake-chg'])) echo $_SESSION['mistake-chg'];
                ?>
            </span>
        </div>
    </main>
    <footer>
         <div data-translatekey="signature" class="signature" >Created by Three Pi Inc.<br>&copy;2020 Copyright</div>
         <div class="social-media">
            <a href="https://discord.com/invite/4sDBNYPWcC"><img width="32px" height="32px" src="img/social-media/discord.png"></a>
            <a href="https://www.facebook.com/Official.Three.Pi/"><img width="32px" height="32px" src="img/social-media/facebook.png"></a>
            <a href="https://github.com/Three-Pi-Company/Three_Pi"><img width="32px" height="32px" src="img/social-media/github.png"></a>
            <a href="https://twitter.com/The_Three_Pi"><img width="32px" height="32px" src="img/social-media/twitter.png"></a>
        </div>
        <div class="lang"></div>
    </footer>

<script src="script.js"></script>
<script>
    var password = document.forms['change-form']['old_password'];
    var new_password = document.forms['change-form']['new_password'];


    var password_attention = document.getElementById('password_attention');
    var new_password_attention = document.getElementById('password_new_attention');

        
    password.addEventListener('blur', Validate, true);
    new_password.addEventListener('blur', Validate, true);

        
    function Validate() {

        if (password.value == "") {
            password.style.boxShadow = "0 0 10px 8px  rgba(255,255,255,255.75)";
            password.style.borderRadius = "18px";
            if(readCookies('lang') == "en"){
                password_attention.innerHTML = "Old password is required";
            } else{
                password_attention.innerHTML = "Stare hasło jest wymagane";
            }
            return false;
        }
        else{
            password.style.boxShadow = "";
            password.style.borderRadius = "";
            password_attention.innerHTML = "";
        }
        if (new_password.value == "") {
            new_password.style.boxShadow = "0 0 10px 8px  rgba(255,255,255,255.75)";
            new_password.style.borderRadius = "18px";
            if(readCookies('lang') == "en"){
                new_password_attention.innerHTML = "New password is required";
            } else{
                new_password_attention.innerHTML = "Nowe hasło jest wymagane";
            }
            return false;
        }
        else{
            new_password.style.boxShadow = "";
            new_password.style.borderRadius = "";
            new_password_attention.innerHTML = "";
        }

        if(new_password.value.match(/[a-z]/g) && new_password.value.match(/[A-Z]/g) && new_password.value.match(/[0-9]/g) && new_password.value.length >= 8){
            new_password_attention.innerHTML = ""; 
        }
        else{
            if(readCookies('lang') == "en"){
                new_password_attention.innerHTML = "Use 8 or more characters with a mix of upper, lower case letters & numbers";
            } else{
                new_password_attention.innerHTML = "Użyj co najmniej 8 znaków, które zawierają jedną dużą, małą literę i co najmniej 1 cyfrę";
            }
            return false;
        }

    }
    
</script>

</body> 
</html>
