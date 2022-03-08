<?php

namespace App\Controller\Shop;

use App\Form\SearchProductType;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController
{
    /**
     * @param ProductRepository $repoProduct
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    #[Route('/shop', name: 'shop')]
    public function shop(ProductRepository $repoProduct, Request $request, PaginatorInterface $paginator): Response
    {
        $products = $repoProduct->findAll();

        $products = $paginator->paginate(
            $products, // On passe les données
            $request->query->getInt('page', 1), // Numéro de la page en cours
            12
        );

        $form = $this->createForm(SearchProductType::class, null);

        return $this->render('shop/shop.html.twig', [
            'products' => $products,
            'search' => $form->createView()
        ]);
    }
}
