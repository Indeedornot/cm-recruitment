<?php

namespace App\Repository;

use App\Entity\QuestionnaireAnswer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuestionnaireAnswer>
 */
class QuestionnaireAnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuestionnaireAnswer::class);
    }

    /**
     * Retrieves latest answer for each question in the given questionnaire.
     */
    public function getPreviousAnswers(array $questionIds, int $userId): array
    {
        $qb = $this->createQueryBuilder('qa')
            ->select('qa')
            ->join('qa.question', 'q')
            ->join('qa.application', 'a')
            ->andWhere('a.client = :userId')
            ->setParameter('userId', $userId)
            ->andWhere('q.id IN (:questionIds)')
            ->setParameter('questionIds', $questionIds)
            ->orderBy('qa.createdAt', 'DESC')
            ->groupBy('q.id');

        return $qb->getQuery()->getResult();
    }

    // ...existing code...
}
