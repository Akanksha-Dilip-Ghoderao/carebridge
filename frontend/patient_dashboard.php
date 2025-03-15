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

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
   <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
    />

    <!-- Google Fonts -->
    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <style>
      body{
        font-family: "Montserrat", sans-serif;
        background-color: #e0f7fa;
      }
    </style>
    <title>Patient Dashboard</title>
  </head>
  <body class="bg-white flex flex-col h-screen">
    <!-- HEADER -->
    <header class="bg-teal-600 text-white py-4 px-6 flex justify-between items-center mb-0">
      <div class="flex items-center gap-4">
         <a href="./patient_dashboard.php">
<img src="./assets/images/logo.png" alt="logo" class="h-12 w-12 rounded-full" />
</a>
      <a href="./patient_dashboard.php" class="hover:underline"><span class="text-xl font-semibold tracking-wide">Care Bridge</span></a>
      </div>
      <nav class="hidden md:flex gap-6 text-[16px] font-small">
        <a href="./find_doctor.php" class="hover:underline">Find Doctor</a>
        <a href="./my_appointments.php" class="hover:underline">My Appointment</a>
        <a href="./about_us.php" class="hover:underline">About Us</a>
        <a href="./contact_us.php" class="hover:underline">Contact Us</a>
      </nav>
      <div class="flex items-center gap-3">
        <i class="fa-regular fa-user text-xl"></i>
        <span class="hidden md:block text-[15px] font-medium">
          <?php echo htmlspecialchars($patient_username); ?>
        </span>
        <a href="../backend/logout.php" class="text-sm underline font-medium">Log Out</a>
      </div>
    </header>

    <!-- HERO SECTION -->
      <!-- ROADMAP -->
       <div class="h-[calc(100%_-_80px)] w-full relative">
         <img
         src="./assets/images/patdash2.svg"
         alt="Doctor Illustration"
         class="w-full h-full  border object-contain"
         />
         
         <!-- <div class="absolute bottom-24">
           <h1 class="text-lg md:text-xl font-bold text-gray-800 tracking-tight">
             Your body can be your best friend or worst enemy.
            </h1>
            <p class="text-lg text-gray-600 mt-2 font-medium">
              It all depends on how you treat it.
            </p>
          </div>
        </div> -->
  </body>
</html>

<?php
$stmt_patient->close();
$conn->close();
?>