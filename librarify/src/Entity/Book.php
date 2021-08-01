<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Book
{
    private UuidInterface $id;
    private $title;
    private $image;
    private $categories;

    public function __construct(UuidInterface $uuid)
    {
        $this->id = $uuid;
        $this->categories = new ArrayCollection();
    }

    /**
     * Create new Book instance with uuid.
     */
    public static function create(): self
    {
        return new self(Uuid::uuid4());
    }

    public function getId(): UuidInterface
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    public function updateCategories(Category ...$categories)
    {
        // Get original Categories
        /** @var Category[]|ArrayCollection */
        $originalCategories = new ArrayCollection();
        foreach ($this->categories as $category) {
            $originalCategories->add($category);
        }

        // Remove categories
        foreach ($originalCategories as $originalCategory) {
            if (!\in_array($originalCategory, $categories)) {
                $this->removeCategory($originalCategory);
            }
        }

        // Add categories
        foreach ($categories as $newCategory) {
            if (!$originalCategories->contains(!$newCategory)) {
                $this->addCategory($newCategory);
            }
        }
    }

    public function update(
        string $title,
        ?string $image,
        Category ...$categories
    ) {
        $this->title = $title;
        $this->image = $image;
        $this->updateCategories(...$categories);
    }
}
