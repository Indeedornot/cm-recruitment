<?php

namespace App\Services\Form;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class PaginationService
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function getPagination(
        string $entity,
        array|int $page = 1,
        int $limit = 10
    ): array {
        if (is_array($page)) {
            $limit = $page['limit'];
            $page = $page['page'];
        }

        $repository = $this->em->getRepository($entity);
        $items = $repository->createQueryBuilder('e')
            ->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit)
            ->getResult();

        $total = $repository->createQueryBuilder('e')
            ->select('COUNT(e)')
            ->getQuery()
            ->getSingleScalarResult();

        return [
            'items' => $items,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
        ];
    }

    public function attachPagination(
        QueryBuilder $qb,
        int|array $page = 1,
        ?int $limit = 10
    ): QueryBuilder {
        $page = is_array($page) ? $page : ['page' => $page, 'limit' => $limit];
        $limit = $page['limit'];
        $page = $page['page'];

        return $qb
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);
    }

    public function handleRequest(
        Request $request,
    ): array {
        $page = $request->request->get('page', $request->query->get('page', 1));
        $limit = $request->request->get('limit', $request->query->get('limit', 10));

        return [
            'page' => $page,
            'limit' => $limit,
        ];
    }
}
