<?php

namespace App\Controller\CGU;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CGUController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route('/conditions-generales-utlisation', name: 'cgu_conditions')]
    public function index(): Response
    {
        return $this->render('cgu/index.html.twig');
    }
}
