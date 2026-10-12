-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-05-2026 a las 03:26:39
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
-- Base de datos: `ct_usm_postulaciones`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `pi_eliminar_postulacion` (IN `p_n_postulacion` INT)   BEGIN
    DELETE FROM post_inicia WHERE N_Postulacion = p_n_postulacion;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `p_region_performance` ()  COMMENT 'informacion sobre regiones para clientes' BEGIN
    SELECT 
        r.nombre_region AS Region, 
        COUNT(e.N_Postulacion) AS Total_Postulaciones
    FROM region r
    JOIN post_inicia e ON r.Id_region = e.Id_region_or
    JOIN empresa p ON e.rut_empresa_postu = p.Rut_Empresa
    GROUP BY r.Id_region
    ORDER BY Total_Postulaciones DESC; 
    SELECT 
        r.Nombre_Region AS Region_Impacto, 
        COUNT(p.N_Postulacion) AS Cantidad_Proyectos
    FROM region r
    JOIN post_inicia p ON r.Id_region = p.Id_region_imp
    GROUP BY r.Id_region
    ORDER BY Cantidad_Proyectos DESC;
END$$

--
-- Funciones
--
CREATE DEFINER=`root`@`localhost` FUNCTION `func_plazo_total_postulacion` (`p_N_postulacion` INT) RETURNS INT(11) DETERMINISTIC BEGIN
	DECLARE Plazo_semanas INT;
    SELECT SUM(e.Plazos_Crono) INTO Plazo_semanas
    FROM cronograma e
    WHERE e.N_Postulacion = p_N_postulacion;
    RETURN Plazo_semanas;
    END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cronograma`
--

CREATE TABLE `cronograma` (
  `N_Postulacion` int(11) NOT NULL,
  `Etapa_Crono` int(5) NOT NULL,
  `Plazos_Crono` int(11) NOT NULL,
  `Entregable_Crono` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cronograma`
--

INSERT INTO `cronograma` (`N_Postulacion`, `Etapa_Crono`, `Plazos_Crono`, `Entregable_Crono`) VALUES
(1, 1, 0, 'Ejemplo de cronograma'),
(2, 1, 0, 'Ejemplo de cronograma'),
(3, 1, 0, 'Ejemplo de cronograma'),
(4, 1, 0, 'Ejemplo de cronograma'),
(50, 1, 0, 'Ejemplo de cronograma'),
(51, 1, 0, 'Ejemplo de cronograma'),
(52, 1, 0, 'Ejemplo de cronograma'),
(53, 1, 0, 'Ejemplo de cronograma'),
(54, 1, 0, 'Ejemplo de cronograma'),
(55, 1, 0, 'Ejemplo de cronograma'),
(56, 1, 0, 'Ejemplo de cronograma'),
(57, 1, 0, 'Ejemplo de cronograma');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `Rut_Empresa` varchar(12) NOT NULL,
  `Nombre_Empresa` varchar(100) NOT NULL,
  `Repre_Empresa` varchar(100) NOT NULL,
  `Mail_Repre_Empresa` varchar(100) NOT NULL,
  `Telefono_Empresa` varchar(20) NOT NULL,
  `Convenio` tinyint(1) NOT NULL,
  `tam_empresa` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`Rut_Empresa`, `Nombre_Empresa`, `Repre_Empresa`, `Mail_Repre_Empresa`, `Telefono_Empresa`, `Convenio`, `tam_empresa`) VALUES
('75.888.999-5', 'Solar Valpo Tech', 'Darth Vader', 'info@solarvalpo.io', '+56 97 555 5555', 1, 1),
('76.222.333-K', 'Software del Sur', 'Emily Blunt', 'contacto@softwaresur.cl', '+56 99 222 2222', 1, 2),
('77.111.111-3', 'Cañerias Verdes', 'Orlando Bloom', 'CañeriasVerdes@gmail.com', '+56 80 111 1111', 1, 1),
('78.444.555-2', 'Ingeniería Vive', 'Gladiador', 'gerencia@ingenieriavive.cl', '+56 98 333 3333', 0, 1),
('79.123.456-7', 'Constructora Siglo XXI', 'Jack Sparrow', 'obras@constructorasiglo.cl', '+56 22 666 6666', 0, 2),
('80.666.777-4', 'Logística Valparaíso S.A.', 'Jake Sully', 'logistica_puerto@gmail.com', '+56 32 444 4444', 1, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_postulacion`
--

CREATE TABLE `estado_postulacion` (
  `Id_Estado` int(3) NOT NULL,
  `Nombre_Estado` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_postulacion`
--

INSERT INTO `estado_postulacion` (`Id_Estado`, `Nombre_Estado`) VALUES
(1, 'En Revision'),
(2, 'Aprobada'),
(3, 'Rechazada'),
(4, 'Cerrada'),
(5, 'Borrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `iniciativa`
--

CREATE TABLE `iniciativa` (
  `Id_iniciativa` int(9) NOT NULL,
  `Nombre_Iniciativa` varchar(255) NOT NULL,
  `Obj_iniciativa` text NOT NULL,
  `Des` text DEFAULT NULL,
  `Resultados_Iniciativa` text DEFAULT NULL,
  `Otros_doc` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `iniciativa`
--

INSERT INTO `iniciativa` (`Id_iniciativa`, `Nombre_Iniciativa`, `Obj_iniciativa`, `Des`, `Resultados_Iniciativa`, `Otros_doc`) VALUES
(1, 'Feria Software', 'Promover proyectos', 'Evento de tecnología', '10 proyectos', NULL),
(2, 'Campus Verde', 'Sustentabilidad', 'Reciclaje en USM', 'Menos basura', NULL),
(3, 'Charlas CT', 'Vinculación', 'Reuniones con empresas', 'Más prácticas', NULL),
(4, 'Robótica', 'I+D', 'Drones de rescate', '2 prototipos', NULL),
(5, 'Gestión Web', 'Digitalizar', 'Plataforma de procesos', 'Ahorro tiempo', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `int_eq`
--

CREATE TABLE `int_eq` (
  `N_Postulacion` int(11) NOT NULL,
  `Rut_int` varchar(12) NOT NULL,
  `Nombre_int` varchar(100) NOT NULL,
  `Departamento_int` varchar(100) NOT NULL,
  `Email_int` varchar(150) NOT NULL,
  `Telefono_int` varchar(20) NOT NULL,
  `Rol_int` int(3) NOT NULL,
  `sede_int` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `post_inicia`
--

CREATE TABLE `post_inicia` (
  `N_Postulacion` int(11) NOT NULL,
  `Codigo` int(10) NOT NULL,
  `Fecha_Post` date NOT NULL,
  `Responsable_1` varchar(100) NOT NULL,
  `Responsable_2` varchar(100) NOT NULL,
  `Presupuesto` decimal(15,2) NOT NULL,
  `id_inicia` int(9) NOT NULL,
  `Id_region_or` int(3) NOT NULL,
  `Id_region_imp` int(3) NOT NULL,
  `id_sede_postu` int(3) NOT NULL,
  `rut_empresa_postu` varchar(12) NOT NULL,
  `tipo_iniciativa_postu` int(3) NOT NULL,
  `id_estado_postu` int(3) NOT NULL,
  `rut_evaluador` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rut_responsable` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `post_inicia`
--

INSERT INTO `post_inicia` (`N_Postulacion`, `Codigo`, `Fecha_Post`, `Responsable_1`, `Responsable_2`, `Presupuesto`, `id_inicia`, `Id_region_or`, `Id_region_imp`, `id_sede_postu`, `rut_empresa_postu`, `tipo_iniciativa_postu`, `id_estado_postu`, `rut_evaluador`, `rut_responsable`) VALUES
(1, 1, '2026-05-11', 'Orlando Bloom', 'Ivan Admin', 1500000.00, 1, 4, 5, 1, '77.111.111-3', 1, 1, '22.222.222-2', '33.333.333-3'),
(2, 2, '2026-05-12', 'Silvia Suller', 'Juan Perez', 3000000.00, 5, 2, 13, 2, '76.222.333-K', 1, 2, '22.222.222-2', '33.333.333-3'),
(3, 3, '2026-05-13', 'Benjamin Vicuña', 'Maria Jara', 5000000.00, 2, 1, 5, 1, '75.888.999-5', 1, 2, '22.222.222-2', '33.333.333-3'),
(4, 4, '2026-05-14', 'Pedro Pascal', 'Ivan', 800000.00, 3, 3, 5, 3, '78.444.555-2', 1, 3, '44.444.444-4', '55.555.555-5'),
(50, 5, '2026-05-25', 'Esteban Dido', 'Ivan Admin', 1250000.00, 1, 5, 5, 1, '77.111.111-3', 2, 1, '22.222.222-2', '33.333.333-3'),
(51, 6, '2026-05-26', 'Elena Nito', 'Juan Perez', 4500000.00, 2, 13, 13, 2, '76.222.333-K', 2, 2, '44.444.444-4', '55.555.555-5'),
(52, 7, '2026-05-27', 'Aquiles Brinco', '', 900000.00, 3, 5, 13, 3, '75.888.999-5', 2, 5, '77.777.777-7', '33.333.333-3'),
(53, 8, '2026-05-28', 'Rosa Melano', 'Sofia Henriquez', 6200000.00, 4, 5, 8, 4, '80.666.777-4', 1, 1, '77.777.777-7', '66.666.666-6'),
(54, 9, '2026-05-29', 'Armando Casas', 'Roberto Valdes', 1750000.00, 5, 2, 2, 2, '78.444.555-2', 2, 3, '88.888.888-8', '33.333.333-3'),
(55, 10, '2026-05-30', 'Luz Cuesta', 'Camila Orellana', 3100000.00, 1, 5, 5, 1, '79.123.456-7', 2, 2, '22.222.222-2', '33.333.333-3'),
(56, 11, '2026-05-31', 'Alan Brito', 'Claudio Campusano', 2800000.00, 2, 4, 5, 4, '76.222.333-K', 2, 1, '44.444.444-4', '66.666.666-6'),
(57, 12, '2026-06-01', 'Zacarias Flores', 'Ivan', 9500000.00, 4, 5, 13, 1, '80.666.777-4', 2, 2, '22.222.222-2', '55.555.555-5');

--
-- Disparadores `post_inicia`
--
DELIMITER $$
CREATE TRIGGER `tg_crear_etapa_inicial` AFTER INSERT ON `post_inicia` FOR EACH ROW BEGIN
    INSERT INTO cronograma (N_Postulacion, Etapa_Crono, Entregable_Crono, Plazos_Crono)
    VALUES (NEW.N_Postulacion, 1, "Ejemplo de cronograma", 0);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tg_verificar_tipo_iniciativa` BEFORE INSERT ON `post_inicia` FOR EACH ROW BEGIN
    DECLARE total_existentes INT;
    SELECT COUNT(*) INTO total_existentes 
    FROM post_inicia 
    WHERE id_inicia = NEW.id_inicia;

    IF total_existentes > 0 THEN
        SET NEW.tipo_iniciativa_postu = 2;
    ELSE
        SET NEW.tipo_iniciativa_postu = 1;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `region`
--

CREATE TABLE `region` (
  `Id_region` int(3) NOT NULL,
  `Nombre_Region` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `region`
--

INSERT INTO `region` (`Id_region`, `Nombre_Region`) VALUES
(1, 'Tarapaca'),
(2, 'Antofagasta'),
(3, 'Atacama'),
(4, 'Coquimbo'),
(5, 'Valparaiso'),
(6, 'OHiggins'),
(7, 'Maule'),
(8, 'BioBio'),
(9, 'Araucania'),
(10, 'Los Lagos'),
(11, 'Aysen'),
(12, 'Magallanes y Antarti'),
(13, 'Metropolitana de San'),
(14, 'Los Rios'),
(15, 'Arica-Parinacota'),
(16, 'Nuble');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `Id_rol` int(3) NOT NULL,
  `Nombre_rol` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`Id_rol`, `Nombre_rol`) VALUES
(1, 'Estudiante'),
(2, 'Profesor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sede`
--

CREATE TABLE `sede` (
  `id_sede` int(3) NOT NULL,
  `Nombre_sede` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sede`
--

INSERT INTO `sede` (`id_sede`, `Nombre_sede`) VALUES
(1, 'Campus Casa Central '),
(2, 'Campus San Joaquin'),
(3, 'Campus Vitacura'),
(4, 'Sede Viña del Mar'),
(5, 'Sede Concepcion');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tamaño_empresa`
--

CREATE TABLE `tamaño_empresa` (
  `Id_tam` int(3) NOT NULL,
  `Tamaño_tipo` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tamaño_empresa`
--

INSERT INTO `tamaño_empresa` (`Id_tam`, `Tamaño_tipo`) VALUES
(1, 'Microempresa'),
(2, 'Mediana'),
(3, 'Grande');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_inicia`
--

CREATE TABLE `tipo_inicia` (
  `Id_tipo` int(3) NOT NULL,
  `N/E` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_inicia`
--

INSERT INTO `tipo_inicia` (`Id_tipo`, `N/E`) VALUES
(1, 'Nueva'),
(2, 'Existente');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_administrador_asignaciones`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_administrador_asignaciones` (
`postulacion_id` int(11)
,`postulacion_codigo` int(10)
,`iniciativa_nombre` varchar(255)
,`empresa_externa` varchar(100)
,`postulacion_estado` varchar(20)
,`fecha_postulacion` date
,`evaluador_nombre` varchar(50)
,`evaluador_rut` varchar(12)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_postulaciones_aceptadas`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_postulaciones_aceptadas` (
`Numero_Postulacion` int(11)
,`Nombre_Iniciativa` varchar(255)
,`Obj_iniciativa` text
,`Region_Origen` varchar(20)
,`Region_Impacto` varchar(20)
,`Nombre_Estado` varchar(20)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_postulaciones_gestionadas`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_postulaciones_gestionadas` (
`N_Postulacion` int(11)
,`id_inicia` int(9)
,`id_sede_postu` int(3)
,`id_estado_postu` int(3)
,`id_region_or` int(3)
,`id_region_imp` int(3)
,`Presupuesto` decimal(15,2)
,`rut_responsable` varchar(12)
,`rut_evaluador` varchar(12)
,`Nombre_Empresa` varchar(100)
,`Nombre_Iniciativa` varchar(255)
,`Nombre_sede` varchar(20)
,`Region Origen` varchar(20)
,`Region Impacto` varchar(20)
,`Nombre_Estado` varchar(20)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `v_postulaciones_evaluador`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `v_postulaciones_evaluador` (
`N_Postulacion` int(11)
,`id_inicia` int(9)
,`id_sede_postu` int(3)
,`id_estado_postu` int(3)
,`Presupuesto` decimal(15,2)
,`rut_evaluador` varchar(12)
,`nombre_empresa` varchar(100)
,`nombre_iniciativa` varchar(255)
,`nombre_sede` varchar(20)
,`Region_Origen` varchar(20)
,`Region_Impacto` varchar(20)
,`nombre_estado` varchar(20)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `web_usuarios`
--

CREATE TABLE `web_usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `rut` varchar(12) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `cargo` enum('Responsable Academico','Evaluador CT_USM','Administrador CT_USM') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `web_usuarios`
--

INSERT INTO `web_usuarios` (`id_usuario`, `nombre`, `rut`, `contrasena`, `cargo`) VALUES
(1, 'Ivan', '11.111.111-1', '1234', 'Administrador CT_USM'),
(2, 'Renato', '22.222.222-2', 'eval123', 'Evaluador CT_USM'),
(3, 'Donkam', '33.333.333-3', '123', 'Responsable Academico'),
(4, 'Juan', '44.444.444-4', '1111', 'Evaluador CT_USM'),
(5, 'Alfonso', '55.555.555-5', '4321', 'Responsable Academico'),
(12, 'Mauricio', '66.666.666-6', 'pass789', 'Responsable Academico'),
(13, 'Laura', '77.777.777-7', 'eval1', 'Evaluador CT_USM'),
(14, 'pablo', '88.888.888-8', 'eval2', 'Evaluador CT_USM'),
(15, 'Ana', '99.999.999-9', 'admin99', 'Administrador CT_USM'),
(16, 'Carlos', '1.111.111-1', 'pass123', 'Responsable Academico');

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_administrador_asignaciones`
--
DROP TABLE IF EXISTS `vista_administrador_asignaciones`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_administrador_asignaciones`  AS SELECT `p`.`N_Postulacion` AS `postulacion_id`, `p`.`Codigo` AS `postulacion_codigo`, `i`.`Nombre_Iniciativa` AS `iniciativa_nombre`, `emp`.`Nombre_Empresa` AS `empresa_externa`, `e`.`Nombre_Estado` AS `postulacion_estado`, `p`.`Fecha_Post` AS `fecha_postulacion`, `u`.`nombre` AS `evaluador_nombre`, `p`.`rut_evaluador` AS `evaluador_rut` FROM ((((`post_inicia` `p` left join `iniciativa` `i` on(`p`.`id_inicia` = `i`.`Id_iniciativa`)) left join `empresa` `emp` on(`p`.`rut_empresa_postu` = `emp`.`Rut_Empresa`)) left join `estado_postulacion` `e` on(`p`.`id_estado_postu` = `e`.`Id_Estado`)) left join `web_usuarios` `u` on(`p`.`rut_evaluador` = `u`.`rut` and `u`.`cargo` = 'Evaluador CT_USM')) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_postulaciones_aceptadas`
--
DROP TABLE IF EXISTS `vista_postulaciones_aceptadas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_postulaciones_aceptadas`  AS SELECT `p`.`N_Postulacion` AS `Numero_Postulacion`, `i`.`Nombre_Iniciativa` AS `Nombre_Iniciativa`, `i`.`Obj_iniciativa` AS `Obj_iniciativa`, `r1`.`Nombre_Region` AS `Region_Origen`, `r2`.`Nombre_Region` AS `Region_Impacto`, `e`.`Nombre_Estado` AS `Nombre_Estado` FROM ((((`post_inicia` `p` join `iniciativa` `i` on(`p`.`id_inicia` = `i`.`Id_iniciativa`)) join `region` `r1` on(`p`.`Id_region_or` = `r1`.`Id_region`)) join `region` `r2` on(`p`.`Id_region_imp` = `r2`.`Id_region`)) join `estado_postulacion` `e` on(`p`.`id_estado_postu` = `e`.`Id_Estado`)) WHERE `e`.`Id_Estado` = 2 ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_postulaciones_gestionadas`
--
DROP TABLE IF EXISTS `vista_postulaciones_gestionadas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_postulaciones_gestionadas`  AS SELECT `p`.`N_Postulacion` AS `N_Postulacion`, `p`.`id_inicia` AS `id_inicia`, `p`.`id_sede_postu` AS `id_sede_postu`, `p`.`id_estado_postu` AS `id_estado_postu`, `p`.`Id_region_or` AS `id_region_or`, `p`.`Id_region_imp` AS `id_region_imp`, `p`.`Presupuesto` AS `Presupuesto`, `p`.`rut_responsable` AS `rut_responsable`, `p`.`rut_evaluador` AS `rut_evaluador`, `emp`.`Nombre_Empresa` AS `Nombre_Empresa`, `i`.`Nombre_Iniciativa` AS `Nombre_Iniciativa`, `s`.`Nombre_sede` AS `Nombre_sede`, `reg_or`.`Nombre_Region` AS `Region Origen`, `reg_imp`.`Nombre_Region` AS `Region Impacto`, `e`.`Nombre_Estado` AS `Nombre_Estado` FROM ((((((`post_inicia` `p` left join `estado_postulacion` `e` on(`p`.`id_estado_postu` = `e`.`Id_Estado`)) left join `empresa` `emp` on(`p`.`rut_empresa_postu` = `emp`.`Rut_Empresa`)) left join `iniciativa` `i` on(`p`.`id_inicia` = `i`.`Id_iniciativa`)) left join `sede` `s` on(`p`.`id_sede_postu` = `s`.`id_sede`)) left join `region` `reg_or` on(`p`.`Id_region_or` = `reg_or`.`Id_region`)) left join `region` `reg_imp` on(`p`.`Id_region_imp` = `reg_imp`.`Id_region`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `v_postulaciones_evaluador`
--
DROP TABLE IF EXISTS `v_postulaciones_evaluador`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_postulaciones_evaluador`  AS SELECT `p`.`N_Postulacion` AS `N_Postulacion`, `p`.`id_inicia` AS `id_inicia`, `p`.`id_sede_postu` AS `id_sede_postu`, `p`.`id_estado_postu` AS `id_estado_postu`, `p`.`Presupuesto` AS `Presupuesto`, `p`.`rut_evaluador` AS `rut_evaluador`, `emp`.`Nombre_Empresa` AS `nombre_empresa`, `ini`.`Nombre_Iniciativa` AS `nombre_iniciativa`, `s`.`Nombre_sede` AS `nombre_sede`, `reg_or`.`Nombre_Region` AS `Region_Origen`, `reg_imp`.`Nombre_Region` AS `Region_Impacto`, `e`.`Nombre_Estado` AS `nombre_estado` FROM ((((((`post_inicia` `p` left join `estado_postulacion` `e` on(`p`.`id_estado_postu` = `e`.`Id_Estado`)) left join `empresa` `emp` on(`p`.`rut_empresa_postu` = `emp`.`Rut_Empresa`)) left join `iniciativa` `ini` on(`p`.`id_inicia` = `ini`.`Id_iniciativa`)) left join `sede` `s` on(`p`.`id_sede_postu` = `s`.`id_sede`)) left join `region` `reg_or` on(`p`.`Id_region_or` = `reg_or`.`Id_region`)) left join `region` `reg_imp` on(`p`.`Id_region_imp` = `reg_imp`.`Id_region`)) ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cronograma`
--
ALTER TABLE `cronograma`
  ADD PRIMARY KEY (`N_Postulacion`,`Etapa_Crono`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`Rut_Empresa`),
  ADD KEY `fk_tam_empresa` (`tam_empresa`),
  ADD KEY `Rut_Empresa` (`Rut_Empresa`);

--
-- Indices de la tabla `estado_postulacion`
--
ALTER TABLE `estado_postulacion`
  ADD PRIMARY KEY (`Id_Estado`),
  ADD KEY `Id_Estado` (`Id_Estado`);

--
-- Indices de la tabla `iniciativa`
--
ALTER TABLE `iniciativa`
  ADD PRIMARY KEY (`Id_iniciativa`),
  ADD KEY `Id_iniciativa` (`Id_iniciativa`);

--
-- Indices de la tabla `int_eq`
--
ALTER TABLE `int_eq`
  ADD PRIMARY KEY (`N_Postulacion`,`Rut_int`),
  ADD KEY `fk_rol` (`Rol_int`),
  ADD KEY `fk_sede_int` (`sede_int`);

--
-- Indices de la tabla `post_inicia`
--
ALTER TABLE `post_inicia`
  ADD PRIMARY KEY (`N_Postulacion`),
  ADD KEY `N_Postulacion` (`N_Postulacion`),
  ADD KEY `fk_region_origen` (`Id_region_or`),
  ADD KEY `fk_region_impacto` (`Id_region_imp`),
  ADD KEY `fk_sede` (`id_sede_postu`),
  ADD KEY `fk_tipo_ini` (`tipo_iniciativa_postu`),
  ADD KEY `fk_estado` (`id_estado_postu`),
  ADD KEY `fk_id_iniciativa` (`id_inicia`),
  ADD KEY `fk_rut_empresa` (`rut_empresa_postu`),
  ADD KEY `rut_evaluador` (`rut_evaluador`),
  ADD KEY `rut_responsable` (`rut_responsable`),
  ADD KEY `rut_responsable_2` (`rut_responsable`);

--
-- Indices de la tabla `region`
--
ALTER TABLE `region`
  ADD PRIMARY KEY (`Id_region`),
  ADD KEY `Id_region` (`Id_region`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`Id_rol`),
  ADD KEY `Id_rol` (`Id_rol`);

--
-- Indices de la tabla `sede`
--
ALTER TABLE `sede`
  ADD PRIMARY KEY (`id_sede`),
  ADD KEY `id_sede` (`id_sede`);

--
-- Indices de la tabla `tamaño_empresa`
--
ALTER TABLE `tamaño_empresa`
  ADD PRIMARY KEY (`Id_tam`),
  ADD KEY `Id_tam` (`Id_tam`);

--
-- Indices de la tabla `tipo_inicia`
--
ALTER TABLE `tipo_inicia`
  ADD PRIMARY KEY (`Id_tipo`),
  ADD KEY `Id_tipo` (`Id_tipo`);

--
-- Indices de la tabla `web_usuarios`
--
ALTER TABLE `web_usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `rut` (`rut`),
  ADD KEY `rut_2` (`rut`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `iniciativa`
--
ALTER TABLE `iniciativa`
  MODIFY `Id_iniciativa` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `post_inicia`
--
ALTER TABLE `post_inicia`
  MODIFY `N_Postulacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de la tabla `region`
--
ALTER TABLE `region`
  MODIFY `Id_region` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `Id_rol` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `sede`
--
ALTER TABLE `sede`
  MODIFY `id_sede` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tamaño_empresa`
--
ALTER TABLE `tamaño_empresa`
  MODIFY `Id_tam` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tipo_inicia`
--
ALTER TABLE `tipo_inicia`
  MODIFY `Id_tipo` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `web_usuarios`
--
ALTER TABLE `web_usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cronograma`
--
ALTER TABLE `cronograma`
  ADD CONSTRAINT `fk_n_post` FOREIGN KEY (`N_Postulacion`) REFERENCES `post_inicia` (`N_Postulacion`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD CONSTRAINT `fk_tam_empresa` FOREIGN KEY (`tam_empresa`) REFERENCES `tamaño_empresa` (`Id_tam`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `int_eq`
--
ALTER TABLE `int_eq`
  ADD CONSTRAINT `fk_n_post2` FOREIGN KEY (`N_Postulacion`) REFERENCES `post_inicia` (`N_Postulacion`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rol` FOREIGN KEY (`Rol_int`) REFERENCES `rol` (`Id_rol`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sede_int` FOREIGN KEY (`sede_int`) REFERENCES `sede` (`id_sede`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `post_inicia`
--
ALTER TABLE `post_inicia`
  ADD CONSTRAINT `fk_estado` FOREIGN KEY (`id_estado_postu`) REFERENCES `estado_postulacion` (`Id_Estado`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_evaluador_asignado` FOREIGN KEY (`rut_evaluador`) REFERENCES `web_usuarios` (`rut`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_id_iniciativa` FOREIGN KEY (`id_inicia`) REFERENCES `iniciativa` (`Id_iniciativa`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_region_impacto` FOREIGN KEY (`Id_region_imp`) REFERENCES `region` (`Id_region`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_region_origen` FOREIGN KEY (`Id_region_or`) REFERENCES `region` (`Id_region`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_responsable_asignado` FOREIGN KEY (`rut_responsable`) REFERENCES `web_usuarios` (`rut`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rut_empresa` FOREIGN KEY (`rut_empresa_postu`) REFERENCES `empresa` (`Rut_Empresa`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sede` FOREIGN KEY (`id_sede_postu`) REFERENCES `sede` (`id_sede`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tipo_ini` FOREIGN KEY (`tipo_iniciativa_postu`) REFERENCES `tipo_inicia` (`Id_tipo`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
