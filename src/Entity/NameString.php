<?php

namespace App\Entity;

use App\Repository\NameStringRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NameStringRepository::class)]
class NameString
{
/**
 * @var int|null
 */
#[ORM\Id]
#[ORM\GeneratedValue]
#[ORM\Column(type: "integer")]
private $id;



    #[ORM\Column]
    private ?int $value = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): static
    {
        $this->value = $value;

        return $this;
    }
}
