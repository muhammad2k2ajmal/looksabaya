<?php
class CommProducts
{
	private $productID;
	private $catID;
	private $subCatID;
	private $conndb;
	private $Code;


	function getAllBanners()
	{
		$conn = new dbClass;
		$this->conndb = $conn;

		$stmt = $conn->getAllData("SELECT * FROM `banner` WHERE `status` = 1 ORDER BY `id` DESC");
		return $stmt;
	}
	function getPolicy($type)
	{
		$conn = new dbClass;
		$this->conndb = $conn;

		$stmt = $conn->getData("SELECT * FROM `policy` where `type`='$type'");
		return $stmt;
	}
	function getAllTestimonials()
	{
		$conn = new dbClass;
		$this->conndb = $conn;

		$stmt = $conn->getAllData("SELECT * FROM `testimonial` WHERE `status` = 1 ORDER BY `id` DESC");
		return $stmt;
	}
	function getAllFaqs()
	{
		$conn = new dbClass;
		$this->conndb = $conn;

		$stmt = $conn->getAllData("SELECT * FROM `faq` WHERE `status` = 1 ORDER BY `id` DESC");
		return $stmt;
	}

	function getAllNewProduct()
	{
		$conn = new dbClass;
		$this->conndb = $conn;

		$stmt = $conn->getAllData("SELECT * FROM `product` WHERE `status` = '1' And `new_arrivals`= '1'");
		return $stmt;
	}
	function getAllProductsBySearchQuery($searchQuery)
{
    $conn = new dbClass;
    $this->conndb = $conn;

    // Use prepared statement to prevent SQL injection
    // $searchTerm = "%" . $searchQuery . "%";

    $sql = "SELECT * FROM `product` 
            WHERE `status` = '1' 
            AND (`name` LIKE '%$searchQuery%' OR `description` LIKE '%$searchQuery%')";

    $stmt = $conn->getAllData($sql);

    return $stmt;
}

	function getAllVideoProduct()
	{
		$conn = new dbClass;
		$this->conndb = $conn;

		$stmt = $conn->getAllData("SELECT * FROM `product`
WHERE `status` = '1'
  AND `video` IS NOT NULL
  AND `video` != ''
  AND `video` != '0';
");
		return $stmt;
	}
	function getAllBestSellingProduct()
	{
		$conn = new dbClass;
		$this->conndb = $conn;

		$stmt = $conn->getAllData("SELECT * FROM `product` WHERE `status` = '1' And `best_selling`= '1'");
		return $stmt;
	}
	function getAllTrendingProduct()
	{
		$conn = new dbClass;
		$this->conndb = $conn;

		$stmt = $conn->getAllData("SELECT * FROM `product` WHERE `status` = '1' And `trending`= '1' limit 6");
		return $stmt;
	}
		public function getAllCategoriesWithProducts()
	{
		$conn = new dbClass;
		$this->conndb = $conn;
		try {
			$query = "
                SELECT DISTINCT c.*
                FROM `category` c
                INNER JOIN `product` p ON p.category_id = c.id
                WHERE c.status = 1 AND p.status = 1
                ORDER BY c.id desc
            ";
			$stmt = $this->conndb->getAllData($query);
			return $stmt ?: []; // Return empty array if no results
		} catch (Exception $e) {
			// Log error in production; for now, return empty array
			return [];
		}
	}


	public function getAllProductsByCategory($CategoryId)
	{
		$conn = new dbClass;
		$this->conndb = $conn;


		$output = $conn->getAllData("
			SELECT * 
			FROM `product` 
			WHERE `status` = '1' 
			AND `category_id` = '$CategoryId' 
			ORDER BY `product_id` DESC
		");

		return $output;
	}

	function getProductsById($Id)
    {
        $conn = new dbClass;
        $this->conndb = $conn;

        $product = $conn->getData("SELECT * FROM `product` WHERE `product_id` = '$Id'");
        $sizes = $conn->getAllData("SELECT size FROM `product_sizes` WHERE `product_id` = '$Id'");
        $product['sizes'] = array_column($sizes, 'size');
        $lists = $conn->getAllData("SELECT list_item FROM `product_lists` WHERE `product_id` = '$Id'");
        $product['lists'] = array_column($lists, 'list_item');
        $colors = $conn->getAllData("SELECT pc.color_id,c.name,c.color_code FROM `product_colors` pc left join color c on c.id=pc.color_id WHERE `product_id` = '$Id'");
        $product['colors'] = $colors;
		$deliveryOptions = $conn->getAllData("SELECT delivery_location, cost FROM `product_delivery_options` WHERE `product_id` = '$Id'");
        $product['delivery_options'] = $deliveryOptions;

        return $product;
    }
	
    function getProductImages($Id)
    {
        $conn = new dbClass();
        $this->conndb = $conn;

        return $conn->getAllData("SELECT * FROM `product_images` WHERE `product_id` = '$Id'");
    }

    function getProductImagesByColor($Id, $ColorId)
    {
        $conn = new dbClass();
        $this->conndb = $conn;

        return $conn->getAllData("SELECT * FROM `product_images` WHERE `product_id` = '$Id' AND `color_id` = '$ColorId'");
    }

	
	public function getAllCategoriesWithProduct()
	{
		$conn = new dbClass;
		$this->conndb = $conn;
		$stmt = $this->conndb->getAllData("
            SELECT DISTINCT c.*
            FROM `category` c
            INNER JOIN `product_categories` pc ON c.id = pc.category_id
            INNER JOIN `product` p ON pc.product_id = p.product_id
            ORDER BY c.id DESC
        ");
		return $stmt;
	}




	public function allOtherProduct($catID,  $productID)
	{
		$conn = new dbClass;
		$this->catID = $catID;
		$this->productID = $productID;
		$this->conndb = $conn;

		$currencyRate = $_SESSION['currencyRates'] ?? 1;

		$stmt = $conn->getAllData("SELECT * FROM `product` WHERE `category_id` = '$catID' AND product_id != '$productID' ORDER BY product_id DESC");

		foreach ($stmt as &$product) {
			if (isset($product['price'])) {
				$product['price'] = $product['price'] * $currencyRate;
			}
		}


		return $stmt;
	}
}

class CommCustomers
{
	private $Id;
	private $CustomerId;
	private $AddressId;
	private $ProductId;
	private $Name;
	private $FirstName;
	private $Surname;
	private $Dob;
	private $Phone;
	private $Email;
	private $Password;
	private $Address;
	private $PlaceName;
	private $StreetName;
	private $Addition;
	private $HouseNo;
	private $PostalCode;
	private $Country;
	private $State;
	private $City;
	private $Postcode;
	private $conndb;

	public function checkCustomer($Email)
	{
		$conn = new dbClass;
		$this->Email = $Email;
		$this->conndb = $conn;

		$output = $conn->getRowCount("SELECT customer_id FROM `customers` WHERE `email` = '$Email'");
		return $output;
	}
	public function checkCustomerPhone($phone)
	{
		$conn = new dbClass;
		$this->Phone = $phone;
		$this->conndb = $conn;

		$output = $conn->getRowCount("SELECT customer_id FROM `customers` WHERE `phone` = '$phone'");
		return $output;
	}
	public function getCustomerAddressCountByType($type)
	{
		$conn = new dbClass;
		// $this->Type = $type;
		$this->conndb = $conn;

		$query = "SELECT address_id FROM `addresses` WHERE `type` = '$type'";
		$output = $conn->getRowCount($query);

		return $output;
	}


	public function register($firstName, $surname, $dob, $email, $password)
	{
		$conn = new dbClass;
		$this->FirstName = $firstName;
		$this->Surname = $surname;
		$this->Dob = $dob;
		$this->Email = $email;
		$this->Password = $password; // Hash the password
		$this->conndb = $conn;

		$stmt = $conn->execute("INSERT INTO `customers`(`first_name`, `surname`, `dob`, `email`, `password`) VALUES ('$firstName', '$surname', '$dob', '$email', '$password')");

		$signupId = $conn->lastInsertId();
		return $signupId;
	}

	// public function userLogin($Email, $Password)
	// {
	// 	$conn = new dbClass;
	// 	$this->Email = $Email;
	// 	$this->Password = $Password;
	// 	$this->conndb = $conn;

	// 	$output = $conn->getData("SELECT `customer_id` FROM `customers` WHERE `email` = '$Email' AND `password` = '$Password'");
	// 	return $output;
	// }
	public function userLogin($Email, $Password)
	{
		$conn = new dbClass;
		$this->Email = $Email;
		$this->Password = $Password;
		$this->conndb = $conn;

		$output = $conn->getData("SELECT `customer_id` FROM `customers` WHERE `email` = '$Email' AND `password` = '$Password'");

		if (!empty($output) && $_SESSION['USER_CHECKOUT'] == 'checkout') {
			$customerId = $output['customer_id'];
			$cartItem = $_SESSION['cart_item'];
			$IpAddress = $_SERVER["REMOTE_ADDR"];

			$conn->execute("UPDATE `cart` SET `customer_id` = '$customerId' WHERE `user_id` = '$cartItem' AND `insert_ip` = '$IpAddress'");


			unset($_SESSION['cart_item']);

			$this->removeDuplicateCartItems($customerId);
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
		");
	}

	public function sendOtp($websiteUrl, $Email)
	{
		$conn = new dbClass;
		$this->Email = $Email;
		$this->conndb = $conn;

		$otp = $_SESSION['otp'] = mt_rand(100000, 999999);
		$_SESSION['email'] = $Email;

		$query = "INSERT INTO `otp_verify`(`email`, `otp`) VALUES (:email, :otp)";

		$params = [
			':email' => $Email,
			':otp' => $otp
		];

		$stmt = $conn->executeStatement($query, $params);

		if ($stmt) {
			// Prepare to send the OTP email
			$todayDate = date('d-M-Y');
			$subject = "Your OTP Code";
			$from = "mailwala@mailwala.com";
			$to = $Email;

			// Construct the email message
			$message = '
			<html>
				<head>
					<title>Your OTP Code</title>        
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
														<img src="' . $websiteUrl . 'ayaans-creation/assets/img/favicon.jpg" style="width:70px;" alt="Logo">
													</td>
													<td style="text-align:right;">
														Date: ' . $todayDate . '
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td style="text-align:center;background:#6E4632;color:#fff;">
											<h2 style="margin: 10px 0;">Your OTP Code</h2>
										</td>
									</tr>
									<tr>
										<td style="padding:10px 32px;">
											<p>Hello,</p>
											<p>Your OTP code for verification is: <strong>' . $otp . '</strong></p>
											<p>Please use this code to complete your verification process.</p>
											<p>Thank you!</p>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</body>
			</html>';

			// Send the email
			mail($to, $subject, $message, "From: <$from>\r\nContent-type: text/html\r\n");

			return true; // Return true indicating OTP was sent
		} else {
			return false; // Return false indicating failure to store OTP
		}
	}

	public function changePassword($Password, $Email)
	{
		$conn = new dbClass;
		$this->Email = $Email;
		$this->Password = $Password;
		$this->conndb = $conn;

		$output = $conn->execute("UPDATE `customers` SET `password` = '$Password' WHERE `email` = '$Email'");
		return $output;
	}
	public function addCustomerAddress($customerId, $type, $country, $postalCode, $houseNo, $addition, $streetName, $placeName)
	{
		$conn = new dbClass;
		$this->conndb = $conn;

		$stmt = $conn->execute("INSERT INTO `addresses` 
			(`customer_id`, `type`, `country`, `postal_code`, `house_no`, `addition`, `street_name`, `place_name`) 
			VALUES 
			('$customerId', '$type', '$country', '$postalCode', '$houseNo', '$addition', '$streetName', '$placeName')");

		return $stmt;
	}


	public function checkSession($Id)
	{
		$this->Id = $Id;
		if (empty($Id))
			echo "<script>window.location.href='login.php'</script>";
	}

	public function userDetails($CustomerId)
	{
		$conn = new dbClass;
		$this->CustomerId = $CustomerId;
		$this->conndb = $conn;

		$output = $conn->getData("SELECT * FROM `customers` WHERE `customer_id` = '$CustomerId'");
		return $output;
	}

	// public function updateuserProfile($Name, $Phone, $Email, $Address, $Apartment, $State, $City, $Postcode, $Id)
	// {
	// 	$conn = new dbClass;
	// 	$this->Id = $Id;
	// 	$this->Name = $Name;
	// 	$this->Phone = $Phone;
	// 	$this->Email = $Email;
	// 	$this->Address = $Address;
	// 	$this->Apartment = $Apartment;
	// 	$this->State = $State;
	// 	$this->City = $City;
	// 	$this->Postcode = $Postcode;
	// 	$this->conndb = $conn;

	// 	$output = $conn->execute("UPDATE `customers` SET `name` = '$Name', `phone` = '$Phone', `email` = '$Email', `address` = '$Address', `apartment` = '$Apartment', `state` = '$State', `city` = '$City', `postcode` = '$Postcode', `updated_at` = NOW() WHERE `customer_id` = '$Id'");
	// 	return $output;
	// }
	public function updateuserProfile($FirstName, $Surname, $Phone, $Email, $Country, $PostalCode, $HouseNo, $Addition, $StreetName, $PlaceName, $Id)
	{
		$conn = new dbClass;
		$this->Id = $Id;
		$this->FirstName = $FirstName;
		$this->Surname = $Surname;
		$this->Phone = $Phone;
		$this->Email = $Email;
		$this->Country = $Country;
		$this->PostalCode = $PostalCode;
		$this->HouseNo = $HouseNo;
		$this->Addition = $Addition;
		$this->StreetName = $StreetName;
		$this->PlaceName = $PlaceName;
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


	public function passwordChange($Password, $Id)
	{
		$conn = new dbClass;
		$this->Id = $Id;
		$this->Password = $Password;
		$this->conndb = $conn;

		$output = $conn->execute("UPDATE `customers` SET `password` = '$Password' WHERE `customer_id` = '$Id'");
		return $output;
	}

	public function userShipDetails($Id)
	{
		$conn = new dbClass;
		$this->Id = $Id;
		$this->conndb = $conn;

		$output = $conn->getData("SELECT * FROM `shipping_address` WHERE `customer_id` = '$Id'");
		return $output;
	}

	public function addShipping($CustomerId, $Name, $Phone, $Email, $Address, $Apartment, $State, $City, $Postcode)
	{
		$conn = new dbClass;
		$this->CustomerId = $CustomerId;
		$this->Name = $Name;
		$this->Phone = $Phone;
		$this->Email = $Email;
		$this->Address = $Address;
		// $this->Apartment = $Apartment;
		$this->State = $State;
		$this->City = $City;
		$this->Postcode = $Postcode;
		$this->conndb = $conn;

		$output = $conn->execute("INSERT INTO `shipping_address`(`customer_id`, `name`, `phone`, `email`, `address`, `apartment`, `state`, `city`, `postcode`) VALUES ('$CustomerId','$Name', '$Phone', '$Email', '$Address', '$Apartment', '$State', '$City', '$Postcode')");
		return $output;
	}
	public function addOrderAddress($CustomerId, $Name, $Phone, $Email, $Address, $Apartment, $State, $City, $Postcode)
	{
		$conn = new dbClass;
		$this->CustomerId = $CustomerId;
		$this->Name = $Name;
		$this->Phone = $Phone;
		$this->Email = $Email;
		$this->Address = $Address;
		// $this->Apartment = $Apartment;
		$this->State = $State;
		$this->City = $City;
		$this->Postcode = $Postcode;
		$this->conndb = $conn;

		$output = $conn->execute("INSERT INTO `order_address`(`customer_id`, `name`, `phone`, `email`, `address`, `apartment`, `state`, `city`, `postcode`) VALUES ('$CustomerId', '$Name', '$Phone', '$Email', '$Address', '$Apartment', '$State', '$City', '$Postcode')");
		return $output;
	}
	public function updateShipping($CustomerId, $AddressId, $Name, $Phone, $Email, $Address, $Apartment, $State, $City, $Postcode)
	{
		$conn = new dbClass;
		$this->CustomerId = $CustomerId;
		$this->AddressId = $AddressId;
		$this->Name = $Name;
		$this->Phone = $Phone;
		$this->Email = $Email;
		$this->Address = $Address;
		// $this->Apartment = $Apartment;
		$this->State = $State;
		$this->City = $City;
		$this->Postcode = $Postcode;
		$this->conndb = $conn;

		$output = $conn->execute("UPDATE `shipping_address` 
                              SET `name` = '$Name', 
                                  `phone` = '$Phone', 
                                  `email` = '$Email', 
                                  `address` = '$Address', 
                                  `apartment` = '$Apartment', 
                                  `state` = '$State', 
                                  `city` = '$City', 
                                  `postcode` = '$Postcode' 
                              WHERE `customer_id` = '$CustomerId'");
		return $output;
	}


	// public function sendOtp($websiteUrl, $Email){  
	// 	$conn = new dbClass;
	// 	$this->Email = $Email;
	// 	$this->conndb = $conn;

	// 	$otp = $_SESSION['otp'] = mt_rand(100000, 999999);
	// 	$stmt = $conn->execute("INSERT INTO `otp_verify`(`email`, `otp`) VALUES ('$Email', '$otp')");

	// 	if ($stmt) {
	// 		// Prepare to send the OTP email
	// 		$todayDate = date('d-M-Y');
	// 		$subject = "Your OTP Code";
	// 		$from = "mailwala@mailwala.com";
	// 		$to = $Email;

	// 		// Construct the email message
	// 		$message = '
	// 		<html>
	// 			<head>
	// 				<title>Your OTP Code</title>        
	// 			</head>

	// 			<body>
	// 				<table style="max-width:600px;margin:auto;padding:4px;background:#353530;border-radius:16px;">
	// 					<tr>
	// 						<td>
	// 							<table width="100%" style="background:white;border-radius:12px;" cellspacing="0">
	// 								<tr>
	// 									<td style="padding:15px 30px;">
	// 										<table width="100%">
	// 											<tr>
	// 												<td width="40">
	// 													<img src="'.$websiteUrl.'ayaans-creation/assets/img/favicon.jpg" style="width:70px;" alt="Logo">
	// 												</td>
	// 												<td style="text-align:right;">
	// 													Date: '.$todayDate.'
	// 												</td>
	// 											</tr>
	// 										</table>
	// 									</td>
	// 								</tr>
	// 								<tr>
	// 									<td style="text-align:center;background:#6E4632;color:#fff;">
	// 										<h2 style="margin: 10px 0;">Your OTP Code</h2>
	// 									</td>
	// 								</tr>
	// 								<tr>
	// 									<td style="padding:10px 32px;">
	// 										<p>Hello,</p>
	// 										<p>Your OTP code for verification is: <strong>' . $otp . '</strong></p>
	// 										<p>Please use this code to complete your verification process.</p>
	// 										<p>Thank you!</p>
	// 									</td>
	// 								</tr>
	// 							</table>
	// 						</td>
	// 					</tr>
	// 				</table>
	// 			</body>
	// 		</html>';

	// 		// Send the email
	// 		mail($to, $subject, $message, "From: <$from>\r\nContent-type: text/html\r\n");

	// 		return true; // Return true indicating OTP was sent
	// 	} else {
	// 		return false; // Return false indicating failure to store OTP
	// 	}		
	// }

}

class OrderPage
{
	private $orderID;
	private $productID;
	private $customerID;
	private $ShippingID;
	private $conndb;

	function getOrderById($orderID)
	{
		$conn = new dbClass;
		$this->conndb = $conn;
		$this->orderID = $orderID;

		$output = $conn->getData("SELECT * FROM `orders_table` WHERE `order_id` = '$orderID'");
		return $output;
	}
	function getProductOrderDetailsById($orderID)
	{
		$conn = new dbClass;
		$this->conndb = $conn;
		$this->orderID = $orderID;

		$output = $conn->getAllData("SELECT * FROM `order_product_details` WHERE `order_id` = '$orderID'");
		return $output;
	}
	function getAllOrder($customerID)
	{
		$conn = new dbClass;
		$this->conndb = $conn;
		// $this->customerID = $customerID;

		$output = $conn->getAllData("SELECT * FROM `orders_table` WHERE `customer_id` = '$customerID' ORDER BY `order_id` DESC");
		if($output){
			foreach ($output as &$order) {
				$order['count'] = $conn->getRowCount("SELECT * FROM `order_product_details` WHERE `order_id` = '{$order['order_id']}'");
			}
		}
		return $output;
	}

	function getProductByOrderId($productID)
	{
		$conn = new dbClass;
		$this->conndb = $conn;
		$this->productID = $productID;

		$output = $conn->getData("SELECT * FROM `products` WHERE `product_id` = '$productID'");
		return $output;
	}

	function getShippingByOrderId($ShippingID)
	{
		$conn = new dbClass;
		$this->conndb = $conn;
		$this->ShippingID = $ShippingID;

		$output = $conn->getData("SELECT * FROM `shipping_address` WHERE `order_number` = '$ShippingID'");
		return $output;
	}
}
class Contact
{
	private $ID;
	private $Name;
	private $Phone;
	private $Email;
	private $Message;
	private $db;

	public function __construct(dbClass $db)
	{
		$this->db = $db;
	}

	public function addContact($Name, $Subject, $Email, $Message)
	{
		try {
			$query = "INSERT INTO contact (name, subject, email, message) VALUES (:name, :subject, :email, :message)";
			$params = array(
				':name' => $Name,
				':subject' => $Subject,
				':email' => $Email,
				':message' => $Message
			);
			return $this->db->executeStatement($query, $params);
		} catch (Exception $e) {
			// Log the error (you can implement actual logging if needed)
			error_log("Error in Contact::addContact: " . $e->getMessage());
			return false;
		}
	}
}

class BannerPage
{
	private $conndb;
	function getBanners()
	{
		$conn = new dbClass;
		$this->conndb = $conn;

		$stmt = $conn->getAllData("SELECT * FROM `banner` WHERE `status` = 1 ORDER BY `id` DESC");
		return $stmt;
	}
}

class NoticePage
{
	private $conndb;
	function getNotice()
	{
		$conn = new dbClass;
		$this->conndb = $conn;

		$stmt = $conn->getAllData("SELECT * FROM `notice` ORDER BY `id` DESC");
		return $stmt;
	}
}
class Categories
{
	private $conndb;

	// Constructor to initialize database connection
	public function __construct()
	{
		$this->conndb = new dbClass;
	}

	// Get all active categories with active products


	

	


	
}





?>
