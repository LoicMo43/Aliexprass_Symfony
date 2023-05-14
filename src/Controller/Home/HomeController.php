<?php

namespace App\Controller\Home;

use App\Repository\HomeSliderRepository;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * Page d'accueil
     * @param ProductRepository $repoProduct
     * @param HomeSliderRepository $repoHomeSlider
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    #[Route('/', name: 'home')]
    public function index(ProductRepository $repoProduct,
                          HomeSliderRepository $repoHomeSlider,
                          Request $request,
                          PaginatorInterface $paginator): Response
    {
        $homeSlider = $repoHomeSlider->findBy(
            [
                'isDisplayed' => true
            ]
        );

        $products = $repoProduct->findAll();
        $productBestSeller = $repoProduct->findByIsBestSeller(1);
        $productSpecialOffer = $repoProduct->findByIsSpecialOffer(1);
        $productNewArrival = $repoProduct->findByIsNewArrival(1);
        $productFeatured = $repoProduct->findByIsFeatured(1);

        $products = $paginator->paginate(
            $products, // On passe les données
            $request->query->getInt('page', 1), // Numéro de la page en cours
            4
        );

        $productBestSeller = $paginator->paginate(
            $productBestSeller, // On passe les données
            $request->query->getInt('page', 1), // Numéro de la page en cours
            4
        );

        $productSpecialOffer = $paginator->paginate(
            $productSpecialOffer, // On passe les données
            $request->query->getInt('page', 1), // Numéro de la page en cours
            4
        );

        $productNewArrival = $paginator->paginate(
            $productNewArrival, // On passe les données
            $request->query->getInt('page', 1), // Numéro de la page en cours
            4
        );

        $productFeatured = $paginator->paginate(
            $productFeatured, // On passe les données
            $request->query->getInt('page', 1), // Numéro de la page en cours
            4
        );

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'products' => $products,
            'productBestSeller' => $productBestSeller,
            'productSpecialOffer' => $productSpecialOffer,
            'productNewArrival' => $productNewArrival,
            'productFeatured' => $productFeatured,
            'homeSlider' => $homeSlider
        ]);
    }
}
