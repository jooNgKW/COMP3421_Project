<?php
// Initialize the session.
session_start();

// Include config file
require_once "../config.php";
 
// Define variables and initialize with empty values
$icon = $first_name = $last_name = $username = $email = $password = $confirm_password = "";
$icon_err = $email_err = $username_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "png" => "image/png");
    $maxsize = 4 * 1024 * 1024;
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    //Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter an email.";
    }else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE email = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters
            $param_email = trim($_POST["email"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "This email is already taken.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    // Check if icon is empty
    if($_FILES['icon']['size'] == 0 && $_FILES['icon']['error'] == 0){
        $icon_err = "Please upload the icon.";  

    // Verify icon type:jpg., jpeg., png. 
    }elseif(!in_array($_FILES["icon"]["type"], $allowed)){
        $icon_err = "Icon can only be jpg., jpeg., png.";
        
    // Verify icon size (10MB maximum)
    }elseif($_FILES["icon"]["size"] > $maxsize){
        $icon_err = "Icon can not be larger than 4 GB";
        
    }
    
    // Check input errors before inserting in database
    if(empty($icon_err) && empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err)){

        //set image
        $info = pathinfo($_FILES['icon']['name']);
        $ext = $info['extension']; // get the extension of the file
        $newname = $username.'.'.$ext;
        $target = $_SERVER['DOCUMENT_ROOT']."/images/".$newname;
        move_uploaded_file($_FILES['icon']['tmp_name'], $target);
        $icon_tmp = $target;
  
        // Prepare an insert statement
        $sql = "INSERT INTO users (email, icon, username, password, first_name, last_name) VALUES (?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssss", $param_email, $param_icon_tmp, $param_username, $param_password, $param_first_name, $param_last_name);
            
            // Set parameters
            $param_email = $email;
            $param_icon_tmp = $icon_tmp;
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_first_name = $first_name;
            $param_last_name = $last_name;
        
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: /");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    //Clear $password and $confirm_password
    $password = "";
    $confirm_password = "";
    
    // Close connection
    mysqli_close($link);
}
?>

<html lang="en">
    
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://kit.fontawesome.com/8f0e351197.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>

    <div class="wrapper">

        <section class="form">
            <header>Chatroom - Sign up</header>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                <div class="error <?php echo empty($$email_err) &&empty($icon_err) && empty($username_err) && empty($password_err) && empty($confirm_password_err)? '': 'show'?>"><?php echo empty($email_err)? (empty($icon_err)? (empty($username_err)? (empty($password_err) ? (empty($confirm_password_err)? "": $confirm_password_err) : $password_err) : $username_err) : $icon_err): $email_err ?></div>

                <div class="f input">
                    <label>Icon</label>
                    <input type="file" name="icon" accept=".png, .jpg, .jpeg" value="<?php echo $icon_tmp;?>">
                </div>

                <div class="name">
                    <div class="f input">
                        <label>First name</label>
                        <input type="text" name="first_name" value="<?php echo $first_name; ?>">
                    </div>
                    <div class="f input">
                        <label>Last name</label>
                        <input type="text" name="last_name" value="<?php echo $last_name; ?>">
                    </div>
                </div>

                <div class="f input">
                    <label>Username</label>
                    <input type="text" name="username" value="<?php echo $username; ?>">
                </div>


                <div class="f input">
                    <label>Email</label>
                    <input type="text" name="email" value="<?php echo $email; ?>">
                </div>

                <div class="f input">
                    <label>Password</label>
                    <input type="password" name="password" value="<?php echo $password; ?>">
                    <!--<i class="fa-solid fa-eye-slash" id="eye"></i>-->
                </div>

                <div class="f input">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" value="<?php echo $confirm_password; ?>">
                    <!--<i class="fa-solid fa-eye-slash" id="eye"></i>-->
                </div>

                <div class="f button" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <input type="submit" value="Create account">
                </div>
                
            </form>

            <div class="link">Already signed up?<a href="/"> Login now</a></div>

        </section>
    </div>

</body>

</html>
