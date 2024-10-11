<?php
// Include the database connection
include 'db.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['customerId'])) {
    // Redirect to login page
    header("Location: login.php");
    exit();
}

// Fetch facilities data from the database
$facilitiesQuery = "SELECT FacilityID, Name, Description, Image FROM facilities";
$facilitiesResult = $conn->query($facilitiesQuery);

if (!$facilitiesResult) {
    die("Error fetching facilities: " . $conn->error);
}

// Store facilities data in an array
$facilities = [];
while ($row = $facilitiesResult->fetch_assoc()) {
    $facilities[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make a Booking - Ansole Sports Club</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <h1>Make a Booking</h1>
            <?php include 'userinfo.php'; ?>
        </div>
        
        <!-- Facilities Section -->
        <div class="facilities-section">
            <?php if (!empty($facilities)): ?>
                <?php foreach ($facilities as $facility): ?>
                    <div class="facility-card">
                        <img src="<?php echo htmlspecialchars($facility['Image']); ?>" alt="<?php echo htmlspecialchars($facility['Name']); ?>" class="facility-image">
                        <h3><?php echo htmlspecialchars($facility['Name']); ?></h3>
                        <p><?php echo htmlspecialchars($facility['Description']); ?></p>
                        <a href="facility_details1.php?id=<?php echo urlencode($facility['FacilityID']); ?>" class="learn-more">Learn more!</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No facilities available at the moment.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
