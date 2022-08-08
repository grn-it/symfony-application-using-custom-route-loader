<?php

namespace App\Entity;

use App\Repository\RouteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RouteRepository::class)]
class Route
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private array $requirements = [];

    #[ORM\Column]
    private array $methods = [];

    #[ORM\Column]
    private array $defaults = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

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

    public function getRequirements(): array
    {
        return $this->requirements;
    }

    public function setRequirements(array $requirements): self
    {
        $this->requirements = $requirements;

        return $this;
    }

    public function getMethods(): array
    {
        return $this->methods;
    }

    public function setMethods(array $methods): self
    {
        $this->methods = $methods;

        return $this;
    }

    public function getDefaults(): array
    {
        return $this->defaults;
    }

    public function setDefaults(array $defaults): self
    {
        $this->defaults = $defaults;

        return $this;
    }
}
