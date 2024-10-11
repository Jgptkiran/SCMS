<?php
// Database connection
$con = new mysqli("localhost", "root", "", "ansoles");

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Queries to fetch data
$customersQuery = "SELECT * FROM customers";
$bookingsQuery = "SELECT * FROM bookings";
$facilitiesQuery = "SELECT * FROM facilities";

$customersResult = $con->query($customersQuery);
$bookingsResult = $con->query($bookingsQuery);
$facilitiesResult = $con->query($facilitiesQuery);
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
<div class="sidebar">
    <div class="logo">
        <a href="admindashboard.php"><img src="../logo.jpg" alt="Logo"></a>
        <h2>ANSOLE BOOKING</h2>
    </div>
    <ul>
        <li><a href="#customer-section" class="nav-link"><i class="fas fa-user"></i> Customer Details</a></li>
        <li><a href="#booking-section" class="nav-link"><i class="fas fa-calendar"></i> Booking Details</a></li>
        <li><a href="#facility-section" class="nav-link"><i class="fas fa-building"></i> Facility Management</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content">
    <!-- Top Bar -->
    <div class="top-bar">
        <h1>Dashboard</h1>
        <div class="user-info">
            <a href="logout.php" class="logout-button">Logout</a>
            <a href="index.php" class="logout-button">Add New Uswe</a>
        </div>
    </div>

    <!-- Booking Section -->
    <section id="booking-section" class="section">
        <h2>List of Bookings</h2>
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Customer ID</th>
                    <th>Date</th>
                    <th>Slots</th>
                    <th>Amount</th>
                    <th>Facility ID</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($bookingsResult->num_rows > 0) {
                    while ($row = $bookingsResult->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['bookingId']) . "</td>
                                <td>" . htmlspecialchars($row['customerId']) . "</td>
                                <td>" . htmlspecialchars($row['date']) . "</td>
                                <td>" . htmlspecialchars($row['slots']) . "</td>
                                <td>" . htmlspecialchars($row['amount']) . "</td>
                                <td>" . htmlspecialchars($row['FacilityID']) . "</td>
                                <td>
                                    <form action='delete_booking.php' method='post' onsubmit='return confirm(\"Are you sure you want to delete this booking?\");'>
                                        <input type='hidden' name='bookingId' value='" . htmlspecialchars($row['bookingId']) . "'>
                                        <button type='submit'>Delete</button>
                                    </form>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No bookings found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>

    <!-- Customer Section -->
    <section id="customer-section" class="section">
        <h2>List of Customers</h2>
        <table>
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Phone Number</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($customersResult->num_rows > 0) {
                    while ($row = $customersResult->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['firstName']) . "</td>
                                <td>" . htmlspecialchars($row['lastName']) . "</td>
                                <td>" . htmlspecialchars($row['email']) . "</td>
                                <td>" . htmlspecialchars($row['address']) . "</td>
                                <td>" . htmlspecialchars($row['contactNumber']) . "</td>
                                <td>
                                    <form action='edit_customer.php' method='post' style='display:inline-block;'>
                                        <input type='hidden' name='customerId' value='" . htmlspecialchars($row['customerId']) . "'>
                                        <button type='submit'>Edit</button>
                                    </form>
                                    <form action='delete_customer.php' method='post' onsubmit='return confirm(\"Are you sure you want to delete this customer?\");' style='display:inline-block;'>
                                        <input type='hidden' name='customerId' value='" . htmlspecialchars($row['customerId']) . "'>
                                        <button type='submit'>Delete</button>
                                    </form>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No customers found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>

    <!-- Facility Section -->
    <section id="facility-section" class="section">
        <h2>List of Facilities</h2>
        <table>
            <thead>
                <tr>
                    <th>Facility Name</th>
                    <th>Description</th>
                    <th>Hourly Rate</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($facilitiesResult->num_rows > 0) {
                    while ($row = $facilitiesResult->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['Name']) . "</td>
                                <td>" . htmlspecialchars($row['Description']) . "</td>
                                <td>" . htmlspecialchars($row['HourlyRate']) . "</td>
                                <td>
                                    <a href='edit_facility.php?facilityId=" . htmlspecialchars($row['FacilityID']) . "'>Edit</a>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No facilities found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const navLinks = document.querySelectorAll('.nav-link');
        const sections = document.querySelectorAll('.section');

        function hideSections() {
            sections.forEach(section => section.classList.remove('active'));
        }

        function showSection() {
            const hash = window.location.hash || '#booking-section';
            hideSections();
            const activeSection = document.querySelector(hash);
            if (activeSection) {
                activeSection.classList.add('active');
            }
        }

        navLinks.forEach(link => {
            link.addEventListener('click', showSection);
        });

        showSection();
    });
</script>

</body>
</html>
