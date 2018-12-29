<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 13.12.18
 * Time: 21:24
 */

namespace TravelBundle\Repository;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use TravelBundle\Entity\Reservation;

class ReservationRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * MessageRepository constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, new ClassMetadata(Reservation::class));
    }

     public function findPastByPlace($place){
         return $this->createQueryBuilder('reservation')
             ->where('reservation.endDate < :date')
             ->andWhere('reservation.place = :place')
             ->setParameters([':date' => new \DateTime('now'), ':place' => $place])
             ->getQuery()
             ->getResult();
     }

    public function findRecentByPlace($place){
        return $this->createQueryBuilder('reservation')
            ->where('reservation.endDate >= :date')
            ->andWhere('reservation.place = :place')
            ->setParameters([':date' => new \DateTime('now'), ':place' => $place])
            ->getQuery()
            ->getResult();
    }

    public function findPastByRenter($user){
        return $this->createQueryBuilder('reservation')
            ->where('reservation.endDate < :date')
            ->andWhere('reservation.renter = :user')
            ->setParameters([':date' => new \DateTime('now'), ':user' => $user])
            ->orderBy('reservation.startDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findRecentByRenter($user){
        return $this->createQueryBuilder('reservation')
            ->where('reservation.endDate >= :date')
            ->andWhere('reservation.renter = :user')
            ->setParameters([':date' => new \DateTime('now'), ':user' => $user])
            ->orderBy('reservation.startDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function save(Reservation $reservation){

        try{
            $this->_em->persist($reservation);
            $this->_em->flush();

            return true;
        }catch (\Exception $e){

            return false;
        }
    }
}