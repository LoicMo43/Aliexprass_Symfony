<?php

namespace App\Controller\Stripe;

use App\Entity\Order;
use App\Services\CartServices;
use App\Services\StockManagerServices;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeSuccessPaymentController extends AbstractController
{
    /**
     * @param Order|null $order
     * @param CartServices $cartServices
     * @param EntityManagerInterface $manager
     * @param StockManagerServices $stockManager
     * @return Response
     */
    #[Route('/stripe-payment-success/{StripeCheckoutSessionId}', name: 'stripe_payment_success')]
    public function index(
        ?Order $order,
        CartServices $cartServices,
        EntityManagerInterface $manager,
        StockManagerServices $stockManager): Response
    {
        if(!$order || $order->getUser() !== $this->getUser()) {
            return $this->redirectToRoute("home");
        }

        if(!$order->getIsPaid()) {
            // Commande payée
            $order->setIsPaid(true);
            // Destockage
            $stockManager->deStock($order);
            // Enrengistrement en base de donnée
            $manager->flush();
            // Un mail au client
            $cartServices->deleteCart();
        }

        return $this->render('stripe/stripe_success_payment/index.html.twig', [
            'controller_name' => 'StripeSuccessPaymentController',
            'order' => $order
        ]);
    }
}
