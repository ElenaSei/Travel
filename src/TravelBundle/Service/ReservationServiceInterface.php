<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 29.12.18
 * Time: 16:10
 */

namespace TravelBundle\Service;


use Doctrine\Common\Collections\ArrayCollection;
use TravelBundle\Entity\Place;
use TravelBundle\Entity\Reservation;
use TravelBundle\Entity\User;

interface ReservationServiceInterface
{
    public function findRecentByPlace(Place $place): ArrayCollection;

    public function findPastByPlace(Place $place): ArrayCollection;

    public function findRecentByRenter(User $user): ArrayCollection;

    public function findPastByRenter(User $user): ArrayCollection;

    public function save(Reservation $reservation): bool;
}