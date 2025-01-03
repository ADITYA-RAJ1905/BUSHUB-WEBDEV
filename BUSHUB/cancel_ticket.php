<?php
$conn = new mysqli('localhost', 'root', 'Vidyad@1905', 'bushub');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = $_POST['booking_id'];

    $stmt = $conn->prepare("SELECT bus_id, num_adults, num_children, num_women FROM bookings WHERE booking_id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();

    $total_seats = $booking['num_adults'] + $booking['num_children'] + $booking['num_women'];
    $bus_id = $booking['bus_id'];

    $stmt = $conn->prepare("UPDATE buses SET seats = seats + ? WHERE bus_id = ?");
    $stmt->bind_param("ii", $total_seats, $bus_id);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM bookings WHERE booking_id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();

    echo "Ticket successfully canceled.";
}
?>
