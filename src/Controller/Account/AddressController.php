<?php

namespace App\Controller\Account;

use App\Entity\Address;
use App\Entity\User;
use App\Form\AddressType;
use App\Repository\AddressRepository;
use App\Services\CartServices;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/address')]
class AddressController extends AbstractController
{
    private RequestStack $requestStack;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        // Injection du service RequestStack : permet de faire la recupération de la session
        $this->requestStack = $requestStack;
    }

    /**
     * @param AddressRepository $addressRepository
     * @return Response
     */
    #[Route('/', name: 'address_index', methods: ['GET'])]
    public function index(AddressRepository $addressRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('address/index.html.twig', [
            'addresses' => $addressRepository->findAll(),
        ]);
    }

    /**
     * Création d'un formulaire "Nouvelle adresse"
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param CartServices $cartServices
     * @return Response
     */
    #[Route('/new', name: 'address_new', methods: ['GET', 'POST'])]
    public function new(Request $request,
                        EntityManagerInterface $entityManager,
                        CartServices $cartServices): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            /** @var User $user */
            $address->setUser($user);
            $entityManager->persist($address);
            // Envoie en base de donnée
            $entityManager->flush();

            /**
             * Si le panier de l'utilisateur contient des articles,
             * le rediriger directement sur la page 'checkout'
             */

            if($cartServices->getFullCart()) {
                return $this->redirectToRoute('checkout');
            }

            // Sinon le rediriger sur la page 'account'
            return $this->redirectToRoute('account', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('address/new.html.twig', [
            'address' => $address,
            'form' => $form,
        ]);
    }

    /**
     * @param Address $address
     * @return Response
     */
    #[Route('/{id}', name: 'address_show', methods: ['GET'])]
    public function show(Address $address): Response
    {
        return $this->render('address/show.html.twig', [
            'address' => $address,
        ]);
    }

    /**
     * Modification de l'adresse
     * @param Request $request
     * @param Address $address
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/{id}/edit', name: 'address_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Address $address, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            // On récupére les données de checkout_data de la session
            if ($this->requestStack->getSession()->get('checkout_data')) {
                $data = $this->requestStack->getSession()->get('checkout_data');
                $data['address'] = $address;
                $this->requestStack->getSession()->set('checkout_data', $data);
                return $this->redirectToRoute('checkout_confirm');
            }

            return $this->redirectToRoute('account', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('address/edit.html.twig', [
            'address' => $address,
            'form' => $form,
        ]);
    }

    /**
     * Suppression d'une adresse
     * @param Request $request
     * @param Address $address
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/{id}', name: 'address_delete', methods: ['POST'])]
    public function delete(Request $request,
                           Address $address,
                           EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $address->getId(), $request->request->get('_token'))) {
            $entityManager->remove($address);
            $entityManager->flush();
        }

        return $this->redirectToRoute('account', [], Response::HTTP_SEE_OTHER);
    }
}
