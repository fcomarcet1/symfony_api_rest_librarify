<?php

namespace App\Form\Model;

use App\Entity\Category;

class CategoryDto
{
    public $id;
    public string $name;

    // instance new obj CategoryDto an set id and name
    public static function createFromCategory(Category $category): self
    {
        $dto = new self();
        $dto->id = $category->getId();
        $dto->name = $category->getName();

        return $dto;
    }
}
