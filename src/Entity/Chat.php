<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ChatRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ChatRepository::class)
 * @ApiResource (
 *     collectionOperations={
 *                 "getchat"={
 *                          "method"="GET",
 *                          "route_name"="getchat",
 *                       "normalization_context"={"groups"={"chatapp:read"}},
 *                       "security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_FORMATEUR')",
 *                      "security_message"="Vous n'avez pas access à cette operation"
 *     },
 *     "postchat"={
 *                 "method"="POST",
 *                 "reoute_name"="postchat",
 *                 "security"="is_granted('ROLE_APPRENANT')",
 *          "security_message"="Vous n'avez pas access à cette operation"
 *     }
 *    }
 * )
 * @ApiFilter(SearchFilter::class, properties={"user": "exact"})
 */
class Chat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Ecrire un sms")
     * @Groups ({"chatapp:read"})
     */
    private $message;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $piecejointe;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="chats")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Promo::class, inversedBy="chats")
     */
    private $promo;

    /**
     * @ORM\Column(type="datetime", nullable=false, options={"default" = "CURRENT_TIMESTAMP"})
     */
    private $date;

    public function __construct()
    {
        $this->date = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getPiecejointe()
    {
        return $this->piecejointe;
    }

    public function setPiecejointe($piecejointe): self
    {
        $this->piecejointe = $piecejointe;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPromo(): ?Promo
    {
        return $this->promo;
    }

    public function setPromo(?Promo $promo): self
    {
        $this->promo = $promo;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

}
