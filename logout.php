<?php

//Destory the session and redirect to the login page
Session_start();
Session_destroy();
header('Location: /');

?>