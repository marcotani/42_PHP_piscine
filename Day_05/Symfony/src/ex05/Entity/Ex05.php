<?php
namespace App\ex05\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: "App\\ex05\\Entity\\Ex05Repository")]
#[ORM\Table(name: "ex05")]
class Ex05
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    #[Assert\NotBlank()]
    private $username;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank()]
    private $name;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    #[Assert\NotBlank()]
    #[Assert\Email()]
    private $email;

    #[ORM\Column(type: "boolean")]
    private $enable = false;

    #[ORM\Column(type: "datetime")]
    #[Assert\NotBlank()]
    private $birthdate;

    #[ORM\Column(type: "text", nullable: true)]
    private $address;

    // Getters and setters ...
    public function getId(): ?int { return $this->id; }
    public function getUsername(): ?string { return $this->username; }
    public function setUsername(string $username): self { $this->username = $username; return $this; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }
    public function getEmail(): ?string { return $this->email; }
    public function setEmail(string $email): self { $this->email = $email; return $this; }
    public function isEnable(): bool { return $this->enable; }
    public function setEnable(bool $enable): self { $this->enable = $enable; return $this; }
    public function getBirthdate(): ?\DateTimeInterface { return $this->birthdate; }
    public function setBirthdate(\DateTimeInterface $birthdate): self { $this->birthdate = $birthdate; return $this; }
    public function getAddress(): ?string { return $this->address; }
    public function setAddress(?string $address): self { $this->address = $address; return $this; }
}
