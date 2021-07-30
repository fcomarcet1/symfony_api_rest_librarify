<?php

namespace App\Service\Category;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Ramsey\Uuid\Uuid;

class GetCategory
{
    // Inject service
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function __invoke(string $id): ?Category
    {
        return $this->categoryRepository->find(Uuid::fromString($id));
    }
}
