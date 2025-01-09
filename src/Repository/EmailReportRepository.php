<?php

namespace App\Repository;

use App\Entity\EmailReport;
use App\Entity\Posting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EmailReport|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmailReport|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmailReport[] findAll()
 * @method EmailReport[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmailReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmailReport::class);
    }
}
