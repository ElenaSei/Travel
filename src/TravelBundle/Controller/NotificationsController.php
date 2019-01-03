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

class NotificationsController extends Controller
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

        $msgUnread = $this->messageService->findUnread($this->getUser());

        $msgCount = count($msgUnread);

        return $this->render('front-end/message/navbar.html.twig',
            ['notificationsCount' => $notificationsCount,
             'msgCount' => $msgCount]);
    }

    /**
     * @Route("/notifications", name="user_notifications")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function notificationAction(){

    }

}