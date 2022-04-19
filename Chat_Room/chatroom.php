<?php 
    session_start();

    include_once "../config.php";

    if(!isset($_SESSION['unique_id'])){
        header("location: /");
    } else{
        $Friend_id = $_SESSION['unique_id'];
    }

    $sql = mysqli_query($link, "SELECT * FROM users WHERE id = {$Friend_id}");
    if(mysqli_num_rows($sql) > 0){
        $Friend_row = mysqli_fetch_assoc($sql);
    }else{
        header("location: /");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chatroom</title>
    <link rel="stylesheet" href="./chatroom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div class="wrapper">
        <section class="chat-area">
            <header>
                <img class="image-icon" src="<?php echo $Friend_row['icon']; ?>" alt="">
                <div class="details">
                    <p><?php echo $Friend_row['username'] ?></p>
                    <div id="friend-caption"><?php echo $Friend_row['caption'] ?></div>
                    <!-- <p><?php echo $Friend_row['status']; ?></p> -->
                </div>
                <a id="pen-button" href="/"><i class="fa fa-close"></i></a> 
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
                <input type="text" name="out_id" value="<?php echo $_SESSION['unique_id']?>" hidden>
                <input type="text" name="in_id" value="<?php echo $user_id?>" hidden>
                <input type="text" name="message" placeholder="Type a message here..." autocomplete="off">
                <button><i class="fab fa-telegram-plane"></i></button>
            </form>
        </section>
    </div>
<script src="javascript/users.js"></script>
</body>
</html>
