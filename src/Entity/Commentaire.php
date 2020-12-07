<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CommentaireRepository::class)
 * @ApiResource (
 *       collectionOperations={
 *              "comm"={
 *            "method"="GET",
 *             "route_name"="comm",
 *          "security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR')",
 *           "security_message"="Vous n'avez pas access à cette operation",
 *          "normalization_context"={"groups"={"comm:read"}}
 *               },
 *     "postcomm"={
 *            "method"="GET",
 *             "route_name"="postcomm",
 *          "security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR')",
 *           "security_message"="Vous n'avez pas access à cette operation",
 *          "normalization_context"={"groups"={"postcomm:read"}}
 *               }
 *     }
 * )
 */
class Commentaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"comm:read"})
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     * @Groups ({"comm:read"})
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=FilDiscussion::class, inversedBy="commentaire")
     * @Groups ({"comm:read"})
     */
    private $filDiscussion;

    /**
     * @ORM\ManyToOne(targetEntity=Formateur::class, inversedBy="commentaires")
     * @Groups ({"comm:read"})
     */
    private $formateur;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getFilDiscussion(): ?FilDiscussion
    {
        return $this->filDiscussion;
    }

    public function setFilDiscussion(?FilDiscussion $filDiscussion): self
    {
        $this->filDiscussion = $filDiscussion;

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
}
