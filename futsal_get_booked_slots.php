<?php
header('Content-Type: application/json');

// Database configuration
$host = 'localhost';
$dbname = 'ansole';
$username = 'root';
$password = '';

// Establish database connection using PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Return a JSON error message if the connection fails
    echo json_encode(['error' => 'Database connection failed.']);
    exit();
}

// Validate the date parameter
$date = isset($_GET['date']) ? trim($_GET['date']) : '';
if (empty($date) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    echo json_encode(['error' => 'Invalid or missing date parameter.']);
    exit();
}

try {
    // Fetch booked slots for the specified date and FacilityID
    $stmt = $pdo->prepare("SELECT slots FROM bookings WHERE date = ? AND FacilityID = 2");
    $stmt->execute([$date]);
    $slots = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Process the fetched slots to create a list of unique booked slots
    $bookedSlots = [];
    foreach ($slots as $slotList) {
        $bookedSlots = array_merge($bookedSlots, explode(',', $slotList));
    }
    $bookedSlots = array_unique($bookedSlots);

    // Return the booked slots as a JSON response
    echo json_encode(['bookedSlots' => $bookedSlots]);
} catch (Exception $e) {
    // Return a JSON error message if the query fails
    echo json_encode(['error' => 'Failed to fetch booked slots.']);
}
?>
