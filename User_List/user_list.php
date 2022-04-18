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

if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Store user input to search variable
    if(empty(trim($_POST["search"]))){
        $search = "";
    } else{
        $search = trim($_POST["search"]);
    }
}

// Query database for user icon
$result = mysqli_query($link, "SELECT icon FROM users WHERE id = '".$_SESSION['id']."'");
$currentuser = mysqli_fetch_array($result, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COMP3421 Final Project</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./user_list.css">
</head>
<body>
    <div class="wrapper">
        <section class="user-page">

            <!-- User Account Information -->
            <div class="account">
                <div class="account-info">
                    <?php echo '<img src="'.$currentuser["icon"].'" alt="'.$_SESSION['username'].'" class="image-icon">'; ?>
					<!--<img src="./img/peter.jpg" alt="'.$username.'" class="image-icon">-->
					<div class="content">
                        <span><?php echo $_SESSION['username']?></span>
                        <p><i class="fa fa-circle" id="my-status-indicator"></i> &nbsp Active</p>
                    </div>
                </div>
                <a id="logout-button" href="/logout.php">Logout</a> 
            </div>

            <!-- Search -->
            <form class="search" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="text" placeholder="Enter name to search...." name="search" value="<?php echo isset($search)?$search:""; ?>">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>

            <!-- Friend Account Information -->
            <div class="user-list">
                <?php
                    // Query all users in the database
                    $sql = "SELECT id, username, icon, email FROM users WHERE id != ?";

                    // Execute SQL
                    if($stmt = mysqli_prepare($link, $sql)){
                        mysqli_stmt_bind_param($stmt, "i", $param_id);
                        $param_id = $_SESSION['id'];
                        if(mysqli_stmt_execute($stmt)){
                            mysqli_stmt_store_result($stmt);
                            mysqli_stmt_bind_result($stmt, $id, $username, $icon, $email);
                            $number_of_list = mysqli_stmt_num_rows($stmt);

                            $counter = 0;
                            // list all the query result
                            for($x = 0; $x < $number_of_list; $x++){
                                if(mysqli_stmt_fetch($stmt)){
                                    if(empty($search) || str_contains($username, $search)){
                                        $counter += 1;
                                        echo 
										'<a href="./redirect.php?id='.$id.'" class="accountbutton">
											<div class="account">
												<div class="account-info">
													<img src="'.$icon.'" alt="'.$username.'" class="image-icon">
													<div class="content">
														<span>'.$username.'</span>
														<p> Email: '.$email.'</p>
													</div>
												</div>
											</div>
                                        </a>';
                                    }
                                }
                            }

                            // Provide negative feedback when no user is found from the query
                            if ($counter == 0){
                                echo "<h4>No user is found!</h4>";
                            }
                        }
                    }

                    mysqli_stmt_close($stmt);

                ?>
            </div>    
        </section>
    </div>
</body>
</html>
