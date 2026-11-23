<?php

namespace App\Service;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;

class BookService
{
    public function __construct(
        private readonly BookRepository $bookRepository,
        private readonly EntityManagerInterface $em
    ) {}

    public function getBookByTitle(string $title): ?Book
    {
        return $this->bookRepository->findOneBy(["title" => $title]);
    }

    public function getBooks(): array
    {
        return $this->bookRepository->findAll() ?? [];
    }

    public function getBookById(int $id): ?Book
    {
        return $this->bookRepository->find($id);
    }

    public function getBooksByTitle(string $title): array
    {
        return $this->bookRepository->findByTitle($title);
    }

    public function getBooksByAfterPublishAt(string $date): array
    {
        return $this
            ->bookRepository
            ->findPublishedAfter(
                new \DateTimeImmutable($date)
            );
    }

    public function getBooksByCategoryName(string $categoryName): array
    {
        return $this->bookRepository->findByCategory($categoryName);
    }

    public function getBooksByCategoriesName(string $categories): array
    {
        $categories = explode(',', $categories);
        return $this->bookRepository->findByCategories($categories);
    }

    public function getBooksByCategoriesNameV2(array $categories): array
    {

        return $this->bookRepository->findByCategories($categories);
    }

    public function saveBook(Book $book): void
    {
        $this->em->persist($book);

        $this->em->flush();
    }

    public function deleteBook(Book $book): void
    {
        $this->em->remove($book);
        $this->em->flush();
    }
}
