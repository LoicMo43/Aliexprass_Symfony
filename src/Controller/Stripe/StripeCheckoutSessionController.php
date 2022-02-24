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
        Stripe::setApiKey($_ENV['STRIPE_SK']);

        $line_items= [];
        foreach($cart['products'] as $data_product) {
            $product = $data_product['product'];
            $line_items[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'unit_amount' => $product->getPrice(),
                    'product_data' => [
                        'name' => $product->getName(),
                        'images' => [$_ENV['YOUR_DOMAIN'].'/uploads/products/'. $product->getImage()],
                    ],
                ],
                'quantity' => $data_product['quantity'],
            ];
        }

        $checkout_session = Session::create([
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => $_ENV['YOUR_DOMAIN'] . '/stripe-payment-success',
            'cancel_url' => $_ENV['YOUR_DOMAIN'] . '/stripe-payment-cancel',
        ]);

        return $this->redirect($checkout_session->url);
    }
}
