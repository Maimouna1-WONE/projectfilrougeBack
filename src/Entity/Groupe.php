<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GroupeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=GroupeRepository::class)
 * @ApiResource (
 *     routePrefix="/admin/groupes",
 *     normalizationContext={"groups"={"groupe:read"}},
 *     denormalizationContext={"groups"={"groupe:write"}},
 *     attributes={
 *          "security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR')",
 *          "security_message"="Vous n'avez pas access à cette Ressource"
 *      },
 *     collectionOperations={
 *              "getall"={"method"="GET",
 *                      "path"=""},
 *     "get"={"method"="GET",
 *                      "path"="/apprenants"},
 *              "post"={"method"="POST",
 *                      "path"=""},
 *     },
 *     itemOperations={
 *              "get"={"method"="GET",
 *                      "path"="/{id}"},
 *              "put"={"method"="PUT",
 *                      "path"="/{id}"},
 *               "delete"={"method"="DELETE",
 *                      "route_name"="delete"}
 *     }
 * )
 * @ApiFilter(BooleanFilter::class, properties={"archive"})
 */
class Groupe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"promo:write","groupe:write","promo:read","principal:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups ({"groupe:read","groupe:write","promo:read","getbpromo:read","promo:write","principal:read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups ({"groupe:read","promo:write","groupe:write"})
     */
    private $periode;

    /**
     * @ORM\ManyToOne(targetEntity=Promo::class, inversedBy="groupes")
     */
    private $promotion;

    /**
     * @ORM\ManyToMany(targetEntity=Apprenant::class, mappedBy="groupe")
     * @ApiSubresource ()
     * @Groups ({"groupe:read","apprenant:read","promo:read","getbpromo:read","promo:write","groupe:write","principal:read","attenteOne:read"})
     */
    private $apprenants;

    /**
     * @ORM\Column(name="archive", type="boolean", options={"default":false})
     */
    private $archive= false;

    /**
     * @ORM\ManyToOne(targetEntity=BriefMonGroupe::class, inversedBy="groupe")
     * @ApiSubresource ()
     * @Groups ({"getbpromo:read"})
     */
    private $briefMonGroupe;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, inversedBy="groupes")
     * @Groups ({"groupe:read","promo:write","groupe:write","principal:read"})
     */
    private $formateur;

    /**
     * @ORM\Column(type="string", length=255, options={"default": "principal"})
     * @Assert\NotBlank(message = "Donner le type du groupe")
     * @Assert\Regex(
     *     pattern="/principal|secondaire/",
     *     message="preciser le type du groupe"
     * )
     * @Groups ({"groupe:read","promo:write","groupe:write"})
     */
    private $type = "principal";


    public function __construct()
    {
        $this->apprenants = new ArrayCollection();
        $this->formateur = new ArrayCollection();
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

    public function getPeriode(): ?string
    {
        return $this->periode;
    }

    public function setPeriode(string $periode): self
    {
        $this->periode = $periode;

        return $this;
    }

    public function getPromotion(): ?Promo
    {
        return $this->promotion;
    }

    public function setPromotion(?Promo $promotion): self
    {
        $this->promotion = $promotion;

        return $this;
    }

    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenants(): Collection
    {
        return $this->apprenants;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenants->contains($apprenant)) {
            $this->apprenants[] = $apprenant;
            $apprenant->addGroupe($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenants->removeElement($apprenant)) {
            $apprenant->removeGroupe($this);
        }

        return $this;
    }

    public function getArchive(): ?bool
    {
        return $this->archive;
    }

    public function setArchive(bool $archive): self
    {
        $this->archive = $archive;

        return $this;
    }

    public function getBriefMonGroupe(): ?BriefMonGroupe
    {
        return $this->briefMonGroupe;
    }

    public function setBriefMonGroupe(?BriefMonGroupe $briefMonGroupe): self
    {
        $this->briefMonGroupe = $briefMonGroupe;

        return $this;
    }

    /**
     * @return Collection|Formateur[]
     */
    public function getFormateur(): Collection
    {
        return $this->formateur;
    }

    public function addFormateur(Formateur $formateur): self
    {
        if (!$this->formateur->contains($formateur)) {
            $this->formateur[] = $formateur;
        }

        return $this;
    }

    public function removeFormateur(Formateur $formateur): self
    {
        $this->formateur->removeElement($formateur);

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

}
