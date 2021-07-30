<?php

namespace App\Service\Category;

use App\Repository\CategoryRepository;

class CheckUniqueCategory
{
    // Inject service
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function __invoke(string $name): bool
    {
        $category = $this->categoryRepository->findByName($name);
        if (0 !== sizeof($category)) {
            return false; //Exists category
        }

        return true;
    }
}
