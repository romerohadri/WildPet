-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 29-05-2026 a las 19:19:56
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `wildpet`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carrito`
--

INSERT INTO `carrito` (`id`, `id_usuario`, `id_producto`, `cantidad`) VALUES
(13, 4, 15, 1),
(16, 5, 31, 1),
(17, 5, 2, 1),
(18, 5, 11, 1),
(19, 5, 12, 1),
(20, 5, 17, 1),
(21, 5, 21, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`) VALUES
(1, 'Perros'),
(2, 'Gatos'),
(3, 'Pájaros'),
(4, 'Peces'),
(5, 'Perros'),
(6, 'Gatos'),
(7, 'Pájaros'),
(8, 'Peces');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedido`
--

CREATE TABLE `detalle_pedido` (
  `id` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL,
  `estado` enum('pendiente','pagado','enviado','entregado') DEFAULT 'pendiente',
  `envio_nombre` varchar(150) DEFAULT '',
  `envio_direccion` varchar(255) DEFAULT '',
  `envio_ciudad` varchar(100) DEFAULT '',
  `envio_provincia` varchar(100) DEFAULT '',
  `envio_codigo_postal` varchar(20) DEFAULT '',
  `envio_pais` varchar(100) DEFAULT '',
  `envio_telefono` varchar(30) DEFAULT '',
  `envio_observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `id_usuario`, `fecha`, `total`, `estado`, `envio_nombre`, `envio_direccion`, `envio_ciudad`, `envio_provincia`, `envio_codigo_postal`, `envio_pais`, `envio_telefono`, `envio_observaciones`) VALUES
(1, 6, '2026-05-22 18:41:16', 108.98, 'pendiente', '', '', '', '', '', '', '', NULL),
(2, 6, '2026-05-22 19:05:05', 35.99, 'pendiente', '', '', '', '', '', '', '', 'Deja el pedido detras del muro a la drecha'),
(3, 7, '2026-05-25 17:25:03', 76.40, 'pendiente', 'Hadrian', 'Avda. Rosalía De Castro', 'Corcubión', 'La Coruña', '15130', 'España', '651512316', 'dejar detrás del muro a la derecha'),
(4, 2, '2026-05-26 18:25:30', 63.97, 'pendiente', 'Hadrián Romero Abelleira', 'Avda. Rosalia de castro', 'Corcubión', 'Coruña', '15130', 'españa', '651512316', 'dejar paquete detrás del muro'),
(5, 2, '2026-05-27 18:03:48', 6.99, 'pendiente', 'prueba', 'Avda. Rosalia de castro', 'Corcubión', 'Coruña', '15130', 'españa', '651512316', 'prueba'),
(6, 6, '2026-05-27 18:05:44', 3.50, 'pendiente', 'hadri', 'AVDA. ROSALIA DE CASTRO, 60', 'CORCUBIÓN', 'CORUÑA', '15130', 'España', '651512316', 'prueba muro');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_items`
--

CREATE TABLE `pedido_items` (
  `id` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `nombre_producto` varchar(255) NOT NULL,
  `precio` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido_items`
--

INSERT INTO `pedido_items` (`id`, `id_pedido`, `id_producto`, `nombre_producto`, `precio`, `cantidad`, `imagen`) VALUES
(1, 1, 16, 'Champú Hipoalergénico', 14.00, 7, 'img2/d12.png'),
(2, 1, 22, 'Pelota interactiva con cascabel', 5.99, 1, 'img2/Pelota interactiva con cascabel .jpg'),
(3, 1, 28, 'Comida en escamas tropical', 4.99, 1, 'img2/Comida en escamas tropical .jpg'),
(4, 2, 2, 'Alimento Seco Premium para Perros', 35.99, 1, 'img2/d2.png'),
(5, 3, 14, 'Arnés Antitirones Premium', 38.20, 2, 'img2/d10.png'),
(6, 4, 26, 'Pecera de cristal pequeña', 39.99, 1, 'img2/Pecera de cristal pequeña .jpg'),
(7, 4, 27, 'Filtro silencioso para acuario', 18.99, 1, 'img2/Filtro silencioso para acuario .jpg'),
(8, 4, 28, 'Comida en escamas tropical', 4.99, 1, 'img2/Comida en escamas tropical .jpg'),
(9, 5, 33, 'Mezcla de semillas natural', 6.99, 1, 'img2/Mezcla de semillas natural pájaro.jpg'),
(10, 6, 21, 'Comida húmeda sabor salmón', 3.50, 1, 'img2/Comida húmeda sabor salmón .webp');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `id_categoria` int(11) NOT NULL,
  `destacado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `imagen`, `stock`, `id_categoria`, `destacado`) VALUES
(1, 'Juguete Masticable para Cachorros', 'Juguete resistente para el desarrollo dental y diversión.', 12.50, 'img2/d1.png', 20, 1, 1),
(2, 'Alimento Seco Premium para Perros', 'Receta rica en proteínas para una vida activa.', 35.99, 'img2/d2.png', 15, 1, 1),
(3, 'Collar de Cuero Ajustable', 'Diseño duradero y cómodo para uso diario.', 24.99, 'img2/d3.png', 10, 1, 0),
(4, 'Dispensador Automático de Premios', 'Ideal para entrenar y recompensar a tu perro con chuches.', 49.00, 'img2/Juguete Dispensador de Premios.jpg', 8, 1, 0),
(5, 'Juguete Masticable para Cachorros', 'Juguete resistente para el desarrollo dental y diversión.', 12.50, 'img2/d1.png', 20, 1, 1),
(6, 'Alimento Seco Premium para Perros', 'Receta rica en proteínas para una vida activa.', 35.99, 'img2/d2.png', 15, 1, 1),
(7, 'Collar de Cuero Ajustable', 'Diseño duradero y cómodo para uso diario.', 24.99, 'img2/d3.png', 10, 1, 0),
(8, 'Dispensador Automático de Golosinas', 'Ideal para entrenar y recompensar a tu perro.', 49.00, 'img2/d4.png', 8, 1, 0),
(9, 'Cepillo de Aseo Suave', 'Mantiene el pelaje brillante y sin enredos.', 18.75, 'img2/d5.png', 12, 1, 0),
(10, 'Cama Ortopédica para Perros', 'Máxima comodidad para un descanso reparador.', 75.00, 'img2/d6.png', 5, 1, 0),
(11, 'Correa Retráctil Robusta', 'Control seguro y libertad para tu paseo.', 29.95, 'img2/d7.png', 7, 1, 0),
(12, 'Kit de Entrenamiento para Perros', 'Herramientas esencial para una buena educación.', 22.00, 'img2/d8.png', 9, 1, 0),
(13, 'Botella de Agua Portátil', 'Hidratación fácil durante las aventuras al aire libre.', 15.49, 'img2/d9.png', 14, 1, 0),
(14, 'Arnés Antitirones Premium', 'Ayuda a controlar los tirones y mejora el paseo.', 38.20, 'img2/d10.png', 6, 1, 0),
(15, 'Set de Juguetes de Cuerda', 'Diversión interactiva para horas de juego.', 19.99, 'img2/d11.png', 11, 1, 0),
(16, 'Champú Hipoalergénico', 'Suave para la piel sensible, deja el pelaje brillante.', 14.00, 'img2/d12.png', 16, 1, 0),
(17, 'Abrigo Impermeable para Perros', 'Protege a tu perro de la lluvia y el viento.', 45.00, 'img2/Abrigo Impermeable para Perros.jpg', 8, 1, 0),
(18, 'Bozal de Malla Transpirable', 'Seguro y cómodo para el control y la socialización.', 20.30, 'img2/d14.png', 10, 1, 0),
(19, 'Juguete Dispensador de Premios', 'Estimula la mente apto para jugar.', 16.99, 'img2/Juguete Dispensador de Premios.jpg', 13, 1, 0),
(20, 'Rascador de palo con base para gatos', 'Ideal para que los gatos afilen sus uñas y jueguen cómodamente.', 24.99, 'img2/rascador_gato.jpg', 10, 2, 0),
(21, 'Comida húmeda sabor salmón', 'Alimento húmedo rico en proteínas y sabor natural.', 3.50, 'img2/Comida húmeda sabor salmón .webp', 25, 2, 0),
(22, 'Pelota interactiva con cascabel', 'Juguete ligero diseñado para mantener activo al gato.', 5.99, 'img2/Pelota interactiva con cascabel .jpg', 18, 2, 0),
(23, 'Cama redonda acolchada', 'Cama suave y cómoda para el descanso diario.', 29.99, 'img2/Cama redonda acolchada gato.webp', 8, 2, 0),
(24, 'Collar ajustable para gato', 'Collar resistente con cierre seguro y ajuste cómodo.', 7.99, 'img2/Collar ajustable para gato .jpg', 15, 2, 0),
(25, 'Fuente automática de agua', 'Mantiene el agua en movimiento para una mejor hidratación.', 34.99, 'img2/Fuente automática de agua gato.webp', 7, 2, 0),
(26, 'Pecera de cristal pequeña', 'Acuario compacto perfecto para peces pequeños.', 39.99, 'img2/Pecera de cristal pequeña .jpg', 6, 4, 0),
(27, 'Filtro silencioso para acuario', 'Mantiene el agua limpia sin generar apenas ruido.', 18.99, 'img2/Filtro silencioso para acuario .jpg', 9, 4, 0),
(28, 'Comida en escamas tropical', 'Alimentación equilibrada para peces tropicales.', 4.99, 'img2/Comida en escamas tropical .jpg', 20, 4, 0),
(29, 'Plantas decorativas acuáticas', 'Decoración artificial para mejorar el aspecto del acuario.', 9.99, 'img2/Plantas decorativas acuáticas .jpg', 14, 4, 0),
(30, 'Luz led para pecera', 'Iluminación eficiente y de bajo consumo para acuarios.', 22.99, 'img2/Luz LED para pecera .jpg', 9, 4, 0),
(31, 'Red pequeña para limpieza', 'Red práctica para mover peces o retirar residuos.', 3.99, 'img2/Red pequeña para limpieza .jpg', 16, 4, 0),
(32, 'Jaula metálica para pájaros', 'Jaula espaciosa y resistente para aves pequeñas.', 54.99, 'img2/Jaula metálica para pájaros .jpg', 5, 3, 0),
(33, 'Mezcla de semillas natural', 'Alimentación variada para canarios y aves domésticas.', 6.99, 'img2/Mezcla de semillas natural pájaro.jpg', 18, 3, 0),
(34, 'Columpio de madera', 'Accesorio pensado para el entretenimiento de las aves.', 8.50, 'img2/Columpio de madera .webp', 12, 3, 0),
(35, 'Pack de bebederos transparente', 'Bebederos, fáciles de instalar y limpiar.', 4.50, 'img2/Bebedero transparente .webp', 20, 3, 0),
(36, 'Snack de frutas para aves', 'Premio natural elaborado con frutas deshidratadas.', 5.99, 'img2/Snack de frutas para pajaros .avif', 15, 3, 0),
(37, 'Palo/percha natural para jaula', 'Percha de madera natural para mejorar el apoyo de las aves.', 7.50, 'img2/Palo:percha natural para jaula .jpg', 11, 3, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('cliente','admin') DEFAULT 'cliente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `rol`) VALUES
(1, 'Usuario Prueba', 'prueba@wildpet.com', '$2y$10$YJGbLVq5ApRq5tqTqPjKu.9VzQQe8D9G9Zvj0pZvp3K9QKYVpJ9gS', 'cliente'),
(2, 'prueba', 'prueba@gmail.com', '$2y$10$JEeXaGJZnTLpbiBcNcEzJuFieaYFJOzS0nm9QxWw.z1YTWL56QYpW', 'cliente'),
(3, 'luis', 'luisromeroprueba@gmail.com', '$2y$10$6XSnaYjIrDvpODIVzWlPm.16zx5lTavF0RtOCJjmFK4X3CenGWY4K', 'cliente'),
(4, 'Prueba', 'hadriromero69@gmail.com', '$2y$10$ZELW7fAEUVtLXnBe1VHmJ.0fEVxmmp.kuXQdtubEVwWHVgGG8iaai', 'cliente'),
(5, 'hola', 'hola@gmail.com', '$2y$10$SEfcs23Xa07338XnP3CIlO.ZahEjm28EhHWd7mjhRI6DpnHPfrsQa', 'cliente'),
(6, 'hadri', 'pruebahadri123@gmail.com', '$2y$10$hy.LdPIXw0HEW2yknxknpu3RIxBpXS9HxEJS7E1/3m9j2SfaFkJYG', 'cliente'),
(7, 'Hadrian', 'romerohadri@gmail.com', '$2y$10$okBZC7QU49Z4ldxbjvjn/uy1uBezROK5G42soF0tsXoKDZf6QFcri', 'cliente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_envio`
--

CREATE TABLE `usuario_envio` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `direccion` varchar(255) DEFAULT '',
  `ciudad` varchar(100) DEFAULT '',
  `provincia` varchar(100) DEFAULT '',
  `codigo_postal` varchar(20) DEFAULT '',
  `pais` varchar(100) DEFAULT '',
  `telefono` varchar(30) DEFAULT '',
  `telefono_secundario` varchar(30) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario_envio`
--

INSERT INTO `usuario_envio` (`id`, `id_usuario`, `direccion`, `ciudad`, `provincia`, `codigo_postal`, `pais`, `telefono`, `telefono_secundario`) VALUES
(1, 6, 'AVDA. ROSALIA DE CASTRO, 60', 'CORCUBIÓN', 'CORUÑA', '15130', 'España', '651512316', ''),
(2, 7, 'Avda. Rosalía De Castro', 'Corcubión', 'La Coruña', '15130', 'España', '651512316', '645571149');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_facturacion`
--

CREATE TABLE `usuario_facturacion` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `direccion` varchar(255) DEFAULT '',
  `ciudad` varchar(100) DEFAULT '',
  `provincia` varchar(100) DEFAULT '',
  `codigo_postal` varchar(20) DEFAULT '',
  `pais` varchar(100) DEFAULT '',
  `telefono` varchar(30) DEFAULT '',
  `nif` varchar(30) DEFAULT '',
  `telefono_secundario` varchar(30) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario_facturacion`
--

INSERT INTO `usuario_facturacion` (`id`, `id_usuario`, `direccion`, `ciudad`, `provincia`, `codigo_postal`, `pais`, `telefono`, `nif`, `telefono_secundario`) VALUES
(1, 6, 'AVDA. ROSALIA DE CASTRO, 60', 'CORCUBIÓN', 'CORUÑA', '15130', 'ESPAÑA', '651512316', '', ''),
(2, 7, 'Avda. Rosalía De Castro , 60', 'Corcubión', 'La Coruña', '15130', 'España', '651512316', '', '645571149');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `pedido_items`
--
ALTER TABLE `pedido_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pi_pedido` (`id_pedido`),
  ADD KEY `idx_pi_producto` (`id_producto`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `usuario_envio`
--
ALTER TABLE `usuario_envio`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `usuario_facturacion`
--
ALTER TABLE `usuario_facturacion`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `pedido_items`
--
ALTER TABLE `pedido_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `usuario_envio`
--
ALTER TABLE `usuario_envio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuario_facturacion`
--
ALTER TABLE `usuario_facturacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `carrito_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD CONSTRAINT `detalle_pedido_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `detalle_pedido_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `pedido_items`
--
ALTER TABLE `pedido_items`
  ADD CONSTRAINT `fk_pi_pedido` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pi_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`);

--
-- Filtros para la tabla `usuario_envio`
--
ALTER TABLE `usuario_envio`
  ADD CONSTRAINT `fk_ue_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario_facturacion`
--
ALTER TABLE `usuario_facturacion`
  ADD CONSTRAINT `fk_uf_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
