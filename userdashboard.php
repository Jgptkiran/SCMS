<?php
// Include the database connection
include 'db.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['customerId'])) {
    header("Location: login.php");
    exit();
}

// Fetch facility data
$facilitiesQuery = "SELECT FacilityID, Name, Image, Description FROM facilities WHERE FacilityID IN (1, 2, 3, 4)";
$facilitiesResult = $conn->query($facilitiesQuery);

if (!$facilitiesResult) {
    die("Error fetching facilities: " . $conn->error);
}

// Fetch bookings data for the logged-in user
$bookingsQuery = "SELECT FacilityID, date, slots FROM bookings WHERE customerId = ?";
$stmt = $conn->prepare($bookingsQuery);
$stmt->bind_param("i", $_SESSION['customerId']);
$stmt->execute();
$bookingsResult = $stmt->get_result();

// Organize bookings data by facility
$bookings = [];
while ($row = $bookingsResult->fetch_assoc()) {
    $bookings[$row['FacilityID']][] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Ansole Sports Club</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <h1>Dashboard</h1>
            <div class="user-info">
                <?php if (isset($_SESSION['firstName'])): ?>
                    <span><?php echo htmlspecialchars($_SESSION['firstName']); ?></span>
                    <a href="logout.php" class="logout-button">Logout</a>
                <?php else: ?>
                    <span>Guest</span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Facilities and Bookings -->
        <div class="facilities-section">
            <?php if ($facilitiesResult->num_rows > 0): ?>
                <?php while ($facility = $facilitiesResult->fetch_assoc()): ?>
                    <div class="facility-card">
                        <img src="<?php echo htmlspecialchars($facility['Image']); ?>" alt="<?php echo htmlspecialchars($facility['Name']); ?>" class="facility-image">
                        <h3><?php echo htmlspecialchars($facility['Name']); ?></h3>
                        <p><?php echo htmlspecialchars($facility['Description']); ?></p>

                        <h4>Your Bookings:</h4>
                        <ul>
                            <?php
                            $facilityID = $facility['FacilityID'];
                            if (isset($bookings[$facilityID])):
                                foreach ($bookings[$facilityID] as $booking):
                                    // Convert the slots string to an array
                                    $slotsArray = !empty($booking['slots']) ? explode(',', $booking['slots']) : [];

                                    // Validate and parse the slots
                                    if (!empty($slotsArray)) {
                                        $firstSlot = explode('-', trim($slotsArray[0]));
                                        $lastSlot = explode('-', trim($slotsArray[count($slotsArray) - 1]));

                                        // Get the start and end times safely
                                        $startTime = isset($firstSlot[0]) ? trim($firstSlot[0]) : 'N/A';
                                        $endTime = isset($lastSlot[1]) ? trim($lastSlot[1]) : (isset($firstSlot[1]) ? trim($firstSlot[1]) : 'N/A');
                                    } else {
                                        $startTime = 'N/A';
                                        $endTime = 'N/A';
                                    }
                            ?>
                                    <li>
                                        Date: <?php echo htmlspecialchars($booking['date']); ?><br>
                                        Time: <?php echo htmlspecialchars($startTime) . ' - ' . htmlspecialchars($endTime); ?>
                                    </li>
                            <?php
                                endforeach;
                            else:
                            ?>
                                <li>No bookings for this facility.</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No facilities found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
