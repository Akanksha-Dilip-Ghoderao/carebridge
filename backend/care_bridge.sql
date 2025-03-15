-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 12, 2025 at 06:04 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `care_bridge`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `patient_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `doctor_name` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `address` varchar(255) NOT NULL,
  `status` enum('Pending','Confirmed','Cancelled') DEFAULT 'Pending',
  `doctor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `user_id`, `patient_name`, `description`, `doctor_name`, `date`, `time`, `address`, `status`, `doctor_id`) VALUES
(1, 6, 'Kiran Mishra', 'Chest pain and breathing issue', 'Dr. Amit Sharma', '2025-03-15', '10:00:00', 'Mumbai Hospital, Mumbai', 'Confirmed', 1),
(2, 8, 'Simran Goyal', 'Skin allergy and itching', 'Dr. Priya Verma', '2025-03-16', '11:30:00', 'Delhi Skin Care Clinic, Delhi', 'Confirmed', 2),
(3, 9, 'Tanvi Rastogi', 'Back pain and muscle stiffness', 'Dr. Rajiv Kapoor', '2025-03-17', '09:45:00', 'Chennai Ortho Center, Chennai', 'Confirmed', 3),
(4, 6, 'Kiran Mishra', 'Severe headache and dizziness', 'Dr. Manoj Agarwal', '2025-03-18', '14:00:00', 'Lucknow Neuro Clinic, Lucknow', 'Pending', 4),
(5, 8, 'Simran Goyal', 'Child vaccination and fever', 'Dr. Nitin Choudhary', '2025-03-19', '16:30:00', 'Patna Child Care, Patna', 'Confirmed', 5),
(6, 9, 'Tanvi Rastogi', 'High fever and viral symptoms', 'Dr. Amit Sharma', '2025-03-20', '08:30:00', 'Mumbai Hospital, Mumbai', 'Pending', 1),
(7, 6, 'Kiran Mishra', 'Skin rash and irritation', 'Dr. Priya Verma', '2025-03-21', '12:00:00', 'Delhi Skin Care Clinic, Delhi', 'Confirmed', 2),
(8, 9, 'Tanvi Rastogi', 'Knee pain and inflammation', 'Dr. Rajiv Kapoor', '2025-03-22', '13:15:00', 'Chennai Ortho Center, Chennai', 'Pending', 3),
(9, 8, 'Simran Goyal', 'Migraine and stress issues', 'Dr. Manoj Agarwal', '2025-03-23', '10:45:00', 'Lucknow Neuro Clinic, Lucknow', 'Confirmed', 4),
(10, 6, 'Kiran Mishra', 'Cough, cold, body pain, and flu', 'Dr. Nitin Choudhary', '2025-03-28', '18:00:00', 'Patna Child Care, Patna', 'Confirmed', 5),
(11, 6, 'kiran_mishra', 'Feeling weak, persistent cough', '', '2025-03-22', '16:27:00', '', 'Pending', 1),
(12, 9, 'tanvi_rastogi', 'Body pain', '', '2025-03-20', '03:25:00', '', 'Confirmed', 5);

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `doctor_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `specialty` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`doctor_id`, `first_name`, `last_name`, `gender`, `specialty`, `city`, `username`, `password`, `created_at`) VALUES
(1, 'Amit', 'Sharma', 'Male', 'Cardiologist', 'Mumbai', 'amit_sharma', 'amit@123', '2025-03-11 04:30:00'),
(2, 'Priya', 'Verma', 'Female', 'Dermatologist', 'Delhi', 'priya_verma', 'priya@123', '2025-03-11 04:35:00'),
(3, 'Rajiv', 'Kapoor', 'Male', 'Orthopedic', 'Chennai', 'rajiv_kapoor', 'rajiv@123', '2025-03-11 05:00:00'),
(4, 'Manoj', 'Agarwal', 'Male', 'Neurologist', 'Lucknow', 'manoj_agarwal', 'manoj@123', '2025-03-11 05:10:00'),
(5, 'Nitin', 'Choudhary', 'Male', 'Pediatrician', 'Patna', 'nitin_choudhary', 'nitin@123', '2025-03-11 05:20:00'),
(45, 'Ashwin', 'Yadav', 'Male', 'Cardiologist', 'Chennai', 'ashwin_yadav', '$2y$10$yUWD/0zqaQFVPJfnnOrc/eJawW5XPfDYwgydrgvV5XePI.eu1ijWW', '2025-03-12 04:56:29');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `gender` enum('male','female','other') NOT NULL,
  `dob` date NOT NULL,
  `city` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('patient','doctor') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `doctor_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `gender`, `dob`, `city`, `username`, `password`, `user_type`, `created_at`, `doctor_id`) VALUES
(1, 'Amit', 'Sharma', 'male', '1985-07-12', 'Mumbai', 'amit_sharma', '$2y$10$zkULTsf4gPyq.piA56jF5uceyq1zg02wC4Mp/zQAnuprZU2dLCmm.', 'doctor', '2025-03-11 04:30:00', 1),
(2, 'Priya', 'Verma', 'female', '1990-05-20', 'Delhi', 'priya_verma', '$2y$10$SMc867Vmfa6JouyvKeEOPu/ix1wNnWgVLxY49/g93qN8j7Vzl7E9a', 'doctor', '2025-03-11 04:35:00', 2),
(3, 'Rajiv', 'Kapoor', 'male', '1981-02-14', 'Chennai', 'rajiv_kapoor', '$2y$10$SZhyuzmxJbsDl/pnMNRjY.SCkIBxzAW.WBmE9aPa/Zaz0Dx4lq6T2', 'doctor', '2025-03-11 05:00:00', 3),
(4, 'Manoj', 'Agarwal', 'male', '1980-04-30', 'Lucknow', 'manoj_agarwal', '$2y$10$cmh0Zd9afXktVPROcrdpj.fl8pEhxtNwZmrfa0akpo6dxfdp1nwtq', 'doctor', '2025-03-11 05:10:00', 4),
(5, 'Nitin', 'Choudhary', 'male', '1977-09-09', 'Patna', 'nitin_choudhary', '$2y$10$3ItWnZcblTXxIbi.Z/AT1eeaV2kWYpKL/Sx5tUWe1.w.28fdHTux2', 'doctor', '2025-03-11 05:20:00', 5),
(6, 'Kiran', 'Mishra', 'female', '1995-06-18', 'Hyderabad', 'kiran_mishra', '$2y$10$krTE7PPCjVSZMeoxNE.dluYSGRCoRfsATkvSuyVtFHrfhms.YTiUm', 'patient', '2025-03-11 04:55:00', NULL),
(7, 'Vikas', 'Joshi', 'male', '1978-11-10', 'Pune', 'vikas_joshi', '$2y$10$hjtvPv5V3RliWRTrPeSo.uc32Nk1yVJpnMuTvMvzp/oTRe2DINWbS', 'patient', '2025-03-11 04:50:00', NULL),
(8, 'Simran', 'Goyal', 'female', '1996-08-08', 'Jaipur', 'simran_goyal', '$2y$10$vYmwBnTjtr.0XjM4IO9eG.ejKqwOqdrg5bnbXJ8KAR8uhBX51WZeW', 'patient', '2025-03-11 05:05:00', NULL),
(9, 'Tanvi', 'Rastogi', 'female', '1991-07-19', 'Indore', 'tanvi_rastogi', '$2y$10$uWdmOry9VR/U4PuFJDfPqOmWdtnJRhSW7LUCVtymLPpsQ0s0vU3Ea', 'patient', '2025-03-11 05:25:00', NULL),
(10, 'Harish', 'Singhania', 'male', '1976-03-14', 'Nagpur', 'harish_singhania', '$2y$10$WITHxc1v0QCJVhsPWk0s8.H1Ds.Oo5dV3jsdLA2rKDFPjI/k8N.9S', 'patient', '2025-03-11 05:40:00', NULL),
(45, 'Ashwin', 'Yadav', 'male', '0000-00-00', 'Chennai', 'ashwin_yadav', '$2y$10$yUWD/0zqaQFVPJfnnOrc/eJawW5XPfDYwgydrgvV5XePI.eu1ijWW', 'doctor', '2025-03-12 04:56:29', 45),
(46, 'Aditya', 'Ghoderao', 'male', '2000-09-12', 'Nashik', 'aditya_ghoderao', '$2y$10$jFqqM/IYNfc7sVEcgj65IuMCVUZP/OslpA/XtT1OKX3th283amq9q', 'patient', '2025-03-12 04:57:17', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_appointments_doctor` (`doctor_id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`doctor_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_doctor_id` (`doctor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `doctor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_appointments_doctor` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`doctor_id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_doctor_id` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`doctor_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
