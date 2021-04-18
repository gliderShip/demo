<?php

namespace App\Repository;

use App\Entity\PianoDiStudio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PianoDiStudio|null find($id, $lockMode = null, $lockVersion = null)
 * @method PianoDiStudio|null findOneBy(array $criteria, array $orderBy = null)
 * @method PianoDiStudio[]    findAll()
 * @method PianoDiStudio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PianoDiStudioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PianoDiStudio::class);
    }

    // /**
    //  * @return PianoDiStudio[] Returns an array of PianoDiStudio objects
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
    public function findOneBySomeField($value): ?PianoDiStudio
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
