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
    private $passwordEncoder;

    /**
     * @var array
     */
    private $users;

    /**
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        //creation du tableau d'info
        $this->users = [
            [
               'username' => 'dusseno',
               'email' => 'dusseno@os-concept.com',
               'password' => 'dusseno',
               'roles' => [],
            ],
            [
                'username' => 'tdelecourt',
                'email' => 'tdelecourt@os-concept.com',
                'password' => 'tdelecourt',
                'roles' => ['ROLE_USER'],
            ],
            [
                'username' => 'bcanape',
                'email' => 'bcanape@os-concept.com',
                'password' => 'bcanape',
                'roles' => ['ROLE_USER'],
            ],
            [
                'username' => 'jrigaux',
                'email' => 'jrigaux@os-concept.com',
                'password' => 'jrigaux',
                'roles' => ['ROLE_ADMIN'],
            ],
            [
                'username' => 'awelkamp',
                'email' => 'awelkamp@os-concept.com',
                'password' => 'awelkamp',
                'roles' => ['ROLE_SUPER_ADMIN'],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->users as $tabUser) {
            $user = $this->createUser($tabUser);
            $manager->persist($user);
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
     * @param array $tabUser
     *
     * @return UserInterface
     */
    public function createUser(array $tabUser): UserInterface
    {
        $user = new User();
        $user->setUsername($tabUser['username']);
        $user->setEmail($tabUser['email']);
        $user->setEnabled(1);
        $user->setRoles($tabUser['roles']);

        $password = $this->passwordEncoder->encodePassword($user, $tabUser['password']);
        $user->setPassword($password);

        return $user;
    }
}