<?php

namespace App\Form\Model;

use App\Entity\Category;
use Ramsey\Uuid\UuidInterface;

class CategoryDto
{
    public ?UuidInterface $id = null;
    public ?string $name = null;

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    // instance new obj CategoryDto an set id and name
    public static function createFromCategory(Category $category): self
    {
        $dto = new self();
        $dto->id = $category->getId();
        $dto->name = $category->getName();

        return $dto;
    }
}
