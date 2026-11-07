SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS `incidencias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `empresa` varchar(50) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `enviado` tinyint(1) NOT NULL DEFAULT 0,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

