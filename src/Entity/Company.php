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
     * @ORM\Column(type = "string", nullable=false)
     */
    private $label;

    /**
     * @var bool
     * @Assert\NotNull()
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $active = 1;

    /**
     * @var string
     * @Assert\NotNull()
     * @Assert\Email()
     * @ORM\Column(type="string", nullable=false)
     */
    private $mailContact;

    /**
     * @var Support[]|ArrayCollection
     * @ORM\OneToMany(  targetEntity="App\Entity\Support",
     *                  mappedBy="company",
     *                  orphanRemoval=true,
     *                  cascade={"persist"})
     */
    private $supports;

    /**
     * @var Project[]|ArrayCollection
     * @ORM\ManyToMany(
     *     targetEntity="App\Entity\Project",
     *     cascade={"persist"}
     *     )
     * @ORM\OrderBy(
     *     {"nom":"ASC"}
     * )
     * @Assert\Count(min="0",minMessage="company.constraint.project.min")
     */
    //TODO : revoir fixtures
    private $projects;

    /**
     * Company constructor.
     */
    public function __construct()
    {
        $this->supports = new ArrayCollection();
        $this->projects = new ArrayCollection();
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
     * @return null|string
     */
    public function getMailContact(): ?string
    {
        return $this->mailContact;
    }

    /**
     * @param null|string $mailContact
     */
    public function setMailContact(?string $mailContact): void
    {
        $this->mailContact = $mailContact;
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

    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(?Project $project): void
    {
        if(!$this->projects->contains($project)) {
            $this->projects->add($project);
        }
    }

    public function removeProject(Project $project): void
    {
        $this->projects->removeElement($project);
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
