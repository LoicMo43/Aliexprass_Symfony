<?php

namespace App\Controller\Product;

use App\Entity\Product;
use App\Entity\ReviewsProduct;
use App\Form\ReviewsProductType;
use App\Repository\ReviewsProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * Page du produit
     * @param Product|null $product
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param ReviewsProductRepository $reviewsProductRepository
     * @param string $slug
     * @return Response
     */
    #[Route('/product/{slug}', name: 'product_details')]
    public function show(?Product $product, Request $request, EntityManagerInterface $entityManager, ReviewsProductRepository $reviewsProductRepository, string $slug) : Response {
        if (!$product) {
            return $this->redirectToRoute('home');
        }

        $reviewsProducts = $reviewsProductRepository->findBy(['product' => $product]);

        unset($review, $form);

        $review = new ReviewsProduct();
        $form = $this->createForm(ReviewsProductType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $review->setComment($form->get('comment')->getData())
                ->setNote($form->get('note')->getData())
                ->setProduct($product)
                ->setUser($this->getUser());

            $entityManager->persist($review);
            $entityManager->flush();

            return $this->redirectToRoute('product_details', ['slug' => $slug]);
        }

        // Vérifier si l'utilisateur connecté est l'auteur de chaque avis et autoriser la suppression
        $canDeleteReview = false;

        foreach ($reviewsProducts as $reviewsProduct) {
            if ($this->getUser() && $reviewsProduct->getUser() === $this->getUser()) {
                $canDeleteReview[$reviewsProduct->getId()] = true;
            } else {
                $canDeleteReview[$reviewsProduct->getId()] = false;
            }
        }

        return $this->render("home/single_product.html.twig", [
            'product' => $product,
            'form' => $form,
            'reviews' => $reviewsProducts,
            'canDeleteReview' => $canDeleteReview
        ]);
    }

    #[Route('/product/delete/{reviewId}', name: 'delete_review')]
    public function deleteReview(int $reviewId, EntityManagerInterface $entityManager, ReviewsProductRepository $reviewsProductRepository): Response {
        $review = $reviewsProductRepository->find($reviewId);

        if (!$review) {
            throw $this->createNotFoundException('Avis introuvable.');
        }

        // Vérifier si l'utilisateur connecté est l'auteur de l'avis
        if ($this->getUser() !== $review->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à supprimer cet avis.');
        }

        $entityManager->remove($review);
        $entityManager->flush();

        // Rediriger vers la page des détails du produit après la suppression
        return $this->redirectToRoute('product_details', ['slug' => $review->getProduct()->getSlug()]);
    }
}
