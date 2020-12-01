<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\BriefMaPromoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=BriefMaPromoRepository::class)
 * @ApiResource ()
 */
class BriefMaPromo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Brief::class, inversedBy="briefMaPromos")
     * @ApiSubresource ()
     * @Groups ({"bripro:read"})
     */
    private $brief;

    /**
     * @ORM\ManyToOne(targetEntity=Promo::class, inversedBy="briefMaPromos")
     */
    private $promo;

    /**
     * @ORM\OneToMany(targetEntity=LivrablePartiel::class, mappedBy="birefmapromo")
     */
    private $livrablePartiels;

    /**
     * @ORM\ManyToOne(targetEntity=BriefApprenant::class, inversedBy="briefmapromo")
     */
    private $briefApprenant;

    public function __construct()
    {
        $this->livrablePartiels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrief(): ?Brief
    {
        return $this->brief;
    }

    public function setBrief(?Brief $brief): self
    {
        $this->brief = $brief;

        return $this;
    }

    public function getPromo(): ?Promo
    {
        return $this->promo;
    }

    public function setPromo(?Promo $promo): self
    {
        $this->promo = $promo;

        return $this;
    }

    /**
     * @return Collection|LivrablePartiel[]
     */
    public function getLivrablePartiels(): Collection
    {
        return $this->livrablePartiels;
    }

    public function addLivrablePartiel(LivrablePartiel $livrablePartiel): self
    {
        if (!$this->livrablePartiels->contains($livrablePartiel)) {
            $this->livrablePartiels[] = $livrablePartiel;
            $livrablePartiel->setBirefmapromo($this);
        }

        return $this;
    }

    public function removeLivrablePartiel(LivrablePartiel $livrablePartiel): self
    {
        if ($this->livrablePartiels->removeElement($livrablePartiel)) {
            // set the owning side to null (unless already changed)
            if ($livrablePartiel->getBirefmapromo() === $this) {
                $livrablePartiel->setBirefmapromo(null);
            }
        }

        return $this;
    }

    public function getBriefApprenant(): ?BriefApprenant
    {
        return $this->briefApprenant;
    }

    public function setBriefApprenant(?BriefApprenant $briefApprenant): self
    {
        $this->briefApprenant = $briefApprenant;

        return $this;
    }
}
