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
 * HTML view for a piece of news.
 *
 * This class follows the Observer and the Model-View-Controller Design
 * Patterns, and exhibits a Fluent Interface through Method Chaining.
 *
 * @author Marco Zanella <mz@openmailbox.org>
 */
class NewsHtml implements \ObserverInterface
{
    /**
     * @var \MVC\Model\News $sponsor Sponsor
     * @todo Which type is this? Should add an interface?
     */
    private $entry;


    /**
     * Constructor.
     * @param Sponsor $sponsor Sponsor model
     * @todo Type
     */
    public function __construct($entry)
    {
        $this->entry = $entry;
    }



    /**
     * Renders the model.
     * @param int|null $index Index of this entry in a list, or null
     * @return self This view itself
     */
    public function output($index = null)
    {
        $entry = $this->entry;
        echo
<<<HTML
<article class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">$entry->title</h3>
  </div>

  <div class="panel-body">
HTML;

        // Prints image, if any
        if (!empty($entry->image)) {
            $height = "";

            if (!is_null($index)) {
                $height .= "&w=";
                switch ($index) {
                case 0:
                    $height .= "512";
                    break;
                case 1: case 2:
                    $height .= "256";
                    break;
                default:
                    $height .= "128";
                }
            }

            echo
<<<HTML
<div class="pull-right"

style="margin: .5em; overflow: hidden">
  <img src="thumbnail/$entry->image$height" data-zoom="$entry->image"
    alt="$entry->title"
    class="img-responsive img-zoomable" />
</div>
HTML;
        }

        // Prints date and time, if entry is event
        if ($entry instanceof \MVC\Model\Event) {
            echo
<<<HTML
<div><strong>Dove:</strong> $entry->place</div>
<div><strong>Quando:</strong> $entry->time</div>
HTML;
        }

        // Prints description
        echo
<<<HTML
    <p>$entry->content</p>
  </div>
</article>
HTML;

        return $this;
    }



    /**
     * Gets updated when an observed subject notifies a change.
     * @param \SubjectInterface $subject Observed subject
     * @return self This view itself
     */
    public function update(\SubjectInterface $subject)
    {
        $this->entry = $subject;
        return $this;
    }
}
