<?php

namespace App\Repository;

use App\Entity\PeriodTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PeriodTrait|null find($id, $lockMode = null, $lockVersion = null)
 * @method PeriodTrait|null findOneBy(array $criteria, array $orderBy = null)
 * @method PeriodTrait[]    findAll()
 * @method PeriodTrait[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PeriodTraitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PeriodTrait::class);
    }

    // /**
    //  * @return PeriodTrait[] Returns an array of PeriodTrait objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PeriodTrait
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
