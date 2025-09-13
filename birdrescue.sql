-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 08, 2025 at 08:05 PM
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
-- Database: `birdrescue`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `birdsheltermaster`
--

CREATE TABLE `birdsheltermaster` (
  `id` int(11) NOT NULL,
  `incharge_name` varchar(100) NOT NULL,
  `incharge_phone` varchar(15) NOT NULL,
  `address` varchar(100) NOT NULL,
  `bird_shelter_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `birdsheltermaster`
--

INSERT INTO `birdsheltermaster` (`id`, `incharge_name`, `incharge_phone`, `address`, `bird_shelter_name`) VALUES
(1, '', '', '', 'omkar vesu'),
(2, '', '', '', 'Kataragam '),
(3, '', '', '', 'Jahangirpura'),
(4, '', '', '', 'Nana Varachha'),
(5, '', '', '', 'Katargam, Katargam Darwaja, Gotalawadi, Sumul Dairy Road'),
(6, '', '', '', 'Amroli');

-- --------------------------------------------------------

--
-- Table structure for table `birdsmaster`
--

CREATE TABLE `birdsmaster` (
  `B_id` int(11) NOT NULL,
  `bird_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `birdsmaster`
--

INSERT INTO `birdsmaster` (`B_id`, `bird_name`) VALUES
(1, 'peacock '),
(2, 'pork'),
(3, 'golu');

-- --------------------------------------------------------

--
-- Table structure for table `birds_rescue`
--

CREATE TABLE `birds_rescue` (
  `id` int(11) NOT NULL,
  `bird_image` varchar(255) NOT NULL,
  `bird` text NOT NULL,
  `caller_mobile` varchar(15) NOT NULL,
  `caller_name` varchar(255) NOT NULL,
  `number_of_birds` int(11) NOT NULL,
  `location` text DEFAULT NULL,
  `address` text NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `assigned_to` int(11) DEFAULT NULL,
  `bird_species_id` int(11) DEFAULT NULL,
  `rescue_status_id` int(11) DEFAULT NULL,
  `bird_shelter_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

CREATE TABLE `contact_us` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `id` int(11) NOT NULL,
  `donor_name` varchar(255) NOT NULL,
  `contact` varchar(15) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `donation_date` date NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `payment_image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rolemaster`
--

CREATE TABLE `rolemaster` (
  `role_id` int(11) NOT NULL,
  `roles` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rolemaster`
--

INSERT INTO `rolemaster` (`role_id`, `roles`) VALUES
(1, 'work'),
(2, 'nothing');

-- --------------------------------------------------------

--
-- Table structure for table `statusmaster`
--

CREATE TABLE `statusmaster` (
  `id` int(11) NOT NULL,
  `bird_status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `statusmaster`
--

INSERT INTO `statusmaster` (`id`, `bird_status`) VALUES
(1, 'Padding '),
(2, 'Rescue '),
(3, 'fly'),
(4, 'death');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Volunteer') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `volunteers`
--

CREATE TABLE `volunteers` (
  `id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL DEFAULT 'assets/images/default.jpg',
  `username` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `family_contact_number` varchar(15) NOT NULL,
  `preferred_areas` text NOT NULL,
  `preferred_birds` text NOT NULL,
  `monthly_rescue_capacity` int(11) NOT NULL,
  `preferred_days` text NOT NULL,
  `preferred_time` varchar(50) NOT NULL,
  `blood_group` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `approved` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `birdsheltermaster`
--
ALTER TABLE `birdsheltermaster`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `birdsmaster`
--
ALTER TABLE `birdsmaster`
  ADD PRIMARY KEY (`B_id`);

--
-- Indexes for table `birds_rescue`
--
ALTER TABLE `birds_rescue`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bird_species_id` (`bird_species_id`),
  ADD KEY `rescue_status_id` (`rescue_status_id`),
  ADD KEY `bird_shelter_id` (`bird_shelter_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rolemaster`
--
ALTER TABLE `rolemaster`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `statusmaster`
--
ALTER TABLE `statusmaster`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `volunteers`
--
ALTER TABLE `volunteers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `birdsheltermaster`
--
ALTER TABLE `birdsheltermaster`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `birdsmaster`
--
ALTER TABLE `birdsmaster`
  MODIFY `B_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `birds_rescue`
--
ALTER TABLE `birds_rescue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `rolemaster`
--
ALTER TABLE `rolemaster`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `statusmaster`
--
ALTER TABLE `statusmaster`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `volunteers`
--
ALTER TABLE `volunteers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `birds_rescue`
--
ALTER TABLE `birds_rescue`
  ADD CONSTRAINT `birds_rescue_ibfk_1` FOREIGN KEY (`bird_species_id`) REFERENCES `birdsmaster` (`B_id`),
  ADD CONSTRAINT `birds_rescue_ibfk_2` FOREIGN KEY (`rescue_status_id`) REFERENCES `statusmaster` (`id`),
  ADD CONSTRAINT `birds_rescue_ibfk_3` FOREIGN KEY (`bird_shelter_id`) REFERENCES `birdsheltermaster` (`id`),
  ADD CONSTRAINT `birds_rescue_ibfk_4` FOREIGN KEY (`role_id`) REFERENCES `rolemaster` (`role_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
