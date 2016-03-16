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
     * Creates a new sponsor.
     * @param Sponsor $sponsor Sponsor to create
     * @return self This data mapper itself
     */
    public function create(Sponsor $sponsor)
    {
        $query = 'INSERT INTO sponsor (name, URL, banner) '
               . 'VALUES (:name, :url, :banner)';
        $stm   = $this->dbh->prepare($query);
        $stm->execute([
            ':name'   => $sponsor->name,
            ':url'    => $sponsor->website,
            ':banner' => $sponsor->banner
        ]);
        $sponsor->id = $this->dbh->lastInsertId();

        return $this;
    }



    /**
     * Reads a sponsor.
     * @param int $id Identifier of the sponsor
     * @return Sponsor Sponsor from the database
     */
    public function read($id)
    {
        $query = 'SELECT * FROM sponsor WHERE id = :id';
        $stm   = $this->dbh->prepare($query);
        $stm->execute([':id' => $id]);

        $record = $stm->fetch();
        return new Sponsor(
            $id,
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
        $query = 'UPDATE sponsor '
               . 'SET name = :name, URL = :website, banner = :banner '
               . 'WHERE id = :id';
        $stm   = $this->dbh->prepare($query);
        $stm->execute([
            ':id' => $sponsor->id,
            ':name' => $sponsor->name,
            ':website' => $sponsor->website,
            ':banner'  => $sponsor->banner
        ]);

        return $this;
    }



    /**
     * Deletes a sponsor.
     * @param Sponsor Sponsor to delete
     * @return self This data mapper iteself
     */
    public function delete(Sponsor $sponsor)
    {
        $query = 'DELETE FROM sponsor WHERE id = :id';
        $stm   = $this->dbh->prepare($query);
        $stm->execute([':id' => $sponsor->id]);

        return $this;
    }



    /**
     * Searches sponsors.
     * @param array $match Matching clauses
     * @param int   $page  Page number (optional)
     * @param int   $size  Size of a page (optional)
     * @return array Sponsors matching clauses
     */
    public function search($match, $page = 1, $size = 10)
    {
        $offset = ($page - 1) * $size;
        $query = 'SELECT * FROM sponsor LIMIT ' . $size . ' OFFSET ' . $offset;

        $stm   = $this->dbh->prepare($query);
        $stm->execute();

        $sponsors = [];
        while ($row = $stm->fetch()) {
            $sponsors[] = new Sponsor(
                $row['id'],
                $row['name'],
                $row['URL'],
                $row['banner']
            );
        }
        return $sponsors;
    }
}
