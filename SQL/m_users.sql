-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Εξυπηρετητής: 127.0.0.1
-- Χρόνος δημιουργίας: 18 Ιαν 2026 στις 13:06:50
-- Έκδοση διακομιστή: 10.4.32-MariaDB
-- Έκδοση PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Βάση δεδομένων: `m_users`
--

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `lab_files`
--

CREATE TABLE `lab_files` (
  `id` int(11) NOT NULL,
  `folder_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `lab_folders`
--

CREATE TABLE `lab_folders` (
  `id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `registrations`
--

CREATE TABLE `registrations` (
  `id` int(11) NOT NULL,
  `am` varchar(50) NOT NULL,
  `section_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `grade` decimal(4,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `registrations`
--

INSERT INTO `registrations` (`id`, `am`, `section_id`, `created_at`, `grade`) VALUES
(39, 'student58990', 1, '2026-01-11 02:20:37', 5.00),
(40, 'student58990', 2, '2026-01-11 02:20:41', NULL),
(56, 'student58990', 3, '2026-01-11 07:20:24', NULL),
(66, 'student58990', 22, '2026-01-12 07:28:19', NULL),
(67, 'student99999', 1, '2026-01-17 16:34:59', 5.50);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `sections`
--

CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `day` varchar(20) NOT NULL,
  `time` varchar(20) NOT NULL,
  `max_students` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `sections`
--

INSERT INTO `sections` (`id`, `day`, `time`, `max_students`, `name`) VALUES
(1, 'Δευτέρα', '10:00', 20, 'Εργαστήριο 1'),
(2, 'Τετάρτη', '12:00', 15, 'Εργαστήριο 2'),
(3, 'Παρασκευή', '14:00', 25, 'Εργαστήριο 3'),
(21, 'Τρίτη', '16:00', 6, 'Εργαστήριο 19'),
(22, 'Παρασκευή', '16:00', 2, 'Εργαστήριο5');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `users`
--

CREATE TABLE `users` (
  `am` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `user_type` enum('user','admin') NOT NULL DEFAULT 'user',
  `password` varchar(255) NOT NULL,
  `section_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `users`
--

INSERT INTO `users` (`am`, `email`, `user_type`, `password`, `section_id`) VALUES
('admin', 'admin@localhost.gr', 'admin', '$2y$10$iDtlRcy9cpf1vn681gpOyOhmV4ak5OsQb2BTjTxFsg2uJtejEmAv6', NULL),
('student58990', 'student58990@hotmail.com', 'user', '$2y$10$xRShC6RM3uSleXrIflaCB.UaRtEEqFSvwGiPv.Q6yYqsC0WBCSd9i', NULL),
('student99999', 'student99999@hotmail.com', 'user', '$2y$10$kHE2gAnr1k2nVN8nBHgbv.0agX1r0Nhcl3mlSk/J9IGqdAl9bFPiG', NULL);

--
-- Ευρετήρια για άχρηστους πίνακες
--

--
-- Ευρετήρια για πίνακα `lab_files`
--
ALTER TABLE `lab_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `folder_id` (`folder_id`);

--
-- Ευρετήρια για πίνακα `lab_folders`
--
ALTER TABLE `lab_folders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `section_id` (`section_id`);

--
-- Ευρετήρια για πίνακα `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `am` (`am`),
  ADD KEY `section_id` (`section_id`);

--
-- Ευρετήρια για πίνακα `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`);

--
-- Ευρετήρια για πίνακα `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`am`);

--
-- AUTO_INCREMENT για άχρηστους πίνακες
--

--
-- AUTO_INCREMENT για πίνακα `lab_files`
--
ALTER TABLE `lab_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT για πίνακα `lab_folders`
--
ALTER TABLE `lab_folders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT για πίνακα `registrations`
--
ALTER TABLE `registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT για πίνακα `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Περιορισμοί για άχρηστους πίνακες
--

--
-- Περιορισμοί για πίνακα `lab_files`
--
ALTER TABLE `lab_files`
  ADD CONSTRAINT `lab_files_ibfk_1` FOREIGN KEY (`folder_id`) REFERENCES `lab_folders` (`id`) ON DELETE CASCADE;

--
-- Περιορισμοί για πίνακα `lab_folders`
--
ALTER TABLE `lab_folders`
  ADD CONSTRAINT `lab_folders_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE;

--
-- Περιορισμοί για πίνακα `registrations`
--
ALTER TABLE `registrations`
  ADD CONSTRAINT `registrations_ibfk_1` FOREIGN KEY (`am`) REFERENCES `users` (`am`),
  ADD CONSTRAINT `registrations_ibfk_2` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
