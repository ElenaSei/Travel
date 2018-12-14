<?php

namespace TravelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Intl\Intl;
use Symfony\Component\Routing\Annotation\Route;
use TravelBundle\Entity\Place;
use TravelBundle\Entity\Reservation;
use TravelBundle\Entity\Role;
use TravelBundle\Entity\User;
use TravelBundle\Form\PlaceType;
use TravelBundle\Form\ReservationType;

class PlaceController extends Controller
{
    /**
     * @Route("/addPlace", name="place_add")
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
                throw new \Exception('This name has already taken!');
            }

            $currentUser = $this->getUser();

            $user = $this
                ->getDoctrine()
                ->getRepository(User::class)
                ->find($currentUser->getId());

            $place->setCountry(Intl::getRegionBundle()->getCountryName($place->getCountry()));

            $place->setOwner($currentUser);


//
//            $uri = $request->getRequestUri();
//            $uri = str_replace('/addPlace?place%5Bname%5D=a&place%5Bdescription%5D=a&place%5Baddress%5D=a&place%5Bprice%5D=1', '', $uri);
//            $files = array_filter(explode('&place%5Bphotos%5D=', $uri));


            $place->setPhoto($this->generateImg($place->getPhoto()));



            if (!$currentUser->isOwner()){
                $role = $this
                    ->getDoctrine()
                    ->getRepository(Role::class)
                    ->findOneBy(['name' => 'ROLE_OWNER']);

                /** @var User $user */
                $user->addRole($role);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($place);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('place/add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/view/{id}", name="place_view")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction($id, Request $request){
        $place = $this->getDoctrine()->getRepository(Place::class)->findOneBy(['id' => $id]);
        $user = $this->getUser();

        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);



        if (isset($request) && $request->isMethod('post')){

            $daterange = explode(' - ', $request->get('daterange'));
            $startDate = $daterange[0];
            $endDate = $daterange[1];

            $reservation->setRenter($user);
            $reservation->setPlace($place);
            $reservation->setStartDate($startDate);
            $reservation->setEndDate($endDate);

            $em = $this->getDoctrine()->getManager();
            $em->persist($reservation);
            $em->flush();

            return $this->redirectToRoute('homepage');

        }

        if ($place === null){
            throw new \Exception('Undefined place');
        }

        return $this->render('place/view.html.twig', ['place' => $place, 'form' => $form->createView()]);
    }

    /**
     * @Route("/edit/{id}", name="place_edit")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id, Request $request){
        $place = $this->getDoctrine()->getRepository(Place::class)->findOneBy(['id' => $id]);

        $photo = $place->getPhoto();

        if ($place === null){
            throw new \Exception('Undefined place');
        }

        if ($place->getOwner() !== $this->getUser()){
            throw new \Exception('You are not owner of this place!');
        }

        $form = $this->createForm(PlaceType::class, $place);
        $form->handleRequest($request);


        if ($form->isSubmitted()){
            $currentUser = $this->getUser();
            $place->setOwner($currentUser);

            if ($place->getPhoto() === null){
                $place->setPhoto($photo);
            }else{
                $place->setPhoto($this->generateImg($place->getPhoto()));
            }


            $place->setCountry(Intl::getRegionBundle()->getCountryName($place->getCountry()));

            $em = $this->getDoctrine()->getManager();
            $em->merge($place);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('place/edit.html.twig', ['place' => $place, 'form' => $form->createView()]);
    }

    /**
     * @Route("/delete/{id}", name="place_delete")
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

    public function generateImg($file)
    {

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('photos_directory'), $fileName);

        return $fileName;
    }

}