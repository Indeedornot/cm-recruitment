<?php

namespace App\Repository;

use App\Entity\CopyText;
use App\Entity\EmailReport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CopyText|null find($id, $lockMode = null, $lockVersion = null)
 * @method CopyText|null findOneBy(array $criteria, array $orderBy = null)
 * @method CopyText[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CopyTextRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CopyText::class);
    }

    /**
     * @return CopyText[]
     */
    public function findAll(): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.disabledAt IS NULL')
            ->getQuery()
            ->getResult();
    }
}
