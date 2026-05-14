-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-05-2026 a las 15:08:56
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `portal_usuarios`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria_cambios_password`
--

CREATE TABLE `auditoria_cambios_password` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `password_anterior_hash` varchar(255) NOT NULL,
  `password_nuevo_hash` varchar(255) NOT NULL,
  `fecha_cambio` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `auditoria_cambios_password`
--

INSERT INTO `auditoria_cambios_password` (`id`, `usuario_id`, `password_anterior_hash`, `password_nuevo_hash`, `fecha_cambio`) VALUES
(4, 7, '$2y$10$18AqrTb.YeiTZ4aCT/n48ewrrsskf/CDLmLqrxluUf.lAnVjkRTPG', '$2y$10$c4i210Yuj6F8UidStCbuD.fVw0xbesKo7WDFO/7o/n.Gv.bdZGZve', '2026-05-14 08:04:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `cedula`, `nombre`, `correo`, `password`, `fecha_registro`) VALUES
(7, '1727712257', 'Byron Ñato V LL', 'bvnato@utpl.edu.ec', '$2y$10$c4i210Yuj6F8UidStCbuD.fVw0xbesKo7WDFO/7o/n.Gv.bdZGZve', '2026-05-14 08:03:07'),
(8, '1727712256', 'Vinicio Llumiquinga', 'lbv@utpl.edu.ec', '$2y$10$gel6J8TGMhlP/a2FNfmwZ.LWap16qIPrQbl8evILsz73EZOCR0/Du', '2026-05-14 08:03:42');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `auditoria_cambios_password`
--
ALTER TABLE `auditoria_cambios_password`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_usuario_auditoria` (`usuario_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `auditoria_cambios_password`
--
ALTER TABLE `auditoria_cambios_password`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `auditoria_cambios_password`
--
ALTER TABLE `auditoria_cambios_password`
  ADD CONSTRAINT `fk_usuario_auditoria` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
