<?php

namespace App\Controller\Product;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * Page du produit
     * @param Product|null $product
     * @return Response
     */
    #[Route('/product/{slug}', name: 'product_details')]
    public function show(?Product $product) : Response {
        if (!$product) {
            return $this->redirectToRoute('home');
        }

        return $this->render("home/single_product.html.twig", [
            'product' => $product
        ]);
    }
}
