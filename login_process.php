<?php
// Include the database connection
include 'db.php'; // Assuming you have a db.php file for database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture and sanitize form data
    $email = htmlspecialchars(trim($_POST['email']));
    $password = trim($_POST['password']); // Keep the password as plain text for password_verify()

    // Prepare SQL to fetch the user based on email
    $sql = "SELECT customerId, firstName, lastName, email, contactNumber, address, password FROM customers WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind the email parameter to the SQL query
        $stmt->bind_param("s", $email);

        // Execute the query
        $stmt->execute();

        // Store result to check if any row was returned
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Bind result variables in the correct order as they appear in the SELECT statement
            $stmt->bind_result($customerId, $firstName, $lastName, $email, $contactNumber, $address, $hashed_password);
            $stmt->fetch();

            // Verify the password
            if (password_verify($password, $hashed_password)) {
                // Password is correct, start a session
                session_start();
                // Store user information in session variables
                $_SESSION['customerId'] = $customerId;
                $_SESSION['firstName'] = $firstName;
                $_SESSION['lastName'] = $lastName;
                $_SESSION['email'] = $email;
                $_SESSION['contactNumber'] = $contactNumber;
                $_SESSION['address'] = $address;

                // Redirect to the user dashboard
                header("Location: userdashboard.php");
                exit;
            } else {
                // Password is incorrect
                $error_message = "Invalid password. Please try again.";
                header("Location: login.php?error=" . urlencode($error_message));
                exit;
            }
        } else {
            // No account found with the given email
            $error_message = "No account found with that email.";
            header("Location: login.php?error=" . urlencode($error_message));
            exit;
        }

        // Close the statement
        $stmt->close();
    } else {
        // SQL statement preparation error
        $error_message = "Error preparing statement: " . $conn->error;
        header("Location: login.php?error=" . urlencode($error_message));
        exit;
    }
}

// Close the connection
$conn->close();
?>
