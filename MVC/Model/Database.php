<?php
/**
 * This file is part of Coro Monte Pasubio.
 *
 * Coro Monte Pasubio is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Coro Monte Pasubio is distributed under the hope it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Coro Monte Pasubio. If not, see <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 *
 * @author    Marco Zanella <mz@openmailbox.org>
 * @copyright 2016 Marco Zanella
 * @license   GNU General Public License, version 3
 */
namespace MVC\Database;

/**
 * Connects to a database.
 * @return object Connection to database
 */
function connect() {
    $config = parse_ini_file('config.ini');
    $dsn    = 'mysql:host=' . $config['db_host'] . ';'
            . 'dbname=' . $config['db_name'];
    $username = $config['db_user'];
    $password = $config['db_pass'];
    $options  = [
        \PDO::ATTR_PERSISTENT => true,
        \PDO::ATTR_ERRMODE    => \PDO::ERRMODE_EXCEPTION
    ];

    return new \PDO($dsn, $username, $password, $options);
}
