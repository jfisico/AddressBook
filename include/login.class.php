<?php

class Login
{
	private $dbconnection = null;
	public $err = array();
	public $msg = array();
	public $user_id;
	
	public function __construct()
	{
		session_start();
		if(isset($_POST["logout"])) {
			$this->logMeOut();
		}
		elseif(isset($_POST["login"])) {
			$this->logMeIn();
		}
	}
	
	private function logMeIn() {
		if($_POST['user_name'] == "") {
			$this->err[] = "Please enter a username.";
		} elseif($_POST['user_pass'] == "") {
			$this->err[] = "Please enter a password.";
		} else {
			$this->dbconnection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
			if (!$this->dbconnection->connect_errno) {
				
				//$this->msg[] = "WORKING";
				
				$user_name = $this->dbconnection->real_escape_string($_POST['user_name']);
				$sql = "SELECT user_id, user_name, user_email, user_password
                        FROM users
                        WHERE user_name = '" . $user_name . "' OR user_email = '" . $user_name . "';";
                $result = $this->dbconnection->query($sql);
				
				if ($result->num_rows == 1) {
					$result_row = $result->fetch_object();
					if (password_verify($_POST['user_pass'], $result_row->user_password)) {
						$_SESSION['user_name'] = $result_row->user_name;
                        $_SESSION['user_email'] = $result_row->user_email;
						$_SESSION['user_id'] = $result_row->user_id;
						$_SESSION['my_status'] = 1;
						$this->msg[] = "Logged in";
					} else {
						$this->err[] = "The password was incorrect. Please try again.";
					}
				} else {
					$this->err[] = "User not found.";
				}
				
			} else {
				$this->err[] = "Database connection problem.";
			}
		
		}
		
	}
	
	public function logMeOut()
    {
        $_SESSION = array();
        session_destroy();
        $this->msg[] = "You have been logged out. Come back soon.";
    }
	
	public function isUserLoggedIn()
    {
        if (isset($_SESSION['my_status']) AND $_SESSION['my_status'] == 1) {
            return true;
        }
		
        return false;
    }
}