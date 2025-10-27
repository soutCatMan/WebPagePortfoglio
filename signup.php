<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - NotionClone</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@300;400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container form-container">
        <h2>Create Your Account</h2>
        <?php
        if (isset($_GET['error'])) {
            echo '<p class="error-message">' . htmlspecialchars($_GET['error']) . '</p>';
        }
        if (isset($_GET['success'])) {
            echo '<p class="success-message">' . htmlspecialchars($_GET['success']) . '</p>';
        }
        ?>
        <form action="handle_signup.php" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required minlength="6">
            </div>
            <button type="submit" class="btn btn-primary">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </div>
</body>

</html>