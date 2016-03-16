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
 * A sponsor.
 *
 * This class follows the Observer and the Model-View-Controller
 * Design Patterns, and exhibits a Fluent Interface through Method
 * Chaining.
 *
 * @author Marco Zanella <mz@openmailbox.org>
 */
class Sponsor implements \SubjectInterface
{
    use \SubjectTrait;

    /**
     * @var int    $id      Identifier of this sponsor
     * @var string $name    Name of this sponsor
     * @var string $website Address of website of this sponsor
     * @var string $banner  Address of banner of this sponsor
     */
    public $id, $name, $website, $banner;


    /**
     * Constructor.
     * @param int    $id      Identifier of the sponsor
     * @param string $name    Name of the sponsor
     * @param string $website Address of website of the sponsor (optional)
     * @param string $banner  Address of banner of the sponsor (optional)
     */
    public function __construct($id, $name, $website = null, $banner = null)
    {
        $this->id      = $id;
        $this->name    = $name;
        $this->website = $website;
        $this->banner  = $banner;
    }
}
