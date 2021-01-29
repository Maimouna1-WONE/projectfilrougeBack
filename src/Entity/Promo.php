<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PromoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PromoRepository::class)
 * @ApiResource (
 *     attributes={
 *          "security"="is_granted('ROLE_ADMIN')",
 *          "security_message"="Vous n'avez pas access à cette Ressource"
 *      },
 *     collectionOperations={
 *              "get"={"method"="GET",
 *                      "path"="/admin/promos",
 *     "security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM') or is_granted('ROLE_APPRENANT')",
 *          "security_message"="Vous n'avez pas access à cette operation",
 *     "normalization_context"={"groups"={"promo:read"}}
 *     },
 *            "add_promo"={
 *                      "method"="POST",
 *                      "route_name"="promo_add",
 *     "denormalization_context"={"groups"={"promo:write"}}
 *                   },
 *                  "getbriefpro"={
 *                      "method"="GET",
 *                      "path"="/formateurs/promos/{$id}/briefs/{id1}",
 *     "security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR')",
 *          "security_message"="Vous n'avez pas access à cette operation",
 *     "normalization_context"={"groups"={"briprom:read"}}
 *     },
 *                  "getprincipal"={
 *                      "method"="GET",
 *                      "route_name"="getprincipal"
 *                      },
 *                  "getbriefpromo"={
 *                      "method"="GET",
 *                      "path"="/formateurs/promos/{id}/groupes/{id1}/briefs",
 *     "security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR')",
 *          "security_message"="Vous n'avez pas access à cette operation",
 *     "normalization_context"={"groups"={"getbpromo:read"}}
 *                     },"bripro"={
 *                      "method"="GET",
 *                      "path"="/formateurs/promos/{id}/briefs",
 *     "security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR')",
 *          "security_message"="Vous n'avez pas access à cette operation",
 *     "normalization_context"={"groups"={"bripro:read"}}
 *                  },
 *                 "attente"={
 *                      "method"="GET",
 *                      "route_name"="attente"
 *     }
 *     },
 *     itemOperations={
 *              "get"={"method"="GET",
 *                      "path"="/admin/promos/{id}",
 *     "security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM')",
 *          "security_message"="Vous n'avez pas access à cette operation"},
 *     "getform"={"method"="GET",
 *                      "path"="/admin/promos/{id}/formateurs",
 *     "normalization_context"={"groups"={"getform:read"}}},
 *              "put"={"method"="PUT",
 *                      "path"="/admin/promos/{id}/formateurs"},
 *                  "putgroupe"=
 *                      {"method"="PUT",
 *                      "path"="/admin/promos/{id}/apprenants"},
 *     "getRef"={"method"="GET",
 *                      "path"="/admin/promos/{id}/referentiels",
 *                  "normalization_context"={"groups"={"getRef:read"}}},
 *     "putpromoref"={"method"="PUT",
 *                      "path"="/admin/promos/{id}/referentiels"},
 *     "getAppGroupepromo"={
 *              "method"="GET",
 *               "path"="/admin/promos/{id}/groupes/{id1}/apprenants"},
 *     "getcompref"={
 *              "method"="GET",
 *              "path"="/formateurs/promos/{id}/referentiels/{id1}/competences",
 *              "normalization_context"={"groups"={"compref:read"}}},
 *     "deletePromo"={"method"="DELETE",
 *                      "path"="/admin/promos/{id}"},
 *                  "attenteOne"={
 *                      "method"="GET",
 *                      "route_name"="attenteOne"},
 *              "getprincipalOne"={
 *                      "method"="GET",
 *                      "route_name"="getprincipalOne"}
 *     }
 * )
 * @ApiFilter(BooleanFilter::class, properties={"archive"})
 */
class Promo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"promo:write", "promo:read","principal:read","getform:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Le libelle ne peut etre vide")
     * @Groups ({"promo:write","promo:read","compref:read","getbpromo:read","bripro:read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"promo:write","promo:read"})
     */
    private $lieu;


    /**
     * @ORM\Column(type="string", length=255, options={"default":"Sonatel Academy"})
     */
    private $fabrique;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * @Groups ({"promo:read"})
     */
    private $avatar;

    /**
     * @ORM\Column(type="string", length=255, options={"default":"francais"})
     * @Groups ({"promo:write","promo:read"})
     */
    private $langue;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"promo:write","promo:read"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups ({"promo:write","promo:read"})
     */
    private $referenceAgate;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups ({"promo:write","promo:read"})
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups ({"promo:write","promo:read"})
     */
    private $dateFin;

    /**
     * @ORM\Column(name="archive", type="boolean", options={"default":false})
     */
    private $archive= false;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, mappedBy="promo")
     * @ApiSubresource ()
     * @Groups ({"promo:write","getform:read","promo:read","formateur:read"})
     */
    private $formateurs;

    /**
     * @ORM\OneToMany(targetEntity=Groupe::class, mappedBy="promotion")
     * @ApiSubresource ()
     * @Groups ({"promo:write","promo:read","groupe:read","getbpromo:read","principal:read","attenteOne:read"})
     */
    private $groupes;


    /**
     * @ORM\ManyToOne(targetEntity=Referentiel::class, inversedBy="promos")
     * @ApiSubresource ()
     * @Groups ({"getRef:read"})
     * @Groups ({"promo:read","referentiel:read","compref:read","getbpromo:read","principal:read","attenteOne:read"})
     */
    private $referentiel;

    /**
     * @ORM\OneToMany(targetEntity=BriefMaPromo::class, mappedBy="promo")
     * @ApiSubresource ()
     * @Groups ({"briprom:read","bripro:read"})
     */
    private $briefMaPromos;

    /**
     * @ORM\OneToMany(targetEntity=Chat::class, mappedBy="promo")
     */
    private $chats;


    public function __construct()
    {
        $this->formateurs = new ArrayCollection();
        $this->groupes = new ArrayCollection();
        $this->briefMaPromos = new ArrayCollection();
        $this->chats = new ArrayCollection();
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

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(?string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getFabrique(): ?string
    {
        return $this->fabrique;
    }

    public function setFabrique(string $fabrique): self
    {
        $this->fabrique = $fabrique;

        return $this;
    }

    public function getAvatar()
    {
        if($this->avatar)
        {
            $avatar_str= stream_get_contents($this->avatar);
            return base64_encode($avatar_str);
        }
        return null;
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getReferenceAgate(): ?string
    {
        return $this->referenceAgate;
    }

    public function setReferenceAgate(string $referenceagate): self
    {
        $this->referenceAgate = $referenceagate;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getArchive(): ?string
    {
        return $this->archive;
    }

    public function setArchive(string $archive): self
    {
        $this->archive = $archive;

        return $this;
    }

    /**
     * @return Collection|Formateur[]
     */
    public function getFormateurs(): Collection
    {
        return $this->formateurs;
    }

    public function addFormateur(Formateur $formateur): self
    {
        if (!$this->formateurs->contains($formateur)) {
            $this->formateurs[] = $formateur;
            $formateur->addPromo($this);
        }

        return $this;
    }

    public function removeFormateur(Formateur $formateur): self
    {
        if ($this->formateurs->removeElement($formateur)) {
            $formateur->removePromo($this);
        }

        return $this;
    }

    /**
     * @return Collection|Groupe[]
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes[] = $groupe;
            $groupe->setPromotion($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            // set the owning side to null (unless already changed)
            if ($groupe->getPromotion() === $this) {
                $groupe->setPromotion(null);
            }
        }

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
            $briefMaPromo->setPromo($this);
        }

        return $this;
    }

    public function removeBriefMaPromo(BriefMaPromo $briefMaPromo): self
    {
        if ($this->briefMaPromos->removeElement($briefMaPromo)) {
            // set the owning side to null (unless already changed)
            if ($briefMaPromo->getPromo() === $this) {
                $briefMaPromo->setPromo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Chat[]
     */
    public function getChats(): Collection
    {
        return $this->chats;
    }

    public function addChat(Chat $chat): self
    {
        if (!$this->chats->contains($chat)) {
            $this->chats[] = $chat;
            $chat->setPromo($this);
        }

        return $this;
    }

    public function removeChat(Chat $chat): self
    {
        if ($this->chats->removeElement($chat)) {
            // set the owning side to null (unless already changed)
            if ($chat->getPromo() === $this) {
                $chat->setPromo(null);
            }
        }

        return $this;
    }

}
