<?php

namespace App\Controller;

use App\Entity\Library;
use App\Repository\LibraryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class LibraryControllerC extends AbstractController
{
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

        $title = $request->request->get('title');
        $writer = $request->request->get('writer');
        $isbn = $request->request->get('isbn');
        $image = $request->request->get('image');

        $book = new Library();
        $book->bookSetter($title, $writer, $isbn, $image);

        $entityManager->persist($book);

        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Book created, successfully!'
        );

        return $this->redirectToRoute('library_view_all');
    }

}
