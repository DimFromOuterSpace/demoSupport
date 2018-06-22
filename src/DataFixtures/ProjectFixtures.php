<?php

namespace App\DataFixtures;

use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ProjectFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 5; ++$i) {
            $project = new Project();
            $project->setNom('Project '.$i);
            $manager->persist($project);
            $this->setReference('project-'.$i, $project);
        }
        $manager->flush();
    }
}
