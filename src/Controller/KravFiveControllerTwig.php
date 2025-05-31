<?php

namespace App\Controller;

//use App\Card\Poker;
//use App\Card\Deck;
//use App\Card\Computer;
//use App\Card\Game;

//use App\Entity\Acount;
//use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class KravFiveControllerTwig extends AbstractController
{
    #[Route("/proj/about", name: "proj_about")]
    public function projAbout(): Response
    {
        //render to report krav five
       return $this->render('proj/proj_about.html.twig');
    }

    #[Route("/proj/about/database", name: "krav_five")]
    public function showFive(): Response
    {
        //render to report krav five
       return $this->render('proj/krav_five.html.twig');
    }

}
