<?php

namespace TravelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use TravelBundle\Entity\Place;

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $country = str_replace('/?myCountry=', '', $request->getRequestUri());

        $places = $this->getDoctrine()->getRepository(Place::class)->findBy(['country' => $country]);

        if (empty($places)){

            $places = $this->getDoctrine()->getRepository(Place::class)->findAll();
        }

        return $this->render('home/index.html.twig', ['places' => $places]);
    }
}
