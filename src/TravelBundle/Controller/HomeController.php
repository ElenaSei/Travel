<?php

namespace TravelBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use TravelBundle\Entity\Search;
use TravelBundle\Entity\User;
use TravelBundle\Form\SearchType;

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $currentUser = $this->getDoctrine()->getRepository(User::class)->find($this->getUser());

            if ($currentUser->getSearch() === null){
                $currentUser->setSearch($search);
                $search->setUser($currentUser);
            }else{
                $search = $this->getDoctrine()->getRepository(Search::class)->findOneBy(['user' => $currentUser]);
                $form = $this->createForm(SearchType::class, $search);
                $form->handleRequest($request);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($search);
            $em->flush();


            return $this->redirectToRoute('place_all', ['searchId' => $search->getId()]);
        }

        return $this->render('front-end/home/index.html.twig', ['form' => $form->createView()]);
    }
}
