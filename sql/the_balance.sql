-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-04-2025 a las 12:40:33
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
(1, 'La Liga de los Campeones', 'Únete a nuestra serie de eventos deportivos y pon a prueba tus habilidades en una competencia futbolística llena de emoción y retos. Participa en diferentes disciplinas, desafía a otros y demuestra que eres el verdadero campeón. ¡Inscríbete ahora y comienza tu camino hacia la victoria!', 15.00, 'Madrid', '2025-04-07 09:37:54', 100, 1, 'nike@gmail.com'),
(2, 'Sprint Final: Competencia de Velocidad', '¿Tienes lo necesario para ser el más rápido? Compite en este evento lleno de adrenalina, donde solo los velocistas más ágiles alcanzarán la meta primero. ¡Demuestra tu rapidez y gana el primer lugar!', 5.00, 'Barcelona', '2025-04-07 09:37:54', 50, 2, 'adidas@gmail.com'),
(3, 'Atletismo sin parar', 'Carrera del 1000 metros de longitud.', 30.00, 'Sevilla', '2025-04-07 09:37:54', 400, 2, 'proveedor@ucm.es'),
(4, 'Torneo de Baloncesto 3x3', 'Participa en el emocionante torneo de baloncesto 3x3 en el centro deportivo local. Equipos de todas las edades son bienvenidos.', 15.00, 'Extremadura', '2025-04-19 09:30:00', 50, 3, 'proveedor@ucm.es'),
(5, 'Exhibición de Tenis con Profesionales', 'Disfruta de una exhibición de tenis con jugadores profesionales en un evento único para los amantes del deporte.', 25.00, 'Las Palmas', '2025-04-07 09:37:54', 100, 4, 'info@tenispro.com');

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

--
-- Volcado de datos para la tabla `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `status`, `shipping_address`, `created_at`) VALUES
(1, 2, 184.94, 'En preparación', 'Av. Complutense, s/n, Moncloa - Aravaca, 1A, 28040, Madrid', '2025-04-07 10:22:46');

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

--
-- Volcado de datos para la tabla `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`, `size`) VALUES
(1, 1, 3, 1, 59.95, 'M'),
(2, 1, 2, 1, 124.99, 'L');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products`
--

CREATE TABLE `products` (
  `id` bigint(20) NOT NULL,
  `provider_id` bigint(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(11,2) NOT NULL,
  `category_id` bigint(11) NOT NULL,
  `image_guid` varchar(36) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `products`
--

INSERT INTO `products` (`id`, `provider_id`, `name`, `description`, `price`, `category_id`, `image_guid`, `created_at`, `active`) VALUES
(1, 4, 'Camiseta nike', 'Camiseta nike para hacer deporte. Tecnolog&iacute;a TECH.', 30.00, 2, '34593f90-0182-476f-a4cd-d6bab331111c', '2025-04-02 15:42:09', 1),
(2, 4, 'Camiseta Cleveland Cavaliers - NBA Retro', 'Rememora los inicios del Rey con esta camiseta Retro de los Cleveland Cavaliers', 124.99, 3, '22e857d0-b22d-4c0f-b227-709a1c44dd91', '2025-04-07 09:58:05', 1),
(3, 5, 'Camiseta de la selecci&oacute;n espa&ntilde;ola', 'La rica historia del f&uacute;tbol espa&ntilde;ol ha llevado a la selecci&oacute;n a ser una de las m&aacute;s respetadas del viejo continente. Adidas Football ha querido rendir homenaje a este logro con esta camiseta de la primera equipaci&oacute;n de la selecci&oacute;n de f&uacute;tbol de Espa&ntilde;a para la Eurocopa 2024 que se disputar&aacute; este verano en Alemania.', 59.95, 1, '37fa7593-b5cf-40d2-9185-b4aea4d353fb', '2025-04-07 10:01:56', 1),
(4, 6, 'CAMISETA LOTTO TOP IV', '&iexcl;Esta camiseta Lotto hombre Top IV es la pieza que necesitas para jugar tu mejor tenis con estilo!\\r\\n\\r\\nMuy ligera y suave, el tejido de esta camiseta te ofrecer&aacute; un confort inigualable y una excelente libertad de movimientos. Gracias a la tecnolog&iacute;a Deep Dry, que permite la r&aacute;pida evacuaci&oacute;n del sudor, te mantendr&aacute;s seco durante toda tu actuaci&oacute;n en la pista.\\r\\n\\r\\nEsta camisa presenta un moderno y atractivo patr&oacute;n de lunares en varios tama&ntilde;os. Se convertir&aacute; r&aacute;pidamente en un elemento b&aacute;sico de tu armario.', 30.00, 4, 'b0212e5c-3b0d-42ce-b8a4-616de5ae053f', '2025-04-07 10:15:10', 1),
(5, 3, 'Camiseta baloncesto Spalding All star', 'Camiseta de baloncesto Spalding 100% poliester de alta calidad, con inserciones de rejilla en la costura lateral.', 18.15, 3, '555963d9-9c78-4e5c-aa36-3cd9392cd5bb', '2025-04-07 10:18:13', 1),
(6, 4, 'Sudadera Nike', 'Sudadera Nike para el d&iacute;a a d&iacute;a.', 50.00, 2, '4af88da4-28f8-436f-aa8b-de477053144f', '2025-04-04 10:52:23', 1),
(11, 5, 'Camiseta Jude Bellingham', 'Vibra con el estilo de Jude Bellingham\\r\\nApoya a tu jugador favorito con total comodidad. Esta camiseta adidas fusiona el f&uacute;tbol con el estilo urbano. El tr&eacute;bol y el logotipo de Jude Bellingham destacan sobre un llamativo estampado. Se ha confeccionado en un tejido interlock suave y c&oacute;modo que te permite mostrar tu pasi&oacute;n por el deporte rey.', 100.00, 2, '01951463-bc8e-4bf0-9748-5174fcc57145', '2025-04-07 10:32:32', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_categories`
--

CREATE TABLE `product_categories` (
  `id` bigint(20) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `product_categories`
--

INSERT INTO `product_categories` (`id`, `name`) VALUES
(5, 'Atletismo'),
(3, 'Baloncesto'),
(1, 'Futbol'),
(2, 'Lifestyle'),
(4, 'Tenis');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_sizes`
--

CREATE TABLE `product_sizes` (
  `product_id` bigint(20) NOT NULL,
  `size_id` bigint(20) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `product_sizes`
--

INSERT INTO `product_sizes` (`product_id`, `size_id`, `stock`) VALUES
(1, 2, 0),
(1, 3, 1),
(2, 1, 50),
(2, 2, 20),
(2, 3, 2),
(2, 4, 14),
(2, 5, 40),
(2, 6, 15),
(3, 1, 20),
(3, 2, 25),
(3, 3, 39),
(3, 4, 0),
(3, 5, 1),
(3, 6, 0),
(4, 1, 15),
(4, 2, 20),
(4, 3, 30),
(4, 4, 50),
(4, 5, 2),
(4, 6, 1),
(5, 1, 15),
(5, 2, 20),
(5, 3, 40),
(5, 4, 10),
(5, 5, 2),
(5, 6, 1),
(6, 1, 10),
(6, 2, 19),
(6, 3, 25),
(6, 4, 30),
(6, 5, 10),
(6, 6, 0),
(11, 1, 10),
(11, 2, 15),
(11, 3, 20),
(11, 4, 20),
(11, 5, 10),
(11, 6, 5);

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
(4, 'nike@gmail.com', '$2y$10$CTipry5dJKU3tjRHbDSh6eBczqdZNCUQXIa5S6JVxUXesDvH7rHI6', 2),
(5, 'adidas@gmail.com', '$2y$10$awXL183BONQas84ukiOuy.jQyrgd1Tardw.3LMXACEJcZCl9w1VoK', 2),
(6, 'info@tenispro.com', '$2y$10$QRUXvAD8fbPY2mWxkt3Xku4LqShFaHR/JvsspicH/T0RdIvJeQldC', 2);

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
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `event_categories`
--
ALTER TABLE `event_categories`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `sizes`
--
ALTER TABLE `sizes`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  ADD CONSTRAINT `product_sizes_ibfk_2` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_sizes_ibfk_3` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
