<?php
require_once("include/database.config.php");
require_once("include/register.class.php");

$registration = new Register();

if (isset($registration)) {
    if ($registration->err) {
        foreach ($registration->err as $error) {
            echo $error;
        }
    }
    if ($registration->msg) {
        foreach ($registration->msg as $message) {
            echo $message;
        }
    }
}
?>


<form method="post" action="register.php" name="registerform">

	<table><tr><td>Username:</td>
    <td><input id="username" type="text" name="user_name" required /></td></tr>



    <tr><td>Email:</td>
    <td><input id="email" type="email" name="user_email" required /></td></tr>


    <tr><td>Password:</td>
    <td><input id="password_new" type="password" name="user_pass" autocomplete="off" /></td></tr>


    <tr><td>Repeat password:</td>
    <td><input id="password_repeat" type="password" name="user_pass_repeat" autocomplete="off" /></td></tr></table>

    <input type="submit" name="register" value="Register" />

</form>

<!-- backlink -->
<a href="index.php">Back to Login Page</a>