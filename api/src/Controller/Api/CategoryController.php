<?php

namespace App\Controller\Api;

use App\Repository\CategoryRepository;
use App\Service\Category\CategoryFormProcessor;
use App\Service\Category\CheckUniqueCategory;
use App\Service\Category\DeleteCategory;
use App\Service\Category\GetCategory;
use Exception;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CategoryController extends AbstractFOSRestController
{
    /**
     * Get list of all categories.
     *
     * @Rest\Get(path="/categories")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function getAction(CategoryRepository $categoryRepository): array
    {
        return $categoryRepository->findAll();
    }

    /**
     * Get category detail.
     *
     * @Rest\Get(path="/categories/{id}")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function getSingleAction(string $id, GetCategory $getCategory)
    {
        try {
            $category = ($getCategory)($id);
        } catch (Exception $exception) {
            //TODO: test return CategoryNotFound:::throwException();
            return View::create('Category not found', Response::HTTP_BAD_REQUEST);
        }

        return $category;
    }

    /**
     * Create a new unique category.
     *
     * @Rest\Post(path="/categories")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function postAction(
        Request $request,
        CategoryFormProcessor $categoryFormProcessor,
        CheckUniqueCategory $checkUniqueCategory
    ) {
        //check if category exists already.
        $issetCategory = ($checkUniqueCategory)($request->get('name'));
        if (!$issetCategory) {
            $data = ['message' => 'This category already exists.'];

            return View::create($data, Response::HTTP_OK);
        }

        [$category, $error] = ($categoryFormProcessor)($request);
        $statusCode = $category ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $category ?? $error;

        return View::create($data, $statusCode);
    }

    /**
     * Create new category.
     *
     * @Rest\Post(path="/categoriesTest")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    /* public function postActionTest(
        Request $request,
        CategoryRepository $categoryRepository,
        CategoryManager $categoryManager
        ) {
        $categoryDto = new CategoryDto();
        $form = $this->createForm(CategoryFormType::class, $categoryDto);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            // create new category set data and save
            $category = Category::create($categoryDto->getName());
            //$category = $categoryManager->create();
            $category->setName($categoryDto->name);

            $categoryManager->save($category);
            //return $category;

            $data = [
                'message' => 'Category created',
                'data' => $category,
            ];

            return View::create($data, Response::HTTP_CREATED);
        }

        return $form;
    } */

    /**
     * Edit Category.
     *
     * @Rest\Post(path="/categories/{id}")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function editAction(
        string $id,
        CategoryFormProcessor $categoryFormProcessor,
        Request $request
    ) {
        try {
            [$category, $error] = ($categoryFormProcessor)($request, $id);
            $statusCode = $category ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
            $data = $category ?? $error;

            return View::create($data, $statusCode);
        } catch (Throwable $t) {
            //TODO: test return CategoryNotFound:::throwException();
            return View::create('Category not found', Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Delete Category.
     *
     * @Rest\Delete(path="/categories/{id}")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function deleteAction(string $id, DeleteCategory $deleteCategory)
    {
        try {
            // Call service to find category and delete
            ($deleteCategory)($id);
        } catch (\Throwable $th) {
            //TODO: test return CategoryNotFound:::throwException();
            return View::create('Category not found, cannot delete this category', Response::HTTP_BAD_REQUEST);
        }

        return View::create(null, Response::HTTP_NO_CONTENT);
    }
}
