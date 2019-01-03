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
use TravelBundle\Entity\User;
use TravelBundle\Repository\MessageRepository;

class MessageService implements MessageServiceInterface
{
    private $messageRepository;
    private $sessionService;

    public function __construct(MessageRepository $messageRepository,
                                SessionServiceInterface $sessionService)
    {
        $this->messageRepository = $messageRepository;
        $this->sessionService = $sessionService;
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

    public function findAllFromSession(Session $session, array $orderBy = null): array
    {
        return $this->messageRepository->findBy(['session' => $session], $orderBy);
    }

    public function findUnread(User $user): array
    {
        $sessionsUnread = $this->sessionService->findUnread($user);

        $messages = [];

        foreach ($sessionsUnread as $session){
            /**
             * @var Message $message
             */
            $message = $this->messageRepository->findOneBy(['session' => $session], ['dateAdded' => 'DESC']);

            if ($message->getRecipient() === $user){
                $messages[] = $message;
            }
        }

        return $messages;
    }

    public function save(Message $message): bool
    {
        return $this->messageRepository->save($message);
    }
}