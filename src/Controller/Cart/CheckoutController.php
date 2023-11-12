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

#[Route('/checkout')]
class CheckoutController extends AbstractController
{
    private CartServices $cartServices;
    private RequestStack $requestStack;

    /**
     * @param CartServices $cartServices
     * @param RequestStack $requestStack
     */
    public function __construct(CartServices $cartServices,
                                RequestStack $requestStack)
    {
        // Injection des services : cartServices et requestStack
        $this->cartServices = $cartServices;
        $this->requestStack = $requestStack;
    }

    /**
     * Page de checkout
     * @return Response
     */
    #[Route('/', name: 'checkout')]
    public function checkout(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');  // Vérifie si l'utilisateur est entièrement authentifié, sinon accès refusé

        /** @var User $user */
        $user = $this->getUser();  // Récupère l'utilisateur connecté actuel

        $cart = $this->cartServices->getFullCart();  // Récupère les données complètes du panier via le service cartServices

        if (isset($cart['product'])) {  // Vérifie s'il y a des produits dans le panier
            return $this->redirectToRoute("home");  // Redirige vers la page d'accueil si le panier contient des produits
        }

        if (!$user->getAddresses()->getValues()) {  // Vérifie si l'utilisateur a des adresses enregistrées dans son compte
            $this->addFlash('checkout_message', 'Veuillez ajouter une adresse à votre compte sans continuer.');  // Ajoute un message flash pour informer l'utilisateur
            return $this->redirectToRoute("address_new");  // Redirige vers la création d'une nouvelle adresse
        }

        if ($this->requestStack->getSession()->get('checkout_data')) {  // Vérifie si les données de validation sont déjà stockées en session
            return $this->redirectToRoute('checkout_confirm');  // Redirige vers la page de confirmation de la commande
        }

        $form = $this->createForm(CheckoutType::class, null, [  // Crée un formulaire de validation de commande avec le type CheckoutType
            'user' => $user  // Passe l'utilisateur en tant qu'option supplémentaire pour le formulaire
        ]);

        return $this->render('checkout/index.html.twig', [  // Rend la vue 'checkout/index.html.twig' avec les données nécessaires
            'cart' => $cart,  // Passage des données du panier à la vue
            'checkout' => $form->createView()  // Passage de la vue du formulaire de validation à la vue
        ]);
    }


    /**
     * Page de confirmation du panier
     * @param Request $request
     * @param OrderServices $orderServices
     * @return Response
     */
    #[Route('/confirm', name: 'checkout_confirm')]
    public function confirm(Request $request,
                            OrderServices $orderServices): Response
    {
        /** @var User $user */
        $user = $this->getUser();  // Récupère l'utilisateur connecté actuel

        $cart = $this->cartServices->getFullCart();  // Récupère les données complètes du panier via le service cartServices

        if (isset($cart['product'])) {  // Vérifie s'il y a des produits dans le panier
            return $this->redirectToRoute("home");  // Redirige vers la page d'accueil si le panier contient des produits
        }

        if (!$user->getAddresses()->getValues()) {  // Vérifie si l'utilisateur a des adresses enregistrées dans son compte
            $this->addFlash('checkout_message', 'Veuillez ajouter une adresse à votre compte sans continuer.');  // Ajoute un message flash pour informer l'utilisateur
            return $this->redirectToRoute("address_new");  // Redirige vers la création d'une nouvelle adresse
        }

        $form = $this->createForm(CheckoutType::class, null, [  // Crée un formulaire de validation de commande avec le type CheckoutType
            'user' => $user  // Passe l'utilisateur en tant qu'option supplémentaire pour le formulaire
        ]);

        $form->handleRequest($request);  // Gère la soumission du formulaire et la manipulation des données associées

        if ($this->requestStack->getSession()->get('checkout_data') || ($form->isSubmitted() && $form->isValid())) {

            if ($this->requestStack->getSession()->get('checkout_data')) {  // Vérifie si les données de validation sont déjà stockées en session
                $data = $this->requestStack->getSession()->get('checkout_data');
            } else {
                $data = $form->getData();  // Récupère les données du formulaire soumis
                $this->requestStack->getSession()->set('checkout_data', $data);  // Stocke les données de validation en session
            }

            $address = $data['address'];  // Récupère l'adresse sélectionnée dans les données de validation
            $carrier = $data['carrier'];  // Récupère le transporteur sélectionné dans les données de validation
            $information = $data['information'];  // Récupère les informations supplémentaires dans les données de validation

            $cart['checkout'] = $data;  // Ajoute les données de validation au tableau des données du panier
            $reference = $orderServices->saveCart($cart, $user);  // Sauvegarde le panier en tant que commande et récupère la référence de commande générée

            return $this->render('checkout/confirm.html.twig', [
                'cart' => $cart,
                'address' => $address,
                'carrier' => $carrier,
                'information' => $information,
                'reference' => $reference,
                'checkout' => $form->createView()
            ]);  // Rend la vue de confirmation de la commande avec les données nécessaires
        }

        return $this->redirectToRoute('checkout');  // Redirige vers la page de validation de la commande si les conditions précédentes ne sont pas remplies
    }


    /**
     * @return Response
     */
    #[Route('/edit', name: 'checkout_edit')]
    public function checkoutEdit(): Response {
        $this->requestStack->getSession()->set('checkout_data', []);
        return $this->redirectToRoute('checkout');
    }
}