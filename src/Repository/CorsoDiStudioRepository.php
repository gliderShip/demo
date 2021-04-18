<?php

namespace App\Repository;

use App\Entity\CorsoDiStudio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CorsoDiStudio|null find($id, $lockMode = null, $lockVersion = null)
 * @method CorsoDiStudio|null findOneBy(array $criteria, array $orderBy = null)
 * @method CorsoDiStudio[]    findAll()
 * @method CorsoDiStudio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CorsoDiStudioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CorsoDiStudio::class);
    }

    // /**
    //  * @return CorsoDiStudio[] Returns an array of CorsoDiStudio objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CorsoDiStudio
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
