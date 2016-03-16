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
 * A musicscore.
 *
 * This class follows the Observer and the Model-View-Controller
 * Design Patterns, and exhibits a Fluent Interface through Method
 * Chaining.
 *
 * @author Marco Zanella <mz@openmailbox.org>
 */
class Musicscore implements \SubjectInterface
{
    use \SubjectTrait;

    /**
     * @var int    $id          Identifier of this music sheet
     * @var string $content     Attached file
     * @var string $name        Name of this music sheet
     * @var string $description Description of this music sheet
     * @var int    $timestamp   Update time
     */
    public $id, $content, $name, $description, $timestamp;


    /**
     * Constructor.
     * @param int $id             Identifier of the music sheet
     * @param string $content     Attached file
     * @param string $name        Name of this music sheet
     * @param string $description Description of this music sheet
     * @param int    $timestamp   Update timestamp
     */
    public function __construct(
        $id, $content,
        $name, $description, $timestamp)
    {
        $this->id          = $id;
        $this->content     = $content;
        $this->name        = $name;
        $this->description = $description;
        $this->timestamp   = $timestamp;
    }
}
