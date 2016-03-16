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
     * Creates a new role.
     * @param Role $role Role to create
     * @return self This data mapper itself
     */
    public function create(Role $role)
    {
        $query = 'INSERT INTO role (name, description) '
               . 'VALUES (:name, :description)';
        $stm   = $this->dbh->prepare($query);
        $stm->execute([
            ':name'        => $role->name,
            ':description' => $role->description
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
        $query = 'SELECT * FROM role WHERE name = :name';
        $stm   = $this->dbh->prepare($query);
        $stm->execute([':name' => $name]);

        $record = $stm->fetch();
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
        $query = 'UPDATE role '
               . 'SET description = :description WHERE name = :name';
        $stm   = $this->dbh->prepare($query);
        $stm->execute([
            ':name'        => $role->name,
            ':description' => $role->description
        ]);

        return $this->deletePermissions($role)->createPermissions($role);
    }



    /**
     * Deletes a role.
     * @param Role $role Role to delete
     * @return self This data mapper itself
     */
    public function delete(Role $role)
    {
        $query = 'DELETE FROM role WHERE name = :name';
        $stm   = $this->dbh->prepare($query);
        $stm->execute([':name' => $role->name]);

        return $this->deleteRoleAssociations($role)->deletePermissions($role);
    }



    /**
     * Creates permissions associated to a role.
     * @param Role $role Role whose permissions will be created
     * @return self This data mapper itself
     */
    private function createPermissions(Role $role)
    {
        $query = 'INSERT INTO permission (role, resource, mode) '
               . 'VALUES (:role, :resource, :mode)';
        $stm   = $this->dbh->prepare($query);

        foreach ($role->resources as $id => $mode) {
            $stm->execute([
                ':role'     => $role->name,
                ':resource' => $id,
                ':mode'     => $mode
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
        $query = 'SELECT resource, mode FROM permission WHERE role = :role';
        $stm   = $this->dbh->prepare($query);
        $stm->execute([':role' => $role->name]);

        while ($row = $stm->fetch()) {
            $role->resources[$row['resource']] = $row['mode'];
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
        $query = 'DELETE FROM role_association WHERE role = :name';
        $stm   = $this->dbh->prepare($query);
        $stm->execute([':name' => $role->name]);

        return $this;
    }



    /**
     * Deletes permissions associated to a role.
     * @param Role $role Role whose permissions will be deleted
     * @return self This data mapper itself
     */
    private function deletePermissions(Role $role)
    {
        $query = 'DELETE FROM permission WHERE role = :name';
        $stm   = $this->dbh->prepare($query);
        $stm->execute([':name' => $role->name]);

        return $this;
    }
}
