<?php

namespace App\Service\Author;

use App\Repository\AuthorRepository;

class CheckUniqueAuthor
{
    // Inject service
    private AuthorRepository $authorRepository;

    public function __construct(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    public function __invoke(string $name): bool
    {
        $author = $this->authorRepository->findByName($name);
        if (0 !== sizeof($author)) {
            return false; //Exists category
        }

        return true;
    }
}
