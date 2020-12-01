<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\LivrablePartielRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=LivrablePartielRepository::class)
 * @ApiResource (
 *     collectionOperations={
 *          "getall"={
 *              "method"="GET",
 *              "path"="/formateurs/promo/{id}/referentiel/{id1}/competences",
 *              "normalization_context"={"groups"={"partielget:read"}}
 *     }
 *     }
 * )
 */
class LivrablePartiel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"partielget:read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $delai;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbrrendu;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbrcorrige;

    /**
     * @ORM\ManyToOne(targetEntity=BriefMaPromo::class, inversedBy="livrablePartiels")
     */
    private $birefmapromo;

    /**
     * @ORM\ManyToMany(targetEntity=Niveau::class, inversedBy="livrablePartiels")
     */
    private $niveau;

    /**
     * @ORM\ManyToOne(targetEntity=ApprenantLivrablePartiel::class, inversedBy="livrablepartiel")
     */
    private $apprenantLivrablePartiel;

    public function __construct()
    {
        $this->niveau = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getNbrrendu(): ?int
    {
        return $this->nbrrendu;
    }

    public function setNbrrendu(int $nbrrendu): self
    {
        $this->nbrrendu = $nbrrendu;

        return $this;
    }

    public function getNbrcorrige(): ?int
    {
        return $this->nbrcorrige;
    }

    public function setNbrcorrige(int $nbrcorrige): self
    {
        $this->nbrcorrige = $nbrcorrige;

        return $this;
    }

    public function getBirefmapromo(): ?BriefMaPromo
    {
        return $this->birefmapromo;
    }

    public function setBirefmapromo(?BriefMaPromo $birefmapromo): self
    {
        $this->birefmapromo = $birefmapromo;

        return $this;
    }

    /**
     * @return Collection|Niveau[]
     */
    public function getNiveau(): Collection
    {
        return $this->niveau;
    }

    public function addNiveau(Niveau $niveau): self
    {
        if (!$this->niveau->contains($niveau)) {
            $this->niveau[] = $niveau;
        }

        return $this;
    }

    public function removeNiveau(Niveau $niveau): self
    {
        $this->niveau->removeElement($niveau);

        return $this;
    }

    public function getApprenantLivrablePartiel(): ?ApprenantLivrablePartiel
    {
        return $this->apprenantLivrablePartiel;
    }

    public function setApprenantLivrablePartiel(?ApprenantLivrablePartiel $apprenantLivrablePartiel): self
    {
        $this->apprenantLivrablePartiel = $apprenantLivrablePartiel;

        return $this;
    }
}
