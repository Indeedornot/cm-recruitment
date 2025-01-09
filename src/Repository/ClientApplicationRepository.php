<?php

namespace App\Repository;

use App\Entity\ClientApplication;
use App\Entity\CopyText;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ClientApplication|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientApplication|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientApplication[] findAll()
 * @method ClientApplication[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientApplicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientApplication::class);
    }
}
