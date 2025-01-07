<?php

namespace App\Repository;

use App\Entity\SubPosting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SubPosting|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubPosting|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubPosting[] findAll()
 * @method SubPosting[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubPostingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubPosting::class);
    }
}
