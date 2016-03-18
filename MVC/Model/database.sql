--
-- This file is part of Coro Monte Pasubio.
--
-- Coro Monte Pasubio is free software: you can redistribute it and/or modify
-- it under the terms of the GNU General Public License as published by
-- the Free Software Foundation, either version 3 of the License, or
-- (at your option) any later version.
--
-- Coro Monte Pasubio is distributed under the hope it will be useful,
-- but WITHOUT ANY WARRANTY; without even the implied warranty of
-- MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
-- GNU General Public License for more details.
--
-- You should have received a copy of the GNU General Public License
-- along with Coro Monte Pasubio. If not, see <http://www.gnu.org/licenses/>.
--
-- author    Marco Zanella <mz@openmailbox.org>
-- copyright 2016 Marco Zanella
-- license   GNU General Public License, version 3
--

--
-- Database configuration

-- User
CREATE TABLE IF NOT EXISTS user (
    username      VARCHAR(64)  NOT NULL,
    password      CHAR(60)     NOT NULL,
    last_login    INT UNSIGNED DEFAULT NULL,
    failed_logins INT UNSIGNED NOT NULL DEFAULT 0,
    last_attempt  INT UNSIGNED NOT NULL DEFAULT 0,
    CONSTRAINT id PRIMARY KEY(username)
);


-- Role
CREATE TABLE IF NOT EXISTS role (
    name        VARCHAR(64) NOT NULL,
    description TINYTEXT,
    CONSTRAINT id PRIMARY KEY(name)
);


-- Resource
CREATE TABLE IF NOT EXISTS resource (
    name        VARCHAR(64) NOT NULL,
    description TINYTEXT,
    CONSTRAINT id PRIMARY KEY(name)
);


-- Many-to-many relation among users and roles
CREATE TABLE IF NOT EXISTS role_association (
    user VARCHAR(64) NOT NULL,
    role VARCHAR(64) NOT NULL,
    CONSTRAINT id PRIMARY KEY(user, role)
);


-- Many-to-many relation with attribute among roles and resources
CREATE TABLE IF NOT EXISTS permission (
    role     VARCHAR(64) NOT NULL,
    resource VARCHAR(64) NOT NULL,
    mode     CHAR(2)     NOT NULL DEFAULT "",
    CONSTRAINT id PRIMARY KEY(role, resource)
);


-- Message
CREATE TABLE IF NOT EXISTS message (
    id        INT UNSIGNED NOT NULL AUTO_INCREMENT,
    title     VARCHAR(128),
    content   MEDIUMTEXT,
    image     VARCHAR(256),
    timestamp INT UNSIGNED NOT NULL,
    CONSTRAINT id PRIMARY KEY(id)
);


-- Event
CREATE TABLE IF NOT EXISTS event (
    id        INT UNSIGNED NOT NULL AUTO_INCREMENT,
    title     VARCHAR(128),
    content   MEDIUMTEXT,
    time      INT UNSIGNED NOT NULL,
    place     VARCHAR(256) NOT NULL,
    image     VARCHAR(256),
    timestamp INT UNSIGNED NOT NULL,
    visible   INT UNSIGNED NOT NULL DEFAULT 0,
    CONSTRAINT id PRIMARY KEY(id)
);


-- Music score
CREATE TABLE IF NOT EXISTS musicscore (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    file        BLOB         NOT NULL,
    name        VARCHAR(64)  NOT NULL,
    description MEDIUMTEXT,
    timestamp   INT UNSIGNED NOT NULL,
    CONSTRAINT id PRIMARY KEY(id)
);


-- Sponsor
CREATE TABLE IF NOT EXISTS sponsor (
    id       INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name     VARCHAR(64)  NOT NULL,
    URL      VARCHAR(256),
    banner   VARCHAR(256),
    priority INT UNSIGNED NOT NULL DEFAULT 0,
    CONSTRAINT id PRIMARY KEY(id)
);
