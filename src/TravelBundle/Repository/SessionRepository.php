<?php

namespace TravelBundle\Repository;


/**
 * SessionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SessionRepository extends \Doctrine\ORM\EntityRepository
{
    public function findByUser($user){
        return $this->createQueryBuilder('session')
            ->where(':user MEMBER OF session.users')
            ->setParameter(':user', $user)
            ->addOrderBy('session.isRead', false)
            ->addOrderBy('session.lastDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findOneByUsersId($recipient, $sender){
        return $this->createQueryBuilder('session')
            ->where(':recipient MEMBER OF session.users')
            ->andWhere(':sender MEMBER OF session.users')
            ->setParameters([':recipient' => $recipient, ':sender' => $sender])
            ->getQuery()
            ->getOneOrNullResult();
    }
}
