<?php

namespace App\DataFixtures;


use App\Entity\Comment;
use App\Entity\Support;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return [
            UserFixtures::class,
            SupportFixtures::class
        ];
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 1000; ++$i) {
            $comment = new Comment();
            $moduloSupport = ($i % 500 + 1);

            /** @var Support $support */
            $support = $this->getReference('support-'.$moduloSupport);

            $comment->setSupport($support);
            $comment->setAuthor($support->getAuthor());

            $comment->setContent($this->faker->realText());
            $randomDate = $this->faker->dateTime();
            $comment->setCreatedAt($randomDate);
            $comment->setUpdatedAt($randomDate);
            $manager->persist($comment);
        }
        $manager->flush();
    }
}