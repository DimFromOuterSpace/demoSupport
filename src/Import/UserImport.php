<?php

namespace App\Import;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use App\Utils\Sanitize;
use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use App\Model\UserCsv;

class UserImport
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var LoggerInterface
     */
    private $logger;
    private $decoder;
    private $denormalizer;
    private $existingUsers;
    private $data;
    private $usersArray;

    /**
     * UserImport constructor.
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        DecoderInterface $decoder,
        DenormalizerInterface $denormalizer
    )
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->decoder = $decoder;
        $this->denormalizer  = $denormalizer;
    }

    public function process()
    {
        $emailList = [];
        array_map(function (UserCsv $userCsv) use ($emailList){
            $emailList[] = $userCsv->getEmail();
        }, $this->usersArray);

        $this->setExistingUsers($emailList);

        /** @var UserCsv $user */
        foreach ($this->usersArray as $user) {
            if(!isset($this->existingUsers[$user->getEmail()])) {
                continue;
            }
            $user = new User();
            $user->setUsername($user->getEmail());
            $user->setEmail($user->getEmail());
            $user->setEnabled(true);
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));
        }


        $user = new User();
        $user->setUsername($email);
        $user->setEmail($email);
        $user->setEnabled(true);
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));

        /** @var Company $company */
        $company = $this->entityManager->getRepository(Company::class)->findOneBy(['label' => $row['company']]);

        if (!$company) {
            //Création compagnie car non existante
            $company = new Company();
            $company->setLabel($row['company']);
            $company->setMailContact($email);
            $this->entityManager->persist($company);
        }

        $user->setCompany(($company));

        $this->entityManager->persist($user);
    }

    function deserializeUsers(string $delimitor)
    {
        /** @var array $users */
        $users = $this->decoder->decode(
            $this->data,
            'csv',
            ['csv_delimiter' => $delimitor]
        );

        foreach ($users as $user) {
            $userCsv = $this->denormalizer->denormalize(
                $user,
                UserCsv::class
            );

            // TODO Check usercsv
            $this->usersArray[] = $userCsv;
        }


    }

    public function setData(array $data)
    {
        $this->data = $data;
    }

    private function setExistingUsers($emailList)
    {
        $this->existingUsers = $this
                ->entityManager
                ->getRepository(User::class)
                ->getUserEmail($emailList);
    }
}
