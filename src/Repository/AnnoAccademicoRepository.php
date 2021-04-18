<?php

namespace App\Repository;

use App\Entity\AnnoAccademico;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AnnoAccademico|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnnoAccademico|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnnoAccademico[]    findAll()
 * @method AnnoAccademico[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnoAccademicoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnnoAccademico::class);
    }

    // /**
    //  * @return AnnoAccademico[] Returns an array of AnnoAccademico objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AnnoAccademico
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
