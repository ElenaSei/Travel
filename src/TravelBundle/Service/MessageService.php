<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 14.12.18
 * Time: 14:10
 */

namespace TravelBundle\Service;

use TravelBundle\Entity\Message;
use TravelBundle\Entity\Search;
use TravelBundle\Entity\Session;
use TravelBundle\Repository\MessageRepository;

class MessageService implements MessageServiceInterface
{
    private $messageRepository;

    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    public function findOnePerSession(array $sessions, array $orderBy = null): array
    {
        $messages = [];

        /**
         * @var Session $session
         */
        foreach ($sessions as $session){
            $messages[] = $this->messageRepository->findOneBy(['session' => $session], $orderBy);
        }

        return $messages;
    }

    public function save(Message $message): bool
    {
        return $this->messageRepository->save($message);
    }

    public function findAllFromSession(Session $session, array $orderBy = null): array
    {
        return $this->messageRepository->findBy(['session' => $session], $orderBy);
    }
}