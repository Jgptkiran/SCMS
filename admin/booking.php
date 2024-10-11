<?php
$con = new mysqli("localhost", "root", "", "ansoles");

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Query to fetch customer data
$query = "SELECT * FROM bookings";
$result = $con->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Data - Ansole Sports Club</title>
    <link rel="stylesheet" href="dashboard.css"> <!-- Optional CSS for styling -->
</head>
<body>
    <header>
        <h1>Customer Data</h1>
    </header>
    <main>
        <section class="customer-data-section">
            <h2>List of Customers</h2>
            <table>
    <thead>
        <tr>
            <th>BookingId</th>
            <th>CustomerId</th>
            <th>Date</th>
            <th>Slots</th>
            <th>Amount</th>
            <th>FacilityId</th>
            <th>Action</th> <!-- New Action Column -->
        </tr>
    </thead>
    <tbody>
        <?php
        // Check if there are results and loop through them
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['bookingId']) . "</td>";
                echo "<td>" . htmlspecialchars($row['customerId']) . "</td>";
                echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                echo "<td>" . htmlspecialchars($row['slots']) . "</td>";
                echo "<td>" . htmlspecialchars($row['amount']) . "</td>";
                echo "<td>" . htmlspecialchars($row['FacilityID']) . "</td>";
                echo "<td>
                        <form action='delete_booking.php' method='post' onsubmit='return confirm(\"Are you sure you want to delete this booking?\");'>
                            <input type='hidden' name='bookingId' value='" . htmlspecialchars($row['bookingId']) . "'>
                            <button type='submit' name='delete' style='background-color: red; color: white; border: none; padding: 5px 10px; cursor: pointer;'>Delete</button>
                        </form>
                      </td>"; // Delete button
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No bookings found.</td></tr>"; // Updated colspan to 7
        }

        // Close the database connection
        $con->close();
        ?>
    </tbody>
</table>

        </section>
    </main>
    <footer>
        <p>&copy; 2024 Ansole Sports Club. All rights reserved.</p>
    </footer>
</body>
</html>
