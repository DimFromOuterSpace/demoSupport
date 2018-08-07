<?php

namespace App\DataFixtures;

use App\Entity\Support;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class SupportFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 2000; ++$i) {
            $support = new Support();
            $support->setTitle('Demande '.$i);
            $support->setDescription('Ceci est un test de descriptif pour la demande '.$i);
            $support->setCreatedAt(new \DateTime());
            $modulo = ($i % 3 + 1);
            $support->setCompany($this->getReference('company-'.$modulo));
            $support->setAuthor($this->getReference('user-'.$modulo));
            $manager->persist($support);
        }

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
