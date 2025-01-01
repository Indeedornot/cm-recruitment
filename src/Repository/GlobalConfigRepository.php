<?php

namespace App\Repository;

use App\Entity\GlobalConfig;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class GlobalConfigRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GlobalConfig::class);
    }

    public function findAll(): array
    {
        return $this->findBy(['disabledAt' => null]);
    }

    public function findByKey(string $key): ?GlobalConfig
    {
        return $this->findOneBy(['key' => $key, 'disabledAt' => null]);
    }

    public function getValue(string $key, mixed $default = null): mixed
    {
        $config = $this->findByKey($key);
        return $config ? $config->getValue() : $default;
    }

    public function save(GlobalConfig $config, bool $flush = false): void
    {
        $this->getEntityManager()->persist($config);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
