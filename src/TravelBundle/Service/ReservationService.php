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
use TravelBundle\Entity\Reservation;
use TravelBundle\Entity\User;
use TravelBundle\Repository\ReservationRepository;

class ReservationService implements ReservationServiceInterface
{

    private $reservationRepository;

    /**
     * ReservationService constructor.
     * @param $reservationRepository
     */
    public function __construct(ReservationRepository $reservationRepository)
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

    public function save(Reservation $reservation): bool
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