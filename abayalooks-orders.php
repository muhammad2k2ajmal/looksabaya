<?php
if (!isset($_SESSION)) {
    session_start();
}
error_reporting(E_ALL);
require "config/config.php";
require "config/common.php";
include_once('config/cart.php');

$conn = new dbClass();
$common = new CommProducts();

// $banners=$common->getAllBanners();
// $testimonials=$common->getAllTestimonials();
// var_dump($banners);
$customer = new CommCustomers();

$orderTable = new OrderPage();
$orderdetails = new OrderPage();

$orderData = $orderTable->getAllOrder($_SESSION['USER_LOGIN']);

$customer->checkSession($_SESSION['USER_LOGIN'] ?? null);
$userDetail = $customer->userDetails($_SESSION['USER_LOGIN']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Looksabaya</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="style.css">

    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- <link rel="icon" href="images/favicon.png" type="image/x-icon"> -->
    <!-- <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon"> -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />


</head>

<body>
    <div id="pageWrapper">
        <?php include 'include/header.php'; ?>


        <div class="container">
            <div class="wrapper">
                <?php include 'include/sidebar.php'; ?>


                <div class="content">
                   

                    <div class="orders-table-card" id="ordersSection" style="display: block;">
                        <h4>Your Orders</h4>
                        <div class="table-responsive">
                            <table class="table table-striped orders-table">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Date</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orderData as $row): ?>

                                        <tr>
                                            <td>#<?php echo $row['order_number']; ?></td>
                                            <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                                            <td><?= $row['count'] ??""?> item<?= $row['count'] > 1 ? 's' : '' ?></td>
                                            <td>Rs. <?= $row['total']?></td>
                                            <td><?php echo $row['payment_status']; ?></td>
                                            <td><button class="btn btn-dark btn-sm"><a href="abayalooks-order-details.php?id=<?php echo base64_encode($row['order_id']); ?>"
                                                        class="text-white">View</a></button></td>
                                        </tr>
                                    <?php endforeach; ?>

                                    <!-- <tr>
                                        <td>#ORD1002</td>
                                        <td>2025-07-18</td>
                                        <td>1 item</td>
                                        <td>$55.00</td>
                                        <td>Processing</td>
                                        <td><button class="btn btn-dark btn-sm"><a href="abayalooks-order-details.html"
                                                    class="text-white">View</a></button></td>
                                    </tr>
                                    <tr>
                                        <td>#ORD1003</td>
                                        <td>2025-07-15</td>
                                        <td>2 items</td>
                                        <td>$210.00</td>
                                        <td>Shipped</td>
                                        <td><button class="btn btn-dark btn-sm"><a href="abayalooks-order-details.html"
                                                    class="text-white">View</a></button></td>
                                    </tr>
                                    <tr>
                                        <td>#ORD1001</td>
                                        <td>2025-07-20</td>
                                        <td>3 items</td>
                                        <td>$150.00</td>
                                        <td>Delivered</td>
                                        <td><button class="btn btn-dark btn-sm"><a href="abayalooks-order-details.html"
                                                    class="text-white">View</a></button></td>
                                    </tr>
                                    <tr>
                                        <td>#ORD1002</td>
                                        <td>2025-07-18</td>
                                        <td>1 item</td>
                                        <td>$55.00</td>
                                        <td>Processing</td>
                                        <td><button class="btn btn-dark btn-sm"><a href="abayalooks-order-details.html"
                                                    class="text-white">View</a></button></td>
                                    </tr>
                                    <tr>
                                        <td>#ORD1003</td>
                                        <td>2025-07-15</td>
                                        <td>2 items</td>
                                        <td>$210.00</td>
                                        <td>Shipped</td>
                                        <td><button class="btn btn-dark btn-sm"><a href="abayalooks-order-details.html"
                                                    class="text-white">View</a></button></td>
                                    </tr> -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>


    </div>
    <?php include 'include/footer.php'; ?>

</body>

<script src="js/jquery.min.js" defer=""></script>
<script src="js/popper.js" defer=""></script>
<script src="js/bootstrap.js" defer=""></script>
<script src="js/custom.js" defer=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebarLinks = document.querySelectorAll('.sidebar .nav-link');
        const contentSections = document.querySelectorAll('.content > div'); // Select all direct child divs of .content

        const profileEditIcon = document.getElementById('profileEditIcon');
        const profileDetails = document.getElementById('profileDetails');
        const profileEditForm = document.getElementById('profileEditForm');
        const cancelEditBtn = document.getElementById('cancelEditBtn');
        const saveProfileBtn = document.getElementById('saveProfileBtn');

        // Function to show a specific content section and hide others
        function showSection(sectionId) {
            contentSections.forEach(section => {
                section.classList.remove('active');
            });
            document.getElementById(sectionId).classList.add('active');

            // Reset profile edit form if switching away from profile section
            if (sectionId !== 'profileSection') {
                profileEditForm.style.display = 'none';
                profileDetails.style.display = 'block';
                profileEditIcon.style.display = 'block';
            }
        }

        // Handle sidebar link clicks
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function (event) {
                event.preventDefault(); // Prevent default link behavior
                sidebarLinks.forEach(item => item.classList.remove('active')); // Remove active from all
                this.classList.add('active'); // Add active to clicked link

                const targetSection = this.dataset.section; // Get the data-section value
                if (targetSection) {
                    // Map data-section to actual section IDs
                    const sectionMap = {
                        'profile': 'profileSection',
                        'change-password': 'changePasswordSection',
                        'orders': 'ordersSection'
                    };
                    showSection(sectionMap[targetSection]);
                }
            });
        });

        // Handle profile edit icon click
        profileEditIcon.addEventListener('click', function () {
            profileDetails.style.display = 'none';
            profileEditForm.style.display = 'block';
            profileEditIcon.style.display = 'none';
        });

        // Handle cancel edit button click
        cancelEditBtn.addEventListener('click', function () {
            profileEditForm.style.display = 'none';
            profileDetails.style.display = 'block';
            profileEditIcon.style.display = 'block';
        });

        // Placeholder for Save Changes button functionality
        saveProfileBtn.addEventListener('click', function () {
            alert('Save Profile Changes clicked! (Functionality to save data not implemented)');
            profileEditForm.style.display = 'none';
            profileDetails.style.display = 'block';
            profileEditIcon.style.display = 'block';
        });

        // Initial display: Ensure profile section is active on page load
        showSection('profileSection');
    });
</script>

</html>