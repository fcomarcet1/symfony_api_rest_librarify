<?php

namespace App\Service\Book;

use App\Model\Exception\Book\BookNotFound;
use App\Repository\BookRepository;

class DeleteBook
{
    private GetBook $getBook;
    private BookRepository $bookRepository;

    public function __construct(GetBook $getBook, BookRepository $bookRepository)
    {
        $this->getBook = $getBook;
        $this->bookRepository = $bookRepository;
    }

    /**
     * @throws BookNotFound
     */
    public function __invoke(string $id)
    {
        $book = ($this->getBook)($id);
        if (!$book) {
            BookNotFound::throwException();
        }
        $this->bookRepository->delete($book);
    }
}
