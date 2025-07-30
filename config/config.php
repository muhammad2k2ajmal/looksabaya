<?php

class dbClass
{

	private $host;
	private $user;
	private $pass;
	private $dbname;
	private $conn;
	private $error;

	public function __construct()
	{
		$this->connect();
	}

	public function __destruct()
	{
		$this->disconnect();
	}

	private function connect()
	{
		// $this->host = 'localhost';
		// $this->dbname = 'abayalooks_db';
		// $this->user = 'abayalooks_db';
		// $this->pass = 'JCacE3uZBdKuK5ZEKEuK';
		$this->host = 'localhost';
		$this->dbname = 'abaya_db';
		$this->user = 'root';
		$this->pass = '';

		// $this->host = 'localhost';		
		// $this->dbname = 'dotcomwala_look_db';
        // $this->user = 'dotcomwala_look_db';
        // $this->pass = 'm7CBc7Kpf8XrqRsbHX7U';

		try {

			$this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->dbname . '', $this->user, $this->pass);

			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		} catch (PDOException $e) {
			echo "Error: " . $e->getMessage();
		}

		if (!$this->conn) {
			$this->error = 'Fatal Error :' . $e->getMessage();
		}

		return $this->conn;

	}

	public function disconnect()
	{
		if ($this->conn) {
			$this->conn = null;
		}
	}

	public function getData($query)
	{
		$result = $this->conn->prepare($query);
		$query = $result->execute();
		if ($query == false) {
			echo 'Error SQL: ' . $query;
			die();
		}

		$result->setFetchMode(PDO::FETCH_ASSOC);
		$reponse = $result->fetch();

		return $reponse;
	}

	public function getAllData($query)
	{
		$result = $this->conn->prepare($query);
		$ret = $result->execute();
		if (!$ret) {
			echo 'Error SQL: ' . $ret;
			die();
		}
		$result->setFetchMode(PDO::FETCH_ASSOC);
		$reponse = $result->fetchAll();

		return $reponse;
	}

	public function getRowCount($query)
	{
		$result = $this->conn->prepare($query);
		$ret = $result->execute();
		if (!$ret) {
			return false;
		}
		$reponse = $result->rowCount();

		return $reponse;
	}

	public function getProductCount($query)
	{
		$result = $this->conn->prepare($query);
		$ret = $result->execute();
		if (!$ret) {
			return false;
		}
		
		// Fetch the count from the result
		$count = $result->fetchColumn();
		return $count !== false ? (int)$count : false; // Return count as an integer
	}

	public function execute($query)
	{
		$response = $this->conn->exec($query);

		if ($response == false)
			return false;
		else
			return true;
	}

	public function updateExecute($query)
	{
		$response = $this->conn->exec($query);

		if ($response == false)
			return false;
		else
			return true;
	}

	public function executeStatement($query, $params = []) {
		$statement = $this->conn->prepare($query);
		
		foreach ($params as $key => &$value) {
			$statement->bindParam($key, $value);
		}
		
		return $statement->execute();
	}

	public function getDataWithParams($query, $params = []) {
		$result = $this->conn->prepare($query);
		
		foreach ($params as $key => &$value) {
			$result->bindParam($key, $value);
		}
		
		$query = $result->execute();
		if ($query == false) {
		   echo 'Error SQL: '.$query;
		   die();
		}
		
		$result->setFetchMode(PDO::FETCH_ASSOC);
		$response = $result->fetch();
		
		return $response;
	}
	public function getAllDataWithParams($query, $params = []) {
    $result = $this->conn->prepare($query);

    foreach ($params as $key => &$value) {
        $result->bindParam($key, $value);
    }

    $execution = $result->execute();
    if ($execution === false) {
        echo 'Error SQL: ';
        print_r($result->errorInfo());
        die();
    }

    $result->setFetchMode(PDO::FETCH_ASSOC);
    $response = $result->fetchAll();

    return $response;
}


	public function addStr($val)
	{
	    if (is_array($val)) {
	        // If the input is an array, recursively process each element
	        foreach ($val as &$element) {
	            $element = $this->addStr($element);
	        }
	        return $val;
	    }

	    // For string input, perform addslashes and trim
	    return addslashes(trim($val));
	}


	public function removeStr($val)
	{
		$res = stripslashes(trim($val));
		return $res;
	}

	public function lastInsertId()
	{

		$res = $this->conn->lastInsertId();

		return $res;
	}

	public function slug($string)
	{
		$slug = strtolower(trim(preg_replace("/[\s-]+/", "-", preg_replace("/[^a-zA-Z0-9\-]/", '-', addslashes($string))), "-"));
		return $slug;
	}

	public function webSettings($id)
	{

		$result = $this->getData("SELECT * FROM `website_settings` WHERE `id` = '$id'");
		return $result;

	}
	public function states()
	{
		$indian_all_states = $this->getAllData("SELECT `id`, `name` FROM `states` WHERE `country_id` = '101' ORDER BY `name` ASC");
		return $indian_all_states;
	}
	public function cities($id)
	{
		$indian_all_cities = $this->getAllData("SELECT `id`, `name` FROM `cities` WHERE `country_id` = '101' AND `state_id` = '$id' ORDER BY `name` ASC");
		return $indian_all_cities;
	}
	public function stateName($id)
	{
		$result = $this->getData("SELECT `name` FROM `states` WHERE `id` = '$id'");
		return $result ? $result['name'] : '';
	}
	public function cityName($id)
	{
		$result = $this->getData("SELECT `name` FROM `cities` WHERE `id` = '$id'");
		return $result ? $result['name'] : ''; 
	}
}

// --- get user detail -- // 
// class userDetail
// {

// 	public $id;
// 	public $conndb;

// 	// --- Get Login User detail --- //
// 	public function loginUserDetail($id)
// 	{

// 		$conn = new dbClass;
// 		$this->conndb = $conn;
// 		$this->id = $id;

// 		$result = $conn->getData("SELECT * FROM tbl_admin WHERE id = '$id'");

// 		return $result;

// 	}

// }

// --- Check Session Value --- //

// class LoginSession
// {

// 	private $session;

// 	public function checkSession($session)
// 	{
// 		$this->session = $session;

// 		if (empty($session))
// 			echo "<script>window.location.href='index.php'</script>";

// 	}

// }

date_default_timezone_set("Asia/Kolkata");
$dateTime = date('Y-m-d H:i:s');
$date = date('Y-m-d');
$time = date('H:i:s');

$websiteTitle = 'Looksabayas';
$websiteUrl = 'https://mailwala.com/';
$copyright = '<strong>Copyright &copy; 2024 <a href="#">Looksabayas</a>.</strong> All rights reserved.';


// calculated the gst amount
function calculateDiscount($originalPrice=0, $discountPercentage=0) {
    // Calculate the discount amount
    $discountAmount = ($originalPrice * $discountPercentage) / 100;

    // Calculate the discounted price
    $discountedPrice = $originalPrice - $discountAmount;

    // Create an associative array to store the results
    $result = [
        'originalPrice' => $originalPrice,
        'discountPercentage' => $discountPercentage,
        'discountAmount' => $discountAmount,
        'discountedPrice' => $discountedPrice
    ];

    return $result;
}

include 'visitors.php';
// include 'countryIp.php';

// $userIp = $_SERVER['REMOTE_ADDR'];

// $Country = new COUNTRYIP();

// $geoData = $Country->getGeoDataByIp($userIp);
// if ($geoData && isset($geoData->country)) {
//     $currencySymbol = $Country->getCurrencySymbol($geoData->country);
// } else {
//     $currencySymbol = '₹';
// }

$currencySymbol = '₹';
?>