<?php

namespace App\Service\Book;

use App\Entity\Book;
use App\Entity\Book\Score;
use App\Form\Model\BookDto;
use App\Form\Model\CategoryDto;
use App\Form\Type\BookFormType;
use App\Model\Exception\Book\BookNotFound;
use App\Repository\BookRepository;
use App\Service\Author\CreateAuthor;
use App\Service\Author\GetAuthor;
use App\Service\Category\CreateCategory;
use App\Service\Category\GetCategory;
use App\Service\FileUploader;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class BookFormProcessor
{
    private GetBook $getBook;
    private BookRepository $bookRepository;
    private CreateCategory $createCategory;
    private GetCategory $getCategory;
    private CreateAuthor $createAuthor;
    private GetAuthor $getAuthor;
    private FileUploader $fileUploader;
    private FormFactoryInterface $formFactory;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        GetBook $getBook,
        BookRepository $bookRepository,
        GetCategory $getCategory,
        CreateCategory $createCategory,
        CreateAuthor $createAuthor,
        GetAuthor $getAuthor,
        FileUploader $fileUploader,
        FormFactoryInterface $formFactory,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->getBook = $getBook;
        $this->bookRepository = $bookRepository;
        $this->createCategory = $createCategory;
        $this->getCategory = $getCategory;
        $this->createAuthor = $createAuthor;
        $this->getAuthor = $getAuthor;
        $this->fileUploader = $fileUploader;
        $this->formFactory = $formFactory;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @throws BookNotFound
     */
    public function __invoke(Request $request, ?string $bookId = null): array
    {
        $book = null;
        $bookDto = null;

        if (null === $bookId) {
            // create new book with uuid && create new BookDto
            $bookDto = BookDto::createEmpty();
        } else {
            // Get book
            $book = ($this->getBook)($bookId);
            // Create new BookDto from this book
            $bookDto = BookDto::createFromBook($book);
            // Get categories if exists --> originalCategories
            foreach ($book->getCategories() as $category) {
                $bookDto->categories[] = CategoryDto::createFromCategory($category);
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

        $categories = [];
        foreach ($bookDto->getCategories() as $newCategoryDto) {
            $category = null;
            if (null !== $newCategoryDto->getId()) {
                // get category to edit
                $category = ($this->getCategory)($newCategoryDto->getId());
            }
            // create category
            if (null === $category) {
                $category = ($this->createCategory)($newCategoryDto->getName());
            }
            // categorias que ha enviado el user
            $categories[] = $category;
        }

        $authors = [];
        foreach ($bookDto->getAuthors() as $newAuthorDto) {
            $author = null;
            if (null !== $newAuthorDto->getId()) {
                $author = ($this->getAuthor)($newAuthorDto->getId());
            }
            if (null === $author) {
                $author = ($this->createAuthor)($newAuthorDto->getName());
            }
            $authors[] = $author;
        }

        $filename = null;
        // Save base64Image
        if ($bookDto->base64Image) {
            $filename = $this->fileUploader->uploadBase64File($bookDto->base64Image);
        }

        if (null === $book) {
            // Create new book
            $book = Book::create(
                $bookDto->getTitle(),
                $filename,
                $bookDto->getDescription(),
                Score::create($bookDto->getScore()),
                $bookDto->getReadAt(),
                $authors,
                $categories
            );
        } else {
            // update existing book
            $book->update(
                $bookDto->getTitle(),
                null === $filename ? $book->getImage() : $filename,
                $bookDto->getDescription(),
                Score::create($bookDto->getScore()),
                $bookDto->getReadAt(),
                $authors,
                $categories
            );
        }
        // save book to database
        $this->bookRepository->save($book);

        // launch created book event
        foreach ($book->pullDomainEvents() as $event) {
            $this->eventDispatcher->dispatch($event);
        }

        // [sucess, error];
        return [$book, null];
    }
}
