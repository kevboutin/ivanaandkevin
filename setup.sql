CREATE SCHEMA IF NOT EXISTS `weprovid_iandk`
	DEFAULT CHARACTER SET utf8;
USE `weprovid_iandk`;

CREATE TABLE IF NOT EXISTS `iandk_rsvp` (
	`email`     VARCHAR(255) NOT NULL,
	`name`      VARCHAR(255) NOT NULL,
	`attendees` INT(2)       NOT NULL DEFAULT 1,
	`created`   DATETIME     NOT NULL,
	`updated`   DATETIME     NOT NULL,
	PRIMARY KEY (`email`)
) 
ENGINE = InnoDB 
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `iandk_wishes` (
    `id`        INT(11)      NOT NULL AUTO_INCREMENT,
	`platform`  VARCHAR(255) NOT NULL,
	`name`      VARCHAR(255) NOT NULL,
	`message`   VARCHAR(255) NOT NULL,
	`created`   DATETIME     NOT NULL,
	`updated`   DATETIME     NOT NULL,
	PRIMARY KEY (`id`)
) 
ENGINE = InnoDB 
DEFAULT CHARACTER SET = utf8;
