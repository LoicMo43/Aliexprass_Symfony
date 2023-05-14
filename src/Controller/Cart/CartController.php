<?php

namespace App\Controller\Cart;

use App\Services\CartServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private CartServices $cartServices;

    /**
     * @param CartServices $cartServices
     */
    public function __construct(CartServices $cartServices)
    {
        $this->cartServices  = $cartServices;
    }

    /**
     * Page du panier
     * @return Response
     */
    #[Route('/cart', name: 'cart')]
    public function index(): Response
    {
        // On récupère tout le contenu du panier de l'utilisateur
        $cart = $this->cartServices->getFullCart();

        // Si le panier est vide le rediriger vers l'accueil
        if (!isset($cart['products']))
        {
            return $this->redirectToRoute('home');
        }

        return $this->render("cart/index.html.twig", [
            'cart'=> $cart
        ]);
    }

    /**
     * Ajout d'un article
     * @param $id
     * @return Response
     */
    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function addToCart($id): Response
    {
        $this->cartServices->addToCart($id);
        return $this->redirectToRoute("cart");
    }

    /**
     * Suppression d'un article
     * @param $id
     * @return Response
     */
    #[Route('/cart/delete/{id}', name: 'cart_delete')]
    public function deleteFromCart($id): Response
    {
        $this->cartServices->deleteFromCart($id);
        return $this->redirectToRoute("cart");
    }

    /**
     * Suppression de tout le panier d'article
     * @param $id
     * @return Response
     */
    #[Route('/cart/deleteAll/{id}', name: 'cart_delete_all')]
    public function deleteAllToCart($id): Response
    {
        $this->cartServices->deleteAllToCart($id);
        return $this->redirectToRoute("cart");
    }
}
