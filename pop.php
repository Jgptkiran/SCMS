<?php
// Database connection (replace with your credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ansole";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Parameters to adjust for slot generation
$facilityID = 1; // Example FacilityID; change this as needed
$date = '2024-09-11'; // Replace with the date you want to populate slots for

// Function to populate time slots
function populateTimeSlots($date, $facilityID) {
    global $conn;

    $startTime = 5; // Start time: 5 AM
    $endTime = 22; // End time: 10 PM

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO timeslots (FacilityID, Date, StartTime, EndTime, Availability) VALUES (?, ?, ?, ?, 'Available')");

    for ($hour = $startTime; $hour < $endTime; $hour++) {
        for ($minute = 0; $minute < 60; $minute += 30) {
            $start = sprintf('%02d:%02d:00', $hour, $minute);
            $endHour = $hour;
            $endMinute = $minute + 30;
            if ($endMinute >= 60) {
                $endMinute = 0;
                $endHour += 1;
            }
            $end = sprintf('%02d:%02d:00', $endHour, $endMinute);

            // Bind parameters and execute statement
            $stmt->bind_param("isss", $facilityID, $date, $start, $end);
            $stmt->execute();
        }
    }

    $stmt->close();
    echo "Time slots populated successfully for date: $date";
}

// Call function to populate slots
populateTimeSlots($date, $facilityID);

$conn->close();
?>
