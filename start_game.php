<?php
// start_game.php - handle the form submission and start the game
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $difficulty = $_POST['difficulty'];
    // Redirect to the game page with the chosen difficulty
    header("Location: game.php?difficulty=$difficulty");
    exit();
} else {
    echo "Please select a difficulty level.";
}
?>
