<?php

namespace App\Repository;

use App\Entity\Insegnamento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Insegnamento|null find($id, $lockMode = null, $lockVersion = null)
 * @method Insegnamento|null findOneBy(array $criteria, array $orderBy = null)
 * @method Insegnamento[]    findAll()
 * @method Insegnamento[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InsegnamentoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Insegnamento::class);
    }

    // /**
    //  * @return Insegnamento[] Returns an array of Insegnamento objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Insegnamento
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
