<?php

class Register
{
	private $dbconnection = null;
	public $err = array();
	public $msg = array();
	
	public function __construct()
	{
		if(isset($_POST["register"])) {
			$this->registerMe();
		}
	}
	
	private function registerMe() {
		if ($_POST['user_name']=="") {
			 $this->err[] = "Username required";
		} elseif ($_POST['user_pass']=="" || $_POST['user_pass_repeat']=="") {
			 $this->err[] = "Please enter a password";
		} elseif ($_POST['user_pass'] !== $_POST['user_pass_repeat']) {
			$this->err[] = "Passwords must match";
		} elseif (empty($_POST['user_email'])) {
			$this->err[] = "Email required";
		} else {
		
			$this->dbconnection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
			if (!$this->dbconnection->connect_errno) {
				
				$user_name = $this->dbconnection->real_escape_string(strip_tags($_POST['user_name'], ENT_QUOTES));
                $user_email = $this->dbconnection->real_escape_string(strip_tags($_POST['user_email'], ENT_QUOTES));
				$user_password = $_POST['user_pass'];
				$user_password_hash = password_hash($user_password, PASSWORD_DEFAULT);
				
				$sql = "SELECT * FROM users WHERE user_name = '" . $user_name . "' OR user_email = '" . $user_email . "';";
                $results = $this->dbconnection->query($sql);
				
				if ($results->num_rows == 1) {
                    $this->err[] = "Sorry, that username or email addres is already in use. Please try again";
                } else {
                    
                    $sql = "INSERT INTO users (user_name, user_password, user_email)
                            VALUES('" . $user_name . "', '" . $user_password_hash . "', '" . $user_email . "');";
                    $results_2 = $this->dbconnection->query($sql);
                   
                    if ($results_2) {
                        $this->msg[] = "Registration successful. You can now log in.";
                    } else {
                        $this->err[] = "Sorry, registration failed. Please try again.";
                    }
                }
				
			} else {
				$this->err[] = "Database connection problem.";
			}
		}
	}
}