<?php

// Start the session
session_start();

    // Check if user is off-online, if yes, redirect to login page
    if(!isset($_SESSION["online"]) || $_SESSION["online"] === false){
        header("location: /");
        exit;
    }

    if(!isset($_SESSION['unique_id'])){
        header("location: /");
    } else{
        $Friend_id = $_SESSION['unique_id'];
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chatroom</title>
    <link rel="stylesheet" href="./chatroom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <div class="wrapper">
        <section class="chat-area">
            <header>
                <a href="/"><i class="fa fa-angle-left"></i></a> 
                <img class="image-icon" src="<?php echo $Friend_row['icon']; ?>" alt="">
                <div class="details">
                    <p><?php echo $Friend_row['username'] ?></p>
                    <div id="friend-caption"><?php echo $Friend_row['caption'] ?></div>
                </div>
            </header>
                
            <div class="chat-box" id="msg-box"></div>

            <form action="./insert_chat.php" class="typing-area" method="post">
                <input class="input-field" type="text" name="message" placeholder="Type a message here..." autocomplete="off">
                <button type="submit"><i class="fab fa-telegram-plane"></i></button>
            </form>
        </section>
    </div>
</body>
<script src="user_list.js"></script>
</html>
