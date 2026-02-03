-- Active: 1734375734430@@localhost@3306
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `estacionmeteo25-26`
--
CREATE DATABASE IF NOT EXISTS `estacionmeteo25-26` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `estacionmeteo25-26`;

-- --------------------------------------------------------

--
-- Eliminar tabla si existe para evitar conflictos
--
DROP TABLE IF EXISTS `datos`;

--
-- Estructura de tabla para la tabla `datos`
--

CREATE TABLE `datos` (
  `fechaSistema` DATETIME NOT NULL DEFAULT current_timestamp(),
  `temperatura` decimal(4,2) DEFAULT NULL,
  `presion` decimal(7,2) DEFAULT NULL,
  `humedad` decimal(5,2) DEFAULT NULL,
  `viento` decimal(5,2) DEFAULT NULL,
  `lluvia` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`fechaSistema`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `datos`
--

INSERT INTO `datos` (`fechaSistema`, `temperatura`, `presion`, `humedad`, `viento`, `lluvia`) VALUES
-- DATOS DE AYER (Para historial)
('2026-01-28 08:00:00', 4.50, 1018.00, 85.00, 10.00, 12.00),
('2026-01-28 14:00:00', 12.00, 1016.00, 50.00, 15.00, 11.00),
('2026-01-28 20:00:00', 8.00, 1015.00, 65.00, 5.00, 13.00),

-- DATOS DE HOY (13 de Enero 2026) - Simulando un día completo
('2026-01-29 00:00:00', 3.00, 1015.00, 90.00, 2.00, 15.00),
('2026-01-29 04:00:00', 2.50, 1014.00, 92.00, 5.00, 20.00),
('2026-01-29 08:00:00', 5.00, 1015.00, 88.00, 12.00, 00.00),
('2026-01-29 10:30:00', 9.50, 1016.00, 70.00, 15.00, 10.00),
('2026-01-29 12:00:00', 11.20, 1017.00, 60.00, 18.00, 3.50),
('2026-01-29 14:00:00', 13.50, 1016.00, 55.00, 20.00, 5.50),
('2026-01-29 16:00:00', 14.00, 1015.00, 50.00, 22.00, 6.50),
('2026-01-29 18:00:00', 11.00, 1014.00, 60.00, 15.00, 7.50),
('2026-01-29 20:00:00', 9.00, 1012.00, 75.00, 10.00, 2.50), -- Empieza a llover
('2026-01-29 22:00:00', 7.50, 1010.00, 80.00, 8.00, 5.00),

-- DATOS DE MAÑANA (Para pruebas futuras)
('2026-01-30 08:00:00', 6.00, 1008.00, 90.00, 25.00, 10.00),
('2026-01-30 12:00:00', 10.00, 1005.00, 85.00, 30.00, 15.00);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;