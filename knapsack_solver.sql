-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 29, 2024 at 08:08 AM
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
-- Database: `knapsack solver`
--

-- --------------------------------------------------------

--
-- Table structure for table `knapsack_inputs`
--

CREATE TABLE `knapsack_inputs` (
  `id` int(11) NOT NULL,
  `knapsack_type` varchar(50) NOT NULL,
  `weights` text NOT NULL,
  `values` text NOT NULL,
  `capacity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `knapsack_inputs`
--

INSERT INTO `knapsack_inputs` (`id`, `knapsack_type`, `weights`, `values`, `capacity`, `created_at`) VALUES
(20, 'Fractional Knapsack', '10, 20, 30', '60, 100, 120', 50, '2024-10-29 07:06:55'),
(21, '0-1 Knapsack', '20,10,30', '100,60,120', 6, '2024-10-29 07:07:46');

-- --------------------------------------------------------

--
-- Table structure for table `knapsack_results`
--

CREATE TABLE `knapsack_results` (
  `id` int(11) NOT NULL,
  `input_id` int(11) NOT NULL,
  `result` text NOT NULL,
  `calculated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `knapsack_results`
--

INSERT INTO `knapsack_results` (`id`, `input_id`, `result`, `calculated_at`) VALUES
(20, 20, '{\"total_value\":240}', '2024-10-29 07:06:55'),
(21, 21, '{\"total_value\":0}', '2024-10-29 07:07:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `knapsack_inputs`
--
ALTER TABLE `knapsack_inputs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `knapsack_results`
--
ALTER TABLE `knapsack_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `input_id` (`input_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `knapsack_inputs`
--
ALTER TABLE `knapsack_inputs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `knapsack_results`
--
ALTER TABLE `knapsack_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `knapsack_results`
--
ALTER TABLE `knapsack_results`
  ADD CONSTRAINT `knapsack_results_ibfk_1` FOREIGN KEY (`input_id`) REFERENCES `knapsack_inputs` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
