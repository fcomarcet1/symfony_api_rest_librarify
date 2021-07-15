<?php

namespace App\Controller\Api;

use App\Entity\Book;
use App\Form\Model\BookDto;
use App\Service\FileUploader;
use App\Form\Type\BookFormType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemOperator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;

class BookController extends AbstractFOSRestController
{
    /**
     * Books list.
     *
     * @Rest\Get(path="/books")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function getAction(BookRepository $bookRepository)
    {
        return $bookRepository->findAll();

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
}
