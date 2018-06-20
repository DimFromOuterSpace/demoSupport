<?php

namespace App\Repository;

use App\Entity\Support;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\RegistryInterface;

class SupportRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Support::class);
    }

    private function paginate(QueryBuilder $queryBuilder, int $number, int $page): Pagerfanta
    {
        $pager = new Pagerfanta(new DoctrineORMAdapter($queryBuilder));
        $pager->setMaxPerPage($number);
        $pager->setCurrentPage($page);

        return $pager;
    }

    public function getLastSupport($number = 10, $page = 1): Pagerfanta
    {
        $queryBuilder = $this->createQueryBuilder('support')
            ->select('support', 'company')
            ->innerJoin('support.company', 'company')
            ->orderBy('support.id', 'DESC');

        return $this->paginate($queryBuilder, $number, $page);
    }
}
