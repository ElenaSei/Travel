<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 14.12.18
 * Time: 14:08
 */

namespace TravelBundle\Service;

use TravelBundle\Entity\Message;
use TravelBundle\Entity\Session;
use TravelBundle\Entity\User;

interface MessageServiceInterface
{
    public function findOnePerSession(array $sessions, array $orderBy = null): array;

    public function findAllFromSession(Session $session, array $orderBy = null): array;

    public function findUnread(User $user): array;

    public function save(Message $message): bool;

}