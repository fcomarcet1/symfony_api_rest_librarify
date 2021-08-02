<?php

namespace App\Controller\Api;

use App\Repository\AuthorRepository;
use App\Service\Author\AuthorFormProcessor;
use App\Service\Author\CheckUniqueAuthor;
use App\Service\Author\DeleteAuthor;
use App\Service\Author\GetAuthor;
use Exception;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AuthorController extends AbstractFOSRestController
{
    /**
     * Get list of all authors.
     *
     * @Rest\Get(path="/authors")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function getAction(AuthorRepository $authorRepository): array
    {
        return $authorRepository->findAll();
    }

    /**
     * Get author detail.
     *
     * @Rest\Get(path="/authors/{id}")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function getSingleAction(string $id, GetAuthor $getAuthor)
    {
        try {
            $author = ($getAuthor)($id);
        } catch (Exception $exception) {
            //TODO: test return CategoryNotFound:::throwException();
            return View::create('Author not found', Response::HTTP_BAD_REQUEST);
        }

        return $author;
    }

    /**
     * Create a new unique author.
     *
     * @Rest\Post(path="/authors")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function postAction(
        Request $request,
        AuthorFormProcessor $authorFormProcessor,
        CheckUniqueAuthor $checkUniqueAuthor
    ) {
        //check if category exists already.
        $issetAuthor = ($checkUniqueAuthor)($request->get('name'));
        if (!$issetAuthor) {
            $data = ['message' => 'This author already exists.'];

            return View::create($data, Response::HTTP_OK);
        }

        [$author, $error] = ($authorFormProcessor)($request);
        $statusCode = $author ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $author ?? $error;

        return View::create($data, $statusCode);
    }

    /**
     * Edit Category.
     *
     * @Rest\Post(path="/authors/{id}")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function editAction(
        string $id,
        AuthorFormProcessor $authorFormProcessor,
        Request $request
    ) {
        try {
            [$author, $error] = ($authorFormProcessor)($request, $id);
            $statusCode = $author ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
            $data = $author ?? $error;

            return View::create($data, $statusCode);
        } catch (Throwable $t) {
            //TODO: test return CategoryNotFound:::throwException();
            return View::create('Author not found', Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Delete Category.
     *
     * @Rest\Delete(path="/authors/{id}")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function deleteAction(string $id, DeleteAuthor $deleteAuthor)
    {
        try {
            // Call service to find category and delete
            ($deleteAuthor)($id);
        } catch (\Throwable $th) {
            //TODO: test return CategoryNotFound:::throwException();
            return View::create('Author not found, cannot delete this category', Response::HTTP_BAD_REQUEST);
        }

        return View::create(null, Response::HTTP_NO_CONTENT);
    }
}
