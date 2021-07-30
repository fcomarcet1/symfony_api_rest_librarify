<?php

namespace App\Service\Category;

use App\Repository\CategoryRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
            //throw new Exception('That book does not exist');
            throw new HttpException(404, 'That book does not exist');
        }

        // delete book
        $this->categoryRepository->delete($category);
    }
}
