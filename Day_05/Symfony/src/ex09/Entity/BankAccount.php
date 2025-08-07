<?php
namespace App\ex09\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BankAccountRepository::class)]
#[ORM\Table(name: "ex09_bank_account")]
class BankAccount
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 20, unique: true)]
    private ?string $accountNumber = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $bankName = null;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2)]
    private ?string $balance = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $createdAt = null;

    // One-to-One relationship with Person (owning side)
    #[ORM\OneToOne(inversedBy: "bankAccount", targetEntity: Person::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Person $person = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->balance = '0.00';
    }

    // Getters and setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccountNumber(): ?string
    {
        return $this->accountNumber;
    }

    public function setAccountNumber(string $accountNumber): self
    {
        $this->accountNumber = $accountNumber;
        return $this;
    }

    public function getBankName(): ?string
    {
        return $this->bankName;
    }

    public function setBankName(string $bankName): self
    {
        $this->bankName = $bankName;
        return $this;
    }

    public function getBalance(): ?string
    {
        return $this->balance;
    }

    public function setBalance(string $balance): self
    {
        $this->balance = $balance;
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
