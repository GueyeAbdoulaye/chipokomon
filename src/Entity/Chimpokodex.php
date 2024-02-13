<?php

namespace App\Entity;

use App\Repository\ChimpokodexRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChimpokodexRepository::class)]
class Chimpokodex
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $pvMax = null;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'devolution')]
    private Collection $evolution;

    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'evolution')]
    private Collection $devolution;

    #[ORM\Column(length: 24)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->evolution = new ArrayCollection();
        $this->devolution = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPvMax(): ?int
    {
        return $this->pvMax;
    }

    public function setPvMax(int $pvMax): static
    {
        $this->pvMax = $pvMax;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getEvolution(): Collection
    {
        return $this->evolution;
    }

    public function addEvolution(self $evolution): static
    {
        if (!$this->evolution->contains($evolution)) {
            $this->evolution->add($evolution);
        }

        return $this;
    }

    public function removeEvolution(self $evolution): static
    {
        $this->evolution->removeElement($evolution);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getDevolution(): Collection
    {
        return $this->devolution;
    }

    public function addDevolution(self $devolution): static
    {
        if (!$this->devolution->contains($devolution)) {
            $this->devolution->add($devolution);
            $devolution->addEvolution($this);
        }

        return $this;
    }

    public function removeDevolution(self $devolution): static
    {
        if ($this->devolution->removeElement($devolution)) {
            $devolution->removeEvolution($this);
        }

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}