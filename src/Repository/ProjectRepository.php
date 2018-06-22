<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function getProject()
    {
        $queryBuilder = $this->createQueryBuilder('project')
            ->orderBy('project.nom', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }
}
