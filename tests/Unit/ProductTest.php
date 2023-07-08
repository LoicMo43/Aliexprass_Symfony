<?php

namespace App\Tests\Unit;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductTest extends KernelTestCase
{
    public function getEntity(): Product
    {
        return (new Product())->setName("iPhone")
            ->setPrice(10)
            ->setDescription("un smarphone")
            ->setQuantity(10) ;
    }
    /**
     * @throws \Exception
     */
    public function testEntityisValid(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $product = new Product();
        $product->setName("iPhone")
            ->setPrice(10)
            ->setDescription("un smarphone")
            ->setQuantity(10);


        $errors = $container->get('validator')->validate($product);

        $this->assertCount(0, $errors);
    }

    public function testInvalidName() {
        self::bootKernel();
        $container = static::getContainer();

        $product = $this->getEntity();
        $product->setName("")
            ->setPrice(10)
            ->setDescription("un smarphone")
            ->setQuantity(10);


        $errors = $container->get('validator')->validate($product);
        $this->assertCount(2, $errors);
    }
}
