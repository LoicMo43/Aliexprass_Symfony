<?php

namespace App\Controller\Wishlist;

use App\Services\WishlistServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/wishlist')]
class WishlistController extends AbstractController
{
    private WishlistServices $wishlistServices;

    /**
     * @param WishlistServices $wishlistServices
     */
    public function __construct(WishlistServices $wishlistServices)
    {
        $this->wishlistServices  = $wishlistServices;
    }
    
    #[Route('/', name: 'wishlist')]
    public function index(): Response
    {
        $wishlist = $this->wishlistServices->getFullWishlist();

        return $this->render('wishlist/index.html.twig', [
            'wishlist' => $wishlist,
        ]);
    }

    #[Route('/add/{slug}/{routeName}/{id}', name: 'wishlist_add')]
    public function addToWishlist($id, $routeName, $slug): RedirectResponse
    {
        $this->wishlistServices->addToWishlist($id);
        return $this->redirectToRoute($routeName,
            [
                "id" => $id,
                'slug' => $slug
            ]
        );
    }
}
