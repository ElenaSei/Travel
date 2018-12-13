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

class MessageController extends Controller
{
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

            $session = $this->getDoctrine()->getRepository(Session::class)->findOneByUsersId($sender, $recipient);

            if (empty($session)){
                $session = new Session();
                $session->addUsers($recipient)->addUsers($sender);

                $session->setIsRead(false);

                $em = $this->getDoctrine()->getManager();
                $em->persist($session);
                $em->flush();

                $sender->addSessions($session);
                $recipient->addSessions($session);
            }

            $session->setIsRead(false);

            $em = $this->getDoctrine()->getManager();
            $em->persist($session);
            $em->flush();

            $message->setSession($session);

            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

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

        $sessions = $this->getDoctrine()->getRepository(Session::class)->findByUser($user);

        $messages = [];

        /**
         * @var Session $session
         */
        foreach ($sessions as $session){
            $messages[] = $this->getDoctrine()->getRepository(Message::class)->findOneBy(['session' => $session], ['dateAdded' => 'DESC']);
        }

        return $this->render('user/all_messages.html.twig', ['messages' => $messages]);
    }

    /**
     * @Route("user/mailbox/{id}", name="user_view_message")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function messageAction($id, Request $request){
        $session = $this->getDoctrine()->getRepository(Session::class)->find($id);
        $messages = $this->getDoctrine()->getRepository(Message::class)->findBy(['session' => $session], ['dateAdded' => 'DESC']);

        $session->setIsRead(true);



        $em = $this->getDoctrine()->getManager();
        $em->merge($session);
        $em->flush();

        return $this->render('user/view_message.html.twig', ['messages' => $messages]);

    }
}
