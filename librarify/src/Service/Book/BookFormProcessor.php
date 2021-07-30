<?php

namespace App\Service\Book;

use App\Entity\Book;
use App\Form\Model\BookDto;
use App\Form\Model\CategoryDto;
use App\Form\Type\BookFormType;
use App\Repository\BookRepository;
use App\Service\Category\CreateCategory;
use App\Service\Category\GetCategory;
use App\Service\FileUploader;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class BookFormProcessor
{
    private GetBook $getBook;
    private BookRepository $bookRepository;
    private CreateCategory $createCategory;
    private GetCategory $getCategory;
    private FileUploader $fileUploader;
    private FormFactoryInterface $formFactory;

    public function __construct(
        GetBook $getBook,
        BookRepository $bookRepository,
        GetCategory $getCategory,
        CreateCategory $createCategory,
        FileUploader $fileUploader,
        FormFactoryInterface $formFactory
    ) {
        $this->getBook = $getBook;
        $this->bookRepository = $bookRepository;
        $this->createCategory = $createCategory;
        $this->getCategory = $getCategory;
        $this->fileUploader = $fileUploader;
        $this->formFactory = $formFactory;
    }

    public function __invoke(Request $request, ?string $bookId = null): array
    {
        $book = null;
        $bookDto = null;
        /** @var CategoryDto[]|ArrayCollection */
        $originalCategories = new ArrayCollection();

        if (null === $bookId) {
            // create new book with uuid && create new BookDto
            $book = Book::create();
            $bookDto = BookDto::createEmpty();
        } else {
            // Get book
            $book = ($this->getBook)($bookId);
            $bookDto = BookDto::createFromBook($book);

            // Get categories if exists --> originalCategories
            foreach ($book->getCategories() as $category) {
                // Create categoryDto from category
                $categoryDto = CategoryDto::createFromCategory($category);
                // add categories to categoryDto
                $bookDto->categories[] = $categoryDto;
                // add categories to $originalCategories
                $originalCategories->add($categoryDto);
            }
        }
        // Create new form-> vinculated --> bookDto class
        $form = $this->formFactory->create(BookFormType::class, $bookDto);
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            // return [success, error]
            return [null, 'Form is not submitted'];
        }
        if (!$form->isValid()) {
            return [null, $form];
        }

        // Remove categories
        // get categories , Once the form is valid use BookDto(data client is here
        foreach ($originalCategories as $originalCategoryDto) {
            if (!\in_array($originalCategoryDto, $bookDto->categories)) {
                $category = ($this->getCategory)($originalCategoryDto->getId());
                $book->removeCategory($category);
            }
        }

        // Add categories
        foreach ($bookDto->getCategories() as $newCategoryDto) {
            if (!$originalCategories->contains($newCategoryDto)) {
                $category = null;
                if (null !== $newCategoryDto->getId()) {
                    $category = ($this->getCategory)($newCategoryDto->getId());
                }
                if (!$category) {
                    $category = ($this->createCategory)($newCategoryDto->getName());
                }
                $book->addCategory($category);
            }
        }
        $book->setTitle($bookDto->title);

        // Save base64Image
        if ($bookDto->base64Image) {
            $filename = $this->fileUploader->uploadBase64File($bookDto->base64Image);
            $book->setImage($filename);
        }
        $this->bookRepository->save($book);

        // return [success, error]
        return [$book, null];
    }
}
