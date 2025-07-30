<?php

if (!isset($_SESSION)) {
    session_start();
}
error_reporting(E_ALL);

require 'config/config.php';
require 'config/cart.php';

$conn = new dbClass();
$cartItem = new Cart();

$ipAddress = $_SERVER["REMOTE_ADDR"];
$cartData = $cartItem->cartItems($_SESSION['cart_item'], $ipAddress);

$cart_id = $cartData[0]['cart_id'];

// Pseudo code for checking cart items
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Arrays to hold the items that were removed or updated
    $removed_items = [];
    $updated_items = [];
    $Quantity_Decrease_items = [];
    $cart_total = 0;

    // Iterate through each item in the cart
    foreach ($cartData as $item) {
        $product_id = $item['product_id']; // Ensure you're using the correct index for product_id
        $quantity = $item['quantity']; // Extract quantity
        $productDetails = $cartItem->getProductsDetail($product_id);
        $stock = $productDetails['stock'];

        // Check stock for each product
        $in_stock = $stock; // Replace this with your function to check stock

        if ($in_stock == 0) {
            // If the product is out of stock, remove it from the cart and track it
            $userId = $_SESSION['cart_item'];
            $productCartId = $cart_id;
            $ipAddress = $_SERVER["REMOTE_ADDR"];
            // Delete the item from the cart
            $cartDeleteItem = $cartItem->removeCartItem($userId, $productCartId, $product_id, $ipAddress);
            $removed_items[] = $item['id'];  // Track the removed item ID
        } elseif ($in_stock < $quantity) {
            // If the stock is less than the quantity in the cart, decrease the quantity in the cart
            $userId = $_SESSION['cart_item'];
            $productCartId = $cart_id;
            $ipAddress = $_SERVER["REMOTE_ADDR"];
            // Decrease the cart item quantity due to stock shortage
            $newQuantity = $in_stock; // Set the quantity to the available stock
            $cartDecreaseQuantityItem = $cartItem->decreaseCartItembecauseofstock($userId, $productCartId, $product_id, $ipAddress, $newQuantity);
            $Quantity_Decrease_items[] = [
                'item_id' => $item['id'],
                'new_quantity' => $newQuantity
            ];
            // Track the updated item with the new quantity
            $updated_items[] = [
                'product_id' => $product_id,
                'updated_quantity' => $newQuantity
            ];
        }
    }

    // Ensure all arrays are set and valid (even if empty)
    echo json_encode([
        'removed_items' => $removed_items ?? [],  // Ensure it's always an array
        'updated_items' => $updated_items ?? [],  // Ensure it's always an array
        'quantity_decreased_items' => $Quantity_Decrease_items ?? []  // Same for this
    ]);
}

?>
