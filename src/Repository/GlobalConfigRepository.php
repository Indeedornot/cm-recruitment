<?php

namespace App\Repository;

use App\Entity\GlobalConfig;
use App\Entity\Posting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

/**
 * @method GlobalConfig|null find($id, $lockMode = null, $lockVersion = null)
 * @method GlobalConfig|null findOneBy(array $criteria, array $orderBy = null)
 * @method GlobalConfig[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
#[Autoconfigure(public: true)]
class GlobalConfigRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GlobalConfig::class);
    }

    /**
     * @return GlobalConfig[]
     */
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
