-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema happydev
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema happydev
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `happydev` DEFAULT CHARACTER SET latin1 ;
USE `happydev` ;

-- -----------------------------------------------------
-- Table `happydev`.`project`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `happydev`.`project` (
  `id` VARCHAR(36) COLLATE 'utf8mb4_unicode_ci' NOT NULL,
  `name` VARCHAR(80) COLLATE 'utf8mb4_unicode_ci' NOT NULL,
  `url_documentation` VARCHAR(255) COLLATE 'utf8mb4_unicode_ci' NOT NULL,
  `date_creation` DATETIME NOT NULL,
  `date_update` DATETIME NOT NULL,
  `date_deleted` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`, `name`, `url_documentation`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `happydev`.`documentation`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `happydev`.`documentation` (
  `id` VARCHAR(36) COLLATE 'utf8mb4_unicode_ci' NOT NULL,
  `version` VARCHAR(36) COLLATE 'utf8mb4_unicode_ci' NOT NULL,
  `path` VARCHAR(255) COLLATE 'utf8mb4_unicode_ci' NOT NULL,
  `project_id` VARCHAR(36) COLLATE 'utf8mb4_unicode_ci' NOT NULL,
  `date_creation` DATETIME NOT NULL,
  `date_update` DATETIME NOT NULL,
  `date_deleted` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`, `version`, `path`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `IDX_73D5A93B166D1F9C` (`project_id` ASC),
  CONSTRAINT `FK_73D5A93B166D1F9C`
    FOREIGN KEY (`project_id`)
    REFERENCES `happydev`.`project` (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `happydev`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `happydev`.`user` (
  `id` VARCHAR(36) COLLATE 'utf8mb4_unicode_ci' NOT NULL,
  `roles` LONGTEXT COLLATE 'utf8mb4_unicode_ci' NOT NULL COMMENT '(DC2Type:array)',
  `date_creation` DATETIME NOT NULL,
  `date_update` DATETIME NOT NULL,
  `date_deleted` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `happydev`.`users_projects`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `happydev`.`users_projects` (
  `user_id` VARCHAR(36) COLLATE 'utf8mb4_unicode_ci' NOT NULL,
  `project_id` VARCHAR(36) COLLATE 'utf8mb4_unicode_ci' NOT NULL,
  PRIMARY KEY (`user_id`, `project_id`),
  INDEX `IDX_27D2987EA76ED395` (`user_id` ASC),
  INDEX `IDX_27D2987E166D1F9C` (`project_id` ASC),
  CONSTRAINT `FK_27D2987E166D1F9C`
    FOREIGN KEY (`project_id`)
    REFERENCES `happydev`.`project` (`id`),
  CONSTRAINT `FK_27D2987EA76ED395`
    FOREIGN KEY (`user_id`)
    REFERENCES `happydev`.`user` (`id`))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
