<?php

namespace TravelBundle\Repository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use TravelBundle\Entity\Session;


/**
 * SessionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SessionRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * MessageRepository constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, new ClassMetadata(Session::class));
    }

    public function findAllByUser($user){
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
            ->where(':recipient MEMBER OF session.users AND :sender MEMBER OF session.users')
            ->setParameters([':recipient' => $recipient, ':sender' => $sender])
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findUnread($user){
        return $this->createQueryBuilder('session')
            ->where(':user MEMBER OF session.users')
            ->andWhere('session.isRead = false')
            ->setParameter(':user', $user)
            ->getQuery()
            ->getResult();
    }

    public function save(Session $message){

        try{
            $this->_em->persist($message);
            $this->_em->flush();

            return true;
        }catch (\Exception $e){

            return false;
        }

    }

    public function update(Session $session){
        try{
            $this->_em->merge($session);
            $this->_em->flush();

            return true;
        }catch (\Exception $e){

            return false;
        }
    }
}
