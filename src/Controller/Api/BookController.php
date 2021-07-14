<?php

namespace App\Controller\Api;

use App\Repository\BookRepository;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;

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
}
