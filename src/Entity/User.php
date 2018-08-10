<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var Company
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="users")
     */
    private $company;

    /**
     * @var Support[]|ArrayCollection
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Support",
     *     mappedBy="author",
     *     orphanRemoval=true,
     *     cascade={"persist"}
     * )
     */
    private $supports;

    /**
     * @var Comment[]|ArrayCollection
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Comment",
     *     mappedBy="author"
     * )
     */
    private $comments;

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
    public function getSupports(): Collection
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
     * @param Comment $comment
     */
    public function removeSupport(Support $support): void
    {
        $support->setAuthor(null);
        $this->supports->removeElement($support);
    }

    /**
     * @return ArrayCollection
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * @param Comment|null $comment
     */
    public function addComment(?Comment $comment): void
    {
        $comment->setAuthor($this);

        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
        }
    }

    /**
     * @param Comment $comment
     */
    public function removeComment(Comment $comment): void
    {
        $comment->setAuthor(null);
        $this->comments->removeElement($comment);
    }
}
