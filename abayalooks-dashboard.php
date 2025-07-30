<?php
if (!isset($_SESSION)) {
    session_start();
}
error_reporting(E_ALL);
require "config/config.php";
require "config/common.php";
include_once('config/cart.php');
require "config/authentication.php";

$conn = new dbClass();
$common = new CommProducts();
$customer = new CommCustomers();

$auth = new Authentication();
// Assuming $auth is the same as $customer (CommCustomers)
// $auth = $customer;

$customer->checkSession($_SESSION['USER_LOGIN'] ?? null);
$userDetail = $customer->userDetails($_SESSION['USER_LOGIN']);

// Fetch billing and shipping addresses
$userBillAddressDetail = $auth->userBillDetails($_SESSION['USER_LOGIN']);
$userAllShipDetail = $auth->userAllShipDetails($_SESSION['USER_LOGIN']);
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
                    <div class="profile-card active" id="profileSection">
                        <a href="abayalooks-edit-profile.php" class="edit-icon" id="profileEditIcon">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>

                        <div id="profileDetails">
                            <div class="profile-detail-row">
                                <strong>Name:</strong>
                                <span><?php echo htmlspecialchars($userDetail['first_name'] ?? '') . ' ' . htmlspecialchars($userDetail['surname'] ?? ''); ?></span>
                            </div>
                            <div class="profile-detail-row">
                                <strong>Phone No:</strong>
                                <span><?php echo htmlspecialchars($userDetail['phone'] ?? ''); ?></span>
                            </div>
                            <!-- <div class="profile-detail-row">
                                <strong>Date of Birth:</strong>
                                <span>6th October 2002</span>
                            </div> -->
                            <div class="profile-detail-row">
                                <strong>Email:</strong>
                                <span><?php echo htmlspecialchars($userDetail['email'] ?? ''); ?></span>
                            </div>
                            <div class="profile-detail-row">
                                <strong>Address:</strong>
                                <span><?php echo htmlspecialchars($userDetail['place_name'] ?? '') . ', ' . htmlspecialchars($userDetail['street_name'] ?? '') . ', ' . htmlspecialchars($userDetail['country'] ?? '') . ', ' . htmlspecialchars($userDetail['postal_code'] ?? ''); ?></span>
                            </div>
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
<!-- <script>
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
</script> -->

</html>