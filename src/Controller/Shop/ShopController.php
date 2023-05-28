<?php

namespace App\Controller\Shop;

use ACSEO\TypesenseBundle\Finder\TypesenseQuery;
use App\Entity\SearchProduct;
use App\Form\SearchProductType;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController
{
    private $productFinder;

    public function __construct($productFinder) {
        $this->productFinder = $productFinder;
    }

    /**
     * @param ProductRepository $repoProduct
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    #[Route('/shop', name: 'shop')]
    public function shop(ProductRepository $repoProduct, Request $request, PaginatorInterface $paginator): Response
    {
        $productsSearch = [];
        $products = $repoProduct->findAll();
        $search = new SearchProduct();

        $form = $this->createForm(SearchProductType::class, $search);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $products = $repoProduct->findWithSearch($search);
            if ($search->getName()) {
                $wordToSearch = $search->getName();
                $query = new TypesenseQuery($wordToSearch, 'name');
                $productsSearch = $this->productFinder->rawQuery($query)->getResults();
            }
        }

        $products = $paginator->paginate(
            $products, // On passe les données
            $request->query->getInt('page', 1), // Numéro de la page en cours
            12
        );

        return $this->render('shop/shop.html.twig', [
            'products' => $products,
            'searchProducts' => $productsSearch,
            'search' => $form->createView()
        ]);
    }
}
