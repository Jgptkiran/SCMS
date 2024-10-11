<?php
header('Content-Type: application/json');

// Database configuration
include 'db.php';

// Retrieve data from POST request
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];
$contactNumber = $_POST['contactNumber'];
$address = $_POST['address'];
$date = $_POST['date'];
$slots = $_POST['slots'];
$amount = $_POST['amount'];
$facilityID = $_POST['facilityID'];

// Generate booking ID on the server side
$bookingId = rand(100000, 999999);

try {
    $pdo->beginTransaction();

    // Check if the email already exists in the customers table
    $stmt = $pdo->prepare("SELECT customerId FROM customers WHERE email = ?");
    $stmt->execute([$email]);
    $existingCustomer = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingCustomer) {
        // If customer exists, use the existing customerId
        $customerId = $existingCustomer['customerId'];
    } else {
        // If customer does not exist, insert new customer details
        $stmt = $pdo->prepare("INSERT INTO customers (firstName, lastName, email, contactNumber, address) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$firstName, $lastName, $email, $contactNumber, $address]);
        $customerId = $pdo->lastInsertId();
    }

    // Insert booking details
    $stmt = $pdo->prepare("INSERT INTO bookings (bookingId, customerId, date, slots, amount, FacilityID) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$bookingId, $customerId, $date, $slots, $amount, $facilityID]);

    $pdo->commit();
    echo json_encode(['success' => true, 'bookingId' => $bookingId]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Booking failed: ' . $e->getMessage()]);
}
?>
