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
 * A role in the system.
 *
 * This class follows the Observer and the Model-View-Controller
 * Design Patterns, and exhibits a Fluent Interface through Method
 * Chaining.
 *
 * @author Marco Zanella <mz@openmailbox.org>
 */
class Role implements \SubjectInterface
{
    use \SubjectTrait;

    /**
     * @var string $name        Name of this role
     * @var string $description Description of this role
     * @var array  $resources   Resources this role has access to
     */
    public $name, $description, $resources;



    /**
     * Constructor.
     * @param string $name        Name of the role
     * @param string $description Description of the role
     */
    public function __construct($name, $description = null)
    {
        $this->name        = $name;
        $this->description = $description;
        $this->resources   = [];
    }



    /**
     * Tells whether this role has read access to given resource.
     * @param Resource $resource Resource to check
     * @return bool True if and only if this role has read access to
     *              given resource
     */
    public function canRead(Resource $resource)
    {
        $res  = $this->resources;
        $name = $resource->name;

        return isset($res[$name]) && (strpos($res[$name], 'r') !== false);
    }



    /**
     * Tells whether this role has write access to given resource.
     * @param Resource $resource Resource to check
     * @return bool True if and only if this role has write access to
     *              given resource
     */
    public function canWrite(Resource $resource)
    {
        $res  = $this->resources;
        $name = $resource->name;

        return isset($res[$name]) && (strpos($res[$name], 'w') !== false);
    }



    /**
     * Grants access to a resource.
     * If this role already had access to given resource, it gets
     * updated.
     * @param Resource $resource Resource to grant access to
     * @param string   $mode     Access mode: "rw" | "r" | "w" | ""
     * @return self This role itself
     */
    public function addResource(Resource $resource, $mode = "")
    {
        $readable  = (strpos($mode, 'r') !== false) ? 'r' : '';
        $writeable = (strpos($mode, 'w') !== false) ? 'w' : '';

        $this->resources[$resource->name] = $readable . $writeable;

        return $this;
    }



    /**
     * Revokes access to a resource.
     * If this role had not access to given resource, nothing happens.
     * @param Resource $resource Resource to revoke access to
     * @return self This role itself
     */
    public function removeResource(Resource $resource)
    {
        unset($this->resources[$resource->name]);

        return $this;
    }
}
