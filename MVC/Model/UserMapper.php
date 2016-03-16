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
 * Data mapper for an user of the system.
 *
 * This class follows the Data Mapper Design Patter and exhibits a Fluent
 * Interface through Method Chaining.
 *
 * @author Marco Zanella <mz@openmailbox.org>
 */
class UserMapper
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
     * Creates a new user.
     * @param User $user User to create
     * @return self This data mapper itself
     */
    public function create(User $user)
    {
        $query = 'INSERT INTO user (username, password, last_login, failed_logins, last_attempt)'
               . 'VALUES (:username, :password, :login, :fail, :attempt)';
        $stm   = $this->dbh->prepare($query);
        $stm->execute([
            ':username' => $user->username,
            ':password' => $user->password,
            ':login'    => $user->last_login,
            ':fail'     => $user->failed_logins,
            ':attempt'  => $user->last_attempt
        ]);

        return $this->createRoles($user);
    }



    /**
     * Reads an user.
     * @param string $username Username of the user
     * @return User User from the database
     */
    public function read($username)
    {
        $query = 'SELECT * FROM user WHERE username = :username';
        $stm   = $this->dbh->prepare($query);
        $stm->execute([':username' => $username]);

        $record = $stm->fetch();
        if ($record === false) {
            return null;
        }

        $user = new User(
            $record['username'],
            $record['password'],
            $record['last_login'],
            $record['failed_logins'],
            $record['last_attempt']
        );
        $this->readRoles($user);

        return $user;
    }



    /**
     * Updates an user.
     * @param User $user User to update
     * @return self This data mapper itself
     */
    public function update(User $user)
    {
        $query = 'UPDATE user SET password = :password, last_login = :last_login, '
               . 'failed_logins = :failed_logins, last_attempt = :last_attempt '
               . 'WHERE username = :username';
        $stm   = $this->dbh->prepare($query);
        $stm->execute([
            ':username'      => $user->username,
            ':password'      => $user->password,
            ':last_login'    => $user->last_login,
            ':failed_logins' => $user->failed_logins,
            ':last_attempt'  => $user->last_attempt
        ]);

        return $this->deleteRoles($user)->createRoles($user);
    }



    /**
     * Deletes an user.
     * @param User $user User to delete
     * @return self This data mapper itself
     */
    public function delete(User $user)
    {
        $query = 'DELETE FROM user WHERE username = :username';
        $stm   = $this->dbh->prepare($query);
        $stm->execute([':username' => $user->username]);

        return $this->deleteRoles($user);
    }



    /**
     * Creates roles associated to an user.
     * @param User $user User whose roles will be created
     * @return self This data mapper itself
     */
    private function createRoles(User $user)
    {
        $query = 'INSERT INTO role_association (user, role) '
               . 'VALUES (:user, :role)';
        $stm   = $this->dbh->prepare($query);

        foreach ($user->roles as $name => $value) {
            $stm->execute([':user' => $user->username, ':role' => $name]);
        }

        return $this;
    }



    /**
     * Reads roles associated to an user.
     * @param User $user User whose roles will be read
     * @return self This data mapper itself
     */
    public function readRoles(User $user)
    {
        $query = 'SELECT role FROM role_association WHERE user = :user';
        $stm   = $this->dbh->prepare($query);
        $stm->execute([':user' => $user->username]);

        while ($row = $stm->fetch()) {
            $user->roles[$row['role']] = true;
        }

        return $this;
    }



    /**
     * Deletes roles associated to an user.
     * @param User $user User whose roles will be deleted
     * @return self This data mapper itself
     */
    public function deleteRoles(User $user)
    {
        $query = 'DELETE FROM role_association WHERE user = :user';
        $stm   = $this->dbh->prepare($query);
        $stm->execute([':user' => $user->username]);

        return $this;
    }
}
