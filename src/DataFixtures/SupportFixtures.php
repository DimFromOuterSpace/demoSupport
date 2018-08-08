<?php

namespace App\DataFixtures;

use App\Entity\Support;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class SupportFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 500; ++$i) {
            $support = new Support();
            $support->setTitle('Demande '.$i);
            $support->setDescription('Ceci est un test de descriptif pour la demande '.$i);
            $support->setCreatedAt(new \DateTime());
            $modulo = ($i % 3 + 1);

            /** @var User $user */
            $user = $this->getReference('user-'.$modulo);

            $support->setCompany($user->getCompany());
            $support->setAuthor($user);
            $manager->persist($support);
        }

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
