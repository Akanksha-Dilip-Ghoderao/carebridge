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

// Get form data & sanitize inputs
$first_name = trim($_POST["first_name"]);
$last_name = trim($_POST["last_name"]);
$gender = trim($_POST["gender"]);
$specialty = trim($_POST["specialty"]);
$city = trim($_POST["city"]);
$username = trim($_POST["username"]);
$password = trim($_POST["password"]);
$confirm_password = trim($_POST["confirm_password"]);

// Validate required fields
if (empty($first_name) || empty($last_name) || empty($gender) || empty($specialty) || empty($city) || empty($username) || empty($password) || empty($confirm_password)) {
    die("All fields are required.");
}

// Check if passwords match
if ($password !== $confirm_password) {
    die("Passwords do not match.");
}

// Check if username already exists
$sql_check = "SELECT id FROM users WHERE username = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $username);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    die("Username already taken.");
}

$stmt_check->close();

// Hash the password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Start transaction to ensure atomicity
$conn->begin_transaction();

try {
    // Insert into users table
    $sql_users = "INSERT INTO users (username, password, user_type, first_name, last_name, gender, city, created_at) 
                  VALUES (?, ?, 'doctor', ?, ?, ?, ?, NOW())";
    $stmt_users = $conn->prepare($sql_users);
    $stmt_users->bind_param("ssssss", $username, $hashed_password, $first_name, $last_name, $gender,$city);

    if (!$stmt_users->execute()) {
        throw new Exception("Error inserting into users table: " . $stmt_users->error);
    }

    // Get the generated user_id (doctor_id)
    $doctor_id = $stmt_users->insert_id;
    $stmt_users->close();

    // Insert into doctors table (linking user_id as doctor_id)
    $sql_doctors = "INSERT INTO doctors (doctor_id, first_name, last_name, gender, specialty, city, username, password, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt_doctors = $conn->prepare($sql_doctors);
    $stmt_doctors->bind_param("isssssss", $doctor_id, $first_name, $last_name, $gender, $specialty, $city, $username, $hashed_password);

    if (!$stmt_doctors->execute()) {
        throw new Exception("Error inserting into doctors table: " . $stmt_doctors->error);
    }
    $stmt_doctors->close();

    // Update users table to set doctor_id as a foreign key
    $sql_update_users = "UPDATE users SET doctor_id = ? WHERE id = ?";
    $stmt_update_users = $conn->prepare($sql_update_users);
    $stmt_update_users->bind_param("ii", $doctor_id, $doctor_id);

    if (!$stmt_update_users->execute()) {
        throw new Exception("Error updating doctor_id in users table: " . $stmt_update_users->error);
    }
    $stmt_update_users->close();

    // Commit transaction
    $conn->commit();

    // Close connection
    $conn->close();

    // Redirect to login page
    echo "<script>alert('Registration successful!'); window.location.href='../frontend/login.html';</script>";

} catch (Exception $e) {
    // Rollback on failure
    $conn->rollback();
    die($e->getMessage());
}

?>
