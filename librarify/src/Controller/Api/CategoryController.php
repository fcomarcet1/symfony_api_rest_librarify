<?php

namespace App\Controller\Api;

use App\Form\Model\CategoryDto;
use App\Form\Type\CategoryFormType;
use App\Service\CategoryFormProcessor;
use App\Service\CategoryManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends AbstractFOSRestController
{
    /**
     * Get list of all categories.
     *
     * @Rest\Get(path="/categories")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function getAction(CategoryManager $categoryManager)
    {
        return $categoryManager->getRepository()->findAll();
    }

    /**
     * Create new category.
     *
     * @Rest\Post(path="/categories")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function postAction(Request $request, CategoryManager $categoryManager)
    {
        $categoryDto = new CategoryDto();
        $form = $this->createForm(CategoryFormType::class, $categoryDto);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            // create new category set data and save
            $category = $categoryManager->create();
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
    }

    /**
     * Edit category.
     *
     * @Rest\Post(path="/categories/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function editAction(
        int $id,
        Request $request,
        CategoryManager $categoryManager,
        CategoryFormProcessor $categoryFormProcessor
    ) {
        // find category to edit
        $category = $categoryManager->find($id);
        if (!$category) {
            return View::create('Category not found, cannot edit this category', Response::HTTP_BAD_REQUEST);
        }

        // Call categoryFormProcessor service he receives $category & $request
        [$category, $error] = ($categoryFormProcessor)($category, $request);

        //If exists $category->Response::HTTP_CREATED else Response::HTTP_BAD_REQUEST
        $statusCode = $category ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $category ?? $error;

        return View::create($data, $statusCode);
    }

    /**
     * Delete Category.
     *
     * @Rest\Delete(path="/categories/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function deleteAction(int $id, CategoryManager $categoryManager)
    {
        //$book = $bookRepository->find($id);
        $category = $categoryManager->find($id);
        if (!$category) {
            return View::create('Category not found, cannot delete this category', Response::HTTP_BAD_REQUEST);
        }

        $categoryManager->delete($category);

        $data = [
            'message' => 'Category successfully deleted',
            'category' => $category->getName(),
        ];

        return View::create($data, Response::HTTP_OK);
    }
}
