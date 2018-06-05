<?php
// src/DataFixtures/CompanyFixtures.php
namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CompanyFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; $i++) {
            $company = new Company();
            $company->setLabel("Entreprise ".$i);
            $manager->persist($company);
            $this->setReference('company-'.$i, $company);
        }

        $manager->flush();
    }
}