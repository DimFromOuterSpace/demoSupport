<?php

namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CompanyFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 5; ++$i) {
            $company = new Company();
            $company->setLabel('Entreprise '.$i);
            $company->setMailContact('dusseno@os-concept.com');
            $manager->persist($company);
            $this->setReference('company-'.$i, $company);
            $company->addProject($this->getReference('project-'.($i % 5 + 1)));
            $company->addProject($this->getReference('project-'.($i % 5 + 1)));
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ProjectFixtures::class,
        ];
    }
}
