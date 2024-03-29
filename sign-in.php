<?php
    session_start();

    if((isset($_SESSION['logged'])) && ($_SESSION['logged']==true))
    {

        header('Location: main.php');
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
    </header>
    <main>
        <div class="form-container">
            <h2 data-translatekey="login_form">Login Form</h2>
            <form method="POST" action="login.php" onsubmit="return Validate()" name="sign-in-form" class="sign-form"> 
                <input data-translatekey="sign-in-form-email" data-translateattribute="placeholder" type="text" name="email" placeholder="Type E-mail">
                    <div class="form-text" id="email_attention"></div>
                <input data-translatekey="sign-in-form-password" data-translateattribute="placeholder" type="password" name="password" placeholder="Type Password" autocomplete="off"> 
                    <div class="form-text" id="password_attention"></div>
                <input data-translatekey="sign-in-form-button-sign-in" data-translateattribute="value" type="submit" name="submit" value="Sign in">     
            </form>
            <span class="user"><span data-translatekey="You_don't_have_an_account_yet?">You don't have an account yet?</span><br> <a href="sign-up.php"><span data-translatekey="Create_an_account">Create an account</span></a></span>
            <span class="db_error">
           <?php
                    if(isset($_SESSION['mistake-log'])) echo $_SESSION['mistake-log'];
            ?>
            </span>
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

<script src="script.js"></script>
<script>
    var email = document.forms['sign-in-form']['email'];
    var password = document.forms['sign-in-form']['password'];

    var email_attention = document.getElementById('email_attention');
    var password_attention = document.getElementById('password_attention');

        
    email.addEventListener('blur', Validate, true);
    password.addEventListener('blur', Validate, true);

        
    function Validate() {

        if (email.value == "") {
            email.style.boxShadow = "0 0 10px 8px  rgba(255,255,255,255.75)";
            email.style.borderRadius = "18px";
            if(readCookies('lang') == "en"){
                email_attention.innerHTML = "E-mail is required";
            } else{
                email_attention.innerHTML = "Adres e-mail jest wymagany";  
            }
            return false;
        }
        else{
            email.style.boxShadow = "";
            email.style.borderRadius = "";
            email_attention.innerHTML = "";
        }
        
        if(email.value.match(/[^@]+@[^\.]+\..+/g)){
            email_attention.innerHTML = "";
        }
        else{
            if(readCookies('lang') == "en"){
                email_attention.innerHTML = "E-mail is not exist";
            } else{
                email_attention.innerHTML = "Adres e-mail nie istnieje";  
            }
            return false;
        }

        if (password.value == "") {
            password.style.boxShadow = "0 0 10px 8px  rgba(255,255,255,255.75)";
            password.style.borderRadius = "18px";
            if(readCookies('lang') == "en"){
                password_attention.innerHTML = "Password is required";
            } else{
                password_attention.innerHTML = "Hasło jest wymagane";
            }
            return false;
        }
        else{
            password.style.boxShadow = "";
            password.style.borderRadius = "";
            password_attention.innerHTML = "";
        }
    }
</script>
</body> 
</html>
    