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

use \Database\JoinClause as WhereClause;

/**
 * Data mapper for a message.
 *
 * This class follows the Data Mapper Design Pattern and exhibits a Fluent
 * Interface through Method Chaining.
 *
 * @author Marco Zanella <mz@openmailbox.org>
 */
class MessageMapper
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
     * Creates a new message.
     * @param Message $message Message to create
     * @return self This data mapper itself
     */
    public function create(Message $message)
    {
        $this->db->insert('message', [
            'title'     => $message->title,
            'content'   => $message->content,
            'image'     => $message->image,
            'timestamp' => $message->timestamp
        ]);
        $message->id = $this->db->lastInsertId();

        return $this;
    }



    /**
     * Reads a message.
     * @param string $id Identifier of the message
     * @return Message Message from the database
     */
    public function read($id)
    {
        $where = new WhereClause();
        $where->setClauses(['id' => $id]);

        $record = $this->db->select('message', [], $where);
        $record = $record[0];

        return new Message(
            $record['id'],
            $record['title'],
            $record['content'],
            $record['image'],
            $record['timestamp']
        );
    }



    /**
     * Updates a message.
     * @param Message $message Message to update
     * @return self This data mapper itself
     */
    public function update(Message $message)
    {
        $where = new WhereClause();
        $where->setClauses(['id' => $message->id]);

        $this->db->update('message', [
            'title'     => $message->title,
            'content'   => $message->content,
            'image'     => $message->image,
            'timestamp' => $message->timestamp
        ], $where);

        return $this;
    }



    /**
     * Deletes a message.
     * @param Message $message Message to delete
     * @return self This data mapper itself
     */
    public function delete(Message $message)
    {
        $where = new WhereClause();
        $where->setClauses(['id' => $message->id]);
        $this->db->delete('message', $where);

        return $this;
    }
}
