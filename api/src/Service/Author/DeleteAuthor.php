<?php

namespace App\Service\Author;

use App\Model\Exception\Author\AuthorNotFound;
use App\Repository\AuthorRepository;

class DeleteAuthor
{
    // Inject service
    private GetAuthor $getAuthor;
    private AuthorRepository $authorRepository;

    public function __construct(AuthorRepository $authorRepository, GetAuthor $getAuthor)
    {
        $this->getAuthor = $getAuthor;
        $this->authorRepository = $authorRepository;
    }

    /**
     * @throws AuthorNotFound
     */
    public function __invoke(string $id)
    {
        // Find author to delete
        $author = ($this->getAuthor)($id);
        if (!$author) {
            AuthorNotFound::throwException();
        }

        // delete book
        $this->authorRepository->delete($author);
    }
}
