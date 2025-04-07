-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-04-2025 a las 11:20:13
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
  `category_id` bigint(20) NOT NULL,
  `email_provider` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `events`
--

INSERT INTO `events` (`id`, `name`, `description`, `price`, `location`, `date`, `capacity`, `category_id`, `email_provider`) VALUES
(1, 'La Liga de los Campeones', 'Únete a nuestra serie de eventos deportivos y pon a prueba tus habilidades en una competencia futbolística llena de emoción y retos. Participa en diferentes disciplinas, desafía a otros y demuestra que eres el verdadero campeón. ¡Inscríbete ahora y comienza tu camino hacia la victoria!', 15.00, 'Madrid', '2025-04-06 20:03:00', 100, 1, 'proveedor@ucm.es'),
(2, 'Sprint Final: Competencia de Velocidad', '¿Tienes lo necesario para ser el más rápido? Compite en este evento lleno de adrenalina, donde solo los velocistas más ágiles alcanzarán la meta primero. ¡Demuestra tu rapidez y gana el primer lugar!', 5.00, 'Barcelona', '2025-03-31 20:03:20', 50, 2, 'proveedor@ucm.es'),
(8, 'Atletismo sin parar', 'Carrera del 1000 metros de longitud.', 30.00, 'Sevilla', '2025-03-31 20:03:22', 400, 2, 'admin@ucm.es'),
(13, 'Basket o Baloncestoooo', 'lorem ipsum', 30.00, 'Berlin', '2025-04-03 10:45:00', 200, 3, 'admin@ucm.es');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `event_categories`
--

CREATE TABLE `event_categories` (
  `id` bigint(20) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `event_categories`
--

INSERT INTO `event_categories` (`id`, `name`) VALUES
(2, 'Atletismo'),
(3, 'Baloncesto'),
(1, 'Futbol'),
(4, 'Tenis');

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

--
-- Volcado de datos para la tabla `event_participants`
--

INSERT INTO `event_participants` (`user_id`, `event_id`, `user_name`, `user_phone`) VALUES
(2, 1, 'Carlos', 999000111);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `total_price` decimal(11,2) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'En preparación',
  `shipping_address` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `order_details`
--

CREATE TABLE `order_details` (
  `id` bigint(20) NOT NULL,
  `order_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(11,2) NOT NULL,
  `size` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products`
--

CREATE TABLE `products` (
  `id` bigint(20) NOT NULL,
  `provider_id` bigint(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `category_id` bigint(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_categories`
--

CREATE TABLE `product_categories` (
  `id` bigint(20) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_sizes`
--

CREATE TABLE `product_sizes` (
  `product_id` bigint(20) NOT NULL,
  `size_id` bigint(20) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sizes`
--

CREATE TABLE `sizes` (
  `id` bigint(20) NOT NULL,
  `name` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sizes`
--

INSERT INTO `sizes` (`id`, `name`) VALUES
(4, 'L'),
(3, 'M'),
(2, 'S'),
(5, 'XL'),
(1, 'XS'),
(6, 'XXL');

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
(3, 'proveedor@ucm.es', '$2y$10$O7JScf6y95jiI47b86.HA.iwFTMVeMPBOuzuiDQfNRz1ckzFof8/C', 2),
(4, 'prov2@gmail.com', '$2y$10$TRu3cRCpIVHs.dzgUw6cKuRV7LdxhPbRVhOK4SsPd5/KAqryvDKCW', 2),
(7, 'prov3@ucm.es', '$2y$10$fioPHuG6y3Neu/KIdfmOq.gaPoCHB/CnhCuZscR4b.Yk3sLV3zo56', 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `email_provider` (`email_provider`),
  ADD KEY `fk_category` (`category_id`);

--
-- Indices de la tabla `event_categories`
--
ALTER TABLE `event_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indices de la tabla `event_participants`
--
ALTER TABLE `event_participants`
  ADD PRIMARY KEY (`user_id`,`event_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indices de la tabla `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indices de la tabla `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_product_category` (`category_id`),
  ADD KEY `fk_provider_id` (`provider_id`);

--
-- Indices de la tabla `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indices de la tabla `product_sizes`
--
ALTER TABLE `product_sizes`
  ADD PRIMARY KEY (`product_id`,`size_id`),
  ADD KEY `size_id` (`size_id`);

--
-- Indices de la tabla `sizes`
--
ALTER TABLE `sizes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

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
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `event_categories`
--
ALTER TABLE `event_categories`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sizes`
--
ALTER TABLE `sizes`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`email_provider`) REFERENCES `users` (`email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `event_categories` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `event_participants`
--
ALTER TABLE `event_participants`
  ADD CONSTRAINT `event_participants_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `event_participants_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);

--
-- Filtros para la tabla `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_provider_id` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `product_sizes`
--
ALTER TABLE `product_sizes`
  ADD CONSTRAINT `product_sizes_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_sizes_ibfk_2` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
