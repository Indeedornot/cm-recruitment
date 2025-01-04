<?php

namespace App\Repository;

use App\Entity\Posting;
use App\Entity\PostingText;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Posting>
 */
class PostingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PostingTextRepository $ptRepository)
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

    public function getDisplayPostingsBaseQb(array $filters = []): QueryBuilder
    {
        $qb = $this->createQueryBuilder('p')
            ->andWhere('p.disabledAt IS NULL')
            ->andWhere('p.completedAt IS NULL')
            ->andWhere('p.closingDate > :now')
            ->setParameter('now', new \DateTime())
            ->orderBy('p.title', 'ASC');

        if (!empty($filters['text'])) {
            $qb
                ->join('p.assignedTo', 'u')
                ->andWhere('p.title LIKE :text OR p.description LIKE :text OR u.name LIKE :text')
                ->setParameter('text', '%' . $filters['text'] . '%');
        }

        if (!empty($filters['assignedTo'])) {
            $qb
                ->andWhere('p.assignedTo = :assignedTo')
                ->setParameter('assignedTo', $filters['assignedTo']);
        }

        if (!empty($filters['age'])) {
            $minAge = $this->ptRepository->getPostingIdsByTextFilter('Min', 'age_min', $filters['age'], '<=');
            $maxAge = $this->ptRepository->getPostingIdsByTextFilter('Max', 'age_max', $filters['age'], '>=');

            $qb->andWhere('p.id IN (' . $minAge->getDQL() . ')')
                ->andWhere('p.id IN (' . $maxAge->getDQL() . ')')
                ->setParameters(new ArrayCollection(array_merge(
                    $minAge->getParameters()->toArray(),
                    $maxAge->getParameters()->toArray(),
                    $qb->getParameters()->toArray()
                )));
        }

        if (!empty($filters['schedule'])) {
            $schedule = $this->ptRepository->getPostingIdsByTextFilter(
                'Schedule',
                'schedule',
                $filters['schedule'],
                'LIKE'
            );

            $qb->andWhere('p.id IN (' . $schedule->getDQL() . ')')
                ->setParameters(new ArrayCollection(array_merge(
                    $schedule->getParameters()->toArray(),
                    $qb->getParameters()->toArray()
                )));
        }
    }

    public function getAdminDisplayPostingsQb(array $filters = []): QueryBuilder
    {
        $qb = $this->createQueryBuilder('p')
            ->andWhere('p.disabledAt IS NULL')
            ->andWhere('p.completedAt IS NULL')
            ->orderBy('p.title', 'ASC');

        return $qb;
    }

    public function getDisplayedPostingsQb(array $filters = []): QueryBuilder
    {
        return $qb;
    }

    public function updateActiveClosingDates(DateTimeImmutable $date): void
    {
        $this->createQueryBuilder('p')
            ->update()
            ->set('p.closingDate', ':date')
            ->andWhere('p.disabledAt IS NULL')
            ->andWhere('p.completedAt IS NULL')
            ->setParameter('date', $date)
            ->getQuery()
            ->execute();
    }
}
