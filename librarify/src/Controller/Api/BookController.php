<?php

namespace App\Controller\Api;

use App\Entity\Book;
use App\Model\Exception\Book\BookNotFound;
use App\Repository\BookRepository;
use App\Service\Book\BookFormProcessor;
use App\Service\Book\DeleteBook;
use App\Service\Book\GetBook;
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
    public function getAction(BookRepository $bookRepository): array
    {
        return $bookRepository->findAll();
    }

    /**
     * Create new book user can select previous categories or add new categories.
     *
     * @Rest\Post(path="/books")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function postAction(BookFormProcessor $bookFormProcessor, Request $request)
    {
        // Call bookFormProcessor service
        [$book, $error] = ($bookFormProcessor)($request);

        $statusCode = $book ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $book ?? $error;

        return View::create($data, $statusCode);
    }

    /**
     * Get book detail.
     *
     * @Rest\Get(path="/books/{id}")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     *
     * @throws BookNotFound
     */
    public function getSingleAction(string $id, GetBook $getBook)
    {
        //Call service to find book to edit
        $book = ($getBook)($id);

        if (!$book) {
            return View::create('Book not found', Response::HTTP_BAD_REQUEST);
        }

        return $book;
    }

    /**
     * Edit book.
     *
     * @Rest\Post(path="/books/{id}")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     *
     * @throws BookNotFound
     */
    public function editAction(
        string $id,
        GetBook $getBook,
        BookFormProcessor $bookFormProcessor,
        Request $request
    ) {
        //Call service to find book to edit
        $book = ($getBook)($id);
        if (!$book) {
            return View::create('Book not found', Response::HTTP_BAD_REQUEST);
        }
        // Call bookFormProcessor service he receives $book & $request
        [$book, $error] = ($bookFormProcessor)($request, $id);

        //If exists $book->Response::HTTP_CREATED else Response::HTTP_BAD_REQUEST
        $statusCode = $book ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $book ?? $error;

        return View::create($data, $statusCode);
    }

    /**
     * Delete a book.
     *
     * @Rest\Delete(path="/books/{id}")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function deleteAction(string $id, DeleteBook $deleteBook)
    {
        try {
            ($deleteBook)($id);
        } catch (\Throwable $th) {
            return View::create('Book not found, cannot delete this book', Response::HTTP_BAD_REQUEST);
        }

        return View::create(null, Response::HTTP_NO_CONTENT);

        /*   $data = [
              'message' => 'Book successfully deleted',
              'data' => null,
          ];
          return View::create($data, Response::HTTP_OK); */
    }
}
