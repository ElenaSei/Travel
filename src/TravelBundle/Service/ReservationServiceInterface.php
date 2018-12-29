<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 29.12.18
 * Time: 16:10
 */

namespace TravelBundle\Service;

use TravelBundle\Entity\Place;
use TravelBundle\Entity\Reservation;
use TravelBundle\Entity\User;

interface ReservationServiceInterface
{
    public function findRecentByPlace(Place $place): array;

    public function findPastByPlace(Place $place): array;

    public function findRecentByRenter(User $user): array;

    public function findPastByRenter(User $user): array;

    public function save(Reservation $reservation): bool;
}