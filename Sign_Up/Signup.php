<?php
// Initialize the session.
session_start();

// Include config file
require_once "../config.php";
 
// Define variables and initialize with empty values
$first_name = $last_name = $username = $email = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
 
    // Check if icon is empty
    if(isset($_FILES["icon"]) && $_FILES["icon"]["error"] == 0){
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "png" => "image/png");
        $filename = $_FILES["icon"]["name"];
        $fileformat = $_FILES["icon"]["format"];
        $filesize = $_FILES["icon"]["size"];
    
        // Verify file type
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!array_key_exists($ext, $allowed)) die("Please upload jpg., jpeg., png. ");
    
        // Verify file size - 10MB maximum
        $maxsize = 10 * 1024 * 1024;
        if($filesize > $maxsize) die("Please select a file not larger than 10 MB");
    
        // Verify the file
        if(in_array($filetype, $allowed)){
            // Check whether file exists before uploading it
            if(file_exists("upload/" . $filename)){
                echo $filename . "is already exists, please upload the new icon!";
            } else{
                move_uploaded_file($_FILES["icon"]["tmp_name"], "upload/" . $filename);
                echo "Uploaded successfully!";
            } 
        } else{
            echo "Error: There was a problem uploading your file. Please try again."; 
        }
        } else{
            echo "Error: " . $_FILES["icon"]["error"];
        }
    }   
    
    //show the information of the uploaded file and stores it in a temporary directory on the web server.
    if($_FILES["photo"]["error"] > 0){
        echo "Error: " . $_FILES["icon"]["error"] . "<br>";
    } else{
        echo "File name: " . $_FILES["icon"]["name"] . "<br>";
        echo "File format: " . $_FILES["icon"]["format"] . "<br>";
        echo "File size: " . ($_FILES["icon"]["size"] / 1024) . " KB<br>";
        echo "Storage: " . $_FILES["icon"]["tmp_name"];
    }

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
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password, first_name, last_name) VALUES (?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_username, $param_password, $param_first_name, $param_last_name);
            
            // Set parameters
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

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="error <?php echo empty($username_err) && empty($password_err) && empty($confirm_password_err)? '': 'show'?>"><?php echo empty($username_err)? (empty($password_err) ? (empty($confirm_password_err)? "": $confirm_password_err) : $password_err) : $username_err ?></div>

                <div class="f input">
                    <label>Icon</label>
                    <input type="file" name ="icon" accept=".png, .jpg, .jpeg">
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
