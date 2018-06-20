<?php

namespace App\Repository;

use App\Entity\Support;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class SupportRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Support::class);
    }

    public function getLastSupport($number = 10)
    {
        $queryBuilder = $this->createQueryBuilder('support')
            ->orderBy('support.id', 'DESC')
            ->setMaxResults($number);

        return $queryBuilder->getQuery()->getResult();
    }
}
