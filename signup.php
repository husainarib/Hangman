<?php
session_start();

// Initialize error and success messages
$error_message = "";
$success_message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Check if the username already exists in users.txt
        $file = fopen("users.txt", "a+");
        $exists = false;
        while (($line = fgets($file)) !== false) {
            list($existingUsername, $existingPassword) = explode(",", trim($line));
            if ($existingUsername === $username) {
                $exists = true;
                break;
            }
        }
        fclose($file);

        if ($exists) {
            $error_message = "Username already exists. Please choose a different one.";
        } else {
            // Store the new username and hashed password
            $file = fopen("users.txt", "a");
            fwrite($file, "$username,$hashedPassword\n");
            fclose($file);
            $success_message = "Account created successfully for $username!";
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
    <title>Sign Up</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <!-- Back to Homepage Button -->
    <a href="homepage.html" class="back-button">Back to Homepage</a>

    <div class="container">
        <div class="header">
            <h1>Sign Up</h1>
        </div>
        <form method="post" action="signup.php">
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
            <?php if (!empty($success_message)): ?>
                <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
            <?php endif; ?>
            <button type="submit" class="start-button">Sign Up</button>
        </form>

        <!-- Sign In Button -->
        <form action="signin.php" method="get">
            <button type="submit" class="start-button">Already have an account? Sign In</button>
        </form>
    </div>
</body>

</html>