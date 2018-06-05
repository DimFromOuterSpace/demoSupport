<?php
// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\Support;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 20; $i++) {
            $support = new Support();
            $support->setTitle("Demande ".$i);
            $support->setDescription("Ceci est un test de descriptif pour la demande ".$i);
            $support->setCreatedAt(new \DateTime());
            $manager->persist($support);
        }

        $manager->flush();
    }
}