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

require_once 'WhereClauseInterface.php';

/**
 * Clause made of equalities, joined by either 'AND' or 'OR'.
 *
 * @author Marco Zanella <mz@openmailbox.org>
 */
class JoinClause implements WhereClauseInterface
{
    /**
     * @var array  $clauses Raw clauses
     * @var string $glue    Glue to join clauses
     */
    protected $clauses, $glue;


    /**
     * Constructor.
     * Sets a 'glue' to join clauses, may be either 'AND' or 'OR' (case
     * does not matter). Uses 'AND' by default or in case of invalid
     * input.
     * @param string $glue Glue to join clauses
     */
    public function __construct($glue = 'AND')
    {
        $glue = strtoupper(trim($glue));
        if (!in_array($glue, ['AND', 'OR'])) {
            $glue = 'AND';
        }

        $this->glue = $glue;
        $this->clauses = [];
    }



    /**
     * Sets criteria for this clause.
     * Clauses are an associative array where keys are field name and
     * values will be tested for equality.
     * For example, ['username' => 'my_user', 'password' => 'my_password']
     * will match every record where `username` is equal to 'my_user'
     * and/or `password` is equal to 'my_password'.
     * @param array $caluses Associative array of clauses
     * @return self This clause itself
     */
    public function setClauses($clauses = [])
    {
        $this->clauses = $clauses;

        return $this;
    }



    /**
     * Returns clauses as an SQL string.
     * String is suitable for using with prepared statements.
     * @return string Clauses in SQL format
     */
    public function toSql()
    {
        $columns = array_keys($this->clauses);
        $pieces  = [];
        $glue    = ' ' . $this->glue . ' ';

        foreach ($columns as $column) {
            $pieces[] = $column . ' = :' . $column . '_w';
        }

        return implode($glue, $pieces);
    }



    /**
     * Returns binders for a prepared statement.
     * @return array Binders for a prepared statement.
     */
    public function toParameters()
    {
        $parameters = [];

        foreach ($this->clauses as $key => $value) {
            $parameters[':' . $key . '_w'] = $value;
        }

        return $parameters;
    }
}
