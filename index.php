<?php
    include 'db.php';
    // Fetch facilities data
    $result = $conn->query("SELECT FacilityID, Name, Description, Image FROM facilities");
    $facilities = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $facilities[] = $row;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sport Club Management System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Header with logo and buttons -->
    <?php 
    include 'header.php';
    ?>
    <!-- Gallery with sliding pictures -->
    <section class="gallery">
        <h2>ANSOLE SPORTS CLUB</h2>
        <div class="slideshow-container">
            <div class="mySlides fade">
                <img src="img1.jpg" class="gallery-image">
            </div>
            <div class="mySlides fade">
                <img src="img2.jpg" class="gallery-image">
            </div>
            <div class="mySlides fade">
                <img src="img3.jpg" class="gallery-image">
            </div>
            <!-- Signup Button -->
            <div class="signup-button-container">
                <a href="sign-up.html" class="signup-button">Become a Member</a>
            </div>
        </div>
    </section>
    <!-- Divider Line -->
    <hr class="section-divider">

    <!-- Booking options -->
    <section class="booking-options">
        <h2>Book Your Activity</h2>
        <div class="options-container">
            <?php foreach ($facilities as $facility): ?>
                <div class="option-card">
                    <img src="<?php echo htmlspecialchars($facility['Image']); ?>" alt="<?php echo htmlspecialchars($facility['Name']); ?>" class="option-image">
                    <h3><?php echo htmlspecialchars($facility['Name']); ?></h3>
                    <p><?php echo htmlspecialchars($facility['Description']); ?></p>
                    <a href="facility_details.php?id=<?php echo htmlspecialchars($facility['FacilityID']); ?>" class="learn-more">Learn more!</a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
   
    <!-- Footer with social links and opening hours -->
    <footer>
        <div class="footer-content">
            <div class="social-links">
                <a href="#" target="_blank">Facebook</a>
                <a href="#" target="_blank">Twitter</a>
                <a href="#" target="_blank">Instagram</a>
                <a href="#" target="_blank">LinkedIn</a>
            </div>
            <div class="opening-hours">
                <p>Opening Hours: Mon-Fri: 7am - 9pm</p>
                <p>Sat-Sun: 8am - 8pm</p>
            </div>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>
