<?php

namespace TravelBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use TravelBundle\Entity\Place;
use TravelBundle\Entity\Reservation;
use TravelBundle\Entity\Role;
use TravelBundle\Entity\Search;
use TravelBundle\Entity\User;
use TravelBundle\Form\PlaceType;
use TravelBundle\Form\ReservationType;

class PlaceController extends Controller
{
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

            if ($this
                    ->getDoctrine()
                    ->getRepository(Place::class)
                    ->findOneBy(['name' => $place->getName()]) !== null){
               $this->addFlash('info','This name has already taken!');
            }

            $currentUser = $this->getUser();

            $user = $this
                ->getDoctrine()
                ->getRepository(User::class)
                ->find($currentUser->getId());

            $place->setOwner($currentUser);

            $place->setPhoto($this->generateImg($place->getPhoto()));

            $address = $place->getAddress();

            $place->setAddress($address);


            if (!$currentUser->isOwner()){
                $role = $this
                    ->getDoctrine()
                    ->getRepository(Role::class)
                    ->findOneBy(['name' => 'ROLE_OWNER']);

                /** @var User $user */
                $user->addRole($role);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($address);
            $em->persist($place);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('front-end/place/add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/view/{id}", name="place_view")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction($id){
        $place = $this->getDoctrine()->getRepository(Place::class)->findOneBy(['id' => $id]);

        if ($place === null){
            throw new \Exception('Undefined place');
        }

        return $this->render('place/view.html.twig', ['place' => $place]);
    }

    /**
     * @Route("/edit/{id}", name="place_edit")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id, Request $request){
        $place = $this->getDoctrine()->getRepository(Place::class)->findOneBy(['id' => $id]);

        if ($place === null){
            throw new \Exception('Undefined place');
        }

        if ($place->getOwner() !== $this->getUser()){
            throw new \Exception('You are not owner of this place!');
        }

        $photo = $place->getPhoto();

        $form = $this->createForm(PlaceType::class, $place);
        $form->handleRequest($request);


        if ($form->isSubmitted()){

            if ($place->getPhoto() === null){
                $place->setPhoto($photo);
            }else{
                $place->setPhoto($this->generateImg($place->getPhoto()));
            }

            $em = $this->getDoctrine()->getManager();
            $em->merge($place);
            $em->flush();

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
        $place = $this->getDoctrine()->getRepository(Place::class)->findOneBy(['id' => $id]);

        $em = $this->getDoctrine()->getManager();
        $em->remove($place);
        $em->flush();

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/place/all/{searchId}", name="place_all")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param int $searchId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function allSelectedAction($searchId){
        $search = $this->getDoctrine()->getRepository(Search::class)->find($searchId);

        $places = $this->getDoctrine()->getRepository(Place::class)->findAllBy($search);


       return $this->render('front-end/place/all.html.twig', ['places' => $places, 'search' => $search]);
    }


    /**
     * @Route("/place/{id}/book/{searchId}", name="place_book")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $id
     * @param $searchId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function bookAction($id, $searchId, Request $request){
        $place = $this->getDoctrine()->getRepository(Place::class)->find($id);
        $search = $this->getDoctrine()->getRepository(Search::class)->find($searchId);

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


            $em = $this->getDoctrine()->getManager();
            $em->persist($reservation);
            $em->flush();

            $this->addFlash('info', 'Have a nice a trip!');
            return $this->redirectToRoute('homepage');

        }

        return $this->render('place/book.html.twig', ['place' => $place, 'search' => $search]);
    }


    public function generateImg($file)
    {

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('photos_directory'), $fileName);

        return $fileName;
    }

}
