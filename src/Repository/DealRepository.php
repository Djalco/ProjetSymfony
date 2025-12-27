<?php

namespace App\Repository;

use App\Entity\Deal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Deal>
 */
class DealRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Deal::class);
    }

    /**
     * @return Deal[] Returns an array of Deal objects
     */
    public function findAll(): array
    {
        return $this->createQueryBuilder('d')
            /* ->andWhere('d.id = :id')
            ->setParameter('id', $id)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
             */->getQuery()
           ->getResult()
       ;
    }

    public function findOneBySomeField($value): ?Deal
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
