<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method findByIsBestSeller(int $int)
 * @method findByIsSpecialOffer(int $int)
 * @method findByIsNewArrival(int $int)
 * @method findByIsFeatured(int $int)
 * @method findOneByName($getProductName)
 * @method findByName($details)
 */
class ProductRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
    * @return Product[] Returns an array of Product objects
    */
    public function findWithSearch($search): array
    {
        $query = $this->createQueryBuilder('p');

        if($search->getMinPrice()) {
            $query = $query->andWhere('p.price > '.$search->getMinPrice()*100);
        }

        if($search->getMaxPrice()) {
            $query = $query->andWhere('p.price < '.$search->getMaxPrice()*100);
        }

        // Tags
        if($search->getTags()) {
            $query = $query->andWhere('p.tags like :val')
                           ->setParameter('val', "%{$search->getTags()}%");
        }

        // Catégories
        if($search->getCategories()) {
            $query = $query->join('p.category', 'c')
                           ->andWhere('c.id IN (:categories)')
                           ->setParameter('categories', $search->getCategories());
        }

      return $query->getQuery()->getResult();
    }
}
