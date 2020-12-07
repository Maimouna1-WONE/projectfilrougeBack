<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\FilDiscussionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=FilDiscussionRepository::class)
 * @ApiResource ()
 */
class FilDiscussion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=ApprenantLivrablePartiel::class, inversedBy="filDiscussions")
     * @Groups ({"comm:read"})
     */
    private $apprenantlivrablepartiel;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="filDiscussionController")
     */
    private $commentaire;

    public function __construct()
    {
        $this->commentaire = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApprenantlivrablepartiel(): ?ApprenantLivrablePartiel
    {
        return $this->apprenantlivrablepartiel;
    }

    public function setApprenantlivrablepartiel(?ApprenantLivrablePartiel $apprenantlivrablepartiel): self
    {
        $this->apprenantlivrablepartiel = $apprenantlivrablepartiel;

        return $this;
    }

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaire(): Collection
    {
        return $this->commentaire;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaire->contains($commentaire)) {
            $this->commentaire[] = $commentaire;
            $commentaire->setFilDiscussion($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaire->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getFilDiscussion() === $this) {
                $commentaire->setFilDiscussion(null);
            }
        }

        return $this;
    }
}
