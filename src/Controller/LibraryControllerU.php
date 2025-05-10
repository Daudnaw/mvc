<?php

namespace App\Controller;

use App\Entity\Library;
use App\Repository\LibraryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class LibraryControllerU extends AbstractController
{
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

        $title = $request->request->get('title');
        $writer = $request->request->get('writer');
        $isbn = $request->request->get('isbn');
        $image = $request->request->get('image');

        $book->bookSetter($title, $writer, $isbn, $image);

        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Book updated, successfully!'
        );

        return $this->redirectToRoute('library_view_all');
    }

}
