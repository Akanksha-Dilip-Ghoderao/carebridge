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

// Check if the doctor is logged in
if (!isset($_SESSION["username"]) || $_SESSION["user_type"] !== "doctor") {
    header("Location: ../backend/login.php"); // Redirect if not logged in
    exit();
}

$doctor_username = $_SESSION["username"];

// Retrieve doctor's user_id
$sql = "SELECT doctor_id FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $doctor_username);
$stmt->execute();
$result = $stmt->get_result();
$doctor = $result->fetch_assoc();
$doctor_id = $doctor['doctor_id'];

// Retrieve doctor's appointments
$sql = "SELECT appointment_id, patient_name, date, time, status 
        FROM appointments 
        WHERE doctor_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Doctor Appointments</title>
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
        .btn {
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .pending {
            background-color: orange;
            color: white;
        }
        .confirmed {
            background-color: green;
            color: white;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2 class="text-2xl font-semibold">Doctor's Panel</h2>
        <nav>
            <a href="./doctor_appointments.php"><i class="fa-solid fa-calendar-check"></i> Appointments</a>
            <a href="./my_patients.php"><i class="fa-solid fa-notes-medical"></i> Patients</a>
            <a href="../backend/logout.php"><i class="fa-solid fa-sign-out"></i> Logout</a>
        </nav>
    </div>

    <div class="dashboard">
        <h2 class="text-3xl font-semibold text-teal-700">Your Appointments</h2>
        <table class="w-full mt-5 border-collapse">
            <thead>
                <tr class="bg-teal-600 text-white">
                    <th class="p-3">Patient Name</th>
                    <th class="p-3">Date</th>
                    <th class="p-3">Time</th>
                    <th class="p-3">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr class="border-b text-center">
                    <td class="p-3"><?php echo htmlspecialchars($row["patient_name"]); ?></td>
                    <td class="p-3"><?php echo htmlspecialchars($row["date"]); ?></td>
                    <td class="p-3"><?php echo htmlspecialchars($row["time"]); ?></td>
                    <td class="p-3">
                        <button class="btn <?php echo $row['status'] === 'Pending' ? 'pending' : 'confirmed'; ?>" 
                                onclick="updateStatus(<?php echo $row['appointment_id']; ?>, this)">
                            <?php echo htmlspecialchars($row["status"]); ?>
                        </button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
        function updateStatus(appointmentId, button) {
            fetch('../backend/update_appointment_status.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'appointment_id=' + appointmentId
            })
            .then(response => response.text())
            .then(data => {
                if (data === 'success') {
                    button.innerText = 'Confirmed';
                    button.classList.remove('pending');
                    button.classList.add('confirmed');
                } else {
                    alert('Error updating appointment status');
                }
            });
        }
    </script>
</body>
</html>
