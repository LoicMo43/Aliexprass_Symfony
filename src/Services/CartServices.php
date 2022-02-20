<?php

namespace App\Services;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartServices {

    private $repoProduct;
    private $requestStack;
    private $tva = 0.2;

    /**
     * @param ProductRepository $repoProduct
     * @param RequestStack $requestStack
     */

    public function __construct(ProductRepository $repoProduct, RequestStack $requestStack) {
        $this->requestStack = $requestStack;
        $this->repoProduct = $repoProduct;
    }

    /**
     * Ajout d'un article au panier
     * @param $id
     */
    public function addToCart($id): void
    {
        $cart = $this->getCart();

        if (isset($cart[$id]))
        {
            // Produit deja présent dans le panier
            $cart[$id]++;
        }
        else {
            // Produit n'est pas encore dans le panier
            $cart[$id] = 1;
        }
        $this->updateCart($cart);
    }

    /**
     * Suppression d'un article au panier
     * @param $id
     */
    public function deleteFromCart($id): void
    {
        $cart = $this->getCart();

        if (isset($cart[$id])) {
            // Produit déja présent dans le panier
            if ($cart[$id] > 1) {
                // Produit qui existe plus d'une fois
                $cart[$id]--;
            }
            else {
                unset($cart[$id]);
            }
        }
        $this->updateCart($cart);
    }

    /**
     * Suppression de tous les produits du panier
     * @param $id
     */
    public function deleteAllToCart($id): void
    {
        $cart = $this->getCart();

        //Produit déjà dans le panier
        unset($cart[$id]);

        $this->updateCart($cart);
    }

    /**
     * Suppression du panier
     * @return void
     */
    public function deleteCart(): void
    {
        $this->updateCart([]);
    }

    /**
     * Mise a jour du panier
     * @param $cart
     */
    public function updateCart($cart): void
    {
        $this->requestStack->getSession()->set('cart', $cart);
        $this->requestStack->getSession()->set('cartData', $this->getFullCart());
    }

    /**
     * Récupération du panier
     * @return mixed
     */
    public function getCart(): mixed
    {
        return $this->requestStack->getSession()->get('cart', []);
    }

    /**
     * Récupération du panier et des ses articles
     * @return array
     */
    public function getFullCart(): array
    {
        $cart = $this->getCart();

        $fullCart = [];
        $quantity_cart = 0;
        $subTotal = 0;

        foreach($cart as $id => $quantity) {
            $product = $this->repoProduct->find($id);
            if ($product) {
                // Produit récupéré avec succès
                $fullCart['products'][] =
                [
                    "quantity" => $quantity,
                    "product" => $product
                ];

                $quantity_cart += $quantity;
                $subTotal += $quantity * $product->getPrice() / 100;

            } else {
                // id incorrecte
                $this->deleteFromCart($id);
            }
        }

        $fullCart['data'] = [
            "quantity_cart" => $quantity_cart,
            "subTotalHT" => $subTotal,
            "taxe" => round($subTotal * $this->tva, 2),
            "subTotalTTC" => round($subTotal + ($subTotal*$this->tva), 2)
        ];
        return $fullCart;
    }
}