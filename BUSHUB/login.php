<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connect to the database
    $conn = new mysqli("localhost", "root", "Vidyad@1905", "bushub");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get POST data and sanitize it
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Prepare and execute the query to check if the user exists
    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    $valid_login = false; // Initialize the valid_login variable to false

    if ($result->num_rows > 0) {
        // User found, fetch the user record
        $user = $result->fetch_assoc();

        // Compare plaintext passwords (no hashing)
        if ($password == $user['password']) {
            // Password matches
            $_SESSION['user_id'] = $user['id']; // Store user ID in session
            $_SESSION['username'] = $user['username']; // Optionally store the username
            $valid_login = true; // Set valid_login to true if credentials match
        } else {
            // Invalid password
            echo "Invalid password.";
        }
    } else {
        // User not found
        echo "User not found.";
    }

    // Check if the login is valid
    if ($valid_login) {
        header("Location: bushub.html");  // Redirect to the home page or booking page
        exit();
    } else {
        // If login fails
        echo "Invalid login credentials.";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BusHub Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="loginbushub.css">
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <img src="bushub.jpg" alt="BusHub Logo" class="logo">
            <h1>Welcome to BusHub</h1>
            <p>Login to your account and book your next journey.</p>
        </div>
        <form method="POST" action="">
            <div class="input-field">
                <input type="text" name="username" placeholder="Enter your username" required>
            </div>
            <div class="input-field">
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="login-btn">Login</button>
        </form>
        <div class="register-link">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
</body>
</html>
