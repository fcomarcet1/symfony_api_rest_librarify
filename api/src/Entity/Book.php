<?php

namespace App\Entity;

use App\Entity\Book\Score;
use App\Event\Book\BookCreatedEvent;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DomainException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Contracts\EventDispatcher\Event;

class Book
{
    private UuidInterface $id;
    private string $title;
    private ?string $image;
    private Score $score;
    private ?string $description;
    private ?DateTimeInterface $createdAt;
    private ?DateTimeInterface $readAt;
    /** @var Collection|Category[] */
    private Collection $categories;
    /** @var Collection|Author[] */
    private Collection $authors;
    private array $domainEvents;

    public function __construct(
        UuidInterface $uuid,
        string $title,
        ?string $image,
        ?string $description,
        ?Score $score,
        ?DateTimeInterface $readAt,
        ?Collection $categories,
        ?Collection $authors
    ) {
        $this->id = $uuid;
        $this->title = $title;
        $this->image = $image;
        $this->description = $description ?? $description;
        $this->score = $score ?? Score::create();
        $this->readAt = $readAt;
        $this->categories = $categories ?? new ArrayCollection();
        $this->authors = $authors ?? new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
    }

    public function addDomainEvent(Event $event): void
    {
        $this->domainEvents[] = $event;
    }

    public function pullDomainEvents(): array
    {
        return $this->domainEvents;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function setScore(Score $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getScore(): Score
    {
        return $this->score;
    }

    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function getReadAt(): ?DateTimeInterface
    {
        return $this->readAt;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }

    public function addAuthor(Author $author): self
    {
        if (!$this->authors->contains($author)) {
            $this->authors[] = $author;
        }

        return $this;
    }

    public function removeAuthor(Author $author): self
    {
        if ($this->authors->contains($author)) {
            $this->authors->removeElement($author);
        }

        return $this;
    }

    public function setAuthors($authors)
    {
        $this->authors = $authors;

        return $this;
    }

    /**
     * @param Category ...$categories
     *
     * @return void
     */
    public function updateCategories(Category ...$categories)
    {
        /** @var Category[]|ArrayCollection */
        $originalCategories = new ArrayCollection();
        foreach ($this->categories as $category) {
            $originalCategories->add($category);
        }

        // Remove categories
        foreach ($originalCategories as $originalCategory) {
            if (!\in_array($originalCategory, $categories)) {
                $this->removeCategory($originalCategory);
            }
        }

        // Add categories
        foreach ($categories as $newCategory) {
            if (!$originalCategories->contains(!$newCategory)) {
                $this->addCategory($newCategory);
            }
        }
    }

    public static function create(
        string $title,
        ?string $image,
        ?string $description,
        ?Score $score,
        ?DateTimeInterface $readAt,
        array $authors,
        array $categories
    ): self {
        $book = new self(
            Uuid::uuid4(),
            $title,
            $image,
            $description,
            $score,
            $readAt,
            new ArrayCollection($categories),
            new ArrayCollection($authors)
        );

        // event created book
        $book->addDomainEvent(new BookCreatedEvent($book->getId()));

        return $book;
    }

    public function updateAuthors(Author ...$authors)
    {
        /** @var Author[]|ArrayCollection */
        $originalAuthors = new ArrayCollection();
        foreach ($this->authors as $author) {
            $originalAuthors->add($author);
        }

        // Remove authors
        foreach ($originalAuthors as $originalAuthor) {
            if (!\in_array($originalAuthor, $authors)) {
                $this->removeAuthor($originalAuthor);
            }
        }

        // Add authors
        foreach ($authors as $newAuthor) {
            if (!$originalAuthors->contains($newAuthor)) {
                $this->addAuthor($newAuthor);
            }
        }
    }

    public function update(
        string $title,
        ?string $image,
        ?string $description,
        ?Score $score,
        ?DateTimeInterface $readAt,
        array $authors,
        array $categories
    ) {
        $this->title = $title;
        $this->image = $image;
        if (null !== $image) {
            $this->image = $image;
        }
        $this->description = $description;
        $this->score = $score;
        $this->readAt = $readAt;
        $this->updateCategories(...$categories);
        $this->updateAuthors(...$authors);
    }

    public function patch(array $data): self
    {
        if (array_key_exists('score', $data)) {
            $this->score = Score::create($data['score']);
        }

        if (array_key_exists('title', $data)) {
            $title = $data['title'];
            if (null === $title) {
                throw new DomainException('Title cannot be null');
            }
            $this->title = $title;
        }

        return $this;
    }

    public function isRead(): ?bool
    {
        return null === $this->readAt ? false : true;
    }

    public function markAsRead(DateTimeInterface $readAt): self
    {
        $this->readAt = $readAt;

        return $this;
    }

    public function __toString()
    {
        return $this->title ?? 'Libro';
    }
}
