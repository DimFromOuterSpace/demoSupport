<?php

namespace App\DataFixtures;

use App\Entity\Support;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class SupportFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 2000; ++$i) {
            $support = new Support();
            $support->setTitle('Demande '.$i);
            $support->setDescription('Ceci est un test de descriptif pour la demande '.$i);
            $support->setCreatedAt(new \DateTime());
            $support->setCompany($this->getReference('company-'.($i % 5 + 1)));
            $manager->persist($support);
        }

        $manager->flush();
    }
}
