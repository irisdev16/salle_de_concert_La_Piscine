<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $capacity = null;

    #[ORM\ManyToOne (targetEntity: Establishment::class, inversedBy: "rooms")]
    #[ORM\JoinColumn(nullable: false, onDelete: 'cascade')]
    private ?Establishment $establishment = null;

    #[ORM\OneToMany(mappedBy: 'room',targetEntity: Event::class)]
    private Collection $events;

    #[ORM\ManyToMany(targetEntity: Tag::class)]
    #[ORM\JoinTable(name: 'room_tag')]
    private Collection $tags;

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

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): static
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getEstablishment(): ?Establishment{
        return $this->establishment;
    }

    public function setEstablishment(?Establishment $establishment): static{
        $this->establishment = $establishment;
        return $this;
    }

    public function getEvents(): Collection{
        return $this->events;
    }

    public function setEvent(?Event $event): static{
        $this->events = $event;
        return $this;
    }

    public function getTags(): Collection{
        return $this->tags;
    }

    public function setTags(?Tag $tags): static{
        $this->tags = $tags;
        return $this;
    }
}
