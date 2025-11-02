-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 02, 2025 at 03:39 PM
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
-- Database: `cocolumber`
--

-- --------------------------------------------------------

--
-- Table structure for table `cutting_jobs`
--

CREATE TABLE `cutting_jobs` (
  `job_id` int(11) NOT NULL,
  `job_name` varchar(100) DEFAULT NULL,
  `inventory_id` int(11) DEFAULT NULL,
  `target_quantity` int(11) NOT NULL,
  `size` varchar(50) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `status` enum('Pending','In Progress','Completed') DEFAULT 'Pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cutting_jobs`
--

INSERT INTO `cutting_jobs` (`job_id`, `job_name`, `inventory_id`, `target_quantity`, `size`, `quantity`, `due_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Gemelina Planks', 1, 0, '2x2x8', 50, '2025-11-03', 'Pending', '2025-11-01 22:31:24', '2025-11-01 22:31:24'),
(2, 'Mahogany Boards', 2, 0, '2x3x10', 30, '2025-11-04', 'In Progress', '2025-11-01 22:31:24', '2025-11-01 22:31:24'),
(3, 'Coconut Lumber Batch 1', 3, 0, '2x4x12', 60, '2025-10-30', 'Completed', '2025-11-01 22:31:24', '2025-11-01 22:31:24'),
(4, 'Akamiza Logs', 6, 0, '2x2x6', 40, '2025-11-05', 'Pending', '2025-11-01 22:31:24', '2025-11-01 22:31:24'),
(5, 'Acacia Slabs', NULL, 0, '2x3x8', 90, '2025-11-06', 'In Progress', '2025-11-01 22:31:24', '2025-11-01 22:31:24'),
(6, 'Teak Beams', 7, 0, '2x2x10', 35, '2025-11-07', 'Pending', '2025-11-01 22:31:24', '2025-11-01 22:31:24'),
(7, 'Bamboo Poles Batch 2', 5, 0, '3x3x12', 70, '2025-11-08', 'Pending', '2025-11-01 22:31:24', '2025-11-01 22:31:24'),
(8, 'Narra Blocks', 9, 0, '2x4x10', 20, '2025-11-10', 'In Progress', '2025-11-01 22:31:24', '2025-11-01 22:31:24'),
(9, 'Eucalyptus Logs Batch 2', 10, 0, '2x2x12', 80, '2025-11-09', 'Completed', '2025-11-01 22:31:24', '2025-11-01 22:31:24'),
(10, 'Fruit Tree Cutting', 6, 0, '2x2x6', 30, '2025-11-11', 'Pending', '2025-11-01 22:31:24', '2025-11-01 22:31:24'),
(11, 'awdwd', 5, 12, NULL, NULL, '2025-11-02', 'Pending', '2025-11-02 21:59:26', '2025-11-02 21:59:26'),
(14, 'wewdwq', 5, 12, NULL, NULL, '2002-02-02', 'Completed', '2025-11-02 22:25:58', '2025-11-02 22:36:52');

-- --------------------------------------------------------

--
-- Table structure for table `deliveries`
--

CREATE TABLE `deliveries` (
  `delivery_id` int(11) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `inventory_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 0,
  `unit` varchar(20) DEFAULT 'pcs',
  `delivery_date` date DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deliveries`
--

INSERT INTO `deliveries` (`delivery_id`, `supplier_id`, `inventory_id`, `quantity`, `unit`, `delivery_date`, `remarks`, `created_at`) VALUES
(1, 1, 1, 250, 'pcs', '2025-11-01', 'Gemelina Logs delivered', '2025-11-01 22:31:23'),
(2, 2, 2, 180, 'pcs', '2025-10-30', 'Mahogany Lumber delivered', '2025-11-01 22:31:23'),
(3, 3, 3, 300, 'pcs', '2025-10-28', 'Coconut Lumber shipment', '2025-11-01 22:31:23'),
(4, 4, 5, 120, 'pcs', '2025-10-26', 'Bamboo Poles delivery', '2025-11-01 22:31:23'),
(5, 5, NULL, 200, 'pcs', '2025-10-25', 'Acacia Lumber', '2025-11-01 22:31:23'),
(6, 6, 6, 75, 'pcs', '2025-10-24', 'Fruit Tree Logs delivery', '2025-11-01 22:31:23'),
(7, 7, 7, 150, 'pcs', '2025-10-22', 'Teak Wood', '2025-11-01 22:31:23'),
(8, 8, 10, 230, 'pcs', '2025-10-20', 'Eucalyptus Logs', '2025-11-01 22:31:23'),
(9, 9, 9, 50, 'pcs', '2025-10-19', 'Narra Logs batch 1', '2025-11-01 22:31:23'),
(10, 10, 4, 90, 'pcs', '2025-10-18', 'Plywood sheets delivered', '2025-11-01 22:31:23'),
(11, 3, 5, 12, '12', '2025-02-02', 'wadw', '2025-11-02 20:08:39');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_items`
--

CREATE TABLE `inventory_items` (
  `inventory_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `size` varchar(50) DEFAULT NULL,
  `quantity` int(11) DEFAULT 0,
  `unit` varchar(20) DEFAULT 'pcs',
  `status` enum('In Stock','Low Stock','Out of Stock') DEFAULT 'In Stock',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory_items`
--

INSERT INTO `inventory_items` (`inventory_id`, `name`, `type`, `size`, `quantity`, `unit`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Gemelina', 'Lumber', '2x2x8', 365, 'pcs', 'In Stock', '2025-11-01 22:31:23', '2025-11-01 22:31:23'),
(2, 'Mahogany', 'Lumber', '2x3x10', 40, 'pcs', 'Low Stock', '2025-11-01 22:31:23', '2025-11-01 22:31:23'),
(3, 'Coconut Lumber', 'Wood', '2x4x12', 120, 'pcs', 'In Stock', '2025-11-01 22:31:23', '2025-11-01 22:31:23'),
(4, 'Plywood', 'Panel', '1/4x4x8', 0, 'pcs', 'Out of Stock', '2025-11-01 22:31:23', '2025-11-01 22:31:23'),
(5, 'aBamboo', 'Pole', '3x3x12', 107, 'pcs', 'In Stock', '2025-11-01 22:31:23', '2025-11-02 20:08:39'),
(6, 'Fruit Tree Logs', 'Lumber', '2x2x6', 75, 'pcs', 'In Stock', '2025-11-01 22:31:23', '2025-11-01 22:31:23'),
(7, 'Teak Wood', 'Premium', '2x2x10', 35, 'pcs', 'Low Stock', '2025-11-01 22:31:23', '2025-11-01 22:31:23'),
(9, 'Narra', 'Lumber', '2x4x10', 10, 'pcs', 'Low Stock', '2025-11-01 22:31:23', '2025-11-01 22:31:23'),
(10, 'Eucalyptus', 'Lumber', '2x2x12', 210, 'pcs', 'In Stock', '2025-11-01 22:31:23', '2025-11-01 22:31:23'),
(11, 'amahogany', 'lumber', '2x2x8', 12, '12', 'Low Stock', '2025-11-02 19:09:56', '2025-11-02 19:09:56');

-- --------------------------------------------------------

--
-- Table structure for table `job_assignments`
--

CREATE TABLE `job_assignments` (
  `assignment_id` int(11) NOT NULL,
  `job_id` int(11) DEFAULT NULL,
  `worker_id` int(11) DEFAULT NULL,
  `assigned_by` int(11) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `assigned_date` datetime DEFAULT current_timestamp(),
  `status` enum('Assigned','Ongoing','Completed') DEFAULT 'Assigned',
  `completion_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_assignments`
--

INSERT INTO `job_assignments` (`assignment_id`, `job_id`, `worker_id`, `assigned_by`, `remarks`, `assigned_date`, `status`, `completion_date`) VALUES
(1, 1, 1, 3, 'Initial job assigned to Juan', '2025-11-01 22:31:24', 'Assigned', NULL),
(2, 2, NULL, 3, 'Mahogany cutting assigned to Pedro', '2025-11-01 22:31:24', 'Ongoing', NULL),
(3, 3, 3, 3, 'Completed by Mario', '2025-11-01 22:31:24', 'Completed', NULL),
(4, 4, 4, 3, 'Akamiza logs cutting', '2025-11-01 22:31:24', 'Assigned', NULL),
(5, 5, 5, 3, 'Acacia slabs in progress', '2025-11-01 22:31:24', 'Ongoing', NULL),
(6, 6, 6, 4, 'Teak beams assigned', '2025-11-01 22:31:24', 'Assigned', NULL),
(7, 7, 7, 4, 'Bamboo batch 2', '2025-11-01 22:31:24', 'Assigned', NULL),
(8, 8, 8, 3, 'Narra cutting started', '2025-11-01 22:31:24', 'Ongoing', NULL),
(9, 9, 9, 4, 'Eucalyptus completed', '2025-11-01 22:31:24', 'Completed', NULL),
(10, 10, 10, 3, 'Fruit tree logs', '2025-11-01 22:31:24', 'Assigned', NULL),
(11, 11, 4, NULL, NULL, '2025-11-02 00:00:00', 'Assigned', NULL),
(14, 14, 4, NULL, NULL, '2025-11-02 22:36:52', 'Completed', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `movement_id` int(11) NOT NULL,
  `inventory_id` int(11) DEFAULT NULL,
  `movement_type` enum('IN','OUT') DEFAULT 'IN',
  `quantity` int(11) DEFAULT 0,
  `reference_type` enum('delivery','cutting_job','manual_adjustment') DEFAULT 'delivery',
  `reference_id` int(11) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock_movements`
--

INSERT INTO `stock_movements` (`movement_id`, `inventory_id`, `movement_type`, `quantity`, `reference_type`, `reference_id`, `remarks`, `created_at`) VALUES
(1, 1, 'IN', 250, 'delivery', 1, 'Received from Mahayag Timber Corp.', '2025-11-01 22:31:23'),
(2, 2, 'IN', 180, 'delivery', 2, 'Received from Zambo Wood Supplies', '2025-11-01 22:31:23'),
(3, 3, 'IN', 300, 'delivery', 3, 'Received from EcoLumber Trading', '2025-11-01 22:31:23'),
(4, 5, 'IN', 120, 'delivery', 4, 'Received Bamboo poles', '2025-11-01 22:31:23'),
(6, 6, 'IN', 75, 'delivery', 6, 'Fruit tree logs arrived', '2025-11-01 22:31:23'),
(7, 7, 'IN', 150, 'delivery', 7, 'Teak wood received', '2025-11-01 22:31:23'),
(8, 10, 'IN', 230, 'delivery', 8, 'Eucalyptus logs restocked', '2025-11-01 22:31:23'),
(9, 9, 'IN', 50, 'delivery', 9, 'Narra logs arrived', '2025-11-01 22:31:23'),
(10, 4, 'IN', 90, 'delivery', 10, 'Plywood sheets delivered', '2025-11-01 22:31:23'),
(11, 1, 'OUT', 50, 'cutting_job', 1, 'Gemelina planks cutting', '2025-11-01 22:31:24'),
(12, 2, 'OUT', 30, 'cutting_job', 2, 'Mahogany boards cutting', '2025-11-01 22:31:24'),
(13, 3, 'OUT', 60, 'cutting_job', 3, 'Coconut lumber batch 1', '2025-11-01 22:31:24'),
(14, 6, 'OUT', 40, 'cutting_job', 4, 'Akamiza logs', '2025-11-01 22:31:24'),
(16, 7, 'OUT', 35, 'cutting_job', 6, 'Teak beams job', '2025-11-01 22:31:24'),
(17, 5, 'OUT', 70, 'cutting_job', 7, 'Bamboo batch 2', '2025-11-01 22:31:24'),
(18, 9, 'OUT', 20, 'cutting_job', 8, 'Narra blocks', '2025-11-01 22:31:24'),
(19, 10, 'OUT', 80, 'cutting_job', 9, 'Eucalyptus logs job', '2025-11-01 22:31:24'),
(20, 6, 'OUT', 30, 'cutting_job', 10, 'Fruit tree cutting job', '2025-11-01 22:31:24');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `supplier_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `email_or_phone` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`supplier_id`, `name`, `contact_person`, `address`, `email_or_phone`, `created_at`) VALUES
(1, 'Mahayag Timber Corp.', 'John Dela Cruz', 'Poblacion, Mahayag', 'timbercorp@gmail.com', '2025-11-01 22:31:23'),
(2, 'Zambo Wood Supplies', 'Maria Lopez', 'Molave, Zamboanga del Sur', 'zambowood@gmail.com', '2025-11-01 22:31:23'),
(3, 'EcoLumber Trading', 'Ramon Bautista', 'Pagadian City', 'eco.lumbertrading@yahoo.com', '2025-11-01 22:31:23'),
(4, 'BlueWood Distributors', 'Erika Villanueva', 'Aurora, ZDS', 'greenwood@gmail.com', '2025-11-01 22:31:23'),
(5, 'Southern Woodlink', 'Jose Cruz', 'Dumingag, ZDS', 'southernwood@gmail.com', '2025-11-01 22:31:23'),
(6, 'TreeLife Lumber', 'Albert Reyes', 'Pagadian City', 'treelife@yahoo.com', '2025-11-01 22:31:23'),
(7, 'Lumber City Depot', 'Karen Manaloto', 'Molave Town', 'lumbercity@gmail.com', '2025-11-01 22:31:23'),
(8, 'Woodgrow Resources', 'Joel Aquino', 'Mahayag', 'woodgrow@gmail.com', '2025-11-01 22:31:23'),
(9, 'Mindanao Forest Supply', 'Rico Santos', 'Iligan City', 'mindforest@gmail.com', '2025-11-01 22:31:23'),
(10, 'Narra Wood Traders', 'Edwin Morales', 'Aurora, ZDS', 'narrawood@gmail.com', '2025-11-01 22:31:23');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff') DEFAULT 'staff',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(3, 'admin', 'admin', 'admin', '2025-10-14 04:45:08'),
(4, 'staff', 'staff', 'staff', '2025-10-14 04:45:08');

-- --------------------------------------------------------

--
-- Table structure for table `workers`
--

CREATE TABLE `workers` (
  `worker_id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contract_no` varchar(50) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workers`
--

INSERT INTO `workers` (`worker_id`, `full_name`, `age`, `address`, `contract_no`, `status`, `created_at`) VALUES
(1, 'Juan Dela Cruz', 32, 'Purok 4, Mahayag', 'CNT-2025-001', 'Active', '2025-11-01 22:31:23'),
(3, 'Mario Lopez', 29, 'Sitio Proper, Mahayag', 'CNT-2025-003', 'Active', '2025-11-01 22:31:23'),
(4, 'Carlos Ramos', 36, 'Mahayag Town Center', 'CNT-2025-004', 'Active', '2025-11-01 22:31:23'),
(5, 'Jomar Fernadawdwaddaw', 27, 'Poblacion, Mahayag', 'CNT-2025-005', 'Active', '2025-11-01 22:31:23'),
(6, 'Reynaldo Cruz', 31, 'Barangay Laperian', 'CNT-2025-006', 'Active', '2025-11-01 22:31:23'),
(7, 'Nestor Robles', 45, 'Molave Town', 'CNT-2025-007', 'Active', '2025-11-01 22:31:23'),
(8, 'Leonardo Aquino', 33, 'Dumingag ZDS', 'CNT-2025-008', 'Active', '2025-11-01 22:31:23'),
(9, 'Oscar Dizon', 29, 'Pagadian City', 'CNT-2025-009', 'Active', '2025-11-01 22:31:23'),
(10, 'Mark Rivera', 34, 'Aurora Town', 'CNT-2025-010', 'Active', '2025-11-01 22:31:23'),
(11, 'sample thisdw', 212, 'dawdawd', 'CNT-2025-001', 'Active', '2025-11-02 20:41:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cutting_jobs`
--
ALTER TABLE `cutting_jobs`
  ADD PRIMARY KEY (`job_id`),
  ADD KEY `inventory_id` (`inventory_id`);

--
-- Indexes for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD PRIMARY KEY (`delivery_id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `inventory_id` (`inventory_id`);

--
-- Indexes for table `inventory_items`
--
ALTER TABLE `inventory_items`
  ADD PRIMARY KEY (`inventory_id`);

--
-- Indexes for table `job_assignments`
--
ALTER TABLE `job_assignments`
  ADD PRIMARY KEY (`assignment_id`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `worker_id` (`worker_id`),
  ADD KEY `assigned_by` (`assigned_by`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`movement_id`),
  ADD KEY `inventory_id` (`inventory_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workers`
--
ALTER TABLE `workers`
  ADD PRIMARY KEY (`worker_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cutting_jobs`
--
ALTER TABLE `cutting_jobs`
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `deliveries`
--
ALTER TABLE `deliveries`
  MODIFY `delivery_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `inventory_items`
--
ALTER TABLE `inventory_items`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `job_assignments`
--
ALTER TABLE `job_assignments`
  MODIFY `assignment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `movement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `workers`
--
ALTER TABLE `workers`
  MODIFY `worker_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cutting_jobs`
--
ALTER TABLE `cutting_jobs`
  ADD CONSTRAINT `cutting_jobs_ibfk_1` FOREIGN KEY (`inventory_id`) REFERENCES `inventory_items` (`inventory_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD CONSTRAINT `deliveries_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `deliveries_ibfk_2` FOREIGN KEY (`inventory_id`) REFERENCES `inventory_items` (`inventory_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `job_assignments`
--
ALTER TABLE `job_assignments`
  ADD CONSTRAINT `job_assignments_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `cutting_jobs` (`job_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `job_assignments_ibfk_2` FOREIGN KEY (`worker_id`) REFERENCES `workers` (`worker_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `job_assignments_ibfk_3` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_ibfk_1` FOREIGN KEY (`inventory_id`) REFERENCES `inventory_items` (`inventory_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
