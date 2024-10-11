<?php
$con = new mysqli("localhost", "root", "", "ansoles");

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Check if delete is requested
$customerId = $_POST['customerId'];

// Prepare the delete query
$query = "DELETE FROM customers WHERE customerId = ?";
$stmt = $con->prepare($query);
$stmt->bind_param('i', $customerId);

if ($stmt->execute()) {
    // Redirect back to the dashboard after deletion
    header("Location:  admindashboard.php#customer-section");
} else {
    echo "Error deleting customer.";
}
?>
