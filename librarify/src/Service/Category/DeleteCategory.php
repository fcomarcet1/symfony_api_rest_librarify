<?php

namespace App\Service\Category;

use App\Model\Exception\Category\CategoryNotFound;
use App\Repository\CategoryRepository;

class DeleteCategory
{
    // Inject service
    private GetCategory $getCategory;
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository, GetCategory $getCategory)
    {
        $this->getCategory = $getCategory;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @throws CategoryNotFound
     */
    public function __invoke(string $id)
    {
        // Find book to delete
        $category = ($this->getCategory)($id);
        if (!$category) {
            CategoryNotFound::throwException();
        }

        // delete book
        $this->categoryRepository->delete($category);
    }
}
