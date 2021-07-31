<?php

namespace App\Service\Category;

use App\Entity\Category;
use App\Form\Model\CategoryDto;
use App\Form\Type\CategoryFormType;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class CategoryFormProcessor
{
    private GetCategory $getCategory;
    private FormFactoryInterface $formFactory;
    private CategoryRepository $categoryRepository;

    public function __construct(
        GetCategory $getCategory,
        FormFactoryInterface $formFactory,
        CategoryRepository $categoryRepository
    ) {
        $this->getCategory = $getCategory;
        $this->formFactory = $formFactory;
        $this->categoryRepository = $categoryRepository;
    }

    public function __invoke(Request $request, ?string $categoryId = null): array
    {
        $category = null;
        $categoryDto = null;

        if (null === $categoryId) { // id = null --> create new
            $categoryDto = CategoryDto::createEmpty();
        } else {
            // get category to edit
            $category = ($this->getCategory)($categoryId);
            // create new categoryDto from category
            $categoryDto = CategoryDto::createFromCategory($category);
        }

        // create form
        $form = $this->formFactory->create(CategoryFormType::class, $categoryDto);
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            return [null, 'Form is not submitted'];
        }
        if (!$form->isValid()) {
            return [null, $form];
        }

        if (null === $category) {
            // create category
            $category = Category::create($categoryDto->getName());
        } else {
            //edit category
            $category->update($categoryDto->getName());
        }

        // save or update category
        $this->categoryRepository->save($category);

        return [$category, null];
    }
}
