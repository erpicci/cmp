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
 * Data mapper for a sponsor.
 *
 * This class follows the Data Mapper Design Patter and exhibits a Fluent
 * Interface through Method Chaining.
 *
 * @author Marco Zanella <mz@openmailbox.org>
 */
class SponsorMapper
{
    /**
     * @var \Database\DatabaseInterface $db A database
     */
    private $dbh;


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
     * Creates a new sponsor.
     * @param Sponsor $sponsor Sponsor to create
     * @return self This data mapper itself
     */
    public function create(Sponsor $sponsor)
    {
        $this->db->insert('sponsor', [
            'name'   => $sponsor->name,
            'url'    => $sponsor->website,
            'banner' => $sponsor->banner
        ]);
        $sponsor->id = $this->db->lastInsertId();

        return $this;
    }



    /**
     * Reads a sponsor.
     * @param int $id Identifier of the sponsor
     * @return Sponsor Sponsor from the database
     */
    public function read($id)
    {
        $where = new \Database\JoinClause();
        $where->setClauses(['id' => $id]);

        $record = $this->db->select('sponsor', [], $where);
        $record = $record[0];

        return new Sponsor(
            $record['id'],
            $record['name'],
            $record['URL'],
            $record['banner']
        );
    }



    /**
     * Updates a sponsor.
     * @param Sponsor Sponsor to update
     * @return self This data mapper itself
     */
    public function update(Sponsor $sponsor)
    {
        $where = new \Database\JoinClause();
        $where->setClauses(['id' => $sponsor->id]);

        $this->db->update('sponsor', [
            'name'   => $sponsor->name,
            'URL'    => $sponsor->website,
            'banner' => $sponsor->banner
        ], $where);

        return $this;
    }



    /**
     * Deletes a sponsor.
     * @param Sponsor Sponsor to delete
     * @return self This data mapper iteself
     */
    public function delete(Sponsor $sponsor)
    {
        $where = new \Database\JoinClause();
        $where->setClauses(['id' => $sponsor->id]);
        $this->db->delete('sponsor', $where);

        return $this;
    }
}
