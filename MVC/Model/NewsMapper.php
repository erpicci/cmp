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

/**
 * Data mapper for news.
 *
 * This class follows the Data Mapper Design Pattern and exhibits a Fluent
 * Interface trough Method Chaining.
 *
 * @author Marco Zanella <mz@openmailbox.org>
 */
class NewsMapper
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
     * Searches news.
     * News can be both messages and events marked as visible.
     * @param \Database\WhereClauseInterface $where   Matching clauses
     * @param int   $page    Page number
     * @param int   $size    Size of a page
     * @param array $sort    Array of fields
     * @param bool  $reverse True if array must be reversed
     * @return array Array of events and messages
     */
    public function search(
        \Database\WhereClauseInterface $where,
        $page       = 1,
        $size       = 10,
        array $sort = ['timestamp'],
        $reverse    = false)
    {
        $news = [];
        $message_mapper = new MessageMapper($this->db);
        $event_mapper   = new EventMapper($this->db);


        // Search clauses
        $where_sql = $where->toSql();
        $where_msg = '';
        $where_ev  = 'WHERE visible = 1';
        if (!empty($where_sql)) {
            $where_msg = 'WHERE ' . $where_sql . ' ';
            $where_ev .= ' AND ' . $where_sql . ' ';
        }

        // Ordering
        $order = '';
        if (!empty($sort)) {
            $order = 'ORDER BY ' . implode(', ', $sort) . ' '
                   . ($reverse ? '' : 'DESC');
        }

        // Limit and offset
        $limit = $size;
        $offset = ($page - 1) * $size;


        // Prepares query
        $query = '(SELECT id, timestamp, "m" FROM message ' . $where_msg . ') '
               . 'UNION '
               . '(SELECT id, timestamp, "e" FROM event ' . $where_ev . ') '
               . $order
               . ' LIMIT ' . $limit . ' OFFSET ' . $offset;
        $stm   = $this->db->prepare($query);
        $stm->execute($where->toParameters());


        // Builds messages and events
        while ($record = $stm->fetch()) {
            if ($record['m'] === 'm') {
                $news[] = $message_mapper->read($record['id']);
            } elseif ($record['m'] === 'e') {
                $news[] = $event_mapper->read($record['id']);
            }
        }

        return $news;
    }
}
