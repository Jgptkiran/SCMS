<?php
header('Content-Type: application/json');

// Include the database connection
include 'db.php';

// Validate and get form data
$customerId = $_POST['customerId'] ?? null;
$facilityID = $_POST['facilityID'] ?? null;
$date = $_POST['date'] ?? null;
$slots = $_POST['slots'] ?? null; // Slots are expected in a format like "10:00 - 11:00"
$amount = $_POST['amount'] ?? null;

// Check for missing data
if (empty($customerId) || empty($facilityID) || empty($date) || empty($slots) || empty($amount)) {
    echo json_encode(['success' => false, 'message' => 'Missing booking details. Please fill in all required fields.']);
    exit();
}

// Get the current date and time
$currentDateTime = new DateTime();

// Combine the date and first time slot to get the booking start time
list($startTime, $endTime) = explode('-', trim($slots));
$bookingDateTime = DateTime::createFromFormat('Y-m-d H:i', $date . ' ' . trim($startTime));

if ($bookingDateTime === false || $bookingDateTime < $currentDateTime) {
    echo json_encode(['success' => false, 'message' => 'Booking time must be in the future.']);
    exit();
}

// Insert booking into the database
try {
    // Prepare the SQL query to insert the booking details
    $stmt = $conn->prepare("INSERT INTO bookings (customerId, FacilityID, date, slots, amount) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisss", $customerId, $facilityID, $date, $slots, $amount);
    $stmt->execute();

    // Check if the insertion was successful
    if ($stmt->affected_rows > 0) {
        $bookingId = $stmt->insert_id; // Get the booking ID of the newly inserted record
        echo json_encode(['success' => true, 'bookingId' => $bookingId]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to confirm booking. Please try again.']);
    }

    // Close the statement and the connection
    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    // Return a JSON error message if an exception occurs
    echo json_encode(['success' => false, 'message' => 'An error occurred while processing your request. Please try again later.']);
}
