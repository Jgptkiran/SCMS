<?php
include 'db.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['customerId'])) {
    // Redirect to login page
    header("Location: login.php");
    exit();
}
?>
<?php
include 'sidebar.php';
?>
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <h1>Favourite List</h1>
            <?php include 'userinfo.php';?>
        </div>

        <!-- Favourite List -->
        <div class="favourite-list">
            <!-- Your favourite list implementation here -->
        </div>
    </div>
</body>
</html>
