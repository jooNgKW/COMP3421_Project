<?php
    session_start();

    // Check if user is off-online, if yes, redirect to login page
    if(!isset($_SESSION["online"]) || $_SESSION["online"] === false){
        header("location: /");
        exit;
    }

    $_SESSION["unique_id"] = $_GET['id'];
    header("location: ../Chat_Room/chatroom.php");
?>