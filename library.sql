-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 31 mars 2023 à 07:22
-- Version du serveur : 8.0.31
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `library`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `FullName` varchar(100) DEFAULT NULL,
  `AdminEmail` varchar(120) DEFAULT NULL,
  `UserName` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `updationDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`id`, `FullName`, `AdminEmail`, `UserName`, `Password`, `updationDate`) VALUES
(1, 'Administrateur', 'admin@gmail.com', 'admin', '$2y$10$eC66yzsKBzwVk6RBqmgFyOaja93dMgeW6b/jmWfoPhyy6HOanbzJW', '2023-03-29 14:44:03');

-- --------------------------------------------------------

--
-- Structure de la table `tblauthors`
--

DROP TABLE IF EXISTS `tblauthors`;
CREATE TABLE IF NOT EXISTS `tblauthors` (
  `id` int NOT NULL AUTO_INCREMENT,
  `AuthorName` varchar(159) DEFAULT NULL,
  `creationDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tblauthors`
--

INSERT INTO `tblauthors` (`id`, `AuthorName`, `creationDate`, `UpdationDate`) VALUES
(1, 'Guillaume Musso', '2017-07-08 12:49:09', '2023-03-29 12:33:06'),
(2, 'Michel Bussi', '2017-07-08 14:30:23', '2023-03-29 12:33:36'),
(3, 'Marc Levy', '2017-07-08 14:35:08', '2023-03-29 12:33:38'),
(4, 'Françoise Bourdin', '2017-07-08 14:35:21', '2023-03-29 12:38:30'),
(5, 'Gilles Legardinier', '2017-07-08 14:35:36', '2023-03-29 12:34:17'),
(9, 'Agnès Martin', '2017-07-08 15:22:03', '2021-07-23 08:44:50'),
(10, 'Annie Ernaux', '2021-06-23 12:39:10', '2021-07-23 08:46:20');

-- --------------------------------------------------------

--
-- Structure de la table `tblbooks`
--

DROP TABLE IF EXISTS `tblbooks`;
CREATE TABLE IF NOT EXISTS `tblbooks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `BookName` varchar(255) DEFAULT NULL,
  `CatId` int DEFAULT NULL,
  `AuthorId` int DEFAULT NULL,
  `ISBNNumber` int DEFAULT NULL,
  `BookPrice` int DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tblbooks`
--

INSERT INTO `tblbooks` (`id`, `BookName`, `CatId`, `AuthorId`, `ISBNNumber`, `BookPrice`, `RegDate`, `UpdationDate`) VALUES
(1, 'La jeune fille de la nuit', 6, 2, 222333, 21, '2023-03-21 10:16:17', '2023-03-31 07:06:29'),
(2, 'Quelqu\'un de bien', 7, 4, 111123, 6, '2023-03-21 10:17:08', NULL),
(7, 'Livre', 6, 3, 777888, 30, '2023-03-27 09:59:05', NULL),
(8, 'Livre', 6, 1, 555444, 30, '2023-03-27 13:06:07', '2023-03-27 13:19:44');

-- --------------------------------------------------------

--
-- Structure de la table `tblcategory`
--

DROP TABLE IF EXISTS `tblcategory`;
CREATE TABLE IF NOT EXISTS `tblcategory` (
  `id` int NOT NULL AUTO_INCREMENT,
  `CategoryName` varchar(150) DEFAULT NULL,
  `Status` int DEFAULT NULL,
  `CreationDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdationDate` timestamp NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tblcategory`
--

INSERT INTO `tblcategory` (`id`, `CategoryName`, `Status`, `CreationDate`, `UpdationDate`) VALUES
(5, 'Techno', 1, '2017-07-04 18:35:39', '2023-03-27 08:57:49'),
(6, 'Science', 1, '2017-07-04 18:35:55', '2023-03-21 07:36:38'),
(7, 'Management', 1, '2017-07-04 18:36:16', '2023-03-24 15:11:50'),
(26, 'Menuiserie', 1, '2023-03-27 08:53:00', '2023-03-30 06:52:30'),
(29, 'Menuiserie', 0, '2023-03-30 06:40:15', '2023-03-30 11:05:50'),
(30, 'Math', 1, '2023-03-30 07:10:12', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `tblissuedbookdetails`
--

DROP TABLE IF EXISTS `tblissuedbookdetails`;
CREATE TABLE IF NOT EXISTS `tblissuedbookdetails` (
  `id` int NOT NULL AUTO_INCREMENT,
  `BookId` int DEFAULT NULL,
  `ReaderID` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `IssuesDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ReturnDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `ReturnStatus` int DEFAULT NULL,
  `fine` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tblissuedbookdetails`
--

INSERT INTO `tblissuedbookdetails` (`id`, `BookId`, `ReaderID`, `IssuesDate`, `ReturnDate`, `ReturnStatus`, `fine`) VALUES
(1, 222333, 'SID002', '2017-07-15 06:09:47', '2023-03-01 14:20:26', 1, 0),
(2, 222333, 'SID002', '2017-07-15 06:12:27', '2023-03-01 14:20:37', 1, 5),
(3, 111123, 'SID002', '2017-07-15 06:13:40', NULL, 0, NULL),
(4, 111123, 'SID002', '2017-07-15 06:23:23', '2023-03-01 14:21:04', 1, 2),
(5, 1, 'SID010', '2017-07-15 10:59:26', NULL, 0, NULL),
(6, 3, 'SID011', '2017-07-15 18:02:55', NULL, 0, NULL),
(7, 1, 'SID011', '2021-07-16 13:59:23', NULL, 0, NULL),
(8, 1, 'SID010', '2021-07-20 08:41:34', NULL, 0, NULL),
(9, 3, 'SID012', '2021-07-20 08:44:53', NULL, 0, NULL),
(10, 1, 'SID012', '2021-07-20 08:47:07', NULL, 0, NULL),
(11, 222333, 'SID009', '2021-07-20 08:51:15', NULL, 0, NULL),
(12, 222333, 'SID009', '2021-07-20 09:53:27', NULL, 0, NULL),
(13, 222333, 'SID014', '2021-07-21 14:49:46', '2021-07-21 22:00:00', 1, NULL),
(14, 222333, 'SID017', '2021-07-29 14:14:15', '2021-08-04 22:00:00', 1, NULL),
(15, 222333, 'SID022', '2021-07-30 07:40:06', NULL, 0, NULL),
(16, 222333, 'SID001', '2021-08-06 15:20:20', NULL, 0, NULL),
(17, 222333, 'SID021', '2021-08-06 15:22:22', NULL, 0, NULL),
(18, 222333, 'SID002', '2023-03-22 13:17:01', '2023-03-22 15:29:53', 1, NULL),
(19, 555444, 'SID020', '2023-03-30 12:40:56', NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `tblreaders`
--

DROP TABLE IF EXISTS `tblreaders`;
CREATE TABLE IF NOT EXISTS `tblreaders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ReaderId` varchar(100) DEFAULT NULL,
  `FullName` varchar(120) DEFAULT NULL,
  `EmailId` varchar(120) DEFAULT NULL,
  `MobileNumber` char(11) DEFAULT NULL,
  `Password` varchar(120) DEFAULT NULL,
  `Status` int DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `tblreaders`
--

INSERT INTO `tblreaders` (`id`, `ReaderId`, `FullName`, `EmailId`, `MobileNumber`, `Password`, `Status`, `RegDate`, `UpdateDate`) VALUES
(11, 'SID020', 'test', 'test@gmail.com', '06060606', '$2y$10$fIgZ28bO4RCAr44wkDC2ReMCeciLqS6c6S52TcpmvmG05Ndw.pt4K', 1, '2023-02-24 08:29:33', NULL),
(28, 'SID002', 'Briard Airald', 'airaldb.73@gmail.com', '0650023762', '$2y$10$hZPHTxGQPtzlqd9WYF78puNNp8nO9sS7aB6VQXpIYH4QoD/oZwrEa', 0, '2023-02-28 09:03:43', '2023-03-22 19:34:54'),
(32, 'SID009', 'john doe', 'airald.73@orange.fr', '0785896030', '$2y$10$/YMBrj5GIMWJbIpxT9.gnuyUA7v6HiFlYxqDa8P4.cGM1MFzuaDNO', 2, '2023-03-22 13:55:50', '2023-03-22 19:34:52');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
