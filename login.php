<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ansole Sports Club</title>
    <link rel="stylesheet" href="loginstyles.css">
</head>
<body>
    <header>
        <h1>Login to Ansole Sports Club</h1>
    </header>
    <main>
        <section class="login-section">
            <h2>Login</h2>

            <!-- Error Message Display -->
            <?php if (isset($_GET['error'])): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>

            <form action="login_process.php" method="post">
                <!-- Email Field -->
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>

                <!-- Password Field -->
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>

                <!-- Login Button -->
                <button type="submit">Login</button>
            </form>
            <p>Don't have an account? <a href="sign-up.html">Sign Up</a></p>
        </section>
    </main>
    
    <footer>
        <p>&copy; 2024 Ansole Sports Club. All rights reserved.</p>
    </footer>
    
    <script>
        // Remove query parameters from the URL without refreshing the page
        if (window.location.search.includes('error=')) {
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    </script>
</body>
</html>
