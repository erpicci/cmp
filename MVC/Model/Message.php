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
 * A message.
 *
 * This class follows the Observer and the Model-View-Controller
 * Design Patterns, and exhibits a Fluent Interface through Method
 * Chaining.
 *
 * @author Marco Zanella <mz@openmailbox.org>
 */
class Message implements \SubjectInterface
{
    use \SubjectTrait;

    /**
     * @var int    $id        Identifier of this music sheet
     * @var string $title     Title of this message
     * @var string $content   Content of this message
     * @var string $image     URL of an image
     * @var int    $timestamp Update time
     */
    public $id, $title, $content, $image, $timestamp;



    /**
     * Constructor.
     * @param int    $id        Identifier of the message
     * @param string $title     Title of the message
     * @param string $content   Attached file
     * @param string $image     URL of an image
     * @param int    $timestamp Update timestamp
     */
    public function __construct(
        $id, $title, $content,
        $image, $timestamp)
    {
        $this->id        = $id;
        $this->title     = $title;
        $this->content   = $content;
        $this->image     = $image;
        $this->timestamp = $timestamp;
    }
}
