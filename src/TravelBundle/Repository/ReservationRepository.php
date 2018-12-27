<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 13.12.18
 * Time: 21:24
 */

namespace TravelBundle\Repository;


class ReservationRepository extends \Doctrine\ORM\EntityRepository
{
     public function findPastByPlace($place){
         return $this->createQueryBuilder('reservation')
             ->where('reservation.startDate <= :date')
             ->andWhere('reservation.place = :place')
             ->setParameters([':date' => new \DateTime('now'), ':place' => $place])
             ->getQuery()
             ->getResult();
     }

    public function findRecentByPlace($place){
        return $this->createQueryBuilder('reservation')
            ->where('reservation.startDate > :date')
            ->andWhere('reservation.place = :place')
            ->setParameters([':date' => new \DateTime('now'), ':place' => $place])
            ->getQuery()
            ->getResult();
    }

    public function findPastByUser($user){
        return $this->createQueryBuilder('reservation')
            ->where('reservation.startDate <= :date')
            ->andWhere('reservation.renter = :user')
            ->setParameters([':date' => new \DateTime('now'), ':user' => $user])
            ->orderBy('reservation.startDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findRecentByUser($user){
        return $this->createQueryBuilder('reservation')
            ->where('reservation.startDate > :date')
            ->andWhere('reservation.renter = :user')
            ->setParameters([':date' => new \DateTime('now'), ':user' => $user])
            ->orderBy('reservation.startDate', 'DESC')
            ->getQuery()
            ->getResult();
    }
}