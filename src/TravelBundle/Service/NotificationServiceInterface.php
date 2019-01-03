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
    public function add(Notification $notification);

    public function findUnread(User $user);
}