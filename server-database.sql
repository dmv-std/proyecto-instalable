-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 22-04-2020 a las 19:24:20
-- Versión del servidor: 10.3.16-MariaDB
-- Versión de PHP: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos:
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `licencias`
--

CREATE TABLE `licencias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `apellido` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `serial` varchar(35) CHARACTER SET utf8 NOT NULL,
  `sitio` varchar(100) CHARACTER SET utf8 NOT NULL,
  `fecha_creacion` date NOT NULL,
  `fecha_expiracion` date NOT NULL,
  `activa` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sist_usersloginacceso`
--

CREATE TABLE `sist_usersloginacceso` (
  `id_usersLoginAcceso` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `fecha` datetime NOT NULL,
  `acceso` tinyint(1) NOT NULL,
  `ip` varchar(30) NOT NULL,
  `hostname` varchar(300) NOT NULL,
  `city` varchar(500) NOT NULL,
  `region` varchar(500) NOT NULL,
  `country` varchar(10) NOT NULL,
  `loc` varchar(100) NOT NULL,
  `org` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sist_usuarios`
--

CREATE TABLE `sist_usuarios` (
  `id` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `apellido` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `pass` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `telefono` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `permisos` varchar(150) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `idCal` int(4) NOT NULL,
  `rolCal` int(1) NOT NULL,
  `rolPag` int(1) NOT NULL,
  `rolPro` int(1) NOT NULL,
  `calendario` int(11) NOT NULL DEFAULT 1,
  `chat` int(11) NOT NULL DEFAULT 1,
  `cotizador` int(11) NOT NULL DEFAULT 1,
  `crm` int(11) NOT NULL DEFAULT 1,
  `formularios` int(11) NOT NULL DEFAULT 1,
  `listas` int(11) NOT NULL DEFAULT 1,
  `outs` int(11) NOT NULL DEFAULT 1,
  `pagos` int(11) NOT NULL DEFAULT 1,
  `produccion` int(11) NOT NULL DEFAULT 1,
  `respuestas` int(11) NOT NULL DEFAULT 1,
  `stock` int(11) NOT NULL DEFAULT 1,
  `certificados` int(11) NOT NULL DEFAULT 1,
  `cotizador2` int(11) NOT NULL DEFAULT 1,
  `rrhh` int(11) NOT NULL DEFAULT 1,
  `firma` varchar(80) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `sist_usuarios`
--

INSERT INTO `sist_usuarios` (`id`, `id_empleado`, `nombre`, `apellido`, `user`, `pass`, `email`, `telefono`, `permisos`, `idCal`, `rolCal`, `rolPag`, `rolPro`, `calendario`, `chat`, `cotizador`, `crm`, `formularios`, `listas`, `outs`, `pagos`, `produccion`, `respuestas`, `stock`, `certificados`, `cotizador2`, `rrhh`, `firma`) VALUES
(1, 0, 'Firstname', 'Lastname', 'Admin', '25d55ad283aa400af464c76d713c07ad', 'change-this@email.com', NULL, 'admin', 3, 2, 1, 2, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `licencias`
--
ALTER TABLE `licencias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sist_usersloginacceso`
--
ALTER TABLE `sist_usersloginacceso`
  ADD PRIMARY KEY (`id_usersLoginAcceso`);

--
-- Indices de la tabla `sist_usuarios`
--
ALTER TABLE `sist_usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `licencias`
--
ALTER TABLE `licencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sist_usersloginacceso`
--
ALTER TABLE `sist_usersloginacceso`
  MODIFY `id_usersLoginAcceso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sist_usuarios`
--
ALTER TABLE `sist_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
