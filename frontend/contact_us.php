<?php
session_start();

// Check if user is logged in and is a patient
if (!isset($_SESSION["username"]) || !isset($_SESSION["user_type"]) || $_SESSION["user_type"] !== "patient") {
    header("Location: ../frontend/login.html");
    exit();
}

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

// Get logged-in patient's username
$patient_username = $_SESSION["username"];

// Fetch patient_id
$sql_patient = "SELECT id FROM users WHERE username = ? AND user_type = 'patient'";
$stmt_patient = $conn->prepare($sql_patient);
$stmt_patient->bind_param("s", $patient_username);
$stmt_patient->execute();
$result_patient = $stmt_patient->get_result();

if ($result_patient->num_rows === 0) {
    die("Error: Patient not found.");
}

$patient_row = $result_patient->fetch_assoc();
$patient_id = $patient_row["id"];

$stmt_patient->close();
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
    />

    <!-- Google Fonts -->
    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />

    <script src="https://cdn.tailwindcss.com"></script>
    <title>Contact Us</title>
    <style>
      body {
        font-family: "Montserrat", sans-serif;
        background-color: #e0f7fa;
      }
      .container { display: flex; max-width: 1200px; margin: auto; padding: 20px; gap: 20px; }
      .contact, .sidebar { background: white; padding: 20px; border-radius: 10px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); }
      .contact { flex: 2; }
      .sidebar { flex: 1; text-align: center; }
      .t { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; margin-top: 5px; font-size: 16px; }
      .social i { margin-right: 8px; color: rgb(13 148 136); }
    </style>
  </head>
  <body class="h-screen">
    <header class="bg-teal-600 text-white py-4 px-6 flex justify-between items-center">
      <div class="flex items-center gap-4">
        <a href="./patient_dashboard.php"> 
        <img src="./assets/images/logo.png" alt="logo" class="h-12 w-12 rounded-full" />
        </a>
        <a href="./patient_dashboard.php" class="hover:underline"><span class="text-xl font-semibold tracking-wide">Care Bridge</span></a>
      </div>
      <nav class="hidden md:flex gap-6 text-[16px] font-small">
        <a href="./find_doctor.php" class="hover:underline">Find Doctor</a>
        <a href="my_appointments.php" class="hover:underline">My Appointment</a>
        <a href="about_us.php" class="hover:underline">About Us</a>
        <a href="contact_us.php" class="hover:underline">Contact Us</a>
      </nav>
      <div class="flex items-center gap-3">
        <i class="fa-regular fa-user text-xl"></i>
        <span class="hidden md:block text-[15px] font-medium"><?php echo htmlspecialchars($patient_username); ?></span>
        <a href="../backend/logout.php" class="text-sm underline font-medium">Log Out</a>
      </div>
    </header>
    <div class=" h-[calc(100%_-_140px)] flex items-start p-4 gap-8">
    <form action="#" method="post" class="w-full">
        <div class="contact">
          <h1 class="text-xl font-semibold text-gray-800 mb-4">Contact Us</h1>
          <label class="text-gray-600 font-medium">Address:</label>
          <input type="text" name="t1" class="t" placeholder="Enter your address" />
          <label class="text-gray-600 font-medium mt-4">Phone Number:</label>
          <input type="number" name="no" class="t" placeholder="Enter your phone number" />
          <label class="text-gray-600 font-medium mt-4">Email ID:</label>
          <input type="email" name="t2" class="t" placeholder="Enter your email" />
          <button type="submit" class="mt-4 bg-teal-600 text-white py-2 px-4 rounded hover:bg-teal-700">Send</button>
      </div>
    </form>
    <div class="min-w-fit">
        <h1 class="text-lg font-semibold text-gray-800">Follow Us</h1>
        <div class="social space-y-3">
          <p><i class="fa-brands fa-x-twitter"></i> Twitter</p>
          <p><i class="fa-brands fa-instagram"></i> Instagram</p>
          <p><i class="fa-brands fa-youtube"></i> YouTube</p>
          <p><i class="fa-brands fa-linkedin"></i> LinkedIn</p>
      </div>
    </div>
    </div>
  </body>
</html>