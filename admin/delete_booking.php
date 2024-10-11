<?php
$con = new mysqli("localhost", "root", "", "ansoles");

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Check if delete is requested
if (isset($_POST['delete'])) {
    $bookingId = $_POST['bookingId'];

    // Prepare and execute delete statement
    $stmt = $con->prepare("DELETE FROM bookings WHERE bookingId = ?");
    $stmt->bind_param("i", $bookingId); // Assuming bookingId is an integer
    if ($stmt->execute()) {
        echo "Booking deleted successfully.";
    } else {
        echo "Error deleting booking: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
}

$con->close();

// Redirect back to the page showing the table
header("Location: admindashboard.php"); // Change to your actual table page
exit();
?>
