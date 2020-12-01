<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\FormateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=FormateurRepository::class)
 * @ApiResource (
 *      attributes={
 *          "security"="is_granted('ROLE_FORMATEUR')",
 *          "security_message"="Vous n'avez pas access à cette Ressource"
 *      },
 *     normalizationContext={"groups"={"formateur:read"}},
 *     collectionOperations={
 *                 "add_formateur"={
 *                          "method"="POST",
 *                             "route_name"="formateur_add",
 *     "security"="is_granted('ROLE_CM') or is_granted('ROLE_ADMIN')",
 *          "security_message"="Vous n'avez pas access à cette operation"
 *              },"getformbrief"={
 *                          "method"="GET",
 *                             "path"="/formateurs/{id}/briefs/brouillon",
 *     "security"="is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN')",
 *          "security_message"="Vous n'avez pas access à cette operation",
 *     "normalization_context"={"groups"={"getbrou:read"}}
 *              },"valide"={
 *                          "method"="GET",
 *                             "path"="/formateurs/{id}/briefs/valide",
 *     "security"="is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN')",
 *          "security_message"="Vous n'avez pas access à cette operation",
 *     "normalization_context"={"groups"={"briefval:read"}}
 *              }
 *     },
 *     itemOperations={
 *              "get"={"method"="GET",
 *                          "path"="/formateurs/{id}",
 *                          "security"="is_granted('ROLE_CM') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN')",
 *          "security_message"="Vous n'avez pas access à cette operation"},
 *     "update_formateur"={
 *                      "route_name"="formateur_update",
 *     "security"="is_granted('ROLE_FORMATEUR') or is_granted('ROLE_ADMIN')",
 *          "security_message"="Vous n'avez pas access à cette operation",
 *                      "method"="POST",
 *                      "route_name"="formateur_update"
 *                }
 *          }
 * )
 */
class Formateur extends  User
{
    /**
     * @ORM\ManyToMany(targetEntity=Promo::class, inversedBy="formateurs")
     */
    private $promo;

    /**
     * @ORM\OneToMany(targetEntity=Brief::class, mappedBy="formateur")
     * @ApiSubresource ()
     * @Groups ({"getbrou:read"})
     */
    private $briefs;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="formateur")
     */
    private $commentaires;

    /**
     * @ORM\ManyToMany(targetEntity=Groupe::class, mappedBy="formateur")
     */
    private $groupes;

    public function __construct()
    {
        $this->promo = new ArrayCollection();
        $this->briefs = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->groupes = new ArrayCollection();
    }

    /**
     * @return Collection|Promo[]
     */
    public function getPromo(): Collection
    {
        return $this->promo;
    }

    public function addPromo(Promo $promo): self
    {
        if (!$this->promo->contains($promo)) {
            $this->promo[] = $promo;
        }

        return $this;
    }

    public function removePromo(Promo $promo): self
    {
        $this->promo->removeElement($promo);

        return $this;
    }

    /**
     * @return Collection|Brief[]
     */
    public function getBriefs(): Collection
    {
        return $this->briefs;
    }

    public function addBrief(Brief $brief): self
    {
        if (!$this->briefs->contains($brief)) {
            $this->briefs[] = $brief;
            $brief->setFormateur($this);
        }

        return $this;
    }

    public function removeBrief(Brief $brief): self
    {
        if ($this->briefs->removeElement($brief)) {
            // set the owning side to null (unless already changed)
            if ($brief->getFormateur() === $this) {
                $brief->setFormateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setFormateur($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getFormateur() === $this) {
                $commentaire->setFormateur(null);
            }
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
            $groupe->addFormateur($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            $groupe->removeFormateur($this);
        }

        return $this;
    }
}
