<?php

namespace App\Controller\Stripe;

use App\Entity\Cart;
use App\Services\OrderServices;
use Doctrine\ORM\EntityManagerInterface;
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
    public function index(?Cart $cart, OrderServices $orderServices, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();  // Récupère l'utilisateur connecté actuel
        if (!$cart) {  // Vérifie si le panier est null
            return $this->redirectToRoute('home');  // Redirige vers la page d'accueil si le panier est null
        }

        $order = $orderServices->createOrder($cart);  // Crée une commande à partir du panier via le service OrderServices
        Stripe::setApiKey($_ENV['STRIPE_SK']);  // Configure la clé API Stripe

        $checkout_session = Session::create([
            'customer_email' => $user->getEmail(),  // Définit l'adresse e-mail du client pour la session de paiement
            'mode' => 'payment',  // Définit le mode de la session de paiement
            'line_items' => $orderServices->getLineItems($cart),  // Récupère les éléments de ligne (produits) de la commande via le service OrderServices
            'success_url' => $_ENV['YOUR_DOMAIN'] . '/stripe-payment-success/{CHECKOUT_SESSION_ID}',  // Définit l'URL de succès de paiement Stripe
            'cancel_url' => $_ENV['YOUR_DOMAIN'] . '/stripe-payment-cancel/{CHECKOUT_SESSION_ID}',  // Définit l'URL d'annulation de paiement Stripe
        ]);

        $order->setStripeCheckoutSessionId($checkout_session->id);  // Définit l'ID de session de paiement Stripe dans la commande
        $manager->flush();  // Enregistre les modifications de la commande en base de données

        return $this->redirect($checkout_session->url);  // Redirige vers l'URL de la session de paiement Stripe
    }

}
