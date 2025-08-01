<?php
// require 'config.php';
class Products
{
	private $Id;
	private $ID;
	private $Image;
	private $Name;
	private $Slug;
	private $Price;
	private $Discount;
	private $Stock;
	private $Sku;
	private $HSN;
	private $Pkgweight;
	private $Pkglength;
	private $Pkgwidth;
	private $Pkgheight;
	private $ShortDesc;
	private $Details;
	private $measurements;
	private $package_contains;
	private $trending;
	private $hotest_eyewear;
	private $Bestsellers;
	private $new_arrivals;
	private $Status;
	private $Table;
	private $size_large;
	private $size_medium;
	private $size_small;
	private $orderNumber;
	private $productId;
	private $productName;
	private $productPrice;
	private $remainingStock;
	private $productQuantity;
	private $discount;
	private $discount_type;
	private $mobile;
	private $name;
	private $conndb;

	function addProducts($frame_color, $frame_material , $gender , $prescription_type , $Image, $Name, $Slug, $Price, $Discount, $Stock, $Sku, $HSN, $Pkgweight, $Pkglength, $Pkgwidth, $Pkgheight, $ShortDesc, $Details, $measurements, $package_contains, $trending, $hotest_eyewear, $Status, $new_arrivals)
	{
		$conn = new dbClass;
		$this->Image = $Image;
		$this->Name = $Name;
		$this->Slug = $Slug;
		$this->Price = $Price;
		$this->Discount = $Discount;
		$this->Stock = $Stock;
		$this->Sku = $Sku;
		$this->HSN = $HSN;
		$this->Pkgheight = $Pkgheight;
		$this->Pkgwidth = $Pkgwidth;
		$this->Pkglength = $Pkglength;
		$this->Pkgweight = $Pkgweight;
		$this->ShortDesc = $ShortDesc;
		$this->Details = $Details;
		$this->measurements = $measurements;
		$this->package_contains = $package_contains;
		$this->trending = $trending;
		$this->hotest_eyewear = $hotest_eyewear;
		$this->Status = $Status;
		$this->new_arrivals = $new_arrivals;
		$this->conndb = $conn;

		// Modified INSERT query to include the new_arrivals field
		$stmt = $conn->execute("INSERT INTO `products`(`frame_color`, `frame_material` , `gender` , `prescription_type` ,`image`, `name`, `slug`, `price`, `discount`, `stock`, `sku`,`hsn`, `pkgweight`, `pkglength`, `pkgwidth`, `pkgheight`, `short_description`, `details`, `measurements`, `package_contains`, `trending`, `hotest_eyewear`, `status`, `new_arrivals`) 
								VALUES ('$frame_color', '$frame_material' , '$gender' , '$prescription_type' ,'$Image', '$Name', '$Slug', '$Price', '$Discount', '$Stock', '$Sku','$HSN', '$Pkgweight', '$Pkglength', '$Pkgwidth', '$Pkgheight', '$ShortDesc', '$Details', '$measurements', '$package_contains', '$trending', '$hotest_eyewear', '$Status', '$new_arrivals')");

		$productId = $conn->lastInsertId(); // Get the last inserted product ID
		return $productId;
	}



	function updateProducts($frame_color, $frame_material , $gender , $prescription_type , $Image, $Name, $Slug, $Price, $Discount, $Stock, $Sku, $HSN, $Pkgweight, $Pkglength, $Pkgwidth, $Pkgheight, $ShortDesc, $Details, $measurements, $package_contains, $trending, $hotest_eyewear, $Status, $new_arrivals, $Id)
	{
		$conn = new dbClass;
		$this->Id = $Id;
		$this->Image = $Image;
		$this->Name = $Name;
		$this->Slug = $Slug;
		$this->HSN = $HSN;
		$this->Pkgheight = $Pkgheight;
		$this->Pkgwidth = $Pkgwidth;
		$this->Pkglength = $Pkglength;
		$this->Pkgweight = $Pkgweight;
		$this->Price = $Price;
		$this->Discount = $Discount;
		$this->Stock = $Stock;
		$this->Sku = $Sku;
		$this->ShortDesc = $ShortDesc;
		$this->Details = $Details;
		$this->measurements = $measurements;
		$this->package_contains = $package_contains;
		$this->trending = $trending;
		$this->hotest_eyewear = $hotest_eyewear;
		$this->Status = $Status;
		$this->new_arrivals = $new_arrivals;
		$this->conndb = $conn;

		// Modify SQL query to update the new_arrivals field
		$stmt = $conn->execute("UPDATE `products` 
								SET `image`='$Image', `name`='$Name', `slug`='$Slug',`HSN`='$HSN', `pkgweight`='$Pkgweight', `pkglength`='$Pkglength', `pkgwidth`='$Pkgwidth', `pkgheight`='$Pkgheight', `price`='$Price', `discount`='$Discount', 
									`stock`='$Stock', `sku`='$Sku', `short_description`='$ShortDesc', `details`='$Details', 
									`measurements`='$measurements', `package_contains`='$package_contains', `trending`='$trending', 
									`hotest_eyewear`='$hotest_eyewear', `status`='$Status', `new_arrivals`='$new_arrivals', 
									`frame_color`='$frame_color', `frame_material`='$frame_material', `gender`='$gender', `prescription_type`='$prescription_type', 
									`updated_at` = NOW() 
								WHERE `product_id` = '$Id'");

		return $stmt;
	}


	function getProducts($Id)
	{
		$conn = new dbClass;
		$this->Id = $Id;
		$this->conndb = $conn;

		$stmt = $conn->getData("SELECT * FROM `products` WHERE `product_id` = '$Id'");
		return $stmt;
	}

	// function getProductsCategory($Id) 
	// {  
	// 	$conn = new dbClass;
	// 	$this->Id = $Id;
	// 	$this->conndb = $conn;

	// 	$stmt = $conn->getAllData("SELECT * FROM `product_category` WHERE `product_id` = '$Id'");
	// 	return $stmt;
	// }

	// function getProductsSubCategory($Id) 
	// {  
	// 	$conn = new dbClass;
	// 	$this->Id = $Id;
	// 	$this->conndb = $conn;

	// 	$stmt = $conn->getAllData("SELECT * FROM `product_subcategory` WHERE `product_id` = '$Id'");
	// 	return $stmt;
	// }

	function allProducts()
	{
		$conn = new dbClass;
		$this->conndb = $conn;

		$stmt = $conn->getAllData("SELECT * FROM `products` ORDER BY `name` ASC");
		return $stmt;
	}

	function allSkuProducts()
	{
		$conn = new dbClass;
		$this->conndb = $conn;

		$stmt = $conn->getAllData("SELECT sku FROM `products` ORDER BY `product_id` DESC");
		return $stmt;
	}

	function getProdcutsImages($Id)
	{
		$conn = new dbClass;
		$this->Id = $Id;
		$this->conndb = $conn;

		$output = $conn->getAllData("SELECT * FROM `products_images` WHERE `product_id` = '$Id'");
		return $output;
	}

	function prodcutsImageCount($Id)
	{
		$conn = new dbClass;
		$this->Id = $Id;
		$this->conndb = $conn;

		$output = $conn->getRowCount("SELECT image_id FROM `products_images` WHERE `product_id` = '$Id'");
		return $output;
	}

	function slug($Name, $Table)
	{
		$conn = new dbClass;
		$this->Name = $Name;
		$this->Table = $Table;
		$this->conndb = $conn;

		$slug = strtolower(trim(preg_replace("/[\s-]+/", "-", preg_replace("/[^a-zA-Z0-9\-]/", '-', addslashes($Name))), "-"));
		$count = $conn->getData("SELECT product_id FROM $Table WHERE `slug` = '" . addslashes($slug) . "'");
		$RowId = $count['product_id'];
		if (!empty($RowId)):
			$slug = strtolower(trim(preg_replace("/[\s-]+/", "-", preg_replace("/[^a-zA-Z0-9\-]/", '-', addslashes($Name . "-" . date('ymdis') . "-" . rand(0, 999)))), "-"));
		endif;
		return $slug;
	}

	function updateSlug($Name, $Table, $Id)
	{
		$conn = new dbClass;
		$this->Id = $Id;
		$this->Name = $Name;
		$this->Table = $Table;
		$this->conndb = $conn;

		$slug = strtolower(trim(preg_replace("/[\s-]+/", "-", preg_replace("/[^a-zA-Z0-9\-]/", '-', addslashes($Name))), "-"));
		$count = $conn->getData("SELECT product_id FROM $Table WHERE `slug` = '" . addslashes($slug) . "' AND product_id!='$Id'");
		// $RowId = $count['product_id'];
		$RowId = ($count !== false) ? $count['product_id'] : null;
		if (!empty($RowId)):
			$slug = strtolower(trim(preg_replace("/[\s-]+/", "-", preg_replace("/[^a-zA-Z0-9\-]/", '-', addslashes($Name . "-" . date('ymdis') . "-" . rand(0, 999)))), "-"));
		endif;
		return $slug;
	}


	function addOfflineOrder($name, $mobile, $discount_type, $discount,$customerGstn)
	{
		$conn = new dbClass;
		$this->name = $name;
		$this->mobile = $mobile;
		$this->discount_type = $discount_type;
		$this->discount = $discount;
		$this->conndb = $conn;
	
		// do {
		// 	$order_number = rand(1000000000, 9999999999);
		// 	$checkOrder = $conn->getData("SELECT COUNT(*) as count FROM `offline_order` WHERE `order_number` = '$order_number'");
		// } while ($checkOrder[0]['count'] > 0);
		$lastInvoice= $this->getLastInvoice();
		$OnlinelastInvoice= $this->getOnlineLastInvoice();

		$biggerNumber=$this->compareInvoices($lastInvoice,$OnlinelastInvoice);

		$order_number = $this->generateInvoiceNumber($biggerNumber);

	
		$stmt = $conn->execute("INSERT INTO `offline_order`(`order_number`, `name`, `mobile`, `discount_type`, `discount`, `customerGstn`)
								VALUES ('$order_number', '$name', '$mobile', '$discount_type', '$discount', '$customerGstn')");
	
		if ($stmt) {
			return $order_number;  
		} else {
			return false; 
		}
	}
	
	function addOfflineOrderProduct($orderNumber, $productId, $productName, $productPrice, $productQuantity, $remainingStock)
	{
		$conn = new dbClass;
		$this->orderNumber = $orderNumber;
		$this->productId = $productId;
		$this->productName = $productName;
		$this->productPrice = $productPrice;
		$this->productQuantity = $productQuantity;
		$this->remainingStock = $remainingStock;
		$this->conndb = $conn;
	
		$stmt = $conn->execute("INSERT INTO `offline_order_product`(`order_number`, `product_id`, `product_name`, `product_price`, `product_quantity`)
								VALUES ('$orderNumber', '$productId', '$productName', '$productPrice', '$productQuantity')");
	
		if ($stmt) {
			$updateStockStmt = $conn->execute("UPDATE `products` 
											   SET `stock` = '$remainingStock', `updated_at` = NOW() 
											   WHERE `product_id` = '$productId'");
			
			if ($updateStockStmt) {
				return true; 
			} else {
				return false; 
			}
		} else {
			return false; 
		}
	}
	


	function allOfflineProducts()
	{
		$conn = new dbClass;
		$this->conndb = $conn;

		$stmt = $conn->getAllData("SELECT * FROM `offline_order` ORDER BY `offline_order_id` DESC");
		return $stmt;
	}
	function allOfflineProductsDtls($ID)
	{
		$conn = new dbClass;
		$this->ID = $ID;
		$this->conndb = $conn;

		$stmt = $conn->getAllData("SELECT * FROM `offline_order` 
		JOIN offline_order_product ON offline_order_product.order_number = offline_order.order_number
		WHERE offline_order.offline_order_id = '$ID'");
		return $stmt;
	}
	public function getInvoiceForOnlineOrders() {
		$conn = new dbClass;
		$this->conndb = $conn;
		
		$lastInvoice= $this->getLastInvoice();
		$OnlinelastInvoice= $this->getOnlineLastInvoice();

		$biggerNumber=$this->compareInvoices($lastInvoice,$OnlinelastInvoice);
		$order_number = $this->generateInvoiceNumber($biggerNumber);
		
		return $order_number;
	}
	public function getBiggerLastInvoice() {
		$conn = new dbClass;
		$this->conndb = $conn;
		
		$lastInvoice= $this->getLastInvoice();
		$OnlinelastInvoice= $this->getOnlineLastInvoice();

		$biggerNumber=$this->compareInvoices($lastInvoice,$OnlinelastInvoice);
		return $biggerNumber;
	}
	public function getLastInvoice() {
		$conn = new dbClass;
		$this->conndb = $conn;
		
		// Query to select products marked as new arrivals
		$output = $conn->getData("SELECT `order_number` FROM `offline_order` ORDER BY `offline_order_id` DESC");
		$order_number=$output['order_number'];
		return $order_number;
	}
	public function getOnlineLastInvoice() {
		$conn = new dbClass;
		$this->conndb = $conn;
		
		// Query to select products marked as new arrivals
		$output = $conn->getData("SELECT `invoice_number` FROM `orders_table` ORDER BY `order_id` DESC");
		$invoice_number=$output['invoice_number'];
		return $invoice_number;
	}
	function generateInvoiceNumber($lastInvoice, $initials = 'PO') {
		// Get current year and next year in 2-digit format
		$currentYearShort = date('y'); // e.g. "25"
		$nextYearShort = date('y', strtotime('+1 year')); // e.g. "26"
	
		// Create year range string
		$yearRange = $currentYearShort . '-' . $nextYearShort;
	
		$newSeq = 1;
	
		if ($lastInvoice) {
			// Split the last invoice by '/'
			$parts = explode('/', $lastInvoice);
	
			if (count($parts) === 3) {
				$lastYearRange = $parts[1];
				$lastSeq = (int)$parts[2];
	
				// If the year range matches, increment sequence
				if ($lastYearRange === $yearRange) {
					$newSeq = $lastSeq + 1;
				}
			}
		}
	
		// Pad sequence to 4 digits
		$newSeqStr = str_pad($newSeq, 4, '0', STR_PAD_LEFT);
	
		// Return the formatted invoice number
		return $initials . '/' . $yearRange . '/' . $newSeqStr;
	}
	function compareInvoices($invoice1, $invoice2) {
		// Helper to extract sequence number safely
		$getSequence = function($invoice) {
			$parts = explode('/', $invoice);
			if (count($parts) === 3 && is_numeric($parts[2])) {
				return (int)$parts[2];
			}
			return false; // Invalid format
		};
	
		$seq1 = $getSequence($invoice1);
		$seq2 = $getSequence($invoice2);
	
		// Case: both are invalid
		if ($seq1 === false && $seq2 === false) {
			return 0;
		}
	
		// Case: only one is valid
		if ($seq1 !== false && $seq2 === false) {
			return $invoice1;
		}
		if ($seq2 !== false && $seq1 === false) {
			return $invoice2;
		}
	
		// Case: both are valid
		if ($seq1 > $seq2) {
			return $invoice1;
		} elseif ($seq2 > $seq1) {
			return $invoice2;
		} else {
			// Sequences are equal, return either
			return $invoice1;
		}
	}
	
	

	





}
?>