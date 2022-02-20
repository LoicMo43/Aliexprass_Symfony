<?php

namespace App\Controller\Stripe;

use App\Services\CartServices;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeCheckoutSessionController extends AbstractController
{
    /**
     * @throws ApiErrorException
     */
    #[Route('/create-checkout-session', name: 'create_checkout_session')]
    public function index(CartServices $cartServices): Response
    {
        $cart = $cartServices->getFullCart();
        Stripe::setApiKey('sk_test_VePHdqKTYQjKNInc7u56JBrQ');

        $checkout_session = Session::create([
            'line_items' => [],
            'mode' => 'payment',
            'success_url' => $_ENV['YOUR_DOMAIN'] . '/stripe-payment-success',
            'cancel_url' => $_ENV['YOUR_DOMAIN'] . '/stripe-payment-cancel',
        ]);

        $line_items = [];

        /*foreach ($cart['product'] as $data_prodcut) {
           [
               'quantity' => 5,
               'product' => Objet
           ]
        }*/

        return $this->json([]);
    }
}
