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

/**
 * Interface of an observer.
 *
 * This interface follows the Observer Design Pattern.
 *
 * @author Marco Zanella <mz@openmailbox.org>
 */
interface ObserverInterface
{
    /**
     * Gets updated when an observed subject notifies a change.
     * @param SubjectInterface $subject Observed subject
     */
    public function update(SubjectInterface $subject);
}



/**
 * Interface of a subject.
 *
 * This interface follows the Observer Design Pattern.
 *
 * @author Marco Zanella <mz@openmailbox.org>
 */
interface SubjectInterface
{
    /**
     * Attaches a new observer to this subject.
     * @param ObserverInterface $observer Observer to attach
     */
    public function attach(ObserverInterface $observer);



    /**
     * Detaches an observer to this subject.
     * If specified observer is not observing this subject, nothin
     * happens.
     * @param ObserverInterface $observer Observer to detach
     */
    public function detach(ObserverInterface $observer);



    /**
     * Notifies a change in this subject.
     * Every observer get notified about the change.
     */
    public function notify();
}



/**
 * An observable subject.
 * This trait implements the SubjectInterface.
 *
 * This trait follows the Observer Design Pattern.
 *
 * @author Marco Zanella <mz@openmailbox.org>
 */
trait SubjectTrait
{
    /**
     * @var array $observers List of observers.
     */
    private $observers = [];


    /**
     * Attaches a new observer to this subject.
     * @param ObserverInterface $observer Observer to attach
     * @return self This subject itself
     */
    public function attach(ObserverInterface $observer)
    {
        $this->observers[] = $observer;

        return $this;
    }



    /**
     * Detaches an observer to this subject.
     * If specified observer is not observing this subject, nothin
     * happens.
     * @param ObserverInterface $observer Observer to detach
     * @return self This subject itself
     */
    public function detach(ObserverInterface $observer)
    {
        $key = array_search($observer, $this->observers);
        unset($this->observers[$key]);

        return $this;
    }



    /**
     * Notifies a change in this subject.
     * Every observer get notified about the change.
     * @return self This subject itself
     */
    public function notify()
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }

        return $this;
    }
}
