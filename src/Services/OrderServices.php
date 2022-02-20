<?php

namespace App\Services;

use App\Entity\Cart;
use App\Entity\CartDetails;
use App\Entity\Order;
use App\Entity\OrderDetails;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\Pure;

class OrderServices {

    private $manager;

    /**
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager) {
        $this->manager = $manager;
    }

    /**
     * Création d'une commande
     * @param $cart
     * @return Order
     */
    public function createOrder($cart): Order
    {
        $order = new Order();
        $order->setReference($cart->getReference())
              ->setCarrierName($cart->getName())
              ->setCarrierPrice($cart->getPrice())
              ->setFullName($cart->getFullName())
              ->setDeliveryAddress($cart->getDeliveryAddress())
              ->setMoreInformations($cart->getInformations())
              ->setQuantity($cart->getQuantity())
              ->setSubTotalHT($cart->getSubTotalHT())
              ->setTaxe($cart->getTaxe())
              ->setSubTotalTTC($cart->getSubTotalTTC())
              ->setUser($cart->getUser())
              ->setCreatedAt($cart->getCreatedAt());

        $this->manager->persist($order);

        $products = $cart->getCartDetails()->getValues();

        foreach($products as $cart_product) {
            $orderDetails = new OrderDetails();
            $orderDetails->setOrders($order)
                         ->setProductName($cart_product->getProductName())
                         ->setProductPrice($cart_product->getProductPrice())
                         ->setQuantity($cart_product->getQuantity())
                         ->setSubTotalHT($cart_product->getSubTotalHT())
                         ->setSubTotalTTC($cart_product->getSubTotalTTC())
                         ->setTaxe($cart_product->getTaxe());
            $this->manager->persist($orderDetails);
        }

        $this->manager->flush();

        return $order;
    }

    /**
     * Sauvegarde du panier
     * @param $data
     * @param $user
     * @return string
     */
    #[Pure] public function saveCart($data, $user): string
    {
        $cart = new Cart();
        $reference = $this->generateUuid();
        $address = $data['checkout']['address'];
        $carrier = $data['checkout']['carrier'];
        $information = $data['checkout']['information'];

        $cart->setReference($reference)
             ->setCarrierName($carrier->getName())
             ->setCarrierPrice($carrier->getPrice())
             ->setFullName($address->getFullName())
             ->setDeliveryAddress($address)
             ->setMoreInformations($information)
             ->setQuantity($data['data']['quantity_cart'])
             ->setSubTotalHT($data['data']['subTotalHT'])
             ->setTaxe($data['data']['taxe'])
             ->setSubTotalTTC(round($data['data']['subTotalTTC']+$carrier->getPrice()/100,2))
             ->setUser($user)
             ->setCreatedAt(new DateTime());

        $this->manager->persist($cart);

        foreach($data['products'] as $products) {

            $cart_details_array = [];
            $cartDetails = new CartDetails();

            $subtotal = ($products['quantity']) * ($products['product']->getPrice()/100);

            $cartDetails->setCarts($cart)
                        ->setProductName($products['product']->getName())
                        ->setProductPrice($products['product']->getPrice()/100)
                        ->setQuantity($products['quantity'])
                        ->setSubTotalHT($subtotal)
                        ->setSubTotalTTC($subtotal*1.2);
            $this->manager->persist($cartDetails);
            $cart_details_array[] = $cartDetails;
        }

        $this->manager->flush();
        return $reference;
    }

    /**
     * Génération d'une ID de commande unique par commande
     * @return string
     */
    public function generateUuid(): string
    {
        // Initialise le générateur de nombres aléatoires Mersenne Twister
        mt_srand((double)microtime()*100000);

        // strtoupper : Renvoie une chaîne en majuscules
        // uniqid : Génère un identifiant unique
        $charid = strtoupper(md5(uniqid(mt_rand(), true)));

        // Générer une chaîne d'un octet à partir d'un nombre
        $hyphen = chr(45);

        // substr : Retourne un segment de chaîne
        return ""
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid, 12,4).$hyphen
            .substr($charid, 16,4).$hyphen
            .substr($charid, 20,12);
    }

}