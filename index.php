<?php

require_once("include/database.config.php");
require_once("include/login.class.php");
require_once("include/addressbook.class.php");

$login = new Login();

if (isset($login)) {
	//echo "TESTING";
	//var_dump($login);
	//print_r($_POST);
	//echo $login->isUserLoggedIn();
	if($login->isUserLoggedIn()) {
		$host  = $_SERVER['HTTP_HOST'];
		$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$extra = 'book.php';
		header("LOCATION: http://$host$uri/$extra");
		exit;
	}
	
	
    if ($login->err) {
        foreach ($login->err as $error) {
            echo $error;
        }
    }
    if ($login->msg) {
        foreach ($login->msg as $message) {
            echo $message;
        }
    }
} 
?>

<form method="post" action="index.php" name="loginform">

    <label for="username">Username</label>
    <input id="username" type="text" name="user_name" required />

    <label for="password">Password</label>
    <input id="password" type="password" name="user_pass" autocomplete="off" required />

    <input type="submit"  name="login" value="Log in" />

</form>

<a href="register.php">Register new account</a>