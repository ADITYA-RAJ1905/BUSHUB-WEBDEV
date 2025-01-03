<?php
// Receive raw POST data
$data = json_decode(file_get_contents("php://input"), true);

// Check if the necessary data is provided
if (isset($data['booking_id']) && isset($data['bus_number']) && isset($data['seats_to_release'])) {
    $booking_id = $data['booking_id'];
    $bus_number = $data['bus_number'];
    $seats_to_release = $data['seats_to_release'];

    // Connect to the database
    $conn = new mysqli("localhost", "root", "Vidyad@1905", "bushub");

    // Check connection
    if ($conn->connect_error) {
        echo json_encode(['success' => false, 'error' => 'Connection failed: ' . $conn->connect_error]);
        exit();
    }

    // Delete the booking from the bookings table
    $delete_sql = "DELETE FROM bookings WHERE booking_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $booking_id);

    if ($delete_stmt->execute()) {
        // Update the seats in the buses table
        $update_sql = "UPDATE buses SET seats = seats + ? WHERE bus_number = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("is", $seats_to_release, $bus_number);

        if ($update_stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error updating seats: ' . $conn->error]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Error deleting booking: ' . $conn->error]);
    }

    // Close connections
    $delete_stmt->close();
    $update_stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Missing required data']);
}
?>
