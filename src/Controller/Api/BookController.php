<?php

namespace App\Controller\Api;

use App\Entity\Book;
use App\Service\BookFormProcessor;
use App\Service\BookManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BookController extends AbstractFOSRestController
{
    /**
     * Books list.
     *
     * @Rest\Get(path="/books")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function getAction(BookManager $bookManager)
    {
        return $bookManager->getRepository()->findAll();
    }

    /**
     * @Rest\Post(path="/books")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function postAction(
        BookManager $bookManager,
        BookFormProcessor $bookFormProcessor,
        Request $request
    ) {
        $book = $bookManager->create();

        // Call bookFormProcessor service he receives $book & $request
        [$book, $error] = ($bookFormProcessor)($book, $request);

        $statusCode = $book ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $book ?? $error;

        return View::create($data, $statusCode);
    }

    /**
     * @Rest\Post(path="/books/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function editAction(
        int $id,
        BookFormProcessor $bookFormProcessor,
        BookManager $bookManager,
        Request $request
    ) {
        // find book to edit
        $book = $bookManager->find($id);
        if (!$book) {
            return View::create('Book not found', Response::HTTP_BAD_REQUEST);
        }
        // Call bookFormProcessor service he receives $book & $request
        [$book, $error] = ($bookFormProcessor)($book, $request);

        //If exists $book->Response::HTTP_CREATED else Response::HTTP_BAD_REQUEST
        $statusCode = $book ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $book ?? $error;

        return View::create($data, $statusCode);
    }

    /**
     * Delete a book.
     *
     * @Rest\Delete(path="/books/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function deleteAction(int $id, BookManager $bookManager)
    {
        //$book = $bookRepository->find($id);
        $book = $bookManager->find($id);
        if (!$book) {
            return View::create('Book not found, cannot delete this book', Response::HTTP_BAD_REQUEST);
        }

        $bookManager->delete($book);

        $data = [
            'message' => 'Book successfully deleted',
            'book' => $book,
        ];

        return View::create($data, Response::HTTP_OK);
    }
}
