<?php

namespace App\Service\Category;

use Ramsey\Uuid\Uuid;
use App\Repository\CategoryRepository;
use App\Model\Exception\Category\CategoryNotFound;

class DeleteCategory
{
    // Inject service
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function __invoke(string $id)
    {
        // Find book to delete
        $category = $this->categoryRepository->find(Uuid::fromString($id));
        if (!$category) {
            CategoryNotFound::throwException();
        }

        // delete book
        $this->categoryRepository->delete($category);
    }
}
