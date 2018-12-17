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
        $currentUser = $this->getDoctrine()->getRepository(User::class)->find($this->getUser());
        $search = $this->getDoctrine()->getRepository(Search::class)->findOneBy(['user' => $currentUser]);

        if (empty($search)){
            $search = new Search();
        }

        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

//            $search->setCountry(Intl::getRegionBundle()->getCountryName($search->getCountry()));

            if ($search !== $currentUser->getSearch()){
                $currentUser->setSearch($search);
                $search->setUser($currentUser);
                $em = $this->getDoctrine()->getManager();
                $em->persist($search);
                $em->flush();
            }else{

                $em = $this->getDoctrine()->getManager();
                $em->merge($search);
                $em->flush();
            }

            return $this->redirectToRoute('place_all', ['searchId' => $search->getId()]);
        }

        return $this->render('front-end/home/index.html.twig', ['form' => $form->createView()]);
    }
}
