<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var Company
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="users")
     */
    private $company;

    /**
     * @var Support[]|ArrayCollection
     * @ORM\OneToMany(  targetEntity="App\Entity\Support",
     *                  mappedBy="user",
     *                  orphanRemoval=true,
     *                  cascade={"persist"})
     */
    private $supports;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        $this->supports = new ArrayCollection();
    }

    /**
     * @return Company
     */
    public function getCompany(): ?Company
    {
        return $this->company;
    }

    /**
     * @param Company $company
     */
    public function setCompany(?Company $company): void
    {
        $this->company = $company;
    }

    /**
     * @return ArrayCollection
     */
    public function getSupports(): ArrayCollection
    {
        return $this->supports;
    }

    /**
     * @param Support|null $support
     */
    public function addSupport(?Support $support): void
    {
        $support->setAuthor($this);

        if (!$this->supports->contains($support)) {
            $this->supports->add($support);
        }
    }

    /**
     * @param Support $support
     */
    public function removeSupport(Support $support): void
    {
        $support->setAuthor(null);
        $this->supports->removeElement($support);
    }
}
