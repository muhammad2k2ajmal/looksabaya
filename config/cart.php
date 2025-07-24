<?php

class Cart
{
	private $cartId;
	private $userId;
	private $ProductId;
	private $Quantity;
	private $IpAddress;
	private $conndb;

	function cartCount($userId, $IpAddress)
	{
		$conn = new dbClass();
		$this->userId = $userId;
		$this->IpAddress = $IpAddress;

		if (isset($_SESSION['USER_LOGIN'])) {
			$customerId = $_SESSION['USER_LOGIN'];
			$stmt = $conn->getData("SELECT COUNT(*) AS CartCount FROM cart WHERE `customer_id` = '$customerId'  AND `type`='cart'");
		} else {
			$stmt = $conn->getData("SELECT COUNT(*) AS CartCount FROM cart WHERE `user_id` = '$userId' AND `type`='cart' AND `insert_ip` = '$IpAddress' AND `customer_id` IS NULL");
		}

		
		return $stmt;
	}

	function cartItems($userId, $IpAddress)
	{
		$conn = new dbClass();
		$this->userId = $userId;
		$this->IpAddress = $IpAddress;

		$deleteStmt = $conn->execute("
        DELETE c 
        FROM cart c 
        LEFT JOIN product p ON c.product_id = p.product_id 
        WHERE p.product_id IS NULL AND c.type = 'cart'
    ");

		if (isset($_SESSION['USER_LOGIN'])) {
			$customerId = $_SESSION['USER_LOGIN'];
			//cart
			$stmt = $conn->getAllData("SELECT * FROM cart WHERE `customer_id` = '$customerId' AND `type`='cart'");
		} else {
			//cart
			$stmt = $conn->getAllData("SELECT * FROM cart WHERE user_id = '$userId' AND insert_ip = '$IpAddress' AND `type`='cart' AND `customer_id` IS NULL");
		}

		
		return $stmt;
	}
	
	function buyNowItems($userId, $IpAddress)
	{
		$conn = new dbClass();
		$this->userId = $userId;
		$this->IpAddress = $IpAddress;

		if (isset($_SESSION['USER_LOGIN'])) {
			$customerId = $_SESSION['USER_LOGIN'];
			//buy_now
			$stmt = $conn->getAllData("SELECT * FROM cart WHERE `customer_id` = '$customerId' AND `type`='buyNow'");
		} else {
			//buy_now
			$stmt = $conn->getAllData("SELECT * FROM cart WHERE user_id = '$userId' AND insert_ip = '$IpAddress' AND `type`='buyNow' AND `customer_id` IS NULL");
		}

		
		return $stmt;
	}

	function cartNumRows($userId, $IpAddress)
	{
		$conn = new dbClass();
		$this->userId = $userId;
		$this->IpAddress = $IpAddress;

		if (isset($_SESSION['USER_LOGIN'])) {
			$customerId = $_SESSION['USER_LOGIN'];
			$stmt = $conn->getRowCount("SELECT cart_id FROM cart WHERE `customer_id` = '$customerId'");
		} else {
			$stmt = $conn->getRowCount("SELECT cart_id FROM cart WHERE user_id = '$userId' AND insert_ip = '$IpAddress' AND `customer_id` IS NULL");
		}

		
		return $stmt;
	}

	function cartCheck($userId, $ProductId, $IpAddress)
	{
		$conn = new dbClass();
		$this->userId = $userId;
		$this->ProductId = $ProductId;
		$this->IpAddress = $IpAddress;

		if (isset($_SESSION['USER_LOGIN'])) {
			$customerId = $_SESSION['USER_LOGIN'];
			$stmt = $conn->getData("SELECT * FROM cart WHERE `product_id` = '$ProductId' AND `customer_id` = '$customerId'  AND `type`='cart'");
		} else {
			$stmt = $conn->getData("SELECT * FROM cart WHERE `product_id` = '$ProductId' AND `user_id` = '$userId' AND `type`='cart' AND `insert_ip` = '$IpAddress' AND `customer_id` IS NULL");

		}

		
		return $stmt;
	}

	function getProductsDetail($ProductId)
	{
		$conn = new dbClass;
		$this->ProductId = $ProductId;
		$this->conndb = $conn;

		$stmt = $conn->getData("SELECT * FROM `products` WHERE `product_id` = '$ProductId'");
		return $stmt;
	}

	// function addCartItem($userId,$ProductId,$Quantity,$IpAddress) 
	// {  
	// 	$conn = new dbClass;
	// 	$this->userId = $userId;
	// 	$this->ProductId = $ProductId;
	// 	$this->Quantity = $Quantity;
	// 	$this->IpAddress = $IpAddress;
	// 	$this->conndb = $conn;

	// 	$stmt = $conn->execute("INSERT INTO `cart`(`user_id`, `product_id`, `quantity`, `insert_ip`) VALUES ('$userId', '$ProductId', '$Quantity', '$IpAddress')");
	// 	return $stmt;
	// }

	function addCartItem($userId, $ProductId, $Quantity, $IpAddress)
	{
		$conn = new dbClass;
		$this->userId = $userId;
		$this->ProductId = $ProductId;
		$this->Quantity = $Quantity;
		$this->IpAddress = $IpAddress;
		$this->conndb = $conn;

		if (isset($_SESSION['USER_LOGIN'])) {
			$customerId = $_SESSION['USER_LOGIN'];
			//cart
			$stmt = $conn->execute("INSERT INTO cart(user_id, product_id, quantity, insert_ip, customer_id, type) 
									VALUES ('$userId', '$ProductId', '$Quantity', '$IpAddress', '$customerId','cart')");
		} else {
			//cart
			$stmt = $conn->execute("INSERT INTO cart(user_id, product_id, quantity, insert_ip, type) 
									VALUES ('$userId', '$ProductId', '$Quantity', '$IpAddress', 'cart')");
		}

		return $stmt;
	}
	function addBuyNowItem($userId, $ProductId, $Quantity, $IpAddress)
	{
		$conn = new dbClass;
		$this->userId = $userId;
		$this->ProductId = $ProductId;
		$this->Quantity = $Quantity;
		$this->IpAddress = $IpAddress;
		$this->conndb = $conn;

		if (isset($_SESSION['USER_LOGIN'])) {
			$customerId = $_SESSION['USER_LOGIN'];
			//buy_now
			$conn->execute("DELETE from `cart` where `customer_id` = '$customerId' AND `type`='buyNow' ");
			//buy_now
			$stmt = $conn->execute("INSERT INTO cart(user_id, product_id, quantity, insert_ip, customer_id,type) 
									VALUES ('$userId', '$ProductId', '$Quantity', '$IpAddress', '$customerId', 'buyNow')");
		} else {
			//buy_now
			$conn->execute("DELETE  from `cart` where `insert_ip` = '$IpAddress' AND `type`='buyNow' ");
			//buy_now
			$stmt = $conn->execute("INSERT INTO cart(user_id, product_id, quantity, insert_ip,type) 
									VALUES ('$userId', '$ProductId', '$Quantity', '$IpAddress', 'buyNow')");
		}

		return $stmt;
	}


	function updateCartItem($userId, $ProductId, $Quantity, $IpAddress, $cartId)
	{
		$conn = new dbClass;
		$this->userId = $userId;
		$this->ProductId = $ProductId;
		$this->Quantity = $Quantity;
		$this->IpAddress = $IpAddress;
		$this->cartId = $cartId;
		$this->conndb = $conn;

			$stmt = $conn->execute("UPDATE `cart` SET `user_id` = '$userId', `product_id` = '$ProductId', `quantity` = '$Quantity', `insert_ip` = '$IpAddress', `updated_at` = NOW() WHERE `cart_id` = '$cartId'");
		return $stmt;
		
	}


	function updateCartItem123($userId, $ProductId, $Quantity, $cartId)
	{
		$conn = new dbClass;
		$this->ProductId = $ProductId;
		$this->Quantity = $Quantity;
		$this->cartId = $cartId;
		$this->conndb = $conn;

			$stmt = $conn->execute("UPDATE `cart` SET `user_id` = '$userId', `product_id` = '$ProductId', `quantity` = '$Quantity',  `updated_at` = NOW() WHERE `cart_id` = '$cartId'");
		return $stmt;
	}

	function removeCartItem($userId, $cartId, $ProductId, $IpAddress)
	{
		$conn = new dbClass;
		$this->userId = $userId;
		$this->cartId = $cartId;
		$this->ProductId = $ProductId;
		$this->IpAddress = $IpAddress;
		$this->conndb = $conn;

		if (isset($_SESSION['USER_LOGIN'])) {
			$customerId = $_SESSION['USER_LOGIN'];
			$stmt = $conn->execute("DELETE FROM `cart` WHERE cart_id = '$cartId' AND product_id = '$ProductId' AND `customer_id` = '$customerId'");
		} else {
			$stmt = $conn->execute("DELETE FROM `cart` WHERE cart_id = '$cartId' AND product_id = '$ProductId' AND (user_id = '$userId' OR insert_ip = '$IpAddress')");
		return $stmt;
		}


		return $stmt;
	}
	function decreaseCartItembecauseofstock($userId, $cartId, $ProductId, $IpAddress,$quantity)
	{
		$conn = new dbClass;
		$this->userId = $userId;
		$this->cartId = $cartId;
		$this->ProductId = $ProductId;
		$this->IpAddress = $IpAddress;
		$this->conndb = $conn;

		if (isset($_SESSION['USER_LOGIN'])) {
			$customerId = $_SESSION['USER_LOGIN'];
			$stmt = $conn->execute("UPDATE `cart` SET `quantity`='$quantity' WHERE cart_id = '$cartId' AND product_id = '$ProductId' AND `customer_id` = '$customerId'");
		} else {
			$stmt = $conn->execute("UPDATE `cart` SET `quantity`='$quantity' WHERE cart_id = '$cartId' AND product_id = '$ProductId' AND (user_id = '$userId' OR insert_ip = '$IpAddress')");
		return $stmt;
		}


		return $stmt;
	}
	// function checkStock($ProductId){
		
	// 	$conn = new dbClass;
	// 	$this->ProductId = $ProductId;
	// 	$stmt = $conn->getData("SELECT * from products where product_id='$ProductId'");
	// 	return $stmt;
	// }
}

?>