<?php

namespace App\Controller\Shop;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController
{
    #[Route('/shop', name: 'shop')]
    public function shop(ProductRepository $repoProduct): Response
    {
        $products = $repoProduct->findAll();

        return $this->render('shop/shop.html.twig', [
            'controller_name' => 'ShopController',
            'products' => $products
        ]);
    }
}
