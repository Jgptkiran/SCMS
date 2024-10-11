<?php
// Include the database connection
include 'db.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['customerId'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details
$customerId = $_SESSION['customerId'];
$query = "SELECT customerId, firstName, lastName, email, contactNumber, address FROM customers WHERE customerId = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customerId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $contactNumber = trim($_POST['contactNumber']);
    $address = trim($_POST['address']);
    $password = trim($_POST['password']);

    // Update user details
    $updateQuery = "UPDATE customers SET firstName = ?, lastName = ?, email = ?, contactNumber = ?, address = ? WHERE customerId = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("sssssi", $firstName, $lastName, $email, $contactNumber, $address, $customerId);
    $updateStmt->execute();
    $updateStmt->close();

    // Handle password change if provided
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $passwordQuery = "UPDATE customers SET password = ? WHERE customerId = ?";
        $passwordStmt = $conn->prepare($passwordQuery);
        $passwordStmt->bind_param("si", $hashedPassword, $customerId);
        $passwordStmt->execute();
        $passwordStmt->close();
    }

    echo "<p>Details updated successfully!</p>";

    // Refresh user data
    header("Location: my_details.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Details - Ansole Sports Club</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="main-content">
        <div class="top-bar">
            <h1>My Details</h1>
            <?php include 'userinfo.php'; ?>
        </div>

        <!-- Display User Details -->
        <?php if ($_SERVER["REQUEST_METHOD"] != "POST" && empty($_GET['edit'])): ?>
            <div class="details-view">
                <p><strong>First Name:</strong> <?php echo htmlspecialchars($user['firstName']); ?></p>
                <p><strong>Last Name:</strong> <?php echo htmlspecialchars($user['lastName']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($user['contactNumber']); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
                <button onclick="window.location.href='my_details.php?edit=true'">Edit</button>
            </div>
        <?php endif; ?>

        <!-- Edit User Details Form -->
        <?php if (!empty($_GET['edit'])): ?>
            <form method="post" action="my_details.php">
                <label for="firstName">First Name:</label>
                <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($user['firstName']); ?>" required><br>

                <label for="lastName">Last Name:</label>
                <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($user['lastName']); ?>" required><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>

                <label for="contactNumber">Contact Number:</label>
                <input type="text" id="contactNumber" name="contactNumber" value="<?php echo htmlspecialchars($user['contactNumber']); ?>" required><br>

                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required><br>

                <label for="password">New Password (Leave blank to keep current password):</label>
                <input type="password" id="password" name="password"><br>

                <button type="submit">Update Details</button>
                <button type="button" onclick="window.location.href='my_details.php'">Cancel</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
