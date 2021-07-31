<?php

namespace App\Service\Category;

use App\Repository\CategoryRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EditCategory
{
    // Inject service
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function __invoke(string $id, string $name)
    {
        // Find book to edit
        $category = $this->categoryRepository->find(Uuid::fromString($id));
        if (!$category) {
            //throw new Exception('That book does not exist');
            throw new HttpException(404, 'That book does not exist');
        }
        //$category->setName($name)

        // edit book
        $this->categoryRepository->save($category);
    }
}
