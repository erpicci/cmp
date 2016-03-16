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
namespace MVC\Model;

require_once 'Database.php';

/**
 * Data mapper for a resource.
 *
 * This class follows the Data Mapper Design Pattern and exhibits a Fluent
 * Interface through Method Chaining.
 *
 * @author Marco Zanella <mz@openmailbox.org>
 */
class ResourceMapper
{
    /**
     * @var object $dbh Database connection
     */
    private $dbh;


    /**
     * Constructor.
     * Connects to the database.
     */
    public function __construct()
    {
        $this->dbh = \MVC\Database\connect();
    }



    /**
     * Creates a new resource.
     * @param Resource $resource Resource to create
     * @return self This data mapper itself
     */
    public function create(Resource $resource)
    {
        $query = 'INSERT INTO resource (identifier, description) '
               . 'VALUES (:id, :description)';
        $stm   = $this->dbh->prepare($query);
        $stm->execute([
            ':id' => $resource->id,
            ':description' => $resource->description
        ]);

        return $this;
    }



    /**
     * Reads a resource.
     * @param string $id Identifier of the resource
     * @return Resource Resource from the database
     */
    public function read($id)
    {
        $query = 'SELECT * FROM resource WHERE identifier = :id';
        $stm   = $this->dbh->prepare($query);
        $stm->execute([':id' => $id]);

        $record = $stm->fetch();
        return new Resource($record['identifier'], $record['description']);
    }



    /**
     * Updates a resource.
     * @param Resource $resource Resource to update
     * @return self This data mapper itself
     */
    public function update(Resource $resource)
    {
        $query = 'UPDATE resource '
               . 'SET description = :description WHERE identifier = :id';
        $stm   = $this->dbh->prepare($query);
        $stm->execute([
            ':id'          => $resource->id,
            ':description' => $resource->description
        ]);

        return $this;
    }



    /**
     * Deletes a resource.
     * @param Resource $resource Resource to delete
     * @return self This data mapper itself
     */
    public function delete(Resource $resource)
    {
        $query = 'DELETE FROM resource WHERE identifier = :id';
        $stm   = $this->dbh->prepare($query);
        $stm->execute([':id' => $resource->id]);

        return $this;
    }



    /**
     * Deletes permissions associated to a resource.
     * @param Resource $resource Resource whose permission will be deleted
     * @return self This data mapper itself
     */
    private function deletePermissions(Resource $resource)
    {
        $query = 'DELETE FROM permission WHERE resource = :name';
        $stm   = $this->dbh->prepare($query);
        $stm->execute([':name' => $resource->id]);

        return $this;
    }
}
