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
 public function findPast($place){
     return $this->createQueryBuilder('reservation')
         ->where('reservation.startDate <= :date')
         ->andWhere('reservation.place = :place')
         ->setParameters([':date' => new \DateTime('now'), ':place' => $place])
         ->getQuery()
         ->getResult();
 }

    public function findRecent($place){
        return $this->createQueryBuilder('reservation')
            ->where('reservation.startDate > :date')
            ->andWhere('reservation.place = :place')
            ->setParameters([':date' => new \DateTime('now'), ':place' => $place])
            ->getQuery()
            ->getResult();
    }
}