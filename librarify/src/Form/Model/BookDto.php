<?php

namespace App\Form\Model;

use App\Entity\Book;
use DateTimeInterface;

class BookDto
{
    public ?string $title = null;
    public ?string $base64Image = null;
    public ?string $description = null;
    public ?int $score = null;
    public ?DateTimeInterface $readAt = null;
    /** @var \App\Form\Model\CategoryDto[]|null */
    public ?array $categories = [];

    public function __construct()
    {
        $this->categories = [];
    }

    public static function createEmpty(): self
    {
        return new self();
    }

    // instance new obj BookDto from $book an set title
    public static function createFromBook(Book $book): self
    {
        $dto = new self();
        $dto->title = $book->getTitle();
        $dto->score = $book->getScore()->getValue();
        /* $dto->description = $book->getDescription();
        $dto->score = $book->getScore(); */

        return $dto;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getBase64Image(): ?string
    {
        return $this->base64Image;
    }

    /**
     * @return \App\Form\Model\CategoryDto[]|null
     */
    public function getCategories(): ?array
    {
        return $this->categories;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function getReadAt(): ?DateTimeInterface
    {
        return $this->readAt;
    }
}
