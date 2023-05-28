<?php

namespace App\Services;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class WishlistServices
{
    public function __construct(ProductRepository $repoProduct,
                                RequestStack $requestStack) {
        $this->requestStack = $requestStack;
        $this->repoProduct = $repoProduct;
    }

    public function createWishlist() {

    }

    /**
     * Ajout d'un produit a la wish
     * @param int $id
     */
    public function addToWishlist(int $id): void
    {
        $wishlist = $this->getWishlist();

        if (isset($wishlist[$id]))
        {
            // Produit deja présent dans le panier
            $wishlist[$id]++;
        }
        else {
            // Produit n'est pas encore dans le panier
            $wishlist[$id] = 1;
        }
        $this->updateWishlist($wishlist);
    }

    /**
     * Suppression d'un article de la wishlist
     * @param int $id
     */
    public function deleteFromCart(int $id): void
    {
        $wishlist = $this->getWishlist();

        if (isset($wishlist[$id])) {
            // Produit déja présent dans le panier
            if ($wishlist[$id] > 1) {
                // Produit qui existe plus d'une fois
                $wishlist[$id]--;
            }
            else {
                unset($wishlist[$id]);
            }
        }
        $this->updateWishlist($wishlist);
    }

    /**
     * Suppression de tous les produits d'une wishlist
     * @param int $id
     */
    public function deleteAllToWishlist(int $id): void
    {
        $wishlist = $this->getWishlist();

        //Produit déjà dans le panier
        unset($wishlist[$id]);

        $this->updateWishlist($wishlist);
    }

    /**
     * Suppression du panier
     * @return void
     */
    public function deleteWishlist(): void
    {
        $this->updateWishlist([]);
    }

    /**
     * Mise a jour de la wishlist
     * @param $cart
     */
    public function updateWishlist($cart): void
    {
        $this->requestStack->getSession()->set('wishlist', $cart);
        $this->requestStack->getSession()->set('wishlistData', $this->getFullWishlist());
    }

    /**
     * Récupération de la wishlist
     * @return mixed
     */
    public function getWishlist(): mixed
    {
        return $this->requestStack->getSession()->get('wishlist', []);
    }

    /**
     * Récupération du panier et des ses articles
     * @return array
     */
    public function getFullWishlist(): array
    {
        $wishlist = $this->getWishlist();

        $fullWishlist = [];
        $quantity_cart = 0;
        $subTotal = 0;

        foreach($wishlist as $id => $quantity) {
            $product = $this->repoProduct->find($id);
            if ($product) {
                // Produit récupéré avec succès
                if ($quantity > $product->getQuantity()) {
                    $quantity = $product->getQuantity();
                    $wishlist[$id] = $quantity;
                    $this->updateWishlist($wishlist);
                }

                $fullWishlist['products'][] =
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

        $fullWishlist['data'] = [
            "quantity_cart" => $quantity_cart,
            "subTotalHT" => $subTotal,
        ];
        return $fullWishlist;
    }
}