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

    public function getLastSupport($number = 10, $page = 1): Pagerfanta
    {
        $queryBuilder = $this->createQueryBuilder('support')
            ->select('support', 'company')
            ->innerJoin('support.company', 'company')
            ->orderBy('support.id', 'DESC');

        return $this->paginate($queryBuilder, $number, $page);
    }

    public function getPaginatedSupportByCompany(int $idCompany, int $number = 10, int $page = 1): Pagerfanta
    {
        $queryBuilder = $this->createQueryBuilder('support')
            ->andWhere('support.company = :idCompany')
            ->setParameter('idCompany', $idCompany)
            ->orderBy('support.id', 'DESC');

        return $this->paginate($queryBuilder, $number, $page);
    }

    private function paginate(QueryBuilder $queryBuilder, int $number, int $page): Pagerfanta
    {
        $pager = new Pagerfanta(new DoctrineORMAdapter($queryBuilder));
        $pager->setMaxPerPage($number);
        $pager->setCurrentPage($page);

        return $pager;
    }

    /**
     * @param int $idUser
     * @param int $number
     * @param int $page
     *
     * @return Pagerfanta
     */
    public function getPaginatedSupportByUser(int $idAuthor, int $number = 10, int $page = 1): Pagerfanta
    {
        $queryBuilder = $this->createQueryBuilder('support')
            ->andWhere('support.author = :idAuthor')
            ->setParameter('idAuthor', $idAuthor)
            ->orderBy('support.id', 'DESC');

        return $this->paginate($queryBuilder, $number, $page);
    }

    public function getPaginatedSupportByCompanyMinusUser(int $idCompany, int $idAuthor, int $number = 10, int $page = 1): Pagerfanta
    {
        $queryBuilder = $this->createQueryBuilder('support')
            ->andWhere('support.company = :idCompany')
            ->andWhere('support.author <> :idAuthor')
            ->setParameter('idCompany', $idCompany)
            ->setParameter('idAuthor', $idAuthor)
            ->orderBy('support.id', 'DESC');

        return $this->paginate($queryBuilder, $number, $page);
    }
}
