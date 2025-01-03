<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If user is not logged in, redirect to the login page
    header("Location: loginbushub.html");
    exit();
}

// Connect to the database
$conn = new mysqli("localhost", "root", "Vidyad@1905", "bushub");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Fetch all bookings for the logged-in user
$sql = "SELECT * FROM bookings WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if there are any bookings
$bookings = array();
while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}

// Return the bookings data as JSON
echo json_encode($bookings);

// Close the database connection
$stmt->close();
$conn->close();
?>
