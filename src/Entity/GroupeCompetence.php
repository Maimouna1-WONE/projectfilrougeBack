<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\GroupeCompetenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=GroupeCompetenceRepository::class)
 * @ApiResource (
 *     routePrefix="/admin/groupecompetences",
 *     normalizationContext={"groups"={"groupecompetence:read"}},
 *     denormalizationContext={"groups"={"groupecompetence:write"}},
 *     attributes={
 *          "security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR')",
 *          "security_message"="Vous n'avez pas access à cette Ressource"
 *      },
 *     collectionOperations={
 *              "getall"={"method"="GET",
 *                      "path"=""},
 *              "getcompe"={"method"="GET",
 *                      "path"="/competences"},
 *              "postgrp"={"method"="POST",
 *                      "route_name"="postgrp"},
 *     },
 *     itemOperations={
 *              "get"={"method"="GET",
 *                      "path"="/{id}"},
 *              "getcom"={"method"="GET",
 *                      "path"="/{id}/competences"},
 *              "update"={"method"="PUT",
 *                      "path"="/{id}"},
 *     "delete"={"method"="DELETE",
 *                      "path"="/{id}"}
 *     }
 * )
 * @ApiFilter(BooleanFilter::class, properties={"archive"})
 */
class GroupeCompetence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"getRef:read","groupecompetence:write","groupecompetence:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"getRef:read","refgrp:read","groupecompetence:read","ref:read","referentiel:read","promo:read","getref:read","compref:read","groupecompetence:write"})
     * @Assert\NotBlank(message = "Le libelle est obligatoire")
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups ({"getRef:read","refgrp:read","groupecompetence:read","ref:read","referentiel:read","promo:read","getref:read","groupecompetence:write"})
     */
    private $description;

    /**
     * @ORM\Column(name="archive", type="boolean", options={"default":false})
     */
    private $archive = false;

    /**
     * @ORM\ManyToMany(targetEntity=Competence::class, inversedBy="groupeCompetences")
     * @ApiSubresource ()
     * @Groups ({"refgrp:read","groupecompetence:read","competence:read","referentiel:read","promo:read","getref:read","ref:read","compref:read","groupecompetence:write"})
     * @Assert\NotBlank(message = "Donner au moins une competence")
    */
    private $competence;

    /**
     * @ORM\ManyToMany(targetEntity=Referentiel::class, mappedBy="groupeCompetence")
     */
    private $referentiels;

    public function __construct()
    {
        $this->competence = new ArrayCollection();
        $this->referentiels = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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
     * @return Collection|Competence[]
     */
    public function getCompetence(): Collection
    {
        return $this->competence;
    }

    public function addCompetence(Competence $competence): self
    {
        if (!$this->competence->contains($competence)) {
            $this->competence[] = $competence;
        }

        return $this;
    }

    public function removeCompetence(Competence $competence): self
    {
        $this->competence->removeElement($competence);

        return $this;
    }

    /**
     * @return Collection|Referentiel[]
     */
    public function getReferentiels(): Collection
    {
        return $this->referentiels;
    }

    public function addReferentiel(Referentiel $referentiel): self
    {
        if (!$this->referentiels->contains($referentiel)) {
            $this->referentiels[] = $referentiel;
            $referentiel->addGroupeCompetence($this);
        }

        return $this;
    }

    public function removeReferentiel(Referentiel $referentiel): self
    {
        if ($this->referentiels->removeElement($referentiel)) {
            $referentiel->removeGroupeCompetence($this);
        }

        return $this;
    }
}
