<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Form\Model\CategoryDto;
use App\Form\Type\CategoryFormType;
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
    public function postAction(
        Request $request,
        CategoryManager $categoryManager
    ) {
        $categoryDto = new CategoryDto();
        $form = $this->createForm(CategoryFormType::class, $categoryDto);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            // create new category set data and save
            $category = $categoryManager->create();
            $category->setName($categoryDto->name);
            $categoryManager->save($category);

            return $category;

            /* $data = [
                'message' => 'Category created',
                'data' => $category,
            ];
            return View::create($data, Response::HTTP_CREATED); */
        }

        return $form;
    }
}
