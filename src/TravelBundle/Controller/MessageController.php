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

class MessageController extends Controller
{
    private $messageService;
    private $sessionService;

    /**
     * MessageController constructor.
     * @param MessageServiceInterface $messageService
     * @param SessionServiceInterface $sessionService
     */
    public function __construct(MessageServiceInterface $messageService, SessionServiceInterface $sessionService)
    {
        $this->messageService = $messageService;
        $this->sessionService = $sessionService;
    }

    /**
     * @Route("/user/{ownerId}/message/{placeId}", name="send_message")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $placeId
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sendAction($ownerId, $placeId, Request $request)
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted()){
            $senderId = $this->getUser();
            $sender = $this->getDoctrine()->getRepository(User::class)->find($senderId);

            $recipient = $this->getDoctrine()->getRepository(User::class)->find($ownerId);

            $message->setRecipient($recipient);
            $message->setSender($sender);

            $session = $this->sessionService->findOneByUsersId($recipient, $sender);

            if (empty($session)){

                $session = new Session();
                $session->addUsers($recipient)->addUsers($sender);

                $session->setIsRead(false);

                if(!$this->sessionService->save($session)){
                    $this->addFlash('info', 'Session could not be saved!');

                    return $this->render('user/send_message.html.twig', ['form' => $form->createView(), 'placeId' => $placeId, 'ownerId' => $ownerId]);
                }

                $sender->addSessions($session);
                $recipient->addSessions($session);
            }

            $session->setIsRead(false);

            if (!$this->sessionService->update($session)){
                $this->addFlash('info', 'Session could not be update!');

                return $this->render('user/send_message.html.twig', ['form' => $form->createView(), 'placeId' => $placeId, 'ownerId' => $ownerId]);
            }

            $message->setSession($session);

            if (!$this->messageService->save($message)){
                $this->addFlash('info', 'Message could not send!');

                return $this->render('user/send_message.html.twig', ['form' => $form->createView(), 'placeId' => $placeId, 'ownerId' => $ownerId]);
            }

            $this->addFlash('info', 'Message send successfully!');
            return $this->redirectToRoute('place_view', ['id' => $placeId]);
        }

        return $this->render('user/send_message.html.twig', ['form' => $form->createView(), 'placeId' => $placeId, 'ownerId' => $ownerId]);
    }

    /**
     * @Route("/user/mailbox", name="user_mailbox")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function allAction(){
        $currentUserId = $this->getUser()->getId();

        $user = $this->getDoctrine()->getRepository(User::class)->find($currentUserId);

        $sessions = $this->sessionService->findAllByUser($user);

        $messages = $this->messageService->findOnePerSession($sessions, ['dateAdded' => 'DESC']);

        return $this->render('user/all_messages.html.twig', ['messages' => $messages]);
    }

    /**
     * @Route("user/mailbox/{id}", name="user_view_message")
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

        $messages = $this->messageService->findAllFromSession(['session' => $session], ['dateAdded' => 'ASC']);

        $session->setIsRead(true);

        if (!$this->sessionService->update($session)){
            $this->addFlash('info', 'Session could not be update!');

            return $this->redirectToRoute('user_mailbox');
        }

        return $this->render('user/view_message.html.twig', ['messages' => $messages]);

    }
}
