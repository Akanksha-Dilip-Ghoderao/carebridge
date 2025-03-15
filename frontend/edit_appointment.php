<?php
session_start();

// Check if user is logged in as a patient
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

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get appointment ID from URL
if (!isset($_GET["id"])) {
    die("Invalid request.");
}

$appointment_id = $_GET["id"];

// Fetch appointment details (including patient and doctor names)
$sql = "SELECT a.date, a.time, a.description, a.patient_name, a.doctor_name, 
               u.first_name AS patient_first, u.last_name AS patient_last, 
               d.first_name AS doctor_first, d.last_name AS doctor_last 
        FROM appointments a
        JOIN users u ON a.user_id = u.id
        JOIN doctors d ON a.doctor_id = d.doctor_id
        WHERE a.appointment_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Appointment not found.");
}

$appointment = $result->fetch_assoc();
$patient_name = $appointment["patient_first"] . " " . $appointment["patient_last"];
$doctor_name = $appointment["doctor_first"] . " " . $appointment["doctor_last"];

// Update appointment if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_date = trim($_POST["appointment_date"]);
    $new_time = trim($_POST["appointment_time"]);
    $new_description = trim($_POST["description"]);

    $sql_update = "UPDATE appointments SET date = ?, time = ?, description = ? WHERE appointment_id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sssi", $new_date, $new_time, $new_description, $appointment_id);

    if ($stmt_update->execute()) {
        echo "<script>alert('Appointment updated successfully!'); window.location.href='my_appointments.php';</script>";
    } else {
        die("Error updating appointment: " . $stmt_update->error);
    }
}
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
      integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <style>
      body {
        font-family: "Inter", sans-serif;
        background-color: #f9fafb;
      }
    </style>
    <title>Edit Appointment</title>
</head>
<body class="bg-gray-100">
    <!-- HEADER -->
    <header class="bg-teal-600 text-white py-4 px-6 flex justify-between items-center">
        <div class="flex items-center gap-4">
             <a href="./patient_dashboard.php">
<img src="./assets/images/logo.png" alt="logo" class="h-12 w-12 rounded-full" />
</a>
            <span class="text-xl font-semibold tracking-wide">Care Bridge</span>
        </div>
        <nav class="hidden md:flex gap-6 text-[16px] font-medium">
            <a href="./find_doctor.php" class="hover:underline">Find Doctor</a>
            <a href="./my_appointments.php" class="hover:underline">My Appointment</a>
            <a href="./about_us.php" class="hover:underline">About Us</a>
            <a href="./contact_us.php" class="hover:underline">Contact Us</a>
        </nav>
        <div class="flex items-center gap-3">
            <i class="fa-regular fa-user text-xl"></i>
            <span class="hidden md:block text-[15px] font-medium">
              <?php
          if (isset($_SESSION['username'])) {
              echo $_SESSION['username'];
          } else {
              echo "Guest (Session Issue)";
          }
          ?>
            </span>
            <a href="../backend/logout.php" class="text-sm underline font-medium">Log Out</a>
        </div>
    </header>
<br>
    <!-- Main Content -->
    <div class="dashboard py-10 px-6 max-w-lg mx-auto bg-white shadow-lg rounded-lg p-6">
        <h1 class="text-3xl font-semibold text-teal-700">Edit Appointment</h1>

        <form method="POST" class="mt-4">
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Patient Name:</label>
                <input type="text" value="<?php echo htmlspecialchars($patient_name); ?>" disabled class="w-full p-2 border rounded bg-gray-100">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Doctor Name:</label>
                <input type="text" value="<?php echo htmlspecialchars($doctor_name); ?>" disabled class="w-full p-2 border rounded bg-gray-100">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Date:</label>
                <input type="date" name="appointment_date" value="<?php echo htmlspecialchars($appointment["date"]); ?>" required class="w-full p-2 border rounded">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Time:</label>
                <input type="time" name="appointment_time" value="<?php echo htmlspecialchars($appointment["time"]); ?>" required class="w-full p-2 border rounded">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Description:</label>
                <textarea name="description" required class="w-full p-2 border rounded"><?php echo htmlspecialchars($appointment["description"]); ?></textarea>
            </div>
            <div class="flex justify-between">
                <a href="my_appointments.php" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-700">Cancel</a>
                <button type="submit" class="bg-teal-700 text-white px-4 py-2 rounded hover:bg-teal-900">Update Appointment</button>
            </div>
        </form>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
