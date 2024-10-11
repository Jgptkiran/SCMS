<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['customerId'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit;
}

// Retrieve customer details from session, with fallback default values if not set
$customerId = $_SESSION['customerId'] ?? '';
$firstName = $_SESSION['firstName'] ?? '';
$lastName = $_SESSION['lastName'] ?? '';
$email = $_SESSION['email'] ?? '';
$contactNumber = $_SESSION['contactNumber'] ?? '';
$address = $_SESSION['address'] ?? '';

// Get facility details (you can update these dynamically based on the selected facility)
$facilityID = 1; // Default facility ID, update dynamically as needed
$location = "Basketball Court"; // Update dynamically based on facility
$field = "Court 1"; // Update dynamically based on the selected field
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation - Ansole Sports Club</title>
    <link rel="stylesheet" href="booking.css"> <!-- Link to your CSS file -->
</head>
<body>
    <h1>Booking Confirmation</h1>
    <div id="bookingDetails">
        <h2>Booking Details</h2>
        <p>Location: <span id="location"><?php echo htmlspecialchars($location); ?></span></p>
        <p>Field: <span id="field"><?php echo htmlspecialchars($field); ?></span></p>
        <p>Booking Start Time: <span id="startTime"></span></p>
        <p>Booking End Time: <span id="endTime"></span></p>
        <p>Total Price: $<span id="totalPrice"></span></p>
    </div>
    <div id="customerDetails">
        <h2>Customer Details</h2>
        <p>First Name: <span><?php echo htmlspecialchars($firstName); ?></span></p>
        <p>Last Name: <span><?php echo htmlspecialchars($lastName); ?></span></p>
        <p>Email: <span><?php echo htmlspecialchars($email); ?></span></p>
        <p>Contact Number: <span><?php echo htmlspecialchars($contactNumber); ?></span></p>
        <p>Address: <span><?php echo htmlspecialchars($address); ?></span></p>
    </div>
    <form id="confirmBookingForm">
        <input type="hidden" name="customerId" value="<?php echo htmlspecialchars($customerId); ?>">
        <input type="hidden" name="facilityID" value="<?php echo htmlspecialchars($facilityID); ?>">
        <input type="hidden" id="startTimeInput" name="startTime">
        <input type="hidden" id="endTimeInput" name="endTime">
        <input type="hidden" id="amountInput" name="amount">
        <input type="hidden" name="firstName" value="<?php echo htmlspecialchars($firstName); ?>">
        <input type="hidden" name="lastName" value="<?php echo htmlspecialchars($lastName); ?>">
        <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
        <input type="hidden" name="address" value="<?php echo htmlspecialchars($address); ?>">
        <input type="hidden" name="contactNumber" value="<?php echo htmlspecialchars($contactNumber); ?>">

        <div id="terms">
            <input type="checkbox" id="termsCheckbox" required>
            <label for="termsCheckbox">I agree to the <a href="#" id="readMore">terms and conditions</a></label>
            <div id="termsDescription" style="display: none;">
                <p>Full terms and conditions...</p>
            </div>
        </div>

        <button type="submit">Confirm Booking</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const date = urlParams.get('date');
            const slots = urlParams.get('slots') ? urlParams.get('slots').split(',') : [];
            const amount = urlParams.get('amount');

            // Validate and populate booking details
            if (slots.length > 0 && amount) {
                document.getElementById('startTime').innerText = slots[0].split(' - ')[0];
                document.getElementById('endTime').innerText = slots[slots.length - 1].split(' - ')[1];
                document.getElementById('totalPrice').innerText = parseFloat(amount).toFixed(2);

                // Populate hidden form fields
                document.getElementById('startTimeInput').value = slots[0].split(' - ')[0];
                document.getElementById('endTimeInput').value = slots[slots.length - 1].split(' - ')[1];
                document.getElementById('amountInput').value = parseFloat(amount).toFixed(2);
            } else {
                alert('Invalid booking details. Please go back and select your booking again.');
                window.location.href = 'booking.php';
            }

            // Toggle terms and conditions description
            document.getElementById('readMore').addEventListener('click', function(event) {
                event.preventDefault();
                const termsDescription = document.getElementById('termsDescription');
                termsDescription.style.display = termsDescription.style.display === 'none' ? 'block' : 'none';
            });

            // Handle form submission
            document.getElementById('confirmBookingForm').addEventListener('submit', function(event) {
                event.preventDefault();

                const formData = new FormData(this);
                formData.append('date', date);
                formData.append('slots', slots.join(','));
                formData.append('amount', amount);

                fetch('confirm_booking1.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Booking confirmed! Booking ID: ' + data.bookingId);
                        window.location.href = 'userdashboard.php'; // Redirect after successful booking
                    } else {
                        alert('Booking failed: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            });
        });
    </script>
</body>
</html>
