<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\ApprenantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ApprenantRepository::class)
 * @ApiResource (
 *     attributes={
 *          "security"="is_granted('ROLE_ADMIN')",
 *          "security_message"="Vous n'avez pas access à cette Ressource"
 *      },
 *     normalizationContext={"groups"={"apprenant:read"}},
 *     collectionOperations={
 *                 "get"={
 *                          "method"="GET",
 *                          "path"="/apprenants",
 *                          "security"="is_granted('ROLE_CM')",
 *          "security_message"="Vous n'avez pas access à cette operation"
 *     },
 *                 "add_apprenant"={
 *                          "method"="POST",
 *                             "route_name"="apprenant_add",
 *     "security"="is_granted('ROLE_ADMIN')",
 *          "security_message"="Vous n'avez pas access à cette operation"
 *              }
 *     },
 *     itemOperations={
 *              "get"={"method"="GET",
 *                          "path"="/apprenants/{id}",
 *                          "security"="is_granted('ROLE_CM') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN') or is_granted('ROLE_APPRENANT')",
 *          "security_message"="Vous n'avez pas access à cette operation"},
 *                  "update_apprenant"={
 *                      "route_name"="apprenant_update",
 *     "security"="is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN') or is_granted('ROLE_APPRENANT')",
 *          "security_message"="Vous n'avez pas access à cette operation",
 *                      "method"="POST",
 *                      "route_name"="apprenant_update"
 *                },"assigne"={"method"="PUT",
 *                          "route_name"="assigne",
 *                          "security"="is_granted('ROLE_FORMATEUR')",
 *          "security_message"="Vous n'avez pas access à cette operation"}
 *          ,"livpartiel"={"method"="PUT",
 *                          "route_name"="livpartiel",
 *                          "security"="is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN')",
 *          "security_message"="Vous n'avez pas access à cette operation"}
 *     }
 * )
 */
class Apprenant extends User
{

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups ({"getalluser:read","apprenant:read","groupe:write","user:read","useritem:read"})
     */
    private $statut;

    /**
     * @ORM\ManyToOne(targetEntity=ProfilSortie::class, inversedBy="apprenants")
     * @Groups ({"getalluser:read","apprenant:read","user:read","useritem:read"})
     */
    private $metier;

    /**
     * @ORM\ManyToMany(targetEntity=Groupe::class, inversedBy="apprenants")
     */
    private $groupe;

    /**
     * @ORM\Column(name="isConnected", type="boolean", options={"default":false})
     * @Groups ({"attenteOne:read"})
     */
    private $isConnected = false;

    /**
     * @ORM\ManyToOne(targetEntity=ApprenantLivrablePartiel::class, inversedBy="apprenant")
     */
    private $apprenantLivrablePartiel;

    /**
     * @ORM\ManyToOne(targetEntity=BriefApprenant::class, inversedBy="apprenant")
     */
    private $briefApprenant;

    /**
     * @ORM\OneToMany(targetEntity=CompetenceValidee::class, mappedBy="apprenant")
     */
    private $competenceValidees;

    /**
     * @ORM\ManyToOne(targetEntity=LivrableAttenduApprenant::class, inversedBy="apprenant")
     */
    private $livrableAttenduApprenant;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->setNom("APPRENANT");
        $this->setGenre("M");
        $this->setTelephone("777777777");
        $this->setPrenom("Apprenant");
        $this->setAdresse("apprenant");
        $this->setLogin("apprenant".rand(1,10000));
        $this->setPassword($encoder->encodePassword($this,"pass_1234"));
        $this->groupe = new ArrayCollection();
        $this->competenceValidees = new ArrayCollection();
    }


    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getMetier(): ?ProfilSortie
    {
        return $this->metier;
    }

    public function setMetier(?ProfilSortie $metier): self
    {
        $this->metier = $metier;

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
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        $this->groupe->removeElement($groupe);

        return $this;
    }

    public function getIsConnected(): ?bool
    {
        return $this->isConnected;
    }

    public function setIsConnected(bool $isConnected): self
    {
        $this->isConnected = $isConnected;

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

    public function getBriefApprenant(): ?BriefApprenant
    {
        return $this->briefApprenant;
    }

    public function setBriefApprenant(?BriefApprenant $briefApprenant): self
    {
        $this->briefApprenant = $briefApprenant;

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
            $competenceValidee->setApprenant($this);
        }

        return $this;
    }

    public function removeCompetenceValidee(CompetenceValidee $competenceValidee): self
    {
        if ($this->competenceValidees->removeElement($competenceValidee)) {
            // set the owning side to null (unless already changed)
            if ($competenceValidee->getApprenant() === $this) {
                $competenceValidee->setApprenant(null);
            }
        }

        return $this;
    }

    public function getLivrableAttenduApprenant(): ?LivrableAttenduApprenant
    {
        return $this->livrableAttenduApprenant;
    }

    public function setLivrableAttenduApprenant(?LivrableAttenduApprenant $livrableAttenduApprenant): self
    {
        $this->livrableAttenduApprenant = $livrableAttenduApprenant;

        return $this;
    }

}
