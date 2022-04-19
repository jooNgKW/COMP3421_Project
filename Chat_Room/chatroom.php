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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
</head>
<body>
    <div class="wrapper">
        <section class="chat-area">
            <header>
                <a href="#" class="back-icon"><i class="fas fa-arrow-left"></i></a>
                <img src="<?php echo $Friend_row['icon']; ?>" alt="">
                <div class="details">
                    <p><?php echo $Friend_row['username'] ?></p>
                    <div><?php echo $Friend_row['first_name']. " ".$Friend_row['last_name'] ?></div>
                    <!-- <p><?php echo $Friend_row['status']; ?></p> -->
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
                <input type="text" name="out_id" value="<?php echo $_SESSION['unique_id']?>" hidden>
                <input class="in_id" type="text" name="in_id" value="<?php echo $_SESSION['id']?>" hidden>
                <input class="input-field" type="text" name="message" placeholder="Type a message here..." autocomplete="off">
                <button><i class="fab fa-telegram-plane"></i></button>
            </form>
        </section>
    </div>
<script src="chatroom.js"></script>
</body>
</html>
