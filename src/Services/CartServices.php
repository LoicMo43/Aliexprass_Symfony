<?php

namespace App\Services;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartServices {

    private ProductRepository $repoProduct;
    private RequestStack $requestStack;
    private float $tva = 0.2;

    /**
     * @param ProductRepository $repoProduct
     * @param RequestStack $requestStack
     */

    public function __construct(ProductRepository $repoProduct,
                                RequestStack $requestStack) {
        $this->requestStack = $requestStack;
        $this->repoProduct = $repoProduct;
    }

    /**
     * Ajout d'un article au panier
     * @param int $id
     */
    public function addToCart(int $id): void
    {
        $cart = $this->getCart();  // Récupère le panier existant

        if (isset($cart[$id])) {  // Vérifie si le produit est déjà présent dans le panier
            $cart[$id]++;  // Incrémente la quantité du produit dans le panier
        } else {
            $cart[$id] = 1;  // Ajoute le produit au panier avec une quantité initiale de 1
        }

        $this->updateCart($cart);  // Met à jour le panier avec les modifications effectuées
    }


    /**
     * Suppression d'un article au panier
     * @param int $id
     */
    public function deleteFromCart(int $id): void {
        $cart = $this->getCart();  // Récupère le panier existant

        if (isset($cart[$id])) {  // Vérifie si le produit est présent dans le panier
            if ($cart[$id] > 1) {  // Vérifie si la quantité du produit est supérieure à 1
                $cart[$id]--;  // Décrémente la quantité du produit dans le panier
            }
            else {
                unset($cart[$id]);  // Supprime le produit du panier s'il n'en reste qu'un
            }
        }

        $this->updateCart($cart);  // Met à jour le panier avec les modifications effectuées
    }


    /**
     * Suppression de tous les produits du panier
     * @param int $id
     */
    public function deleteAllToCart(int $id): void
    {
        $cart = $this->getCart();  // Récupère le panier existant

        unset($cart[$id]);  // Supprime complètement le produit du panier en utilisant son ID

        $this->updateCart($cart);  // Met à jour le panier avec les modifications effectuées
    }


    /**
     * Suppression du panier
     * @return void
     */
    public function deleteCart(): void
    {
        $this->updateCart([]);  // Met à jour le panier en le remplaçant par un tableau vide
    }

    /**
     * Mise a jour du panier
     * @param $cart
     */
    public function updateCart($cart): void
    {
        $this->requestStack->getSession()->set('cart', $cart);  // Met à jour la session en enregistrant le panier actuel
        $this->requestStack->getSession()->set('cartData', $this->getFullCart());  // Met à jour la session en enregistrant les données complètes du panier
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
        $cart = $this->getCart();  // Récupère le panier existant

        $fullCart = [];  // Tableau qui contiendra les données complètes du panier
        $quantity_cart = 0;  // Variable pour stocker la quantité totale des produits dans le panier
        $subTotal = 0;  // Variable pour stocker le sous-total du panier

        foreach ($cart as $id => $quantity) {  // Boucle à travers chaque produit du panier avec sa quantité

            $product = $this->repoProduct->find($id);  // Recherche du produit à partir de son ID

            if ($product) {  // Vérifie si le produit existe

                if ($quantity > $product->getQuantity()) {  // Vérifie si la quantité du produit dépasse la quantité disponible en stock
                    $quantity = $product->getQuantity();  // Prend la quantité du produit au stock disponible
                    $cart[$id] = $quantity;  // Met à jour la quantité dans le panier
                    $this->updateCart($cart);  // Met à jour le panier avec les modifications effectuées
                }

                $fullCart['products'][] = [
                    "quantity" => $quantity,
                    "product" => $product
                ];  // Ajoute le produit avec sa quantité au tableau 'products' dans le tableau 'fullCart'

                $quantity_cart += $quantity;  // Ajoute la quantité du produit à la quantité totale des produits dans le panier
                $subTotal += $quantity * $product->getPrice() / 100;  // Calcule le sous-total en multipliant la quantité par le prix du produit

            } else {
                $this->deleteFromCart($id);  // Supprime le produit du panier s'il n'existe plus
            }
        }

        $fullCart['data'] = [
            "quantity_cart" => $quantity_cart,
            "subTotalHT" => $subTotal,
            "taxe" => round($subTotal * $this->tva, 2),  // Calcule le montant de la taxe en fonction du sous-total et du taux de TVA
            "subTotalTTC" => round($subTotal + ($subTotal * $this->tva), 2)  // Calcule le sous-total TTC en ajoutant le montant de la taxe
        ];

        return $fullCart;  // Retourne le tableau contenant les données complètes du panier
    }

}