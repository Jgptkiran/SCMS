<?php
// Include the database connection
include 'db.php'; // Assuming you have db.php for the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form data and sanitize
    $firstName = htmlspecialchars(trim($_POST['first_name']));
    $lastName = htmlspecialchars(trim($_POST['last_name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $contactNumber = htmlspecialchars(trim($_POST['phone']));
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT); // Hash the password for security
    $address = htmlspecialchars(trim($_POST['address'])); // Assuming you have an address field in your form

    // Prepare an SQL statement to insert data
    $sql = "INSERT INTO customers (firstName, lastName, email, contactNumber, password, address) 
            VALUES (?, ?, ?, ?, ?, ?)";

    // Initialize a statement and prepare it
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameters to the statement
        $stmt->bind_param("ssssss", $firstName, $lastName, $email, $contactNumber, $password, $address);

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect to index.php on success
            header('Location: index.php');
            exit; // Stop further execution
        } else {
            // Handle errors during execution
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        // Handle errors during statement preparation
        echo "Error preparing statement: " . $conn->error;
    }
}

// Close the connection
$conn->close();
?>
