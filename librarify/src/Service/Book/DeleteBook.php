<?php

namespace App\Service\Book;

use App\Repository\BookRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DeleteBook
{
    // Inject service
    private BookRepository $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function __invoke(string $id)
    {
        // Find book to delete
        $book = $this->bookRepository->find(Uuid::fromString($id));
        if (!$book) {
            //throw new Exception('That book does not exist');
            throw new HttpException(404, 'That book does not exist');
        }

        // delete book
        $this->bookRepository->delete($book);
    }
}
