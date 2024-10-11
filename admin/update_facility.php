<?php
// Connect to the database
$con = new mysqli("localhost", "root", "", "ansoles");

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Handle form submission to update facility
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $facilityId = $_POST['facilityId'];
    $facilityName = $_POST['Name'];
    $Description = $_POST['Description'];
    $HourlyRate = $_POST['HourlyRate'];

    // Update the facility details in the database
    $updateQuery = "UPDATE facilities SET facilityName = ?, Description = ?, HourlyRate = ? WHERE FacilityID = ?";
    $updateStmt = $con->prepare($updateQuery);
    $updateStmt->bind_param("sssi", $facilityName, $Description, $HourlyRate, $facilityId);

    if ($updateStmt->execute()) {
        echo "Facility updated successfully!";
        header("location: admindashboard.php#facility-section"); // Redirect to the dashboard after update
        exit();
    } else {
        echo "Error updating facility: " . $con->error;
    }
}

// Close the database connection
$con->close();
?>
