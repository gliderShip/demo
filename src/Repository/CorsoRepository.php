<?php

namespace App\Repository;

use App\Entity\Corso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Corso|null find($id, $lockMode = null, $lockVersion = null)
 * @method Corso|null findOneBy(array $criteria, array $orderBy = null)
 * @method Corso[]    findAll()
 * @method Corso[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CorsoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Corso::class);
    }

    // /**
    //  * @return Corso[] Returns an array of Corso objects
    //  */
    public function findByExampleField($value)
    {
        return
            $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getArrayResult()
        ;
    }



    public function findOneBySomeField($value): ?Corso
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val', $value)
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
