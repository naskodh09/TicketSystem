SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Compatible with newer MySQL versions. (After MySQL-5.5)
-- This SQL uses utf8mb4 and has CURRENT_TIMESTAMP function.
--

--
-- Creates database `ticketsystem` unless it already exists and uses `ticketsystem`
-- Default Schema
--

CREATE DATABASE IF NOT EXISTS `ticketsystem` DEFAULT CHARACTER SET utf8mb4;
USE `ticketsystem`;

--
-- Table structure for table `accounts`
--

CREATE TABLE IF NOT EXISTS `accounts` (
    `id`                        INT NOT NULL AUTO_INCREMENT,
    `name`                      VARCHAR(64) NOT NULL,
    `email`                     VARCHAR(64) NOT NULL,
    `password`                  VARCHAR(255) NOT NULL,
    `adminlevel`                ENUM('0','1') NOT NULL DEFAULT '0',
    `approved`                  ENUM('0','1') NOT NULL DEFAULT '0',
    `insert_time`               TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `last_login`                TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT `PK_userid` PRIMARY KEY (`id`),
    UNIQUE KEY `unique_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `tickets`
--

CREATE TABLE IF NOT EXISTS `tickets` (
    `id`                        INT NOT NULL AUTO_INCREMENT,
    `file`                      VARCHAR(255) ,
    `subject`                   VARCHAR(255) NOT NULL,
    `content`                   TEXT NOT NULL,
    `flagged`                   ENUM('0','1') NOT NULL DEFAULT '0',
    `closed`                    ENUM('0','1') NOT NULL DEFAULT '0',
    `user_id`                   INT NOT NULL,
    `created_at`                TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`                TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `closed_at`                 TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT `PK_ticketid` PRIMARY KEY (`id`),
    CONSTRAINT `FK_userid` FOREIGN KEY `FK_userid` (`user_id`)
        REFERENCES `accounts` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
    `id`                        INT NOT NULL AUTO_INCREMENT,
    `content`                   TEXT NOT NULL,
    `ticket_id`                 INT NOT NULL,
    `user_id`                   INT NOT NULL,
    `created_at`                TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`                TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT `PK_commentid` PRIMARY KEY (`id`),
    CONSTRAINT `FK_ticketid` FOREIGN KEY `FK_ticketid` (`ticket_id`)
        REFERENCES `tickets` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `FK_useridx` FOREIGN KEY `FK_useridx` (`user_id`)
        REFERENCES `accounts` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------
--
-- Creates default admin user, only use this for testing
-- Delete this user in a production database
-- Creates a user with email 'admin@admin.com' and password 'test'
--

INSERT INTO accounts (name, email, adminlevel, approved, password) VALUES ('ADMIN USER', 'admin@admin.com', '1', '1', '$2y$10$SfhYIDtn.iOuCW7zfoFLuuZHX6lja4lF4XA4JqNmpiH/.P3zB8JCa');


-- ----------------------------------------------------------
--
-- Creates default user `ticketsystem_user` with password `changeme` unless it already exists
-- Granting permissions to user `ticketsystem_user`, created below
-- Reloads the privileges from the grant tables in the MySQL system database.
--

CREATE USER IF NOT EXISTS `ticketsystem_user`@`localhost` IDENTIFIED BY 'changeme';
GRANT SELECT, UPDATE, INSERT ON `ticketsystem`.* TO 'ticketsystem_user'@'localhost';
FLUSH PRIVILEGES;
