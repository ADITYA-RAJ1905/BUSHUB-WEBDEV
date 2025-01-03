BusHub 🚍
A Comprehensive Bus Booking Platform
BusHub is a user-friendly web application designed to simplify bus booking and management. It allows users to search for buses, view schedules, book tickets, and manage bookings with ease.

🌟 Features
User Authentication:
Secure login and registration system.
Redirect users to register if credentials are invalid.
Bus Search and Booking:
Search for buses by departure and destination cities.
View available buses with detailed information (routes, fares, and seat availability).
Passenger Management:
Confirm bookings with passenger details (adults, children, women).
Automatically calculate fares based on input.
Booking Management:
View all bookings with improved CSS design.
Cancel bookings if needed.
Individualized Data Handling:
Each logged-in user's data, including bookings, is managed and displayed separately.
🛠️ Technologies Used
Frontend: HTML, CSS, JavaScript
Backend: PHP
Database: MySQL
📂 Database Schema
Users Table: Stores user credentials and details.
Buses Table:
bus_id, bus_number, route_name, departure_city, destination_city, date, time, seats, fare_per_seat.
Bookings Table:
booking_id, bus_number, customer_name, age, gender, num_adults, num_children, num_women, total_fare, date_of_booking.

📄 Future Enhancements
Add seat selection functionality.
Implement a real-time seat availability checker.
Enable payment gateway integration.
🤝 Contribution
Contributions are welcome! Feel free to fork this repository and submit pull requests.

