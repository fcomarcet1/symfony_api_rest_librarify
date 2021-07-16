<?php

namespace App\Controller\Api;

use App\Entity\Book;
use App\Form\Model\BookDto;
use App\Form\Type\BookFormType;
use App\Service\BookFormProcessor;
use App\Service\BookManager;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
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
     * Creates a Book resource.
     *
     * @Rest\Post(path="/books")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function postAction(
        Request $request,
        EntityManagerInterface $em,
        FileUploader $fileUploader
    ) {
        // Create new DTO
        $bookDto = new BookDto();
        $form = $this->createForm(BookFormType::class, $bookDto);
        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            return new Response('', Response::HTTP_BAD_REQUEST);
        }

        if ($form->isValid()) {
            $book = new Book();
            $book->setTitle($bookDto->title);
            if ($bookDto->base64Image) {
                // Upload base64Image
                $filename = $fileUploader->uploadBase64File($bookDto->base64Image);
                $book->setImage($filename);
            }

            $em->persist($book);
            $em->flush();

            return $book;
        }

        return $form;
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
}
