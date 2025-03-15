<?php
// Database connection
$servername = "localhost";
$username = "root"; // Default username in XAMPP
$password = ""; // No password
$dbname = "care_bridge";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $gender = $_POST["gender"];
    $dob = $_POST["dob"];
    $city = $_POST["city"];
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hash the password

    // Insert into database
    $sql = "INSERT INTO users (first_name, last_name, gender, dob, city, username, password) 
            VALUES ('$first_name', '$last_name', '$gender', '$dob', '$city', '$username', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Registration successful!'); window.location.href='../frontend/login.html';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close connection
$conn->close();
?>
