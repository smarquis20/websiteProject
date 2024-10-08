<?php
$servername = "localhost";
$username = "admin";
$password = "admin";
$dbname = "tiffanyspringsward";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch member details
$sql = "SELECT member_id, last_name, first_name, age, phone_number FROM members ORDER BY last_name ASC";
$result = $conn->query($sql);

$members = [];
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $members[] = $row;
    }
} 

$conn->close();

echo json_encode($members);
?>
