<?php

namespace App\Service;

use App\Entity\Book;
use App\Form\Model\BookDto;
use App\Form\Model\CategoryDto;
use App\Form\Type\BookFormType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class BookFormProcessor
{
    private $bookManager;
    private $categoryManager;
    private $fileUploader;
    private $formFactory;

    /**
     * Nota: en el controlador creamos el form mediante el servicio createForm:
     *      $form = $this->createForm(BookFormType::class, $bookDto);
     * pero en el servicio no podemos usar esto si hacemos un debug del container vemos que
     * utiliza FormFactoryInterface $formFactory y es lo necesitamos inyectar en el constructor
     * para poder crear un form en el servicio:
     *      $form = $this->formFactory->create(BookFormType::class, $bookDto);.
     */
    public function __construct(
        BookManager $bookManager,
        CategoryManager $categoryManager,
        FileUploader $fileUploader,
        FormFactoryInterface $formFactory
    ) {
        $this->bookManager = $bookManager;
        $this->categoryManager = $categoryManager;
        $this->fileUploader = $fileUploader;
        $this->formFactory = $formFactory;
    }

    public function __invoke(Book $book, Request $request): array
    {
        // Create new bookDto from book
        $bookDto = BookDto::createFromBook($book);
        $originalCategories = new ArrayCollection();

        // Get categories if exists
        foreach ($book->getCategories() as $category) {
            // Create categoryDto from category
            $categoryDto = CategoryDto::createFromCategory($category);
            // add categories to categoryDto
            $bookDto->categories[] = $categoryDto;
            // add categories to $originalCategories
            $originalCategories->add($categoryDto);
        }
        // Create new form->bookDto class
        $form = $this->formFactory->create(BookFormType::class, $bookDto);
        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            // return tupla [success, error]
            return [null, 'Form is not submitted'];
        }

        if ($form->isValid()) {
            // remove categories
            // get categories , Once the form is valid use BookDto(data client is here)
            foreach ($originalCategories as $originalCategoryDto) {
                if (!in_array($originalCategoryDto, $bookDto->categories)) {
                    $category = $this->categoryManager->find($originalCategoryDto->id);
                    $book->removeCategory($category);
                }
            }

            // Add categories
            foreach ($bookDto->categories as $newCategoryDto) {
                if (!$originalCategories->contains($newCategoryDto)) {
                    // Si le pasamos el id por el json no crea nueva category ->find($newCategoryDto->id ?? 0)
                    $category = $this->categoryManager->find($newCategoryDto->id ?? 0);
                    if (!$category) {
                        $category = $this->categoryManager->create();
                        $category->setName($newCategoryDto->name);
                        $this->categoryManager->persist($category);
                    }

                    $book->addCategory($category);
                }
            }

            $book->setTitle($bookDto->title);

            if ($bookDto->base64Image) {
                $filename = $this->fileUploader->uploadBase64File($bookDto->base64Image);
                $book->setImage($filename);
            }

            $this->bookManager->save($book);
            $this->bookManager->reload($book);

            // return tupla [success, error]
            return [$book, null];
        }
        // return tupla [success, error]
        return [null, $form];
    }
}
