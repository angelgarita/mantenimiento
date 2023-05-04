CREATE TABLE IF NOT EXISTS `#__mantenimiento_mapa` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`zoom` INT NOT NULL  DEFAULT 8,
`latitud` FLOAT NOT NULL ,
`longitud` FLOAT NOT NULL ,
`ancho` INT NOT NULL  DEFAULT 800,
`alto` INT NOT NULL  DEFAULT 800,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE INDEX `#__mantenimiento_mapa_zoom` ON `#__mantenimiento_mapa`(`zoom`);

INSERT INTO `#__mantenimiento_mapa` (`id`, `zoom`, `latitud`, `longitud`, `ancho`, `alto`) VALUES (1, 6, 40.3, -3.7, 800, 800);

CREATE TABLE IF NOT EXISTS `#__mantenimientos` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`ind_estacion` VARCHAR(5) NOT NULL,
	`fecha` DATE NOT NULL,
	`tecnicos` TEXT NOT NULL,
	`actuacion` TEXT NOT NULL,
	`comentarios` TEXT NULL DEFAULT NULL,
	`estado` VARCHAR(1) NOT NULL,
	`ultimo` TINYINT(1) NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8mb3_general_ci';

CREATE TABLE IF NOT EXISTS `#__estaciones` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`nombre` VARCHAR(100) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`ind_sinoptico` VARCHAR(5) NULL DEFAULT NULL,
	`ind_climatologico` VARCHAR(5) NOT NULL DEFAULT '',
	`tipo_estacion` VARCHAR(10) NULL DEFAULT NULL,
	`variables` VARCHAR(25) NULL DEFAULT NULL,
	`tipo_mant` VARCHAR(1) NOT NULL,
	`provincia` VARCHAR(10) NOT NULL,
	`latitud` DECIMAL(10,8) NOT NULL,
	`longitud` DECIMAL(11,9) NOT NULL,
	`altitud` INT(4) NULL DEFAULT NULL,
	`geografica` VARCHAR(10) NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8mb3_general_ci';
