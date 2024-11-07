<?php
session_start();

// Initialize or retrieve the wins cookie and session variable for instant access
if (!isset($_COOKIE['wins'])) {
    setcookie('wins', 0, time() + (86400 * 30), "/");
    $_SESSION['wins'] = 0;
} else {
    $_SESSION['wins'] = $_COOKIE['wins'];
}

// Set up words for each difficulty
$words = [
    'easy' => ['apple', 'book', 'cat', 'dog', 'fish'],
    'medium' => ['planet', 'computer', 'unicorn', 'castle', 'rocket'],
    'hard' => ['javascript', 'university', 'extraterrestrial', 'submarine', 'microscope']
];

// Retrieve the difficulty level from the session or set default
$difficulty = $_SESSION['difficulty'] ?? 'easy';

// Pick a random word if not already set or if a new difficulty level is chosen
if (!isset($_SESSION['word']) || $_SESSION['difficulty'] != $difficulty) {
    $_SESSION['word'] = $words[$difficulty][array_rand($words[$difficulty])];
    $_SESSION['guesses'] = [];
    $_SESSION['attempts'] = 6; // Allow 6 attempts per game
    $_SESSION['game_over'] = false;
    $_SESSION['difficulty'] = $difficulty;
}

// Handle letter guessing if the game is active
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['guess']) && !$_SESSION['game_over']) {
    $guess = strtolower($_POST['guess']);
    if (!in_array($guess, $_SESSION['guesses'])) {
        $_SESSION['guesses'][] = $guess;
        if (strpos($_SESSION['word'], $guess) === false) {
            $_SESSION['attempts']--;
        }
    }
}

// Check game status
$word = $_SESSION['word'];
$displayWord = '';
$allGuessed = true;

foreach (str_split($word) as $letter) {
    if (in_array($letter, $_SESSION['guesses'])) {
        $displayWord .= $letter . ' ';
    } else {
        $displayWord .= '_ ';
        $allGuessed = false;
    }
}

$isGameOver = $_SESSION['attempts'] <= 0;
$isGameWon = $allGuessed && !$isGameOver;

// Update the wins count and cookie if the game is won
if ($isGameWon && !$_SESSION['game_over']) {
    $_SESSION['wins']++; // Update session win count
    setcookie('wins', $_SESSION['wins'], time() + (86400 * 30), "/"); // Update cookie win count
    $_SESSION['game_over'] = true; // Mark game as over
} elseif ($isGameOver) {
    $_SESSION['game_over'] = true;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hangman Game</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Hangman - <?php echo ucfirst($difficulty); ?> Mode</h1>
        <p>Guess the word:</p>
        <p class="word"><?php echo $displayWord; ?></p>

        <!-- Display win count -->
        <p>Your Total Wins: <?php echo $_SESSION['wins']; ?></p>

        <?php if ($isGameOver): ?>
            <p class="message">Game Over! The word was "<?php echo $word; ?>"</p>
        <?php elseif ($isGameWon): ?>
            <p class="message">Congratulations! You guessed the word "<?php echo $word; ?>"</p>
        <?php else: ?>
            <p>Attempts remaining: <?php echo $_SESSION['attempts']; ?></p>
            <form method="post">
                <input type="text" name="guess" maxlength="1" required>
                <button type="submit">Guess</button>
            </form>
            <p>Guessed letters: <?php echo implode(', ', $_SESSION['guesses']); ?></p>
        <?php endif; ?>

        <?php if ($isGameOver || $isGameWon): ?>
            <!-- Clear session and start new game on 'Play Again' -->
            <a href="reset.php" class="play-again-button">Play Again</a>
        <?php endif; ?>
    </div>
</body>
</html>

