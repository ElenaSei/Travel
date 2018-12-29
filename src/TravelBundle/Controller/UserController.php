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
use TravelBundle\Service\PlaceServiceInterface;
use TravelBundle\Service\ReservationServiceInterface;
use TravelBundle\Service\RoleServicesInterface;
use TravelBundle\Service\UserServiceInterface;

class UserController extends Controller
{
    private $userService;
    private $roleService;
    private $reservationService;
    private $placeService;

    /**
     * UserController constructor.
     * @param UserServiceInterface $userService
     * @param RoleServicesInterface $roleServices
     * @param ReservationServiceInterface $reservationService
     * @param PlaceServiceInterface $placeService
     */
    public function __construct(UserServiceInterface $userService,
                                RoleServicesInterface $roleServices,
                                ReservationServiceInterface $reservationService,
                                PlaceServiceInterface $placeService)
    {
        $this->userService = $userService;
        $this->roleService = $roleServices;
        $this->reservationService = $reservationService;
        $this->placeService = $placeService;
    }

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

            if ($this->userService->findOneByUsername($user->getUsername())){
                $this->addFlash('info','Username is taken!');
                return $this->render('front-end/home/register.html.twig', ['form' => $form->createView()]);
            }
            if ($this->userService->findOneByEmail($user->getEmail())){
                $this->addFlash('info','Email is taken!');
                return $this->render('front-end/home/register.html.twig', ['form' => $form->createView()]);
            }

            if ($user->getPassword() !== $request->request->get('confirm_password')){
                $this->addFlash('info','Password mismatch!');
                return $this->render('front-end/home/register.html.twig', ['form' => $form->createView()]);
            }

            $password = $this
                ->get('security.password_encoder')
                ->encodePassword($user, $user->getPassword());

            $user->setPassword($password);

            $role = $this->roleService->findOneByName('ROLE_RENTER');
            $user->addRole($role);

            $this->userService->save($user);

            return $this->redirectToRoute('security_login');
        }

        return $this->render('front-end/home/register.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/userTrips", name="user_trips")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function userTrips(){
        $currentUser = $this->getUser();

        $trips['past'] = $this->reservationService->findPastByRenter($currentUser);
        $trips['recent'] = $this->reservationService->findRecentByRenter($currentUser);

        if (empty($trips)){
            $this->addFlash('info', 'You don`t have any trips yet');
        }

        return $this->render('front-end/user/trips.html.twig', ['trips' => $trips]);

    }

    /**
     * @Route("/userPlaces", name="user_places")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userPlaces(){
        $currentUser = $this->getUser();
        $places = $this->placeService->findAllByOwner($currentUser);

        return $this->render('front-end/user/places.html.twig', ['places' => $places]);
    }
}
