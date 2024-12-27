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

    // ...existing code...
}
