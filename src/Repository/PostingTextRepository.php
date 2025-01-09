<?php

namespace App\Repository;

use App\Entity\Posting;
use App\Entity\PostingText;
use App\Entity\QuestionnaireAnswer;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PostingText|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostingText|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostingText[] findAll()
 * @method PostingText[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostingTextRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostingText::class);
    }

    public function getPostingIdsByTextFilter(
        string $alias,
        string $key,
        string $value,
        string $condition = '=',
        int $type = ParameterType::INTEGER
    ): QueryBuilder {
        if ($condition === 'LIKE') {
            $value = '%' . $value . '%';
            $type = ParameterType::STRING;
        }

        $ctAlias = 'c' . $alias;
        $postingAlias = 'p' . $alias;
        $ptAlias = 'ct' . $alias;

        return $this->createQueryBuilder($ptAlias)
            ->select("$postingAlias.id")
            ->leftJoin("$ptAlias.copyText", $ctAlias)
            ->leftJoin("$ptAlias.posting", $postingAlias)
            ->andWhere("$ctAlias.key = " . ':cKey' . $alias)
            ->andWhere("$ptAlias.value $condition " . ':cValue' . $alias)
            ->setParameter('cKey' . $alias, $key)
            ->setParameter('cValue' . $alias, $value, $type);
    }

    //    /**
    //     * @return Posting[] Returns an array of Posting objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Posting
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
