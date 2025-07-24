<?php
class Authentication
{
	private $Id;
	private $CustomerId;
	private $ProductId;
	private $order_number;
	private $FirstName;
	private $LastName;
	private $FName;
	private $LName;
	private $Phone;
	private $Dob;
	private $Email;
	private $Password;
	private $Address;
	private $Apartment;
	private $State;
	private $City;
	private $Postcode;
	private $Title;
	private $Fee;
	private $Mobile;
	private $FullName;
	private $conndb;

	private $imagePath;

	public function checkCustomer($Email)
	{
		$conn = new dbClass;
		$this->Email = $Email;
		$this->conndb = $conn;

		$output = $conn->getRowCount("SELECT customer_id FROM `customers` WHERE `email` = '$Email'");
		return $output;
	}

	// public function register($UserName, $Email, $Password){  
	// 	$conn = new dbClass;
	// 	$this->Email = $Email;
	// 	$this->Password = $Password;
	// 	$this->conndb = $conn;

	// 	$stmt = $conn->execute("INSERT INTO `customers`(`username`, `email`, `password`) VALUES ('$UserName', '$Email', '$Password')");

	// 	$registerId = $conn->lastInsertId();
	// 	return $registerId;
	// }

	public function register($firstName, $surname, $dob, $email, $password)
	{
		$conn = new dbClass;

		$this->conndb = $conn;


		$output2 = $conn->execute("INSERT INTO `customers`(`first_name`, `surname`, `dob`, `email`, `password`) VALUES ('$firstName', '$surname', '$dob', '$email', '$password')");
		$output = $conn->getData("SELECT `customer_id` FROM `customers` WHERE `email` = '$email' AND `password` = '$password'");
		$output1 = $conn->getData("SELECT * FROM `customers` WHERE `email` = '$email' AND `password` = '$password'");
		$_SESSION['CUSTOMER_NAME'] = $output1['first_name'] . " " . $output1['surname'];
		$id = $output1['customer_id'];
		// $_SESSION['CUSTOMER_NAME']='Ajmal Alam';

		if (!empty($output) && $_SESSION['USER_CHECKOUT'] == 'checkout') {
			$customerId = $output['customer_id'];
			$cartItem = $_SESSION['cart_item'];
			$IpAddress = $_SERVER["REMOTE_ADDR"];

			//cart
			$conn->execute("UPDATE `cart` SET `customer_id` = '$customerId' WHERE `user_id` = '$cartItem' AND `insert_ip` = '$IpAddress' AND `type`='cart'");
			unset($_SESSION['cart_item']);
			$this->removeDuplicateCartItems($customerId);
		}
		if (!empty($output) && $_SESSION['USER_CHECKOUT'] == 'buynow') {
			$customerId = $output['customer_id'];
			$cartItem = $_SESSION['cart_item'];
			$IpAddress = $_SERVER["REMOTE_ADDR"];
			//buy_now	
			$conn->execute("DELETE  from `cart` where `customer_id` = '$customerId' AND `type`='buyNow' ");
			//buy_now
			$conn->execute("UPDATE `cart` SET `customer_id` = '$customerId' WHERE `user_id` = '$cartItem' AND `insert_ip` = '$IpAddress' AND `type`='buyNow'");
			unset($_SESSION['cart_item']);
			// $this->removeDuplicateCartItems($customerId);
		}
		return $id;
	}
	// public function register($FirstName, $LastName, $Phone, $Dob, $Email, $Password, $Cookies) {  
	// 	$conn = new dbClass;
	// 	$this->Cookies = $Cookies;
	// 	$this->FirstName = $FirstName;
	// 	$this->LastName = $LastName;
	// 	$this->Phone = $Phone;
	// 	$this->Dob = $Dob;
	// 	$this->Email = $Email;
	// 	$this->Password = $Password;
	// 	$this->conndb = $conn;

	// 	$stmt = $conn->execute("INSERT INTO `customers` (`first_name`, `last_name`, `phone`, `dob`, `email`, `password`) 
	// 							VALUES ('$FirstName', '$LastName', '$Phone', '$Dob', '$Email', '$Password')");
	// 	$registerId = $conn->lastInsertId();

	// 	$checkEmail = $this->getCheckEmailSubscriber($Email);  

	// 	if (empty($checkEmail)) {
	// 		$stmt1  = $conn->execute("INSERT INTO `subscribers` (`email`) VALUES ('$Email')");    
	// 		$subcriberId = $conn->lastInsertId();    
	// 		$stmt1  = $conn->execute("INSERT INTO `subscribers_cookies` (`subscribers_id`, `cookies`) 
	// 								   VALUES ('$subcriberId', '$Cookies')");    
	// 	} else {
	// 		$stmt1  = $conn->execute("INSERT INTO `subscribers_cookies` (`subscribers_id`, `cookies`) 
	// 								   VALUES ('" . $checkEmail['id'] . "', '$Cookies')");    
	// 	}

	// 	return $registerId;
	// }


	function getCheckEmailSubscriber($Email)
	{
		$conn = new dbClass;
		$this->conndb = $conn;

		$query = "SELECT * FROM `subscribers` WHERE `email` = '$Email'";
		$result = $conn->getData($query);
		return $result;
	}
	// public function userLogin($Email, $Password) {  
	// 	$conn = new dbClass;
	// 	$this->Email = $Email;
	// 	$this->Password = $Password;
	// 	$this->conndb = $conn;

	// 	$output = $conn->getData("SELECT `customer_id` FROM `customers` WHERE `email` = '$Email' AND `password` = '$Password'");

	// 	if (isset($_SESSION['USER_CHECKOUT']) && $_SESSION['USER_CHECKOUT'] == 'checkout' && !empty($output['customer_id']) && isset($_SESSION['cart_item'])) {
	// 		$cartItem = $_SESSION['cart_item'];
	// 		$remoteAddr = $_SERVER["REMOTE_ADDR"];
	// 		$output1 = $conn->getAllData("SELECT `cart_id` FROM `cart` WHERE `user_id` = '$cartItem' AND `insert_ip` = '$remoteAddr'");

	// 		if (isset($output['customer_id']) && isset($output1['cart_id']) && !empty($output1['cart_id'])) {
	// 			$customerId = $output['customer_id'];
	// 			$cartId = $output1['cart_id'];
	// 			$conn->execute("UPDATE `cart` SET `customer_id` = '$customerId' WHERE `cart_id` = '$cartId'");
	// 		}
	// 		$this->removeDuplicateCartItems($customerId);
	// 		unset($_SESSION['cart_item']);
	// 	}

	// 	return $output;
	// }
	public function userLogin($Email, $Password)
	{
		$conn = new dbClass;
		$this->Email = $Email;
		$this->Password = $Password;
		$this->conndb = $conn;

		$output = $conn->getData("SELECT `customer_id` FROM `customers` WHERE `email` = '$Email' AND `password` = '$Password'");
		$output1 = $conn->getData("SELECT * FROM `customers` WHERE `email` = '$Email' AND `password` = '$Password'");

		$_SESSION['CUSTOMER_NAME'] = $output1['first_name'] . " " . $output1['surname'];


		// $_SESSION['CUSTOMER_NAME']='Ajmal Alam';

		if (!empty($output) && $_SESSION['USER_CHECKOUT'] == 'checkout') {
			$customerId = $output['customer_id'];
			$cartItem = $_SESSION['cart_item'];
			$IpAddress = $_SERVER["REMOTE_ADDR"];

			//cart
			$conn->execute("UPDATE `cart` SET `customer_id` = '$customerId' WHERE `user_id` = '$cartItem' AND `insert_ip` = '$IpAddress' AND `type`='cart'");
			unset($_SESSION['cart_item']);
			$this->removeDuplicateCartItems($customerId);
		}
		if (!empty($output) && $_SESSION['USER_CHECKOUT'] == 'buynow') {
			$customerId = $output['customer_id'];
			$cartItem = $_SESSION['cart_item'];
			$IpAddress = $_SERVER["REMOTE_ADDR"];
			//buy_now	
			$conn->execute("DELETE  from `cart` where `customer_id` = '$customerId' AND `type`='buyNow' ");
			//buy_now
			$conn->execute("UPDATE `cart` SET `customer_id` = '$customerId' WHERE `user_id` = '$cartItem' AND `insert_ip` = '$IpAddress' AND `type`='buyNow'");
			unset($_SESSION['cart_item']);
			// $this->removeDuplicateCartItems($customerId);
		}
		return $output;
	}
	public function removeDuplicateCartItems($customerId)
	{
		$conn = $this->conndb;

		$conn->execute("
			DELETE FROM `cart`
			WHERE `cart_id` NOT IN (
				SELECT MAX(`cart_id`)
				FROM `cart`
				WHERE `customer_id` = '$customerId'
				GROUP BY `product_id`
			)
			AND `customer_id` = '$customerId'
			AND `type`='cart'
		");
	}

	public function userDetails($Id)
	{
		$conn = new dbClass;
		$this->Id = $Id;
		$this->conndb = $conn;

		$output = $conn->getData("SELECT * FROM `customers` WHERE `customer_id` = '$Id'");
		return $output;
	}

	public function resetPassword($Email)
	{
		$conn = new dbClass;
		$this->Email = $Email;
		$this->conndb = $conn;

		$websiteUrl = 'https://www.peuraopticals.com/';

		$output = $conn->getData("SELECT `customer_id`, `first_name`, `last_name`, `email`, `password` FROM `customers` WHERE `email` = '$Email'");

		if (!empty($output['customer_id'])):
			$todayDate = date('d-M-Y');
			$name = ucwords($output['first_name'] . ' ' . $output['last_name']);
			$password = $output['password'];
			$subject = "Your Login Password";
			$from = "peurainfo@peuraopticals.com";
			$to = $output['email'];

			$message = '
			<html>
				<head>
					<title>Forget Password</title>        
				</head>

				<body>
				<table style="max-width:600px;margin:auto;padding:4px;background:#353530;border-radius:16px;">
					<tr>
						<td>
							<table width="100%" style="background:white;border-radius:12px;" cellspacing="0">
								<tr>
									<td style="padding:15px 30px;">
										<table width="100%">
											<tr>
												<td width="40">
													
												</td>
												<td style="text-align:right;">
													Date: ' . $todayDate . '
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td style="text-align:center;background:#fcc201;color:#353530;">
										<h2 style="margin: 10px 0;">Your Password</h2>
									</td>
								</tr>
								<tr>
									<td style="padding:10px 32px;">
										<p style="line-height: 24px;">
											Hello <strong>' . $name . '</strong>,<br>
											You recently requested to reset your password for your Login.
										</p>
										<table width="100%" style="margin:10px 0;line-height: 25px;">
											<tr>
												<th align="left">Your Password</th>
												<td>: ' . $password . '</td>
											</tr>
											<tr>
												<td>To SignIn Your Account</td>
												<td>
												: <a href="https://www.peuraopticals.com/login.php">Click Here</a>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				</body>
		  	</html>';

			mail($to, $subject, $message, "From: <$from>\r\nContent-type: text/html\r\n");

			return true;
		else:
			return false;
		endif;
	}

	public function checkSession($Id)
	{
		$this->Id = $Id;
		if (empty($Id))
			echo "<script>window.location.href='index.php'</script>";
	}
	public function addCustomerAddress($customerId, $type, $country, $postalCode, $houseNo, $addition, $streetName, $placeName, $firstName = null, $surname = null, $phone = null, $email = null)
	{
		// Validate required fields
		if (empty($customerId) || !is_numeric($customerId)) {
			return false; // Invalid customer_id
		}

		$conn = new dbClass;
		$this->conndb = $conn;

		// Handle NULL values for nullable fields
		$type = $type === null ? 'NULL' : "'$type'";
		$country = $country === null ? 'NULL' : "'$country'";
		$postalCode = $postalCode === null ? 'NULL' : "'$postalCode'";
		$houseNo = $houseNo === null ? 'NULL' : "'$houseNo'";
		$addition = $addition === null ? 'NULL' : "'$addition'";
		$streetName = $streetName === null ? 'NULL' : "'$streetName'";
		$placeName = $placeName === null ? 'NULL' : "'$placeName'";
		$firstName = $firstName === null ? 'NULL' : "'$firstName'";
		$surname = $surname === null ? 'NULL' : "'$surname'";
		$phone = $phone === null ? 'NULL' : "'$phone'";
		$email = $email === null ? 'NULL' : "'$email'";

		// Build the SQL query
		$query = "INSERT INTO `addresses` 
        (`customer_id`, `type`, `country`, `postal_code`, `house_no`, `addition`, `street_name`, `place_name`, 
         `first_name`, `surname`, `phone`, `email`, `created_at`, `updated_at`) 
        VALUES 
        ('$customerId', $type, $country, $postalCode, $houseNo, $addition, $streetName, $placeName, 
         $firstName, $surname, $phone, $email, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";

		// Execute the query
		$stmt = $conn->execute($query);

		return $stmt;
	}
	public function updateCustomerAddress($addressId, $customerId, $type, $country, $postalCode, $houseNo, $addition, $streetName, $placeName, $firstName = null, $surname = null, $phone = null, $email = null)
	{
		// Validate required fields
		if (empty($addressId) || !is_numeric($addressId) || empty($customerId) || !is_numeric($customerId)) {
			return false; // Invalid address_id or customer_id
		}

		$conn = new dbClass;
		$this->conndb = $conn;

		// Handle NULL values for nullable fields
		$firstName = $firstName === null ? 'NULL' : "'$firstName'";
		$surname = $surname === null ? 'NULL' : "'$surname'";
		$phone = $phone === null ? 'NULL' : "'$phone'";
		$email = $email === null ? 'NULL' : "'$email'";
		$type = $type === null ? 'NULL' : "'$type'";
		$country = $country === null ? 'NULL' : "'$country'";
		$postalCode = $postalCode === null ? 'NULL' : "'$postalCode'";
		$houseNo = $houseNo === null ? 'NULL' : "'$houseNo'";
		$addition = $addition === null ? 'NULL' : "'$addition'";
		$streetName = $streetName === null ? 'NULL' : "'$streetName'";
		$placeName = $placeName === null ? 'NULL' : "'$placeName'";

		// Build the SQL query
		$query = "UPDATE `addresses` 
        SET 
            `customer_id` = '$customerId', 
            `type` = $type, 
            `country` = $country, 
            `postal_code` = $postalCode, 
            `house_no` = $houseNo, 
            `addition` = $addition, 
            `street_name` = $streetName, 
            `place_name` = $placeName,
            `first_name` = $firstName, 
            `surname` = $surname, 
            `phone` = $phone, 
            `email` = $email,
            `updated_at` = CURRENT_TIMESTAMP
        WHERE 
            `address_id` = '$addressId'";

		// Execute the query
		$stmt = $conn->execute($query);

		return $stmt;
	}
	public function updateuserProfile($FirstName, $Surname, $Phone, $Email, $Country, $PostalCode, $HouseNo, $Addition, $StreetName, $PlaceName, $Id)
	{
		$conn = new dbClass;
		$this->Id = $Id;
		$this->FirstName = $FirstName;
		$this->conndb = $conn;

		// Update query with the correct column names
		$output = $conn->execute("UPDATE `customers` 
								SET `first_name` = '$FirstName', 
									`surname` = '$Surname', 
									`phone` = '$Phone', 
									`email` = '$Email', 
									`country` = '$Country', 
									`postal_code` = '$PostalCode', 
									`house_no` = '$HouseNo', 
									`addition` = '$Addition', 
									`street_name` = '$StreetName', 
									`place_name` = '$PlaceName', 
									`updated_at` = NOW() 
								WHERE `customer_id` = '$Id'");

		return $output;
	}
	public function updateuserImage($imagePath, $Id)
	{
		$conn = new dbClass;
		$this->Id = $Id;
		$this->imagePath = $imagePath;


		$output = $conn->execute("UPDATE `customers` SET `image` = '$imagePath' WHERE `customer_id` = '$Id'");
		return $output;
	}

	public function userShipDetails($Id)
	{
		$conn = new dbClass;
		$this->Id = $Id;
		$this->conndb = $conn;

		$output = $conn->getData("SELECT * FROM `addresses` WHERE `customer_id` = '$Id' And type='shipping'");
		return $output;
	}
	public function userShipDetailsById($Id)
	{
		$conn = new dbClass;
		$this->Id = $Id;
		$this->conndb = $conn;

		$output = $conn->getData("SELECT * FROM `addresses` WHERE `address_id` = '$Id' And type='shipping'");
		return $output;
	}
	public function userBillDetails($Id)
	{
		$conn = new dbClass;
		$this->Id = $Id;
		$this->conndb = $conn;

		$output = $conn->getData("SELECT * FROM `addresses` WHERE `customer_id` = '$Id' And type='billing'");
		return $output;
	}
	public function userBillDetailsById($Id)
	{
		$conn = new dbClass;
		$this->Id = $Id;
		$this->conndb = $conn;

		$output = $conn->getData("SELECT * FROM `addresses` WHERE `address_id` = '$Id' And type='billing'");
		return $output;
	}
	public function userShipDetailsByShipId($Id)
	{
		$conn = new dbClass;
		$this->Id = $Id;
		$this->conndb = $conn;
		$output = $conn->getData("SELECT * FROM `shipping_address` WHERE `id` = '$Id' ");
		return $output;
	}
	public function userAllShipDetails($Id)
	{
		$conn = new dbClass;
		$this->Id = $Id;
		$this->conndb = $conn;

		$output = $conn->getAllData("SELECT * FROM `addresses` WHERE `customer_id` = '$Id' And type='shipping' order by address_id desc");
		return $output;
	}
	public function getuserShipDetailsByShipId($Id)
	{
		$conn = new dbClass;
		$this->Id = $Id;
		$this->conndb = $conn;

		$output = $conn->getData("SELECT * FROM `addresses` WHERE `address_id` = '$Id' And type='shipping'");
		return $output;
	}
	public function deleteAddress($addressId, $customerId)
	{
		$conn = new dbClass();
		$query = "DELETE FROM addresses WHERE address_id = '$addressId' AND customer_id = '$customerId'";
		$stmt = $conn->execute($query);
		return $stmt;
	}

	public function userShipLogin($Id)
	{
		$conn = new dbClass;
		$this->Id = $Id;
		$this->conndb = $conn;

		$output = $conn->getRowCount("SELECT * FROM `shipping_address` WHERE `customer_id` = '$Id'");
		return $output;
	}

	public function addShipping($CustomerId, $FirstName, $LastName, $Phone, $Email, $Address, $Apartment, $State, $City, $Postcode)
	{
		$conn = new dbClass;
		$this->CustomerId = $CustomerId;

		$this->FirstName = $FirstName;
		$this->LastName = $LastName;
		$this->Phone = $Phone;
		$this->Email = $Email;
		$this->Address = $Address;
		$this->Apartment = $Apartment;
		$this->State = $State;
		$this->City = $City;
		$this->Postcode = $Postcode;
		$this->conndb = $conn;

		$output = $conn->execute("INSERT INTO `shipping_address`(`customer_id`, `first_name`, `last_name`, `phone`, `email`, `address`, `apartment`, `state`, `city`, `postcode`) VALUES ('$CustomerId', '$FirstName', '$LastName', '$Phone', '$Email', '$Address', '$Apartment', '$State', '$City', '$Postcode')");
		return $output;
	}

	public function addOrderShipAddress($shipId)
	{
		$conn = new dbClass();
		$shipId = intval($shipId); // Sanitize input

		$query = "INSERT INTO order_ship_address (
			customer_id, ship_id, first_name, surname, phone, 
			email, street_name, country, place_name, postal_code, addition
		) 
		SELECT customer_id, address_id, first_name, surname, phone, 
			email, street_name, country, place_name, postal_code, addition
		FROM addresses
		WHERE address_id = $shipId";

		// Execute the query
		$output = $conn->execute($query);

		if ($output) {
			// $result = $conn->getdata("SELECT postal_code FROM order_ship_address WHERE `ship_id` = $shipId order by id desc");
			// $output = $result['postal_code'] ?? null;
			$output=$conn->lastInsertId();
			
		}

		return $output;
	}
	public function addOrderBillAddress($shipId)
	{
		$conn = new dbClass();
		$shipId = intval($shipId); // Sanitize input

		$query = "INSERT INTO order_bill_address(
			customer_id, ship_id, first_name, surname, phone, 
			email, street_name, country, place_name, postal_code, addition
		) 
		SELECT customer_id, address_id, first_name, surname, phone, 
			email, street_name, country, place_name, postal_code, addition
		FROM addresses
		WHERE address_id = $shipId";

		// Execute the query
		$output = $conn->execute($query);
				if ($output) {
			// $result = $conn->getdata("SELECT postal_code FROM order_ship_address WHERE `ship_id` = $shipId order by id desc");
			// $output = $result['postal_code'] ?? null;
			$output=$conn->lastInsertId();
			
		}

		return $output;
	}


	public function userOrderAddressDetailsByShipId($shipId)
	{
		$conn = new dbClass;
		$this->conndb = $conn;
		$output = $conn->getData("SELECT id FROM `order_ship_address` WHERE `ship_id` = '$shipId' ORDER BY `id` DESC limit 1");
		return $output;
	}
	public function userOrderAddressDetailsBillId($shipId)
	{
		$conn = new dbClass;
		$this->conndb = $conn;
		$output = $conn->getData("SELECT id FROM `order_bill_address` WHERE `ship_id` = '$shipId' ORDER BY `id` DESC limit 1");
		return $output;
	}

	public function updateShipping($FirstName, $LastName, $Phone, $Email, $Address, $Apartment, $State, $City, $Postcode, $Id)
	{
		$conn = new dbClass;
		$this->Id = $Id;
		$this->FirstName = $FirstName;
		$this->LastName = $LastName;
		$this->Phone = $Phone;
		$this->Email = $Email;
		$this->Address = $Address;
		$this->Apartment = $Apartment;
		$this->State = $State;
		$this->City = $City;
		$this->Postcode = $Postcode;
		$this->conndb = $conn;

		$output = $conn->execute("UPDATE `shipping_address` SET `first_name` = '$FirstName', `last_name` = '$LastName', `phone` = '$Phone', `email` = '$Email', `address` = '$Address', `apartment` = '$Apartment', `state` = '$State', `city` = '$City', `postcode` = '$Postcode', `updated_at` = NOW() WHERE `id` = '$Id'");
		return $output;
	}

	public function updateShippingByShipId($FirstName, $LastName, $Phone, $Email, $Address, $Apartment, $State, $City, $Postcode, $Id)
	{
		$conn = new dbClass;
		$this->Id = $Id;
		$this->FirstName = $FirstName;
		$this->LastName = $LastName;
		$this->Phone = $Phone;
		$this->Email = $Email;
		$this->Address = $Address;
		$this->Apartment = $Apartment;
		$this->State = $State;
		$this->City = $City;
		$this->Postcode = $Postcode;
		$this->conndb = $conn;

		$output = $conn->execute("UPDATE `shipping_address` SET `first_name` = '$FirstName', `last_name` = '$LastName', `phone` = '$Phone', `email` = '$Email', `address` = '$Address', `apartment` = '$Apartment', `state` = '$State', `city` = '$City', `postcode` = '$Postcode', `updated_at` = NOW() WHERE `id` = '$Id'");
		return $output;
	}

	public function changePassword($Password, $Id)
	{
		$conn = new dbClass;
		$this->Id = $Id;
		$this->Password = $Password;
		$this->conndb = $conn;

		$output = $conn->execute("UPDATE `customers` SET `password` = '$Password' WHERE `customer_id` = '$Id'");
		return $output;
	}
	// 	function createAndLoginUser($firstName, $surname, $email, $phone, $country, $postalCode, $houseNo, $addition, $streetName, $placeName)
	// {
	// 	$conn = new dbClass;

	// 	$this->conndb = $conn;
	// 	// Check if email already exists
	// 	$checkUserExist = $this->checkCustomer($email);
	// 	if ($checkUserExist) {
	// 		return ['success' => false, 'error' => "Email is already registered. Please log in."];
	// 	}

	// 	// Generate a random password
	// 	$password = bin2hex(random_bytes(8)); // 16-character random password
	// 	$query = "INSERT INTO customers 
    //       (first_name, surname, email, phone, country, postal_code, house_no, addition, street_name, place_name, password, created_at, updated_at) 
    //       VALUES 
    //       ('$firstName', '$surname', '$email', '$phone', '$country', '$postalCode', '$houseNo', '$addition', '$streetName', '$placeName', '$password', NOW(), NOW())";
	// 	// Execute the query
	// 	$stmt = $conn->execute($query);
	// 	if ($stmt) {
	// 		$userId = $conn->lastInsertId();
	// 		$sqlLogin = $this->userLogin($email, $password);

	// 		$_SESSION['USER_LOGIN'] = $userId;
	// 		$_SESSION['USER_EMAIL'] = $email;
	// 		if (!isset($_SESSION['cart_item'])) {
	// 			$_SESSION['cart_item'] = strtoupper(uniqid() . time() . str_shuffle('12345'));
	// 			header("Location: " . $_SERVER['REQUEST_URI']); // Reloads the same page
	// 			exit;
	// 		}

	// 		// Optionally send email with credentials
	// 		// mail($email, "Your Account Details", "Email: $email\nPassword: $password");

	// 		return ['success' => true, 'userId' => $userId, 'password' => $password];
	// 	}
	// 	return ['success' => false, 'error' => "Failed to create account."];
	// }
	function createAndLoginUser($firstName, $surname, $email, $phone, $country, $postalCode, $houseNo, $addition, $streetName, $placeName, $type)
{
    $conn = new dbClass;
    $this->conndb = $conn;

    // Check if email already exists
    $checkUserExist = $this->checkCustomer($email);
    if ($checkUserExist) {
        return ['success' => false, 'error' => "Email is already registered. Please log in."];
    }

    // Generate a random password
    $password = bin2hex(random_bytes(8));

    $query = "INSERT INTO customers 
          (first_name, surname, email, phone, country, postal_code, house_no, addition, street_name, place_name, password, created_at, updated_at) 
          VALUES 
          ('$firstName', '$surname', '$email', '$phone', '$country', '$postalCode', '$houseNo', '$addition', '$streetName', '$placeName', '$password', NOW(), NOW())";
    
    // Execute and check for errors
    $stmt = $conn->execute($query);
    if ($stmt) {
        $userId = $conn->lastInsertId();
        $sqlLogin = $this->userLogin($email, $password);

        if (!empty($sqlLogin)) {
            $_SESSION['USER_LOGIN'] = $userId;
            $_SESSION['USER_EMAIL'] = $email;
            if (!isset($_SESSION['cart_item'])) {
                $_SESSION['cart_item'] = strtoupper(uniqid() . time() . str_shuffle('12345'));
                session_write_close(); // Save session before redirect

            }
				$to = $email;
                $subject = "New Account Created";
				$message = '<html><body>';
				$message .= '<h1>Welcome to Our Website! Looksabaya</h1>';
				$message .= '<p>Your account has been successfully created.</p>';
				$message .= '<p><strong>Email:</strong> ' . $email . '</p>';
				$message .= '<p><strong>Password:</strong> ' . $password . '</p>';
				$message .= '</body></html>';
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        mail($to, $subject, $message, $headers);

            return ['success' => true, 'userId' => $userId, 'password' => $password];
        } else {
            error_log("Login failed for email: $email, password: $password");
            return ['success' => false, 'error' => "Login failed after account creation."];
        }
    } else {
        error_log("Failed to create user with email: $email, query: $query");
        return ['success' => false, 'error' => "Failed to create account."];
    }
}
}
