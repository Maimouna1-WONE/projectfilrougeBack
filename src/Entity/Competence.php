<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CompetenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CompetenceRepository::class)
 * @ApiResource(
 *     routePrefix="/admin/competences",
 *     normalizationContext={"groups"={"competence:read"}},
 *     denormalizationContext={"groups"={"competence:write"}},
 *     attributes={
 *          "security"="is_granted('ROLE_ADMIN')",
 *          "security_message"="Vous n'avez pas access à cette Ressource"
 *      },
 *     collectionOperations={
 *              "getall"={"method"="GET",
 *                      "path"="",
 *     "security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM') or is_granted('ROLE_APPRENANT')",
 *          "security_message"="Vous n'avez pas access à cette operation"
 *     },
 *              "postcmp"={"method"="POST",
 *                      "route_name"="postcmp",
 *              "denormalization_context"={"groups"={"addniv:write"}}
 * }
 *     },
 *     itemOperations={
 *              "get"={"method"="GET",
 *                      "path"="/{id}",
 *     "security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM')",
 *          "security_message"="Vous n'avez pas access à cette operation"},
 *              "put"={
 *                      "method"="PUT",
 *                      "path"="/{id}"},
 *              "delete"={"method"="DELETE",
 *                      "path"="/{id}"}
 *     }
 * )
 * @ApiFilter(BooleanFilter::class, properties={"archive"})
 */
class Competence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"competence:write","competence:read","groupecompetence:read","referentiel:read","promo:read","getref:read","ref:read","compref:read","addniv:write","groupecompetence:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "le libelle est obligatoire")
     * @Groups({"competence:write","competence:read","groupecompetence:read","referentiel:read","promo:read","getref:read","ref:read","compref:read","addniv:write","groupecompetence:write"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "met la description")
     * @Groups({"competence:write","competence:read","groupecompetence:read","referentiel:read","promo:read","getref:read","ref:read","addniv:write","groupecompetence:write"})
     */
    private $description;

    /**
     * @ORM\Column(name="archive", type="boolean", options={"default":false})
     */
    private $archive = false;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeCompetence::class, mappedBy="competence")
     * @Groups ({"addniv:write"})
     */
    private $groupeCompetences;

    /**
     * @ORM\OneToMany(targetEntity=Niveau::class, mappedBy="competence", cascade={"persist"})
     * @ApiSubresource ()
     * @Assert\Count(
     *      min = 3,
     *      max = 3,
     *      minMessage = "You must specify at least one niveau",
     *      maxMessage = "You cannot specify more than {{ limit }} niveaux"
     * )
     * @Groups ({"competence:write","competence:read","getref:read","addniv:write","groupecompetence:read"})
     */
    private $niveau;

    /**
     * @ORM\OneToMany(targetEntity=CompetenceValidee::class, mappedBy="competence")
     */
    private $competenceValidees;


    public function __construct()
    {
        $this->groupeCompetences = new ArrayCollection();
        $this->niveaux = new ArrayCollection();
        $this->niveau = new ArrayCollection();
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
     * @return Collection|GroupeCompetence[]
     */
    public function getGroupeCompetences(): Collection
    {
        return $this->groupeCompetences;
    }

    public function addGroupeCompetence(GroupeCompetence $groupeCompetence): self
    {
        if (!$this->groupeCompetences->contains($groupeCompetence)) {
            $this->groupeCompetences[] = $groupeCompetence;
            $groupeCompetence->addCompetence($this);
        }

        return $this;
    }

    public function removeGroupeCompetence(GroupeCompetence $groupeCompetence): self
    {
        if ($this->groupeCompetences->removeElement($groupeCompetence)) {
            $groupeCompetence->removeCompetence($this);
        }

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
            $niveau->setCompetence($this);
        }

        return $this;
    }

    public function removeNiveau(Niveau $niveau): self
    {
        if ($this->niveau->removeElement($niveau)) {
            // set the owning side to null (unless already changed)
            if ($niveau->getCompetence() === $this) {
                $niveau->setCompetence(null);
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
            $competenceValidee->setCompetence($this);
        }

        return $this;
    }

    public function removeCompetenceValidee(CompetenceValidee $competenceValidee): self
    {
        if ($this->competenceValidees->removeElement($competenceValidee)) {
            // set the owning side to null (unless already changed)
            if ($competenceValidee->getCompetence() === $this) {
                $competenceValidee->setCompetence(null);
            }
        }

        return $this;
    }


}
