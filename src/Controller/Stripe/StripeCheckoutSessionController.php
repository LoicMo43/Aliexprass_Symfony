<?php

namespace App\Controller\Stripe;

use App\Entity\Cart;
use App\Services\OrderServices;
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
    #[Route('/create-checkout-session/{reference}', name: 'create_checkout_session')]
    public function index(?Cart $cart, OrderServices $orderServices): Response
    {
        $user = $this->getUser();
        if (!$cart) {return $this->redirectToRoute('home');}

        $orderServices->createOrder($cart);
        Stripe::setApiKey($_ENV['STRIPE_SK']);

        $checkout_session = Session::create([
            'customer_email' => $user->getEmail(),
            'mode' => 'payment',
            'line_items' => $orderServices->getLineItems($cart),
            'success_url' => $_ENV['YOUR_DOMAIN'] . '/stripe-payment-success',
            'cancel_url' => $_ENV['YOUR_DOMAIN'] . '/stripe-payment-cancel',
        ]);

        return $this->redirect($checkout_session->url);
    }
}
