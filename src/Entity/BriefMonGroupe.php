<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\BriefMonGroupeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=BriefMonGroupeRepository::class)
 * @ApiResource ()
 */
class BriefMonGroupe
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
     * @ORM\OneToMany(targetEntity=Groupe::class, mappedBy="briefMonGroupe")
     */
    private $groupe;

    /**
     * @ORM\OneToMany(targetEntity=Brief::class, mappedBy="briefMonGroupe")
     * @ApiSubresource ()
     * @Groups ({"getbpromo:read"})
     */
    private $brief;

    public function __construct()
    {
        $this->groupe = new ArrayCollection();
        $this->brief = new ArrayCollection();
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
     * @return Collection|Groupe[]
     */
    public function getGroupe(): Collection
    {
        return $this->groupe;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupe->contains($groupe)) {
            $this->groupe[] = $groupe;
            $groupe->setBriefMonGroupe($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupe->removeElement($groupe)) {
            // set the owning side to null (unless already changed)
            if ($groupe->getBriefMonGroupe() === $this) {
                $groupe->setBriefMonGroupe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Brief[]
     */
    public function getBrief(): Collection
    {
        return $this->brief;
    }

    public function addBrief(Brief $brief): self
    {
        if (!$this->brief->contains($brief)) {
            $this->brief[] = $brief;
            $brief->setBriefMonGroupe($this);
        }

        return $this;
    }

    public function removeBrief(Brief $brief): self
    {
        if ($this->brief->removeElement($brief)) {
            // set the owning side to null (unless already changed)
            if ($brief->getBriefMonGroupe() === $this) {
                $brief->setBriefMonGroupe(null);
            }
        }

        return $this;
    }
}
