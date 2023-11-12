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
    public function show(?Product $product,  // Définition de la méthode "show" prenant en paramètre un objet Product optionnel
                         Request $request,  // Objet Request contenant les données de la requête HTTP
                         EntityManagerInterface $entityManager,  // Objet pour gérer les opérations de persistance avec la base de données
                         ReviewsProductRepository $reviewsProductRepository,  // Repository pour accéder aux avis des produits
                         string $slug) : Response {  // Paramètre de chaîne de caractères représentant le slug du produit et renvoyant une réponse HTTP

        if (!$product) {  // Vérification si le produit existe
            return $this->redirectToRoute('home');  // Redirection vers la page d'accueil si le produit n'existe pas
        }

        $reviewsProducts = $reviewsProductRepository->findBy(['product' => $product]);  // Récupération des avis du produit depuis le repository

        unset($review, $form);  // Suppression des variables $review et $form s'il existent déjà

        $review = new ReviewsProduct();  // Création d'une nouvelle instance de la classe ReviewsProduct
        $form = $this->createForm(ReviewsProductType::class, $review);  // Création d'un formulaire basé sur le type ReviewsProductType et l'instance de review
        $form->handleRequest($request);  // Traitement des données du formulaire

        if ($form->isSubmitted() && $form->isValid()) {  // Vérification si le formulaire a été soumis et est valide
            $review->setComment($form->get('comment')->getData())  // Récupération et configuration du commentaire à partir des données du formulaire
            ->setNote($form->get('note')->getData())  // Récupération et configuration de la note à partir des données du formulaire
            ->setProduct($product)  // Définition du produit associé à l'avis
            ->setUser($this->getUser());  // Définition de l'utilisateur associé à l'avis

            $entityManager->persist($review);  // Persistance de l'avis en attente d'être enregistré dans la base de données
            $entityManager->flush();  // Enregistrement effectif de l'avis dans la base de données

            return $this->redirectToRoute('product_details', ['slug' => $slug]);  // Redirection vers la page de détails du produit
        }

        $canDeleteReview = false;  // Initialisation de la variable $canDeleteReview à false

        foreach ($reviewsProducts as $reviewsProduct) {  // Boucle sur tous les avis du produit
            if ($this->getUser() && $reviewsProduct->getUser() === $this->getUser()) {  // Vérification si l'utilisateur est connecté et est l'auteur de l'avis
                $canDeleteReview[$reviewsProduct->getId()] = true;  // Autorisation de suppression de l'avis associé
            } else {
                $canDeleteReview[$reviewsProduct->getId()] = false;  // Interdiction de suppression de l'avis associé
            }
        }

        return $this->render("home/single_product.html.twig", [  // Renvoi de la vue "single_product.html.twig" avec les variables à transmettre
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
