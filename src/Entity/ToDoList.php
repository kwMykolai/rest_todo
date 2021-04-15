<?php

namespace App\Entity;

use App\Repository\ToDoListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ToDoListRepository::class)
 * @ORM\Table(indexes={@ORM\Index(name="title_idx", columns={"title"})})
 */
class ToDoList
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("rest")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     * @Groups("rest")
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("rest")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("rest")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=ListEntry::class, mappedBy="toDoList", orphanRemoval=true, cascade={"persist"})
     * @Groups("with_entries")
     */
    private $entries;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     * @Groups("rest")
     */
    private $colour;

    public function __construct()
    {
        $this->entries = new ArrayCollection();
        if (!$this->createdAt) {
            $this->createdAt = new \DateTime();
        }
        if (!$this->updatedAt) {
            $this->updatedAt = new \DateTime();
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|ListEntry[]
     */
    public function getEntries(): Collection
    {
        return $this->entries;
    }

    public function addEntry(ListEntry $entry): self
    {
        if (!$this->entries->contains($entry)) {
            $this->entries[] = $entry;
            $entry->setToDoList($this);
        }

        return $this;
    }

    public function removeEntry(ListEntry $entry): self
    {
        if ($this->entries->removeElement($entry)) {
            // set the owning side to null (unless already changed)
            if ($entry->getToDoList() === $this) {
                $entry->setToDoList(null);
            }
        }

        return $this;
    }

    public function getColour(): ?string
    {
        return $this->colour;
    }

    public function setColour(?string $colour): self
    {
        $this->colour = $colour;

        return $this;
    }
}
