<?php
// Database connection
$con = new mysqli("localhost", "root", "", "ansoles");

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Handle form submission
if (isset($_POST['submit'])) {
    // Retrieve form data and trim leading/trailing whitespace
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $password = trim($_POST['password']);
    $phone = trim($_POST['phone']);

    // Basic validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($address) || empty($password) || empty($phone)) {
        $error_message = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please enter a valid email address.";
    } else {
        // Check if the email already exists
        $check_email_query = "SELECT * FROM customers WHERE email = ?";
        $stmt = $con->prepare($check_email_query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Email already exists
            $error_message = "This email is already registered. Please use a different email address.";
        } else {
            // Hash the password before storing it
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Prepare and bind the SQL statement
            $stmt = $con->prepare("INSERT INTO customers (firstName, lastName, email, address, password, contactNumber) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $first_name, $last_name, $email, $address, $hashed_password, $phone);

            // Execute the statement
            if ($stmt->execute()) {
                // Redirect to a success page
                header("Location: success.php");
                exit();
            } else {
                $error_message = "Error: " . $stmt->error;
            }
        }

        // Close the statement
        $stmt->close();
    }
}

// Close the database connection
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Ansole Sports Club</title>
    <link rel="stylesheet" href="signupstyles.css">
</head>
<body>
    <header>
        <h1>Welcome to Ansole Sports Club</h1>
    </header>
    <main>
        <section class="signup-section">
            <h2>Sign Up</h2>
            <?php
            // Display error message if set
            if (isset($error_message)) {
                echo "<p style='color: red;'>$error_message</p>";
            }
            ?>
            <form action="index.php" method="post">
                <label for="first-name">First Name:</label>
                <input type="text" id="first-name" name="first_name" required>

                <label for="last-name">Last Name:</label>
                <input type="text" id="last-name" name="last_name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <label for="phone">Phone Number:</label>
                <input type="tel" id="phone" name="phone" required>

                <button type="submit" name="submit">Sign Up</button>
            </form>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Ansole Sports Club. All rights reserved.</p>
    </footer>
</body>
</html>
