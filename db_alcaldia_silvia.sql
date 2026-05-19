-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-05-2026 a las 08:12:05
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
-- Base de datos: `db_alcaldia_silvia`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria`
--

CREATE TABLE `auditoria` (
  `id_auditoria` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL COMMENT 'Puede ser NULL si la acción es un intento de login fallido',
  `accion` varchar(50) NOT NULL COMMENT 'INSERT, UPDATE, DELETE, LOGIN',
  `tabla_afectada` varchar(50) DEFAULT NULL,
  `registro_id` int(11) DEFAULT NULL,
  `detalles_previos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Estado de los datos antes del cambio' CHECK (json_valid(`detalles_previos`)),
  `detalles_nuevos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Estado de los datos después del cambio' CHECK (json_valid(`detalles_nuevos`)),
  `direccion_ip` varchar(45) NOT NULL,
  `fecha_hora` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `auditoria`
--

INSERT INTO `auditoria` (`id_auditoria`, `id_usuario`, `accion`, `tabla_afectada`, `registro_id`, `detalles_previos`, `detalles_nuevos`, `direccion_ip`, `fecha_hora`) VALUES
(1, 1, 'INSERT', 'contratos', 1, NULL, '{\"numero_contrato\":\"007\",\"id_contratista\":\"1\",\"tipo_contrato\":\"Prestaci\\u00f3n de Servicios\",\"modalidad_seleccion\":\"Contrataci\\u00f3n Directa\",\"fuente_recursos\":\"SGP\",\"valor_total\":\"20000\",\"plazo_ejecucion\":\"6 MESES\",\"numero_cdp\":\"009\",\"numero_rp\":\"010\",\"objeto_contrato\":\"PLATAFORMAS\",\"fecha_elaboracion\":\"2026-04-17\",\"oficina_dependencia\":\"Oficina Asesora Juridica\",\"fecha_inicio\":\"2026-04-20\"}', '::1', '2026-04-19 05:02:56'),
(2, 1, 'INSERT', 'contratistas', 2, NULL, '{\"tipo_documento\":\"CC\",\"documento\":\"10722526\",\"nombre_razon_social\":\"GERMAN ROLANDO BURBANO CORREA\",\"genero\":\"Masculino\"}', '::1', '2026-04-19 05:14:02'),
(3, 1, 'INSERT', 'contratos', 2, NULL, '{\"numero_contrato\":\"007\",\"id_contratista\":\"2\",\"tipo_contrato\":\"Prestaci\\u00f3n de Servicios\",\"modalidad_seleccion\":\"Contrataci\\u00f3n Directa\",\"fuente_recursos\":\"SGP\",\"valor_total\":\"20000\",\"plazo_ejecucion\":\"6 MESES\",\"numero_cdp\":\"009\",\"numero_rp\":\"010\",\"objeto_contrato\":\"SECOP Y SIA\",\"fecha_elaboracion\":\"2026-04-16\",\"oficina_dependencia\":\"Oficina Asesora Juridica\",\"fecha_inicio\":\"2026-04-17\"}', '::1', '2026-04-19 05:14:50'),
(4, 1, 'INSERT', 'presupuesto', 2, NULL, '{\"monto_pagado\":\"20000\"}', '::1', '2026-04-19 05:28:12'),
(5, 1, 'INSERT', 'control_plataformas', 2, NULL, '{\"id_contrato\":\"2\",\"estado_secop\":\"Publicado\",\"url_secop\":\"\",\"estado_sia_observa\":\"Cargado\",\"observaciones\":\"\"}', '::1', '2026-04-19 05:37:48'),
(6, 1, 'INSERT', 'contratistas', 3, NULL, '{\"tipo_persona\":\"Natural\",\"tipo_documento\":\"CC\",\"documento\":\"1064440931\",\"nombre_razon_social\":\"ANDRES CAMILO VIDAL RODRIGUEZ\",\"genero\":\"Masculino\",\"direccion\":\"Calle 12 # 2-80 Silvia, Cauca\",\"telefono\":\"3147741394\",\"correo\":\"apoyoinfrasilvia@gmail.com\",\"entidad_bancaria\":\"Bancolombia\",\"tipo_cuenta\":\"Ahorros\",\"numero_cuenta\":\"86879366757\"}', '::1', '2026-04-23 22:07:32'),
(7, 1, 'UPDATE', 'presupuesto', 2, NULL, '{\"monto_pagado\":\"3333333.33\"}', '::1', '2026-04-23 22:18:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contratistas`
--

CREATE TABLE `contratistas` (
  `id_contratista` int(11) NOT NULL,
  `tipo_persona` enum('Natural','Jurídica') NOT NULL DEFAULT 'Natural',
  `tipo_documento` enum('CC','NIT','CE','Pasaporte') NOT NULL,
  `documento` varchar(50) NOT NULL,
  `nombre_razon_social` varchar(255) NOT NULL,
  `genero` enum('Masculino','Femenino','Otro','No Aplica') NOT NULL COMMENT 'No Aplica para Personas Jurídicas',
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `correo` varchar(150) DEFAULT NULL,
  `entidad_bancaria` varchar(100) DEFAULT NULL,
  `tipo_cuenta` enum('Ahorros','Corriente') DEFAULT NULL,
  `numero_cuenta` varchar(100) DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `contratistas`
--

INSERT INTO `contratistas` (`id_contratista`, `tipo_persona`, `tipo_documento`, `documento`, `nombre_razon_social`, `genero`, `direccion`, `telefono`, `correo`, `entidad_bancaria`, `tipo_cuenta`, `numero_cuenta`, `creado_en`) VALUES
(2, 'Natural', 'CC', '10722526', 'GERMAN ROLANDO BURBANO CORREA', 'Masculino', NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-19 05:14:02'),
(3, 'Natural', 'CC', '1064440931', 'ANDRES CAMILO VIDAL RODRIGUEZ', 'Masculino', 'Calle 12 # 2-80 Silvia, Cauca', '3147741394', 'apoyoinfrasilvia@gmail.com', 'Bancolombia', 'Ahorros', '86879366757', '2026-04-23 22:07:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contratos`
--

CREATE TABLE `contratos` (
  `id_contrato` int(11) NOT NULL,
  `numero_contrato` varchar(100) NOT NULL,
  `bpin` varchar(50) DEFAULT NULL,
  `linea_estrategica` varchar(255) DEFAULT NULL,
  `id_contratista` int(11) NOT NULL,
  `tipo_contrato` varchar(100) NOT NULL COMMENT 'Ej: Prestación de Servicios, Obra, Suministro',
  `modalidad_seleccion` varchar(100) NOT NULL COMMENT 'Ej: Contratación Directa, Licitación Pública',
  `fuente_recursos` varchar(150) NOT NULL COMMENT 'Ej: SGP, Recursos Propios, Regalías',
  `valor_total` decimal(15,2) NOT NULL,
  `forma_pago` text DEFAULT NULL,
  `plazo_ejecucion` varchar(100) NOT NULL COMMENT 'Ej: 6 meses, 45 días',
  `plazo_ejecucion_real` varchar(100) DEFAULT NULL,
  `cdp` varchar(100) DEFAULT NULL,
  `rp` varchar(100) DEFAULT NULL,
  `rubro_presupuestal` varchar(255) DEFAULT NULL,
  `objeto_contrato` text NOT NULL,
  `fecha_elaboracion` date NOT NULL,
  `fecha_firma` date DEFAULT NULL,
  `id_supervisor` int(11) NOT NULL,
  `secretaria` varchar(150) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_reinicio` tinyint(1) DEFAULT 0,
  `tiene_cesion` tinyint(1) DEFAULT 0,
  `fecha_cesion` date DEFAULT NULL,
  `id_nuevo_contratista` int(11) DEFAULT NULL,
  `estado_contrato` enum('En Ejecución','Suspendido','Terminado','Liquidado') DEFAULT 'En Ejecución',
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `tiene_prorroga` tinyint(1) DEFAULT 0,
  `numero_prorroga` int(11) DEFAULT 0,
  `tiempo_prorroga` varchar(50) DEFAULT NULL,
  `tiene_suspension` tinyint(1) DEFAULT 0,
  `numero_suspension` int(11) DEFAULT 0,
  `duracion_suspension` varchar(50) DEFAULT NULL,
  `tiene_reinicio` tinyint(1) DEFAULT 0,
  `numero_reinicio` int(11) DEFAULT 0,
  `fecha_terminacion` date DEFAULT NULL,
  `fecha_terminacion_real` date DEFAULT NULL,
  `fecha_liquidacion` date DEFAULT NULL,
  `estado` enum('Borrador','Activo','Suspendido','Terminado','Liquidado') DEFAULT 'Borrador',
  `link_secop` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `contratos`
--

INSERT INTO `contratos` (`id_contrato`, `numero_contrato`, `bpin`, `linea_estrategica`, `id_contratista`, `tipo_contrato`, `modalidad_seleccion`, `fuente_recursos`, `valor_total`, `forma_pago`, `plazo_ejecucion`, `plazo_ejecucion_real`, `cdp`, `rp`, `rubro_presupuestal`, `objeto_contrato`, `fecha_elaboracion`, `fecha_firma`, `id_supervisor`, `secretaria`, `fecha_inicio`, `fecha_reinicio`, `tiene_cesion`, `fecha_cesion`, `id_nuevo_contratista`, `estado_contrato`, `creado_en`, `tiene_prorroga`, `numero_prorroga`, `tiempo_prorroga`, `tiene_suspension`, `numero_suspension`, `duracion_suspension`, `tiene_reinicio`, `numero_reinicio`, `fecha_terminacion`, `fecha_terminacion_real`, `fecha_liquidacion`, `estado`, `link_secop`) VALUES
(2, '007', NULL, NULL, 2, 'Prestación de Servicios', 'Contratación Directa', 'SGP', 20000000.00, NULL, '1 MESES', NULL, NULL, NULL, NULL, 'SECOP Y SIA', '2026-04-16', NULL, 1, 'Oficina Asesora Juridica', '2026-04-17', 0, 0, NULL, NULL, 'En Ejecución', '2026-04-19 05:14:50', 0, 0, NULL, 0, 0, NULL, 0, 0, NULL, NULL, NULL, 'Activo', NULL),
(8, '012-2026', '202600000050478', '1. Silvia un referente de turismo', 3, 'Prestación de Servicios', 'Contratación directa', 'SGP - Propósito General', 20400000.00, 'Actas mensuales', '180', NULL, '018', '021', '1.2.2.1.2', 'PRESTAR LOS SERVICIOS PROFESIONALES PARA BRINDAR APOYO TÉCNICO Y ADMINISTRATIVO EN LOS PROCESOS DE PLANEACIÓN, SEGUIMIENTO Y CONTROL PARA LA EJECUCIÓN DE LOS PROYECTOS DE INVERSIÓN A CARGO DE LA SECRETARÍA DE INFRAESTRUCTURA DE LA ALCALDÍA MUNICIPAL DE SILVIA CAUCA', '0000-00-00', '2026-01-16', 3, 'Secretaría de Infraestructura', '2026-01-16', 0, 0, NULL, NULL, 'En Ejecución', '2026-05-19 04:27:20', 0, 0, NULL, 0, 0, NULL, 0, 0, '2026-07-15', NULL, NULL, 'Activo', 'https://www.contratos.gov.co/entidades/entLogin.html');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `control_plataformas`
--

CREATE TABLE `control_plataformas` (
  `id_control` int(11) NOT NULL,
  `id_contrato` int(11) NOT NULL,
  `estado_secop` enum('Pendiente','En Borrador','Publicado') DEFAULT 'Pendiente',
  `url_secop` varchar(255) DEFAULT NULL,
  `estado_sia_observa` enum('Pendiente','Cargado','Con Observaciones') DEFAULT 'Pendiente',
  `observaciones` text DEFAULT NULL,
  `ultima_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `control_plataformas`
--

INSERT INTO `control_plataformas` (`id_control`, `id_contrato`, `estado_secop`, `url_secop`, `estado_sia_observa`, `observaciones`, `ultima_actualizacion`) VALUES
(1, 2, 'Publicado', '', 'Cargado', '', '2026-04-19 05:37:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presupuesto`
--

CREATE TABLE `presupuesto` (
  `id_presupuesto` int(11) NOT NULL,
  `id_contrato` int(11) NOT NULL,
  `rubros_presupuestales` text NOT NULL,
  `valor_asignado` decimal(15,2) NOT NULL,
  `numero_pagos_proyectados` int(11) NOT NULL,
  `numero_pagos_realizados` int(11) DEFAULT 0,
  `saldo_pendiente` decimal(15,2) NOT NULL,
  `ultima_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `presupuesto`
--

INSERT INTO `presupuesto` (`id_presupuesto`, `id_contrato`, `rubros_presupuestales`, `valor_asignado`, `numero_pagos_proyectados`, `numero_pagos_realizados`, `saldo_pendiente`, `ultima_actualizacion`) VALUES
(1, 2, '2.3.2.2.09', 20000000.00, 6, 1, 16666666.67, '2026-04-23 22:18:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(50) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre_rol`, `descripcion`) VALUES
(1, 'Administrador Contractual', 'Acceso total'),
(2, 'Financiero / Presupuesto', 'Edición financiera'),
(3, 'Consulta / Control Interno', 'Solo lectura'),
(4, 'Supervisor de Contrato', 'Edición de novedades');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `tipo_persona` enum('Natural','Jurídica') NOT NULL DEFAULT 'Natural',
  `tipo_documento` enum('CC','NIT','CE') NOT NULL DEFAULT 'CC',
  `documento` varchar(50) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `tipo_vinculacion` enum('Carrera Administrativa','Libre Nombramiento','Provisionalidad','Contratista de Prestación de Servicios') NOT NULL,
  `secretaria` varchar(150) NOT NULL,
  `correo` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL COMMENT 'Hash generado con password_hash()',
  `estado` enum('Activo','Inactivo') DEFAULT 'Activo',
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `id_rol`, `tipo_persona`, `tipo_documento`, `documento`, `nombres`, `apellidos`, `tipo_vinculacion`, `secretaria`, `correo`, `password`, `estado`, `creado_en`) VALUES
(1, 1, 'Natural', 'CC', '', 'Admin', 'Alcaldía Silvia', 'Carrera Administrativa', '', 'admin@silvia-cauca.gov.co', '$2y$10$NPJAJvuSawqZ/v6BNrWZOu2YDJ1Kr0oP05nlOnMtBbUvdQABsybAa', 'Activo', '2026-04-19 04:03:01'),
(3, 4, 'Natural', 'CC', '76332168', 'JULIO CESAR', 'ORTEGA GIRALDO', 'Libre Nombramiento', 'Secretaría de Infraestructura', 'secretariadeinfraestructura@silvia-cauca.gov.co', '$2y$10$bABtiGu2kZS5AcU.x8Kp9ucnDto.D7iHExVBunTAS0B0VlnrN.toC', 'Activo', '2026-04-23 22:05:14');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  ADD PRIMARY KEY (`id_auditoria`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `contratistas`
--
ALTER TABLE `contratistas`
  ADD PRIMARY KEY (`id_contratista`),
  ADD UNIQUE KEY `documento` (`documento`);

--
-- Indices de la tabla `contratos`
--
ALTER TABLE `contratos`
  ADD PRIMARY KEY (`id_contrato`),
  ADD UNIQUE KEY `numero_contrato` (`numero_contrato`),
  ADD KEY `id_contratista` (`id_contratista`),
  ADD KEY `id_supervisor` (`id_supervisor`),
  ADD KEY `fk_contrato_nuevo_contratista` (`id_nuevo_contratista`);

--
-- Indices de la tabla `control_plataformas`
--
ALTER TABLE `control_plataformas`
  ADD PRIMARY KEY (`id_control`),
  ADD UNIQUE KEY `id_contrato` (`id_contrato`);

--
-- Indices de la tabla `presupuesto`
--
ALTER TABLE `presupuesto`
  ADD PRIMARY KEY (`id_presupuesto`),
  ADD UNIQUE KEY `id_contrato` (`id_contrato`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`),
  ADD UNIQUE KEY `nombre_rol` (`nombre_rol`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD UNIQUE KEY `documento` (`documento`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  MODIFY `id_auditoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `contratistas`
--
ALTER TABLE `contratistas`
  MODIFY `id_contratista` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `contratos`
--
ALTER TABLE `contratos`
  MODIFY `id_contrato` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `control_plataformas`
--
ALTER TABLE `control_plataformas`
  MODIFY `id_control` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `presupuesto`
--
ALTER TABLE `presupuesto`
  MODIFY `id_presupuesto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `auditoria`
--
ALTER TABLE `auditoria`
  ADD CONSTRAINT `auditoria_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `contratos`
--
ALTER TABLE `contratos`
  ADD CONSTRAINT `contratos_ibfk_1` FOREIGN KEY (`id_contratista`) REFERENCES `contratistas` (`id_contratista`) ON UPDATE CASCADE,
  ADD CONSTRAINT `contratos_ibfk_2` FOREIGN KEY (`id_supervisor`) REFERENCES `usuarios` (`id_usuario`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_contrato_nuevo_contratista` FOREIGN KEY (`id_nuevo_contratista`) REFERENCES `contratistas` (`id_contratista`);

--
-- Filtros para la tabla `control_plataformas`
--
ALTER TABLE `control_plataformas`
  ADD CONSTRAINT `control_plataformas_ibfk_1` FOREIGN KEY (`id_contrato`) REFERENCES `contratos` (`id_contrato`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `presupuesto`
--
ALTER TABLE `presupuesto`
  ADD CONSTRAINT `presupuesto_ibfk_1` FOREIGN KEY (`id_contrato`) REFERENCES `contratos` (`id_contrato`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
