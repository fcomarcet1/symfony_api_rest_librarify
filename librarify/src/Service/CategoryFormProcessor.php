<?php

namespace App\Service;

use App\Entity\Category;
use App\Form\Model\CategoryDto;
use App\Form\Type\CategoryFormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class CategoryFormProcessor
{
    private $bookManager;
    private $categoryManager;
    private $formFactory;

    public function __construct(
        BookManager $bookManager,
        CategoryManager $categoryManager,
        FormFactoryInterface $formFactory
    ) {
        $this->bookManager = $bookManager;
        $this->categoryManager = $categoryManager;
        $this->formFactory = $formFactory;
    }

    public function __invoke(Category $category, Request $request): array
    {
        $categoryDto = new CategoryDto();
        $form = $this->formFactory->create(CategoryFormType::class, $categoryDto);
        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            // return tupla [success, error]
            return [null, 'Form is not submitted'];
        }

        if ($form->isValid()) {
            $category->setName($categoryDto->name);
            $this->categoryManager->flush();
            // add$categoryManager->reload($category);

            return [$category, null];
        }

        return [null, $form];
    }

    /* public function addFormProcessor(Request $request): array
    {
        $categoryDto = new CategoryDto();
        $form = $this->formFactory->create(CategoryFormType::class, $categoryDto);
        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            // return tupla [success, error]
            return [null, 'Form is not submitted'];
        }

        if ($form->isValid()) {
            $category = new Category();
            $category->setName($categoryDto->name);
            $this->categoryManager->save($category);
            $this->categoryManager->reload($category);

            return [$category, null];
        }

        return [null, $form];
    }

    public function editFormProcessor(Category $category, Request $request): array
    {
        $categoryDto = new CategoryDto();
        $form = $this->formFactory->create(CategoryFormType::class, $categoryDto);
        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            // return tupla [success, error]
            return [null, 'Form is not submitted'];
        }

        if ($form->isValid()) {
            $category->setName($categoryDto->name);
            $this->categoryManager->flush();
            // add$categoryManager->reload($category);

            return [$category, null];
        }

        return [null, $form];
    } */
}
