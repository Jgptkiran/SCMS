<div class="user-info">
                <!-- Check if session is set and display the email -->
                <?php if (isset($_SESSION['firstName'])): ?>
                    <span><?php echo htmlspecialchars($_SESSION['firstName']); ?></span>
                    <!-- Logout link -->
                    <a href="logout.php" class="logout-button">Logout</a>
                <?php else: ?>
                    <span>Guest</span>
                <?php endif; ?>
            </div>