<?php
    session_start();

    // Check if user is off-online, if yes, redirect to login page
    if(!isset($_SESSION["online"]) || $_SESSION["online"] === false){
        header("location: /");
        exit;
    }

    if(isset($_SESSION['unique_id'])){
        include_once "../config.php";
        $From_id = $_SESSION['id'];
        $To_id = $_SESSION['unique_id'];
        $output = "";

        $sql_msg = mysqli_query($link, "SELECT * FROM messages WHERE (From_id = {$From_id} AND To_id = {$To_id})
                                    OR (From_id = {$To_id} AND To_id = {$From_id}) ORDER BY msg_id");

        $sql_icon = mysqli_query($link, "SELECT icon FROM users WHERE id='{$To_id}'");
        $row_icon = mysqli_fetch_assoc($sql_icon);                     

        if(mysqli_num_rows($sql_msg)>0){
            while($row = mysqli_fetch_assoc($sql_msg)){
                if($row['From_id'] == $From_id){
                    $output .= '<div class = "chat outgoing">
                                <div class="details">
                                    <p> '. $row['msg'] .'</p>
                                </div>
                                </div>';
                }else{
                    $output .= '<div class = "chat incoming">
                                <img src="'.$row_icon['icon'].'" alt="" class="image-icon">
                                <div class="details">
                                    <p> '. $row['msg'] .'</p>
                                </div>
                                </div>';
                }
            }
            echo $output;
        }
        
        


    }else{
        header("../login.php");
    }

?>
