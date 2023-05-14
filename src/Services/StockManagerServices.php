<?php

namespace App\Services;

use App\Entity\Order;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class StockManagerServices
{
    private EntityManagerInterface $entityManager;
    private ProductRepository $productRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ProductRepository $productRepository
     */
    public function __construct(EntityManagerInterface $entityManager, ProductRepository $productRepository)
    {
        $this->entityManager = $entityManager;
        $this->productRepository = $productRepository;
    }

    /**
     * @param Order $order
     * @return void
     */
    public function deStock(Order $order): void
    {
        $orderDetails = $order->getOrderDetails()->getValues();
        foreach ($orderDetails as $details) {
            $product = $this->productRepository->findByName($details->getProductName())[0];
            $newQuantity = $product->getQuantity() - $details->getQuantity();
            $product->setQuantity($newQuantity);
        }
    }

}