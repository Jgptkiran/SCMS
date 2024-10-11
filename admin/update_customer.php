<?php
$con = new mysqli("localhost", "root", "", "ansoles");

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$customerId = $_POST['customerId'];
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];
$address = $_POST['address'];
$contactNumber = $_POST['contactNumber'];
$password = $_POST['password'];

// Check if the password field is filled; if so, hash the password
if (!empty($password)) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the new password
    $query = "UPDATE customers SET firstName = ?, lastName = ?, email = ?, address = ?, contactNumber = ?, password = ? WHERE customerId = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('ssssssi', $firstName, $lastName, $email, $address, $contactNumber, $hashedPassword, $customerId);
} else {
    // If password is not provided, update other details only
    $query = "UPDATE customers SET firstName = ?, lastName = ?, email = ?, address = ?, contactNumber = ? WHERE customerId = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('sssssi', $firstName, $lastName, $email, $address, $contactNumber, $customerId);
}

// Execute the query and check if the update was successful
if ($stmt->execute()) {
    header("Location: admindashboard.php#customer-section"); // Redirect to the dashboard on success
} else {
    echo "Error updating customer details.";
}
?>
