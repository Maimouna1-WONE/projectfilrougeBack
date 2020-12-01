<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\GroupeTagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=GroupeTagRepository::class)
 * @ApiResource (
 *     routePrefix="/admin/groupetags",
 *     normalizationContext={"groups"={"groupetag:read"}},
 *     denormalizationContext={"groups"={"groupetag:write"}},
 *     attributes={
 *          "security"="is_granted('ROLE_ADMIN')",
 *          "security_message"="Vous n'avez pas access à cette Ressource"
 *      },
 *     collectionOperations={
 *              "get"={"method"="GET",
 *                      "path"="",
 *     "normalization_context"={"groups"={"getgroupetag:read"}}
 *     },
 *              "post"={"method"="POST",
 *                      "path"=""}
 *     },
 *     itemOperations={
 *              "get"={"method"="GET",
 *                      "path"="/{id}"},
 *              "gettag"={"method"="GET",
 *                      "path"="/{id}/tags"},
 *              "put"={"method"="PUT",
 *                      "path"="/{id}"},
 *              "delete"={"method"="DELETE",
 *                      "path"="/{id}"}
 *     }
 * )
 * @ApiFilter(BooleanFilter::class, properties={"archive"})
 */
class GroupeTag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"groupetag:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"groupetag:read","getgroupetag:read","tag:read","groupetag:write"})
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="groupeTags")
     * @ApiSubresource ()
     * @Groups ({"groupetag:read","getgroupetag:read","groupetag:write"})
     */
    private $tag;

    /**
     * @ORM\Column(name="archive", type="boolean", options={"default":false})
     */
    private $archive= false;

    public function __construct()
    {
        $this->tag = new ArrayCollection();
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

    public function getArchive(): ?bool
    {
        return $this->archive;
    }

    public function setArchive(bool $archive): self
    {
        $this->archive = $archive;

        return $this;
    }
}
