<?php

namespace App\DataFixtures;

use App\Entity\Support;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class SupportFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 500; ++$i) {
            $support = new Support();
            $support->setTitle('Demande '.$i);
            $support->setDescription('Ceci est un test de descriptif pour la demande '.$i);
            $support->setCreatedAt(new \DateTime());
            $moduloUser = ($i % 3 + 1);
            /** @var User $user */
            $user = $this->getReference('user-'.$moduloUser);

            $support->setCompany($user->getCompany());
            $support->setAuthor($user);
            $manager->persist($support);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
