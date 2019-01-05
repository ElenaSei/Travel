<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 2.01.19
 * Time: 16:27
 */

namespace TravelBundle\Service;

use TravelBundle\Entity\Notification;
use TravelBundle\Entity\User;

interface NotificationServiceInterface
{
    public function add(Notification $notification): bool;

    public function update(Notification $notification): bool;

    public function findUnread(User $user): array;

    public function findOneById(int $id): ?Notification;
}