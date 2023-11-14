-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-11-2023 a las 12:59:13
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `concesionario`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `DNI` varchar(20) NOT NULL,
  `Nombre` varchar(20) DEFAULT NULL,
  `Apellidos` varchar(20) DEFAULT NULL,
  `Domicilio` varchar(20) DEFAULT NULL,
  `FechaNac` date DEFAULT NULL,
  `VIN_coches` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`DNI`, `Nombre`, `Apellidos`, `Domicilio`, `FechaNac`, `VIN_coches`) VALUES
('05245677L', 'Rodrigo', 'Pérez', 'Calle Fernandez De l', '2000-04-11', '23456GFDB'),
('12304964Y', 'Alejandro', 'Sánchez', 'Calle Sol, 8', '2002-08-19', '23456YHUS');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coches`
--

CREATE TABLE `coches` (
  `VIN` varchar(20) NOT NULL,
  `Matricula` varchar(20) DEFAULT NULL,
  `Marca` varchar(20) DEFAULT NULL,
  `Modelo` varchar(20) DEFAULT NULL,
  `Ano` varchar(20) DEFAULT NULL,
  `Precio` int(11) DEFAULT NULL,
  `Km` int(11) DEFAULT NULL,
  `DNI_vendedores` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `coches`
--

INSERT INTO `coches` (`VIN`, `Matricula`, `Marca`, `Modelo`, `Ano`, `Precio`, `Km`, `DNI_vendedores`) VALUES
('23456GFDB', '3467LKF', 'Ford', 'Fiesta', '2007', 2500, 100000, '06293364H'),
('23456YHUS', '0493HGS', 'Ferrari', 'Roma', '2017', 200500, 80000, '03245754K');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vendedores`
--

CREATE TABLE `vendedores` (
  `DNI` varchar(20) NOT NULL,
  `Nombre` varchar(20) DEFAULT NULL,
  `Apellidos` varchar(20) DEFAULT NULL,
  `FechaAlta` date DEFAULT NULL,
  `FechaNac` date DEFAULT NULL,
  `Rol` varchar(20) DEFAULT NULL,
  `contrasena` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vendedores`
--

INSERT INTO `vendedores` (`DNI`, `Nombre`, `Apellidos`, `FechaAlta`, `FechaNac`, `Rol`, `contrasena`) VALUES
('03245754K', 'Victor', 'Valdes', '2023-11-11', '2001-03-13', 'admin', '29bb72f3aa2d13f4c0da08cda282f6dce2edf9ef58e800123effc5666059351b'),
('06293364H', 'Javier', 'Diaz', '2023-11-13', '2004-10-01', 'junior', '52f87a36d63aaaeb8e413bd8498b3d8d7918af494b20ded56c16cc03e8eb27e7');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`DNI`),
  ADD KEY `fk_VIN_coches` (`VIN_coches`);

--
-- Indices de la tabla `coches`
--
ALTER TABLE `coches`
  ADD PRIMARY KEY (`VIN`),
  ADD KEY `fk_DNI_vendedores` (`DNI_vendedores`);

--
-- Indices de la tabla `vendedores`
--
ALTER TABLE `vendedores`
  ADD PRIMARY KEY (`DNI`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD CONSTRAINT `fk_VIN_coches` FOREIGN KEY (`VIN_coches`) REFERENCES `coches` (`VIN`);

--
-- Filtros para la tabla `coches`
--
ALTER TABLE `coches`
  ADD CONSTRAINT `fk_DNI_vendedores` FOREIGN KEY (`DNI_vendedores`) REFERENCES `vendedores` (`DNI`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
