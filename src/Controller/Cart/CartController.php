<?php

namespace App\Controller\Cart;

use App\Repository\ProductRepository;
use App\Services\CartServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cart')]
class CartController extends AbstractController
{
    private CartServices $cartServices;
    private ProductRepository $productRepository;

    /**
     * @param CartServices $cartServices
     * @param ProductRepository $productRepository
     */
    public function __construct(CartServices $cartServices, ProductRepository $productRepository)
    {
        $this->cartServices  = $cartServices;
        $this->productRepository = $productRepository;
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
     * @param int $id
     * @param string $routeName
     * @param string $slug
     * @return RedirectResponse
     */
    #[Route('/add/{slug}/{routeName}/{id}', name: 'cart_add')]
    public function addToCart(int $id, string $routeName, string $slug): RedirectResponse
    {
        $product = $this->productRepository->findBy(["id" => $id]);  // Recherche du produit dans le repository en fonction de son ID

        if ($product[0]->getQuantity() > 0) {  // Vérification si la quantité du produit est supérieure à 0
            $this->cartServices->addToCart($id);  // Ajout du produit au panier via le service cartServices
        }

        return $this->redirectToRoute($routeName,  // Redirection vers une route spécifiée
            [
                "id" => $id,  // Paramètre d'ID passé à la route
                'slug' => $slug  // Paramètre de slug passé à la route
            ]
        );
    }


    /**
     * Ajout d'une quantité d'article
     * @param int $id
     * @param int $qty
     * @return Response
     */
    #[Route('/addQuantity/{id}', name: 'cart_add_quantity')]
    public function addToCartQuantity(int $id, int $qty): Response
    {
        $product = $this->productRepository->findBy(["id" => $id]);

        if ($product[0]->getQuantity() > 0) {
            for ($i = 0; $i < $qty; $i++) {
                $this->cartServices->addToCart($id);
            }
        }
        return $this->redirectToRoute("cart");
    }

    /**
     * Acheter un article
     * @param int $id
     * @return Response
     */
    #[Route('/buy/{id}', name: 'cart_buy')]
    public function buyNow(int $id): Response
    {
        $product = $this->productRepository->findBy(["id" => $id]);

        if ($product[0]->getQuantity() > 0) {
            $this->cartServices->addToCart($id);
        }

        return $this->redirectToRoute("cart");
    }

    /**
     * Acheter une quantité d'article
     * @param int $id
     * @param int $qty
     * @return Response
     */
    #[Route('/buyQuantity/{id}', name: 'cart_buy_quantity')]
    public function buyNowQuantity(int $id, int $qty): Response
    {
        $product = $this->productRepository->findBy(["id" => $id]);

        if ($product[0]->getQuantity() > 0) {
            for ($i = 0; $i < $qty; $i++) {
                $this->cartServices->addToCart($id);
            }
        }

        return $this->redirectToRoute("cart");
    }

    /**
     * Suppression d'un article
     * @param int $id
     * @return Response
     */
    #[Route('/delete/{id}', name: 'cart_delete')]
    public function deleteFromCart(int $id): Response
    {
        $this->cartServices->deleteFromCart($id);
        return $this->redirectToRoute("cart");
    }

    /**
     * Suppression de tout le panier d'article
     * @param int $id
     * @return Response
     */
    #[Route('/deleteAll/{id}', name: 'cart_delete_all')]
    public function deleteAllToCart(int $id): Response
    {
        $this->cartServices->deleteAllToCart($id);
        return $this->redirectToRoute("cart");
    }
}
