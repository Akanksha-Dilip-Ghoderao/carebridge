<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Make an Appointment</title>
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
        font-family: "Montserrat", sans-serif;
        background-color: #e0f7fa;
      }
    </style>
  </head>
  <body>
    <!-- HEADER -->
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
      <nav class="hidden md:flex gap-6 text-[16px] font-medium">
        <a href="./find_doctor.php" class="hover:underline">Find Doctor</a>
        <a href="./my_appointments.php" class="hover:underline"
          >My Appointment</a
        >
        <a href="./about_us.php" class="hover:underline">About Us</a>
        <a href="./contact_us.php" class="hover:underline">Contact Us</a>
      </nav>
      <div class="flex items-center gap-3">
        <i class="fa-regular fa-user text-xl"></i>
        <span class="hidden md:block text-[15px] font-medium">
          <?php
            session_start();
            if (isset($_SESSION['username'])) {
                echo $_SESSION['username'];
            } else {
                echo "Guest (Session Issue)";
            }
          ?>
        </span>
        <a href="../backend/logout.php" class="text-sm underline font-medium"
          >Log Out</a
        >
      </div>
    </header>

    <!-- APPOINTMENT FORM -->
    <div class="flex justify-center items-center h-screen">
      <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h1 class="text-2xl font-semibold text-teal-700 text-center mb-4">
          Make an Appointment
        </h1>
        <form action="../backend/process_appointment.php" method="post">
          <input
            type="text"
            id="doctor-name"
            class="w-full border border-teal-600 rounded p-2 mb-3"
            name="doctor"
            readonly
          />
          <input
            type="text"
            id="patient-name"
            class="w-full border border-teal-600 rounded p-2 mb-3"
            name="patient"
            value="<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>"
            required
          />
          <input
            type="date"
            class="w-full border border-teal-600 rounded p-2 mb-3"
            name="date"
            required
          />
          <input
            type="time"
            class="w-full border border-teal-600 rounded p-2 mb-3"
            name="time"
            required
          />
          <textarea
            class="w-full border border-teal-600 rounded p-2 mb-3"
            name="ailment"
            placeholder="Describe your ailment"
            required
          ></textarea>
          <button
            type="submit"
            class="w-full bg-teal-600 text-white p-2 rounded font-medium hover:bg-teal-800"
          >
            Book Appointment
          </button>
        </form>
      </div>
    </div>

    <script>
      // Extract doctor name from URL and set it in the form
      const urlParams = new URLSearchParams(window.location.search);
      const doctorName = urlParams.get("doctor");
      if (doctorName) {
        document.getElementById("doctor-name").value = doctorName;
      }
    </script>
  </body>
</html>
