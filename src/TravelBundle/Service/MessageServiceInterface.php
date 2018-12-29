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

interface MessageServiceInterface
{
    public function findOnePerSession(array $sessions, array $orderBy = null): array;

    public function findAllFromSession(Session $session, array $orderBy = null): array;

    public function save(Message $message): bool;

}