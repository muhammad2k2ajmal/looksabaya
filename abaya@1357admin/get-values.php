<?php

include '../config/config.php';
include 'functions/operations.php';

if(!empty($_POST["category_id"])):
    $categories = new Categories();

    $sqlsubCatQuery = $categories->getSubCatgoriesDropdown($_POST["category_id"]);
        echo '<option value="">Select Subcategory</option>';
        foreach($sqlsubCatQuery as $sqlsubcatRow):
            echo '<option value="'.$sqlsubcatRow['id'].'">'.$sqlsubcatRow['name'].'</option>';
        endforeach;
endif;

// Delete product image 
if(isset($_REQUEST['deleteImages']) && isset($_REQUEST['image_id'])){
	$db = new dbClass();
	$imgId = $_REQUEST['image_id'];
    $selectSql = $db->getData("SELECT `image` FROM `product_images` WHERE `image_id` = '$imgId'");
    unlink("../adminuploads/products/".$selectSql['image']);
    $deleteSql = $db->execute("DELETE FROM `product_images` WHERE `image_id` = '$imgId'");
}

?>