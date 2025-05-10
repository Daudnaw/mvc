<?php

namespace App\Entity;

use App\Repository\LibraryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LibraryRepository::class)]
class Library
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $isbn = null;

    #[ORM\Column(length: 255)]
    private ?string $writer = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

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

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(?string $isbn): static
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getWriter(): ?string
    {
        return $this->writer;
    }

    public function setWriter(string $writer): static
    {
        $this->writer = $writer;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Set multiple properties with optional values.
     *
     * @param string|null $title
     * @param string|null $writer
     * @param string|null $isbn
     * @param string|null $image
     * @return $this
     */
    public function bookSetter(
        ?string $title = null,
        ?string $writer = null,
        ?string $isbn = null,
        ?string $image = null
    ): self {
        $this->setTitle($title ?? $this->getTitle() ?? '');
        $this->setWriter($writer ?? $this->getWriter() ?? '');
        $this->setIsbn($isbn ?? $this->getIsbn() ?? '');
        $this->setImage($image ?? $this->getImage() ?? '');

        return $this;
    }
}
