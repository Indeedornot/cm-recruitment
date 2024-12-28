<?php

namespace App\Repository;

use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Question>
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

    public function findAll(): array
    {
        return $this->createQueryBuilder('q')
            ->orderBy('q.sortOrder', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // ...existing code...
}
