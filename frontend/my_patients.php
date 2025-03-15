<?php
session_start();

// Check if user is logged in and is a doctor
if (!isset($_SESSION["username"]) || !isset($_SESSION["user_type"]) || $_SESSION["user_type"] !== "doctor") {
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

// Get the logged-in doctor's username
$doctor_username = $_SESSION["username"];

// Fetch doctor_id from users table
$sql_doctor = "SELECT doctor_id FROM users WHERE username = ? AND user_type = 'doctor'";
$stmt_doctor = $conn->prepare($sql_doctor);
$stmt_doctor->bind_param("s", $doctor_username);
$stmt_doctor->execute();
$result_doctor = $stmt_doctor->get_result();

if ($result_doctor->num_rows === 0) {
    die("Error: Doctor not found in the database.");
}

$doctor_row = $result_doctor->fetch_assoc();
$doctor_id = $doctor_row["doctor_id"];

// Fetch patients assigned to the logged-in doctor
$sql = "SELECT users.first_name, users.last_name, users.gender, users.city, appointments.date 
        FROM appointments 
        JOIN users ON appointments.user_id = users.id 
        WHERE appointments.doctor_id = ?
        ORDER BY appointments.date DESC";

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
    <title>My Patients</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #0f766e;
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
    <h1 class="text-3xl font-semibold text-teal-700">My Patients</h1>
    <p class="text-gray-600 mt-2">List of patients assigned to you along with their appointment dates.</p>

    <table>
        <thead>
            <tr>
                <th>Patient Name</th>
                <th>Gender</th>
                <th>City</th>
                <th>Appointment Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row["first_name"] . " " . $row["last_name"]); ?></td>
                    <td><?php echo htmlspecialchars($row["gender"]); ?></td>
                    <td><?php echo htmlspecialchars($row["city"]); ?></td>
                    <td><?php echo htmlspecialchars($row["date"]); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

</div>

</body>
</html>

<?php
$stmt->close();
$stmt_doctor->close();
$conn->close();
?>
