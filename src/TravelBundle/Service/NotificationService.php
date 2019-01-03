<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 2.01.19
 * Time: 16:49
 */

namespace TravelBundle\Service;

use TravelBundle\Entity\Notification;
use TravelBundle\Entity\User;
use TravelBundle\Repository\NotificationRepository;

class NotificationService implements NotificationServiceInterface
{
    private $notificationRepository;

    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    public function add(Notification $notification)
    {
        return $this->notificationRepository->save($notification);
    }

    public function findUnread(User $user)
    {
        return $this->notificationRepository->findUnread($user);
    }
}