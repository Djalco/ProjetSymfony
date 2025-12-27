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

    public function listDeals() : array{
        return $this->createQueryBuilder('deal')
            ->andWhere('deal.enable = :valeur')
            ->setParameter('valeur',true)
            ->orderBy('deal.create_at','DESC')
            ->getQuery()
            ->getResult();
    }
}