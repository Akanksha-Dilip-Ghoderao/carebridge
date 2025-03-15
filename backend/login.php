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

// Debugging: Check if POST data is received
if (empty($_POST["username"]) || empty($_POST["password"]) || empty($_POST["user_type"])) {
    die("Missing required fields.");
}

$user = trim($_POST["username"]);
$pass = trim($_POST["password"]);
$user_type = trim($_POST["user_type"]); // 'doctor' or 'patient'

// Check if user exists with the given user_type$user_type
$sql = "SELECT * FROM users WHERE username = ? AND user_type = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $user, $user_type);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Verify password (ensure passwords are hashed)
    if (password_verify($pass, $row["password"])) {
        session_regenerate_id(true);
        $_SESSION["username"] = $user;
        $_SESSION["user_type"] = $user_type;

        // Redirect based on user user_type$user_type
        if ($user_type === "doctor") {
            header("Location: ../frontend/doctor_dashboard.php");
        } else {
            header("Location: ../frontend/patient_dashboard.php");
        }
        exit();
    } else {
        echo "<script>alert('Incorrect password!'); window.location.href='../frontend/login.html';</script>";
    }
} else {
    echo "<script>alert('User not found!'); window.location.href='../frontend/login.html';</script>";
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
