<?php
    // Start the session
    session_start();

    // Config Database
    require_once "../config.php";

    // Check if user is off-online, if yes, redirect to login page
    if(!isset($_SESSION["online"]) || $_SESSION["online"] === false){
        header("location: /");
        exit;
}

    if(isset($_POST["new-caption"])){
        $sql = 'UPDATE users SET caption = ? WHERE id = ?';
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "ss", $param_caption,$param_id);

            
            // Parameters declaration
            $param_caption = $_POST["new-caption"];
            $param_id = $_SESSION["id"];
        
            // SQL Execution
            if(mysqli_stmt_execute($stmt)){
                header("location: /");
            } else{
                echo "System error! Contact website admin for further assistance.";
            }
    
            mysqli_stmt_close($stmt);
        }
    }
?>