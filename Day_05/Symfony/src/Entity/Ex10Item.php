<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "ex10_orm")]
class Ex10Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: "string", length: 255)]
    private $content;

    public function getId(): ?int { return $this->id; }
    public function getContent(): ?string { return $this->content; }
    public function setContent(string $content): self { $this->content = $content; return $this; }
}
