<?php
// Start the session
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "Vidyad@1905";
$dbname = "bushub"; // Make sure your database name is correct

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the bus number and route name are sent in the POST request
if (isset($_POST['bus_number']) && isset($_POST['route_name'])) {
    $busNumber = $_POST['bus_number'];
    $routeName = $_POST['route_name'];
    
    // Prepare and execute a query to fetch the fare for the selected bus
    $sql = "SELECT fare_per_seat FROM buses WHERE bus_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $busNumber);
    $stmt->execute();
    $stmt->bind_result($farePerSeat);
    $stmt->fetch();
    
    // If fare is found, store bus details and fare in session
    if ($farePerSeat) {
        $_SESSION['bus_number'] = $busNumber;
        $_SESSION['route_name'] = $routeName;
        $_SESSION['fare_per_seat'] = $farePerSeat; // Store fare in session
    }
    
    // Close the connection
    $stmt->close();
    $conn->close();
    
    // Respond with a success message
    echo json_encode(["status" => "success"]);
} else {
    // If data is missing, return an error
    echo json_encode(["status" => "error", "message" => "Invalid data"]);
}
?>
