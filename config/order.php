<?php 
class Order
{
	private $orderID;
	private $productID;
	private $payment_id;
	private $ShippingID;
	private $userId;
	private $conndb;


	// function orderItems($userId){  
	// 	$conn = new dbClass();
	// 	$this->userId = $userId;
		

	// 	$stmt = $conn->getAllData("SELECT 
    //         o.order_id, o.customer_id, o.product_price, o.product_quantity, o.product_total, o.created_at,
    //         s.address, s.state, s.postcode,
    //         p.name, p.product_id, p.image
    //     FROM orders_table o
    //     LEFT JOIN shipping_address s ON o.address_id = s.id
    //     LEFT JOIN product p ON o.product_id = p.product_id 
    //     WHERE o.customer_id = '$userId'
	// 	ORDER BY o.order_id DESC");
	// 	return $stmt;
	// }
	function orderItems($userId){  
		$conn = new dbClass();
		$this->userId = $userId;
		$stmt = $conn->getAllData("SELECT * FROM order_payment WHERE customer_id = '$userId' ORDER BY payment_id DESC");
		return $stmt;
	}
	function orderItemsById($payment_id){  
		$conn = new dbClass();
		$this->payment_id = $payment_id;
		$stmt = $conn->getData("SELECT * FROM order_payment WHERE payment_id = '$payment_id'");
		return $stmt;
	}

	// public function getOrderById($orderID){
	// 	$conn = new dbClass;
	// 	$this->conndb = $conn;
	// 	$this->orderID = $orderID;
	
	// 	$output = $conn->getData("SELECT * FROM `orders_table` WHERE `order_id` = '$orderID'");
	// 	return $output;
	// }
	public function getOrderById($payment_id){
		$conn = new dbClass;
		$this->conndb = $conn;
		$this->payment_id = $payment_id;
	
		$output = $conn->getAllData("SELECT * FROM `orders_table` WHERE `payment_id` = '$payment_id'");
		return $output;
	}

	public function getProductByOrderId($productID){
		$conn = new dbClass;
		$this->conndb = $conn;
		$this->productID = $productID;
	
		$output = $conn->getData("SELECT * FROM `product` WHERE `product_id` = '$productID'");
		return $output;
	}

	public function getShippingByOrderId($ShippingID){
		$conn = new dbClass;
		$this->conndb = $conn;
		$this->ShippingID = $ShippingID;
	
		$output = $conn->getData("SELECT * FROM `order_address` WHERE `id` = '$ShippingID'");
		return $output;
	}

}