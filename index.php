<?php
// Start the session
session_start();

// Check if the session is started (user logged in), if yes, redirect the user to user_list
if(isset($_SESSION["online"]) && $_SESSION["online"] === true){
    header("location: ./User_List/user_list.php");
    exit;
}
 
// Config the database
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check 1: validate if username is null
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter your username!";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check 2: validate if password is null
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password!";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Check 3: validate if the credentials is valid
    if(empty($username_err) && empty($password_err)){
        // SQL statment to get user infomation, if the provided username exists
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if($sql_statment = mysqli_prepare($link, $sql)){
            // Variables binding to the SQL
            mysqli_stmt_bind_param($sql_statment, "s", $param_username);
            
            // Variable declaration
            $param_username = $username;
            
            // SQL Execution
            if(mysqli_stmt_execute($sql_statment)){
                // Result storage
                mysqli_stmt_store_result($sql_statment);
                
                // Check if there is any result obtained from the SQL
                if(mysqli_stmt_num_rows($sql_statment) == 1){                    
                    mysqli_stmt_bind_result($sql_statment, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($sql_statment)){

                        //check if the password match
                        if(password_verify($password, $hashed_password)){

                            // Start a new session and initialize variables
                            session_start();
                            $_SESSION["online"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Redirect to user list
                            header("location: /User_List/user_list.php");
                            exit;

                        } else{
                            // Incorrect password
                            $login_err = "Login failed! (Invalid username/password)";
                        }
                    }
                } else{
                    // Incorrect username/password
                    $login_err = "Login failed! (Invalid username/password)";
                }
            } else{
                echo "System Error! Please contact IT services for further assistance.";
            }

            // Statement end
            mysqli_stmt_close($sql_statment);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ChatTogether Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./index.css">

</head>
<body>
    <div class="wrapper">
		<header>
			<span><h2>ChatTogether</h2><span>
			<br/>
			<p>Please fill in your credentials to login.</p>
		</header>
        <?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }
        ?>
		<br/>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
			<br/>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
			<div class="form-group">
				<div class="link">
					<p>Don't have an account? <a href="./Sign_Up/Signup.php">Sign up now</a>.</p>
				</div>
			</div>
		</form>
    </div>
</body>
</html>
