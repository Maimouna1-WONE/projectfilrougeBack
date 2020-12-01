<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use App\Repository\ProfilSortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProfilSortieRepository::class)
 * @ApiResource(
 *     routePrefix="/admin/profilssorties",
 *     normalizationContext={"groups"={"profilssortie:read"}},
 *     attributes={
 *          "security"="is_granted('ROLE_ADMIN')",
 *          "security_message"="Vous n'avez pas access à cette Ressource"
 *      },
 *     collectionOperations={
 *              "get"={"method"="GET",
 *                      "path"="",
 *     "security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM') or is_granted('ROLE_APPRENANT')",
 *          "security_message"="Vous n'avez pas access à cette operation"
 *     },
 *              "post"={"method"="POST",
 *                      "path"=""}
 *     },
 *     itemOperations={
 *              "get"={"method"="GET",
 *                      "path"="/{id}",
 *     "security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM')",
 *          "security_message"="Vous n'avez pas access à cette operation"},
 *              "put"={"method"="PUT",
 *                      "path"="/{id}"},
 *              "delete"={
 *                      "method"="DELETE",
 *                      "path"="/{id}"
 *     }
 *     }
 * )
 * @ApiFilter(BooleanFilter::class, properties={"archive"})
 */
class ProfilSortie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"profilssortie:read"})
     * @Assert\NotBlank(message = "Le libelle ne peut etre vide")
     */
    private $libelle;

    /**
     * @ORM\Column(name="archive", type="boolean", options={"default":false})
     */
    private $archive= false;

    /**
     * @ORM\OneToMany(targetEntity=Apprenant::class, mappedBy="metier")
     * @ApiSubresource ()
     * @Groups ({"profilssortie:read","apprenant:read"})
     */
    private $apprenants;

    public function __construct()
    {
        $this->apprenants = new ArrayCollection();
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
            $apprenant->setMetier($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenants->removeElement($apprenant)) {
            // set the owning side to null (unless already changed)
            if ($apprenant->getMetier() === $this) {
                $apprenant->setMetier(null);
            }
        }

        return $this;
    }
}
