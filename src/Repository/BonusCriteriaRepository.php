<?php

namespace App\Repository;

use App\Entity\BonusCriteria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\ParameterType;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BonusCriteria>
 */
class BonusCriteriaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BonusCriteria::class);
    }

    /**
     * @param string $key
     * @return BonusCriteria[]
     */
    public function findByPhase(string $key): array
    {
        return $this->createQueryBuilder('b')
            ->where('JSON_CONTAINS_PATH(b.value, \'one\', :key) = 1')
            ->setParameter('key', "$.$key")
            ->orderBy('b.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findLabelsByKeys(array $keys): array
    {
        $result = $this->createQueryBuilder('b')
            ->select('b.key, b.label')
            ->where('b.key IN (:keys)')
            ->setParameter('keys', $keys, ArrayParameterType::STRING)
            ->getQuery()
            ->execute();

        return array_merge(array_map(fn($row) => [$row['key'] => $row['label']], $result));
    }

    public function getValuesByKeys(string $phase, array $keys): array
    {
        $result = $this->createQueryBuilder('b')
            ->select('b.key, JSON_UNQUOTE(JSON_EXTRACT(b.value, :phase)) as value')
            ->where('b.key IN (:keys) AND JSON_CONTAINS_PATH(b.value, \'one\', :phase) = 1')
            ->setParameter('keys', $keys, ArrayParameterType::STRING)
            ->setParameter('phase', "$.$phase")
            ->getQuery()
            ->execute();

        return array_merge(array_map(fn($row) => [$row['key'] => $row['value']], $result));
    }
}
