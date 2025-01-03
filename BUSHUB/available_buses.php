<?php
// db_config.php: Include your database connection here
include 'db_config.php';

// Get the form data from the POST request
$from = $_POST['from'];
$to = $_POST['to'];
$date = $_POST['date'];

// Prepare the SQL query to fetch buses
$query = "SELECT bus_number, route_name, seats, fare_per_seat 
          FROM buses 
          WHERE departure_city = ? AND destination_city = ? AND date = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("sss", $from, $to, $date);
$stmt->execute();
$result = $stmt->get_result();

// Fetch buses from the result
$buses = [];
while ($row = $result->fetch_assoc()) {
    $buses[] = $row;
}

// Close the connection and return the response as JSON
$stmt->close();
$conn->close();

echo json_encode($buses);
?>
