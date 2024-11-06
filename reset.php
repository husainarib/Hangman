<?php
session_start();
session_destroy(); // Clear all session data
header("Location: homepage.html"); // Redirect back to homepage
exit();
?>
