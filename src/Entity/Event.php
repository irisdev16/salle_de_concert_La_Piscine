<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $start_time = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $end_time = null;

    #[ORM\ManyToOne(targetEntity: Room::class, inversedBy: 'events')]
    private ?Room $room = null;

    #[ORM\ManyToOne(targetEntity: Animator::class, inversedBy: 'events')]
    private ?Animator $animator = null;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'events')]
    private ?Category $category = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStartTime(): ?\DateTimeImmutable
    {
        return $this->start_time;
    }

    public function setStartTime(\DateTimeImmutable $start_time): static
    {
        $this->start_time = $start_time;

        return $this;
    }

    public function getEndTime(): ?\DateTimeImmutable
    {
        return $this->end_time;
    }

    public function setEndTime(\DateTimeImmutable $end_time): static
    {
        $this->end_time = $end_time;

        return $this;
    }

    public function getRoom(): ?Room{
        return $this->room;
    }

    public function setRoom(?Room $room): static{
        $this->room = $room;
        return $this;
    }

    public function getAnimator(): ?Animator{
        return $this->animator;
    }

    public function setAnimator(?Animator $animator): static{
        $this->animator = $animator;
        return $this;
    }

    public function getCategory(): ?Category{
        return $this->category;
    }

    public function setCategory(?Category $category): static{
        $this->category = $category;
        return $this;
    }
}
