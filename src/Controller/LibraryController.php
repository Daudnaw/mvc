<?php

namespace App\Controller;

use App\Entity\Library;
use App\Repository\LibraryRepository;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class LibraryController extends AbstractController
{
    #[Route('/library', name: 'app_library')]
    public function index(): Response
    {
        return $this->render('library/index.html.twig', [
            'controller_name' => 'LibraryController',
        ]);
    }

    #[Route('/book/create', name: 'book_create')]
    public function createBook(
    ): Response {

        return $this->render('library/book_create.html.twig');
    }

    #[Route('/library/create', name: 'library_create', methods:['POST'])]
    public function createLibrary(
        Request $request,
        ManagerRegistry $doctrine
    ): Response {
        $entityManager = $doctrine->getManager();

        // Getting form data from POST
        $title = $request->request->get('title');
        $writer = $request->request->get('writer');
        $isbn = $request->request->get('isbn');
        $image = $request->request->get('image');

        $book = new Library();
        $book->setTitle($title);
        $book->setIsbn($isbn);
        $book->setWriter($writer);
        $book->setImage($image);

        // tell Doctrine you want to (eventually) save the Product
        // (no queries yet)
        $entityManager->persist($book);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Book created, successfully!'
        );

        return $this->redirectToRoute('library_view_all');
    }

    #[Route('/library/show/{id}', name: 'library_by_id')]
    public function showLibraryById(
        LibraryRepository $libraryRepository,
        int $id
    ): Response {
        $book = $libraryRepository
            ->find($id);

        $data = [
            'book' => $book
        ];

        return $this->render('library/single_book.html.twig', $data);
    }

    #[Route('/library/delete/{id}', name: 'library_delete_by_id')]
    public function deleteLibraryById(
        ManagerRegistry $doctrine,
        int $id
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Library::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }

        $entityManager->remove($book);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Book deleted, successfully!'
        );

        return $this->redirectToRoute('library_view_all');
    }

    #[Route('/book/update/{id}', name: 'book_update')]
    public function updateBook(
        LibraryRepository $libraryRepository,
        int $id
    ): Response {

        $book = $libraryRepository
            ->find($id);

        $data = [
            'book' => $book
        ];

        return $this->render('library/book_update.html.twig', $data);
    }

    #[Route('/library/update', name: 'library_update', methods:['POST'])]
    public function updateLibrary(
        Request $request,
        ManagerRegistry $doctrine
    ): Response {
        $entityManager = $doctrine->getManager();
        $id = $request->request->get('id');
        $book = $entityManager->getRepository(Library::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }

        // Getting form data from POST
        $title = $request->request->get('title');
        $writer = $request->request->get('writer');
        $isbn = $request->request->get('isbn');
        $image = $request->request->get('image');

        $book->setTitle($title);
        $book->setIsbn($writer);
        $book->setWriter($isbn);
        $book->setImage($image);

        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Book updated, successfully!'
        );

        return $this->redirectToRoute('library_view_all');
    }

    #[Route('/library/view', name: 'library_view_all')]
    public function viewAllLibrary(
        LibraryRepository $libraryRepository
    ): Response {
        $books = $libraryRepository->findAll();

        $data = [
            'books' => $books
        ];

        return $this->render('library/view.html.twig', $data);
    }

    #[Route('api/library/books')]
    public function showAllLibrary(
        LibraryRepository $libraryRepository
    ): Response {
        $books = $libraryRepository
            ->findAll();
    
        //return $this->json($books);
        $response = $this->json($books);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route('api/library/book/{isbn}')]
    public function searchBook(
        LibraryRepository $libraryRepository,
        string $isbn
    ): Response {

        $book = $libraryRepository
            ->findByIsbn($isbn);

        $response = $this->json($book);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
