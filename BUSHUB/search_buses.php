<?php
$conn = new mysqli('localhost', 'root', 'Vidyad@1905', 'bushub');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $from = $_POST['from'];
    $to = $_POST['to'];
    $date = $_POST['date'];

    $stmt = $conn->prepare(
        "SELECT bus_id, bus_number, route_name, time, seats, fare_per_seat 
        FROM buses 
        WHERE departure_city = ? AND destination_city = ? AND date = ?"
    );
    $stmt->bind_param("sss", $from, $to, $date);
    $stmt->execute();

    $result = $stmt->get_result();
    $buses = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($buses);
}
?>
