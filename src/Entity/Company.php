<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompanyRepository")
 */
class Company
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type = "integer")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(type = "string")
     */
    private $label;

    /**
     * @var bool
     * @Assert\NotNull()
     * @ORM\Column(type = "boolean")
     */
    private $active = 1;

    /**
     * @var Support[]|ArrayCollection
     * @ORM\OneToMany(  targetEntity="App\Entity\Support",
     *                  mappedBy="company",
     *                  orphanRemoval=true,
     *                  cascade={"persist"})
     */
    private $supports;

    /**
     * Company constructor.
     */
    public function __construct()
    {
        $this->supports = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel(?string $label): void
    {
        $this->label = $label;
    }

    /**
     * @return bool
     */
    public function isActive(): ?bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(?bool $active): void
    {
        $this->active = $active;
    }

    public function getSupports(): Collection
    {
        return $this->supports;
    }

    public function addSupport(?Support $support): void
    {
        $support->setCompany($this);

        if (!$this->supports->contains($support)) {
            $this->supports->add($support);
        }
    }

    public function removeSupport(Support $support): void
    {
        $support->setCompany(null);
        $this->supports->removeElement($support);
    }

    /**
     * TODO methode requise pour easyadmin.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->label;
    }
}
