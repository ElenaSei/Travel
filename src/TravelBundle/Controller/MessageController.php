<?php

namespace TravelBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use TravelBundle\Entity\Message;
use TravelBundle\Entity\Session;
use TravelBundle\Entity\User;
use TravelBundle\Form\MessageType;
use TravelBundle\Service\MessageServiceInterface;
use TravelBundle\Service\SessionServiceInterface;
use TravelBundle\Service\UserServiceInterface;

class MessageController extends Controller
{
    private $messageService;
    private $sessionService;
    private $userService;

    /**
     * MessageController constructor.
     * @param MessageServiceInterface $messageService
     * @param SessionServiceInterface $sessionService
     * @param UserServiceInterface $userService
     */
    public function __construct(MessageServiceInterface $messageService,
                                SessionServiceInterface $sessionService,
                                UserServiceInterface $userService)
    {
        $this->messageService = $messageService;
        $this->sessionService = $sessionService;
        $this->userService = $userService;
    }

    /**
     * @Route("/message/{recipientId}", name="send_message")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $recipientId
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sendAction($recipientId, Request $request)
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        /**
         * @var User $recipient;
         */
        $recipient = $this->userService->findOneById($recipientId);
        $currentUser = $this->getUser();

        if($recipient === $currentUser){
            $this->addFlash('info', 'You cannot contact yourself!');

            return $this->render('front-end/message/send.html.twig', ['form' => $form->createView(), 'recipient' => $recipient]);
        }

        if ($form->isSubmitted()){
            /**
             * @var User $sender;
             */
            $sender = $this->userService->findOneByUser($currentUser);

            $message->setRecipient($recipient);
            $message->setSender($sender);

            $session = $this->sessionService->findOneByUsersId($recipient, $sender);

            if (empty($session)){

                $session = new Session();
                $session->addUsers($recipient)->addUsers($sender);

                $this->sessionService->save($session);

                $sender->addSessions($session);
                $recipient->addSessions($session);
            }

            $session->setIsRead(false);
            $this->sessionService->update($session);

            $message->setSession($session);

            $this->messageService->save($message);

            $this->addFlash('info', 'Message send successfully!');
            return $this->redirectToRoute('user_mailbox');
        }

        return $this->render('front-end/message/send.html.twig', ['form' => $form->createView(), 'recipient' => $recipient]);
    }

    /**
     * @Route("/user/mailbox", name="user_mailbox")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function allAction(){
        $currentUser = $this->getUser();

        $user = $this->userService->findOneByUser($currentUser);

        $sessions = $this->sessionService->findAllByUser($user);

        $messages = $this->messageService->findOnePerSession($sessions, ['dateAdded' => 'DESC']);

        return $this->render('front-end/message/all.html.twig', ['messages' => $messages]);
    }

    /**
     * @Route("user/mailbox/{id}", name="user_view_message")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function messageAction($id, Request $request){

        /**
         * @var Session $session
         */
        $session = $this->sessionService->findOneById($id);

        if (empty($session)){
            $this->addFlash('info', 'Session does not exist!');

            return $this->redirectToRoute('user_mailbox');
        }
        /**
         * @var Message $message
         */
        $message = $this->messageService->findOnePerSession([$session], ['dateAdded' => 'DESC'])[0];

//        dump($message);
//        exit;
        if ($message->getRecipient() === $this->getUser()){
            $session->setIsRead(true);
        }

        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted()){
            $session->setIsRead(false);
            $message->setSession($session);
            $message->setSender($this->getUser());

            foreach ($session->getUsers() as $user){
                if ($user !== $this->getUser()){
                    $message->setRecipient($user);
                    break;
                }
            }

            $this->messageService->save($message);

        }

        $messages = $this->messageService->findAllFromSession($session, ['dateAdded' => 'ASC']);


        if (!$this->sessionService->update($session)){
            $this->addFlash('info', 'Session could not be update!');

            return $this->redirectToRoute('user_mailbox');
        }


        return $this->render('front-end/message/view.html.twig', ['messages' => $messages, 'form' => $form->createView(), 'id' => $session->getId()]);

    }
}
