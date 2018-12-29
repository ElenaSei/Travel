<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 29.12.18
 * Time: 16:10
 */

namespace TravelBundle\Service;

use TravelBundle\Entity\Place;
use TravelBundle\Entity\Booking;
use TravelBundle\Entity\User;

interface BookingServiceInterface
{
    public function findRecentByPlace(Place $place): array;

    public function findPastByPlace(Place $place): array;

    public function findRecentByRenter(User $user): array;

    public function findPastByRenter(User $user): array;

    public function save(Booking $reservation): bool;
}