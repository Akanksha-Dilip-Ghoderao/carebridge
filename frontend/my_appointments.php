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

// Fetch appointments for the logged-in patient, including status
$sql = "SELECT appointments.appointment_id, doctors.first_name AS doctor_name, doctors.specialty, 
               appointments.date, appointments.time, appointments.description, appointments.status
        FROM appointments
        JOIN doctors ON appointments.doctor_id = doctors.doctor_id
        WHERE appointments.user_id = ?
        ORDER BY appointments.date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
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
    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <style>
      body {
        font-family: "Montserrat", sans-serif;
        background-color: #e0f7fa;
      }
    </style>
    <title>Patient Dashboard</title>
  </head>
  <body class="bg-gray-100">
    <header class="bg-teal-600 text-white py-4 px-6 flex justify-between items-center">
      <div class="flex items-center gap-4">
        <img src="./assets/images/logo.png" alt="logo" class="h-12 w-12 rounded-full" />
        <a href="./patient_dashboard.php" class="hover:underline">
          <span class="text-xl font-semibold tracking-wide">Care Bridge</span>
        </a>
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
          <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : "Guest (Session Issue)"; ?>
        </span>
        <a href="../backend/logout.php" class="text-sm underline font-medium">Log Out</a>
      </div>
    </header>
    <br /><br />

    <div class="dashboard px-6">
      <h1 class="text-3xl font-semibold text-teal-700">My Appointments</h1>
      <p class="text-gray-600 mt-2">Click on an appointment ID to edit it.</p>

      <table class="w-full border-collapse mt-6">
        <thead>
          <tr>
            <th class="bg-teal-600 text-white p-3">Appointment ID</th>
            <th class="bg-teal-600 text-white p-3">Doctor</th>
            <th class="bg-teal-600 text-white p-3">Specialty</th>
            <th class="bg-teal-600 text-white p-3">Date</th>
            <th class="bg-teal-600 text-white p-3">Time</th>
            <th class="bg-teal-600 text-white p-3">Description</th>
            <th class="bg-teal-600 text-white p-3">Status</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
              <td class="p-3 border"><a href="edit_appointment.php?id=<?php echo $row['appointment_id']; ?>" class="text-teal-600 font-bold"><?php echo $row["appointment_id"]; ?></a></td>
              <td class="p-3 border"><?php echo htmlspecialchars($row["doctor_name"]); ?></td>
              <td class="p-3 border"><?php echo htmlspecialchars($row["specialty"]); ?></td>
              <td class="p-3 border"><?php echo htmlspecialchars($row["date"]); ?></td>
              <td class="p-3 border"><?php echo htmlspecialchars($row["time"]); ?></td>
              <td class="p-3 border"><?php echo htmlspecialchars($row["description"]); ?></td>
              <td class="p-3 border font-bold">
                <?php 
                  $status = htmlspecialchars($row["status"]); 
                  if ($status === "Confirmed") {
                    echo '<span class="text-green-600">'.$status.'</span>';
                  } elseif ($status === "Pending") {
                    echo '<span class="text-yellow-600">'.$status.'</span>';
                  } elseif ($status === "Cancelled") {
                    echo '<span class="text-red-600">'.$status.'</span>';
                  } else {
                    echo '<span class="text-gray-600">'.$status.'</span>';
                  }
                ?>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </body>
</html>

<?php
$stmt->close();
$stmt_patient->close();
$conn->close();
?>
