<?php
class CommProducts
{
	private $productID;
	private $catID;
	private $subCatID;
	private $conndb;
	private $Code;

	function getAllCoupons()
	{
		$conn = new dbClass;
		return $conn->getAllData("SELECT * FROM `coupon` WHERE `status` = 1 AND `expiry_date` > NOW() ORDER BY `id` DESC");
	}
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
	function getAllProductById($product_id)
	{
		$conn = new dbClass;
		$this->conndb = $conn;

		$stmt = $conn->getData("SELECT * FROM `product` WHERE `product_id` = '$product_id'");
		return $stmt;
	}
	function getAllFeaturedProduct()
	{
		$conn = new dbClass;
		$this->conndb = $conn;

		$stmt = $conn->getAllData("SELECT * FROM `product` WHERE `status` = '1' And `featured`= '1'");
		return $stmt;
	}
	function getAllNewProduct()
	{
		$conn = new dbClass;
		$this->conndb = $conn;

		$stmt = $conn->getAllData("SELECT * FROM `product` WHERE `status` = '1' And `new_arrivals`= '1'");
		return $stmt;
	}

	// function getAllCategories()
	// {
	// 	$conn = new dbClass;
	// 	$this->conndb = $conn;

	// 	$stmt = $conn->getAllData("SELECT * FROM `category` ORDER BY `id` DESC");
	// 	return $stmt;
	// }
	// function getAllCategories()
	// {
	// 	$conn = new dbClass;
	// 	$this->conndb = $conn;

	// 	$stmt = $conn->getAllData("
	// 		SELECT DISTINCT c.* 
	// 		FROM `category` c
	// 		INNER JOIN `product` p ON c.id = p.category_id
	// 		ORDER BY c.id DESC
	// 	");

	// 	return $stmt;
	// }
	public function getAllProductsBySubCategory($subCategoryId)
	{
		$conn = new dbClass;
		$this->conndb = $conn;


		$output = $conn->getAllData("
			SELECT * 
			FROM `product` 
			WHERE `status` = '1' 
			AND `subcategory_id` = '$subCategoryId' 
			ORDER BY `product_id` DESC
		");

		return $output;
	}

	// function getSubCategoriesWithProducts($categoryId)
	// {
	// 	$conn = new dbClass;
	// 	$this->conndb = $conn;

	// 	$stmt = $conn->getAllData("
	// 		SELECT DISTINCT sc.* 
	// 		FROM sub_category sc
	// 		INNER JOIN product p ON sc.id = p.subcategory_id
	// 		WHERE sc.category_id = '$categoryId' AND sc.status = 1
	// 		ORDER BY sc.id DESC
	// 	");

	// 	return $stmt;
	// }
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

	// Get all subcategories that have products
	// public function getAllSubcategoriesWithProduct() {
	//     $stmt = $this->conndb->getAllData("
	//         SELECT DISTINCT sc.*
	//         FROM `subcategory` sc
	//         INNER JOIN `product_subcategories` psc ON sc.id = psc.subcategory_id
	//         INNER JOIN `product` p ON psc.product_id = p.product_id
	//         ORDER BY sc.id DESC
	//     ");
	//     return $stmt;
	// }
	public function getAllSubcategoriesWithProduct($category_id)
	{
		$conn = new dbClass;
		$this->conndb = $conn;
		$stmt = $this->conndb->getAllData("
			SELECT DISTINCT sc.*
			FROM `subcategory` sc
			INNER JOIN `product_subcategories` psc ON sc.id = psc.subcategory_id
			INNER JOIN `product` p ON psc.product_id = p.product_id
			WHERE sc.category_id = '$category_id'
			ORDER BY sc.id DESC
		");

		return $stmt;
	}

	// Get all subsubcategories that have products
	public function getAllSubsubcategoriesWithProduct()
	{
		$stmt = $this->conndb->getAllData("
            SELECT DISTINCT ssc.*
            FROM `subsubcategory` ssc
            INNER JOIN `product_subsubcategories` pssc ON ssc.id = pssc.subsubcategory_id
            INNER JOIN `product` p ON pssc.product_id = p.product_id
            ORDER BY ssc.id DESC
        ");
		return $stmt;
	}


	// public function bestProducts(){
	// 	$conn = new dbClass;
	// 	$this->conndb = $conn;

	// 	$currencyRate = $_SESSION['currencyRates'] ?? 1;

	// 	$output = $conn->getAllData("SELECT * FROM `product` WHERE `status` = '1' AND `best_sellers` = '1' ORDER BY `product_id` DESC");

	// 	foreach ($output as &$product) {
	// 		if (isset($product['price']) && isset($product['discount'])) {
	// 			$product['price'] = number_format($product['price'] * $currencyRate, 3, '.', '');
	// 			$product['discount'] = number_format($product['discount'] * $currencyRate, 3, '.', '');
	// 		}
	// 	}

	// 	return $output;
	// }

	public function bestProducts()
	{
		$conn = new dbClass;
		$this->conndb = $conn;

		$currencyRate = $_SESSION['currencyRates'] ?? 1;

		$output = $conn->getAllData("SELECT * FROM `product` WHERE `status` = '1' AND `best_sellers` = '1' ORDER BY `product_id` DESC");

		foreach ($output as &$product) {
			if (isset($product['price'])) {
				$product['price'] = $product['price'] * $currencyRate;
			}
		}

		return $output;
	}

	public function newProducts()
	{
		$conn = new dbClass;
		$this->conndb = $conn;

		$currencyRate = $_SESSION['currencyRates'] ?? 1;

		$output = $conn->getAllData("SELECT * FROM `product` WHERE `status` = '1' AND `new_arrivals` = '1' ORDER BY `product_id` DESC");

		foreach ($output as &$product) {
			if (isset($product['price'])) {
				$product['price'] = $product['price'] * $currencyRate;
			}
		}

		return $output;
	}

	public function masonicProducts()
	{
		$conn = new dbClass;
		$this->conndb = $conn;

		$currencyRate = $_SESSION['currencyRates'] ?? 1;

		$output = $conn->getAllData("SELECT * FROM `product` WHERE `status` = '1' AND `home_visibility` = '1' AND `category_id` = '1' ORDER BY `product_id` DESC");

		foreach ($output as &$product) {
			if (isset($product['price'])) {
				$product['price'] = $product['price'] * $currencyRate;
			}
		}

		return $output;
	}



	public function urnsProducts()
	{
		$conn = new dbClass;
		$this->conndb = $conn;

		$currencyRate = $_SESSION['currencyRates'] ?? 1;

		$output = $conn->getAllData("SELECT * FROM `product` WHERE `status` = '1' AND `home_visibility` = '1' AND `category_id` = '3' ORDER BY `product_id` DESC");

		foreach ($output as &$product) {
			if (isset($product['price'])) {
				$product['price'] = $product['price'] * $currencyRate;
			}
		}

		return $output;
	}

	public function knitProducts()
	{
		$conn = new dbClass;
		$this->conndb = $conn;

		$currencyRate = $_SESSION['currencyRates'] ?? 1;

		$output = $conn->getAllData("SELECT * FROM `product` WHERE `status` = '1' AND `home_visibility` = '1' AND `category_id` = '9' ORDER BY `product_id` DESC");

		foreach ($output as &$product) {
			if (isset($product['price'])) {
				$product['price'] = $product['price'] * $currencyRate;
			}
		}

		return $output;
	}

	public function getProdcutsById($productID)
	{
		$conn = new dbClass;
		$this->conndb = $conn;
		$this->productID = $productID;

		$currencyRate = $_SESSION['currencyRates'] ?? 1;

		$output = $conn->getData("SELECT * FROM `product` WHERE `status` = '1' AND `product_id` = '$productID'");

		if ($output) {
			if (isset($output['price'])) {
				$output['price'] = $output['price'] * $currencyRate;
			}

		}
		return $output;
	}

	public function getProdcutsImages($productID)
	{
		$conn = new dbClass;
		$this->productID = $productID;
		$this->conndb = $conn;


		$output = $conn->getAllData("SELECT * FROM `product_images` WHERE `product_id` = '$productID'");
		return $output;
	}

	public function prodcutsImageCount($productID)
	{
		$conn = new dbClass;
		$this->productID = $productID;
		$this->conndb = $conn;

		$output = $conn->getRowCount("SELECT image_id FROM `product_images` WHERE `product_id` = '$productID'");
		return $output;
	}

	public function getProductCategories($productID)
	{
		$conn = new dbClass;
		$this->productID = $productID;
		$this->conndb = $conn;

		$productID = intval($productID);

		$sql = "
			SELECT DISTINCT
				c.id AS category_id, 
				c.name AS category_name
			FROM product p 
			JOIN category c ON c.id = p.category_id
			WHERE p.product_id = $productID
		";

		$output = $conn->getAllData($sql);
		return $output;
	}

	public function getProductSubCategories($productID)
	{
		$conn = new dbClass;
		$this->productID = $productID;
		$this->conndb = $conn;

		$productID = intval($productID);

		$sql = "
			SELECT DISTINCT
				sc.id AS subcategory_id, 
				sc.category_id AS category_id, 
				sc.name AS subcategory_name
			FROM product p 
			JOIN sub_category sc ON sc.id = p.subcategory_id
			WHERE p.product_id = $productID
		";

		$output = $conn->getAllData($sql);
		return $output;
	}

	public function allOtherProduct($catID, $subCatID, $productID)
	{
		$conn = new dbClass;
		$this->catID = $catID;
		$this->subCatID = $subCatID;
		$this->productID = $productID;
		$this->conndb = $conn;

		$currencyRate = $_SESSION['currencyRates'] ?? 1;

		$stmt = $conn->getAllData("SELECT * FROM `product` WHERE `category_id` = '$catID' AND `subcategory_id` = '$subCatID' AND product_id != '$productID' ORDER BY product_id DESC");

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

	public function addContact($Name, $Phone, $Email, $Message)
	{
		try {
			$query = "INSERT INTO contact (name, phone, email, message) VALUES (:name, :phone, :email, :message)";
			$params = array(
				':name' => $Name,
				':phone' => $Phone,
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
	public function getAllCategoriesWithProducts()
	{
		try {
			$query = "
                SELECT DISTINCT c.*
                FROM `category` c
                INNER JOIN `product_categories` pc ON c.id = pc.category_id
                INNER JOIN `product` p ON pc.product_id = p.product_id
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
	public function getAllSubCategoriesWithProducts()
	{
		try {
			$query = "
                SELECT DISTINCT c.*
                FROM `sub_category` c
                INNER JOIN `product_subcategories` pc ON c.id = pc.subcategory_id
                INNER JOIN `product` p ON pc.product_id = p.product_id
                WHERE c.status = 1 AND p.status = 1
                ORDER BY c.name ASC
            ";
			$stmt = $this->conndb->getAllData($query);
			return $stmt ?: []; // Return empty array if no results
		} catch (Exception $e) {
			// Log error in production; for now, return empty array
			return [];
		}
	}
	// function getCategoryBySub($id){
	// 	try {
	//         $query = "SELECT * FROM category where id='$id'";
	//         $stmt = $this->conndb->getAllData($query);
	//         return $stmt ?: []; // Return empty array if no results
	//     } catch (Exception $e) {
	//         // Log error in production; for now, return empty array
	//         return [];
	//     }
	// }

	// Get subcategories for a specific category with active products
	public function getSubCategoriesWithProducts($category_id)
	{
		try {
			// Validate input
			if (!is_numeric($category_id) || $category_id <= 0) {
				return [];
			}

			// Use prepared statement (assuming dbClass supports it)
			$query = "
                SELECT DISTINCT sc.*
                FROM `sub_category` sc
                INNER JOIN `product_subcategories` psc ON sc.id = psc.subcategory_id
                INNER JOIN `product` p ON psc.product_id = p.product_id
                WHERE sc.category_id = '$category_id' AND p.status = 1
                ORDER BY sc.name ASC
            ";
			// Simulate prepared statement (adjust based on dbClass implementation)
			$stmt = $this->conndb->getAllData($query);
			return $stmt ?: [];
		} catch (Exception $e) {
			return [];
		}
	}

	// Get sub-subcategories for a specific category and subcategory with active products
	public function getSubSubCategoriesWithProducts($category_id, $subcategory_id)
	{
		try {
			// Validate inputs
			if (!is_numeric($category_id) || !is_numeric($subcategory_id) || $category_id <= 0 || $subcategory_id <= 0) {
				return [];
			}

			$query = "
                SELECT DISTINCT ssc.*
                FROM `sub_sub_category` ssc
                INNER JOIN `product_subsubcategories` pssc ON ssc.id = pssc.subsubcategory_id
                INNER JOIN `product` p ON pssc.product_id = p.product_id
                WHERE ssc.category_id = '$category_id' AND ssc.sub_category_id = '$subcategory_id' 
                AND p.status = 1
                ORDER BY ssc.name ASC
            ";
			$stmt = $this->conndb->getAllData($query);
			return $stmt ?: [];
		} catch (Exception $e) {
			return [];
		}
	}
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

// class ShopPage
// {
// 	private $conn;
// 	private $common;
// 	private $products_per_page = 12;

// 	public function __construct()
// 	{
// 		$this->conn = new dbClass();
// 		$this->common = new CommProducts();
// 	}

// 	// Build the main product query with filters
// 	public function buildProductQuery($params)
// 	{
// 		// Decode base64-encoded IDs
// 		$category_id = isset($params['cid']) ? (int) base64_decode($params['cid']) : 0;
// 		$subcategory_id = isset($params['scid']) ? (int) base64_decode($params['scid']) : 0;
// 		$subsubcategory_id = isset($params['sscid']) ? (int) base64_decode($params['sscid']) : 0;
// 		$price_min = isset($params['price_min']) ? (int) $params['price_min'] : 0;
// 		$price_max = isset($params['price_max']) ? (int) $params['price_max'] : 25000;
// 		$sort_by = isset($params['sort']) ? $params['sort'] : 'popular';
// 		$colors = isset($params['colors']) && is_array($params['colors']) ? array_map('trim', $params['colors']) : [];
// 		$materials = isset($params['materials']) && is_array($params['materials']) ? array_map('trim', $params['materials']) : [];
// 		$current_page = isset($params['page']) && is_numeric($params['page']) && $params['page'] > 0 ? (int) $params['page'] : 1;
// 		$new_arrivals = isset($params['new']) && $params['new'] == 1 ? 1 : 0;
// 		$bestsellers = isset($params['bestseller']) && $params['bestseller'] == 1 ? 1 : 0;

// 		// Base query
// 		// Initialize base query (FROM only — joins will be added dynamically)
// 		$sql = "SELECT DISTINCT p.*, 
//             CASE 
//                 WHEN p.discount > 0 THEN p.price - (p.price * p.discount / 100) 
//                 ELSE p.price 
//             END as final_price
//             FROM product p";

// 		$count_sql = "SELECT COUNT(DISTINCT p.product_id) as total FROM product p";

// 		$conditions = [];
// 		$joins = [];

// 		// Always join product_categories and filter category


// 		// Add category/subcategory joins and conditions
// 		if ($subsubcategory_id > 0) {
// 			$joins[] = "INNER JOIN product_subsubcategories pss ON p.product_id = pss.product_id";
// 			$conditions[] = "pss.subsubcategory_id = $subsubcategory_id";
// 		} elseif ($subcategory_id > 0) {
// 			$joins[] = "INNER JOIN product_subcategories ps ON p.product_id = ps.product_id";
// 			$conditions[] = "ps.subcategory_id = $subcategory_id";
// 		} elseif ($category_id > 0 && $subcategory_id != '') {
// 			$joins[] = "INNER JOIN product_subcategories ps ON p.product_id = ps.product_id";
// 			$joins[] = "INNER JOIN sub_category sc ON ps.subcategory_id = sc.id";
// 			$conditions[] = "sc.category_id = $category_id";
// 		}elseif($category_id != '') {
// 			$joins[] = "INNER JOIN product_categories pc ON p.product_id = pc.product_id";
// 			$conditions[] = "pc.category_id = $category_id";
// 		}

// 		// Add new arrivals or bestsellers filter
// 		if ($new_arrivals) {
// 			$conditions[] = "p.new_arrivals = 1";
// 		} elseif ($bestsellers) {
// 			$conditions[] = "p.best_sellers = 1";
// 		}

// 		// Add price filter
// 		if ($price_min > 0 || $price_max < 25000) {
// 			$conditions[] = "CASE WHEN p.discount > 0 THEN p.price - (p.price * p.discount / 100) ELSE p.price END BETWEEN $price_min AND $price_max";
// 		}

// 		// Add color filter
// 		if (!empty($colors)) {
// 			$joins[] = "INNER JOIN product_colors pc ON p.product_id = pc.product_id";
// 			$colors_escaped = array_map(function ($color) {
// 				return "'" . $this->conn->addStr($color) . "'";
// 			}, $colors);
// 			$conditions[] = "pc.color IN (" . implode(',', $colors_escaped) . ")";
// 		}

// 		// Add material filter
// 		if (!empty($materials)) {
// 			$joins[] = "INNER JOIN product_materials pm ON p.product_id = pm.product_id";
// 			$materials_escaped = array_map(function ($material) {
// 				return "'" . $this->conn->addStr($material) . "'";
// 			}, $materials);
// 			$conditions[] = "pm.material IN (" . implode(',', $materials_escaped) . ")";
// 		}

// 		// Add status condition
// 		$conditions[] = "p.status = 1";

// 		// Combine query parts for count
// 		$count_sql .= " " . implode(" ", $joins);
// 		if (!empty($conditions)) {
// 			$count_sql .= " WHERE " . implode(" AND ", $conditions);
// 		}

// 		// Combine query parts for products
// 		if (!empty($joins)) {
// 			$sql .= " " . implode(" ", $joins);
// 		}
// 		if (!empty($conditions)) {
// 			$sql .= " WHERE " . implode(" AND ", $conditions);
// 		}

// 		// Add sorting
// 		switch ($sort_by) {
// 			case 'newest':
// 				$sql .= " ORDER BY p.created_at DESC";
// 				break;
// 			case 'price_low':
// 				$sql .= " ORDER BY final_price ASC";
// 				break;
// 			case 'price_high':
// 				$sql .= " ORDER BY final_price DESC";
// 				break;
// 			case 'name':
// 				$sql .= " ORDER BY p.name ASC";
// 				break;
// 			default:
// 				$sql .= " ORDER BY p.best_sellers DESC, p.featured DESC";
// 				break;
// 		}

// 		// Add pagination
// 		$offset = ($current_page - 1) * $this->products_per_page;
// 		$sql .= " LIMIT " . $this->products_per_page . " OFFSET $offset";

// 		return [
// 			'main_query' => $sql,
// 			'count_query' => $count_sql,
// 			'category_id' => $category_id,
// 			'subcategory_id' => $subcategory_id,
// 			'subsubcategory_id' => $subsubcategory_id,
// 			'price_min' => $price_min,
// 			'price_max' => $price_max,
// 			'sort_by' => $sort_by,
// 			'colors' => $colors,
// 			'materials' => $materials,
// 			'current_page' => $current_page,
// 			'new_arrivals' => $new_arrivals,
// 			'bestsellers' => $bestsellers
// 		];
// 	}

// 	// Get total products for pagination
// 	public function getTotalProducts($count_query)
// 	{
// 		return $this->conn->getData($count_query)['total'];
// 	}

// 	// Get products for the current page
// 	public function getProducts($main_query)
// 	{
// 		return $this->conn->getAllData($main_query);
// 	}

// 	// Get category information for breadcrumb
// 	public function getCategoryInfo($category_id, $subcategory_id, $subsubcategory_id)
// 	{
// 		$category_info = [];
// 		if ($category_id > 0) {
// 			$cat_sql = "SELECT name FROM category WHERE id = $category_id";
// 			$category_info['category'] = $this->conn->getData($cat_sql);
// 		}
// 		if ($subcategory_id > 0) {
// 			$sub_sql = "SELECT name FROM sub_category WHERE id = $subcategory_id";
// 			$category_info['subcategory'] = $this->conn->getData($sub_sql);
// 		}
// 		if ($subsubcategory_id > 0) {
// 			$subsub_sql = "SELECT name FROM sub_sub_category WHERE id = $subsubcategory_id";
// 			$category_info['subsubcategory'] = $this->conn->getData($subsub_sql);
// 		}
// 		return $category_info;
// 	}

// 	// Get categories with products
// 	public function getCategories()
// 	{
// 		$categories_sql = "SELECT DISTINCT c.* 
//                          FROM category c
//                          INNER JOIN sub_category sc ON c.id = sc.category_id
//                          INNER JOIN product_subcategories ps ON sc.id = ps.subcategory_id
//                          INNER JOIN product p ON ps.product_id = p.product_id
//                          WHERE c.status = 1 AND p.status = 1
//                          ORDER BY c.name";
// 		return $this->conn->getAllData($categories_sql);
// 	}

// 	// Get available colors for filter
// 	public function getAvailableColors()
// 	{
// 		$colors_sql = "SELECT DISTINCT pc.color 
//                       FROM product_colors pc 
//                       INNER JOIN product p ON pc.product_id = p.product_id 
//                       WHERE p.status = 1 
//                       ORDER BY pc.color";
// 		return $this->conn->getAllData($colors_sql);
// 	}

// 	// Get available materials for filter
// 	public function getAvailableMaterials()
// 	{
// 		$materials_sql = "SELECT DISTINCT pm.material 
//                         FROM product_materials pm 
//                         INNER JOIN product p ON pm.product_id = p.product_id 
//                         WHERE p.status = 1 
//                         ORDER BY pm.material";
// 		return $this->conn->getAllData($materials_sql);
// 	}

// 	// Get subcategories for a category
// 	public function getSubcategories($category_id)
// 	{
// 		$sub_sql = "SELECT DISTINCT sc.* 
//                    FROM sub_category sc
//                    INNER JOIN product_subcategories ps ON sc.id = ps.subcategory_id
//                    INNER JOIN product p ON ps.product_id = p.product_id
//                    WHERE sc.category_id = $category_id 
//                      AND sc.status = 1 
//                      AND p.status = 1
//                    ORDER BY sc.name";
// 		return $this->conn->getAllData($sub_sql);
// 	}

// 	// Get subsubcategories for a subcategory
// 	public function getSubsubcategories($subcategory_id)
// 	{
// 		$subsub_sql = "SELECT DISTINCT ssc.* 
//                       FROM sub_sub_category ssc
//                       INNER JOIN product_subsubcategories pss ON ssc.id = pss.subsubcategory_id
//                       INNER JOIN product p ON pss.product_id = p.product_id
//                       WHERE ssc.sub_category_id = $subcategory_id 
//                         AND ssc.status = 1 
//                         AND p.status = 1
//                       ORDER BY ssc.name";
// 		return $this->conn->getAllData($subsub_sql);
// 	}

// 	// Get product images
// 	public function getProductImages($product_id)
// 	{
// 		$img_sql = "SELECT image FROM product_images WHERE product_id = $product_id ORDER BY image_id LIMIT 2";
// 		return $this->conn->getAllData($img_sql);
// 	}

// 	// Calculate pagination details
// 	public function getPaginationDetails($total_products, $current_page)
// 	{
// 		$total_pages = ceil($total_products / $this->products_per_page);
// 		return [
// 			'total_pages' => $total_pages,
// 			'products_per_page' => $this->products_per_page
// 		];
// 	}
// }
class ShopPage
{
    private $conn;
    private $common;
    private $products_per_page = 12;

    public function __construct()
    {
        $this->conn = new dbClass();
        $this->common = new CommProducts();
    }

    public function buildProductQuery($params)
    {
        // Decode and validate input parameters
        $category_id = isset($params['cid']) ? (int)base64_decode($params['cid']) : 0;
        $subcategory_id = isset($params['scid']) ? (int)base64_decode($params['scid']) : 0;
        $subsubcategory_id = isset($params['sscid']) ? (int)base64_decode($params['sscid']) : 0;
        $price_min = isset($params['price_min']) ? (int)$params['price_min'] : 0;
        $price_max = isset($params['price_max']) ? (int)$params['price_max'] : 25000;
        $sort_by = isset($params['sort']) ? $params['sort'] : 'popular';
        $colors = isset($params['colors']) && is_array($params['colors']) ? array_map('trim', $params['colors']) : [];
        $materials = isset($params['materials']) && is_array($params['materials']) ? array_map('trim', $params['materials']) : [];
        $current_page = isset($params['page']) && is_numeric($params['page']) && $params['page'] > 0 ? (int)$params['page'] : 1;
        $new_arrivals = isset($params['new']) && $params['new'] == 1 ? 1 : 0;
        $bestsellers = isset($params['bestseller']) && $params['bestseller'] == 1 ? 1 : 0;

        // Handle price_range parameter
        if (isset($params['price_range']) && !empty($params['price_range'])) {
            $range = explode('-', $params['price_range']);
            if (count($range) === 2) {
                $price_min = (int)$range[0];
                $price_max = (int)$range[1];
            }
        }

        // Validate inputs
        if ($price_min < 0 || $price_max < 0 || $price_min > $price_max) {
            $price_min = 0;
            $price_max = 25000;
        }
        if (!in_array($sort_by, ['newest', 'price_low', 'price_high', 'name', 'popular'])) {
            $sort_by = 'popular';
        }

        // Initialize base query
        $sql = "SELECT DISTINCT p.*, 
                CASE 
                    WHEN p.discount > 0 THEN p.price - (p.price * p.discount / 100) 
                    ELSE p.price 
                END as final_price
                FROM product p";
        $count_sql = "SELECT COUNT(DISTINCT p.product_id) as total FROM product p";

        $joins = [];
        $conditions = [];
        $params_array = [];

        // Category/subcategory/subsubcategory filters
        if ($subsubcategory_id > 0) {
            $joins[] = "INNER JOIN product_subsubcategories pss ON p.product_id = pss.product_id";
            $conditions[] = "pss.subsubcategory_id = :subsubcategory_id";
            $params_array[':subsubcategory_id'] = $subsubcategory_id;
        } elseif ($subcategory_id > 0) {
            $joins[] = "INNER JOIN product_subcategories ps ON p.product_id = ps.product_id";
            $conditions[] = "ps.subcategory_id = :subcategory_id";
            $params_array[':subcategory_id'] = $subcategory_id;
        } elseif ($category_id > 0) {
            $joins[] = "INNER JOIN product_categories pcat ON p.product_id = pcat.product_id";
            $conditions[] = "pcat.category_id = :category_id";
            $params_array[':category_id'] = $category_id;
        }

        // New arrivals or bestsellers filter
        if ($new_arrivals) {
            $conditions[] = "p.new_arrivals = 1";
        } elseif ($bestsellers) {
            $conditions[] = "p.best_sellers = 1";
        }

        // Price filter
        if ($price_min > 0 || $price_max < 25000) {
            $conditions[] = "CASE WHEN p.discount > 0 THEN p.price - (p.price * p.discount / 100) ELSE p.price END BETWEEN :price_min AND :price_max";
            $params_array[':price_min'] = $price_min;
            $params_array[':price_max'] = $price_max;
        }

        // Color filter
        if (!empty($colors)) {
            $joins[] = "INNER JOIN product_colors pcol ON p.product_id = pcol.product_id";
            $color_placeholders = [];
            foreach ($colors as $index => $color) {
                $placeholder = ":color_$index";
                $color_placeholders[] = $placeholder;
                $params_array[$placeholder] = $color;
            }
            $conditions[] = "pcol.color IN (" . implode(',', $color_placeholders) . ")";
        }

        // Material filter
        if (!empty($materials)) {
            $joins[] = "INNER JOIN product_materials pm ON p.product_id = pm.product_id";
            $material_placeholders = [];
            foreach ($materials as $index => $material) {
                $placeholder = ":material_$index";
                $material_placeholders[] = $placeholder;
                $params_array[$placeholder] = $material;
            }
            $conditions[] = "pm.material IN (" . implode(',', $material_placeholders) . ")";
        }

        // Status condition
        $conditions[] = "p.status = 1";

        // Combine query parts
        if (!empty($joins)) {
            $sql .= " " . implode(" ", $joins);
            $count_sql .= " " . implode(" ", $joins);
        }
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
            $count_sql .= " WHERE " . implode(" AND ", $conditions);
        }

        // Add sorting
        switch ($sort_by) {
            case 'newest':
                $sql .= " ORDER BY p.created_at DESC";
                break;
            case 'price_low':
                $sql .= " ORDER BY final_price ASC";
                break;
            case 'price_high':
                $sql .= " ORDER BY final_price DESC";
                break;
            case 'name':
                $sql .= " ORDER BY p.name ASC";
                break;
            default:
                $sql .= " ORDER BY p.best_sellers DESC, p.featured DESC";
                break;
        }

        // Add pagination (concatenate integers directly)
        $limit = (int)$this->products_per_page;
        $offset = (int)(($current_page - 1) * $this->products_per_page);
        $sql .= " LIMIT $limit OFFSET $offset";

        return [
            'main_query' => $sql,
            'count_query' => $count_sql,
            'params' => $params_array,
            'category_id' => $category_id,
            'subcategory_id' => $subcategory_id,
            'subsubcategory_id' => $subsubcategory_id,
            'price_min' => $price_min,
            'price_max' => $price_max,
            'sort_by' => $sort_by,
            'colors' => $colors,
            'materials' => $materials,
            'current_page' => $current_page,
            'new_arrivals' => $new_arrivals,
            'bestsellers' => $bestsellers
        ];
    }

    public function getTotalProducts($count_query, $params)
    {
        $result = $this->conn->getDataWithParams($count_query, $params);
        return isset($result['total']) ? (int)$result['total'] : 0;
    }

    public function getProducts($main_query, $params)
    {
        return $this->conn->getAllDataWithParams($main_query, $params);
    }

    public function getCategoryInfo($category_id, $subcategory_id, $subsubcategory_id)
    {
        $category_info = [];
        if ($category_id > 0) {
            $cat_sql = "SELECT name FROM category WHERE id = :category_id";
            $category_info['category'] = $this->conn->getDataWithParams($cat_sql, [':category_id' => $category_id]);
        }
        if ($subcategory_id > 0) {
            $sub_sql = "SELECT name FROM sub_category WHERE id = :subcategory_id";
            $category_info['subcategory'] = $this->conn->getDataWithParams($sub_sql, [':subcategory_id' => $subcategory_id]);
        }
        if ($subsubcategory_id > 0) {
            $subsub_sql = "SELECT name FROM sub_sub_category WHERE id = :subsubcategory_id";
            $category_info['subsubcategory'] = $this->conn->getDataWithParams($subsub_sql, [':subsubcategory_id' => $subsubcategory_id]);
        }
        return $category_info;
    }

    public function getCategories()
    {
        $categories_sql = "SELECT DISTINCT c.* 
                         FROM category c
                         INNER JOIN sub_category sc ON c.id = sc.category_id
                         INNER JOIN product_subcategories ps ON sc.id = ps.subcategory_id
                         INNER JOIN product p ON ps.product_id = p.product_id
                         WHERE c.status = 1 AND p.status = 1
                         ORDER BY c.name";
        return $this->conn->getAllData($categories_sql);
    }

    public function getAvailableColors()
    {
        $colors_sql = "SELECT DISTINCT pcol.color 
                      FROM product_colors pcol 
                      INNER JOIN product p ON pcol.product_id = p.product_id 
                      WHERE p.status = 1 
                      ORDER BY pcol.color";
        return $this->conn->getAllData($colors_sql);
    }

    public function getAvailableMaterials()
    {
        $materials_sql = "SELECT DISTINCT pm.material 
                        FROM product_materials pm 
                        INNER JOIN product p ON pm.product_id = p.product_id 
                        WHERE p.status = 1 
                        ORDER BY pm.material";
        return $this->conn->getAllData($materials_sql);
    }

    public function getSubcategories($category_id)
    {
        $sub_sql = "SELECT DISTINCT sc.* 
                   FROM sub_category sc
                   INNER JOIN product_subcategories ps ON sc.id = ps.subcategory_id
                   INNER JOIN product p ON ps.product_id = p.product_id
                   WHERE sc.category_id = :category_id 
                     AND sc.status = 1 
                     AND p.status = 1
                   ORDER BY sc.name";
        return $this->conn->getAllDataWithParams($sub_sql, [':category_id' => $category_id]);
    }

    public function getSubsubcategories($subcategory_id)
    {
        $subsub_sql = "SELECT DISTINCT ssc.* 
                      FROM sub_sub_category ssc
                      INNER JOIN product_subsubcategories pss ON ssc.id = pss.subsubcategory_id
                      INNER JOIN product p ON pss.product_id = p.product_id
                      WHERE ssc.sub_category_id = :subcategory_id 
                        AND ssc.status = 1 
                        AND p.status = 1
                      ORDER BY ssc.name";
        return $this->conn->getAllDataWithParams($subsub_sql, [':subcategory_id' => $subcategory_id]);
    }

    public function getProductImages($product_id)
    {
        $img_sql = "SELECT image FROM product_images WHERE product_id = :product_id ORDER BY image_id LIMIT 2";
        return $this->conn->getAllDataWithParams($img_sql, [':product_id' => $product_id]);
    }

    public function getPaginationDetails($total_products, $current_page)
    {
        $total_pages = ceil($total_products / $this->products_per_page);
        return [
            'total_pages' => $total_pages,
            'products_per_page' => $this->products_per_page
        ];
    }
}

class Coupons
{
	private $ID;
	private $Name;
	private $Code;
	private $ExpiryDate;
	private $Description;
	private $DiscountPercentage; // Added for discount_percentage
	private $Status;
	private $conndb;





	function allCoupons()
	{
		$conn = new dbClass;
		$this->conndb = $conn;

		$stmt = $conn->getAllData("SELECT * FROM `coupon` ORDER BY `id` DESC");
		return $stmt;
	}

	function getCoupons($ID)
	{
		$conn = new dbClass;
		$this->ID = $ID;
		$this->conndb = $conn;

		$stmt = $conn->getData("SELECT * FROM `coupon` WHERE `id` = '$ID'");
		return $stmt;
	}

	function checkCoupons($Code, $type)
	{
		$conn = new dbClass;
		$this->Code = $Code;
		$this->conndb = $conn;

		$stmt = $conn->getData("SELECT COUNT(*) as count FROM `coupon` WHERE `code` = '$Code'");
		return $stmt['count'];
	}

	function checkCouponUsage($couponId, $userId)
	{
		$conn = new dbClass;
		$this->ID = $couponId;
		$this->conndb = $conn;
		$userId = $_SESSION['USER_LOGIN'];

		$stmt = $conn->getData("SELECT COUNT(*) as count FROM `coupon_usage` WHERE `coupon_id` = '$couponId' AND `user_id` = '$userId'");
		return $stmt['count'];
	}

	function recordCouponUsage($couponId, $userId): bool
	{
		$conn = new dbClass;
		$this->ID = $couponId;
		$this->conndb = $conn;

		$stmt = $conn->execute("INSERT INTO `coupon_usage` (`coupon_id`, `user_id`) VALUES ('$couponId', '$userId')");
		return $stmt;
	}

	function validateCoupon($code, $userId, $cartTotalAmt)
	{
		$conn = new dbClass;
		$this->Code = $code;
		$this->conndb = $conn;

		$code = $conn->addStr($code); // Sanitize input
		$couponData = $conn->getData("SELECT * FROM `coupon` WHERE `code` = '$code' AND `status` = 1 AND `expiry_date` > NOW()");

		if ($couponData) {
			$couponId = $couponData['id'];
			$minimum = $couponData['minimum'];

			$isUsed = $this->checkCouponUsage($couponId, $userId);
			if ($isUsed == 0) {
				if ($minimum <= $cartTotalAmt) {
					return ['valid' => true, 'coupon' => $couponData];

				} else {
					return ['valid' => false, 'error' => 'Please Add more Product To use this coupon.'];
				}
			} else {
				return ['valid' => false, 'error' => 'You have already used this coupon.'];
			}
		} else {
			return ['valid' => false, 'error' => 'Invalid or expired coupon code.'];
		}
	}
}
?>
<?php
// Assuming this is part of config/cart.php or a related file where ShopPage class is defined
class ShopPage1
{
	private $conn;

	public function __construct()
	{
		// Assuming $this->conn is initialized in config/config.php or elsewhere
		$this->conn = new dbClass(); // Replace with actual connection logic
	}

	public function buildProductQuery($params)
	{
		$category_id = isset($params['cid']) ? base64_decode($params['cid']) : null;
		$subcategory_id = isset($params['scid']) ? base64_decode($params['scid']) : null;
		$subsubcategory_id = isset($params['sscid']) ? base64_decode($params['sscid']) : null;
		$search_term = isset($params['search']) ? trim($params['search']) : '';
		$price_range = isset($params['price_range']) ? $params['price_range'] : '';
		$sort_by = isset($params['sort']) ? $params['sort'] : 'popular';
		$colors = isset($params['colors']) && is_array($params['colors']) ? $params['colors'] : [];
		$materials = isset($params['materials']) && is_array($params['materials']) ? $params['materials'] : [];
		$current_page = isset($params['page']) && is_numeric($params['page']) ? (int) $params['page'] : 1;
		$products_per_page = 9; // Assuming 9 products per page as per original pagination logic

		$price_min = 0;
		$price_max = 999999;
		if ($price_range) {
			list($price_min, $price_max) = explode('-', $price_range);
		} elseif (isset($params['price_min']) && isset($params['price_max'])) {
			$price_min = (int) $params['price_min'];
			$price_max = (int) $params['price_max'];
		}

		// Base query
		$main_query = "SELECT DISTINCT p.*
                       FROM product p
                       LEFT JOIN product_subcategories ps ON p.product_id = ps.product_id
                       LEFT JOIN sub_category sc ON ps.subcategory_id = sc.id
                       LEFT JOIN category c ON sc.category_id = c.id
                       LEFT JOIN sub_sub_category ssc ON ps.subcategory_id = ssc.id
                       LEFT JOIN product_colors pc ON p.product_id = pc.product_id
                       LEFT JOIN product_materials pm ON p.product_id = pm.product_id
                       WHERE p.status = 1";

		$count_query = "SELECT COUNT(DISTINCT p.product_id) as total
                        FROM product p
                        LEFT JOIN product_subcategories ps ON p.product_id = ps.product_id
                        LEFT JOIN sub_category sc ON ps.subcategory_id = sc.id
                        LEFT JOIN category c ON sc.category_id = c.id
                        LEFT JOIN sub_sub_category ssc ON ps.subcategory_id = ssc.id
                        LEFT JOIN product_colors pc ON p.product_id = pc.product_id
                        LEFT JOIN product_materials pm ON p.product_id = pm.product_id
                        WHERE p.status = 1";

		$conditions = [];

		// Search term condition
		if ($search_term) {
			// $search_term = $this->conn->escapeString($search_term); // Assuming escapeString method for SQL injection prevention
			$conditions[] = "(p.name LIKE '%$search_term%'
                             OR c.name LIKE '%$search_term%'
                             OR sc.name LIKE '%$search_term%'
                             OR ssc.name LIKE '%$search_term%')";
		}

		// Category filters
		if ($category_id) {
			$category_id = (int) $category_id;
			$conditions[] = "c.id = $category_id";
		}
		if ($subcategory_id) {
			$subcategory_id = (int) $subcategory_id;
			$conditions[] = "sc.id = $subcategory_id";
		}
		if ($subsubcategory_id) {
			$subsubcategory_id = (int) $subsubcategory_id;
			$conditions[] = "ssc.id = $subsubcategory_id";
		}

		// Price filter
		$conditions[] = "p.price BETWEEN $price_min AND $price_max";

		// Color filter
		if (!empty($colors)) {
			$color_conditions = [];
			foreach ($colors as $color) {
				// $color = $this->conn->escapeString($color);
				$color_conditions[] = "pc.color = '$color'";
			}
			$conditions[] = "(" . implode(" OR ", $color_conditions) . ")";
		}

		// Material filter
		if (!empty($materials)) {
			$material_conditions = [];
			foreach ($materials as $material) {
				// $material = $this->conn->escapeString($material);
				$material_conditions[] = "pm.material = '$material'";
			}
			$conditions[] = "(" . implode(" OR ", $material_conditions) . ")";
		}

		// Combine conditions
		if (!empty($conditions)) {
			$main_query .= " AND " . implode(" AND ", $conditions);
			$count_query .= " AND " . implode(" AND ", $conditions);
		}

		// Sorting
		switch ($sort_by) {
			case 'newest':
				$main_query .= " ORDER BY p.created_at DESC";
				break;
			case 'price_low':
				$main_query .= " ORDER BY p.price ASC";
				break;
			case 'price_high':
				$main_query .= " ORDER BY p.price DESC";
				break;
			case 'name':
				$main_query .= " ORDER BY p.name ASC";
				break;
			case 'popular':
			default:
				// $main_query .= " ORDER BY p.popularity DESC"; // Assuming popularity field exists
				break;
		}

		// Pagination
		$offset = ($current_page - 1) * $products_per_page;
		$main_query .= " LIMIT $products_per_page OFFSET $offset";

		return [
			'main_query' => $main_query,
			'count_query' => $count_query,
			'category_id' => $category_id,
			'subcategory_id' => $subcategory_id,
			'subsubcategory_id' => $subsubcategory_id,
			'price_min' => $price_min,
			'price_max' => $price_max,
			'sort_by' => $sort_by,
			'colors' => $colors,
			'materials' => $materials,
			'current_page' => $current_page,
			'search_term' => $search_term
		];
	}

	// Other methods (assumed to remain unchanged)
	public function getTotalProducts($count_query)
	{
		$result = $this->conn->getData($count_query);
		return $result['total'] ?? 0;
	}

	public function getPaginationDetails($total_products, $current_page)
	{
		$products_per_page = 9;
		$total_pages = ceil($total_products / $products_per_page);
		return [
			'total_pages' => $total_pages,
			'products_per_page' => $products_per_page
		];
	}

	public function getProducts($main_query)
	{
		return $this->conn->getAllData($main_query);
	}

	public function getCategoryInfo($category_id, $subcategory_id, $subsubcategory_id)
	{
		$category_info = [];
		if ($category_id) {
			$category = $this->conn->getData("SELECT * FROM category WHERE id = " . (int) $category_id);
			if ($category)
				$category_info['category'] = $category;
		}
		if ($subcategory_id) {
			$subcategory = $this->conn->getData("SELECT * FROM sub_category WHERE id = " . (int) $subcategory_id);
			if ($subcategory)
				$category_info['subcategory'] = $subcategory;
		}
		if ($subsubcategory_id) {
			$subsubcategory = $this->conn->getData("SELECT * FROM sub_sub_category WHERE id = " . (int) $subsubcategory_id);
			if ($subsubcategory)
				$category_info['subsubcategory'] = $subsubcategory;
		}
		return $category_info;
	}

	public function getCategories()
	{
		$categories_sql = "SELECT DISTINCT c.* 
                         FROM category c
                         INNER JOIN sub_category sc ON c.id = sc.category_id
                         INNER JOIN product_subcategories ps ON sc.id = ps.subcategory_id
                         INNER JOIN product p ON ps.product_id = p.product_id
                         WHERE c.status = 1 AND p.status = 1
                         ORDER BY c.name";
		return $this->conn->getAllData($categories_sql);
	}

	public function getSubcategories($category_id)
	{
		$sql = "SELECT DISTINCT sc.* 
                FROM sub_category sc
                INNER JOIN product_subcategories ps ON sc.id = ps.subcategory_id
                INNER JOIN product p ON ps.product_id = p.product_id
                WHERE sc.category_id = " . (int) $category_id . " AND sc.status = 1 AND p.status = 1
                ORDER BY sc.name";
		return $this->conn->getAllData($sql);
	}

	public function getSubsubcategories($subcategory_id)
	{
		$sql = "SELECT DISTINCT ssc.* 
                FROM sub_sub_category ssc
                INNER JOIN product_subcategories ps ON ssc.id = ps.subcategory_id 
                INNER JOIN product p ON ps.product_id = p.product_id
                WHERE ssc.subcategory_id = " . (int) $subcategory_id . " AND ssc.status = 1 AND p.status = 1
                ORDER BY ssc.name";
		return $this->conn->getAllData($sql);
	}

	public function getAvailableColors()
	{
		$sql = "SELECT DISTINCT color FROM product_colors ORDER BY color";
		return $this->conn->getAllData($sql);
	}

	public function getAvailableMaterials()
	{
		$sql = "SELECT DISTINCT material FROM product_materials ORDER BY material";
		return $this->conn->getAllData($sql);
	}

	public function getProductImages($product_id)
	{
		$sql = "SELECT * FROM product_images WHERE product_id = " . (int) $product_id . " ";
		return $this->conn->getAllData($sql);
	}
}
?>