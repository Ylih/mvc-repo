<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LibraryController extends AbstractController
{
    #[Route('/library', name: 'app_library', methods: ['GET'])]
    public function showBooks(BookRepository $bookRepo): Response
    {
        $books = $bookRepo->findAll();

        $bookArr = [];
        foreach ($books as $book) {
            $bookArr[] = [
                "id" => $book->getId(),
                "title"=> $book->getTitle(),
                "isbn" => $book->getIsbn(),
                "author" => $book->getAuthor(),
                "img" => $book->getImage(),
            ];
        }

        $data = [
            "books" => $bookArr,
        ];

        return $this->render('library/index.html.twig', $data);
    }

    #[Route('/library/create', name: 'create_book_landing', methods: ['GET'])]
    public function createLanding(): Response
    {
        return $this->render('library/create.html.twig');
    }

    #[Route('/library/create', name: 'book_create', methods: ['POST'])]
    public function createBook(
        Request $req,
        ManagerRegistry $doctrine
    ): Response {
        $entityManager = $doctrine->getManager();

        /** @var string $title */
        $title = $req->request->get('title');

        /** @var string $author */
        $author = $req->request->get('author');

        /** @var int $isbn */
        $isbn = $req->request->get('isbn');

        /** @var string $imageUrl */
        $imageUrl = $req->request->get('image');

        $book = new Book();
        $book->setTitle($title);
        $book->setAuthor($author);
        $book->setIsbn($isbn);
        $book->setImage($imageUrl);

        $entityManager->persist($book);

        $entityManager->flush();

        return $this->redirectToRoute('app_library');
    }

    #[Route('/library/show/{bookId}', name: 'book_show_specific', methods: ['GET'])]
    public function showBook(BookRepository $bookRepo, int $bookId): Response
    {
        $book = $bookRepo->find($bookId);

        if (!$book) {
            throw new Exception("There is no book with this ID.");
        }

        $bookData = [
            "id" => $book->getId(),
            "title"=> $book->getTitle(),
            "isbn" => $book->getIsbn(),
            "author" => $book->getAuthor(),
            "img" => $book->getImage(),
        ];

        $data = [
            "book" => $bookData,
        ];

        return $this->render('library/show.html.twig', $data);
    }

    #[Route('/library/update/{bookId}', name: 'book_update_get', methods: ['GET'])]
    public function getUpdateBook(BookRepository $bookRepo, int $bookId): Response
    {
        $book = $bookRepo->find($bookId);

        if (!$book) {
            throw new Exception("There is no book with this ID.");
        }

        $bookData = [
            "id" => $book->getId(),
            "title"=> $book->getTitle(),
            "isbn" => $book->getIsbn(),
            "author" => $book->getAuthor(),
            "img" => $book->getImage(),
        ];

        $data = [
            "book" => $bookData,
        ];

        return $this->render('library/update.html.twig', $data);
    }

    #[Route('/library/update/{bookId}', name: 'book_update_post', methods: ['POST'])]
    public function updateBook(BookRepository $bookRepo, Request $req, int $bookId): Response
    {
        $book = $bookRepo->find($bookId);

        if (!$book) {
            throw new Exception("There is no book with this ID.");
        }

        /** @var string $title */
        $title = $req->request->get('title');

        /** @var string $author */
        $author = $req->request->get('author');

        /** @var int $isbn */
        $isbn = $req->request->get('isbn');

        /** @var string $imageUrl */
        $imageUrl = $req->request->get('image');

        $book->setTitle($title);
        $book->setAuthor($author);
        $book->setIsbn($isbn);
        $book->setImage($imageUrl);

        $bookRepo->save($book, true);

        return $this->redirectToRoute('book_show_specific', ['bookId'=>$bookId]);
    }

    #[Route('/library/delete/{bookId}', name: 'book_delete_get', methods: ['GET'])]
    public function getDeleteBook(BookRepository $bookRepo, int $bookId): Response
    {
        $book = $bookRepo->find($bookId);

        if (!$book) {
            throw new Exception("There is no book with this ID.");
        }

        $bookData = [
            "id" => $book->getId(),
            "title"=> $book->getTitle(),
            "isbn" => $book->getIsbn(),
            "author" => $book->getAuthor(),
            "img" => $book->getImage(),
        ];

        $data = [
            "book" => $bookData,
        ];

        return $this->render('library/delete.html.twig', $data);
    }

    #[Route('/library/delete/{bookId}', name: 'book_delete_post', methods: ['POST'])]
    public function deleteBook(BookRepository $bookRepo, int $bookId): Response
    {
        $book = $bookRepo->find($bookId);

        if (!$book) {
            throw new Exception("There is no book with this ID.");
        }

        $bookRepo->remove($book, true);

        return $this->redirectToRoute('app_library');
    }
}
