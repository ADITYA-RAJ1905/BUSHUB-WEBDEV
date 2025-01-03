<?php
session_start();

// Connect to the database
$conn = new mysqli("localhost", "root", "Vidyad@1905", "bushub");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the user ID from session
$user_id = $_SESSION['user_id'];

// Fetch the booking details for the logged-in user
$sql = "SELECT * FROM bookings WHERE user_id = ? ORDER BY booking_id DESC LIMIT 1";  // Get the most recent booking
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $booking = $result->fetch_assoc();
    $bus_number = $booking['bus_number'];
    $route_name = $booking['route_name'];
    $passenger_name = $booking['passenger_name'];
    $adults = $booking['num_adults'];
    $women = $booking['num_women'];
    $children = $booking['num_children'];
    $total_fare = $booking['total_fare'];
    $booking_date = $booking['booking_date'];
} else {
    echo "No bookings found.";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Bus Ticket - BusHub</title>
    <link rel="stylesheet" href="ticket.css">
</head>
<body>

    <div class="ticket-container">
        <header>
            <h1>BusHub Ticket</h1>
            <p>Your booking details are below:</p>
        </header>

        <div class="ticket">
            <div class="ticket-header">
                <h2>Booking Confirmation</h2>
                <p><strong>Ticket ID:</strong> <?php echo $booking['booking_id']; ?></p>
            </div>

            <div class="ticket-body">
                <div class="ticket-info">
                    <p><strong>Bus Number:</strong> <?php echo $bus_number; ?></p>
                    <p><strong>Route:</strong> <?php echo $route_name; ?></p>
                    <p><strong>Passenger Name:</strong> <?php echo $passenger_name; ?></p>
                    <p><strong>Adults:</strong> <?php echo $adults; ?></p>
                    <p><strong>Women:</strong> <?php echo $women; ?></p>
                    <p><strong>Children:</strong> <?php echo $children; ?></p>
                    <p><strong>Total Fare:</strong> ₹<?php echo number_format($total_fare, 2); ?></p>
                    <p><strong>Date of Booking:</strong> <?php echo $booking_date; ?></p>
                </div>

                <div class="ticket-footer">
                    <p><strong>Thank you for booking with BusHub!</strong></p>
                    <button class="btn" onclick="window.location.href='bushub.html'">Go to Homepage</button>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
