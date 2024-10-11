<?php
$con = new mysqli("localhost", "root", "", "ansoles");

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Query to fetch customer data
$query = "SELECT * FROM customers";
$result = $con->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Data - Ansole Sports Club</title>
    <link rel="stylesheet" href="styles.css"> <!-- Optional CSS for styling -->
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
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Phone Number</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Check if there are results and loop through them
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['firstName']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['lastName']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['contactNumber']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No customers found.</td></tr>";
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
