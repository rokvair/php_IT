-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 20, 2024 at 01:39 AM
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
-- Database: `laivai`
--

-- --------------------------------------------------------

--
-- Table structure for table `atsiliepimai`
--

CREATE TABLE `atsiliepimai` (
  `id` int(20) NOT NULL,
  `nuomotojas` int(5) NOT NULL,
  `naudotojas` int(5) NOT NULL,
  `tekstas` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ivertinimas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `atsiliepimai`
--

INSERT INTO `atsiliepimai` (`id`, `nuomotojas`, `naudotojas`, `tekstas`, `ivertinimas`) VALUES
(1, 1, 1004, 'Great experience renting from this user!', 5),
(2, 2, 1005, 'Good communication and timely responses.', 4),
(3, 3, 1006, 'Very helpful and accommodating. Highly recommend!', 5),
(4, 4, 1004, 'Satisfactory overall. Could improve availability.', 3),
(5, 7, 1007, 'BEST', 5),
(6, 7, 1007, 'NICE', 4),
(7, 7, 1007, 'G', 3);

-- --------------------------------------------------------

--
-- Table structure for table `jachtos`
--

CREATE TABLE `jachtos` (
  `id` int(5) NOT NULL,
  `pavadinimas` varchar(255) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `kaina` decimal(10,2) DEFAULT NULL,
  `savId` int(5) NOT NULL,
  `aprasas` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `jachtos`
--

INSERT INTO `jachtos` (`id`, `pavadinimas`, `foto`, `kaina`, `savId`, `aprasas`) VALUES
(1, 'Ocean Breeze', 'ocean_breeze.jpg', 500.00, 1, 'geras laivelis rekomenduoju'),
(2, 'Sea Voyager', 'sea_voyager.jpg', 750.00, 2, 'liuksas per bangas'),
(3, 'Blue Horizon', 'blue_horizon.jpg', 600.00, 3, 'greitas, bei erdvus\r\n'),
(4, 'Golden Wave', 'golden_wave.jpg', 1000.00, 4, 'Skris su vėjeliu\r\n'),
(5, 'Laivelis', 'https://iqboatlifts.com/wp-content/uploads/2018/06/Yacht-vs-Boat-Whats-the-Difference-Between-the-Two-1024x571.jpg', 800.00, 6, 'Gražus laivelis '),
(6, 'M?lyn?', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTQux6x_AMr0sJZLb5blYzbudLHTx5eGJhM4A&s', 1000.00, 7, 'Graži tikrai'),
(8, 'Laivelis', 'https://iqboatlifts.com/wp-content/uploads/2018/06/Yacht-vs-Boat-Whats-the-Difference-Between-the-Two-1024x571.jpg', 2323.00, 8, 'ssssss'),
(9, 'BlueB', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTQux6x_AMr0sJZLb5blYzbudLHTx5eGJhM4A&s', 722.00, 8, 'GSSG'),
(10, 'Laivynelis', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTQux6x_AMr0sJZLb5blYzbudLHTx5eGJhM4A&s', 3322.00, 8, 'Gerulis');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(20) NOT NULL,
  `siuntejo_id` int(10) NOT NULL,
  `gavejo_id` int(10) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `message` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `siuntejo_id`, `gavejo_id`, `created`, `message`) VALUES
(10, 6, 1006, '2024-12-20 01:01:37', 'HOWDY'),
(11, 7, 1005, '2024-12-20 01:09:03', 'sadasdad'),
(12, 1007, 3, '2024-12-20 01:18:56', 'HOWDY'),
(13, 1007, 3, '2024-12-20 01:18:59', 'HOWDY'),
(14, 8, 1007, '2024-12-20 01:20:29', 'Hey'),
(15, 1007, 7, '2024-12-20 01:21:03', 'HEYYYY'),
(16, 1007, 8, '2024-12-20 01:46:32', 'PSSST'),
(17, 8, 1007, '2024-12-20 01:46:47', 'yo'),
(18, 1007, 8, '2024-12-20 02:27:39', 'NICE BOAT'),
(19, 1007, 8, '2024-12-20 02:27:44', 'NICE BOAT'),
(20, 8, 1007, '2024-12-20 02:28:06', 'ACIU');

-- --------------------------------------------------------

--
-- Table structure for table `naudotojai`
--

CREATE TABLE `naudotojai` (
  `id` int(10) NOT NULL,
  `varpav` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `naudotojai`
--

INSERT INTO `naudotojai` (`id`, `varpav`, `email`, `password`) VALUES
(1004, 'aasdgsdh', 'gg@gmail.com', '$2y$10$ZumsRDcAngf1MF6QDwKv5.aErfv1gGE6O6jXf5ih7ZYe2yP/GKqzO'),
(1005, 'asd', 'lololol@gmail.com', '$2y$10$Ta88KqmqunW4Xc2B0iSj.OhbgKMkLiDOxn8x7K6pjAIBiSI53KVX6'),
(1006, 'Jonas', '123@gmail.com', '$2y$10$6JimtvGjTFuOvAblFpoiKOccBfJk9vImcYtgQcXXUZDnS.TYMfb0m'),
(1007, 'Jonas Jonaitis', 'jon@gmail.com', '$2y$10$rK2tIdyV7XDZIWTY46mQfenfGqGq4OXwk5W2un2VpAdQJitYAVipm');

-- --------------------------------------------------------

--
-- Table structure for table `nuomotojai`
--

CREATE TABLE `nuomotojai` (
  `id` int(5) NOT NULL,
  `varpav` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `nuomotojai`
--

INSERT INTO `nuomotojai` (`id`, `varpav`, `email`, `password`) VALUES
(1, 'Alex Johnson', 'alex.johnson@example.com', NULL),
(2, 'Maria Lopez', 'maria.lopez@example.com', NULL),
(3, 'Kevin Smith', 'kevin.smith@example.com', NULL),
(4, 'Sophia Brown', 'sophia.brown@example.com', NULL),
(5, NULL, 'sam@gmail.com', '$2y$10$9yllVKKA8p.IACgMKaPh5.mzBJbPOssirOLueDgfif79zXU9ilM1q'),
(6, NULL, 'same@gmail.com', '$2y$10$1pKUhoedVRnfNCBn.NWYGu5X4s4YIeTJU2lHr3P9hfdC/lK9EHvDe'),
(7, 'Sem Sam', 'sem@gmail.com', '$2y$10$XMqRy1a78gslnQVsLziK7eqdICgTvrJc9Y.1eTKtU3gN2mixxyQCG'),
(8, 'Sem Sam', 'seam@gmail.com', '$2y$10$C87O0QC2mMsuO93L6bussue2oyDLVjDXMO0SoacrSJnextlO1zwJG'),
(42, 'Adm', 'adm@mail.com', '$2y$10$SNyu9lNs02TUPy0ROJVMWug7na9sX7evb8jnf04ZotnvrYqUkF2Xe');

-- --------------------------------------------------------

--
-- Table structure for table `rezervacijos`
--

CREATE TABLE `rezervacijos` (
  `id` int(20) NOT NULL,
  `naudotojas` int(5) NOT NULL,
  `jachta` int(5) NOT NULL,
  `laikotarpis_nuo` datetime NOT NULL DEFAULT current_timestamp(),
  `laikotarpis_iki` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `rezervacijos`
--

INSERT INTO `rezervacijos` (`id`, `naudotojas`, `jachta`, `laikotarpis_nuo`, `laikotarpis_iki`) VALUES
(5, 1004, 1, '2018-05-03 00:00:00', '2022-06-07 00:00:00'),
(6, 1005, 2, '2015-02-03 00:25:00', '2022-06-07 00:25:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `atsiliepimai`
--
ALTER TABLE `atsiliepimai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_nuomotojas_id` (`nuomotojas`),
  ADD KEY `fk_naudotojas_id` (`naudotojas`);

--
-- Indexes for table `jachtos`
--
ALTER TABLE `jachtos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `savId` (`savId`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `naudotojai`
--
ALTER TABLE `naudotojai`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_email` (`email`);

--
-- Indexes for table `nuomotojai`
--
ALTER TABLE `nuomotojai`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rezervacijos`
--
ALTER TABLE `rezervacijos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_naudotojo_id` (`naudotojas`),
  ADD KEY `fk_jachtos_id` (`jachta`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `atsiliepimai`
--
ALTER TABLE `atsiliepimai`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `jachtos`
--
ALTER TABLE `jachtos`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `naudotojai`
--
ALTER TABLE `naudotojai`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1008;

--
-- AUTO_INCREMENT for table `nuomotojai`
--
ALTER TABLE `nuomotojai`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `rezervacijos`
--
ALTER TABLE `rezervacijos`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jachtos`
--
ALTER TABLE `jachtos`
  ADD CONSTRAINT `savId` FOREIGN KEY (`savId`) REFERENCES `nuomotojai` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rezervacijos`
--
ALTER TABLE `rezervacijos`
  ADD CONSTRAINT `fk_jachtos_id` FOREIGN KEY (`jachta`) REFERENCES `jachtos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_naudotojo_id` FOREIGN KEY (`naudotojas`) REFERENCES `naudotojai` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
