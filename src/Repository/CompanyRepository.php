<?php

namespace App\Repository;

use App\Entity\Company;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CompanyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Company::class);
    }

    public function getLastCompanies($number = 10)
    {
        $queryBuilder = $this->createQueryBuilder('company')
            ->orderBy('company.id', 'DESC')
            ->setMaxResults($number);

        return $queryBuilder->getQuery()->getResult();
    }
}
