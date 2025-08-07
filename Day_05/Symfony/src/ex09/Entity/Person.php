<?php
namespace App\ex09\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonRepository::class)]
#[ORM\Table(name: "ex09_person")]
class Person
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    private ?string $username = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(type: "boolean")]
    private bool $enable = false;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $birthdate = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: "string", length: 20, nullable: true)]
    private ?string $maritalStatus = null;

    // One-to-One relationship with BankAccount
    #[ORM\OneToOne(mappedBy: "person", targetEntity: BankAccount::class, cascade: ["persist", "remove"])]
    private ?BankAccount $bankAccount = null;

    // One-to-Many relationship with Address
    #[ORM\OneToMany(mappedBy: "person", targetEntity: Address::class, cascade: ["persist", "remove"])]
    private Collection $addresses;

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    // Getters and setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function isEnable(): bool
    {
        return $this->enable;
    }

    public function setEnable(bool $enable): self
    {
        $this->enable = $enable;
        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;
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

    public function getMaritalStatus(): ?string
    {
        return $this->maritalStatus;
    }

    public function setMaritalStatus(?string $maritalStatus): self
    {
        $this->maritalStatus = $maritalStatus;
        return $this;
    }

    // Bank Account relationship methods
    public function getBankAccount(): ?BankAccount
    {
        return $this->bankAccount;
    }

    public function setBankAccount(?BankAccount $bankAccount): self
    {
        // Unset the owning side of the relation if necessary
        if ($bankAccount === null && $this->bankAccount !== null) {
            $this->bankAccount->setPerson(null);
        }

        // Set the owning side of the relation if necessary
        if ($bankAccount !== null && $bankAccount->getPerson() !== $this) {
            $bankAccount->setPerson($this);
        }

        $this->bankAccount = $bankAccount;
        return $this;
    }

    // Address collection methods
    /**
     * @return Collection<int, Address>
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(Address $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses[] = $address;
            $address->setPerson($this);
        }
        return $this;
    }

    public function removeAddress(Address $address): self
    {
        if ($this->addresses->removeElement($address)) {
            // Set the owning side to null (unless already changed)
            if ($address->getPerson() === $this) {
                $address->setPerson(null);
            }
        }
        return $this;
    }
}
