<?php

namespace App\Repository;

use App\Entity\ModuloInsegnamento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ModuloInsegnamento|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModuloInsegnamento|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModuloInsegnamento[]    findAll()
 * @method ModuloInsegnamento[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModuloInsegnamentoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModuloInsegnamento::class);
    }

    // /**
    //  * @return ModuloInsegnamento[] Returns an array of ModuloInsegnamento objects
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
    public function findOneBySomeField($value): ?ModuloInsegnamento
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
