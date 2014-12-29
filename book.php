<?php

require_once("include/database.config.php");
require_once("include/login.class.php");
require_once("include/addressbook.class.php");
require_once("include/header.php");

$login = new Login();

if (isset($login)) {
	
	if($login->isUserLoggedIn()) {
		$addressbook = new Addressbook($_SESSION['user_id']);
		
	} else {
		$host  = $_SERVER['HTTP_HOST'];
		$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$extra = 'index.php';
		header("LOCATION: http://$host$uri/$extra");
		exit;
	}
	
	
    
} 

$action = null;

if (isset($_GET['action'])) {
	$action = $_GET['action'];
}

switch($action) {
	default:
		echo "<a href='?action=add'>Add new contact</a>";
		echo "<table class='CSSTableGenerator'>";
		$addressbook->display_contacts();
		echo "</table>";
	break;
	case 'view':
		if(isset($_GET['ID'])) {
			$addressbook->display_single(ID);
		}
	break;
	case 'add':
		if(isset($_POST['submit'])) {
				//print_r($_POST);
				$addressbook->add_new();
		}
		
		//var_dump($values);
		echo '<strong>Add Entry</strong><br><br>
			<form method="POST" action="?action=add">
			<table class="CSSTableGenerator" border="0" width="50%" cellspacing="2" cellpadding="2">
			<tr><td>
			Name: </td><td><input type="text" name="name" value=""></td></tr><tr><td>
			Address: </td><td><textarea name="address" ></textarea></td></tr><tr><td>
			Phone: </td><td><input type="text" name="phone" value=""></td></tr><tr><td>
			Email: </td><td><input type="text" name="email"  value=""></td></tr><tr><td>
			URL: </td><td><input type="text" name="url"  value=""></td></tr><tr><td>
			Note: </td><td><textarea name="note"></textarea></td></tr><tr><td>
			<tr><td align="left" colspan="2">
			<input type="submit" name="submit" value="submit"></td></form>
			</tr></table><br /><br />
			<a href="book.php">Back to listing</a><br /><br />';
	
	break;
	case 'edit':
		
		if(isset($_GET['ID'])) {
			
			if(isset($_POST['submit'])) {
				//print_r($_POST);
				$addressbook->set_single($_GET['ID'],$_POST['name'],$_POST['phone'],$_POST['email'],$_POST['address'],$_POST['url'],$_POST['note']);
			}
			$values = $addressbook->get_single($_GET['ID']);
		}
		//var_dump($values);
		echo '<strong>Edit Entry</strong><br><br>
			<form method="POST" action="?action=edit&ID='.$_GET['ID'].'">
			<table class="CSSTableGenerator" border="0" width="50%" cellspacing="2" cellpadding="2">
			<tr><td>
			Name: </td><td><input type="text" name="name" value="'.$values->name.'"></td></tr><tr><td>
			Address: </td><td><textarea name="address" >'.$values->address.'</textarea></td></tr><tr><td>
			Phone: </td><td><input type="text" name="phone" value="'.$values->phone.'"></td></tr><tr><td>
			Email: </td><td><input type="text" name="email"  value="'.$values->email.'"></td></tr><tr><td>
			URL: </td><td><input type="text" name="url"  value="'.$values->url.'"></td></tr><tr><td>
			Note: </td><td><textarea name="note">'.$values->note.'</textarea></td></tr><tr><td>
			<tr><td align="left" colspan="2">
			<input type="submit" name="submit" value="submit"></td></form>
			</tr></table><br /><br />
			<a href="book.php">Back to listing</a><br /><br />';
	break;
	case 'delete':
		if(isset($_GET['ID'])) {
			$addressbook->delete_single($_GET['ID']);
		}
		echo '<br /><br />
			<a href="book.php">Back to listing</a><br /><br />';
	break;
	
}
if ($addressbook->err) {
	foreach ($addressbook->err as $error) {
		echo '<h1>'. $error . '</h1>';
	}
}
if ($addressbook->msg) {
	foreach ($addressbook->msg as $message) {
		echo '<h1>'. $message . '</h1>';
	}
}
?>
<br />
<br />
<br />
<br />

<form method="post" action="book.php" name="logout">
	<input type="submit" name="logout" value="Log out" />
</form>
