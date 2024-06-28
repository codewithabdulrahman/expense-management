-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 28, 2024 at 02:00 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `expense_management_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `EMPLOYEE_ID` int(22) NOT NULL,
  `USER_ID` int(22) NOT NULL,
  `EMPLOYEE_NAME` text NOT NULL,
  `EMPLOYEE_EMAIL` text NOT NULL,
  `EMPLOYEE_PICTURE` blob NOT NULL,
  `EMPLOYEE_CITY` text NOT NULL,
  `EMPLOYEE_POSITION` text NOT NULL,
  `EMPLOYEE_GENDER` text NOT NULL,
  `EMPLOYEE_NUMBER` text NOT NULL,
  `EMPLOYEE_ADRESS` text NOT NULL,
  `EMPLOYEE_SALARY` text NOT NULL,
  `EMPLOYEE_PROBATION_START` date NOT NULL,
  `EMPLOYEE_PROBATION_END` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `EXPENSES_ID` int(11) NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `EXPENSE_TITLE` text NOT NULL,
  `EXPENSE_DESCRIPTION` text NOT NULL,
  `EXPENSE_AMOUNT` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`EXPENSES_ID`, `USER_ID`, `EXPENSE_TITLE`, `EXPENSE_DESCRIPTION`, `EXPENSE_AMOUNT`) VALUES
(9, 0, 'Rem commodi eius qui', 'At quia veritatis no', '9'),
(15, 0, 'ok', 'Exercitationem expli', '788898'),
(17, 0, 'Et ab itaque ut eum ', 'Voluptas id velit qu', '31');

-- --------------------------------------------------------

--
-- Table structure for table `income`
--

CREATE TABLE `income` (
  `INCOME_ID` int(11) NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `INCOME_SOURCE` text NOT NULL,
  `TOTAL_AMOUNT` text NOT NULL,
  `DATE_RECEIVED` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `income`
--

INSERT INTO `income` (`INCOME_ID`, `USER_ID`, `INCOME_SOURCE`, `TOTAL_AMOUNT`, `DATE_RECEIVED`) VALUES
(22, 0, '716', '20', '1971-12-19'),
(24, 0, 'free lancing ', '20003', '1971-12-03'),
(28, 0, '239', '80', '1982-03-07'),
(29, 0, 'through fiveer project', '1235553', '2024-06-13');

-- --------------------------------------------------------

--
-- Table structure for table `leaves`
--

CREATE TABLE `leaves` (
  `LEAVE_ID` int(22) NOT NULL,
  `EMPLOYEE_ID` int(11) NOT NULL,
  `LEAVE_TITLE` text NOT NULL,
  `LEAVE_DESCRIPTION` text NOT NULL,
  `NUMBER_OF_DAYS` text NOT NULL,
  `USER_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leaves`
--

INSERT INTO `leaves` (`LEAVE_ID`, `EMPLOYEE_ID`, `LEAVE_TITLE`, `LEAVE_DESCRIPTION`, `NUMBER_OF_DAYS`, `USER_ID`) VALUES
(26, 121, 'Iure nulla excepturi', 'Mollitia officia vol', '3', 0),
(27, 121, 'Iure nulla excepturi', 'Mollitia officia vol', '3', 0);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `email` text NOT NULL,
  `token` int(11) NOT NULL,
  `expires` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `user_id`, `email`, `token`, `expires`) VALUES
(19, 26, '', 90, 2024),
(22, 26, '', 1231, 2024),
(23, 26, '', 90000, 2024),
(24, 26, '', 86, 2024),
(25, 26, '', 6306467, 2024),
(29, 26, '', 328, 2024);

-- --------------------------------------------------------

--
-- Table structure for table `probation`
--

CREATE TABLE `probation` (
  `PROBATION_ID` int(11) NOT NULL,
  `EMPLOYEE_ID` int(11) NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `START_DATE` date NOT NULL,
  `END_DATE` date NOT NULL,
  `RESULT` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `probation`
--

INSERT INTO `probation` (`PROBATION_ID`, `EMPLOYEE_ID`, `USER_ID`, `START_DATE`, `END_DATE`, `RESULT`) VALUES
(29, 119, 0, '1995-10-02', '1984-10-19', 'Lorem totam minim Na'),
(30, 121, 0, '2009-08-04', '1988-05-28', 'Obcaecati assumenda ');

-- --------------------------------------------------------

--
-- Table structure for table `salary`
--

CREATE TABLE `salary` (
  `SALARY_ID` int(22) NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `EMPLOYEE_ID` int(11) NOT NULL,
  `PAY_DATE` date NOT NULL,
  `SALARY_AMOUNT` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `salary`
--

INSERT INTO `salary` (`SALARY_ID`, `USER_ID`, `EMPLOYEE_ID`, `PAY_DATE`, `SALARY_AMOUNT`) VALUES
(42, 0, 119, '2001-09-14', '12'),
(43, 0, 121, '1988-01-03', '50'),
(44, 0, 122, '2024-06-13', '12345');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `USER_ID` int(22) NOT NULL,
  `NAME` text NOT NULL,
  `EMAIL` text NOT NULL,
  `PASSWORD` text NOT NULL,
  `ROLE` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`USER_ID`, `NAME`, `EMAIL`, `PASSWORD`, `ROLE`) VALUES
(16, 'Ariel Flowers', 'qofivoma@mailinator.com', 'Pa$$w0rd!', 'In esse non laudanti'),
(17, 'Jermaine Mcintyre', 'xyzegym@mailinator.com', 'Pa$$w0rd!', 'Consequatur quae in'),
(18, 'Holmes Spears', 'soti@mailinator.com', 'Pa$$w0rd!', 'Enim sunt ipsum vo'),
(19, 'adnan', 'fegoji@mailinator.com', 'Pa$$w0rd!', 'Odit quibusdam ab si'),
(20, 'Pamela Roman', 'jepowoj@mailinator.com', 'Pa$$w0rd!', 'Odit laudantium quo'),
(21, 'Melvin Copeland', 'fiveruku@mailinator.com', '12', 'Blanditiis eum irure'),
(22, 'Bruno Mcmahon', 'waxejid@mailinator.com', 'asddas', 'Iste ipsum enim ali'),
(23, 'Germane Mooney', 'fovepo@mailinator.com', '$2y$10$VKsyPyNw/9H/zehg4GtynOumlAPXfXJWF/ojZffQ/7KnsyI2wcpVO', 'Quaerat et qui molli'),
(24, 'Sybil Barton', 'syvihozyj@mailinator.com', '$2y$10$Ievn08ztuvHpXetEKM8Wve66tMqHrpJ4wOiBUzDAsx2sH2GOdTz/S', 'Autem eos impedit '),
(25, 'Denise Blackwell', 'bipik@mailinator.com', '$2y$10$0snhySVBRPqtJCVsVZhgeOc9ZP5Jpbp4NoNd1XK2b7jB6MhWYpFbi', 'A atque possimus an'),
(26, 'adii', 'itsadii995@gmail.com', '$2y$10$v/JLMGl6JpoapwiK2WtK3uzDsbP/mtmmZiex4Q.LQiLDRbihaYFPK', 'dev'),
(27, 'Kim Nunez', 'vugufe@mailinator.com', '$2y$10$OozT91XxyPvW0ke1UucTe..rTIDNwQAqBFTcCGOpDmmJA15OnmDUO', 'Distinctio Consequa'),
(28, 'Sydnee Ortega', 'kojupov@mailinator.com', '$2y$10$Z2fbAlmGcQRSDeNtTgERZ.I1aoCZD/c9.C7YqmjQMvVmcDzxxdhwS', 'Est praesentium sus'),
(29, 'Mason Hogan', 'admin@gmail.com', '$2y$10$sE4G7D9Ammcv.JOgNoyh/.ng8cnVhixySuwwAwYf07s.7br6zIMyy', 'Non nobis omnis et N'),
(30, 'Ebony Blevins', 'canef@mailinator.com', '$2y$10$pMJSa7lZE4ZwmM7ejf7leue5XpldjzqZWqKDia0tFCAvEjGk5gcK6', 'Laborum Cumque exce'),
(31, 'Felix Hayden', 'pobo@mailinator.com', '$2y$10$qbfAq69KqBgZyg6nbEQdSu5joZFXVx9ulEgusdKlWEyDlGjFPi9/m', 'Provident enim quia'),
(32, 'Ahmed', 'itsadii95@gmail.com', '$2y$10$CNrB./a.dBUAAM8JZsdZ.uJyK4nVXxvsdqEIh80xzDCPVTmwjv.6C', 'Admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`EMPLOYEE_ID`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`EXPENSES_ID`);

--
-- Indexes for table `income`
--
ALTER TABLE `income`
  ADD PRIMARY KEY (`INCOME_ID`);

--
-- Indexes for table `leaves`
--
ALTER TABLE `leaves`
  ADD PRIMARY KEY (`LEAVE_ID`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `probation`
--
ALTER TABLE `probation`
  ADD PRIMARY KEY (`PROBATION_ID`);

--
-- Indexes for table `salary`
--
ALTER TABLE `salary`
  ADD PRIMARY KEY (`SALARY_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`USER_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `EMPLOYEE_ID` int(22) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `EXPENSES_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `income`
--
ALTER TABLE `income`
  MODIFY `INCOME_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `leaves`
--
ALTER TABLE `leaves`
  MODIFY `LEAVE_ID` int(22) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `probation`
--
ALTER TABLE `probation`
  MODIFY `PROBATION_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `salary`
--
ALTER TABLE `salary`
  MODIFY `SALARY_ID` int(22) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `USER_ID` int(22) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
