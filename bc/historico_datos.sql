-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 04-11-2025 a las 15:46:40
-- Versión del servidor: 8.0.41-0ubuntu0.24.04.1
-- Versión de PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `gcp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historico_datos`
--

CREATE TABLE `historico_datos` (
  `id` int NOT NULL,
  `fk_elementos` int NOT NULL,
  `datos_anteriores` longtext NOT NULL,
  `usuario_modificacion` int NOT NULL,
  `fecha_modificacion` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `historico_datos`
--

INSERT INTO `historico_datos` (`id`, `fk_elementos`, `datos_anteriores`, `usuario_modificacion`, `fecha_modificacion`) VALUES
(1, 10, '{\"id\":10,\"fk_tipos\":1,\"fk_clases\":5,\"fk_subclases\":1,\"clase_sap\":16700100,\"codigo\":\"1334333\",\"sn\":122,\"descripcion\":\"GPS ALTA PRECISION. SISTEMA DE POSICIONAMIENTO GLO\",\"inventario\":\"100\",\"serie\":\"GMZ20584001824\",\"cantidad\":1,\"valor_old\":22074500,\"valor\":\"22.00\",\"fk_contratos\":1,\"fk_dependencias\":7,\"responsable\":588,\"responsable2\":120383,\"en_tramite\":0,\"estado_uso\":\"Servible\",\"concepto\":null,\"uso\":null,\"devolver\":0,\"estado\":\"Activo\",\"creado_por\":1,\"fecha_creacion\":\"2024-12-12 09:48:14\",\"modificado_por\":2,\"fecha_modificacion\":\"2025-11-04 10:40:58\"}', 2, '2025-11-04 10:41:38'),
(2, 11, '{\"id\":11,\"fk_tipos\":1,\"fk_clases\":5,\"fk_subclases\":1,\"clase_sap\":16700100,\"codigo\":\"1330001233234\",\"sn\":31,\"descripcion\":\"TERMINAL PORTATIL IZAR HANDHELD CAT S40 + I@M 2 AN\",\"inventario\":\"288230010007005000\",\"serie\":\"0012F32F82FDD\",\"cantidad\":1,\"valor_old\":20354950,\"valor\":\"20354950.00\",\"fk_contratos\":1,\"fk_dependencias\":19,\"responsable\":14,\"responsable2\":101825,\"en_tramite\":0,\"estado_uso\":\"Servible\",\"concepto\":null,\"uso\":null,\"devolver\":0,\"estado\":\"Activo\",\"creado_por\":1,\"fecha_creacion\":\"2024-12-12 09:48:14\",\"modificado_por\":2,\"fecha_modificacion\":\"2025-11-04 10:35:13\"}', 2, '2025-11-04 10:42:31'),
(3, 11, '{\"id\":11,\"fk_tipos\":1,\"fk_clases\":5,\"fk_subclases\":1,\"clase_sap\":16700100,\"codigo\":\"13300\",\"sn\":31,\"descripcion\":\"TERMINAL PORTATIL IZAR HANDHELD CAT S40 + I@M 2 AN\",\"inventario\":\"288230010007005000\",\"serie\":\"0012F32F82FDD\",\"cantidad\":1,\"valor_old\":20354950,\"valor\":\"20354950.00\",\"fk_contratos\":1,\"fk_dependencias\":19,\"responsable\":14,\"responsable2\":101825,\"en_tramite\":0,\"estado_uso\":\"Servible\",\"concepto\":null,\"uso\":null,\"devolver\":0,\"estado\":\"Activo\",\"creado_por\":1,\"fecha_creacion\":\"2024-12-12 09:48:14\",\"modificado_por\":2,\"fecha_modificacion\":\"2025-11-04 10:42:31\"}', 2, '2025-11-04 10:42:38');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `historico_datos`
--
ALTER TABLE `historico_datos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_elementos` (`fk_elementos`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `historico_datos`
--
ALTER TABLE `historico_datos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
