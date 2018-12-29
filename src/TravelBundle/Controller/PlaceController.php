<?php

namespace TravelBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use TravelBundle\Entity\Place;
use TravelBundle\Entity\Reservation;
use TravelBundle\Form\PlaceType;
use TravelBundle\Form\ReservationType;
use TravelBundle\Service\AddressServiceInterface;
use TravelBundle\Service\PlaceServiceInterface;
use TravelBundle\Service\ReservationServiceInterface;
use TravelBundle\Service\RoleServicesInterface;
use TravelBundle\Service\SearchServiceInterface;
use TravelBundle\Service\UserServiceInterface;

class PlaceController extends Controller
{
    private $userService;
    private $placeService;
    private $searchService;
    private $roleService;
    private $addressService;
    private $reservationService;

    public function __construct(UserServiceInterface $userService,
                                PlaceServiceInterface $placeService,
                                RoleServicesInterface $roleServices,
                                SearchServiceInterface $searchService,
                                AddressServiceInterface $addressService,
                                ReservationServiceInterface $reservationService)
    {
        $this->userService = $userService;
        $this->placeService = $placeService;
        $this->searchService = $searchService;
        $this->roleService = $roleServices;
        $this->addressService = $addressService;
        $this->reservationService = $reservationService;
    }

    /**
     * @Route("/addPlace", name="place_add")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $place = new Place();
        $form = $this->createForm(PlaceType::class, $place);
        $form->handleRequest($request);

        if ($form->isSubmitted()){

            if ($this->placeService->findOneByName($place->getName()) !== null){
               $this->addFlash('info','This name has already taken!');
            }

            $currentUser = $this->userService->findOneByUser($this->getUser());

            $place->setOwner($currentUser);
            $place->setPhoto($this->generateImg($place->getPhoto()));

            $address = $place->getAddress();
            $place->setAddress($address);

            if (!$currentUser->isOwner())
            {
                $role = $this->roleService->findOneByName('ROLE_OWNER');
                $currentUser->addRole($role);
            }

            $this->addressService->save($address);
            $this->placeService->save($place);

            return $this->redirectToRoute('homepage');
        }

        return $this->render('front-end/place/add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/details/{id}", name="place_details")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction($id){
        $place = $this->placeService->findOneById($id);

        if ($place === null){
            throw new \Exception('Undefined place');
        }

        $reservations['past'] = $this->reservationService->findPastByPlace($place);
        $reservations['recent'] = $this->reservationService->findRecentByPlace($place);

        return $this->render('front-end/place/details.html.twig', ['place' => $place, 'reservations' => $reservations]);
    }

    /**
     * @Route("/edit/{id}", name="place_edit")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id, Request $request){
        $place = $this->placeService->findOneById($id);

        $photo = $place->getPhoto();

        $form = $this->createForm(PlaceType::class, $place);
        $form->handleRequest($request);

        $reservations = $this->reservationService->findRecentByPlace($place);

        if ($place === null){
            $this->addFlash('info','Undefined place');

        }elseif ($place->getOwner() !== $this->getUser()){
            $this->addFlash('info', 'You are not owner of this place!');

        }elseif (!empty($reservations)){
            $this->addFlash('info', 'This place has been booked. You cannot edit it.');

        }elseif ($form->isSubmitted()){

            if ($place->getPhoto() === null){
                $place->setPhoto($photo);
            }else{
                $place->setPhoto($this->generateImg($place->getPhoto()));
            }

            $this->placeService->update($place);

            return $this->redirectToRoute('homepage');
        }

        return $this->render('front-end/place/edit.html.twig', ['place' => $place, 'form' => $form->createView()]);
    }

    /**
     * @Route("/delete/{id}", name="place_delete")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id){
        $place = $this->placeService->findOneById($id);

        $reservations = $this->reservationService->findRecentByPlace($place);

        if (!empty($reservations)){
            $this->addFlash('info', 'The place is booked. You cannot deleted it.');

            return $this->redirectToRoute('place_details', ['id' => $id]);
        }

        $this->placeService->delete($place);

        $this->addFlash('info', 'The place was deleted successfully!');

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/place/all/{searchId}", name="place_all")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param int $searchId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function allSelectedAction($searchId){
        $search = $this->searchService->findOneById($searchId);

        $places = $this->placeService->findAllBySearch($search);

       return $this->render('front-end/place/all.html.twig', ['places' => $places, 'search' => $search]);
    }


    /**
     * @Route("/place/{id}/book/{searchId}", name="place_book")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $id
     * @param $searchId
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function bookAction($id, $searchId, Request $request){
        $place = $this->placeService->findOneById($id);
        $search = $this->searchService->findOneById($searchId);

        $reservation = new Reservation();

        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if (isset($request) && $request->isMethod('post')){
            $currentUser = $this->getUser();

            $startDate = $search->getStartDate();
            $endDate = $search->getEndDate();


            $reservation->setRenter($currentUser);
            $reservation->setPlace($place);
            $reservation->setStartDate($startDate);
            $reservation->setEndDate($endDate);

            $totalPrice = str_replace('$', '', $request->request->get('totalPrice'));
            $reservation->setTotalMoney($totalPrice);

            $this->reservationService->save($reservation);

            $this->addFlash('info', 'Have a nice a trip!');
            return $this->redirectToRoute('homepage');

        }

        return $this->render('front-end/place/book.html.twig', ['place' => $place, 'search' => $search]);
    }


    public function generateImg($file)
    {

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('photos_directory'), $fileName);

        return $fileName;
    }

}
