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

require_once 'DatabaseInterface.php';

/**
 * Adapter of a PDO prepared statement.
 *
 * @author Marco Zanella <mz@openmailbox.org>
 */
class MysqlStatementAdapter implements StatementInterface
{
    /**
     * @var object $statement Prepared statement
     */
    protected $statement;


    /**
     * Constructor.
     * @param object $statement PDO prepared statement
     */
    public function __construct($statement)
    {
        $this->statement = $statement;
    }



    /**
     * Executes this prepared statement.
     * @param array $parameters Array of binders
     * @return self This prepared statement itself
     */
    public function execute(array $parameters = [])
    {
        return $this->statement->execute($parameters);
    }



    /**
     * Fetches a record.
     * @return mixed A record
     */
    public function fetch()
    {
        return $this->statement->fetch(\PDO::FETCH_ASSOC);
    }
}



/**
 * Adapter of a MySQL database.
 * Uses PDO internally.
 *
 * This class exhibits a Fluent Interface through Method Chaining.
 *
 * @author Marco Zanella <mz@openmailbox.org>
 */
class MysqlAdapter implements DatabaseInterface
{
    /**
     * @var \PDO $dbh Connection to the database
     */
    protected $dbh;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->connect();
    }



    /**
     * Connects to the database.
     * Connection parameters are read from cofiguration file.
     * @return self This database itself
     */
    public function connect()
    {
        $config = parse_ini_file('config.ini');
        $dsn    = 'mysql:host=' . $config['db_host'] . ';'
                . 'dbname=' . $config['db_name'];
        $username = $config['db_user'];
        $password = $config['db_pass'];
        $options  = [
            \PDO::ATTR_PERSISTENT => true,
            \PDO::ATTR_ERRMODE    => \PDO::ERRMODE_EXCEPTION
        ];

        $this->dbh = new \PDO($dsn, $username, $password, $options);

        return $this;
    }



    /**
     * Returns identifier of last inserted element.
     * @return int Identifier of last inserted element
     */
    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }



    /**
     * Builds a prepared statement.
     * @param string $query Query to prepare
     * @return MysqlStatementAdapter A prepared statement
     */
    public function prepare($query)
    {
        return new MysqlStatementAdapter($this->dbh->prepare($query));
    }



    /**
     * Inserts data into this database.
     * @param string $entity Table name
     * @param array  $what   Data to insert
     * @return self This database itself
     */
    public function insert($entity, array $what)
    {
        $columns    = array_keys($what);
        $parameters = [];

        foreach ($what as $key => $value) {
            $parameters[':' . $key] = $value;
        }

        $query = 'INSERT INTO ' . $entity
               . '(' . implode(', ', $columns) . ') '
               . 'VALUES (:' . implode(', :', $columns) . ')';
        $stm   = $this->prepare($query);
        $stm->execute($parameters);

        return $this;
    }



    /**
     * Selects data from this database.
     * @param string               $entity Table name
     * @param array                $what   Columns to select
     * @param WhereClauseInterface $where  Where clause
     * @return array Array of records
     */
    public function select($entity, array $what, WhereClauseInterface $where)
    {
        $what  = (empty($what)) ? '*' : implode(', ', $what);

        $query = 'SELECT ' . $what . ' FROM ' . $entity;
        if (!empty($where->toSql())) {
            $query .= ' WHERE ' . $where->toSql();
        }
        $stm = $this->prepare($query);
        $stm->execute($where->toParameters());

        $records = [];
        while ($row = $stm->fetch()) {
            $records[] = $row;
        }

        return $records;
    }



    /**
     * Updates records of this database.
     * @param string               $entity Table name
     * @param array                $what   Data to update
     * @param WhereClauseInterface $where  Where clause
     * @return self This database itself
     */
    public function update($entity, array $what, WhereClauseInterface $where)
    {
        $sets = [];
        $parameters = [];
        foreach ($what as $key => $value) {
            $sets[] = $key . ' = :' . $key;
            $parameters[':' . $key] = $value;
        }
        $parameters = array_merge($parameters, $where->toParameters());

        $query = 'UPDATE ' . $entity
               . ' SET ' . implode(', ', $sets);
        if (!empty($where->toSql())) {
            $query .= ' WHERE ' . $where->toSql();
        }
        $stm = $this->prepare($query);
        $stm->execute($parameters);

        return $this;
    }



    /**
     * Deletes records from this database.
     * @param string               $entity Table name
     * @param WhereClauseInterface $where  Where clause
     * @return self This database itself
     */
    public function delete($entity, WhereClauseInterface $where)
    {
        $query = 'DELETE FROM ' . $entity;
        if (!empty($where->toSql())) {
            $query .= ' WHERE ' . $where->toSql();
        }
        $stm = $this->prepare($query);
        $stm->execute($where->toParameters());

        return $this;
    }
}
