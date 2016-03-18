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
 * Data mapper for a musicscore.
 *
 * This class follows the Data Mapper Design Pattern and exhibits a Fluent
 * Interface through Method Chaining.
 *
 * @author Marco Zanella <mz@openmailbox.org>
 */
class MusicscoreMapper
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
     * Creates a new musicscore.
     * @param Musicscore $musicscore Musicscore to create
     * @return self This data mapper itself
     */
    public function create(Musicscore $musicscore)
    {
        $this->db->insert('musicscore', [
            'file'        => $musicscore->content,
            'name'        => $musicscore->name,
            'description' => $musicscore->description,
            'timestamp'   => $musicscore->timestamp
        ]);
        $musicscore->id = $this->db->lastInsertId();

        return $this;
    }



    /**
     * Reads a musicscore.
     * @param string $id Identifier of the musicscore
     * @return Musicscore Musicscore from the database
     */
    public function read($id)
    {
        $where = new \Database\JoinClause();
        $where->setClauses(['id' => $id]);

        $record = $this->db->select('musicscore', [], $where);
        $record = $record[0];

        return new Musicscore(
            $record['id'],
            $record['file'],
            $record['name'],
            $record['description'],
            $record['timestamp']
        );
    }



    /**
     * Updates a musicscore.
     * @param Musicscore $musicscore Musicscore to update
     * @return self This data mapper itself
     */
    public function update(Musicscore $musicscore)
    {
        $where = new \Database\JoinClause();
        $where->setClauses(['id' => $musicscore->id]);

        $this->db->update('musicscore', [
            'file'        => $musicscore->content,
            'name'        => $musicscore->name,
            'description' => $musicscore->description,
            'timestamp'   => $musicscore->timestamp
        ], $where);

        return $this;
    }



    /**
     * Deletes a musicscore.
     * @param Musicscore $musicscore Musicscore to delete
     * @return self This data mapper itself
     */
    public function delete(Musicscore $musicscore)
    {
        $where = new \Database\JoinClause();
        $where->setClauses(['id' => $musicscore->id]);
        $this->db->delete('musicscore', $where);

        return $this;
    }
}
