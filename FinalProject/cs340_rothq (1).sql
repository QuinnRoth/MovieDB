-- phpMyAdmin SQL Dump
-- version 5.2.1-1.el7.remi
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 10, 2024 at 05:21 PM
-- Server version: 10.6.19-MariaDB-log
-- PHP Version: 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cs340_rothq`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`cs340_rothq`@`%` PROCEDURE `AddMovie` (IN `movieTitle` VARCHAR(100), IN `movieGenre` VARCHAR(20), IN `movieReleaseYear` YEAR(4), IN `isAvailable` TINYINT(1))   BEGIN
    INSERT INTO Movies (title, genre, release_year, availability)
    VALUES (movieTitle, movieGenre, movieReleaseYear, isAvailable);
END$$

--
-- Functions
--
CREATE DEFINER=`cs340_rothq`@`%` FUNCTION `GetAverageMovieAge` () RETURNS DECIMAL(5,2) DETERMINISTIC BEGIN
    DECLARE avgAge DECIMAL(5, 2);

    SELECT AVG(YEAR(CURRENT_DATE) - release_year) INTO avgAge
    FROM Movies;

    RETURN avgAge;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Actors`
--

CREATE TABLE `Actors` (
  `actor_id` int(4) NOT NULL,
  `name` varchar(20) NOT NULL,
  `sex` varchar(10) NOT NULL,
  `birthdate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `Actors`
--

INSERT INTO `Actors` (`actor_id`, `name`, `sex`, `birthdate`) VALUES
(1, 'John Smith', 'Male', '1983-06-08'),
(2, 'Jane Blue', 'Female', '1994-11-08'),
(3, 'Andrew Goodman', 'Male', '1989-06-13'),
(4, 'Megan Tan', 'Female', '1976-02-23'),
(5, 'Greg Terner', 'Male', '2000-07-04'),
(6, 'Charles Portman', 'Male', '1960-11-20'),
(7, 'Michael Moor', 'Male', '1999-04-22'),
(8, 'Tim Harding', 'Male', '1989-09-12'),
(9, 'Maxine Prance', 'Female', '1998-11-20'),
(10, 'Sophia Johnson', 'Female', '2004-08-01');

-- --------------------------------------------------------

--
-- Table structure for table `Acts in`
--

CREATE TABLE `Acts in` (
  `actor_id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `Acts in`
--

INSERT INTO `Acts in` (`actor_id`, `movie_id`) VALUES
(1, 1),
(1, 3),
(2, 2),
(3, 3),
(3, 4),
(4, 4),
(5, 5),
(5, 7),
(6, 6),
(6, 8),
(7, 1),
(7, 7),
(8, 8),
(9, 9),
(9, 10),
(10, 10);

-- --------------------------------------------------------

--
-- Table structure for table `Director`
--

CREATE TABLE `Director` (
  `director_id` int(4) NOT NULL,
  `name` varchar(20) NOT NULL,
  `sex` varchar(20) NOT NULL,
  `birthdate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `Director`
--

INSERT INTO `Director` (`director_id`, `name`, `sex`, `birthdate`) VALUES
(1, 'William Hop', 'Male', '1973-02-28'),
(2, 'Mary Marter', 'Female', '1959-03-07'),
(3, 'John Doe', 'Male', '1980-06-15'),
(4, 'Jane Smith', 'Female', '1975-08-22'),
(5, 'Alex Johnson', 'Non-binary', '1990-11-03'),
(6, 'Chris Lee', 'Male', '1985-09-12'),
(7, 'Patricia Brown', 'Female', '1967-01-19'),
(8, 'Michael Green', 'Male', '1978-04-05'),
(9, 'Sophia White', 'Female', '1983-12-14'),
(10, 'David Black', 'Male', '1995-07-20');

-- --------------------------------------------------------

--
-- Table structure for table `Directs`
--

CREATE TABLE `Directs` (
  `director_id` int(4) NOT NULL,
  `movie_id` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `Directs`
--

INSERT INTO `Directs` (`director_id`, `movie_id`) VALUES
(1, 1),
(1, 5),
(2, 2),
(3, 3),
(4, 4),
(5, 6),
(6, 7),
(7, 8),
(8, 9),
(9, 10),
(10, 3),
(10, 10);

-- --------------------------------------------------------

--
-- Table structure for table `Movies`
--

CREATE TABLE `Movies` (
  `movie_id` int(4) NOT NULL,
  `title` varchar(100) NOT NULL,
  `genre` varchar(20) NOT NULL,
  `release_year` year(4) NOT NULL,
  `availability` tinyint(1) NOT NULL,
  `rent_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `renter_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `Movies`
--

INSERT INTO `Movies` (`movie_id`, `title`, `genre`, `release_year`, `availability`, `rent_date`, `return_date`, `renter_id`) VALUES
(1, 'Mystery of the Old Clock', 'Mystery', '2015', 1, NULL, NULL, NULL),
(2, 'Love in the Time of Robots', 'Romance', '2020', 0, '2024-11-20', '2024-12-01', 1),
(3, 'The Last Frontier', 'Western', '2018', 1, NULL, NULL, NULL),
(4, 'Space Invaders', 'Sci-Fi', '2023', 0, '2024-08-15', '2024-08-22', 2),
(5, 'Journey to the Unknown', 'Adventure', '2019', 1, NULL, NULL, NULL),
(6, 'Under the Ocean', 'Documentary', '2021', 1, NULL, NULL, NULL),
(7, 'Haunted House', 'Horror', '2016', 0, '2024-10-12', '2024-10-19', 3),
(8, 'The Chef\'s Special', 'Drama', '2017', 1, NULL, NULL, NULL),
(9, 'Faster Than Light', 'Action', '2022', 0, '2024-04-01', '2024-04-08', 4),
(10, 'Laughter in the Rain', 'Comedy', '2014', 1, NULL, NULL, NULL),
(11, 'test movie', 'test genre', '2024', 1, '2024-12-10', '2024-12-10', 8),
(12, 'Intersteller', 'Adventure', '1995', 0, '2024-12-10', '2024-12-13', NULL),
(13, 'test two', 'test', '0000', 0, '2024-12-26', '2024-12-21', 3);

--
-- Triggers `Movies`
--
DELIMITER $$
CREATE TRIGGER `update_movie_availability` BEFORE UPDATE ON `Movies` FOR EACH ROW BEGIN
    IF NEW.rent_date IS NOT NULL AND NEW.return_date IS NULL THEN
        SET NEW.availability = 0; -- Movie is unavailable when rented
    ELSEIF NEW.return_date IS NOT NULL THEN
        SET NEW.availability = 1; -- Movie is available when returned
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Renter`
--

CREATE TABLE `Renter` (
  `renter_id` int(4) NOT NULL,
  `name` varchar(20) NOT NULL,
  `movie_id` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `Renter`
--

INSERT INTO `Renter` (`renter_id`, `name`, `movie_id`) VALUES
(1, 'Maximus Finch', 2),
(2, 'Luna Starling', 4),
(3, 'Orion Blaze', 7),
(4, 'Zara Moon', 9),
(5, 'Felix Aurora', NULL),
(6, 'Juno Frost', NULL),
(7, 'Sage Whisper', NULL),
(8, 'Cassian Wave', NULL),
(9, 'Nova Quill', NULL),
(10, 'Aria Silver', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Actors`
--
ALTER TABLE `Actors`
  ADD PRIMARY KEY (`actor_id`);

--
-- Indexes for table `Acts in`
--
ALTER TABLE `Acts in`
  ADD PRIMARY KEY (`actor_id`,`movie_id`),
  ADD KEY `movie_id` (`movie_id`);

--
-- Indexes for table `Director`
--
ALTER TABLE `Director`
  ADD PRIMARY KEY (`director_id`);

--
-- Indexes for table `Directs`
--
ALTER TABLE `Directs`
  ADD PRIMARY KEY (`director_id`,`movie_id`),
  ADD KEY `movie_id` (`movie_id`);

--
-- Indexes for table `Movies`
--
ALTER TABLE `Movies`
  ADD PRIMARY KEY (`movie_id`),
  ADD KEY `renter_id` (`renter_id`);

--
-- Indexes for table `Renter`
--
ALTER TABLE `Renter`
  ADD PRIMARY KEY (`renter_id`),
  ADD KEY `movie_id` (`movie_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Acts in`
--
ALTER TABLE `Acts in`
  ADD CONSTRAINT `Acts in_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `Movies` (`movie_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Acts in_ibfk_2` FOREIGN KEY (`actor_id`) REFERENCES `Actors` (`actor_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Directs`
--
ALTER TABLE `Directs`
  ADD CONSTRAINT `Directs_ibfk_1` FOREIGN KEY (`director_id`) REFERENCES `Director` (`director_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Directs_ibfk_2` FOREIGN KEY (`movie_id`) REFERENCES `Movies` (`movie_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Movies`
--
ALTER TABLE `Movies`
  ADD CONSTRAINT `Movies_ibfk_1` FOREIGN KEY (`renter_id`) REFERENCES `Renter` (`renter_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `Renter`
--
ALTER TABLE `Renter`
  ADD CONSTRAINT `Renter_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `Movies` (`movie_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
