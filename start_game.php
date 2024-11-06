<?php
session_start();

// Check if difficulty is set in POST request
if (isset($_POST['difficulty'])) {
    // Store the selected difficulty in session
    $_SESSION['difficulty'] = $_POST['difficulty'];
    
    // Redirect to the game page
    header("Location: hangman.php");
    exit();
} else {
    // If no difficulty was selected, redirect back to homepage
    header("Location: homepage.html");
    exit();
}
?>

