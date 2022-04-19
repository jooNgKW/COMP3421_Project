<?php 
    session_start();

    if(isset($_SESSION['unique_id'])){
        include_once "../config.php";

        $out_id = $_SESSION['unique_id'];
        $in_id = mysqli_real_escape_string($link, $_POST['in_id']);
        $message = mysqli_real_escape_string($link, $_POST['message']);

        $sql = "INSERT INTO messages (in_msg_id, out_msg_id, msg) VALUES ('{$in_id}', '{$out_id}', '{$message}')";

        if (mysqli_query($link, $sql)) {
            header("location: ./chatroom.php");
          } else {
            echo "System Error, contact web admin for further assistance." . $sql . "<br>" . $link->error;
          }

    }else{
        header("location: ../login.php");
    }


    
?>
