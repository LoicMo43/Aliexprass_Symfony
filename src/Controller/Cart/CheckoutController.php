<?php

namespace App\Controller\Cart;

use App\Entity\User;
use App\Form\CheckoutType;
use App\Services\CartServices;
use App\Services\OrderServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends AbstractController
{
    private $cartServices;
    private $requestStack;

    /**
     * @param CartServices $cartServices
     * @param RequestStack $requestStack
     */
    public function __construct(CartServices $cartServices, RequestStack $requestStack)
    {
        $this->cartServices = $cartServices;
        $this->requestStack = $requestStack;
    }

    /**
     * @return Response
     */
    #[Route('/checkout', name: 'checkout')]
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $cart = $this->cartServices->getFullCart();

        if (isset($cart['product'])) {
            return $this->redirectToRoute("home");
        }

        if (!$user->getAddresses()->getValues()) {
            $this->addFlash('checkout_message', 'Please add an address to your account awithout continuing.');
            return $this->redirectToRoute("address_new");
        }

        if ($this->requestStack->getSession()->get('checkout_data')) {
            return $this->redirectToRoute('checkout_confirm');
        }

        $form = $this->createForm(CheckoutType::class, null, [
            'user' => $user
        ]);

        return $this->render('checkout/index.html.twig', [
            'cart' => $cart,
            'checkout' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param OrderServices $orderServices
     * @return Response
     */
    #[Route('/checkout/confirm', name: 'checkout_confirm')]
    public function confirm(Request $request, OrderServices $orderServices): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $cart = $this->cartServices->getFullCart();

        if (isset($cart['product'])) {
            return $this->redirectToRoute("home");
        }

        if (!$user->getAddresses()->getValues()) {
            $this->addFlash('checkout_message', 'Please add an address to your account awithout continuing.');
            return $this->redirectToRoute("address_new");
        }

        $form = $this->createForm(CheckoutType::class, null, [
            'user' => $user
        ]);

        $form->handleRequest($request); // On analyse la requete HTTP

        if ($this->requestStack->getSession()->get('checkout_data') || ($form->isSubmitted() && $form->isValid())) {

            if ($this->requestStack->getSession()->get('checkout_data')) {
                $data = $this->requestStack->getSession()->get('checkout_data');
            }
            else {
                $data = $form->getData();
                $this->requestStack->getSession()->set('checkout_data', $data);
            }

            $address = $data['address'];
            $carrier = $data['carrier'];
            $information = $data['information'];

            // Sauvegarde du panier
            $cart['checkout'] = $data;
            $reference =  $orderServices->saveCart($cart, $user);

            return $this->render('checkout/confirm.html.twig', [
                'cart' => $cart,
                'address' => $address,
                'carrier' => $carrier,
                'information' => $information,
                'checkout' => $form->createView()
            ]);
        }
        return $this->redirectToRoute('checkout');
    }

    /**
     * @return Response
     */
    #[Route('/checkout/edit', name: 'checkout_edit')]
    public function checkoutEdit(): Response {
        $this->requestStack->getSession()->set('checkout_data', []);
        return $this->redirectToRoute('checkout');
    }
}