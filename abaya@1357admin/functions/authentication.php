<?php

class Authentication {

	private $email;
	private $pass;
	private $conn;
	
	public function adminLogin($email,$pass) {
	
		$conn = new dbClass();
		$this->conn = $conn;
		$this->email = $email;
		$this->pass = $pass;
		
		$result = $conn->getData("SELECT * FROM `admin` WHERE `email` = '$email' AND `password` = '$pass'");
		
		if($result!=''){	

			$conn->updateExecute("UPDATE `admin` SET  `login_ip` = '".$_SERVER['REMOTE_ADDR']."', `login_date` = now() WHERE `email` = '$email'");

			$_SESSION['ADMIN_USER_ID'] = $result['id'];			
			return true; 		
		} else {
			return false;
		}
	}

	public function checkSession() {
        if (
            !isset($_SESSION['ADMIN_USER_ID']) || $_SESSION['ADMIN_USER_ID'] == ''
        ) {
            header('Location: index.php');
            exit();
        }
    }
	
	public function SignOut() {
		unset($_SESSION['ADMIN_USER_ID']);
		session_destroy();
		echo "<script>window.location.href='index.php'</script>";
	}
}

class ChangePassword
{
    private $userId;
	private $enteredPassword;
	private $newPassword;
	private $conn;

    public function changePassword1($userId, $newPassword)
	{
		$conn = new dbClass();
		$this->conn = $conn;
		$this->userId = $userId;
		$this->newPassword = $newPassword;

		$userId = (int) $userId;

		try {
			$query = "UPDATE `admin` SET `password` = :password WHERE `id` = :id";
			$params = array(':password' => $newPassword, ':id' => $userId);

			$stmt = $conn->executeStatement($query, $params);

			if ($stmt) {
				return true; // Successfully changed password
			} else {
				return false; // Error occurred
			}
		} catch (PDOException $e) {
			error_log("Error changing password: " . $e->getMessage());
			return false; // Error occurred
		}
	}

	public function verifyPassword($userId, $enteredPassword)
	{
		$conn = new dbClass();
		$this->conn = $conn;
		$this->userId = $userId;
		$this->enteredPassword = $enteredPassword;

		$userId = (int) $userId;

		try {
			$result = $conn->getData("SELECT `password` FROM `admin` WHERE `id` = '$userId'");

			if ($result && $result['password'] === $enteredPassword) {
				return true; // Passwords match
			} else {
				return false; // Incorrect password
			}
		} catch (PDOException $e) {
			error_log("Error verifying password: " . $e->getMessage());
			return false; // Error occurred
		}
	}
}

?>