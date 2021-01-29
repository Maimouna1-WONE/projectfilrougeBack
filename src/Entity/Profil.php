<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\ProfilRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProfilRepository::class)
 * @UniqueEntity(
 *     fields={"libelle"},
 *     message = "ce profil existe deja"
 * )
 * @ApiResource(
 *     routePrefix="/admin/profils",
 *      attributes={
 *          "security"="is_granted('ROLE_ADMIN')",
 *          "security_message"="Vous n'avez pas access à cette Ressource"
 *      },
 *     normalizationContext={"groups"={"profil:read"}},
 *     collectionOperations={
 *                 "add_profil"={
 *                      "method"="POST",
 *                      "path"="",
 *                   },
 *               "get"={"method"="GET",
 *                      "path"=""}
 *     },
 *     itemOperations={
 *              "get"={"method"="GET",
 *                      "path"="/{id}"},
 *              "put"={"method"="PUT",
 *                      "path"="/{id}"},
 *              "getUsers"={
*                       "method"="GET",
 *                      "path"="/{id}/users"
 *                  },"delete"={"method"="DELETE",
 *                      "path"="/{id}"}
 *          }
 * )
 * @ApiFilter(BooleanFilter::class, properties={"archive"})
 */
class Profil
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"profil:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Donner le libelle du profil")
     * @Groups({"profil:read","user:read","useritem:read","getalluser:read"})
     */
    private $libelle;

    /**
     * @ORM\Column(name="archive", type="boolean", options={"default":false})
     * @Groups({"profil:read"})
     */
    private $archive=false;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="profil")
     * @ApiSubresource ()
     * @Groups({"profil:read","user:read"})
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
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
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setProfil($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            if ($user->getProfil() === $this) {
                $user->setProfil(null);
            }
        }

        return $this;
    }
}
