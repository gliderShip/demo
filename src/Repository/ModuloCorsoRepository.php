<?php

namespace App\Repository;

use App\Entity\ModuloCorso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ModuloCorso|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModuloCorso|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModuloCorso[]    findAll()
 * @method ModuloCorso[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModuloCorsoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModuloCorso::class);
    }

    // /**
    //  * @return ModuloCorso[] Returns an array of ModuloCorso objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ModuloCorso
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
