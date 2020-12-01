<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TagRepository::class)
 * @ApiResource(
 *     routePrefix="/admin/tags",
 *     normalizationContext={"groups"={"tag:read"}},
 *     attributes={
 *          "security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR')",
 *          "security_message"="Vous n'avez pas access à cette Ressource"
 *      },
 *     collectionOperations={
 *              "get"={"method"="GET",
 *                      "path"=""},
 *              "post"={"method"="POST",
 *                      "path"=""}
 *     },
 *     itemOperations={
 *              "get"={"method"="GET",
 *                      "path"="/{id}",
 *     "normalization_context"={"groups"={"gettag:read"}}},
 *              "update"={"method"="PUT",
 *                      "route_name"="update"},
 *                  "delete"={"method"="DELETE",
 *                          "path"="/{id}"}
 *     }
 * )
 * @ApiFilter(BooleanFilter::class, properties={"archive"})
 */
class Tag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Le libelle ne peut etre vide")
     * @Groups ({"tag:read","groupetag:read","getgroupetag:read","briefget:read","getbpromo:read"})
     */
    private $libelle;

    /**
     * @ORM\Column(name="archive", type="boolean", options={"default":false})
     */
    private $archive=false;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeTag::class, mappedBy="tag")
     * @ApiSubresource ()
     * @Groups ({"gettag:read","tag:read"})
     */
    private $groupeTags;

    /**
     * @ORM\ManyToMany(targetEntity=Brief::class, mappedBy="tag")
     */
    private $briefs;

    public function __construct()
    {
        $this->groupeTags = new ArrayCollection();
        $this->briefs = new ArrayCollection();
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
     * @return Collection|GroupeTag[]
     */
    public function getGroupeTags(): Collection
    {
        return $this->groupeTags;
    }

    public function addGroupeTag(GroupeTag $groupeTag): self
    {
        if (!$this->groupeTags->contains($groupeTag)) {
            $this->groupeTags[] = $groupeTag;
            $groupeTag->addTag($this);
        }

        return $this;
    }

    public function removeGroupeTag(GroupeTag $groupeTag): self
    {
        if ($this->groupeTags->removeElement($groupeTag)) {
            $groupeTag->removeTag($this);
        }

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
            $brief->addTag($this);
        }

        return $this;
    }

    public function removeBrief(Brief $brief): self
    {
        if ($this->briefs->removeElement($brief)) {
            $brief->removeTag($this);
        }

        return $this;
    }
}
