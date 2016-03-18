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
 * Interface of a where clause.
 *
 * @author Marco Zanella <mz@openmailbox.org>
 */
interface WhereClauseInterface
{
    /**
     * Sets criteria for this clause.
     * @param mixed $caluses Criteria to set
     * @return This clause itself
     */
    public function setClauses($clauses);



    /**
     * Returns clauses as an SQL string.
     * String is suitable for using with prepared statements.
     * @return string Clauses in SQL format
     */
    public function toSql();



    /**
     * Returns binders for a prepared statement.
     * @return array Binders for a prepared statement.
     */
    public function toParameters();
}
