<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $lastEditedAt = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $lastEditedBy = null;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: 'text')]
    private ?string $content = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $created = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;
        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): static
    {
        $this->created = $created;
        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function getLastEditedAt(): ?\DateTimeInterface
    {
        return $this->lastEditedAt;
    }

    public function setLastEditedAt(?\DateTimeInterface $lastEditedAt): static
    {
        $this->lastEditedAt = $lastEditedAt;
        return $this;
    }

    public function getLastEditedBy(): ?User
    {
        return $this->lastEditedBy;
    }

    public function setLastEditedBy(?User $user): static
    {
        $this->lastEditedBy = $user;
        return $this;
    }

    public function setAuthor(User $author): static
    {
        $this->author = $author;
        return $this;
    }
}
