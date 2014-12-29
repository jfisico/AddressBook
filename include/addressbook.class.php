<?php

class Addressbook
{
	private $dbconnection = null;
	private $userID;
	public $err = array();
	public $msg = array();
	
	public $name, $email, $address, $phone, $url, $note;
	
	function Addressbook($myID) {
		$this->userID = $myID;
	}
	
	function add_new() {
		if($_POST['name'] == "") {
			$this->err[] = "Please enter a name.";
		} elseif($_POST['email'] == "") {
			$this->err[] = "Please enter an email.";
		} elseif($_POST['address'] == "") {
			$this->err[] = "Please enter an address.";
		} elseif($_POST['phone'] == "") {
			$this->err[] = "Please enter a phone number.";
		} else {
			$this->dbconnection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
			if (!$this->dbconnection->connect_errno) {
				
				$name = $this->dbconnection->real_escape_string(strip_tags($_POST['name'], ENT_QUOTES));
				$email = $this->dbconnection->real_escape_string(strip_tags($_POST['email'], ENT_QUOTES));
				$phone = $this->dbconnection->real_escape_string(strip_tags($_POST['phone'], ENT_QUOTES));
				$address = $this->dbconnection->real_escape_string(strip_tags($_POST['address'], ENT_QUOTES));
				$url = $this->dbconnection->real_escape_string(strip_tags($_POST['url'], ENT_QUOTES));
				$note = $this->dbconnection->real_escape_string(strip_tags($_POST['note'], ENT_QUOTES));
				
				$sql = "SELECT * FROM contacts WHERE email = '" . $email . "';";
					$results = $this->dbconnection->query($sql);
					
					if ($results->num_rows == 1) {
						$this->err[] = "Sorry, that email is already associated with one of your contacts.";
					} else {
						$sql = "INSERT INTO contacts (userID, name, email, address, phone, url, note)
								VALUES('" . $this->userID. "', '" . $name . "', '" . $email . "', '" . $address . "', '" . $phone . "', '" . $url . "', '" . $note . "');";
						$results_2 = $this->dbconnection->query($sql);
					   
						if ($results_2) {
							$this->msg[] = "Contact added.";
						} else {
							$this->err[] = "Sorry, adding this contact failed. Please try again";
						}
					}
			}
		}
		
	}
	
	function display_contacts() {
		$this->dbconnection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if (!$this->dbconnection->connect_errno) {
			$sql = "SELECT * FROM contacts WHERE userID = '" . $this->userID . "' ORDER BY name;";
			$results = $this->dbconnection->query($sql);
			while($obj = $results->fetch_object()){ 
				echo "<tr>
				<td><strong>$obj->name, Phone: $obj->phone</strong></td>
				<td align='right'><a href='?action=edit&ID=$obj->ID'>Edit</a> - <a href='?action=delete&ID=$obj->ID'>Delete</a></tr>
				<tr><td colspan='2'><span style='font-size:.8em'>$obj->address, $obj->email, $obj->phone</span></td></tr>";
			}
			$results->close(); 
			unset($obj); 
		}		
		
	}
	
	function display_single($contactID) {
		//Unused, next release may have additional fields and content, such as a contact picture that could be displayed for a single contact.
	}
	
	function get_single($contactID) {
		$this->dbconnection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if (!$this->dbconnection->connect_errno) {
			$sql = "SELECT * FROM contacts WHERE ID = '" . $contactID . "';";
			$results = $this->dbconnection->query($sql);
		}
		$obj = $results->fetch_object();
		return $obj;
	}
	
	function set_single($contactID, $name, $phone, $email, $address, $url, $note) {
		$this->dbconnection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if (!$this->dbconnection->connect_errno) {
			$sql = "UPDATE contacts SET name = '".$name."', phone = '".$phone."', email = '".$email."', address = '".$address."', url = '".$url."', note = '".$note."' WHERE ID = '" . $contactID . "';";
			$results = $this->dbconnection->query($sql);
			
				if ($results) {
					$this->msg[] = "Edit successful.";
				} else {
					$this->err[] = "Sorry, editing failed, please try again." . $sql;
				}
		}
	}
	
	function delete_single($contactID) {
		$this->dbconnection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if (!$this->dbconnection->connect_errno) {
			$sql = "DELETE FROM contacts WHERE ID = ".$contactID.";";
			$results = $this->dbconnection->query($sql);
			
				if ($results) {
					$this->msg[] = "Deleted successfully.";
				} else {
					$this->err[] = "Sorry, deleting this contact failed, please try again." . $sql;
				}
		}
	}
	
}