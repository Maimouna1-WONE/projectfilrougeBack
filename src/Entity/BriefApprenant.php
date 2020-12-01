<?php

namespace App\Entity;

use App\Repository\BriefApprenantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BriefApprenantRepository::class)
 */
class BriefApprenant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statut;

    /**
     * @ORM\OneToMany(targetEntity=Apprenant::class, mappedBy="briefApprenant")
     */
    private $apprenant;

    /**
     * @ORM\OneToMany(targetEntity=BriefMaPromo::class, mappedBy="briefApprenant")
     */
    private $briefmapromo;

    public function __construct()
    {
        $this->apprenant = new ArrayCollection();
        $this->briefmapromo = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): self
    {
        $this->statut = $statut;

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
            $apprenant->setBriefApprenant($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenant->removeElement($apprenant)) {
            // set the owning side to null (unless already changed)
            if ($apprenant->getBriefApprenant() === $this) {
                $apprenant->setBriefApprenant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|BriefMaPromo[]
     */
    public function getBriefmapromo(): Collection
    {
        return $this->briefmapromo;
    }

    public function addBriefmapromo(BriefMaPromo $briefmapromo): self
    {
        if (!$this->briefmapromo->contains($briefmapromo)) {
            $this->briefmapromo[] = $briefmapromo;
            $briefmapromo->setBriefApprenant($this);
        }

        return $this;
    }

    public function removeBriefmapromo(BriefMaPromo $briefmapromo): self
    {
        if ($this->briefmapromo->removeElement($briefmapromo)) {
            // set the owning side to null (unless already changed)
            if ($briefmapromo->getBriefApprenant() === $this) {
                $briefmapromo->setBriefApprenant(null);
            }
        }

        return $this;
    }
}
