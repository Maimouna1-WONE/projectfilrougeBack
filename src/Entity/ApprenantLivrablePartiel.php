<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ApprenantLivrablePartielRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ApprenantLivrablePartielRepository::class)
 * @ApiResource ()
 */
class ApprenantLivrablePartiel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"comm:read"})
     */
    private $delai;

    /**
     * @ORM\Column(type="boolean")
     */
    private $etat;

    /**
     * @ORM\OneToMany(targetEntity=LivrablePartiel::class, mappedBy="apprenantLivrablePartiel")
     * @Groups ({"comm:read"})
     */
    private $livrablepartiel;

    /**
     * @ORM\OneToMany(targetEntity=Apprenant::class, mappedBy="apprenantLivrablePartiel")
     * @Groups ({"partielget:read"})
     * @Groups ({"comm:read"})
     */
    private $apprenant;

    /**
     * @ORM\OneToMany(targetEntity=FilDiscussion::class, mappedBy="apprenantlivrablepartiel")
     */
    private $filDiscussions;

    public function __construct()
    {
        $this->livrablepartiel = new ArrayCollection();
        $this->apprenant = new ArrayCollection();
        $this->filDiscussions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDelai(): ?string
    {
        return $this->delai;
    }

    public function setDelai(string $delai): self
    {
        $this->delai = $delai;

        return $this;
    }

    public function getEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @return Collection|LivrablePartiel[]
     */
    public function getLivrablepartiel(): Collection
    {
        return $this->livrablepartiel;
    }

    public function addLivrablepartiel(LivrablePartiel $livrablepartiel): self
    {
        if (!$this->livrablepartiel->contains($livrablepartiel)) {
            $this->livrablepartiel[] = $livrablepartiel;
            $livrablepartiel->setApprenantLivrablePartiel($this);
        }

        return $this;
    }

    public function removeLivrablepartiel(LivrablePartiel $livrablepartiel): self
    {
        if ($this->livrablepartiel->removeElement($livrablepartiel)) {
            // set the owning side to null (unless already changed)
            if ($livrablepartiel->getApprenantLivrablePartiel() === $this) {
                $livrablepartiel->setApprenantLivrablePartiel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenant(): Collection
    {
        return $this->apprenant;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenant->contains($apprenant)) {
            $this->apprenant[] = $apprenant;
            $apprenant->setApprenantLivrablePartiel($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenant->removeElement($apprenant)) {
            // set the owning side to null (unless already changed)
            if ($apprenant->getApprenantLivrablePartiel() === $this) {
                $apprenant->setApprenantLivrablePartiel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FilDiscussion[]
     */
    public function getFilDiscussions(): Collection
    {
        return $this->filDiscussions;
    }

    public function addFilDiscussion(FilDiscussion $filDiscussion): self
    {
        if (!$this->filDiscussions->contains($filDiscussion)) {
            $this->filDiscussions[] = $filDiscussion;
            $filDiscussion->setApprenantlivrablepartiel($this);
        }

        return $this;
    }

    public function removeFilDiscussion(FilDiscussion $filDiscussion): self
    {
        if ($this->filDiscussions->removeElement($filDiscussion)) {
            // set the owning side to null (unless already changed)
            if ($filDiscussion->getApprenantlivrablepartiel() === $this) {
                $filDiscussion->setApprenantlivrablepartiel(null);
            }
        }

        return $this;
    }
}
