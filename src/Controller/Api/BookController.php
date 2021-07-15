<?php

namespace App\Controller\Api;

use App\Entity\Book;
use App\Form\Model\BookDto;
use App\Form\Type\BookFormType;
use App\Repository\BookRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use League\Flysystem\FilesystemOperator;
use Symfony\Component\HttpFoundation\Request;

class BookController extends AbstractFOSRestController
{
    /**
     * List of all books.
     *
     * @Rest\Get(path="/books")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function getAction(BookRepository $bookRepository)
    {
        return $bookRepository->findAll();
    }

    /**
     * Create new book opcional upload base64Image.
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

        if ($form->isSubmitted() && $form->isValid()) {
            //$data = $form->getData();

            // save image(base64Image) in public/storage/images/books service FileUploader
            $filename = $fileUploader->uploadBase64File($bookDto->base64Image);

            $book = new Book();
            $book->setTitle($bookDto->title);
            $book->setImage($filename);
            $em->persist($book);
            $em->flush();

            return $book;
        }

        return $form;
    }
}
