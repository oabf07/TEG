-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.0.45 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.13.0.7147
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para teg_rio_cristal
CREATE DATABASE IF NOT EXISTS `teg_rio_cristal` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `teg_rio_cristal`;

-- Volcando estructura para tabla teg_rio_cristal.cargos
CREATE TABLE IF NOT EXISTS `cargos` (
  `id_cargo` int NOT NULL AUTO_INCREMENT,
  `id_socio` int NOT NULL,
  `id_periodo` int NOT NULL,
  `id_concepto` int NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha_generacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_cargo`),
  KEY `fk_cargos_concepto` (`id_concepto`),
  KEY `idx_cargos_socio` (`id_socio`),
  KEY `idx_cargos_periodo` (`id_periodo`),
  CONSTRAINT `fk_cargos_concepto` FOREIGN KEY (`id_concepto`) REFERENCES `conceptos_pago` (`id_concepto`),
  CONSTRAINT `fk_cargos_periodo` FOREIGN KEY (`id_periodo`) REFERENCES `periodos` (`id_periodo`),
  CONSTRAINT `fk_cargos_socio` FOREIGN KEY (`id_socio`) REFERENCES `socios` (`id_socio`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla teg_rio_cristal.cargos: ~21 rows (aproximadamente)
INSERT INTO `cargos` (`id_cargo`, `id_socio`, `id_periodo`, `id_concepto`, `monto`, `fecha_generacion`) VALUES
	(1, 1, 1, 1, 23.00, '2026-08-14 19:11:02'),
	(2, 2, 1, 1, 23.00, '2026-08-14 19:11:02'),
	(3, 3, 1, 1, 23.00, '2026-08-14 19:11:02'),
	(4, 4, 1, 1, 23.00, '2026-08-14 19:11:02'),
	(5, 5, 1, 1, 23.00, '2026-08-14 19:11:02'),
	(8, 1, 1, 2, 9.00, '2026-08-14 19:11:02'),
	(9, 2, 1, 2, 9.00, '2026-08-14 19:11:02'),
	(10, 3, 1, 2, 9.00, '2026-08-14 19:11:02'),
	(11, 4, 1, 2, 9.00, '2026-08-14 19:11:02'),
	(12, 5, 1, 2, 9.00, '2026-08-14 19:11:02'),
	(15, 1, 1, 3, 0.00, '2026-08-14 19:11:02'),
	(16, 2, 1, 3, 0.00, '2026-08-14 19:11:02'),
	(17, 3, 1, 3, 0.00, '2026-08-14 19:11:02'),
	(18, 4, 1, 3, 0.00, '2026-08-14 19:11:02'),
	(19, 5, 1, 3, 0.00, '2026-08-14 19:11:02'),
	(22, 1, 1, 4, 50.00, '2026-08-14 19:11:02'),
	(23, 1, 1, 5, 15.00, '2026-08-14 19:11:02'),
	(24, 2, 1, 5, 15.00, '2026-08-14 19:11:02'),
	(25, 3, 1, 5, 15.00, '2026-08-14 19:11:02'),
	(26, 4, 1, 5, 15.00, '2026-08-14 19:11:02'),
	(27, 5, 1, 5, 15.00, '2026-08-14 19:11:02');

-- Volcando estructura para tabla teg_rio_cristal.conceptos_pago
CREATE TABLE IF NOT EXISTS `conceptos_pago` (
  `id_concepto` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `tipo_concepto` enum('finanza','prestamo','cuota_extraordinaria','otro') NOT NULL,
  `monto_default` decimal(10,2) NOT NULL DEFAULT '0.00',
  `aplica_a` enum('todos','propietario','afiliado','suplente') NOT NULL DEFAULT 'todos',
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `fecha_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_concepto`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla teg_rio_cristal.conceptos_pago: ~5 rows (aproximadamente)
INSERT INTO `conceptos_pago` (`id_concepto`, `nombre`, `tipo_concepto`, `monto_default`, `aplica_a`, `activo`, `fecha_creacion`) VALUES
	(1, 'Finanza base', 'finanza', 23.00, 'todos', 1, '2026-08-14 19:11:02'),
	(2, 'Protector Choque', 'finanza', 9.00, 'todos', 1, '2026-08-14 19:11:02'),
	(3, 'Montepio', 'finanza', 0.00, 'todos', 1, '2026-08-14 19:11:02'),
	(4, 'Prestamo personal', 'prestamo', 50.00, 'propietario', 1, '2026-08-14 19:11:02'),
	(5, 'Cuota Asamblea Mayo', 'cuota_extraordinaria', 15.00, 'todos', 1, '2026-08-14 19:11:02');

-- Volcando estructura para tabla teg_rio_cristal.notificaciones_sms
CREATE TABLE IF NOT EXISTS `notificaciones_sms` (
  `id_notificacion` int NOT NULL AUTO_INCREMENT,
  `id_socio` int NOT NULL,
  `id_pago` int DEFAULT NULL,
  `mensaje` varchar(300) NOT NULL,
  `saldo_anterior` decimal(10,2) NOT NULL,
  `monto_pagado` decimal(10,2) NOT NULL,
  `saldo_nuevo` decimal(10,2) NOT NULL,
  `fecha_envio` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` enum('pendiente','enviado','fallido') NOT NULL DEFAULT 'pendiente',
  PRIMARY KEY (`id_notificacion`),
  KEY `fk_sms_socio` (`id_socio`),
  KEY `fk_sms_pago` (`id_pago`),
  CONSTRAINT `fk_sms_pago` FOREIGN KEY (`id_pago`) REFERENCES `pagos` (`id_pago`),
  CONSTRAINT `fk_sms_socio` FOREIGN KEY (`id_socio`) REFERENCES `socios` (`id_socio`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla teg_rio_cristal.notificaciones_sms: ~3 rows (aproximadamente)
INSERT INTO `notificaciones_sms` (`id_notificacion`, `id_socio`, `id_pago`, `mensaje`, `saldo_anterior`, `monto_pagado`, `saldo_nuevo`, `fecha_envio`, `estado`) VALUES
	(1, 1, 1, 'Debia $23.00, pago $23.00, resta $0.00', 23.00, 23.00, 0.00, '2026-08-14 19:11:02', 'pendiente'),
	(2, 1, 2, 'Debia $0.00, pago $20.00, resta $-20.00', 0.00, 20.00, -20.00, '2026-08-14 19:11:02', 'pendiente'),
	(3, 2, 3, 'Debia $23.00, pago $23.00, resta $0.00', 23.00, 23.00, 0.00, '2026-08-14 19:11:02', 'pendiente');

-- Volcando estructura para tabla teg_rio_cristal.pagos
CREATE TABLE IF NOT EXISTS `pagos` (
  `id_pago` int NOT NULL AUTO_INCREMENT,
  `id_cargo` int NOT NULL,
  `id_socio` int NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha_pago` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `referencia` varchar(50) DEFAULT NULL,
  `metodo_pago` enum('transferencia','pago_movil','efectivo','divisas') NOT NULL,
  `registrado_por` int NOT NULL,
  PRIMARY KEY (`id_pago`),
  KEY `fk_pagos_usuario` (`registrado_por`),
  KEY `idx_pagos_socio` (`id_socio`),
  KEY `idx_pagos_cargo` (`id_cargo`),
  CONSTRAINT `fk_pagos_cargo` FOREIGN KEY (`id_cargo`) REFERENCES `cargos` (`id_cargo`),
  CONSTRAINT `fk_pagos_socio` FOREIGN KEY (`id_socio`) REFERENCES `socios` (`id_socio`),
  CONSTRAINT `fk_pagos_usuario` FOREIGN KEY (`registrado_por`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla teg_rio_cristal.pagos: ~3 rows (aproximadamente)
INSERT INTO `pagos` (`id_pago`, `id_cargo`, `id_socio`, `monto`, `fecha_pago`, `referencia`, `metodo_pago`, `registrado_por`) VALUES
	(1, 1, 1, 23.00, '2026-08-14 19:11:02', 'REF-0001', 'pago_movil', 2),
	(2, 16, 1, 20.00, '2026-08-14 19:11:02', 'REF-0002', 'efectivo', 2),
	(3, 2, 2, 23.00, '2026-08-14 19:11:02', 'REF-0003', 'transferencia', 2);

-- Volcando estructura para tabla teg_rio_cristal.periodo_conceptos
CREATE TABLE IF NOT EXISTS `periodo_conceptos` (
  `id_periodo_concepto` int NOT NULL AUTO_INCREMENT,
  `id_periodo` int NOT NULL,
  `id_concepto` int NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_periodo_concepto`),
  UNIQUE KEY `uq_periodo_concepto` (`id_periodo`,`id_concepto`),
  KEY `fk_pc_concepto` (`id_concepto`),
  CONSTRAINT `fk_pc_concepto` FOREIGN KEY (`id_concepto`) REFERENCES `conceptos_pago` (`id_concepto`),
  CONSTRAINT `fk_pc_periodo` FOREIGN KEY (`id_periodo`) REFERENCES `periodos` (`id_periodo`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla teg_rio_cristal.periodo_conceptos: ~5 rows (aproximadamente)
INSERT INTO `periodo_conceptos` (`id_periodo_concepto`, `id_periodo`, `id_concepto`, `monto`) VALUES
	(1, 1, 1, 23.00),
	(2, 1, 2, 9.00),
	(3, 1, 3, 0.00),
	(4, 1, 4, 50.00),
	(5, 1, 5, 15.00);

-- Volcando estructura para tabla teg_rio_cristal.periodos
CREATE TABLE IF NOT EXISTS `periodos` (
  `id_periodo` int NOT NULL AUTO_INCREMENT,
  `mes` tinyint NOT NULL,
  `anio` smallint NOT NULL,
  `fecha_generacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `generado_por` int NOT NULL,
  `estado` enum('abierto','cerrado') NOT NULL DEFAULT 'abierto',
  PRIMARY KEY (`id_periodo`),
  UNIQUE KEY `uq_periodo` (`mes`,`anio`),
  KEY `fk_periodos_usuario` (`generado_por`),
  CONSTRAINT `fk_periodos_usuario` FOREIGN KEY (`generado_por`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla teg_rio_cristal.periodos: ~1 rows (aproximadamente)
INSERT INTO `periodos` (`id_periodo`, `mes`, `anio`, `fecha_generacion`, `generado_por`, `estado`) VALUES
	(1, 8, 2026, '2026-08-14 19:11:02', 1, 'abierto');

-- Volcando estructura para tabla teg_rio_cristal.socios
CREATE TABLE IF NOT EXISTS `socios` (
  `id_socio` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) NOT NULL,
  `nombre_completo` varchar(150) NOT NULL,
  `cedula` varchar(20) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `tipo` enum('propietario','afiliado','suplente') NOT NULL,
  `id_socio_titular` int DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `fecha_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_socio`),
  UNIQUE KEY `codigo` (`codigo`),
  UNIQUE KEY `cedula` (`cedula`),
  KEY `idx_socios_tipo` (`tipo`),
  KEY `idx_socios_titular` (`id_socio_titular`),
  CONSTRAINT `fk_socios_titular` FOREIGN KEY (`id_socio_titular`) REFERENCES `socios` (`id_socio`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla teg_rio_cristal.socios: ~5 rows (aproximadamente)
INSERT INTO `socios` (`id_socio`, `codigo`, `nombre_completo`, `cedula`, `telefono`, `tipo`, `id_socio_titular`, `activo`, `fecha_registro`) VALUES
	(1, 'S01', 'Juan Gonzalez', 'V-12345678', '04141234567', 'propietario', NULL, 1, '2026-08-14 19:11:02'),
	(2, 'S02', 'Pedro Martinez', 'V-23456789', '04241234567', 'propietario', NULL, 1, '2026-08-14 19:11:02'),
	(3, 'Afi01', 'Luis Fernandez', 'V-34567890', '04141112233', 'afiliado', NULL, 1, '2026-08-14 19:11:02'),
	(4, 'A01', 'Ana Torres', 'V-45678901', '04161112233', 'suplente', 1, 1, '2026-08-14 19:11:02'),
	(5, 'A02', 'Jose Ramirez', 'V-56789012', '04261112233', 'suplente', 2, 1, '2026-08-14 19:11:02');

-- Volcando estructura para tabla teg_rio_cristal.solvencias
CREATE TABLE IF NOT EXISTS `solvencias` (
  `id_solvencia` int NOT NULL AUTO_INCREMENT,
  `id_socio` int NOT NULL,
  `fecha_emision` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `emitido_por` int NOT NULL,
  `saldo_al_emitir` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id_solvencia`),
  KEY `fk_solvencias_socio` (`id_socio`),
  KEY `fk_solvencias_usuario` (`emitido_por`),
  CONSTRAINT `fk_solvencias_socio` FOREIGN KEY (`id_socio`) REFERENCES `socios` (`id_socio`),
  CONSTRAINT `fk_solvencias_usuario` FOREIGN KEY (`emitido_por`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla teg_rio_cristal.solvencias: ~0 rows (aproximadamente)

-- Volcando estructura para tabla teg_rio_cristal.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `nombre_completo` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `rol` enum('administrador','secretaria') NOT NULL,
  `creado_por` int DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `fecha_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `usuario` (`usuario`),
  KEY `fk_usuarios_creador` (`creado_por`),
  CONSTRAINT `fk_usuarios_creador` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla teg_rio_cristal.usuarios: ~2 rows (aproximadamente)
INSERT INTO `usuarios` (`id_usuario`, `nombre_completo`, `usuario`, `password_hash`, `rol`, `creado_por`, `activo`, `fecha_creacion`) VALUES
	(1, 'Carlos Rodriguez', 'admin', 'temporal123', 'administrador', NULL, 1, '2026-08-14 19:11:02'),
	(2, 'Maria Perez', 'mperez', 'temporal123', 'secretaria', 1, 1, '2026-08-14 19:11:02');

-- Volcando estructura para disparador teg_rio_cristal.trg_pagos_after_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `trg_pagos_after_insert` AFTER INSERT ON `pagos` FOR EACH ROW BEGIN
DECLARE v_deuda_cargo    DECIMAL(10,2);
    DECLARE v_pagado_total   DECIMAL(10,2);
    DECLARE v_saldo_anterior DECIMAL(10,2);
    DECLARE v_saldo_nuevo    DECIMAL(10,2);

    SELECT monto INTO v_deuda_cargo
    FROM cargos WHERE id_cargo = NEW.id_cargo;

    SELECT COALESCE(SUM(monto), 0) INTO v_pagado_total
    FROM pagos WHERE id_cargo = NEW.id_cargo;

    SET v_saldo_anterior = v_deuda_cargo - (v_pagado_total - NEW.monto);
    SET v_saldo_nuevo    = v_deuda_cargo - v_pagado_total;

    INSERT INTO notificaciones_sms
        (id_socio, id_pago, mensaje, saldo_anterior, monto_pagado, saldo_nuevo)
    VALUES (
        NEW.id_socio,
        NEW.id_pago,
        CONCAT('Debia $', v_saldo_anterior, ', pago $', NEW.monto, ', resta $', v_saldo_nuevo),
        v_saldo_anterior,
        NEW.monto,
        v_saldo_nuevo
    );
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
