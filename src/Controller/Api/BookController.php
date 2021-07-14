<?php

namespace App\Controller\Api;

use App\Entity\Book;
use App\Form\Type\BookFormType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
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
     * Create new book.
     *
     * @Rest\Post(path="/books")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function postAction(Request $request, EntityManagerInterface $em)
    {
        //$title = $request->get('title', null);
        /* $params = json_decode($request->getContent());
        $title = $params->title;
        if (empty($title)) {
            return [
                'success' => false,
                'error' => 'Title cannot be empty',
                'data' => null,
            ];
        } */
        $book = new Book();
        $form = $this->createForm(BookFormType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($book);
            $em->flush();

            return $book;
        }

        return $form;

        /* $response = [
            'success' => true,
            'data' => $book
        ];
        return $response;
    } */
    }
}
