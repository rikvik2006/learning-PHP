-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Gen 23, 2025 alle 04:25
-- Versione del server: 10.4.24-MariaDB
-- Versione PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bussanoriccardo`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `rappresentante`
--

CREATE TABLE `rappresentante` (
  `ID` int(11) NOT NULL,
  `Nome` varchar(50) NOT NULL,
  `Cognome` varchar(50) NOT NULL,
  `UltimoFatturato` int(10) UNSIGNED DEFAULT NULL,
  `Regione` varchar(255) NOT NULL,
  `Provincia` varchar(255) NOT NULL,
  `PercentualeProvvigione` int(10) UNSIGNED NOT NULL CHECK (`PercentualeProvvigione` >= 0 and `PercentualeProvvigione` <= 100)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `rappresentante`
--

INSERT INTO `rappresentante` (`ID`, `Nome`, `Cognome`, `UltimoFatturato`, `Regione`, `Provincia`, `PercentualeProvvigione`) VALUES
(1, 'Chucho', 'Westcott', 7120, 'Lombardia', 'Milano', 43),
(2, 'Desiree', 'Munkton', 5304, 'Toscana', 'Livorno', 78),
(3, 'Julietta', 'Asquith', 5308, 'Toscana', 'Firenze', 55),
(4, 'Cristabel', 'Tieman', 5282, 'Lazio', 'Roma', 83),
(5, 'Alica', 'Terne', 8508, 'Calabria', 'Reggio Calabria', 32),
(6, 'Camey', 'Rapson', 205, 'Veneto', 'Padova', 60),
(7, 'Kerwinn', 'Rupp', 4729, 'Veneto', 'Venezia', 61),
(8, 'Kendre', 'Radmer', 1467, 'Lazio', 'Roma', 86),
(9, 'Adelice', 'Please', NULL, 'Friuli-Venezia Giulia', 'Trieste', 35),
(10, 'Pat', 'Jovasevic', 133, 'Lazio', 'Roma', 32),
(11, 'Chaddie', 'Earl', 8050, 'Veneto', 'Padova', 67),
(12, 'Kali', 'Figgen', 2948, 'Toscana', 'Firenze', 21),
(13, 'Costa', 'Please', NULL, 'Veneto', 'Verona', 30),
(14, 'Cathryn', 'Zanicchi', 1589, 'Veneto', 'Verona', 33),
(15, 'Mar', 'Gruszecki', 877, 'Lombardia', 'Milano', 26),
(16, 'Maire', 'Carrigan', 9381, 'Emilia-Romagna', 'Bologna', 56),
(17, 'Brina', 'Capozzi', 620, 'Lombardia', 'Milano', 80),
(18, 'Grethel', 'Seywood', 4589, 'Emilia-Romagna', 'Bologna', 12),
(19, 'Marley', 'Neilands', 5708, 'Lazio', 'Roma', 53),
(20, 'Jeanie', 'Sisey', 4415, 'Lombardia', 'Milano', 65),
(21, 'Isac', 'Damarell', NULL, 'Veneto', 'Verona', 41),
(22, 'Cordi', 'Sturney', 7002, 'Calabria', 'Reggio Calabria', 42),
(23, 'North', 'Giacobbo', 6624, 'Piemonte', 'Torino', 44),
(24, 'Codi', 'Eller', 9614, 'Lazio', 'Roma', 47),
(25, 'Esther', 'Adey', 2687, 'Lombardia', 'Milano', 24),
(26, 'Stefanie', 'Jarrette', 1587, 'Veneto', 'Venezia', 69),
(27, 'Michaelina', 'Gayton', NULL, 'Veneto', 'Padova', 82),
(28, 'Dannye', 'Fortnam', 4046, 'Lombardia', 'Bergamo', 50),
(29, 'Michel', 'Matyas', 8543, 'Emilia-Romagna', 'Bologna', 55),
(30, 'Darill', 'Ciccoloi', 9200, 'Friuli-Venezia Giulia', 'Trieste', 33),
(31, 'Aguste', 'Pragnell', NULL, 'Emilia-Romagna', 'Bologna', 38),
(32, 'Moshe', 'Autrie', 9232, 'Toscana', 'Firenze', 58),
(33, 'Murvyn', 'Gallatly', 4426, 'Piemonte', 'Torino', 86),
(34, 'Florance', 'Commin', NULL, 'Emilia-Romagna', 'Villanova', 41),
(35, 'Codie', 'Starking', 9766, 'Calabria', 'Reggio Calabria', 37),
(36, 'Adelaide', 'Hornbuckle', 9123, 'Veneto', 'Padova', 63),
(37, 'Janeva', 'Cowtherd', 9639, 'Veneto', 'Venezia', 81),
(38, 'Ferrel', 'Martindale', 5819, 'Piemonte', 'Torino', 90),
(39, 'Milli', 'Vassall', 2676, 'Lombardia', 'Brescia', 67),
(40, 'Vidovik', 'Eschalotte', 279, 'Toscana', 'Livorno', 13),
(41, 'Nata', 'Climpson', NULL, 'Lazio', 'Roma', 65),
(42, 'Sheilah', 'Blabey', NULL, 'Toscana', 'Firenze', 40),
(43, 'Cristian', 'Dugmore', 3679, 'Lazio', 'Roma', 92),
(44, 'Torey', 'Kears', 8197, 'Emilia-Romagna', 'Villanova', 38),
(45, 'Giulietta', 'Asser', 5104, 'Veneto', 'Verona', 45),
(46, 'Paulina', 'Schuchmacher', 3998, 'Lombardia', 'Brescia', 23),
(47, 'Darbie', 'Town', 2414, 'Calabria', 'Reggio Calabria', 50),
(48, 'Leslie', 'Deacock', 9420, 'Lazio', 'Roma', 20),
(49, 'Aindrea', 'D\'Adda', 1837, 'Lazio', 'Roma', 69),
(50, 'Dynah', 'Matskevich', 3026, 'Friuli-Venezia Giulia', 'Trieste', 83),
(51, 'Retha', 'Kembrey', 1346, 'Emilia-Romagna', 'Bologna', 91),
(52, 'Domini', 'Barck', 9360, 'Lombardia', 'Bergamo', 73),
(53, 'Olimpia', 'Brownsmith', 2141, 'Lazio', 'Roma', 46),
(54, 'Waite', 'Slyman', 4360, 'Veneto', 'Venezia', 14),
(55, 'Annabelle', 'Lagadu', 86, 'Veneto', 'Mestre', 50),
(56, 'Carlee', 'Tidder', 8563, 'Piemonte', 'Torino', 24),
(57, 'Dolli', 'Sheridan', 6616, 'Lazio', 'Roma', 73),
(58, 'Andros', 'Casse', 1234, 'Veneto', 'Padova', 18),
(59, 'Freddi', 'Solano', 6070, 'Friuli-Venezia Giulia', 'Trieste', 8),
(60, 'Hans', 'Fulford', 3960, 'Veneto', 'Venezia', 52),
(61, 'Delila', 'De Lacey', 3709, 'Veneto', 'Padova', 19),
(62, 'Boigie', 'Ahren', 4347, 'Friuli-Venezia Giulia', 'Trieste', 42),
(63, 'Courtney', 'Doxsey', 602, 'Lombardia', 'Milano', 88),
(64, 'Erastus', 'Claire', 462, 'Emilia-Romagna', 'Bologna', 49),
(65, 'Karine', 'Posselow', 1290, 'Piemonte', 'Torino', 88),
(66, 'Shelden', 'O\'Bradden', 8942, 'Emilia-Romagna', 'Villanova', 53),
(67, 'Dorotea', 'Machel', 2719, 'Veneto', 'Verona', 26),
(68, 'Brice', 'Tourville', 6603, 'Veneto', 'Padova', 41),
(69, 'Brynna', 'Cunradi', NULL, 'Friuli-Venezia Giulia', 'Trieste', 54),
(70, 'Hans', 'Rawling', 1906, 'Lazio', 'Roma', 30),
(71, 'Hadria', 'Baike', NULL, 'Friuli-Venezia Giulia', 'Trieste', 41),
(72, 'Forester', 'Baigent', 2513, 'Toscana', 'Firenze', 99),
(73, 'Paolo', 'Reignolds', NULL, 'Lazio', 'Roma', 96),
(74, 'Fowler', 'Mulrenan', 8131, 'Lazio', 'Roma', 74),
(75, 'Maddie', 'Alliban', 7261, 'Veneto', 'Padova', 83),
(76, 'Bambie', 'Ambrogini', 5143, 'Lombardia', 'Milano', 68),
(77, 'Beret', 'Grent', 1721, 'Toscana', 'Firenze', 16),
(78, 'Paco', 'Devita', 8356, 'Lazio', 'Roma', 33),
(79, 'Vincents', 'Lownes', 1687, 'Toscana', 'Livorno', 21),
(80, 'Farr', 'Konig', NULL, 'Veneto', 'Mestre', 47),
(81, 'Byram', 'Ayrs', 2914, 'Lombardia', 'Milano', 23),
(82, 'Morissa', 'Casiero', 4137, 'Lazio', 'Roma', 99),
(83, 'Bernice', 'Pratten', 3759, 'Friuli-Venezia Giulia', 'Trieste', 39),
(84, 'Del', 'Sings', 2926, 'Emilia-Romagna', 'Villanova', 74),
(85, 'Anastasia', 'Giovannoni', 5520, 'Lombardia', 'Bergamo', 73),
(86, 'Lindsy', 'Novakovic', 9618, 'Friuli-Venezia Giulia', 'Trieste', 53),
(87, 'Fredelia', 'Giacopelo', 9938, 'Lombardia', 'Milano', 84),
(88, 'Timothy', 'Tomlin', 6802, 'Lombardia', 'Milano', 25),
(89, 'Roana', 'McDuall', 3833, 'Lombardia', 'Milano', 72),
(90, 'Shayne', 'Franceschino', NULL, 'Piemonte', 'Torino', 14),
(91, 'Lynda', 'Atkinson', 3877, 'Piemonte', 'Torino', 74),
(92, 'Marietta', 'Maslen', 454, 'Lombardia', 'Milano', 93),
(93, 'Rolfe', 'Vice', 3753, 'Toscana', 'Firenze', 91),
(94, 'Andria', 'Martinez', 4708, 'Toscana', 'Firenze', 85),
(95, 'Horacio', 'Karlolczak', 7038, 'Veneto', 'Mestre', 26),
(96, 'Rycca', 'Reschke', NULL, 'Toscana', 'Firenze', 88),
(97, 'Theo', 'Loomis', 765, 'Piemonte', 'Torino', 22),
(98, 'Klaus', 'Batho', 5845, 'Lazio', 'Roma', 30),
(99, 'Rhonda', 'Crich', 4859, 'Veneto', 'Padova', 70),
(100, 'Gabriella', 'MacCague', 4032, 'Lombardia', 'Milano', 100),
(101, 'Giovanni', 'Lattuga', 100000, 'Piemonte', 'Torino', 98),
(102, 'Giovanni', 'Lattuga', 10, 'Calabria', 'Salerno', 1),
(108, 'Riccardo', 'Bussano', 4294967295, 'Piemonte', 'Torino', 100),
(110, 'Marco', 'Php', 1500, 'Basilicata', 'Cagliari', 100),
(111, 'Nono', 'Ottavo', 98989, 'Emilia Romagna', 'Bologna', 70);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `rappresentante`
--
ALTER TABLE `rappresentante`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `rappresentante`
--
ALTER TABLE `rappresentante`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
