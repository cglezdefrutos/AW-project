-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-03-2025 a las 11:41:43
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
-- Base de datos: `the_balance`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `events`
--

CREATE TABLE `events` (
  `id` bigint(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(11,2) NOT NULL,
  `location` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `capacity` int(11) NOT NULL,
  `category` varchar(50) NOT NULL,
  `email_provider` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `events`
--

INSERT INTO `events` (`id`, `name`, `description`, `price`, `location`, `date`, `capacity`, `category`, `email_provider`) VALUES
(1, 'La Liga de los Campeones', 'Únete a nuestra serie de eventos deportivos y pon a prueba tus habilidades en una competencia futbolística llena de emoción y retos. Participa en diferentes disciplinas, desafía a otros y demuestra que eres el verdadero campeón. ¡Inscríbete ahora y comienza tu camino hacia la victoria!', 10.00, 'Madrid', '2025-03-25 11:00:00', 100, 'Futbol', 'proveedor@ucm.es'),
(2, 'Sprint Final: Competencia de Velocidad', '¿Tienes lo necesario para ser el más rápido? Compite en este evento lleno de adrenalina, donde solo los velocistas más ágiles alcanzarán la meta primero. ¡Demuestra tu rapidez y gana el primer lugar!', 5.00, 'Barcelona', '2025-03-28 17:00:00', 50, 'Atletismo', 'proveedor@ucm.es'),
(3, 'Torneo de Baloncesto', 'Estás listo para hacer historia en la cancha? Únete a nuestro torneo de baloncesto, donde equipos de todas partes competirán por la gloria. Demuestra tu destreza, estrategia y habilidades de alto nivel para llevar a tu equipo a la victoria!', 20.00, 'Sevilla', '2025-03-07 14:30:00', 80, 'Baloncesto', 'proveedor@ucm.es');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `event_participants`
--

CREATE TABLE `event_participants` (
  `user_id` bigint(20) NOT NULL,
  `event_id` bigint(20) NOT NULL,
  `user_name` varchar(50) DEFAULT NULL,
  `user_phone` int(9) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(150) NOT NULL,
  `usertype` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `usertype`) VALUES
(1, 'admin@ucm.es', '$2y$10$dxFk/WvLJXsdGqy5B1bzIOWW1sYEr2.dkacX3pgZoWFAbyJdbrP4u', 0),
(2, 'usuario@ucm.es', '$2y$10$eeukdBpd7WAkcIs.8K7ZgOHnURQb8/0ufAArl0ksPcuxra9/SLVRa', 1),
(3, 'proveedor@ucm.es', '$2y$10$O7JScf6y95jiI47b86.HA.iwFTMVeMPBOuzuiDQfNRz1ckzFof8/C', 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `email_provider` (`email_provider`);

--
-- Indices de la tabla `event_participants`
--
ALTER TABLE `event_participants`
  ADD PRIMARY KEY (`user_id`,`event_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`email_provider`) REFERENCES `users` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `event_participants`
--
ALTER TABLE `event_participants`
  ADD CONSTRAINT `event_participants_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `event_participants_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
