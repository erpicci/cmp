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

require_once __DIR__ . '/../../ObserverPattern.php';

/**
 * An user of the system.
 *
 * Users must authenticate to the system before accessing resources.
 *
 * This class follows the Observer and the Model-View-Controller
 * Design Patterns, and exhibits a Fluent Interface through Method
 * Chaining.
 *
 * @author Marco Zanella <mz@openmailbox.org>
 */
class User implements \SubjectInterface
{
    use \SubjectTrait;

    /**
     * @var string $username      Username of this user
     * @var string $password      Password of this user
     * @var int    $last_login    Timestamp of last successful login
     * @var int    $failed_logins Number of consecutive failed login
     * @var int    $last_attempt  Timestamp of last login attempt
     * @var array  $roles         Roles of this user
     */
    public $username, $password,
           $last_login, $failed_logins, $last_attempt,
           $roles;


    /**
     * Constructor.
     * @param string $username      Identifier of the user
     * @param string $password      Password of the user
     * @param int    $last_login    Timestamp of last successful login
     * @param int    $failed_logins Number of consecutive failed login
     * @param int    $last_attempt  Timestamp of last login attempt
     */
    public function __construct(
        $username, $password,
        $last_login, $failed_logins, $last_attempt)
    {
        $this->username      = $username;
        $this->password      = $password;
        $this->last_login    = $last_login;
        $this->failed_logins = $failed_logins;
        $this->last_attempt  = $last_attempt;
        $this->roles         = [];
    }



    /**
     * Authenticates an user.
     * Checks whether user provided correct identifier and password. If
     * authentication fails, user must wait before submitting another
     * request. Time between requests grows exponentially to make brute
     * force attacks unfeasible.
     * @param string $password Password of the user
     * @return bool True if user successfully authenticated, false otherwise
     */
    public function authenticate($password)
    {
        // Waits an exponential time between failed login attempts
        $now = time();
        if ($this->last_attempt + pow(2, $this->failed_logins) > $now) {
            return false;
        }

        // Checks password
        $this->last_attempt = time();
        if (!password_verify($password, $this->password)) {
            $this->failed_logins++;
            return false;
        }

        // Successful login
        $this->last_login    = time();
        $this->failed_logins = 0;
        return true;
    }



    /**
     * Sets password for this user.
     * Password is hashed using a salt mechanism.
     * @param string $password Plain text password
     * @return self This user itself
     */
    public function setPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }



    /**
     * Adds a role to this user.
     * @param Role $role Role to add
     * @return self This user itself
     */
    public function addRole(Role $role)
    {
        $this->roles[$role->name] = true;

        return $this;
    }



    /**
     * Removes a role from this user.
     * If user had not given role, nothing happens.
     * @param Role $role Role to remove
     * @return self This user itself
     */
    public function removeRole(Role $role)
    {
        unset($this->roles[$role->name]);

        return $this;
    }
}
