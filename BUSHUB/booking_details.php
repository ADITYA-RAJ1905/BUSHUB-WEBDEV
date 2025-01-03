<?php
// Start the session to store session variables
session_start();

// Include the database connection
include 'db_config.php';  // Adjust the path if needed

// Check if the session variables for bus info are set (this should be set when selecting the bus)
if (!isset($_SESSION['bus_number'])) {
    // If no bus has been selected, redirect the user to the booking page
    header("Location: bookbushub.html");
    exit();
}

// Get the bus_number from the session
$busNumber = $_SESSION['bus_number'];

// Fetch the fare_per_seat from the database for the selected bus
$query = "SELECT fare_per_seat FROM buses WHERE bus_number = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $busNumber);
$stmt->execute();
$stmt->bind_result($farePerSeat);
$stmt->fetch();
$stmt->close();

// Set default values for the form fields
$totalFare = 0;
$discountedFare = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details - BusHub</title>
    <link rel="stylesheet" href="bushub.css">
    <style>
        /* Simple styles to resemble previous layout */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background-color: #333;
            color: white;
            padding: 10px 0;
            text-align: center;
        }

        header nav ul {
            list-style-type: none;
            padding: 0;
        }

        header nav ul li {
            display: inline;
            margin-right: 20px;
        }

        header nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
        }

        .container {
            width: 80%;
            margin: 0 auto;

        }

        .booking-details-section {
            background-color: #fff;
            margin: 20px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .booking-details-section h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .booking-details-section form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .booking-details-section label {
            margin-top: 10px;
            font-size: 16px;
        }

        .booking-details-section input[type="text"],
        .booking-details-section input[type="number"],
        .booking-details-section input[type="date"],
        .booking-details-section input[type="number"][readonly] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .booking-details-section button {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            margin-top: 20px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
        }

        .booking-details-section button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header>
        <div class="container">
            <h1 class="logo">BusHub</h1>
            <nav>
                <ul class="nav-links">
                    <li><a href="bushub.html">Home</a></li>
                    <li><a href="bookbushub.html">Book a Bus</a></li>
                    <li><a href="routesbushub.html">Routes</a></li>
                    <li><a href="aboutusbushub.html">About Us</a></li>
                    <li><a href="contactbushub.html">Contact Us</a></li>
                    <li><a href="loginbushub.html">Login</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Booking Details Section -->
    <section class="booking-details-section" id="bookingDetails">
        <h2>Enter Passenger Details</h2>
        <form id="bookingDetailsForm" action="confirm_booking.php" method="POST">
            <input type="hidden" name="bus_number" id="bus_number" value="<?php echo $busNumber; ?>">  <!-- Hidden field for bus_number -->

            <label for="passenger_name">Passenger Name:</label>
            <input type="text" id="passenger_name" name="passenger_name" required>

            <label for="num_adults">Number of Men:</label>
            <input type="number" id="num_adults" name="num_adults" min="1" value="0" required>

            <label for="num_women">Number of Women:</label>
            <input type="number" id="num_women" name="num_women" min="0" value="0" required>

            <label for="num_children">Number of Children:</label>
            <input type="number" id="num_children" name="num_children" min="0" value="0" required>

            <label for="total_fare">Total Fare:</label>
            <input type="number" id="total_fare" name="total_fare" readonly value="0" required>

            <button type="button" id="calculateFareBtn">Calculate Fare</button>
            <button type="submit">Confirm Booking</button>
        </form>
    </section>

    <script>
        // Function to calculate fare with discount
        document.getElementById('calculateFareBtn').addEventListener('click', function() {
            const farePerSeat = <?php echo $farePerSeat; ?>;  // Get fare from PHP variable
            const numAdults = parseInt(document.getElementById('num_adults').value) || 0;
            const numWomen = parseInt(document.getElementById('num_women').value) || 0;
            const numChildren = parseInt(document.getElementById('num_children').value) || 0;

            // Assume discounts: 10% for women and 50% for children
            const womenDiscount = 0.10;  // 10% discount for women
            const childrenDiscount = 0.50;  // 50% discount for children

            // Calculate fare
            const totalAdultsFare = numAdults * farePerSeat;
            const totalWomenFare = numWomen * farePerSeat * (1 - womenDiscount);  // Apply discount for women
            const totalChildrenFare = numChildren * farePerSeat * (1 - childrenDiscount);  // Apply discount for children
            //$total_fare = $fare * $num_adults + ($fare * 0.1) * $num_women + ($fare * 0.5) * $num_children;
            const totalFare = farePerSeat*numAdults+(farePerSeat*0.1)*numWomen+(farePerSeat*0.5)*numChildren;

            // Display total fare in the input field
            document.getElementById('total_fare').value = totalFare.toFixed(2);
        });
    </script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
