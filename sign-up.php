<?php
    session_start();
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
            <h2 data-translatekey="register_form">Register Form</h2>
            <form method="POST" action="register.php" onsubmit="return Validate()" name="sign-up-form" class="sign-form">
                <input data-translatekey="sign-up-form-nickname" data-translateattribute="placeholder" type="text" name="nickname" placeholder="Type Nickname" autocomplete="off">
                    <div class="form-text" id="nickname_attention"></div>
                <input data-translatekey="sign-up-form-email" data-translateattribute="placeholder" type="text" name="email" placeholder="Type E-mail" autocomplete="off">
                    <div class="form-text" id="email_attention"></div>
                <input data-translatekey="sign-up-form-password" data-translateattribute="placeholder" type="password" name="password" placeholder="Type Password" autocomplete="off"> 
                    <div class="form-text" id="password_attention"></div>
                <input data-translatekey="sign-up-form-password-again" data-translateattribute="placeholder" type="password" name="password_confirm" placeholder="Type Password Again" autocomplete="off">
                    <div class="form-text" id="password_match_attention"></div>
                <span class="checkbox"><label style="cursor: pointer;"><input name="checkbox" type="checkbox"/><span data-translatekey="sign-up-form-checkbox">Agree to our terms of service</span></label></span>
                    <div class="form-text" id="check_attention"></div>
                <input data-translatekey="sign-up-form-button-sign-up" data-translateattribute="value" type="submit" name="submit" value="Sign up">   
            </form>
            <span data-translatekey="google" class="google">
                This page is protected by Google reCAPTCHA to ensure you're not a&nbsp;bot.
            </span>
            <span class="db_error">
                <?php
                    if(isset($_SESSION['mistake-reg'])) echo $_SESSION['mistake-reg'];
                ?>
            </span>
            <span class="user">
                <span data-translatekey="Do_you_have_an_account?">Do you have an account?</span><br> <a href="sign-in.php"><span data-translatekey="Login_to_your_account" >Login to your account</span></a>
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
    var nickname = document.forms['sign-up-form']['nickname'];
    var email = document.forms['sign-up-form']['email'];
    var password = document.forms['sign-up-form']['password'];
    var password_confirm = document.forms['sign-up-form']['password_confirm'];
    var checkbox = document.forms['sign-up-form']['checkbox'];


    var nickname_attention = document.getElementById('nickname_attention');
    var email_attention = document.getElementById('email_attention');
    var password_attention = document.getElementById('password_attention');
    var check_attention = document.getElementById('check_attention');

        
    nickname.addEventListener('blur', Validate, true);
    email.addEventListener('blur', Validate, true);
    password.addEventListener('blur', Validate, true);
    password_confirm.addEventListener('blur', Validate, true);
    checkbox.addEventListener('blur', Validate, true);

        
    function Validate() {

        if (nickname.value == "") {
            nickname.style.boxShadow = "0 0 10px 8px  rgba(255,255,255,255.75)";
            nickname.style.borderRadius = "18px"; 
            if(readCookies('lang') == "en"){
                nickname_attention.innerHTML = "Nickname is required";
            } else{
                nickname_attention.innerHTML = "Nazwa jest wymagana";  
            }
            return false;
        }
        else{
            nickname.style.boxShadow = "";
            nickname.style.borderRadius = "";
            nickname_attention.innerHTML = "";
        }

        if(nickname.value.length < 4){
            nickname.style.boxShadow = "0 0 10px 8px  rgba(255,255,255,255.75)";
            nickname.style.borderRadius = "18px"; 
            if(readCookies('lang') == "en"){
                nickname_attention.innerHTML = "The username need to be longer then 3 letters";
            } else{
                nickname_attention.innerHTML = "Nazwa użytkownika musi zawierać więcej niż 3 litery";  
            }
            return false;
        }
        else{
            nickname.style.boxShadow = "";
            nickname.style.borderRadius = ""; 
            nickname_attention.innerHTML = "";   
        }
        if(nickname.value.length >= 12){
            nickname.style.boxShadow = "0 0 10px 8px  rgba(255,255,255,255.75)";
            nickname.style.borderRadius = "18px"; 
            if(readCookies('lang') == "en"){
                nickname_attention.innerHTML = "The username need to be shorter then 12 letters";
            } else{
                nickname_attention.innerHTML = "Nazwa użytkownika musi być krótsza od 12 liter";  
            }
            return false;
        }
        else{
            nickname.style.boxShadow = "";
            nickname.style.borderRadius = ""; 
            nickname_attention.innerHTML = "";   
        }
        if(nickname.value.match(/[^a-zA-Z\d]/g)){
            nickname.style.boxShadow = "0 0 10px 8px  rgba(255,255,255,255.75)";
            nickname.style.borderRadius = "18px"; 
            if(readCookies('lang') == "en"){
                nickname_attention.innerHTML = "The username cannot contain of special characters";
            } else{
                nickname_attention.innerHTML = "Nazwa użytkonika nie może zawierać znaków specjalnych!";  
            }
            return false;
        }
        else{
            nickname.style.boxShadow = "";
            nickname.style.borderRadius = ""; 
            nickname_attention.innerHTML = "";   
        }
        
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
                email_attention.innerHTML = "Ten adres e-mail nie istnieje";
            }
            return false;
        }

        if (password.value == "") {
            password.style.boxShadow = "0 0 10px 8px  rgba(255,255,255,255.75)";
            password.style.borderRadius = "18px";
            password_confirm.style.boxShadow = "0 0 10px 8px  rgba(255,255,255,255.75)";
            password_confirm.style.borderRadius = "18px";
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
            password_confirm.style.boxShadow = "";
            password_confirm.style.borderRadius = "";
            password_attention.innerHTML = "";
        }

        if(password.value.match(/[a-z]/g) && password.value.match(/[A-Z]/g) && password.value.match(/[0-9]/g) && password.value.length >= 8){
            password_attention.innerHTML = ""; 
        }
        else{
            if(readCookies('lang') == "en"){
                password_attention.innerHTML = "Use 8 or more characters with a mix of upper, lower case letters & numbers";
            } else{
                password_attention.innerHTML = "Użyj co najmniej 8 znaków, które zawierają jedną dużą, małą literę i co najmniej 1 cyfrę";
            }
            return false;
        }

        if (password.value != password_confirm.value) {
            password.style.boxShadow = "0 0 10px 8px  rgba(255,255,255,255.75)";
            password.style.borderRadius = "18px";
            password_confirm.style.boxShadow = "0 0 10px 8px  rgba(255,255,255,255.75)";
            password_confirm.style.borderRadius = "18px";
            if(readCookies('lang') == "en"){
                password_match_attention.innerHTML = "Second password do not match";
            } else{
                password_match_attention.innerHTML = "Hasła nie pasują";
            }
            return false;
        }
        else{
            password.style.boxShadow = "";
            password.style.borderRadius = "";
            password_confirm.style.boxShadow = "";
            password_confirm.style.borderRadius = "";
            password_match_attention.innerHTML = "";
        }

        if(checkbox.checked){
            check_attention.innerHTML = ""; 
        }
        else{
            if(readCookies('lang') == "en"){
                check_attention.innerHTML = "You must agree our terms of service";
            } else{
                check_attention.innerHTML = "Musisz zaakceptować nasze warunki usługi";
            }
            return false;
        }
    }

</script>
</body> 
</html>