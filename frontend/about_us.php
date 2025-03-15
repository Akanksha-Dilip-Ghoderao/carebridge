<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Font Awesome for Icons -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
      integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />

    <!-- Google Fonts -->
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />

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

    <title>About Us</title>

    <style>
      body {
        font-family: "Montserrat", sans-serif;
        background-color: #e0f7fa;
      }

      .container {
        max-width: 1000px;
        margin: auto;
        padding: 20px;
      }

      .para {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
        line-height: 1.8;
      }
    </style>
  </head>
  <body class="h-screen">
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
      <nav class="hidden md:flex gap-6 text-[16px] font-small">
        <a href="./find_doctor.php" class="hover:underline">Find Doctor</a>
        <a href="my_appointments.php" class="hover:underline">My Appointment</a>
        <a href="about_us.php" class="hover:underline">About Us</a>
        <a href="contact_us.php" class="hover:underline">Contact Us</a>
      </nav>
      <div class="flex items-center gap-3">
        <i class="fa-regular fa-user text-xl"></i>
        <span class="hidden md:block text-[15px] font-medium">
          <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; ?>
        </span>
        <a href="../backend/logout.php" class="text-sm underline font-medium"
          >Log Out</a
        >
      </div>
    </header>

    <!-- ABOUT US SECTION -->
    <div class="container h-[calc(100%_-_140px)]">
      <div class="para h-full flex flex-col">
        <h3 class="text-2xl font-semibold text-gray-800 mb-4">
          About Us – Care Bridge
        </h3>
        <div class=" h-full overflow-y-auto">
        <b><p>Connecting Patients with Trusted Doctors</p></b>
        <p class="text-gray-700">
          Welcome to Care Bridge, your trusted platform for finding and booking
          appointments with qualified doctors. Our mission is to make healthcare
          accessible, convenient, and hassle-free for everyone. Whether you need
          a routine check-up or specialized medical care, we connect you with
          experienced professionals who are ready to help.<br />
          Why Choose Care Bridge? <br /><br />For Patients: <br />✅ Find the
          Right Doctor <br />✅ Browse through a wide range of doctors based on
          specialty, location, and availability. <br />✅ Book Appointments
          Online <br />✅ Say goodbye to long waiting times! Schedule
          appointments at your convenience. <br />✅ Easy Access to Medical Care
          <br />✅ Whether you're at home or on the go, find the best healthcare
          professionals near you. <br /><br />For Doctors: <br />✅ Manage Your
          Appointments <br />✅ Keep track of patient bookings and schedules
          with ease. <br />✅ Grow Your Practice <br />✅ Reach more patients
          and expand your professional network. <br />✅ Improve Patient
          Experience <br />✅ Provide seamless consultation and care with an
          organized appointment system. <br /><br /><b
            >Our Vision At Care Bridge</b
          ><br />We envision a world where healthcare is efficient, transparent,
          and patient-centered. We aim to bridge the gap between doctors and
          patients, ensuring that everyone receives timely medical attention.
          <br /><br /><b>How It Works?</b><br />1️⃣ Search for a Doctor – Find
          the right specialist based on your needs.<br />2️⃣ Book an Appointment
          – Select a suitable time slot and confirm your appointment.<br />3️⃣
          Visit & Consult – Meet your doctor, get expert advice, and stay
          healthy! <br /><br />Join Us Today! Whether you’re a patient looking
          for quality healthcare or a doctor seeking to manage appointments
          efficiently. <br />Care Bridge is here to support you. <br /><b
            >Together, let’s build a healthier tomorrow!</b
          >
        </p>
        </div>
      </div>
    </div>
  </body>
</html>
