<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\BriefRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BriefRepository::class)
 * @ApiResource (
 *
 *     collectionOperations={
 *     "getbriefalll"={
 *              "method"="GET",
 *               "path"="/formateurs/briefs",
 *                "security"="is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN')",
 *          "security_message"="Vous n'avez pas access à cette operation",
 *     "normalization_context"={"groups"={"briefget:read"}}
 *     },
 *      "postbrief"={
 *              "method"="POST",
 *               "path"="/formateurs/briefs",
 *                 "route_name"="postbrief",
 *                "security"="is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN')",
 *          "security_message"="Vous n'avez pas access à cette operation",
 *          "denormalization_context"={"groups"={"postbrief:write"}}
 *     },
 *     "duplique"={
 *                   "method"="POST",
 *                      "route_name"="duplique",
 *     "security"="is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN')",
 *          "security_message"="Vous n'avez pas access à cette operation"
 *                   },
 *     "brprgr"={
 *             "method"="GET",
 *             "route_name"="brprgr",
 *             "security"="is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN')",
 *             "security_message"="Vous n'avez pas access à cette operation",
 *             "normalization_context"={"groups"={"prgrpbr:read"}}
 *              },
 *     "brouillon"={
 *             "method"="GET",
 *             "route_name"="brouillon",
 *             "security"="is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN')",
 *             "security_message"="Vous n'avez pas access à cette operation"
 *              },
 *     "valide"={
 *             "method"="GET",
 *             "route_name"="valide",
 *             "security"="is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN')",
 *             "security_message"="Vous n'avez pas access à cette operation"
 *              },
 *     "brpm"={"method"="GET",
 *              "route_name"="brpm",
 *     "normalization_context"={"groups"={"brpm:read"}}
 *                }
 *     },
 *     itemOperations={
 *               "deletebr"={
 *                      "method"="DELETE",
 *                      "path"="/formateurs/promo/{id}/brief/{id1}"
 *                   },
 *              "putbr"={
 *                      "method"="PUT",
 *                      "path"="/formateurs/promo/{id}/briefs/{id1}"
 *                   },"assigne"={
 *                      "method"="PUT",
 *                      "route_name"="assigne"
 *                   },
 *     "getone"={
 *                "method"="GET",
 *                "path"="/formateurs/{id}/promo/{id1}/briefs/{id2}",
 *              "security"="is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN')",
 *             "security_message"="Vous n'avez pas access à cette operation",
 *             "normalization_context"={"groups"={"getonebrief:read"}}
 *                   }
 *     }
 * )
 */
class Brief
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"brpm:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"postbrief:write","briefget:read","getbpromo:read","bripro:read","prgrpbr:read","getonebrief:read"})
     * @Assert\NotBlank(message = "Donner la langue")
     */
    private $langue;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"postbrief:write","briefget:read","getbpromo:read","bripro:read"})
     * @Assert\NotBlank(message = "Donner le titre")
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"postbrief:write","briefget:read","getbpromo:read","bripro:read"})
     * @Assert\NotBlank(message = "Donner une description")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"postbrief:write","briefget:read","getbpromo:read"})
     * @Assert\NotBlank(message = "Donner le contexte")
     */
    private $contexte;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups ({"postbrief:write","briefget:read","getbpromo:read","bripro:read"})
     * @Assert\NotBlank(message = "Donner la modalité pedagogique")
     */
    private $modalitePedagogique;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups ({"postbrief:write","briefget:read","getbpromo:read","bripro:read"})
     * @Assert\NotBlank(message = "Donner une modalité d'evaluation")
     */
    private $modaliteEvaluation;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"postbrief:write","briefget:read","getbpromo:read","bripro:read"})
     * @Assert\NotBlank(message = "Donner une critere de performance")
     */
    private $CriterePerformance;

    /**
     * @ORM\Column(type="blob",nullable=true)
     * @Groups ({"postbrief:write"})
     */
    private $avatar;

    /**
     * @ORM\Column(type="date")
     * @Groups ({"briefget:read","bripro:read"})
     */
    private $dateCreation;

    /**
     * @ORM\ManyToOne(targetEntity=Formateur::class, inversedBy="briefs")
     * @ApiSubresource ()
     * @Groups ({"getbpromo:read"})
     */
    private $formateur;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="briefs")
     * @Groups ({"briefget:read","getbpromo:read"})
     */
    private $tag;

    /**
     * @ORM\OneToMany(targetEntity=BriefMaPromo::class, mappedBy="brief")
     */
    private $briefMaPromos;

    /**
     * @ORM\OneToMany(targetEntity=Ressource::class, mappedBy="brief")
     * @Groups ({"briefget:read","getbpromo:read"})
     */
    private $ressource;

    /**
     * @ORM\ManyToOne(targetEntity=BriefMonGroupe::class, inversedBy="brief")
     */
    private $briefMonGroupe;

    /**
     * @ORM\ManyToMany(targetEntity=Niveau::class, inversedBy="briefs")
     * @Groups ({"briefget:read","getbpromo:read"})
     */
    private $niveau;

    /**
     * @ORM\ManyToMany(targetEntity=LivrableAttendu::class, inversedBy="briefs")
     * @Groups ({"briefget:read","getbpromo:read"})
     */
    private $livrableattendu;

    /**
     * @ORM\Column(name="archive", type="boolean", options={"default":false})
     */
    private $archive=false;

    /**
     * @ORM\Column(type="string", length=255, name="statut")
     * @Assert\NotBlank(message = "Donner le statut")
     * @Assert\Regex(
     *     pattern="/brouillon|assigne|valide/",
     *     message="soit le brief est assigne, soit c'est valide, soit c'est au brouillon"
     * )
     */
    private $statut;


    public function __construct()
    {
        $this->tag = new ArrayCollection();
        $this->briefMaPromos = new ArrayCollection();
        $this->ressource = new ArrayCollection();
        $this->niveau = new ArrayCollection();
        $this->livrableattendu = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): self
    {
        $this->langue = $langue;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

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

    public function getContexte(): ?string
    {
        return $this->contexte;
    }

    public function setContexte(string $contexte): self
    {
        $this->contexte = $contexte;

        return $this;
    }

    public function getModalitePedagogique(): ?string
    {
        return $this->modalitePedagogique;
    }

    public function setModalitePedagogique(string $modalitePedagogique): self
    {
        $this->modalitePedagogique = $modalitePedagogique;

        return $this;
    }

    public function getModaliteEvaluation(): ?string
    {
        return $this->modaliteEvaluation;
    }

    public function setModaliteEvaluation(string $modaliteEvaluation): self
    {
        $this->modaliteEvaluation = $modaliteEvaluation;

        return $this;
    }

    public function getCriterePerformance(): ?string
    {
        return $this->CriterePerformance;
    }

    public function setCriterePerformance(string $CriterePerformance): self
    {
        $this->CriterePerformance = $CriterePerformance;

        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getFormateur(): ?Formateur
    {
        return $this->formateur;
    }

    public function setFormateur(?Formateur $formateur): self
    {
        $this->formateur = $formateur;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTag(): Collection
    {
        return $this->tag;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tag->contains($tag)) {
            $this->tag[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tag->removeElement($tag);

        return $this;
    }

    /**
     * @return Collection|BriefMaPromo[]
     */
    public function getBriefMaPromos(): Collection
    {
        return $this->briefMaPromos;
    }

    public function addBriefMaPromo(BriefMaPromo $briefMaPromo): self
    {
        if (!$this->briefMaPromos->contains($briefMaPromo)) {
            $this->briefMaPromos[] = $briefMaPromo;
            $briefMaPromo->setBrief($this);
        }

        return $this;
    }

    public function removeBriefMaPromo(BriefMaPromo $briefMaPromo): self
    {
        if ($this->briefMaPromos->removeElement($briefMaPromo)) {
            // set the owning side to null (unless already changed)
            if ($briefMaPromo->getBrief() === $this) {
                $briefMaPromo->setBrief(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Ressource[]
     */
    public function getRessource(): Collection
    {
        return $this->ressource;
    }

    public function addRessource(Ressource $ressource): self
    {
        if (!$this->ressource->contains($ressource)) {
            $this->ressource[] = $ressource;
            $ressource->setBrief($this);
        }

        return $this;
    }

    public function removeRessource(Ressource $ressource): self
    {
        if ($this->ressource->removeElement($ressource)) {
            // set the owning side to null (unless already changed)
            if ($ressource->getBrief() === $this) {
                $ressource->setBrief(null);
            }
        }

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

    /**
     * @return Collection|LivrableAttendu[]
     */
    public function getLivrableattendu(): Collection
    {
        return $this->livrableattendu;
    }

    public function addLivrableattendu(LivrableAttendu $livrableattendu): self
    {
        if (!$this->livrableattendu->contains($livrableattendu)) {
            $this->livrableattendu[] = $livrableattendu;
        }

        return $this;
    }

    public function removeLivrableattendu(LivrableAttendu $livrableattendu): self
    {
        $this->livrableattendu->removeElement($livrableattendu);

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

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

}
