<?php

namespace App\Controller\Api;

use App\Entity\Book;
use App\Entity\Category;
use App\Form\Model\BookDto;
use App\Form\Model\CategoryDto;
use App\Form\Type\BookFormType;
use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use App\Service\FileUploader;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
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

    /**
     * @Rest\Post(path="/books/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function editAction(
        int $id,
        EntityManagerInterface $em,
        BookRepository $bookRepository,
        CategoryRepository $categoryRepository,
        Request $request,
        FileUploader $fileUploader
    ) {
        $book = $bookRepository->find($id);
        if (!$book) {
            throw $this->createNotFoundException('Book not found');
        }
        $bookDto = BookDto::createFromBook($book);

        // Get categories if exists
        $originalCategories = new ArrayCollection();
        foreach ($book->getCategories() as $category) {
            // Create categoryDto from category
            $categoryDto = CategoryDto::createFromCategory($category);
            // add categories to categoryDto
            $bookDto->categories[] = $categoryDto;
            // add categories to $originalCategories
            $originalCategories->add($categoryDto);
        }

        $form = $this->createForm(BookFormType::class, $bookDto);
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            return new Response('', Response::HTTP_BAD_REQUEST);
        }
        if ($form->isValid()) {
            // remove categories
            // get categories , Once the form is valid use BookDto(data client is here)
            foreach ($originalCategories as $originalCategoryDto) {
                if (!in_array($originalCategoryDto, $bookDto->categories)) {
                    $category = $categoryRepository->find($originalCategoryDto->id);
                    $book->removeCategory($category);
                }
            }

            // User add categories
            foreach ($bookDto->categories as $newCategoryDto) {
                if (!$originalCategories->contains($newCategoryDto)) {
                    $category = $categoryRepository->find($newCategoryDto->id ?? 0);
                    if (!$category) {
                        $category = new Category();
                        $category->setName($newCategoryDto->name);
                        $em->persist($category);
                    }

                    $book->addCategory($category);
                }
            }

            $book->setTitle($bookDto->title);
            // upload base64Image
            if ($bookDto->base64Image) {
                $filename = $fileUploader->uploadBase64File($bookDto->base64Image);
                $book->setImage($filename);
            }
            // save book in db
            $em->persist($book);
            $em->flush();
            $em->refresh($book);

            return $book;
        }

        return $form;
    }
}
