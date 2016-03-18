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
 * Data mapper for a role.
 *
 * This class follows the Data Mapper Design Pattern and exhibits a Fluent
 * Interface through Method Chaining.
 *
 * @author Marco Zanella <mz@openmailbox.org>
 */
class RoleMapper
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
     * Creates a new role.
     * @param Role $role Role to create
     * @return self This data mapper itself
     */
    public function create(Role $role)
    {
        $this->db->insert('role', [
            'name'        => $role->name,
            'description' => $role->description
        ]);

        return $this->deletePermissions($role)->createPermissions($role);
    }



    /**
     * Reads a role.
     * @param string $name Name of the role
     * @return Resource Resource from the database
     */
    public function read($name)
    {
        $where = new \Database\JoinClause();
        $where->setClauses(['name' => $name]);

        $record = $this->db->select('role', [], $where);
        $record = $record[0];

        $role = new Role($record['name'], $record['description']);
        $this->readPermissions($role);

        return $role;
    }



    /**
     * Updates a role.
     * @param Role $role Role to update
     * @return self This data mapper itself
     */
    public function update(Role $role)
    {
        $where = new \Database\JoinClause();
        $where->setClauses(['name' => $role->name]);

        $this->db->update('role', [
            'name' => $role->name,
            'description' => $role->description
        ], $where);

        return $this->deletePermissions($role)->createPermissions($role);
    }



    /**
     * Deletes a role.
     * @param Role $role Role to delete
     * @return self This data mapper itself
     */
    public function delete(Role $role)
    {
        $where = new \Database\JoinClause();
        $where->setClauses(['name' => $role->name]);
        $this->db->delete('role', $where);

        return $this->deleteRoleAssociations($role)->deletePermissions($role);
    }



    /**
     * Creates permissions associated to a role.
     * @param Role $role Role whose permissions will be created
     * @return self This data mapper itself
     */
    private function createPermissions(Role $role)
    {
        foreach ($role->resources as $name => $mode) {
            $this->db->insert('permission', [
                'role'     => $role->name,
                'resource' => $id,
                'mode'     => $mode
            ]);
        }

        return $this;
    }



    /**
     * Reads permissions associated to a role.
     * @param Role $role Role whose permission will be read
     * @return self This data mapper itself
     */
    private function readPermissions(Role $role)
    {
        $where = new \Database\JoinClause();
        $where->setClauses(['role' => $role->name]);

        $records = $this->db->select('permission', [], $where);
        foreach ($records as $record) {
            $role->resources[$record['resource']] = $record['mode'];
        }

        return $this;
    }



    /**
     * Delectes role associations involving a role.
     * @param Role $role Role whose association will be deleted
     * @return self This data mapper itself
     */
    private function deleteRoleAssociations(Role $role)
    {
        $where = new \Database\JoinClause();
        $where->setClauses(['role' => $role->name]);
        $this->db->delete('role_association', $where);

        return $this;
    }



    /**
     * Deletes permissions associated to a role.
     * @param Role $role Role whose permissions will be deleted
     * @return self This data mapper itself
     */
    private function deletePermissions(Role $role)
    {
        $where = new \Database\JoinClause();
        $where->setClauses(['role' => $role->name]);
        $this->db->delete('permission', $where);

        return $this;
    }
}
