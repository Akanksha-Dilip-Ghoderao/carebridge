<?php
session_start();

// Database Connection
$servername = "localhost";
$username_db = "root"; // Change this if needed
$password_db = ""; // Change this if needed
$database = "care_bridge"; // Change this to your actual database name

// Create Connection
$conn = new mysqli($servername, $username_db, $password_db, $database);

// Check Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

$username = $_SESSION['username'];

// Fetch doctor's name from the database
$query = "SELECT first_name, last_name FROM doctors WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($first_name, $last_name);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
      integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Doctor's Dashboard</title>
    <style>
      body {
        font-family: "Montserrat", sans-serif;
        background-color: #f0fdfa;
      }

      .sidebar {
        width: 250px;
        height: 100vh;
        background: #0f766e;
        color: white;
        padding: 20px;
        position: fixed;
      }

      .sidebar a {
        display: block;
        color: white;
        padding: 12px;
        margin: 8px 0;
        border-radius: 5px;
        text-decoration: none;
        transition: 0.3s;
      }

      .sidebar a:hover {
        background: #115e59;
      }

      .dashboard {
        margin-left: 270px;
        padding: 20px;
      }
    </style>
  </head>
  <body>
    <div class="sidebar">
      <h2 class="text-2xl font-semibold">Doctor's Panel</h2>
      <nav>
        <a href="./doctor_appointments.php"
          ><i class="fa-solid fa-calendar-check"></i> Appointments</a
        >
        <a href="./my_patients.php"
          ><i class="fa-solid fa-notes-medical"></i> Patients</a
        >
        <a href="../backend/logout.php"
          ><i class="fa-solid fa-sign-out"></i> Logout</a
        >
      </nav>
    </div>

    <div class="dashboard flex flex-col items-center text-center h-screen flex flex-col">
      <h1 class="text-3xl font-semibold text-teal-700">
        Welcome, Dr. <?php echo htmlspecialchars($first_name . " " . $last_name); ?>!
      </h1>
      <p class="text-gray-600 mt-2">
        Manage your patients and appointments efficiently.
      </p>

      <!-- Doctor Image -->
      <img
        src="./assets/images/hospital.svg"
        alt="Doctor Illustration"
        class="mt-6 w-full h-[calc(100%_-_100px)] border object-cover"
      />
    </div>
  </body>
</html>
