<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @return Product[]
     */
    public function findWithSearch($search): array
    {
        $query = $this->createQueryBuilder('p');

        if ($search->getMinPrice()) {
            $query->andWhere('p.price > :minPrice')->setParameter('minPrice', $search->getMinPrice() * 100);
        }

        if ($search->getMaxPrice()) {
            $query->andWhere('p.price < :maxPrice')->setParameter('maxPrice', $search->getMaxPrice() * 100);
        }

        if ($search->getTags()) {
            $query->andWhere('p.tags LIKE :val')->setParameter('val', "%{$search->getTags()}%");
        }

        if ($search->getCategories()) {
            $query
                ->join('p.category', 'c')
                ->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $search->getCategories());
        }

        return $query->getQuery()->getResult();
    }

    /**
     * @return Product[]
     */
    public function searchByTerm(string $term, int $limit = 8): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.name LIKE :term OR p.tags LIKE :term')
            ->setParameter('term', sprintf('%%%s%%', $term))
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}