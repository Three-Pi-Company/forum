 <?php

    session_start();

    if (!isset($_SESSION['logged']))
    {
        header('Location: ../sign-in.php');
        exit();
    }
    
    $title = urldecode($_GET["title"]);
    $category = $_GET["category"];

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
    <?php    
        require_once "../connect.php";
        $connection = @new mysqli($host, $db_user, $db_password, $db_name);

        if ($connection->connect_errno!=0){
            echo "Error:" .$connection->connect_errno;
        }
        else{
            $user = $_SESSION['user'];
                    
            $result = @$connection->query("SELECT * FROM topics WHERE title='$title' AND category='$category';");
            $result_num = $result->num_rows;
            $data = $result->fetch_assoc();
                    
            if($result_num == 0){
                header('Location: forum.php?category='.$category);
            } else{
                $top_id = $data['id'];
                $creator = $data['creator'];
                $desc = $data['description'];
                    
                $result_reply = @$connection->query("SELECT id, message, author, DATE_FORMAT(reply_date, '%h:%i %p' ) AS hours, DATE_FORMAT(reply_date, '%a, %D %b %Y' ) AS days FROM posts WHERE top_id='$data[id]' ORDER BY id;");
                $replies = $result_reply->num_rows;
    ?>
    <div class="container">
        <div class="topic-title">
            <h2><?php echo $title;?></h2>
        </div>
        <div class="topic-details">
            <?php
                echo '<span class="text">'.$desc.'</span><p style="text-align: right;"><span data-translatekey="created_by">Created by</span>'.$creator.'</p>';
                if($_SESSION['user'] == $creator){echo "<span class='close' id='close_$top_id'><span data-translatekey='close' class='close1'>Close </span><span data-translatekey='topic' class='close2'>topic</span></span>";}
            ?>
        </div>
        <div class="posts">
    <?php        
                while( $posts = $result_reply->fetch_assoc()){
                            
                    $post_id = $posts['id'];
                    $message = $posts['message'];
                    $author = $posts['author'];
                    $dt = $posts['days'];
                    $h = $posts['hours'];
                    
                    $type = -1;

                    //user's likes/unlikes

                    $status_like = @$connection->query("SELECT type FROM likes WHERE user='$user' AND post_id='$post_id'");
                    $likes_row = $status_like->num_rows;
                    $likes_table = $status_like->fetch_assoc();
                    if($likes_row > 0){
                        $type = $likes_table['type'];
                    }

                    //count likes
                    $like_query = @$connection->query("SELECT COUNT(*) AS cntlikes FROM likes WHERE type='1' AND post_id='$post_id'");
                    $table_like = $like_query->fetch_assoc();
                    $total_Likes = $table_like['cntlikes'];
                            
                    //count unlikes
                    $unlike_query = @$connection->query("SELECT COUNT(*) AS cntunlikes FROM likes WHERE type='0' AND post_id='$post_id'");
                    $table_unlike = $unlike_query->fetch_assoc();
                    $total_Unlikes = $table_unlike['cntunlikes'];
    ?>
            <section>
                <div style="display:flex;">
                    <div class="post" id="post_<?php echo $post_id;?>" style="flex-basis: 70%;"><?php echo '<div id="text_'.$post_id.'">'.$message.'</div>' ;?>
                    
                    <?php $result = $connection->query("SELECT image FROM images WHERE post_id='$post_id';"); 
            
                        if($result->num_rows > 0){ ?> 
                            <div class="gallery"> 
                            <?php while($row = $result->fetch_assoc()){ ?> 
                                <img width='50%' height='auto' src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($row['image']); ?>" /> 
                            <?php } ?> 
                            </div> 
                        <?php } ?>
                    <?php echo '<div><sub><span data-translatekey="by">wrote by:</span><span id="author_'.$post_id.'">'.$author.'</span></sub></div>'; ?>
                    </div>
                    <div style="text-align: center; margin-left:auto; flex-basis: 25%;"><?php echo $dt.', <br>'.$h; ?></div>
                    <img src="../img/quote.png" style=" cursor: pointer; width: 32px; height: 32px;" class="quote" id="quote_<?php echo $post_id;?>"></img>
                </div>
                <div class="post-action">
                    <input type="button" id="like_<?php echo $post_id; ?>" class="like" style="<?php if($type == 1){ echo "background-color: green;"; } ?>" />&nbsp;(<span id="likes_<?php echo $post_id; ?>"><?php echo $total_Likes; ?></span>)&nbsp;
                    <input type="button" id="unlike_<?php echo $post_id; ?>" class="unlike" style="<?php if($type == 0){ echo "background-color: red;"; } ?>" />&nbsp;(<span id="unlikes_<?php echo $post_id; ?>"><?php echo $total_Unlikes; ?></span>)
                </div>
            </section>

                            
    <?php
                } 
                $connection->close();  
            }                    
        }
        if($replies == 0){
            echo "<section id='noreplies' style='text-align: center;'><span data-translatekey='no_answers'>No answers</span></section>";
        }
    ?>
            <form id="reply-form" class="reply-form" name="reply-form" enctype="multipart/form-data">
                <textarea maxlength="255" name="message"></textarea>
                <span style="color: red;" class="empty-error"></span>
                <input type="file" value="Dodaj zdjęcie" name="image">
                <button data-translatekey="reply_form_button_reply" onclick="sendpost();" type="button">Reply</button>
            </form>
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
    var content = document.querySelector('.topic-title').getElementsByTagName('h2')[0];
    if(content.innerText.includes('【Closed】')){
        document.forms['reply-form'].style.display = 'none';
        document.querySelector('.close').remove();
    }
    if(document.contains(document.querySelector('.close'))){
        document.querySelector('.close').addEventListener('click', function(e) {
            var boolean = confirm("Are you sure?")
            if(boolean){
                var id = e.target.id;
                var split_id = id.split("_");
                var close_id = split_id[1];

                $.ajax({
                        url: 'close.php',
                        type: 'post',
                        data: {close_id:close_id},
                        dataType: 'json',
                        success: function(data){
                            var status = data['status'];
                            if(status == 'pass'){
                                content.innerText += " 【Closed】";
                                document.forms['reply-form'].style.display = 'none';
                                document.querySelector('.close').remove();
                            }
                        }
                });
            }
        });
    }

    function sendpost(){ 
        var message = document.forms['reply-form']['message'];
        var file = document.forms['reply-form']['image'];
        var form = document.forms['reply-form'];
        var dataform = new FormData(form);
        var top_id = '<?php echo $top_id;?>';
        dataform.append("top_id", top_id);
        var empty = document.querySelector('.empty-error');
        var value = message.value;
        var m = value.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/\n/g, '&#10;');
        console.log(m);
            
        if(message.value.length > 255){
            if(readCookies('lang') == "en"){
                empty.innerHTML = "Too long data!";
            } else{
                empty.innerHTML = "Za dużo danych";  
            }
        } else if(message.value == ""){
            if(readCookies('lang') == "en"){
                empty.innerHTML = "Message is empty!";
            } else{
                empty.innerHTML = "Wiadomośc jest pusta!"; 
            }
        }
        else if(message.value != ""){
            empty.innerHTML = "";
            $.ajax({
                url: 'reply.php',
                type: 'post',
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                cache: false,
                data: dataform,
                dataType: 'json',
                success: function(data){
                message.value = "";
                file.value = "";
                var post_id = data['post_id'];
                if (document.contains(document.querySelector("#noreplies"))) {
                    document.querySelector("#noreplies").remove();
                }
                    
                var section = document.createElement( 'section' );
                section.innerHTML = "<span style='display: flex;'><span style='flex-basis: 70%;'>" + m + "<br><sub>by <?php echo $user;?></sub><br><br></span><span style='text-align: center; margin-left:auto; flex-basis: 25%;'>just now</span></span><div class='post-action'><input type='button' id='like_" + post_id + "' class='like'  />&nbsp;(<span id='likes_" + post_id + "'>0</span>)&nbsp; <input type='button' id='unlike_" + post_id + "' class='unlike'  />&nbsp;(<span id='unlikes_" + post_id + "'>0</span>)</div>";
                    
                var container = document.querySelector('.posts');
                var form = document.forms['reply-form'];
                container.insertBefore(section, form);
                }
            });
        } 
    }



    //quote someone
    document.querySelector('.posts').addEventListener('click', function(e) {
        if(e.target.className == 'quote'){
            var id = e.target.id;
            var split_id = id.split("_");
            var quote_id = split_id[1];

            let message = document.querySelector("#text_" + quote_id).textContent;
            let author = document.querySelector("#author_" + quote_id).textContent;

            let quote = '/// ' + author + ' wrote: ' + message + ' /// \n ';
            document.forms["reply-form"]["message"].value = quote;
            document.forms["reply-form"]["message"].scrollIntoView();
            document.forms["reply-form"]["message"].focus();

        }
        // like and unlike click
        if(e.target.className == 'like' || e.target.className == 'unlike'){

            var id = e.target.id;   // Getting Button id
            var split_id = id.split("_");

            var text = split_id[0];
            var post_id = split_id[1];  // postid

            // Finding click type
            var type = 0;
            if(text == "like"){
                type = 1;
            }else{
                type = 0;
            }

            // AJAX Request
            $.ajax({
                url: 'like.php',
                type: 'post',
                data: {post_id:post_id,type:type},
                dataType: 'json',
                success: function(data){
                    var likes = data['likes'];
                    var unlikes = data['unlikes'];

                    $("#likes_"+post_id).text(likes);        // setting likes
                    $("#unlikes_"+post_id).text(unlikes);    // setting unlikes

                    if(type == 1){
                        $("#like_"+post_id).css("background-color","green");
                        $("#unlike_"+post_id).css("background-color","rgb(43, 122, 171)");
                        $("#unlike_"+post_id).hover(function(){
                            $(this).css("background-color","red");
                        }, function(){
                            $(this).css("background-color","rgb(43, 122, 171)");
                        });
                        $("#like_"+post_id).hover(function(){
                            $(this).css("background-color","green");
                        }, function(){
                            $(this).css("background-color","green");
                        });
                    }

                    if(type == 0){
                        $("#unlike_"+post_id).css("background-color","red");
                        $("#like_"+post_id).css("background-color","rgb(43, 122, 171)");
                        $("#like_"+post_id).hover(function(){
                            $(this).css("background-color","green");
                        }, function(){
                            $(this).css("background-color","rgb(43, 122, 171)");
                        });
                        $("#unlike_"+post_id).hover(function(){
                            $(this).css("background-color","red");
                        }, function(){
                            $(this).css("background-color","red");
                        });
                    }
                }
            }); 
        }
    });

    document.querySelector('.reply-form').addEventListener('keypress', function(e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            document.querySelector('button').click();
        }
    });


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