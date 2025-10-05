<?php

namespace App\Controller\Home;

use App\Repository\HomeSliderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * Page d'accueil
     */
    #[Route('/', name: 'home')]
    public function index(HomeSliderRepository $repoHomeSlider): Response
    {
        $homeSlider = $repoHomeSlider->findBy(
            [
                'isDisplayed' => true
            ]
        );

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'homeSlider' => $homeSlider
        ]);
    }
}