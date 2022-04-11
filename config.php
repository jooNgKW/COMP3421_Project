<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('database_server_ip', 'localhost');
define('database_username', 'root');
define('database_password', '');
define('database_name', 'COMP3421');
 
/* Attempt to connect to MySQL database */
try{
    $link = mysqli_connect(database_server_ip, database_username, database_password, database_name);
} catch(Exception $e){
    die("<h1>SQL Server offline. <br> Contact administrator for more information.</h1>");
}

 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}