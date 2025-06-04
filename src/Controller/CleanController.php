<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Acount;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class CleanController extends AbstractController
{
   #[Route("/proj/clear", name: "proj_clear")]
    public function projClear(
        ManagerRegistry $doctrine,
        SessionInterface $session,
        ): Response
    {
        $entityManager = $doctrine->getManager();

        $entityManager->createQuery('DELETE FROM App\Entity\Acount')->execute();

        $entityManager->createQuery('DELETE FROM App\Entity\Customer')->execute();

        $connection = $entityManager->getConnection();


        $connection->executeStatement("DELETE FROM sqlite_sequence WHERE name='acount'");
        $connection->executeStatement("DELETE FROM sqlite_sequence WHERE name='customer'");

        $session->clear();
        return $this->redirectToRoute('player_create');;
    }


}
