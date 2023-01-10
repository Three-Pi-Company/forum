<?php

    session_start();
    define('SECRET_KEY','6Lc0Kt4ZAAAAANvcqfcyQkadlIs7H98C1GLCBOqd');
    
    if($_POST){
    
        function getCaptcha($SecretKey){
            $Response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".SECRET_KEY."&response=".$SecretKey);
            $Return = json_decode($Response);
            return $Return;
        }

        $Return = getCaptcha($_POST['g-recaptcha-response']);
        // var_dump($Return);
        // if($Return->success == true && $Return->score > 0.5){
        if(true){
            
            require_once "connect.php";

            $connection = @new mysqli($host, $db_user, $db_password, $db_name);

            if ($connection->connect_errno!=0){
                echo "Error:" .$connection->connect_errno;
            }
            else{

                $nick = $_POST['nickname'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $password_hash = password_hash($password, PASSWORD_DEFAULT);


                $result_nick = @$connection->query("SELECT id FROM users WHERE nick='$nick'");
                $how_many_nick = $result_nick->num_rows;
                $result_email = @$connection->query("SELECT id FROM users WHERE email='$email'");
                $how_many_email = $result_email->num_rows;

                if (!$result_nick || !$result_email ) echo "Error: Bad query";
                
                else if($how_many_nick>0 && $how_many_email>0){
                    $_SESSION['mistake-reg'] = '<span data-translatekey="mistake-reg_1" >This nickname and email already exists</span>';
                    header('Location: sign-up.php');
                }
                else if($how_many_nick>0){
                    $_SESSION['mistake-reg'] = '<span data-translatekey="mistake-reg_2">This nickname already exists</span>';
                    header('Location: sign-up.php');
                }
                else if($how_many_email>0){
                    $_SESSION['mistake-reg'] = '<span data-translatekey="mistake-reg_3">This email already exists</span>';
                    header('Location: sign-up.php');
                }
                
                else{

                    if($connection->query("INSERT INTO users VALUES (NULL, '$nick', '$email', '$password_hash', default)")){
                        unset($_SESSION['mistake-reg']);
                        $_SESSION['logged'] = true;
                        $_SESSION['user'] = $nick;
                        $_SESSION['email'] = $email;
                        header('Location: main.php');
                    }
                    else{

                    echo "Error: ".$connection->error;

                    }

                }

                $connection->close();
            }


        }
        else{
            $_SESSION['mistake-reg'] = '<span data-translatekey="mistake-reg_4">You are a robot!</span>';
            header('Location: sign-up.php');
        }
    }
?>


