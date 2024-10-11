<?php
$con = new mysqli("localhost", "root", "", "ansoles");

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Assuming you've connected to the database already
$customerId = $_POST['customerId'];

// Fetch customer details using customerId
$query = "SELECT * FROM customers WHERE customerId = ?";
$stmt = $con->prepare($query);
$stmt->bind_param('i', $customerId);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Customer</title>

    <link rel="stylesheet" href="dashboard.css">

</head>
<body>
<div class="edit-customer-form">
    <h2>Edit Customer</h2>
    <form action="update_customer.php" method="post">
        <input type="hidden" name="customerId" value="<?php echo htmlspecialchars($customer['customerId']); ?>">
        
        <label for="firstName">First Name:</label>
        <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($customer['firstName']); ?>" required><br>

        <label for="lastName">Last Name:</label>
        <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($customer['lastName']); ?>" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($customer['email']); ?>" required><br>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($customer['address']); ?>" required><br>

        <label for="contactNumber">Phone Number:</label>
        <input type="text" id="contactNumber" name="contactNumber" value="<?php echo htmlspecialchars($customer['contactNumber']); ?>" required><br>

        <label for="password">New Password (Leave blank to keep current):</label>
        <input type="password" id="password" name="password"><br> <!-- Optional Password Field -->

        <button type="submit">Update</button>
    </form>
</div>

</body>
</html>
