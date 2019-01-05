<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 3.01.19
 * Time: 16:21
 */

namespace TravelBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use TravelBundle\Service\MessageServiceInterface;
use TravelBundle\Service\NotificationServiceInterface;
use TravelBundle\Service\SessionServiceInterface;

class NavBarController extends Controller
{
    private $notificationService;
    private $sessionService;
    private $messageService;

    /**
     * UserController constructor.
     * @param NotificationServiceInterface $notificationService
     * @param SessionServiceInterface $sessionService
     * @param MessageServiceInterface $messageService
     */
    public function __construct(NotificationServiceInterface $notificationService,
                                SessionServiceInterface $sessionService,
                                MessageServiceInterface $messageService)
    {
        $this->notificationService = $notificationService;
        $this->sessionService = $sessionService;
        $this->messageService = $messageService;
    }

    /**
     * @Route( name="navbar_notifications")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function notificationCountAction(){
        $notificationsUnread = $this->notificationService->findUnread($this->getUser());
        $notificationsCount = count($notificationsUnread);

        return $this->render('front-end/navbar/notifications.html.twig',
            ['notificationsCount' => $notificationsCount,
             'notifications' => $notificationsUnread]);
    }

    /**
     * @Route( name="navbar_messages")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function messagesCountAction(){
        $msgUnread = $this->messageService->findUnread($this->getUser());
        $msgCount = count($msgUnread);

        return $this->render('front-end/navbar/messages.html.twig',
            ['msgCount' => $msgCount]);
    }

}