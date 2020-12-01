<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CompetenceValideeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CompetenceValideeRepository::class)
 * @ApiResource (
 *     collectionOperations={
 *              "comp"={
 *            "method"="GET",
 *             "path"="/apprenants/{id}/promos/{id1}/referentiels/{id2}/competences",
 *          "security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR')",
 *           "security_message"="Vous n'avez pas access à cette operation",
 *          "normalization_context"={"groups"={"apppro:read"}}
 *               }
 *     }
 * )
 */
class CompetenceValidee
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $niveau1;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $niveau2;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $niveau3;

    /**
     * @ORM\ManyToOne(targetEntity=Apprenant::class, inversedBy="competenceValidees")
     */
    private $apprenant;

    /**
     * @ORM\ManyToOne(targetEntity=Competence::class, inversedBy="competenceValidees")
     */
    private $competence;

    /**
     * @ORM\ManyToOne(targetEntity=Referentiel::class, inversedBy="competenceValidees")
     */
    private $referentiel;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNiveau1(): ?string
    {
        return $this->niveau1;
    }

    public function setNiveau1(string $niveau1): self
    {
        $this->niveau1 = $niveau1;

        return $this;
    }

    public function getNiveau2()
    {
        return $this->niveau2;
    }

    public function setNiveau2($niveau2): self
    {
        $this->niveau2 = $niveau2;

        return $this;
    }

    public function getNiveau3()
    {
        return $this->niveau3;
    }

    public function setNiveau3($niveau3): self
    {
        $this->niveau3 = $niveau3;

        return $this;
    }

    public function getApprenant(): ?Apprenant
    {
        return $this->apprenant;
    }

    public function setApprenant(?Apprenant $apprenant): self
    {
        $this->apprenant = $apprenant;

        return $this;
    }

    public function getCompetence(): ?Competence
    {
        return $this->competence;
    }

    public function setCompetence(?Competence $competence): self
    {
        $this->competence = $competence;

        return $this;
    }

    public function getReferentiel(): ?Referentiel
    {
        return $this->referentiel;
    }

    public function setReferentiel(?Referentiel $referentiel): self
    {
        $this->referentiel = $referentiel;

        return $this;
    }
}
