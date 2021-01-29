<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use App\Repository\ReferentielRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReferentielRepository::class)
 * @ApiResource (
 *     routePrefix="/admin/referentiels",
 *     normalizationContext={"groups"={"referentiel:read"}},
 *     denormalizationContext={"groups"={"referentiel:write"}},
 *     attributes={
 *          "security"="is_granted('ROLE_ADMIN')",
 *          "security_message"="Vous n'avez pas access à cette Ressource"
 *      },
 *     collectionOperations={
 *              "getall"={"method"="GET",
 *                      "path"="",
 *                      "normalization_context"={"groups"={"getref:read"}}
 *              },"get"={"method"="GET",
 *                      "path"="/groupecompetences",
 *                      "normalization_context"={"groups"={"ref:read"}}
 *              },
 *              "add"={"method"="POST",
 *                      "route_name"="add"}
 *     },
 *     itemOperations={
 *              "get"={"method"="GET",
 *                      "path"="/{id}",
 *     "normalization_context"={"groups"={"getref:read"}}
 *     },
 *                  "getgrcomp"={"method"="GET",
 *                      "route_name"="refgp",
 *     "normalization_context"={"groups"={"refgrp:read"}}
 *             },
 *              "put"={"method"="PUT",
 *                      "path"="/{id}"},
 *     "delete"={"method"="DELETE",
 *                      "path"="/{id}"}
 *     }
 * )
 * @ApiFilter(BooleanFilter::class, properties={"archive"})
 */
class Referentiel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"getRef:read","referentiel:read","getref:read","promo:read","principal:read","attenteOne:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "le libelle est obligatoire")
     * @Groups({"getRef:read","refgrp:read","promo:read","referentiel:read","referentiel:write","promo:read","getref:read","compref:read","getbpromo:read","principal:read","attenteOne:read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "la presentation est obligatoire")
     * @Groups({"getRef:read","referentiel:read","referentiel:write","promo:read","getref:read"})
     */
    private $presentation;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeCompetence::class, inversedBy="referentiels", cascade={"persist"})
     * @ApiSubresource ()
     * @Groups ({"getRef:read","refgrp:read","ref:read","referentiel:read","promo:read","getref:read","compref:read","referentiel:write"})
     */
    private $groupeCompetence;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * @Groups ({"getref:read","referentiel:write"})
     */
    private $programme;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     * @Groups ({"getRef:read","referentiel:write","getref:read"})
     */
    private $critereEvaluation;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     * @Groups ({"getRef:read","referentiel:write","getref:read"})
     */
    private $critereAdmission;

    /**
     * @ORM\Column(name="archive", type="boolean", options={"default":false})
     */
    private $archive =false;

    /**
     * @ORM\OneToMany(targetEntity=Promo::class, mappedBy="referentiel")
     */
    private $promos;

    /**
     * @ORM\OneToMany(targetEntity=CompetenceValidee::class, mappedBy="referentiel")
     */
    private $competenceValidees;

    public function __construct()
    {
        $this->groupeCompetence = new ArrayCollection();
        $this->promos = new ArrayCollection();
        $this->competenceValidees = new ArrayCollection();
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

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    /**
     * @return Collection|GroupeCompetence[]
     */
    public function getGroupeCompetence(): Collection
    {
        return $this->groupeCompetence;
    }

    public function addGroupeCompetence(GroupeCompetence $groupeCompetence): self
    {
        if (!$this->groupeCompetence->contains($groupeCompetence)) {
            $this->groupeCompetence[] = $groupeCompetence;
        }

        return $this;
    }

    public function removeGroupeCompetence(GroupeCompetence $groupeCompetence): self
    {
        $this->groupeCompetence->removeElement($groupeCompetence);

        return $this;
    }

    public function getProgramme()
    {
        if($this->programme)
        {
            $programme_str= stream_get_contents($this->programme);
            return base64_encode($programme_str);
        }
        return null;
    }

    public function setProgramme($programme): self
    {
        $this->programme = $programme;

        return $this;
    }

    public function getCritereEvaluation(): ?string
    {
        return $this->critereEvaluation;
    }

    public function setCritereEvaluation(string $critereEvaluation): self
    {
        $this->critereEvaluation = $critereEvaluation;

        return $this;
    }

    public function getCritereAdmission(): ?string
    {
        return $this->critereAdmission;
    }

    public function setCritereAdmission(string $critereAdmission): self
    {
        $this->critereAdmission = $critereAdmission;

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

    /**
     * @return Collection|Promo[]
     */
    public function getPromos(): Collection
    {
        return $this->promos;
    }

    public function addPromo(Promo $promo): self
    {
        if (!$this->promos->contains($promo)) {
            $this->promos[] = $promo;
            $promo->setReferentiel($this);
        }

        return $this;
    }

    public function removePromo(Promo $promo): self
    {
        if ($this->promos->removeElement($promo)) {
            // set the owning side to null (unless already changed)
            if ($promo->getReferentiel() === $this) {
                $promo->setReferentiel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CompetenceValidee[]
     */
    public function getCompetenceValidees(): Collection
    {
        return $this->competenceValidees;
    }

    public function addCompetenceValidee(CompetenceValidee $competenceValidee): self
    {
        if (!$this->competenceValidees->contains($competenceValidee)) {
            $this->competenceValidees[] = $competenceValidee;
            $competenceValidee->setReferentiel($this);
        }

        return $this;
    }

    public function removeCompetenceValidee(CompetenceValidee $competenceValidee): self
    {
        if ($this->competenceValidees->removeElement($competenceValidee)) {
            // set the owning side to null (unless already changed)
            if ($competenceValidee->getReferentiel() === $this) {
                $competenceValidee->setReferentiel(null);
            }
        }

        return $this;
    }

}
