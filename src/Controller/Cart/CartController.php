<?php

namespace App\Controller\Cart;

use App\Services\CartServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @param $routeName
     * @param $slug
     * @return RedirectResponse
     */
    #[Route('/cart/add/{slug}/{routeName}/{id}', name: 'cart_add')]
    public function addToCart($id, $routeName, $slug): RedirectResponse
    {
        $this->cartServices->addToCart($id);
        return $this->redirectToRoute($routeName,
            [
                "id" => $id,
                'slug' => $slug
            ]
        );
    }

    /**
     * Ajout d'une quantité d'article
     * @param $id
     * @param $qty
     * @return Response
     */
    #[Route('/cart/addQuantity/{id}', name: 'cart_add_quantity')]
    public function addToCartQuantity($id, $qty): Response
    {
        for ($i = 0; $i < $qty; $i++) {
            $this->cartServices->addToCart($id);
        }

        return $this->redirectToRoute("cart");
    }

    /**
     * Acheter un article
     * @param $id
     * @return Response
     */
    #[Route('/cart/buy/{id}', name: 'cart_buy')]
    public function buyNow($id): Response
    {
        $this->cartServices->addToCart($id);
        return $this->redirectToRoute("cart");
    }

    /**
     * Acheter une quantité d'article
     * @param $id
     * @param $qty
     * @return Response
     */
    #[Route('/cart/buyQuantity/{id}', name: 'cart_buy_quantity')]
    public function buyNowQuantity($id, $qty): Response
    {
        for ($i = 0; $i < $qty; $i++) {
            $this->cartServices->addToCart($id);
        }

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
