<?php
// Include the database configuration
include 'db.php';

// Get the facility ID from the URL
$facilityID = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Validate the Facility ID
if ($facilityID <= 0) {
    echo 'Invalid Facility ID.';
    exit();
}

// Fetch facility details using mysqli
try {
    $stmt = $conn->prepare("SELECT * FROM facilities WHERE FacilityID = ?");
    $stmt->bind_param("i", $facilityID);
    $stmt->execute();
    $result = $stmt->get_result();
    $facility = $result->fetch_assoc();

    // Check if the facility exists
    if (!$facility) {
        echo 'Facility not found.';
        exit();
    }

    // Conditional redirection based on Facility ID
    switch ($facilityID) {
        case 1:
            header('Location: basketball1.php');
            exit();
        case 2:
            header('Location: booking1.php');
            exit();
        case 3:
            header('Location: swimming1.php');
            exit();
        case 4:
            header('Location: tennis1.php');
            exit();
        default:
            echo 'No redirection available for this facility.';
            exit();
    }
} catch (Exception $e) {
    echo 'Failed to fetch facility details: ' . $e->getMessage();
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
