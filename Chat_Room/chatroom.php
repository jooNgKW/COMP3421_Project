<?php
    session_start();
    if(isset($_SESSION[''unique_id])){
        include_once "config.php";
        $outgoing_id = mysqli_real_escape_string($conn, $POST["outgoing_id"]);
        $incoming_id = mysqli_real_escape_string($conn, $POST["incoming_id"]);
        $message = mysqli_real_escape_string($conn, $POST["message"]);
        
        if(!empty($message)){
            $sql = mysqli_query($conn, "INSERT INTO message (incoming_msg_id, outgoing_msg_id, msg) VALUES ({$incoming_id},{$outgoing_id},'{$message}')")  
        }else{
            header("../login.php")
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chatroom</title>
    <link rel="stylesheet" href="./chatroom.css">
</head>
<body>
    <div class="wrapper">
        <section class="chat-area">
            <header>
                <a href="#" class="back-icon"><i class="fas fa-arrow-left"></i></a>
                <img src="" alt="icon">
                <div class="details">
                    <span>Coding Nepal</span>
                    <p>Active now</p>
                </div>
            </header>

            <div class="chat-box">

                <div class="chat outgoing">
                    <div class="details">
                        <p>This is the chat content from you</p>
                    </div>
                </div>

                <div class="chat incoming">
                    <img src="" alt="icon">
                    <div class="details">
                        <p>This is the chat content from your friend</p>
                    </div>
                </div>

                <div class="chat outgoing">
                    <div class="details">
                        <p>This is the chat content from you</p>
                    </div>
                </div>

                <div class="chat incoming">
                    <img src="" alt="icon">
                    <div class="details">
                        <p>This is the chat content from your friend</p>
                    </div>
                </div>

            </div>

            <form action="#" class="typing-area">
                <input type="text" placeholder="Type a message here...">
                <button><i class="fab fa-telegram-plane"></i></button>
            </form>
        </section>
    </div>

</body>
</html>
