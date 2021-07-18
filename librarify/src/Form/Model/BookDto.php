<?php

namespace App\Form\Model;

use App\Entity\Book;

class BookDto
{
    public $title;
    public $base64Image;
    public $categories;

    public function __construct()
    {
        $this->categories = [];
    }

    // instance new obj BookDto from $book an set title
    public static function createFromBook(Book $book): self
    {
        $dto = new self();
        $dto->title = $book->getTitle();

        return $dto;
    }
}
