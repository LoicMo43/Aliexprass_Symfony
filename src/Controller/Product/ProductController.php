<?php

declare(strict_types=1);

namespace App\Controller\Product;

use App\Entity\Product;
use App\Entity\ReviewsProduct;
use App\Form\ReviewsProductType;
use App\Repository\ProductRepository;
use App\Repository\ReviewsProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product/{slug}', name: 'product_details')]
    public function show(
        ?Product $product,
        Request $request,
        EntityManagerInterface $entityManager,
        ReviewsProductRepository $reviewsProductRepository,
        string $slug
    ): Response {
        if (!$product) {
            return $this->redirectToRoute('home');
        }

        $reviewsProducts = $reviewsProductRepository->findBy(['product' => $product]);

        $review = new ReviewsProduct();
        $form = $this->createForm(ReviewsProductType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $review
                ->setComment($form->get('comment')->getData())
                ->setNote($form->get('note')->getData())
                ->setProduct($product)
                ->setUser($this->getUser());

            $entityManager->persist($review);
            $entityManager->flush();

            return $this->redirectToRoute('product_details', ['slug' => $slug]);
        }

        $canDeleteReview = [];
        foreach ($reviewsProducts as $reviewsProduct) {
            $canDeleteReview[$reviewsProduct->getId()] = $this->getUser() && $reviewsProduct->getUser() === $this->getUser();
        }

        return $this->render('home/single_product.html.twig', [
            'product' => $product,
            'form' => $form,
            'reviews' => $reviewsProducts,
            'canDeleteReview' => $canDeleteReview,
        ]);
    }

    #[Route('/product/delete/{reviewId}', name: 'delete_review')]
    public function deleteReview(int $reviewId, EntityManagerInterface $entityManager, ReviewsProductRepository $reviewsProductRepository): Response
    {
        $review = $reviewsProductRepository->find($reviewId);

        if (!$review) {
            throw $this->createNotFoundException('Avis introuvable.');
        }

        if ($this->getUser() !== $review->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à supprimer cet avis.');
        }

        $entityManager->remove($review);
        $entityManager->flush();

        return $this->redirectToRoute('product_details', ['slug' => $review->getProduct()->getSlug()]);
    }

    #[Route('/search/suggestions', name: 'search_suggestions', methods: ['GET'])]
    public function suggestions(Request $request, ProductRepository $productRepository): JsonResponse
    {
        $query = trim((string) $request->query->get('q', ''));

        if ($query === '') {
            return $this->json([]);
        }

        $results = $productRepository->searchByTerm($query, 8);

        $payload = array_map(static fn (Product $product): array => [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'slug' => $product->getSlug(),
            'image' => $product->getImage(),
            'price' => $product->getPrice(),
            'url' => $request->getSchemeAndHttpHost() . '/product/' . $product->getSlug(),
        ], $results);

        return $this->json($payload);
    }

    #[Route('/search', name: 'product_search', methods: ['GET'])]
    public function search(Request $request, ProductRepository $productRepository): Response
    {
        $query = trim((string) $request->query->get('q', ''));
        $products = $query === '' ? [] : $productRepository->searchByTerm($query, 50);

        return $this->render('shop/search_results.html.twig', [
            'query' => $query,
            'products' => $products,
        ]);
    }
}