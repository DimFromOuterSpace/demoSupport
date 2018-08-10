<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SupportRepository")
 */
class Support
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
    private $title;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(type = "text", nullable = true)
     */
    private $description;

    /**
     * @var \DateTime
     * @Assert\NotBlank()
     * @ORM\Column(type = "datetime", nullable = false)
     */
    private $createdAt;

    /**
     * @var User
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="supports")
     */
    private $author;

    /**
     * @var Company
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="supports")
     */
    private $company;

    /**
     * @var ArrayCollection|Comment[]
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Comment",
     *     mappedBy="support"
     * )
     *
     */
    private $comments;

    /**
     * Support constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->comments = new ArrayCollection();
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
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(?\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
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
     * @return User
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * @param User|null $author
     */
    public function setAuthor(?User $author): void
    {
        $this->author = $author;
    }

    /**
     * @return ArrayCollection
     */
    public function getComments(): ?Collection
    {
        return $this->comments;
    }

    /**
     * @param Comment $comment
     */
    public function addComment(?Comment $comment): void
    {
        $comment->setSupport($this);

        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
        }
    }

    public function removeComment(?Comment $comment): void
    {
        $comment->setSupport(null);

        $this->comments->removeElement($comment);
    }
}
