<?php
namespace App\ex13\Entity;

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

    // Getters and setters ...
}
