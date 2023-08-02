<!DOCTYPE html>
<html>
<head>
    <title>Booking Page</title>
    <link rel="stylesheet" type="text/css" href="booking.css">
</head>

<body>
    <header>
        <h1>Booking Details</h1>
    </header>

    <main>
        <section>
            <h2>Guest Information</h2>
            <form action="" method="POST" id="booking-form">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email:</label>   
                <input type="email" id="email" name="email" required>

                <label for="phoneno">Phone_no:</label>
                <input type="text" id="phone_no" name="phone_no" required>

                <label for="age">Age:</label>
                <input type="text" id="age" name="age" required>

                <label for="gender">Gender:</label><br>
                <input type="radio" id="male" name="gender" value="male">
                <label for="male">Male</label>

                <input type="radio" id="female" name="gender" value="female">
                <label for="female">Female</label>

                <input type="radio" id="other" name="gender" value="other">
                <label for="other">Other</label>

                <!-- <input type="submit" name="submit" value="Submit"> -->
                <button type="submit">Submit Booking</button> 
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2023 Hotel Felicity. All rights reserved.</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bookingForm = document.getElementById('booking-form');

            bookingForm.addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(bookingForm);

                // Perform any additional logic or API calls to handle the booking submission
                // For example, you can use fetch() to send the form data to the backend server for processing.

                // After the booking is processed, you can redirect the user to a confirmation page.
                window.location.href = 'confirmation.html';
            });
        });
        use LDAP\ResultEntry;
    </script>
    
<?php
echo "This PHP script is being executed.";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $host = 'localhost';
    $dbUsername = 'root';
    $dbPassword = '';
    $dbName = 'hotel_hotel';

    $name = $_POST["name"];
    $email = $_POST['email'];
    $phone_no = $_POST['phone_no'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];

    $connection = new mysqli($host, $dbUsername, $dbPassword, $dbName);

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    // Use prepared statement to prevent SQL injection
    $sql = "INSERT INTO customer(name, email, phone_no, age, gender) VALUES (?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("sssis", $name, $email, $phone_no, $age, $gender);

    if ($stmt->execute()) {
        echo "Record inserted successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $connection->error;
    }

    $stmt->close();
    $connection->close();
}

?>


</body>
</html>