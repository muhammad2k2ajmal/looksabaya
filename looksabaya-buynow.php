<?php
if (!isset($_SESSION)) {
    session_start();
}
error_reporting(E_ALL);
ini_set('display_errors', false);

require "config/config.php";
require "config/common.php";
require "config/authentication.php";
include_once('config/cart.php');
require 'config/calculate-shipping.php';

$conn = new dbClass();
$products = new CommProducts();
$common = new CommProducts();
$auth = new Authentication();
$cartItem = new Cart();
$calculator = new PincodeDistanceCalculator();

if (!isset($_SESSION['USER_LOGIN'])) {
    $_SESSION['USER_CHECKOUT'] = 'checkout';
    $_SERVER['REQUEST_URI'] = "looksabaya-buynow.php";
    header('Location: abayalooks-login.php');
    exit();
}
$shippingCharge = 0;
$errors = [];
$customerId = isset($_SESSION['USER_LOGIN']) ? $_SESSION['USER_LOGIN'] : null;

$userDetail = null;
$userAllShipDetail = [];
$userBillAddressDetail = null;

if ($customerId) {
    $userDetail = $auth->userDetails($customerId);
    $userAllShipDetail = $auth->userAllShipDetails($customerId);
    $userBillAddressDetail = $auth->userBillDetails($customerId);
}

$defaultPostcode = !empty($userAllShipDetail) ? $userAllShipDetail[0]['postal_code'] : '';
if ($defaultPostcode) {
    try {
        $shippingCharge = $calculator->calculatePrice($defaultPostcode);
        if (!is_numeric($shippingCharge) || $shippingCharge < 0) {
            $shippingCharge = 0;
        }
    } catch (Exception $e) {
        $shippingCharge = 0;
        $_SESSION['errmsg'] = "Error calculating shipping: " . $e->getMessage();
    }
}

$ipAddress = $_SERVER["REMOTE_ADDR"];
$cartData = $cartItem->buyNowItems($_SESSION['cart_item'] ?? [], $ipAddress);
// var_dump($cartData);
if (!$cartData) {
    header('Location: index.php');
    exit();
}

if (isset($_REQUEST['update'])) {
    $address_id = $conn->addStr(trim($_POST['address_id'] ?? ''));
    $type = $conn->addStr(trim($_POST['type'] ?? 'shipping'));
    $firstName = $conn->addStr(trim($_POST['firstName'] ?? ''));
    $surname = $conn->addStr(trim($_POST['surname'] ?? ''));
    $phone = $conn->addStr(trim($_POST['phone'] ?? ''));
    $email = $conn->addStr(trim($_POST['email'] ?? ''));
    $country = $conn->addStr(trim($_POST['country'] ?? ''));
    $postalCode = $conn->addStr(trim($_POST['postalCode'] ?? ''));
    $houseNo = $conn->addStr(trim($_POST['houseNo'] ?? ''));
    $addition = $conn->addStr(trim($_POST['addition'] ?? ''));
    $streetName = $conn->addStr(trim($_POST['streetName'] ?? ''));
    $placeName = $conn->addStr(trim($_POST['placeName'] ?? ''));

    if (empty($errors)) {
        if ($customerId) {
            $sqlQuery = $auth->updateCustomerAddress(
                $address_id,
                $customerId,
                $type,
                $country,
                $postalCode,
                $houseNo,
                $addition,
                $streetName,
                $placeName,
                $firstName,
                $surname,
                $phone,
                $email
            );

            if ($sqlQuery) {
                $_SESSION['msg'] = "Address updated successfully.";
                header("Location: looksabaya-checkout.php");
                exit;
            } else {
                $errors[] = "Error updating address.";
            }
        } else {
            $errors[] = "You must be logged in to update an address.";
        }
    }
    $_SESSION['errmsg'] = implode("<br>", $errors);
}

if (isset($_REQUEST['add'])) {
    $type = $conn->addStr(trim($_POST['type'] ?? 'shipping'));
    $firstName = $conn->addStr(trim($_POST['firstName'] ?? ''));
    $surname = $conn->addStr(trim($_POST['surname'] ?? ''));
    $phone = $conn->addStr(trim($_POST['phone'] ?? ''));
    $email = $conn->addStr(trim($_POST['email'] ?? ''));
    $country = $conn->addStr(trim($_POST['country'] ?? ''));
    $postalCode = $conn->addStr(trim($_POST['postalCode'] ?? ''));
    $houseNo = $conn->addStr(trim($_POST['houseNo'] ?? ''));
    $addition = $conn->addStr(trim($_POST['addition'] ?? ''));
    $streetName = $conn->addStr(trim($_POST['streetName'] ?? ''));
    $placeName = $conn->addStr(trim($_POST['placeName'] ?? ''));

    if (empty($errors)) {
        if (!$customerId) {
            $result = $auth->createAndLoginUser($firstName, $surname, $email, $phone, $country, $postalCode, $houseNo, $addition, $streetName, $placeName, $type);
            if ($result['success']) {
                $customerId = $result['userId'];
                $_SESSION['msg'] = "Account created and address added successfully. Password sent to your email.";
            } else {
                $errors[] = $result['error'];
                $_SESSION['errmsg'] = implode("<br>", $errors);
                header("Location: looksabaya-checkout.php");
                exit;
            }
        }

        $sqlQuery = $auth->addCustomerAddress(
            $customerId,
            $type,
            $country,
            $postalCode,
            $houseNo,
            $addition,
            $streetName,
            $placeName,
            $firstName,
            $surname,
            $phone,
            $email
        );

        if ($sqlQuery) {
            $_SESSION['msg'] = "Address " . ($type === 'billing' ? "updated" : "added") . " successfully.";
            header("Location: looksabaya-checkout.php");
            exit;
        } else {
            $errors[] = "Error " . ($type === 'billing' ? "updating" : "adding") . " address.";
        }
    }
    $_SESSION['errmsg'] = implode("<br>", $errors);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abayalooks</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.15/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="js/toastr/css/toastr.min.css">
</head>
<body>
    <div id="pageWrapper">
        <?php include 'include/header.php'; ?>
        <main>
            <header class="d-flex text-center breadCrumbHeader">
                <div class="alignHolder w-100 d-flex">
                    <div class="align py-2 w-100">
                        <div class="container">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="index.php" class="text-decoration-none">Home</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="looksabaya-cart.php" class="text-decoration-none">Shopping Cart</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Buy Now</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </header>
            <section class="carttablewrap py-7">
                <div class="container">
                    <div class="row">
                        <div class="col-12 text-center">
                            <h1 class="mnHding fw-normal mb-7 mb-md-8">Checkout</h1>
                        </div>
                        <div class="col-12 col-lg-8">
                            <div class="pe-lg-16">
                                <aside class="alert checkoutAlert d-flex justify-content-between align-items-center fw-normal rounded-0 pt-4 pb-3 px-3 mb-3">
                                    <div class="d-flex">
                                        <i class="ri-user-line me-2"></i>
                                        <h4 class="h2vii fw-medium text-capitalize">Shipping Addresses</h4>
                                    </div>
                                    <a class="alertPopBtn text-decoration-none ms-1 text-end" data-bs-toggle="collapse"
                                        href="#loginAlertPopup" aria-expanded="true" aria-controls="loginAlertPopup">
                                        <i class="ri-arrow-down-s-line"></i>
                                    </a>
                                </aside>
								<?php	if(count($userAllShipDetail)<3):?>
									<div id="loginAlertPopup" class="alertCollapseWrap collapse py-2 ps-1 pe-2 mb-4 show">
										<form id="addaddress" method="post" enctype="multipart/form-data" class="ChForm">
											<input type="hidden" name="type" value="shipping">
											<div class="bilingDetailsWrap row pt-lg-5 pt-xl-6 mb-8 mb-xl-14">
												<div class="form-row d-flex flex-wrap">
													<div class="formCol formCol50 mb-3">
														<div class="form-group d-block">
															<span class="fLabel fw-normal text-capitalize d-block mb-1">First Name <em class="req">*</em></span>
															<input type="text" class="form-control d-block w-100" id="firstName" name="firstName" >
														</div>
													</div>
													<div class="formCol formCol50 mb-3">
														<div class="form-group d-block">
															<span class="fLabel fw-normal text-capitalize d-block mb-1">Last Name <em class="req">*</em></span>
															<input type="text" class="form-control d-block w-100" id="surname" name="surname" >
														</div>
													</div>
													<div class="formCol formCol50 mb-3">
														<div class="form-group d-block">
															<span class="fLabel fw-normal text-capitalize d-block mb-1">Email Address <em class="req">*</em></span>
															<input type="email" class="form-control d-block w-100" id="email" name="email" >
														</div>
													</div>
													<div class="formCol formCol50 mb-3">
														<div class="form-group d-block">
															<span class="fLabel fw-normal text-capitalize d-block mb-1">Phone <em class="req">*</em></span>
															<input type="text" class="form-control d-block w-100" id="phone" name="phone" >
														</div>
													</div>
													<div class="formCol mb-3">
														<div class="form-group d-block">
															<span class="fLabel fw-normal text-capitalize d-block mb-1">Country <em class="req">*</em></span>
															<div class="coolSelectWrapper">
																<select class="coolSelect form-control" id="country" name="country" >
																	<option value="" disabled selected>Select Country</option>
																	<option value="India">India</option>
																	<option value="United States">United States</option>
																	<option value="China">China</option>
																</select>
															</div>
														</div>
													</div>
													<div class="formCol mb-3">
														<div class="form-group d-block">
															<span class="fLabel fw-normal text-capitalize d-block mb-1">Address <em class="req">*</em></span>
															<input type="text" class="form-control d-block w-100 mb-2" id="streetName" name="streetName" placeholder="Street Address" >
															<input type="text" class="form-control d-block w-100" id="addition" name="addition" placeholder="Apartment, suite, unit etc. (optional)">
														</div>
													</div>
													<div class="formCol mb-3">
														<div class="form-group d-block">
															<span class="fLabel fw-normal text-capitalize d-block mb-1">Town / City <em class="req">*</em></span>
															<input type="text" class="form-control d-block w-100" id="placeName" name="placeName" >
														</div>
													</div>
													<div class="formCol formCol50 mb-3">
														<div class="form-group d-block">
															<span class="fLabel fw-normal text-capitalize d-block mb-1">House No. <em class="req">*</em></span>
															<input type="text" class="form-control d-block w-100" id="houseNo" name="houseNo" >
														</div>
													</div>
													<div class="formCol formCol50 mb-3">
														<div class="form-group d-block">
															<span class="fLabel fw-normal text-capitalize d-block mb-1">Postcode / ZIP <em class="req">*</em></span>
															<input type="text" class="form-control d-block w-100" id="postalCode" name="postalCode" >
														</div>
													</div>
													<div class="formCol mb-8">
														<button type="submit" name="add" class="btn_hover_color">Add Address</button>
													</div>
												</div>
											</div>
										</form>
									</div>
								<?php endif;?>
                                <?php if ($customerId && !empty($userAllShipDetail)): ?>
                                    <?php $i = 0; foreach ($userAllShipDetail as $shippingDetails): ?>
                                        <div class="row align-items-start mb-3" id="address-block-<?php echo $i; ?>">
                                            <div class="col-md-1">
                                                <input type="radio" name="shipping-address" id="select-item-<?php echo $i; ?>" class="select-item" value="<?php echo htmlspecialchars($shippingDetails['address_id']); ?>" data-postcode="<?php echo htmlspecialchars($shippingDetails['postal_code']); ?>" <?php echo $i == 0 ? 'checked' : ''; ?>>
                                            </div>
                                            <div class="col-md-10">
                                                <p class="mb-0" id="details<?php echo $i; ?>">
                                                    <?php echo htmlspecialchars($shippingDetails['first_name'] . ' ' . $shippingDetails['surname'] . ', ' . $shippingDetails['phone']); ?>
                                                </p>
                                                <p class="mb-0">
                                                    <?php echo htmlspecialchars($shippingDetails['email'] . ', ' . $shippingDetails['house_no'] . ', ' . $shippingDetails['addition'] . ', ' . $shippingDetails['street_name'] . ', ' . $shippingDetails['place_name'] . ', ' . $shippingDetails['country'] . ', ' . $shippingDetails['postal_code']); ?>
                                                </p>
                                            </div>
                                            <div class="col-md-1 text-end">
                                                <a href="javascript:void(0);" class="btn btn-warning btn-sm edit-btn" id="edit-btn-<?php echo $i; ?>" data-target="#form<?php echo $i; ?>" data-details="#details<?php echo $i; ?>" style="display: <?php echo $i == 0 ? 'inline-block' : 'none'; ?>;">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                            </div>
                                            <form class="deliveryForm p-0" method="POST" enctype="multipart/form-data">
                                                <input type="hidden" name="type" value="cart">
                                                <input type="hidden" name="shipId" value="<?php echo htmlspecialchars($shippingDetails['address_id']); ?>">
                                                <input type="hidden" name="totalAmount" id="totalAmount-<?php echo $i; ?>">
                                                <input type="hidden" name="shippingChargeShould" id="shippingChargeShould-<?php echo $i; ?>">
                                                <!-- <div class="col-md-12 text-end mt-3 pe-0 delivery-btn-group">
                                                    <button type="submit" name="submit" value="submit" class="btn btn-dark btn-sm deliver-btn" id="deliver-btn-<?php echo $i; ?>" style="display: <?php echo $i == 0 ? 'inline-block' : 'none'; ?>;">
                                                        DELIVERY HERE
                                                    </button>
                                                </div> -->
                                            </form>
                                            <div class="address-edit-form" id="form<?php echo $i; ?>" style="display: none;">
                                                <h4>Edit Address</h4>
                                                <form class="row g-3" id="updateaddress-<?php echo $i; ?>" method="post" enctype="multipart/form-data">
                                                    <input type="hidden" name="address_id" value="<?php echo htmlspecialchars($shippingDetails['address_id']); ?>">
                                                    <input type="hidden" name="type" value="shipping">
                                                    <div class="col-md-6">
                                                        <label for="firstName<?php echo $i; ?>" class="form-label">First Name</label>
                                                        <input type="text" class="form-control" id="firstName<?php echo $i; ?>" name="firstName" value="<?php echo htmlspecialchars($shippingDetails['first_name']); ?>" >
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="surname<?php echo $i; ?>" class="form-label">Surname</label>
                                                        <input type="text" class="form-control" id="surname<?php echo $i; ?>" name="surname" value="<?php echo htmlspecialchars($shippingDetails['surname']); ?>" >
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="phone<?php echo $i; ?>" class="form-label">Phone</label>
                                                        <input type="text" class="form-control" id="phone<?php echo $i; ?>" name="phone" value="<?php echo htmlspecialchars($shippingDetails['phone']); ?>" >
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="email<?php echo $i; ?>" class="form-label">Email</label>
                                                        <input type="email" class="form-control" id="email<?php echo $i; ?>" name="email" value="<?php echo htmlspecialchars($shippingDetails['email']); ?>" >
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="country<?php echo $i; ?>" class="form-label">Country</label>
                                                        <select class="form-control" id="country<?php echo $i; ?>" name="country" >
                                                            <option value="" disabled>Select Country</option>
                                                            <option value="India" <?php echo $shippingDetails['country'] == 'India' ? 'selected' : ''; ?>>India</option>
                                                            <option value="United States" <?php echo $shippingDetails['country'] == 'United States' ? 'selected' : ''; ?>>United States</option>
                                                            <option value="China" <?php echo $shippingDetails['country'] == 'China' ? 'selected' : ''; ?>>China</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="streetName<?php echo $i; ?>" class="form-label">Street Address</label>
                                                        <input type="text" class="form-control" id="streetName<?php echo $i; ?>" name="streetName" value="<?php echo htmlspecialchars($shippingDetails['street_name']); ?>" >
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="addition<?php echo $i; ?>" class="form-label">Landmark (optional)</label>
                                                        <input type="text" class="form-control" id="addition<?php echo $i; ?>" name="addition" value="<?php echo htmlspecialchars($shippingDetails['addition']); ?>">
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="placeName<?php echo $i; ?>" class="form-label">Town / City</label>
                                                        <input type="text" class="form-control" id="placeName<?php echo $i; ?>" name="placeName" value="<?php echo htmlspecialchars($shippingDetails['place_name']); ?>" >
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="houseNo<?php echo $i; ?>" class="form-label">House No.</label>
                                                        <input type="text" class="form-control" id="houseNo<?php echo $i; ?>" name="houseNo" value="<?php echo htmlspecialchars($shippingDetails['house_no']); ?>" >
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="postalCode<?php echo $i; ?>" class="form-label">Postcode</label>
                                                        <input type="text" class="form-control" id="postalCode<?php echo $i; ?>" name="postalCode" value="<?php echo htmlspecialchars($shippingDetails['postal_code']); ?>" >
                                                    </div>
                                                    <div class="mt-4">
                                                        <button type="submit" name="update" class="btn_hover_color save-address-btn me-2">Save Changes</button>
                                                        <button type="button" class="btn_hover_color cancel-edit-btn">Cancel</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    <?php $i++; endforeach; ?>
                                <?php else: ?>
                                    <p>No shipping addresses found. Please add a new address above.</p>
                                <?php endif; ?>
                                <div id="couponAlertPopup" class="alertCollapseWrap collapse py-2 ps-1 pe-2 mb-4">
                                    <form id="addaddress-billing" method="post" enctype="multipart/form-data" class="ChForm">
                                        <input type="hidden" name="type" value="billing">
                                        <div class="bilingDetailsWrap row pt-lg-5 pt-xl-6 mb-8 mb-xl-14">
                                            <h3 class="h2vii fw-medium text-capitalize mb-6">Billing Details</h3>
                                            <div class="form-row d-flex flex-wrap">
                                                <div class="formCol formCol50 mb-3">
                                                    <div class="form-group d-block">
                                                        <span class="fLabel fw-normal text-capitalize d-block mb-1">First Name <em class="req">*</em></span>
                                                        <input type="text" class="form-control d-block w-100" id="firstName-billing" name="firstName" >
                                                    </div>
                                                </div>
                                                <div class="formCol formCol50 mb-3">
                                                    <div class="form-group d-block">
                                                        <span class="fLabel fw-normal text-capitalize d-block mb-1">Last Name <em class="req">*</em></span>
                                                        <input type="text" class="form-control d-block w-100" id="surname-billing" name="surname" >
                                                    </div>
                                                </div>
                                                <div class="formCol formCol50 mb-3">
                                                    <div class="form-group d-block">
                                                        <span class="fLabel fw-normal text-capitalize d-block mb-1">Email Address <em class="req">*</em></span>
                                                        <input type="email" class="form-control d-block w-100" id="email-billing" name="email" >
                                                    </div>
                                                </div>
                                                <div class="formCol formCol50 mb-3">
                                                    <div class="form-group d-block">
                                                        <span class="fLabel fw-normal text-capitalize d-block mb-1">Phone <em class="req">*</em></span>
                                                        <input type="text" class="form-control d-block w-100" id="phone-billing" name="phone" >
                                                    </div>
                                                </div>
                                                <div class="formCol mb-3">
                                                    <div class="form-group d-block">
                                                        <span class="fLabel fw-normal text-capitalize d-block mb-1">Country <em class="req">*</em></span>
                                                        <div class="coolSelectWrapper">
                                                            <select class="coolSelect form-control" id="country-billing" name="country" >
                                                                <option value="" disabled selected>Select Country</option>
                                                                <option value="India">India</option>
                                                                <option value="United States">United States</option>
                                                                <option value="China">China</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="formCol mb-3">
                                                    <div class="form-group d-block">
                                                        <span class="fLabel fw-normal text-capitalize d-block mb-1">Address <em class="req">*</em></span>
                                                        <input type="text" class="form-control d-block w-100 mb-2" id="streetName-billing" name="streetName" placeholder="Street Address" >
                                                        <input type="text" class="form-control d-block w-100" id="addition-billing" name="addition" placeholder="Apartment, suite, unit etc. (optional)">
                                                    </div>
                                                </div>
                                                <div class="formCol mb-3">
                                                    <div class="form-group d-block">
                                                        <span class="fLabel fw-normal text-capitalize d-block mb-1">Town / City <em class="req">*</em></span>
                                                        <input type="text" class="form-control d-block w-100" id="placeName-billing" name="placeName" >
                                                    </div>
                                                </div>
                                                <div class="formCol formCol50 mb-3">
                                                    <div class="form-group d-block">
                                                        <span class="fLabel fw-normal text-capitalize d-block mb-1">House No. <em class="req">*</em></span>
                                                        <input type="text" class="form-control d-block w-100" id="houseNo-billing" name="houseNo" >
                                                    </div>
                                                </div>
                                                <div class="formCol formCol50 mb-3">
                                                    <div class="form-group d-block">
                                                        <span class="fLabel fw-normal text-capitalize d-block mb-1">Postcode / ZIP <em class="req">*</em></span>
                                                        <input type="text" class="form-control d-block w-100" id="postalCode-billing" name="postalCode" >
                                                    </div>
                                                </div>
                                                <div class="formCol mb-8">
                                                    <button type="submit" name="add" class="btn_hover_color">Add Billing Address</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="mt-3">
                                    <input class="form-check-input" type="checkbox" id="sameAddressCheckbox" checked>
                                    <label class="form-check-label">Billing and shipping address are the same</label>
                                </div>
                                <div id="billingAddressSection" class="alertCollapseWrap collapse py-2 ps-1 pe-2 mb-4" style="display: none;">
                                    <?php if ($customerId && $userBillAddressDetail): ?>
                                        <div class="row align-items-start mb-3" id="address-block-billing">
                                            <div class="col-md-1">
                                                <input type="radio" name="billing-address" id="select-item-billing" class="select-item" value="<?php echo htmlspecialchars($userBillAddressDetail['address_id']); ?>" checked>
                                            </div>
                                            <div class="col-md-10">
                                                <p class="mb-0" id="details-billing">
                                                    <?php echo htmlspecialchars($userBillAddressDetail['first_name'] . ' ' . $userBillAddressDetail['surname'] . ', ' . $userBillAddressDetail['phone']); ?>
                                                </p>
                                                <p class="mb-0">
                                                    <?php echo htmlspecialchars($userBillAddressDetail['email'] . ', ' . $userBillAddressDetail['house_no'] . ', ' . $userBillAddressDetail['addition'] . ', ' . $userBillAddressDetail['street_name'] . ', ' . $userBillAddressDetail['place_name'] . ', ' . $userBillAddressDetail['country'] . ', ' . $userBillAddressDetail['postal_code']); ?>
                                                </p>
                                            </div>
                                            <div class="col-md-1 text-end">
                                                <a href="javascript:void(0);" class="btn btn-warning btn-sm edit-btn" id="edit-btn-billing" data-target="#form-billing" data-details="#details-billing">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                            </div>
                                            <div class="address-edit-form" id="form-billing" style="display: none;">
                                                <h4>Edit Billing Address</h4>
                                                <form class="row g-3" id="updateaddress-billing" method="post" enctype="multipart/form-data">
                                                    <input type="hidden" name="address_id" value="<?php echo htmlspecialchars($userBillAddressDetail['address_id']); ?>">
                                                    <input type="hidden" name="type" value="billing">
                                                    <div class="col-md-6">
                                                        <label for="firstName-billing-edit" class="form-label">First Name</label>
                                                        <input type="text" class="form-control" id="firstName-billing-edit" name="firstName" value="<?php echo htmlspecialchars($userBillAddressDetail['first_name']); ?>" >
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="surname-billing-edit" class="form-label">Surname</label>
                                                        <input type="text" class="form-control" id="surname-billing-edit" name="surname" value="<?php echo htmlspecialchars($userBillAddressDetail['surname']); ?>" >
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="phone-billing-edit" class="form-label">Phone</label>
                                                        <input type="text" class="form-control" id="phone-billing-edit" name="phone" value="<?php echo htmlspecialchars($userBillAddressDetail['phone']); ?>" >
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="email-billing-edit" class="form-label">Email</label>
                                                        <input type="email" class="form-control" id="email-billing-edit" name="email" value="<?php echo htmlspecialchars($userBillAddressDetail['email']); ?>" >
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="country-billing-edit" class="form-label">Country</label>
                                                        <select class="form-control" id="country-billing-edit" name="country" >
                                                            <option value="" disabled>Select Country</option>
                                                            <option value="India" <?php echo $userBillAddressDetail['country'] == 'India' ? 'selected' : ''; ?>>India</option>
                                                            <option value="United States" <?php echo $userBillAddressDetail['country'] == 'United States' ? 'selected' : ''; ?>>United States</option>
                                                            <option value="China" <?php echo $userBillAddressDetail['country'] == 'China' ? 'selected' : ''; ?>>China</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="streetName-billing-edit" class="form-label">Street Address</label>
                                                        <input type="text" class="form-control" id="streetName-billing-edit" name="streetName" value="<?php echo htmlspecialchars($userBillAddressDetail['street_name']); ?>" >
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="addition-billing-edit" class="form-label">Landmark (optional)</label>
                                                        <input type="text" class="form-control" id="addition-billing-edit" name="addition" value="<?php echo htmlspecialchars($userBillAddressDetail['addition']); ?>">
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="placeName-billing-edit" class="form-label">Town / City</label>
                                                        <input type="text" class="form-control" id="placeName-billing-edit" name="placeName" value="<?php echo htmlspecialchars($userBillAddressDetail['place_name']); ?>" >
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="houseNo-billing-edit" class="form-label">House No.</label>
                                                        <input type="text" class="form-control" id="houseNo-billing-edit" name="houseNo" value="<?php echo htmlspecialchars($userBillAddressDetail['house_no']); ?>" >
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="postalCode-billing-edit" class="form-label">Postcode</label>
                                                        <input type="text" class="form-control" id="postalCode-billing-edit" name="postalCode" value="<?php echo htmlspecialchars($userBillAddressDetail['postal_code']); ?>" >
                                                    </div>
                                                    <div class="mt-4">
                                                        <button type="submit" name="update" class="btn_hover_color save-address-btn me-2">Save Changes</button>
                                                        <button type="button" class="btn_hover_color cancel-edit-btn">Cancel</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <p>No billing address found. Please add a new address above.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="ms-lg-n10">
                                <div class="odrSide border py-7 px-7">
                                    <h5 class="cartHeading fw-medium mb-4">Your Order</h5>
                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="subheading fw-normal">Product</span>
                                        <span class="Hprice fw-normal">Subtotal</span>
                                    </div>
                                    <hr class="mb-4">
                                    <?php
                                    $subtotal = 0;
                                    $discountTotal = 0;
                                    $appliedCoupon = isset($_SESSION['applied_coupon']) ? $_SESSION['applied_coupon'] : null;
                                    $couponDiscount = 0;
$cartData = $cartItem->buyNowItems($_SESSION['cart_item'] ?? [], $ipAddress);

                                    // var_dump($cartData);
                                    if (!empty($cartData)):
                                        foreach ($cartData as $cartRow):
                                            $cartProductSql = $products->getProductsById($cartRow['product_id']);
                                            if (!$cartProductSql) continue;
                                            $discountInfo = $cartProductSql['price'] * (1 - ($cartProductSql['discount'] ?? 0) / 100);
                                            $cartProductTotal = $cartRow['quantity'] * $discountInfo;
                                            $subtotal += $cartProductTotal;
                                            $discountTotal += ($cartProductSql['price'] - $discountInfo) * $cartRow['quantity'];
                                    ?>
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div class="d-flex">
                                                    <img src="adminUploads/products/<?php echo htmlspecialchars($cartProductSql['image']); ?>" alt="<?php echo htmlspecialchars($cartProductSql['name']); ?>" class="img-thumbnail border-0 p-0 me-4 rounded-0 tb-img" width="60px" height="60px">
                                                    <div>
                                                        <span class="tb-heading d-block fw-light"><?php echo htmlspecialchars($cartProductSql['name']); ?></span>
                                                        <small class="textQty fw-normal">QTY : <?php echo $cartRow['quantity']; ?></small>
                                                    </div>
                                                </div>
                                                <span class="tb-price fw-normal">Rs.<?php echo number_format($cartProductTotal, 2); ?></span>
                                            </div>
                                    <?php
                                        endforeach;
                                        $couponDiscount = $appliedCoupon && isset($appliedCoupon['discount_percentage']) ? $subtotal * ($appliedCoupon['discount_percentage'] / 100) : 0;
                                        if ($couponDiscount > 1000) {
                                            $couponDiscount = 1000;
                                        }
                                        $finalTotal = $subtotal  + $shippingCharge;
                                    ?>
                                        <hr class="mb-4">
                                        <div class="d-flex justify-content-between mb-3">
                                            <span class="Hprice fw-normal">Subtotal</span>
											<input type="hidden" name="subtotal" id="subtotal" value="<?php echo $subtotal; ?>">
                                            <span class="tb-price fw-normal" id="subtotalAmount">Rs.<?php echo number_format($subtotal, 2); ?></span>
                                        </div>
                                        <!-- <div class="d-flex justify-content-between mb-3">
                                            <span class="Hprice fw-normal">Discount</span>
                                            <span class="tb-price fw-normal" id="totalDiscount">-Rs.<?php echo number_format($discountTotal + $couponDiscount, 2); ?></span>
                                        </div> -->
                                        <div class="d-flex justify-content-between mb-3">
                                            <span class="Hprice fw-normal">Shipping</span>
                                            <span class="tb-price fw-normal" id="shippingCharge">Rs.<?php echo number_format($shippingCharge, 2); ?></span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-6">
                                            <span class="subheading fw-normal">Total</span>
                                            <strong class="Hprice fw-medium" id="totalWithShipping">Rs.<?php echo number_format($finalTotal, 2); ?></strong>
                                        </div>
                                    <?php else: ?>
                                        <p>Your cart is empty.</p>
                                    <?php endif; ?>
                                    <div class="form-check clor mb-5">
                                        <input class="form-check-input" type="checkbox" id="terms" checked>
                                        <label class="form-check-label small ps-1" for="terms">
                                            I have read and agree to the website <a href="javascript:void(0);">terms and conditions</a> *
                                        </label>
                                    </div>
                                    <button class="btn btnP btn-dark fw-medium w-100 mb-1" id="placeOrderBtn" onclick="validateAndProceed()">Place Order</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <?php include 'include/footer.php'; ?>
    </div>
    <!-- <script src="js/jquery.min.js" defer></script>
    <script src="js/popper.js" defer></script>
    <script src="js/bootstrap.js" defer></script>
    <script src="js/custom.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.15/dist/sweetalert2.all.min.js" defer></script>
    <script src="js/toastr/js/toastr.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" defer></script> -->
	<script src="js/jquery.min.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/popper.js" defer=""></script>
    <script src="js/bootstrap.js" defer=""></script>
    <script src="js/custom.js" defer=""></script>
    <script src="js/toastr/js/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.15/dist/sweetalert2.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        <?php if (isset($_SESSION['msg'])): ?>
            toastr.success("<?php echo $_SESSION['msg']; ?>");
            <?php unset($_SESSION['msg']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['errmsg'])): ?>
            toastr.error("<?php echo $_SESSION['errmsg']; ?>");
            <?php unset($_SESSION['errmsg']); ?>
        <?php endif; ?>

        document.addEventListener('DOMContentLoaded', function () {
			document.getElementById('placeOrderBtn').addEventListener('click', validateAndProceed);
            const radioButtons = document.querySelectorAll('input[name="shipping-address"], input[name="billing-address"]');
            const editButtons = document.querySelectorAll('.edit-btn');
            const cancelButtons = document.querySelectorAll('.cancel-edit-btn');

            function updateButtonVisibility() {
                radioButtons.forEach(radio => {
                    const parentRow = radio.closest('.row.align-items-start.mb-3');
                    if (parentRow) {
                        const deliverButton = parentRow.querySelector('.deliver-btn');
                        const editButton = parentRow.querySelector('.edit-btn');
                        const addressDetails = parentRow.querySelector('[id^="details"]');
                        const editForm = parentRow.querySelector('.address-edit-form');

                        if (radio.checked) {
                            if (deliverButton) deliverButton.style.display = 'inline-block';
                            if (editButton) editButton.style.display = 'inline-block';
                            if (editForm) editForm.style.display = 'none';
                            if (addressDetails) addressDetails.style.display = 'block';
                        } else {
                            if (deliverButton) deliverButton.style.display = 'none';
                            if (editButton) editButton.style.display = 'none';
                            if (editForm) editForm.style.display = 'none';
                            if (addressDetails) addressDetails.style.display = 'block';
                        }
                    }
                });
            }

            radioButtons.forEach(radio => {
                radio.addEventListener('change', updateButtonVisibility);
            });

            editButtons.forEach(editBtn => {
                editBtn.addEventListener('click', function () {
                    const parentRow = editBtn.closest('.row.align-items-start.mb-3');
                    if (parentRow) {
                        const formId = editBtn.dataset.target;
                        const detailsId = editBtn.dataset.details;
                        const editForm = parentRow.querySelector(formId);
                        const addressDetails = parentRow.querySelector(detailsId);
                        const deliverButton = parentRow.querySelector('.deliver-btn');

                        if (editForm) editForm.style.display = 'block';
                        if (addressDetails) addressDetails.style.display = 'none';
                        if (editBtn) editBtn.style.display = 'none';
                        if (deliverButton) deliverButton.style.display = 'none';
                    }
                });
            });

            cancelButtons.forEach(cancelBtn => {
                cancelBtn.addEventListener('click', function () {
                    const parentRow = cancelBtn.closest('.row.align-items-start.mb-3');
                    if (parentRow) {
                        const editForm = parentRow.querySelector('.address-edit-form');
                        const addressDetails = parentRow.querySelector('[id^="details"]');
                        const editButton = parentRow.querySelector('.edit-btn');
                        const deliverButton = parentRow.querySelector('.deliver-btn');
                        const radioBtn = parentRow.querySelector('.select-item');

                        if (editForm) editForm.style.display = 'none';
                        if (addressDetails) addressDetails.style.display = 'block';
                        if (radioBtn && radioBtn.checked) {
                            if (editButton) editButton.style.display = 'inline-block';
                            if (deliverButton) deliverButton.style.display = 'inline-block';
                        }
                    }
                });
            });

            updateButtonVisibility();

            $('form[id^="updateaddress"], #addaddress, #addaddress-billing').each(function () {
                $(this).validate({
                    rules: {
                        firstName: { required: true, minlength: 2 },
                        surname: { required: true, minlength: 2 },
                        phone: { required: true, minlength: 10, maxlength: 15 },
                        email: { required: true, email: true },
                        country: { required: true },
                        postalCode: { required: true, minlength: 4, maxlength: 10 },
                        houseNo: { required: true },
                        streetName: { required: true },
                        placeName: { required: true }
                    },
                    messages: {
                        firstName: "Please enter a valid first name",
                        surname: "Please enter a valid surname",
                        phone: "Please enter a valid phone number (10-15 digits)",
                        email: "Please enter a valid email address",
                        country: "Please select a country",
                        postalCode: "Please enter a valid postal code (4-10 characters)",
                        houseNo: "Please enter a house number",
                        streetName: "Please enter a street name",
                        placeName: "Please enter a town/city"
                    },
                    errorElement: 'span',
                    errorPlacement: function(error, element) {
                        error.addClass('invalid-feedback');
                        element.closest('.col-md-4, .col-md-6, .col-md-12').append(error);
                    },
                    highlight: function(element) {
                        $(element).addClass('is-invalid').removeClass('is-valid');
                    },
                    unhighlight: function(element) {
                        $(element).removeClass('is-invalid').addClass('is-valid');
                    }
                });
            });

            function updateShippingCharge(postcode, index) {
                if (!postcode) {
                    toastr.error('Please select a valid shipping address.');
                    updateUIWithZeroShipping();
                    return;
                }

                $.ajax({
                    url: 'calculate_shipping_charge',
                    method: 'POST',
                    data: { postcode: postcode },
                    dataType: 'json',
                    success: function(response) {
                        if (response.error) {
                            toastr.error(response.error);
                            updateUIWithZeroShipping();
                            return;
                        }

                        let shippingCharge = parseFloat(response.shippingCharge) || 0;
                        if (isNaN(shippingCharge) || shippingCharge < 0) {
                            toastr.error('Invalid shipping charge');
                            updateUIWithZeroShipping();
                            return;
                        }

                        // let subtotalText = $('#subtotalAmount').text().replace(/[^\d.]/g, '');
                        let subtotalText = $('#subtotal').val();
                        let subtotal = parseFloat(subtotalText) || 0;
                        let discountText = $('#totalDiscount').text().replace(/[^\d.]/g, '');
                        let discount = parseFloat(discountText) || 0;
                        let finalTotal = subtotal  + shippingCharge;

						

                        $('#shippingCharge').text('Rs.' + shippingCharge.toFixed(2));
                        $('#totalWithShipping').text('Rs.' + finalTotal.toFixed(2));
                        $(`#shippingChargeShould-${index}`).val(shippingCharge);
                        $(`#totalAmount-${index}`).val(finalTotal);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('AJAX error:', textStatus, errorThrown);
                        toastr.error('Unable to calculate shipping charge');
                        updateUIWithZeroShipping();
                    }
                });

                function updateUIWithZeroShipping() {
                    // let subtotalText = $('#subtotalAmount').text().replace(/[^\d.]/g, '');
					let subtotalText = $('#subtotal').val();
                    let subtotal = parseFloat(subtotalText) || 0;
                    let discountText = $('#totalDiscount').text().replace(/[^\d.]/g, '');
                    let discount = parseFloat(discountText) || 0;
                    let finalTotal = subtotal ;
                    $('#shippingCharge').text('Rs.0.00');
                    $('#totalWithShipping').text('Rs.' + finalTotal.toFixed(2));
                    $(`#shippingChargeShould-${index}`).val(0);
                    $(`#totalAmount-${index}`).val(finalTotal);
                }
            }

            $('input[name="shipping-address"]').on('change', function() {
                let postcode = $(this).data('postcode');
                let index = $(this).attr('id').replace('select-item-', '');
                updateShippingCharge(postcode, index);
            });

            const chkSame = $('#sameAddressCheckbox');
            const billingSection = $('#billingAddressSection');

            function toggleBilling() {
                billingSection.css('display', chkSame.is(':checked') ? 'none' : 'block');
                if (!chkSame.is(':checked')) {
                    const billingRadio = billingSection.find('input[type="radio"][name="billing-address"]');
                    if (billingRadio.length) {
                        billingRadio.prop('checked', true);
                    } else {
                        toastr.error('No billing address available. Please add one.');
                        chkSame.prop('checked', true);
                        billingSection.css('display', 'none');
                    }
                }
            }

            chkSame.on('change', toggleBilling);
            toggleBilling();

            if ($("input[name='shipping-address']:checked").length > 0) {
                let postcode = $("input[name='shipping-address']:checked").data('postcode');
                let index = $("input[name='shipping-address']:checked").attr('id').replace('select-item-', '');
                updateShippingCharge(postcode, index);
            }

            function validateAndProceed() {
                if (!$('#subtotalAmount').length || parseFloat($('#subtotalAmount').text().replace(/[^\d.]/g, '')) <= 0) {
                    toastr.error('Your cart is empty or invalid. Please add items to proceed.');
                    return;
                }

                const shippingAddress = $("input[name='shipping-address']:checked");
                if (shippingAddress.length === 0) {
                    toastr.error('Please select a shipping address.');
                    return;
                }

                const postcode = shippingAddress.data('postcode');
                if (!postcode || !/^[0-9A-Za-z\s\-]{4,10}$/.test(postcode)) {
                    toastr.error('Please select a shipping address with a valid postcode.');
                    return;
                }

                const shippingChargeText = $('#shippingCharge').text().replace(/[^\d.]/g, '');
                const shippingCharge = parseFloat(shippingChargeText) || 0;
                if (isNaN(shippingCharge) || shippingCharge < 0) {
                    toastr.error('Shipping charge could not be calculated. Please ensure the postcode is valid.');
                    return;
                }

                const sameAddressChecked = $('#sameAddressCheckbox').is(':checked');
                let billId = '';
                if (!sameAddressChecked) {
                    const billingAddress = $("input[name='billing-address']:checked");
                    if (billingAddress.length === 0) {
                        toastr.error('Please select a billing address or check "Billing and shipping address are the same".');
                        return;
                    }
                    billId = billingAddress.val();
                } else {
                    billId = shippingAddress.val();
                }

                const shipId = shippingAddress.val();
                const type = 'buyNow';
                const couponCode = '<?php echo isset($_SESSION["coupon_code"]) ? addslashes($_SESSION['coupon_code']) : ""; ?>';
                window.location.href = `update-shipping.php?shipId=${encodeURIComponent(shipId)}&billId=${encodeURIComponent(billId)}&type=${encodeURIComponent(type)}&coupon=${encodeURIComponent(couponCode)}`;
            }
        });
    </script>
</body>
</html>