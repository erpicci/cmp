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
namespace MVC\View;

require_once __DIR__ . '/../../ObserverPattern.php';

/**
 * JSON view for a sponsor.
 *
 * This class follows the Observer and the Model-View-Controller Design
 * Patterns, and exhibits a Fluent Interface through Method Chaining.
 *
 * @author Marco Zanella <mz@openmailbox.org>
 */
class SponsorJson implements \ObserverInterface
{
    /**
     * @var \MVC\Model\Sponsor $sponsor Sponsor
     */
    private $sponsor;


    /**
     * Constructor.
     * @param Sponsor $sponsor Sponsor model
     */
    public function __construct(\MVC\Model\Sponsor $sponsor)
    {
        $this->sponsor = $sponsor;
    }



    /**
     * Renders the model.
     * @return self This view itself
     */
    public function output()
    {
        echo json_encode([
            'id' => $this->sponsor->id,
            'name' => $this->sponsor->name,
            'website' => $this->sponsor->website,
            'banner'  => $this->sponsor->banner
        ]);

        return $this;
    }



    /**
     * Gets updated when an observed subject notifies a change.
     * @param \SubjectInterface $subject Observed subject
     * @return self This view itself
     */
    public function update(\SubjectInterface $subject)
    {
        $this->sponsor = $subject;
        return $this;
    }
}
