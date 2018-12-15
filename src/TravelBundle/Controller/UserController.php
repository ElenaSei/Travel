<?php

namespace TravelBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use TravelBundle\Entity\Place;
use TravelBundle\Entity\Reservation;
use TravelBundle\Entity\Role;
use TravelBundle\Entity\User;
use TravelBundle\Form\UserType;

class UserController extends Controller
{
    /**
     * @Route("/register", name="user_register")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted()){

            if ($this->getDoctrine()->getRepository(User::class)->findOneBy(['username' => $user->getUsername()])){
                $this->addFlash('info','Username already exists!');
                return $this->render('user/register.html.twig', ['form' => $form->createView()]);
            }
            if ($this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $user->getEmail()])){
                $this->addFlash('info','Email already exists!');
                return $this->render('user/register.html.twig', ['form' => $form->createView()]);
            }

            if ($user->getPassword() !== $request->request->get('confirm_password')){
                $this->addFlash('info','Password mismatch!');
                return $this->render('user/register.html.twig', ['form' => $form->createView()]);
            }

            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPassword());

            $user->setPassword($password);

            $role = $this
                ->getDoctrine()
                ->getRepository(Role::class)
                ->findOneBy(['name' => 'ROLE_RENTER']);

            $user->addRole($role);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('security_login');
        }

        return $this->render('user/register.html.twig', ['form' => $form->handleRequest()]);
    }

    /**
     * @Route("/userTrips", name="user_trips")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function userTrips(){
        $currentUser = $this->getUser();

        $trips = $this->getDoctrine()->getRepository(Reservation::class)->findBy(['renter' => $currentUser]);

        if (empty($trips)){
            $this->addFlash('info', 'You don`t have any trips yet');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('user/trips.html.twig', ['trips' => $trips]);

    }

    /**
     * @Route("/userPlaces", name="user_places")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userPlaces(){
        $currentUser = $this->getUser();
        $places = $this->getDoctrine()->getRepository(Place::class)->findBy(['owner' => $currentUser]);

        return $this->render('user/places.html.twig', ['places' => $places]);
    }

    /**
     * @Route("/reservation/{id}", name="user_reservation")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function reservation($id){
        $place = $this->getDoctrine()->getRepository(Place::class)->findOneBy(['id' => $id]);
        $reservation = $this->getDoctrine()->getRepository(Reservation::class)->findOneBy(['place' => $place]);


        return $this->render('user/reservation.html.twig', ['reservation' => $reservation]);
    }


}
