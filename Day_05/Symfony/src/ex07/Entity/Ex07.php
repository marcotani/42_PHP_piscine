<?php
namespace App\ex07\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\\ex07\\Entity\\Ex07Repository")]
#[ORM\Table(name: "ex07")]
class Ex07
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    private $username;

    #[ORM\Column(type: "string", length: 255)]
    private $name;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    private $email;

    #[ORM\Column(type: "boolean")]
    private $enable = false;

    #[ORM\Column(type: "datetime")]
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
    public function getEnable(): bool { return $this->enable; }
    public function isEnable(): bool { return $this->enable; }
    public function setEnable(bool $enable): self { $this->enable = $enable; return $this; }
    public function getBirthdate(): ?\DateTimeInterface { return $this->birthdate; }
    public function setBirthdate(\DateTimeInterface $birthdate): self { $this->birthdate = $birthdate; return $this; }
    public function getAddress(): ?string { return $this->address; }
    public function setAddress(?string $address): self { $this->address = $address; return $this; }
}
