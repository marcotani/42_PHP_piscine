<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "ex13_employee")]
class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 100)]
    private ?string $firstname = null;

    #[ORM\Column(type: "string", length: 100)]
    private ?string $lastname = null;

    #[ORM\Column(type: "string", length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $birthdate = null;

    #[ORM\Column(type: "boolean")]
    private bool $active = true;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $employed_since = null;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $employed_until = null;

    #[ORM\Column(type: "string", columnDefinition: "ENUM('8','6','4')")]
    private ?string $hours = null;

    #[ORM\Column(type: "integer")]
    private ?int $salary = null;

    #[ORM\Column(type: "string", columnDefinition: "ENUM('manager','account_manager','qa_manager','dev_manager','ceo','coo','backend_dev','frontend_dev','qa_tester')")]
    private ?string $position = null;

    #[ORM\ManyToOne(targetEntity: Employee::class)]
    #[ORM\JoinColumn(name: "manager_id", referencedColumnName: "id", nullable: true)]
    private ?Employee $manager = null;

    public function getId(): ?int { return $this->id; }
    public function getFirstname(): ?string { return $this->firstname; }
    public function setFirstname(string $firstname): self { $this->firstname = $firstname; return $this; }
    public function getLastname(): ?string { return $this->lastname; }
    public function setLastname(string $lastname): self { $this->lastname = $lastname; return $this; }
    public function getEmail(): ?string { return $this->email; }
    public function setEmail(string $email): self { $this->email = $email; return $this; }
    public function getBirthdate(): ?\DateTimeInterface { return $this->birthdate; }
    public function setBirthdate(?\DateTimeInterface $birthdate): self { $this->birthdate = $birthdate; return $this; }
    public function isActive(): bool { return $this->active; }
    public function setActive(bool $active): self { $this->active = $active; return $this; }
    public function getEmployedSince(): ?\DateTimeInterface { return $this->employed_since; }
    public function setEmployedSince(?\DateTimeInterface $employed_since): self { $this->employed_since = $employed_since; return $this; }
    public function getEmployedUntil(): ?\DateTimeInterface { return $this->employed_until; }
    public function setEmployedUntil(?\DateTimeInterface $employed_until): self { $this->employed_until = $employed_until; return $this; }
    public function getHours(): ?string { return $this->hours; }
    public function setHours(string $hours): self { $this->hours = $hours; return $this; }
    public function getSalary(): ?int { return $this->salary; }
    public function setSalary(int $salary): self { $this->salary = $salary; return $this; }
    public function getPosition(): ?string { return $this->position; }
    public function setPosition(string $position): self { $this->position = $position; return $this; }
    public function getManager(): ?Employee { return $this->manager; }
    public function setManager(?Employee $manager): self { $this->manager = $manager; return $this; }
}
