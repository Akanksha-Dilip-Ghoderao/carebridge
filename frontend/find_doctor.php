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

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.html"); // Redirect if not logged in
    exit();
}

$username = $_SESSION['username'];

// Fetch doctors from the database
$doctor_query = "SELECT * FROM doctors";
$result = $conn->query($doctor_query);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Find a Doctor</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome for Icons -->
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
      body {
        font-family: "Montserrat", sans-serif;
        background-color: #e0f7fa;
      }
    </style>
  </head>
  <body class="bg-gray-100 h-screen">
    <!-- NAVIGATION BAR -->
    <header
      class="bg-teal-600 text-white py-4 px-6 flex justify-between items-center"
    >
      <div class="flex items-center gap-4">
        
        <img
          src="./assets/images/logo.png"
          alt="logo"
          class="h-12 w-12 rounded-full"
        />
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
          <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; ?>
        </span>
        <a href="../backend/logout.php" class="text-sm underline font-medium">Log Out</a>
      </div>
    </header>

    <!-- FIND DOCTOR SECTION -->
    <div class="max-w-3xl mx-auto bg-white p-8 mt-10 rounded-lg shadow-md flex flex-col h-[calc(100%_-_140px)]">
      <h1 class="text-2xl font-semibold text-teal-700 text-center mb-6">
        Find a Doctor
      </h1>

      <div class="space-y-4 overflow-y-auto h-full">
        <?php if ($result->num_rows > 0): ?>
          <?php while ($doctor = $result->fetch_assoc()): ?>
            <div
              class="flex justify-between items-center p-4 border border-teal-600 rounded-lg bg-teal-50"
            >
              <div>
                <?php 
                $availability = rand(0, 1) ? 'Available' : 'Not Available'; // Simulating availability
                $statusClass = ($availability === 'Available') ? 'bg-teal-600' : 'bg-red-600';
                ?>
                <?php if ($availability === 'Available'): ?>
                  <div
                    class="text-lg font-semibold text-teal-700 cursor-pointer underline"
                    onclick="makeAppointment('<?php echo $doctor['first_name'] . ' ' . $doctor['last_name']; ?>')"
                  >
                    Dr. <?php echo htmlspecialchars($doctor['first_name'] . ' ' . $doctor['last_name']); ?>
                  </div>
                <?php else: ?>
                  <div class="text-lg font-semibold text-gray-500">
                    Dr. <?php echo htmlspecialchars($doctor['first_name'] . ' ' . $doctor['last_name']); ?>
                  </div>
                <?php endif; ?>
                <div class="text-sm text-teal-600"><?php echo htmlspecialchars($doctor['specialty']); ?></div>
                <div>📍 <?php echo htmlspecialchars($doctor['city']); ?></div>
              </div>
              <span class="px-3 py-1 text-sm font-medium text-white rounded <?php echo $statusClass; ?>">
                <?php echo $availability; ?>
              </span>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p class="text-center text-gray-600">No doctors available at the moment.</p>
        <?php endif; ?>
      </div>
    </div>

    <script>
      function makeAppointment(doctorName) {
        window.location.href = `make_appointment.php?doctor=${encodeURIComponent(doctorName)}`;
      }
    </script>
  </body>
</html>

<?php $conn->close(); ?>
