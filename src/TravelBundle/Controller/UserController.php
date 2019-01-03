<?php

namespace TravelBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use TravelBundle\Entity\Place;
use TravelBundle\Entity\Booking;
use TravelBundle\Entity\Role;
use TravelBundle\Entity\User;
use TravelBundle\Form\UserType;
use TravelBundle\Service\NotificationServiceInterface;
use TravelBundle\Service\PlaceServiceInterface;
use TravelBundle\Service\BookingServiceInterface;
use TravelBundle\Service\RoleServicesInterface;
use TravelBundle\Service\UserServiceInterface;

class UserController extends Controller
{
    private $userService;
    private $roleService;
    private $bookingService;
    private $placeService;
    private $notificationService;

    /**
     * UserController constructor.
     * @param UserServiceInterface $userService
     * @param RoleServicesInterface $roleServices
     * @param BookingServiceInterface $bookingService
     * @param PlaceServiceInterface $placeService
     * @param NotificationServiceInterface $notificationService
     */
    public function __construct(UserServiceInterface $userService,
                                RoleServicesInterface $roleServices,
                                BookingServiceInterface $bookingService,
                                PlaceServiceInterface $placeService,
                                NotificationServiceInterface $notificationService)
    {
        $this->userService = $userService;
        $this->roleService = $roleServices;
        $this->bookingService = $bookingService;
        $this->placeService = $placeService;
        $this->notificationService = $notificationService;
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
    public function tripsAction(){
        $currentUser = $this->getUser();

        $trips['past'] = $this->bookingService->findPastByRenter($currentUser);
        $trips['recent'] = $this->bookingService->findRecentByRenter($currentUser);

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
    public function placesAction(){
        $currentUser = $this->getUser();
        $places = $this->placeService->findAllByOwner($currentUser);

        return $this->render('front-end/user/places.html.twig', ['places' => $places]);
    }
}
