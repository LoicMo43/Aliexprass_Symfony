<?php

namespace App\Controller\Shop;

use App\Form\SearchProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController
{
    /**
     * @param ProductRepository $repoProduct
     * @return Response
     */
    #[Route('/shop', name: 'shop')]
    public function shop(ProductRepository $repoProduct): Response
    {
        $products = $repoProduct->findAll();

        $form = $this->createForm(SearchProductType::class, null);

        return $this->render('shop/shop.html.twig', [
            'products' => $products,
            'search' => $form->createView()
        ]);
    }
}
