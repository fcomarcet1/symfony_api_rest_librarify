<?php

namespace App\Form\Model;

use App\Entity\Author;
use Ramsey\Uuid\UuidInterface;

class AuthorDto
{
    public ?UuidInterface $id = null;
    public ?string $name = null;

    public static function createEmpty(): self
    {
        return new self();
    }

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    // instance new obj CategoryDto an set id and name
    public static function createFromCategory(Author $author): self
    {
        $dto = new self();
        $dto->id = $author->getId();
        $dto->name = $author->getName();

        return $dto;
    }
}
