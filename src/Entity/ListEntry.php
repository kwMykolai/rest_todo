<?php

namespace App\Entity;

use App\Repository\ListEntryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ListEntryRepository::class)
 * @ORM\Table(indexes={@ORM\Index(name="title_idx", columns={"title"}), @ORM\Index(name="comment_idx", columns={"comment"})})
 */
class ListEntry
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("rest")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128)
     * @Groups("rest")
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     * @Groups("rest")
     */
    private $comment;

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
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups("rest")
     */
    private $closeDue;

    /**
     * @ORM\ManyToOne(targetEntity=ToDoList::class, inversedBy="entries")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("with_list")
     */
    private $toDoList;

    public function __construct()
    {
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

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

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

    public function getCloseDue(): ?\DateTimeInterface
    {
        return $this->closeDue;
    }

    public function setCloseDue(?\DateTimeInterface $closeDue): self
    {
        $this->closeDue = $closeDue;

        return $this;
    }

    public function getToDoList(): ?ToDoList
    {
        return $this->toDoList;
    }

    public function setToDoList(?ToDoList $toDoList): self
    {
        $this->toDoList = $toDoList;

        return $this;
    }
}
