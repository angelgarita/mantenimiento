CREATE TABLE IF NOT EXISTS `#__estacion` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`nombre` VARCHAR(255)  NOT NULL ,
`ind_sinoptico` VARCHAR(255)  NULL  DEFAULT "",
`ind_climatologico` VARCHAR(255)  NOT NULL ,
`tipo_estacion` VARCHAR(255)  NOT NULL ,
`variables` VARCHAR(255)  NULL  DEFAULT "",
`tipo_mant` VARCHAR(255)  NULL  DEFAULT "",
`provincia` VARCHAR(255)  NOT NULL ,
`latitud` DECIMAL NOT NULL ,
`longitud` DECIMAL NOT NULL ,
`altitud` INT NOT NULL ,
`geografica` VARCHAR(255)  NULL  DEFAULT "",
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE INDEX `#__estacion_altitud` ON `#__estacion`(`altitud`);

