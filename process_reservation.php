<?php
// Create a connection to your MySQL database
$connection = new mysqli('localhost', 'root', '', 'hotel_hotel');

// Check for connection errors
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Check if the customer ID (cid) is present in the URL parameters
if (isset($_GET['cid'])) {
    $cid = $_GET['cid'];
    echo "Customer ID (cid): " . $cid . "<br>";

    // Use prepared statements to safely include the 'cid' value in the SQL query
    $sql = "SELECT * FROM customer WHERE cid = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $cid);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the query was executed successfully
    if ($result) {
        // Fetch the customer data from the query result
        $customerData = $result->fetch_assoc();
        // Now $customerData will contain the data of the customer with the given 'cid'
    } else {
        echo "Error: " . $sql . "<br>" . $connection->error;
    }

    // Check if the booking form was submitted
    if (isset($_POST['submit_booking'])) {
        // Retrieve form data
        $check_in = $_POST['checkInDate'];
        $check_out = $_POST['checkOutDate'];
        $roomType = $_POST['roomType'];
        $guests = $_POST['guests'];
        // Add more fields for booking information

        // Insert booking data into the "booking" table
        $sql = "INSERT INTO booking (cid, check_in, check_out, room_type, NO_OF_GUESTS) VALUES (?, ?, ?, ?, ?)";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("isssi", $cid, $check_in, $check_out, $roomType, $guests);

        if ($stmt->execute()) {
            // Get the booking ID (bid) of the newly inserted booking
            $bid = $stmt->insert_id;

            // Insert data into the "reservation" table
            $sql = "INSERT INTO reservation (cid, bid) VALUES (?, ?)";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("ii", $cid, $bid);

            if ($stmt->execute()) {
                // Redirect to a confirmation page or any other desired page
                header("Location: confirmation.php?cid=$cid&bid=$bid");
                exit;
            } else {
                echo "Error: " . $sql . "<br>" . $connection->error;
            }
        } else {
            echo "Error: " . $sql . "<br>" . $connection->error;
        }
    }
}

// Close the prepared statement and the connection
$stmt->close();
$connection->close();
?>
