<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LibraryControllerJson extends AbstractController
{
    #[Route('api/library/books', name: 'library_json', methods: ['GET'])]
    public function showBooks(BookRepository $bookRepo): Response 
    {
        $books = $bookRepo->findAll();

        $res = $this->json($books);
        $res->setEncodingOptions(
            $res->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $res;
    }

    #[Route('api/library/book/{isbn}', name: 'specific_book_json', methods: ['GET'])]
    public function showBook(BookRepository $bookRepo, int $isbn): Response
    {
        $book = $bookRepo->findOneBy(["isbn"=>$isbn]);

        $res = $this->json($book);
        $res->setEncodingOptions(
            $res->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $res;
    }
}
