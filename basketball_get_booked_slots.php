<?php
header('Content-Type: application/json');

// Database configuration
$host = 'localhost';
$dbname = 'ansole';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}

$date = $_GET['date'];

try {
    $stmt = $pdo->prepare("SELECT slots FROM bookings WHERE date = ? and FacilityID = 1");
    $stmt->execute([$date]);
    $slots = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $bookedSlots = [];
    foreach ($slots as $slotList) {
        $bookedSlots = array_merge($bookedSlots, explode(',', $slotList));
    }

    echo json_encode(['bookedSlots' => array_unique($bookedSlots)]);
} catch (Exception $e) {
    echo json_encode(['error' => 'Failed to fetch booked slots: ' . $e->getMessage()]);
}
?>
