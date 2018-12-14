<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 14.12.18
 * Time: 14:08
 */

namespace TravelBundle\Service;

use TravelBundle\Entity\Message;

interface MessageServiceInterface
{
    public function findOnePerSession(array $criteria, array $orderBy = null);

    public function findAllFromSession(array $criteria, array $orderBy = null);

    public function save(Message $message): bool;

}