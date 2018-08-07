<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @var array
     */
    private $users;

    /**
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;

        //3 USER, 1 ADMIN, 1 SUPER ADMIN
        for ($i = 1; $i <= 3; ++$i) {
            $this->users[] =
                [
                    'mail' => 'user'.$i.'@test.com',
                    'password' => 'test',
                    'role' => ['ROLE_USER'],
                    'name' => 'user'.$i,
                    'company' => 'company-'.$i,
                    'reference' => 'user-'.$i,
                ];
        }

        $this->users[] =
            [
                'mail' => 'admin@test.com',
                'password' => 'test',
                'role' => ['ROLE_ADMIN'],
                'name' => 'admin',
            ];

        $this->users[] =
            [
                'mail' => 'super-admin@test.com',
                'password' => 'test',
                'role' => ['ROLE_SUPER_ADMIN'],
                'name' => 'super-admin',
            ];
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->users as $user) {
            $userResult = $this->createUser($user);
            $manager->persist($userResult);
            if (isset($user['reference'])) {
                $this->setReference($user['reference'], $userResult);
            }
        }
        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [
            CompanyFixtures::class,
        ];
    }

    /**
     * @param array $userToCreate
     *
     * @return UserInterface
     */
    private function createUser(array $userToCreate): UserInterface
    {
        $user = new User();
        $user->setEmail($userToCreate['mail']);
        $user->setEnabled(true);
        $user->setPassword($this->encoder->encodePassword($user, $userToCreate['password']));
        $user->setRoles($userToCreate['role']);
        $user->setUsername($userToCreate['name']);

        if(isset($userToCreate['company'])) {
            $user->setCompany($this->getReference($userToCreate['company']));
        }

        return $user;
    }
}
