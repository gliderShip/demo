<?php

namespace App\Repository;

use App\Entity\FacebookUser;
use App\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Symfony\Component\String\u;

/**
 * @method FacebookUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method FacebookUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method FacebookUser[]    findAll()
 * @method FacebookUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FacebookUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FacebookUser::class);
    }

    public function findByPage(int $page = 1): Paginator
    {
        $qb = $this->createQueryBuilder('u')
            ->orderBy('u.id', 'ASC');

        return (new Paginator($qb, $pageSize = 100))->paginate($page);
    }

    /**
     * @return FacebookUser[]
     */
    public function findBySearchQuery(string $query, int $limit = Paginator::PAGE_SIZE): array
    {
        $searchTerms = $this->extractSearchTerms($query);

        if (0 === \count($searchTerms)) {
            return [];
        }

        $queryBuilder = $this->createQueryBuilder('u');

        foreach ($searchTerms as $key => $term) {
            $queryBuilder
                ->orWhere('u.workCompany LIKE :t_' . $key)
                ->setParameter('t_' . $key, '%' . $term . '%');
        }

        return $queryBuilder
//            ->orderBy('u.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Transforms the search string into an array of search terms.
     */
    private function extractSearchTerms(string $searchQuery): array
    {
        $searchQuery = u($searchQuery)->replaceMatches('/[[:space:]]+/', ' ')->trim();
        $terms = array_unique($searchQuery->split(' '));

        // ignore the search terms that are too short
        return array_filter($terms, function ($term) {
            return 3 <= $term->length();
        });
    }
    // /**
    //  * @return FaceBookUsers[] Returns an array of FaceBookUsers objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FaceBookUsers
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
