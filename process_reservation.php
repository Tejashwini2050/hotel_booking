<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Room Reservation</title>
  <link rel="stylesheet" href="room.css">
</head>
<body>
  <div class="reservation-container">
    <h1>Room Reservation</h1>
    <form id="reservationForm" action="" method="post">


      <label for="checkInDate">Check-in Date</label>
      <input type="date" id="checkInDate" name="checkInDate" required>
      
      <label for="checkOutDate">Check-out Date</label>
      <input type="date" id="checkOutDate" name="checkOutDate" required>
      
      <label for="roomType">Room Type</label>
      <select id="roomType" name="roomType" required>
        <option value="single">Single Room</option>
        <option value="deluxe">Deluxe Room</option>
        <option value="esuite">Executive Suite</option>
        <option value="psuite">Presidential Suite</option>
      </select>
      
      <label for="guests">Number of Guests:</label>
      <input type="number" id="guests" name="guests" min="1" max="10" value="1">
      
      
      <button type="submit" name="submit_booking">Reserve Room</button>
    </form>
  </div>
</body>
</html>
<?php
// Check if the customer ID (cid) is present in the POST data
if (isset($_GET['cid'])) {
    $cid = $_GET['cid'];
    echo "Customer ID (cid): " . $cid . "<br>";
} else {
    die("Customer ID (cid) not found in form data.");
}

// Rest of your code for processing the reservation...


// Create a connection to your MySQL database
$connection = new mysqli('localhost', 'root', '', 'hotel_hotel');

// Check for connection errors
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Check if the booking form was submitted
if (isset($_POST['submit_booking'])) {
    // Retrieve form data
    $check_in = $_POST['checkInDate'];
    $check_out = $_POST['checkOutDate'];
    $roomType = $_POST['roomType'];
    $guests = $_POST['guests'];
    // Add more fields for booking information
     
    // Find the corresponding room number (rno) based on the selected room type
    $roomSql = "SELECT rno FROM room WHERE type = ?";
    $stmt = $connection->prepare($roomSql);
    $stmt->bind_param("s", $roomType);
    $stmt->execute();
    $roomResult = $stmt->get_result();

    if ($roomResult->num_rows > 0) {
        $roomData = $roomResult->fetch_assoc();
        $rno = $roomData['rno'];
    }
    // Insert booking data into the "booking" table
    $sql = "INSERT INTO booking (cid, check_in, check_out, room_type, NO_OF_GUESTS) VALUES (?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("isssi", $cid, $check_in, $check_out, $roomType, $guests);

    if ($stmt->execute()) {
        // Get the booking ID (bid) of the newly inserted booking
        $bid = $stmt->insert_id;

        // Insert data into the "reservation" table
        if ($stmt->fetch()) {
          // Insert data into the "reservation" table
          $reservationSql = "INSERT INTO reservation (cid, bid, rno) VALUES (?, ?, ?)";
          $stmt = $connection->prepare($reservationSql);
          $stmt->bind_param("iii", $cid, $bid, $rno);
      
          if ($stmt->execute()) {
              // Redirect to a confirmation page or any other desired page
              header("Location: confirmation.php?cid=$cid&bid=$bid");
              exit;
        } else {
            echo "Error: " . $sql . "<br>" . $stmt->error;
        }
    } else {
        echo "Error: " . $sql . "<br>" . $stmt->error;
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$connection->close();
?>
