<?php
// Connect to the database
$con = new mysqli("localhost", "root", "", "ansoles");

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Get the facility ID from the query parameter
$facilityId = $_GET['facilityId'];

// Fetch the existing facility details
$query = "SELECT * FROM facilities WHERE FacilityID = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $facilityId);
$stmt->execute();
$result = $stmt->get_result();
$facility = $result->fetch_assoc();

// Handle form submission to update facility
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $facilityName = $_POST['facilityName'];
    $description = $_POST['description'];
    $HourlyRate = $_POST['rate'];

    // Update the facility details in the database
    $updateQuery = "UPDATE facilities SET Name = ?, Description = ?, HourlyRate = ? WHERE FacilityID = ?";
    $updateStmt = $con->prepare($updateQuery);
    $updateStmt->bind_param("sssi", $facilityName, $description, $HourlyRate, $facilityId);

    if ($updateStmt->execute()) {
        echo "Facility updated successfully!";
        header("Location: admindashboard.php#facility-section"); // Redirect to the dashboard after update
        exit();
    } else {
        echo "Error updating facility: " . $con->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Facility - Ansole Sports Club</title>
    <link rel="stylesheet" href="dashboard.css">
    
</head>
<body>
<div class="edit-facility-form">
    <h1>Edit Facility</h1>
    <form action="edit_facility.php?facilityId=<?php echo $facilityId; ?>" method="POST">
        <label for="facilityName">Facility Name:</label>
        <input type="text" id="facilityName" name="facilityName" value="<?php echo htmlspecialchars($facility['Name']); ?>" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?php echo htmlspecialchars($facility['Description']); ?></textarea>

        <label for="location">Hourly Rate:</label>
        <input type="text" id="location" name="rate" value="<?php echo htmlspecialchars($facility['HourlyRate']); ?>" required>

        <button type="submit">Update Facility</button>
    </form>
</div>

</body>
</html>
