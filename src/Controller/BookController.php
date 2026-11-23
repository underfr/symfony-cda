<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Service\BookService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class BookController extends AbstractController
{
    public function __construct(
        private readonly BookService $bookService
    ) {}

    #[Route(path: '/books', name: 'app_books')]
    public function showallBooks(): Response
    {
        $books = $this->bookService->getBooks();

        return $this->render('book/show_all_books.html.twig', [
            'books' => $books
        ]);
    }

    #[Route(path: '/books/{id<\d+>}', name: 'app_book')]
    public function showBookById(int $id): Response
    {
        $book = $this->bookService->getBookById($id);

        if (!$book) {
            throw $this->createNotFoundException('Livre introuvable');
        }

        return $this->render('book/show_book_id.html.twig', ['book' => $book]);
    }

    #[Route(path: '/books/search/{title}', name: 'app_book_search')]
    public function showBooksByTitle(string $title): Response
    {
        $books = $this->bookService->getBooksByTitle($title);

        return $this->render('book/show_all_books.html.twig', [
            'books' => $books
        ]);
    }

    #[Route(path:'/books/recent/{date}', name: 'app_book_recent')]
    public function showBooksByAfterPublishAt(string $date): Response
    {
        $books = $this->bookService->getBooksByAfterPublishAt($date);

        return $this->render('book/show_all_books.html.twig', [
            'books' => $books
        ]);
    }

    #[Route(path:'/books/category/{name}', name: 'app_book_category')]
    public function showBooksByCategoryName(string $name): Response
    {
        $books = $this->bookService->getBooksByCategoryName($name);

        return $this->render('book/show_all_books.html.twig', [
            'books' => $books
        ]);
    }

    #[Route(path: '/books/{id<\d+>}/edit', name: 'app_book_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'êtes pas autorisé')]
    public function edit(int $id, Request $request): Response
    {
        $book = $this->bookService->getBookById($id);

        if (!$book) {
            throw $this->createNotFoundException('Livre introuvable');
        }

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->bookService->saveBook($book);

            $this->addFlash('notice', 'Le livre ' . $book->getTitle() . ' a été modifié');

            return $this->redirectToRoute('app_book', ['id' => $book->getId()]);
        }

        return $this->render('book/book_form.html.twig', [
            'form' => $form,
            'is_edit' => true,
        ]);
    }

    #[Route(path:'/books/categories/{categories}', name: 'app_book_categories')]
    public function showBooksByCategoriesName(string $categories): Response
    {
        $books = $this->bookService->getBooksByCategoriesName($categories);
        return $this->render('book/show_all_books.html.twig', [
            'books' => $books
        ]);
    }

    #[Route(path:'/boooks', name:'app_boooks')]
    public function test(
        #[MapQueryParameter] array $categories
    ): Response {

        $book = $this->bookService->getBooksByCategoriesNameV2($categories);

        return $this->render('book/show_all_books.html.twig', [
            'books' => $book
        ]);
    }

    #[Route(path:'/book/new', name:'app_book_new')]
    #[IsGranted('ROLE_ADMIN', message:'Vous n\'etes pas autorisé')]
    public function new(Request $request): Response
    {
        $book = new Book();

        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->bookService->saveBook($book);

            $this->addFlash('notice', 'Le livre ' . $book->getTitle() . ' a été ajouté');

            return $this->redirectToRoute('app_book', ['id' => $book->getId()]);
        }

        return $this->render('book/book_form.html.twig', [
            'form'=> $form,
            'is_edit' => false,
        ]);
    }

    #[Route(path: '/books/{id<\d+>}/delete', name: 'app_book_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'êtes pas autorisé')]
    public function delete(int $id, Request $request): Response
    {
        $book = $this->bookService->getBookById($id);

        if (!$book) {
            throw $this->createNotFoundException('Livre introuvable');
        }

        if (!$this->isCsrfTokenValid('delete_book_' . $book->getId(), $request->getPayload()->getString('_token'))) {
            throw $this->createAccessDeniedException('Jeton CSRF invalide');
        }

        $title = $book->getTitle();
        $this->bookService->deleteBook($book);

        $this->addFlash('notice', 'Le livre ' . $title . ' a été supprimé');

        return $this->redirectToRoute('app_books');
    }
}
