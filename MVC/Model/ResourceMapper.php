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

require_once __DIR__ . '/../../Database/JoinClause.php';


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
     * @var \Database\DatabaseInterface $db A database
     */
    private $db;


    /**
     * Constructor.
     * Connects to the database.
     * @param \Database\DatabaseInterface $db Database to connect to
     */
    public function __construct(\Database\DatabaseInterface $db)
    {
        $this->db = $db;
    }



    /**
     * Creates a new resource.
     * @param Resource $resource Resource to create
     * @return self This data mapper itself
     */
    public function create(Resource $resource)
    {
        $this->db->insert('resource', [
            'name'        => $resource->name,
            'description' => $resource->description
        ]);

        return $this;
    }



    /**
     * Reads a resource.
     * @param string $name Name of the resource
     * @return Resource Resource from the database
     */
    public function read($name)
    {
        $where = new \Database\JoinClause();
        $where->setClauses(['name' => $name]);

        $record = $this->db->select('resource', [], $where);
        $record = $record[0];

        return new Resource($record['name'], $record['description']);
    }



    /**
     * Updates a resource.
     * @param Resource $resource Resource to update
     * @return self This data mapper itself
     */
    public function update(Resource $resource)
    {
        $where = new \Database\JoinClause();
        $where->setClauses(['name' => $resource->name]);

        $this->db->update('resource', [
            'description' => $resource->description
        ], $where);

        return $this;
    }



    /**
     * Deletes a resource.
     * @param Resource $resource Resource to delete
     * @return self This data mapper itself
     */
    public function delete(Resource $resource)
    {
        $where = new \Database\JoinClause();
        $where->setClauses(['name' => $resource->name]);
        $this->db->delete('resource', $where);

        return $this->deletePermissions($resource);
    }



    /**
     * Deletes permissions associated to a resource.
     * @param Resource $resource Resource whose permission will be deleted
     * @return self This data mapper itself
     */
    private function deletePermissions(Resource $resource)
    {
        $where = new \Database\JoinClause();
        $where->setClauses(['resource' => $resource->name]);
        $this->db->delete('permission', $where);

        return $this;
    }
}
