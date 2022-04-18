<?php
// Database parameters
define('database_server_ip', 'localhost');
define('database_username', 'root');
define('database_password', '');
define('database_name', 'COMP3421');
 
// Connect to the database
try{
    $link = mysqli_connect(database_server_ip, database_username, database_password, database_name);
} catch(Exception $e){
    die("<h1>SQL Server offline. <br> Contact administrator for more information.</h1>");
}

 
// Verify DB connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}