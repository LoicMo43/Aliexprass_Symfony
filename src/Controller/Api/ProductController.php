<?php

namespace App\Controller\Api;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/api/products', name: 'api_products', methods: ['GET'])]
    public function __invoke(ProductRepository $productRepository): JsonResponse
    {
        $products = array_map(
            static function (Product $product): array {
                $categories = [];

                foreach ($product->getCategory() as $category) {
                    $categories[] = $category->getName();
                }

                return [
                    'id' => $product->getId(),
                    'name' => $product->getName(),
                    'slug' => $product->getSlug(),
                    'price' => $product->getPrice(),
                    'image' => sprintf('/assets/uploads/products/%s', $product->getImage()),
                    'categories' => $categories,
                    'isBestSeller' => (bool) $product->getIsBestSeller(),
                    'isNewArrival' => (bool) $product->getIsNewArrival(),
                    'isFeatured' => (bool) $product->getIsFeatured(),
                    'isSpecialOffer' => (bool) $product->getIsSpecialOffer(),
                    'tags' => $product->getTags(),
                ];
            },
            $productRepository->findAll()
        );

        return $this->json($products);
    }
}