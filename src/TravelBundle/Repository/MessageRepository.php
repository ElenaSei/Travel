<?php

namespace TravelBundle\Repository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use TravelBundle\Entity\Message;
use TravelBundle\Entity\Session;
use TravelBundle\Entity\User;

/**
 * MessageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MessageRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * MessageRepository constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, new ClassMetadata(Message::class));
    }

    public function findOneBySessionAndRecipient(Session $session, User $user){
        return $this->createQueryBuilder('message')
            ->where('message.recipient = :user AND message.session = :session')
            ->setParameters(['user' => $user, 'session' => $session])
            ->getQuery()
            ->getResult();
    }

    public function save(Message $message){

        try{
            $this->_em->persist($message);
            $this->_em->flush();

            return true;
        }catch (\Exception $e){

            return false;
        }

    }
}
