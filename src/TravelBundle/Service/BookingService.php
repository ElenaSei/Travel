<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 29.12.18
 * Time: 16:11
 */

namespace TravelBundle\Service;

use Doctrine\Common\Collections\ArrayCollection;
use TravelBundle\Entity\Place;
use TravelBundle\Entity\Booking;
use TravelBundle\Entity\User;
use TravelBundle\Repository\BookingRepository;

class BookingService implements BookingServiceInterface
{

    private $reservationRepository;

    /**
     * BookingService constructor.
     * @param $reservationRepository
     */
    public function __construct(BookingRepository $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;
    }


    public function findRecentByPlace(Place $place): array
    {
        $reservation = $this->reservationRepository->findRecentByPlace($place);

        return $reservation;
    }

    public function findPastByPlace(Place $place): array
    {
        $reservation = $this->reservationRepository->findPastByPlace($place);

        return $reservation;
    }

    public function save(Booking $reservation): bool
    {
        return $this->reservationRepository->save($reservation);
    }

    public function findRecentByRenter(User $user): array
    {
        return $this->reservationRepository->findRecentByRenter($user);
    }

    public function findPastByRenter(User $user): array
    {
        return $this->reservationRepository->findPastByRenter($user);
    }
}