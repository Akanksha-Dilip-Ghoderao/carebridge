<?php
session_start();

// Database connection
$server = "localhost";
$username = "root";
$password = "";
$database = "care_bridge";

$conn = new mysqli($server, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure request is POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    die("405 Method Not Allowed");
}

// Get appointment ID
if (!isset($_POST["appointment_id"])) {
    die("Invalid request");
}

$appointment_id = $_POST["appointment_id"];

// Update appointment status
$sql = "UPDATE appointments SET status = 'Confirmed' WHERE appointment_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $appointment_id);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error";
}

$stmt->close();
$conn->close();
?>
