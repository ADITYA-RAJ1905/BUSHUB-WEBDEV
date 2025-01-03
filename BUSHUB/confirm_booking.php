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

// Get the bus number and other details from session or POST
$bus_number = isset($_SESSION['bus_number']) ? $_SESSION['bus_number'] : '';
$route_name = isset($_SESSION['route_name']) ? $_SESSION['route_name'] : '';
$fare = isset($_SESSION['fare_per_seat']) ? $_SESSION['fare_per_seat'] : 0;
$passenger_name = isset($_POST['passenger_name']) ? $_POST['passenger_name'] : '';
$num_adults = isset($_POST['num_adults']) ? $_POST['num_adults'] : 0;
$num_women = isset($_POST['num_women']) ? $_POST['num_women'] : 0;
$num_children = isset($_POST['num_children']) ? $_POST['num_children'] : 0;

// Debugging: Check the values of variables
echo "Bus Number: $bus_number <br>";
echo "Route Name: $route_name <br>";
echo "Fare: $fare <br>";
echo "Passenger Name: $passenger_name <br>";
echo "Number of Adults: $num_adults <br>";
echo "Number of Women: $num_women <br>";
echo "Number of Children: $num_children <br>";

// Calculate the total fare (with discount for women and children)
$total_fare = $fare * $num_adults + ($fare * 0.1) * $num_women + ($fare * 0.5) * $num_children;

// Debugging: Check if total fare is being calculated correctly
echo "Total Fare: $total_fare <br>";

// If the form is submitted, insert the booking into the database
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the user ID from the session
    $user_id = $_SESSION['user_id'];

    // Get the available seats for the bus
    $sql_check_seats = "SELECT seats FROM buses WHERE bus_number = ?";
    $check_stmt = $conn->prepare($sql_check_seats);
    $check_stmt->bind_param("s", $bus_number);
    $check_stmt->execute();
    $check_stmt->bind_result($available_seats);
    $check_stmt->fetch();
    $check_stmt->close();

    // Calculate total number of passengers
    $total_passengers = $num_adults + $num_women + $num_children;

    // Check if there are enough available seats
    if ($available_seats >= $total_passengers) {
        // Proceed with inserting the booking
        $sql = "INSERT INTO bookings (bus_number, route_name, passenger_name, num_adults, num_women, num_children, total_fare, user_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind the parameters
            $stmt->bind_param("sssiidii", $bus_number, $route_name, $passenger_name, $num_adults, $num_women, $num_children, $total_fare, $user_id);

            // Execute the query
            if ($stmt->execute()) {
                // Update the available seats after booking
                $new_seat_count = $available_seats - $total_passengers;
                $update_seats_sql = "UPDATE buses SET seats = ? WHERE bus_number = ?";
                $update_stmt = $conn->prepare($update_seats_sql);
                $update_stmt->bind_param("is", $new_seat_count, $bus_number);

                if ($update_stmt->execute()) {
                    // Redirect to the confirmation page (ticket details)
                    header("Location: ticket.php");
                    exit();
                } else {
                    echo "Error updating seats: " . $update_stmt->error;
                }

                $update_stmt->close();
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Not enough available seats. Please try again with fewer passengers.";
    }
}

$conn->close();
?>
