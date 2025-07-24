<?php
require '../config/config.php';
require 'functions/operations.php';

$db = new dbClass();
$admin = new Categories();

if (isset($_POST['category_id'])) {
    $category_id = $_POST['category_id'];
    $category = $admin->getCategories($category_id);
    echo $category['name'] ?? 'Unknown Category';
}
?>