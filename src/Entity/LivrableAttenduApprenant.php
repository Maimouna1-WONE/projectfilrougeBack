<?php

namespace App\Entity;

use App\Repository\LivrableAttenduApprenantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LivrableAttenduApprenantRepository::class)
 */
class LivrableAttenduApprenant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\OneToMany(targetEntity=LivrableAttendu::class, mappedBy="livrableattenduapprenant")
     */
    private $livrableAttendus;

    /**
     * @ORM\OneToMany(targetEntity=Apprenant::class, mappedBy="livrableAttenduApprenant")
     */
    private $apprenant;

    public function __construct()
    {
        $this->livrableAttendus = new ArrayCollection();
        $this->apprenant = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return Collection|LivrableAttendu[]
     */
    public function getLivrableAttendus(): Collection
    {
        return $this->livrableAttendus;
    }

    public function addLivrableAttendu(LivrableAttendu $livrableAttendu): self
    {
        if (!$this->livrableAttendus->contains($livrableAttendu)) {
            $this->livrableAttendus[] = $livrableAttendu;
            $livrableAttendu->setLivrableattenduapprenant($this);
        }

        return $this;
    }

    public function removeLivrableAttendu(LivrableAttendu $livrableAttendu): self
    {
        if ($this->livrableAttendus->removeElement($livrableAttendu)) {
            // set the owning side to null (unless already changed)
            if ($livrableAttendu->getLivrableattenduapprenant() === $this) {
                $livrableAttendu->setLivrableattenduapprenant(null);
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
            $apprenant->setLivrableAttenduApprenant($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenant->removeElement($apprenant)) {
            // set the owning side to null (unless already changed)
            if ($apprenant->getLivrableAttenduApprenant() === $this) {
                $apprenant->setLivrableAttenduApprenant(null);
            }
        }

        return $this;
    }
}
