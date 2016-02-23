-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         5.6.20 - MySQL Community Server (GPL)
-- SO del servidor:              Win32
-- HeidiSQL Versión:             9.2.0.4947
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Volcando estructura para tabla tatu_carreta.categoria_lang
DROP TABLE IF EXISTS `categoria_lang`;
CREATE TABLE IF NOT EXISTS `categoria_lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria_id` int(11) NOT NULL,
  `lang_id` int(11) NOT NULL,
  `nombre` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_carga` datetime NOT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  `fecha_baja` datetime DEFAULT NULL,
  `usuario_id_carga` int(11) NOT NULL,
  `usuario_id_baja` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_categoria_lang_categoria` (`categoria_id`),
  KEY `FK_categoria_lang_lang` (`lang_id`),
  KEY `url` (`url`(255)),
  CONSTRAINT `FK_categoria_lang_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`),
  CONSTRAINT `FK_categoria_lang_lang` FOREIGN KEY (`lang_id`) REFERENCES `lang` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla tatu_carreta.html_lang
DROP TABLE IF EXISTS `html_lang`;
CREATE TABLE IF NOT EXISTS `html_lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `html_id` int(11) NOT NULL,
  `lang_id` int(11) NOT NULL,
  `cuerpo` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `FK_html_lang_html` (`html_id`),
  KEY `FK_html_lang_lang` (`lang_id`),
  CONSTRAINT `FK_html_lang_html` FOREIGN KEY (`html_id`) REFERENCES `html` (`id`),
  CONSTRAINT `FK_html_lang_lang` FOREIGN KEY (`lang_id`) REFERENCES `lang` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla tatu_carreta.imagen_lang
DROP TABLE IF EXISTS `imagen_lang`;
CREATE TABLE IF NOT EXISTS `imagen_lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `imagen_id` int(11) NOT NULL,
  `lang_id` int(11) NOT NULL,
  `epigrafe` mediumtext COLLATE utf8_unicode_ci,
  `estado` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_carga` datetime NOT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  `fecha_baja` datetime DEFAULT NULL,
  `usuario_id_carga` int(11) NOT NULL,
  `usuario_id_baja` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_imagen_lang_imagen` (`imagen_id`),
  KEY `FK_imagen_lang_lang` (`lang_id`),
  CONSTRAINT `FK_imagen_lang_imagen` FOREIGN KEY (`imagen_id`) REFERENCES `imagen` (`id`),
  CONSTRAINT `FK_imagen_lang_lang` FOREIGN KEY (`lang_id`) REFERENCES `lang` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla tatu_carreta.item_lang
DROP TABLE IF EXISTS `item_lang`;
CREATE TABLE IF NOT EXISTS `item_lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL DEFAULT '0',
  `lang_id` int(11) NOT NULL DEFAULT '0',
  `titulo` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `descripcion` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_carga` datetime NOT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  `fecha_baja` datetime DEFAULT NULL,
  `usuario_id_carga` int(11) NOT NULL,
  `usuario_id_baja` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `url` (`url`(255)),
  KEY `FK_item_lang_item` (`item_id`),
  KEY `FK_item_lang_lang` (`lang_id`),
  CONSTRAINT `FK_item_lang_item` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`),
  CONSTRAINT `FK_item_lang_lang` FOREIGN KEY (`lang_id`) REFERENCES `lang` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla tatu_carreta.lang
DROP TABLE IF EXISTS `lang`;
CREATE TABLE IF NOT EXISTS `lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `nombre` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_carga` datetime NOT NULL,
  `fecha_baja` datetime DEFAULT NULL,
  `usuario_id_carga` int(11) NOT NULL,
  `usuario_id_baja` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `codigo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla tatu_carreta.menu_lang
DROP TABLE IF EXISTS `menu_lang`;
CREATE TABLE IF NOT EXISTS `menu_lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `lang_id` int(11) NOT NULL,
  `nombre` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_carga` datetime NOT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  `fecha_baja` datetime DEFAULT NULL,
  `usuario_id_carga` int(11) NOT NULL,
  `usuario_id_baja` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `url` (`url`(255)),
  KEY `FK_menu_lang_menu` (`menu_id`),
  KEY `FK_menu_lang_lang` (`lang_id`),
  CONSTRAINT `FK_menu_lang_lang` FOREIGN KEY (`lang_id`) REFERENCES `lang` (`id`),
  CONSTRAINT `FK_menu_lang_menu` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla tatu_carreta.muestra_lang
DROP TABLE IF EXISTS `muestra_lang`;
CREATE TABLE IF NOT EXISTS `muestra_lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `muestra_id` int(11) NOT NULL,
  `lang_id` int(11) NOT NULL,
  `cuerpo` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `FK_muestra_lang_muestra` (`muestra_id`),
  KEY `FK_muestra_lang_lang` (`lang_id`),
  CONSTRAINT `FK_muestra_lang_lang` FOREIGN KEY (`lang_id`) REFERENCES `lang` (`id`),
  CONSTRAINT `FK_muestra_lang_muestra` FOREIGN KEY (`muestra_id`) REFERENCES `muestra` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla tatu_carreta.portfolio_completo_lang
DROP TABLE IF EXISTS `portfolio_completo_lang`;
CREATE TABLE IF NOT EXISTS `portfolio_completo_lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `portfolio_completo_id` int(11) NOT NULL,
  `lang_id` int(11) NOT NULL,
  `cuerpo` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `FK_portfolio_completo_lang_portfolio_completo` (`portfolio_completo_id`),
  KEY `FK_portfolio_completo_lang_lang` (`lang_id`),
  CONSTRAINT `FK_portfolio_completo_lang_lang` FOREIGN KEY (`lang_id`) REFERENCES `lang` (`id`),
  CONSTRAINT `FK_portfolio_completo_lang_portfolio_completo` FOREIGN KEY (`portfolio_completo_id`) REFERENCES `portfolio_completo` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla tatu_carreta.producto_lang
DROP TABLE IF EXISTS `producto_lang`;
CREATE TABLE IF NOT EXISTS `producto_lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `producto_id` int(11) NOT NULL,
  `lang_id` int(11) NOT NULL,
  `cuerpo` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `FK_producto_lang_producto` (`producto_id`),
  KEY `FK_producto_lang_lang` (`lang_id`),
  CONSTRAINT `FK_producto_lang_lang` FOREIGN KEY (`lang_id`) REFERENCES `lang` (`id`),
  CONSTRAINT `FK_producto_lang_producto` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla tatu_carreta.seccion_lang
DROP TABLE IF EXISTS `seccion_lang`;
CREATE TABLE IF NOT EXISTS `seccion_lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `seccion_id` int(11) NOT NULL,
  `lang_id` int(11) NOT NULL,
  `titulo` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_carga` datetime NOT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  `fecha_baja` datetime DEFAULT NULL,
  `usuario_id_carga` int(11) NOT NULL,
  `usuario_id_baja` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_seccion_lang_seccion` (`seccion_id`),
  KEY `FK_seccion_lang_lang` (`lang_id`),
  CONSTRAINT `FK_seccion_lang_lang` FOREIGN KEY (`lang_id`) REFERENCES `lang` (`id`),
  CONSTRAINT `FK_seccion_lang_seccion` FOREIGN KEY (`seccion_id`) REFERENCES `seccion` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- La exportación de datos fue deseleccionada.


-- Volcando estructura para tabla tatu_carreta.texto_lang
DROP TABLE IF EXISTS `texto_lang`;
CREATE TABLE IF NOT EXISTS `texto_lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `texto_id` int(11) NOT NULL,
  `lang_id` int(11) NOT NULL,
  `cuerpo` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `FK_texto_lang_texto` (`texto_id`),
  KEY `FK_texto_lang_lang` (`lang_id`),
  CONSTRAINT `FK_texto_lang_lang` FOREIGN KEY (`lang_id`) REFERENCES `lang` (`id`),
  CONSTRAINT `FK_texto_lang_texto` FOREIGN KEY (`texto_id`) REFERENCES `texto` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- La exportación de datos fue deseleccionada.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
