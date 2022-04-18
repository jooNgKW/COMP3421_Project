<?php 
    session_start();
    if(isset($_SESSION['unique_id'])){
        include_once "config.php";
        $out_id = $_SESSION['unique_id'];
        $ing_id = mysqli_real_escape_string($link, $_POST['in_id']);
        $message = mysqli_real_escape_string($link, $_POST['message']);
        if(!empty($message)){
            $sql = mysqli_query($conn, "INSERT INTO messages (in_msg_id, out_msg_id, msg)
                                        VALUES ({$in_id}, {$out_id}, '{$message}')") or die();
        }
    }else{
        header("location: ../login.php");
    }


    
?>
