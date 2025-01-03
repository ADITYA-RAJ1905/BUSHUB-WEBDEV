<?php
// Retrieve bus_id from URL
$bus_id = $_GET['bus_id'] ?? null;

if ($bus_id) {
    // Connect to the database
    $conn = new mysqli('localhost', 'root', 'Vidyad@1905', 'bushub');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch bus details from the database using bus_id
    $stmt = $conn->prepare("SELECT * FROM buses WHERE bus_id = ?");
    $stmt->bind_param("i", $bus_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $bus = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Page</title>
</head>
<body>
    <h1>Book Your Ticket</h1>

    <?php if ($bus): ?>
        <h2>Bus Details</h2>
        <p><strong>Bus Number:</strong> <?= $bus['bus_number'] ?></p>
        <p><strong>Route:</strong> <?= $bus['route_name'] ?></p>
        <p><strong>Departure:</strong> <?= $bus['departure_city'] ?></p>
        <p><strong>Destination:</strong> <?= $bus['destination_city'] ?></p>
        <p><strong>Date:</strong> <?= $bus['date'] ?></p>
        <p><strong>Time:</strong> <?= $bus['time'] ?></p>
        <p><strong>Fare per Seat:</strong> ₹<?= $bus['fare_per_seat'] ?></p>

        <!-- Booking Form -->
        <h3>Enter Your Details</h3>
        <form action="confirm_booking.php" method="post">
            <input type="hidden" name="bus_id" value="<?= $bus['bus_id'] ?>"> <!-- Bus ID dynamically set -->

            <label for="customer_name">Name:</label>
            <input type="text" name="customer_name" id="customer_name" required>

            <label for="age">Age:</label>
            <input type="number" name="age" id="age" required>

            <label for="gender">Gender:</label>
            <select name="gender" id="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>

            <label for="num_adults">Number of Adults:</label>
            <input type="number" name="num_adults" id="num_adults" min="0" required>

            <label for="num_children">Number of Children:</label>
            <input type="number" name="num_children" id="num_children" min="0" required>

            <label for="num_women">Number of Women:</label>
            <input type="number" name="num_women" id="num_women" min="0" required>

            <label for="total_fare">Total Fare:</label>
            <input type="number" name="total_fare" id="total_fare" value="0" readonly required>
            
            <button type="submit">Book Now</button>
        </form>
    <?php else: ?>
        <p>Sorry, the bus details could not be found.</p>
    <?php endif; ?>

</body>
</html>
