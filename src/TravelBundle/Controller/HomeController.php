<?php

namespace TravelBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use TravelBundle\Entity\Search;
use TravelBundle\Form\SearchType;
use TravelBundle\Service\SearchServiceInterface;
use TravelBundle\Service\UserServiceInterface;

class HomeController extends Controller
{
    private $userService;
    private $searchService;

    public function __construct(UserServiceInterface $userService, SearchServiceInterface $searchService)
    {
        $this->userService = $userService;
        $this->searchService = $searchService;
    }

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

            $currentUser = $this->userService->findOneByUser($this->getUser());

            if ($currentUser->getSearch() === null){
                $currentUser->setSearch($search);
                $search->setUser($currentUser);
            }else{
                $search = $this->searchService->findOneByUser($currentUser);
                $form = $this->createForm(SearchType::class, $search);
                $form->handleRequest($request);
            }

            $this->searchService->save($search);

            return $this->redirectToRoute('place_all', ['searchId' => $search->getId()]);
        }

        return $this->render('front-end/home/index.html.twig', ['form' => $form->createView()]);
    }
}
