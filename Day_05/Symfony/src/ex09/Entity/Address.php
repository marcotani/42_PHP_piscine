<?php
namespace App\ex09\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
#[ORM\Table(name: "ex09_address")]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 20)]
    private ?string $type = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $street = null;

    #[ORM\Column(type: "string", length: 100)]
    private ?string $city = null;

    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $state = null;

    #[ORM\Column(type: "string", length: 20, nullable: true)]
    private ?string $postalCode = null;

    #[ORM\Column(type: "string", length: 100)]
    private ?string $country = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $createdAt = null;

    // Many-to-One relationship with Person
    #[ORM\ManyToOne(targetEntity: Person::class, inversedBy: "addresses")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Person $person = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    // Getters and setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;
        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;
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

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): self
    {
        $this->person = $person;
        return $this;
    }
}
