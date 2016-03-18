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
namespace Database;

/**
 * Interface of a prepared statement.
 *
 * @author Marco Zanella <mz@openmailbox.org>
 */
interface StatementInterface
{
    /**
     * Executes this prepared statement.
     * @param array $parameters Array of binders
     * @return self This prepared statement itself
     */
    public function execute(array $parameters);



    /**
     * Fetches a record.
     * @return mixed A record
     */
    public function fetch();
}



/**
 * Interface of a database.
 *
 * This interface exhibits a Fluent Interface through Method Chaining.
 *
 * @author Marco Zanella <mz@openmailbox.org>
 */
interface DatabaseInterface
{
    /**
     * Builds a prepared statement.
     * @param mixed $query Query to prepare
     * @return StatementInterface A prepared statement
     */
    public function prepare($query);



    /**
     * Inserts data into this database.
     * @param string $entity Entity to manipulate
     * @param array  $what   Data to insert
     * @return self This database itself
     */
    public function insert($entity, array $what);



    /**
     * Selects data from this database.
     * @param string               $entity Entity to manipulate
     * @param array                $what   Fields to select
     * @param WhereClauseInterface $where  Where clause
     * @return array Array of records
     */
    public function select($entity, array $what, WhereClauseInterface $where);



    /**
     * Updates records of this database.
     * @param string               $entity Entity to manipulate
     * @param array                $what   Data to update
     * @param WhereClauseInterface $where  Where clause
     * @return self This database itself
     */
    public function update($entity, array $what, WhereClauseInterface $where);



    /**
     * Deletes records from this database.
     * @param string               $entity Entity to manipulate
     * @param WhereClauseInterface $where  Where clause
     * @return self This database itself
     */
    public function delete($entity, WhereClauseInterface $where);
}
