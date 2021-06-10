-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2021 at 04:50 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `coding`
--

-- --------------------------------------------------------

--
-- Table structure for table `answer`
--

CREATE TABLE `answer` (
  `id` int(11) NOT NULL,
  `answer` text NOT NULL,
  `question_id` int(11) NOT NULL,
  `correct` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `answer`
--

INSERT INTO `answer` (`id`, `answer`, `question_id`, `correct`) VALUES
(1, 'is the', 3, 0),
(2, 'best', 3, 1),
(4, 'i', 3, 0),
(5, 'love', 3, 1),
(6, 'him', 3, 0),
(7, 'kuragi', 3, 1),
(8, 'machi', 3, 0),
(9, 'honda', 3, 1),
(10, 'tohru', 3, 0),
(11, 'kuragi', 3, 1),
(12, 'san', 3, 0),
(13, 'qui', 3, 0),
(14, 'ci', 3, 0),
(15, 'sono', 3, 1),
(16, 'le risposte', 3, 0),
(17, 'tout', 9, 0),
(18, 'l\'universe', 9, 0),
(19, 'tout', 3, 1),
(20, 'l', 3, 0),
(21, 'uni', 3, 0),
(22, 'verse', 3, 0),
(23, 'ecco', 9, 0),
(24, 'le', 9, 0),
(25, 'risposte', 9, 1),
(26, 'ci', 9, 0),
(27, 'sono', 9, 0),
(28, 'le risposte', 9, 1),
(29, 'dotter', 9, 1),
(30, 'de', 9, 0),
(31, 'forest', 9, 0),
(32, 'Točno', 11, 0),
(33, 'Netočno', 11, 1),
(34, 'Prvi odgovor', 12, 0),
(35, 'Drugi odgovor', 12, 1),
(36, 'Treći odgovor', 12, 0),
(37, 'Četvrti odgovor', 12, 0),
(38, 'Peti odgovor', 12, 1),
(39, 'Šesti odgovor', 12, 0),
(40, 'Neki odgovor', 13, 1),
(41, 'print(4)', 14, 1);

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

CREATE TABLE `language` (
  `id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `language`
--

INSERT INTO `language` (`id`, `name`, `image`, `description`) VALUES
(1, 'Python', NULL, 'Python je interpreterski programski jezik. Interpreterski programski jezici\nsu jezici kod kojih se izvorni kôd izvršava direktno uz pomoć\ninterpretera, tj. kod ovakvih tipova programskih jezika nema potrebe za\nkompajliranjem prije izvršavanja, tj. prevođenjem u izvršni oblik.\nProgrami pisani u programskom jeziku Python su kraći, a i za njihovo\npisanje utrošak vremena je puno manji. Python programerima dopušta\nnekoliko stilova pisanja programa: strukturno, objektno orijentirano i\naspektno orijentirano programiranje'),
(3, 'C#', NULL, 'C# je objektno orijentirani programski jezik kojeg su razvili Anders Hejlsberg i drugi u tvrtci Microsoft.\nC# je izumljen s ciljem da .NET platforma dobije programski jezik, koji bi maksimalno iskoristio njezine sposobnosti. Sličan je programskim jezicima Java i C++.');

-- --------------------------------------------------------

--
-- Table structure for table `lesson`
--

CREATE TABLE `lesson` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `language_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `lesson`
--

INSERT INTO `lesson` (`id`, `name`, `description`, `language_id`) VALUES
(4, 'this is a lesson', 'this is a desc of the lesson this is a desc of the lesson this is a desc of the lesson this is a desc of the lesson', 1),
(5, 'this is a lesson once again', 'this is a desc of the lesson this is a desc of the lnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnesson this is a', 3),
(6, 'this is a lesson agan', 'this is a desc of the lesson this is a desc of the lessothis is a desc of the lesson this is a desc of the lesson this is a desc of the lesson n', 3),
(7, 'fruits basket', 'gatiru hatori gatiru hatorigatiru hatorigatiru hatorigatiru hatorigatiru hatorigatiru hatorigatiru hatorigatiru hatorigatiru hatorigatiru hatorigatiru hatori', 3),
(8, 'Test lekcija', 'This is actually for real ok. This is actually for real ok. This is actually for real ok. This is actually for real ok.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `question_type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`id`, `question`, `lesson_id`, `question_type`) VALUES
(3, 'sohma yuki', 4, 1),
(6, 'Crimea is ______?', 6, 3),
(9, 'seyo seyo seyo seyo kono pelechki?', 4, 2),
(10, 'iskodiraj mi elif u pythonu', 4, 4),
(11, 'Pitanje sa jednim točnim odgovorom', 8, 1),
(12, 'Pitanje sa više točnih odgovora', 8, 2),
(13, 'Pitanje sa nadopunjavanjem', 8, 3),
(14, 'Pitanje sa kodiranjem', 8, 4);

-- --------------------------------------------------------

--
-- Table structure for table `question_type`
--

CREATE TABLE `question_type` (
  `id` int(11) NOT NULL,
  `type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `question_type`
--

INSERT INTO `question_type` (`id`, `type`) VALUES
(1, 'Jedan točan'),
(2, 'Više točnih'),
(3, 'Nadopunjavanje'),
(4, 'Kodiranje');

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

CREATE TABLE `user_profile` (
  `id` int(11) NOT NULL,
  `username` varchar(15) NOT NULL,
  `password` varchar(60) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `role_code` varchar(3) NOT NULL DEFAULT 'USR'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_profile`
--

INSERT INTO `user_profile` (`id`, `username`, `password`, `image`, `role_code`) VALUES
(5, 'emukei', '$2y$10$kF7ufHrq1nUMkl0WTybL/OnaJw6FyfGUq188ARBTMR6j97t2qemtC', NULL, 'USR');

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `code` varchar(3) NOT NULL,
  `name` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`code`, `name`) VALUES
('AD', 'Admin'),
('MOD', 'Moderator'),
('USR', 'Korisnik');

-- --------------------------------------------------------

--
-- Table structure for table `user_session`
--

CREATE TABLE `user_session` (
  `id` int(11) NOT NULL,
  `session_id` varchar(40) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user_session_language`
--

CREATE TABLE `user_session_language` (
  `user_session_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user_session_lesson`
--

CREATE TABLE `user_session_lesson` (
  `user_session_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user_session_question`
--

CREATE TABLE `user_session_question` (
  `user_session_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answered` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answer`
--
ALTER TABLE `answer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lesson`
--
ALTER TABLE `lesson`
  ADD PRIMARY KEY (`id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lesson_id` (`lesson_id`),
  ADD KEY `question_type` (`question_type`);

--
-- Indexes for table `question_type`
--
ALTER TABLE `question_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_code` (`role_code`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `user_session`
--
ALTER TABLE `user_session`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_session_language`
--
ALTER TABLE `user_session_language`
  ADD KEY `user_session_id` (`user_session_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `user_session_lesson`
--
ALTER TABLE `user_session_lesson`
  ADD KEY `user_session_id` (`user_session_id`),
  ADD KEY `lesson_id` (`lesson_id`);

--
-- Indexes for table `user_session_question`
--
ALTER TABLE `user_session_question`
  ADD KEY `user_session_id` (`user_session_id`),
  ADD KEY `question_id` (`question_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answer`
--
ALTER TABLE `answer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `language`
--
ALTER TABLE `language`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `lesson`
--
ALTER TABLE `lesson`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `question_type`
--
ALTER TABLE `question_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_profile`
--
ALTER TABLE `user_profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_session`
--
ALTER TABLE `user_session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answer`
--
ALTER TABLE `answer`
  ADD CONSTRAINT `answer_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lesson`
--
ALTER TABLE `lesson`
  ADD CONSTRAINT `lesson_ibfk_1` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `question_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `lesson` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `question_ibfk_2` FOREIGN KEY (`question_type`) REFERENCES `question_type` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD CONSTRAINT `user_profile_ibfk_1` FOREIGN KEY (`role_code`) REFERENCES `user_role` (`code`) ON UPDATE CASCADE;

--
-- Constraints for table `user_session`
--
ALTER TABLE `user_session`
  ADD CONSTRAINT `user_session_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_profile` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_session_language`
--
ALTER TABLE `user_session_language`
  ADD CONSTRAINT `user_session_language_ibfk_1` FOREIGN KEY (`user_session_id`) REFERENCES `user_session` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_session_language_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_session_lesson`
--
ALTER TABLE `user_session_lesson`
  ADD CONSTRAINT `user_session_lesson_ibfk_1` FOREIGN KEY (`user_session_id`) REFERENCES `user_session` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_session_lesson_ibfk_2` FOREIGN KEY (`lesson_id`) REFERENCES `lesson` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_session_question`
--
ALTER TABLE `user_session_question`
  ADD CONSTRAINT `user_session_question_ibfk_1` FOREIGN KEY (`user_session_id`) REFERENCES `user_session` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_session_question_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
