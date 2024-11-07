<?php
session_start();

// Check if user is logged in and display welcome message
if (isset($_SESSION['username'])) {
    echo "<p class='success-message'>Welcome, " . htmlspecialchars($_SESSION['username']) . "!</p>";
}
?>
