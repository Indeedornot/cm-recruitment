<?php

namespace App\Repository;

use App\Entity\Posting;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Posting>
 */
class PostingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Posting::class);
    }

    //    /**
    //     * @return Posting[] Returns an array of Posting objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Posting
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function getAdminDisplayPostingsQb(array $filters = []): QueryBuilder
    {
        $qb = $this->createQueryBuilder('p')
            ->andWhere('p.disabledAt IS NULL')
            ->orderBy('p.createdAt', 'DESC');

        if (isset($filters['title']) && $filters['title']) {
            $qb
                ->andWhere('p.title LIKE :title')
                ->setParameter('title', '%' . $filters['title'] . '%');
        }

        if (isset($filters['assignedTo']) && $filters['assignedTo']) {
            $qb
                ->andWhere('p.assignedTo = :assignedTo')
                ->setParameter('assignedTo', $filters['assignedTo']);
        }

        return $qb;
    }

    public function getDisplayedPostingsQb(array $filters = []): QueryBuilder
    {
        $qb = $this->createQueryBuilder('p')
            ->andWhere('p.disabledAt IS NULL')
            ->andWhere('p.closingDate > :now')
            ->setParameter('now', new \DateTime())
            ->orderBy('p.createdAt', 'DESC');

        if (isset($filters['title']) && $filters['title']) {
            $qb
                ->andWhere('p.title LIKE :title')
                ->setParameter('title', '%' . $filters['title'] . '%');
        }

        if (isset($filters['assignedTo']) && $filters['assignedTo']) {
            $qb
                ->andWhere('p.assignedTo = :assignedTo')
                ->setParameter('assignedTo', $filters['assignedTo']);
        }

        return $qb;
    }

    public function updateActiveClosingDates(DateTimeImmutable $date): void
    {
        $this->createQueryBuilder('p')
            ->update()
            ->set('p.closingDate', ':date')
            ->andWhere('p.disabledAt IS NULL')
            ->setParameter('date', $date)
            ->getQuery()
            ->execute();
    }
}
