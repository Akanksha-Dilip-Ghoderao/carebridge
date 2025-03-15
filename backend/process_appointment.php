<?php
session_start(); // Start session to get username

// Check if user is logged in
if (!isset($_SESSION["username"])) {
    die("Error: User not logged in.");
}

// Database connection
$servername = "localhost";
$username = "root"; // Change if necessary
$password = ""; // Change if necessary
$dbname = "care_bridge"; // Update with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get logged-in username
$username = $_SESSION["username"];

// Get user_id from users table
$sql_user = "SELECT id FROM users WHERE username = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("s", $username);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows == 0) {
    die("Error: User not found.");
}

$row_user = $result_user->fetch_assoc();
$user_id = $row_user["id"];
$stmt_user->close();

// Get form data
$doctor_name = $_POST["doctor"]; // Doctor's full name (e.g., "John Doe")
$patient = $_POST["patient"];
$date = $_POST["date"];
$time = $_POST["time"];
$ailment = $_POST["ailment"];

// Fetch doctor_id from doctors table
$sql_doctor = "SELECT doctor_id FROM doctors WHERE CONCAT(first_name, ' ', last_name) = ?";
$stmt_doctor = $conn->prepare($sql_doctor);
$stmt_doctor->bind_param("s", $doctor_name);
$stmt_doctor->execute();
$result_doctor = $stmt_doctor->get_result();

if ($result_doctor->num_rows == 0) {
    die("Error: Doctor not found.");
}

$row_doctor = $result_doctor->fetch_assoc();
$doctor_id = $row_doctor["doctor_id"];
$stmt_doctor->close();

// Insert appointment into database with doctor_id as foreign key
$sql = "INSERT INTO appointments (user_id, doctor_id, patient_name, date, time, description) 
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iissss", $user_id, $doctor_id, $patient, $date, $time, $ailment);

if ($stmt->execute()) {
    // Redirect to my_appointments.php after successful booking
    header("Location: ../frontend/my_appointments.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
