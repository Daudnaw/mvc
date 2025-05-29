<?php

namespace App\Controller;

use App\Card\Poker;
use App\Card\Deck;
use App\Card\Computer;
use App\Card\Game;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AcountRepository;

class FirstDisplayControllerTwig extends AbstractController
{
    #[Route("/proj", name: "proj")]
    public function projLanding(
    ): Response {


        return $this->render('proj/proj.html.twig');
    }

    #[Route("/proj/start", name: "proj_start")]
    public function projStart(
        SessionInterface $session,
        AcountRepository $acountRepository
    ): Response
    {
        $one = $acountRepository->findBalanceById(4);
        $two = $acountRepository->findBalanceById(5);
        $monkey = $acountRepository->findBalanceById(6);
        $computer = $acountRepository->findBalanceById(7);
        $house = $acountRepository->findBalanceById(8);

        $session->set("acountOne", $one);
        $session->set("acountTwo", $two);
        $session->set("acountComputer", $computer);
        $session->set("acountMonkey", $monkey);
        $session->set("acountHouse", $house);
        $session->set("countRound", 0);

        return $this->redirectToRoute('proj_number');
    }

    #[Route("/proj/number", name: "proj_number")]
    public function projNumber(
        SessionInterface $session
    ): Response
    {

        $number = 2;

        $deck = New Deck();

        $session->set("deck", $deck);
        $session->set("numberPlayers", $number);
        $session->set("playerOne", []);
        $session->set("playerMonkey", []);
        $session->set("playerComputer", []);
        $session->set("playerTwo", []);
        $session->set("checkOne", '1');
        $session->set("checkTwo", '0');

        return $this->redirectToRoute('session_setter');
    }

    #[Route("/proj/session", name: "session_setter")]
    public function projSession(
        SessionInterface $session
    ): Response
    {
        $deck = $session->get("deck");
        $number = $session->get("numberPlayers");
        $playerOne = $session->get("playerOne");
        $playerMonkey = $session->get("playerMonkey");
        $playerComputer = $session->get("playerComputer");
        $playerTwo = $session->get("playerTwo");
        $accountOne = $session->get("acountOne");
        $accountTwo = $session->get("acountTwo");
        $accountComputer = $session->get("acountComputer");
        $accountMonkey = $session->get("acountMonkey");
        $accountHouse = $session->get("acountHouse");

        if ($number == 1) {
            $playerOne = array_merge($playerOne, $deck->drawCards(5));
            $playerMonkey = array_merge($playerMonkey, $deck->drawCards(5));
            $playerComputer = array_merge($playerComputer, $deck->drawCards(5));
            $playerTwo = null;
            $accountOne = $accountOne - 50;
            $accountMonkey = $accountMonkey - 50;
            $accountComputer = $accountComputer - 50;
            $accountHouse = $accountHouse + 150;
        }

        if ($number == 2) {
            $playerOne = array_merge($playerOne, $deck->drawCards(5));
            $playerMonkey = array_merge($playerMonkey, $deck->drawCards(5));
            $playerComputer = array_merge($playerComputer, $deck->drawCards(5));
            $playerTwo = array_merge($playerTwo, $deck->drawCards(5));
            $accountOne = $accountOne-50;
            $accountTwo = $accountTwo-50;
            $accountMonkey = $accountMonkey-50;
            $accountComputer = $accountComputer-50;
            $accountHouse = $accountHouse + 200;
        }

        $allPlayer = [$playerOne, $playerMonkey, $playerComputer, $playerTwo];
        $session->set("allPlayer", $allPlayer);

        $session->set("acountOne", $accountOne);
        $session->set("acountTwo", $accountTwo);
        $session->set("acountMonkey", $accountMonkey);
        $session->set("acountComputer", $accountComputer);
        $session->set("acountHouse", $accountHouse);

         return $this->redirectToRoute('first_display');
    }

    #[Route("/proj/display", name: "first_display")]
    public function firstDisplay(
        SessionInterface $session
    ): Response
    {
        $deck = New Game();
        $allPlayer = $session->get("allPlayer");

        $playerOne = $deck->getString($allPlayer[0]);
        $foldOne = $session->get("foldOne");

        if ( $foldOne === '1') {
            $playerOne = null;
        }
        $playerMonkey = $deck->getString($allPlayer[1]);
        $playerComputer = $deck->getString($allPlayer[2]);
        $playerTwo = $deck->getString($allPlayer[3]);

        $checkOne = $session->get('checkOne');
        $checkTwo = $session->get('checkTwo');

        $data = [
            'playerOne' => $playerOne,
            'playerMonkey' => $playerMonkey,
            'playerComputer' => $playerComputer,
            'playerTwo' => $playerTwo,
            'checkOne' => $checkOne,
            'checkTwo' => $checkTwo
        ];
        return $this->render('proj/check_one.html.twig', $data);
    }

}
