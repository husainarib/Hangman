<?php
session_start();

// Initialize error message
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $file = fopen("users.txt", "r");
        $loggedIn = false;

        while (($line = fgets($file)) !== false) {
            list($storedUsername, $storedHashedPassword) = explode(",", trim($line));
            if ($storedUsername === $username && password_verify($password, $storedHashedPassword)) {
                $_SESSION['username'] = $username;
                $_SESSION['welcome_message'] = "Welcome, " . htmlspecialchars($username) . "!";
                $loggedIn = true;
                break;
            }
        }
        fclose($file);

        if ($loggedIn) {
            header("Location: homepage.html");
            exit;
        } else {
            $error_message = "Invalid username or password.";
        }
    } else {
        $error_message = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <!-- Back to Homepage Button -->
    <a href="homepage.html" class="back-button">Back to Homepage</a>

    <div class="container">
        <div class="header">
            <h1>Sign In</h1>
        </div>
        <form method="post" action="signin.php">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <?php if (!empty($error_message)): ?>
                <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
            <?php endif; ?>
            <button type="submit" class="start-button">Sign In</button>
        </form>

        <!-- Sign Up Button -->
        <form action="signup.php" method="get">
            <button type="submit" class="start-button">Don't have an account? Sign Up</button>
        </form>
    </div>
</body>

</html>