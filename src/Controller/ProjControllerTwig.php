<?php

namespace App\Controller;

//use App\Card\Card;
//use App\Card\CardGraphic;
use App\Card\Poker;
use App\Card\Deck;
use App\Card\Computer;
use App\Card\Game;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProjControllerTwig extends AbstractController
{
    #[Route("/proj", name: "proj")]
    public function projLanding(
    ): Response {


        return $this->render('proj/proj.html.twig');
    }

    #[Route("/proj/start", name: "proj_start")]
    public function projStart(): Response
    {

        return $this->render('proj/proj_start.html.twig');
    }

    #[Route("/proj/number", name: "proj_number", methods: ['POST'])]
    public function projNumber(
        Request $request,
        SessionInterface $session
    ): Response
    {

        $number = $request->request->get('number');

        $deck = New Deck();

        $session->set("deck", $deck);
        $session->set("numberPlayers", $number);
        $session->set("playerOne", []);
        $session->set("playerMonkey", []);
        $session->set("playerComputer", []);
        $session->set("playerTwo", []);

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

        if ($number == 1) {
            $playerOne = array_merge($playerOne, $deck->drawCards(5));
            $playerMonkey = array_merge($playerMonkey, $deck->drawCards(5));
            $playerComputer = array_merge($playerComputer, $deck->drawCards(5));
            $playerTwo = null;
        }

        if ($number == 2) {
            $playerOne = array_merge($playerOne, $deck->drawCards(5));
            $playerMonkey = array_merge($playerMonkey, $deck->drawCards(5));
            $playerComputer = array_merge($playerComputer, $deck->drawCards(5));
            $playerTwo = array_merge($playerTwo, $deck->drawCards(5));
        }

        $allPlayer = [$playerOne, $playerMonkey, $playerComputer, $playerTwo];
        $session->set("allPlayer", $allPlayer);

       // $show = [];

        //$playerOne [] = array_merge($playerOne, $deck->drawCards(5));

        
         return $this->redirectToRoute('first_display');
       //return $this->render('proj/trial.html.twig', $data);
    }

    #[Route("/proj/display", name: "first_display")]
    public function firstDisplay(
        SessionInterface $session
    ): Response
    {
        $deck = New Game();
        $allPlayer = $session->get("allPlayer");
       // $playerOne = $allPlayer[0];
       // $playerOne = $deck->getString($playerOne);
        $playerOne = $deck->getString($allPlayer[0]);
        $playerMonkey = $deck->getString($allPlayer[1]);
        $playerComputer = $deck->getString($allPlayer[2]);
        $playerTwo = $deck->getString($allPlayer[3]);
        //$allPlayer = [$playerOne, $playerMonkey];


        $data = [
            'playerOne' => $playerOne,
            'playerMonkey' => $playerMonkey,
            'playerComputer' => $playerComputer,
            'playerTwo' => $playerTwo
        ];
        //return $this->render('proj/trial.html.twig', $data);
        return $this->render('proj/first_display.html.twig', $data);
    }

    #[Route("/proj/dicardone", name: "discard_one", methods: ['POST'])]
    public function discardOne(
        SessionInterface $session,
        Request $request
    ): Response
    {
        $deck = $session->get('deck');
        $allPlayer = $session->get("allPlayer");
       // $playerOne = $allPlayer[0];
       // $playerOne = $deck->getString($playerOne);
        $playerOne = $allPlayer[0];
        //dd($request->request->all());
        $input = $request->request->all();
        $indexes = $input['cardsToChange'] ?? [];
        //$playerOne = $session->get('playerOne');

        foreach ($indexes as $index) {
            unset($playerOne[(int)$index]);
        }

        $playerOne = array_values($playerOne);
        $playerOne = array_merge($playerOne, $deck->drawCards(count($indexes)));
        
        $allPlayer[0] = $playerOne;
        $session->set('allPlayer', $allPlayer);

       // return $this->render('proj/trial.html.twig', $data);
       return $this->redirectToRoute('monkey_random');
    }

    #[Route("/proj/monkey", name: "monkey_random")]
    public function monkeyRandom(
        SessionInterface $session
    ): Response
    {
        $deck = $session->get('deck');
        $allPlayer = $session->get("allPlayer");
        $playerMonkey = $allPlayer[1];
        $numCards = rand(1, 5);

        $cardIndexes = array_rand($playerMonkey, $numCards);
        $cardIndexes = (array) $cardIndexes;

        foreach ($cardIndexes as $index)  {
            unset($playerMonkey[$index]);
        }

        $playerMonkey = array_values($playerMonkey);
        $playerMonkey = array_merge($playerMonkey, $deck->drawCards($numCards));

        
        $allPlayer[1] = $playerMonkey;
        $session->set('allPlayer', $allPlayer);

       // return $this->render('proj/trial.html.twig', $data);
       return $this->redirectToRoute('computer_smart');
    }

    #[Route("/proj/computer", name: "computer_smart")]
    public function computerSmart(
        SessionInterface $session
    ): Response
    {
        $deck = $session->get('deck');
        $poker = new Poker();
        $computer = new Computer($poker, $deck);

        $allPlayer = $session->get("allPlayer");
        $playerComputer = $allPlayer[2];

        $afterDecision = $computer->play($playerComputer);
        $allPlayer[2] = $afterDecision;
        $session->set('allPlayer', $allPlayer);

       // return $this->render('proj/trial.html.twig', $data);
       return $this->redirectToRoute('first_display');
    }


    #[Route("/proj/trial", name: "trial")]
    public function trial(
        SessionInterface $session
    ): Response
    {
        
        $deck = new Deck();
        $drawn = $deck->drawCards(5);

        $poker = New Poker();
        $computer = new Computer($poker, $deck);
        $high = $poker->getHighCard($drawn);
        $rankFreq = $poker->getRankFreq($drawn);
        $isPair = $poker->isPair($drawn);
        $isTwoPair = $poker->isTwoPair($drawn);
        $isThree = $poker->isThree($drawn);
        $isFour = $poker->isFour($drawn);
        $isFullHouse = $poker->isFullHouse($drawn);

        $list = [$isPair, $isTwoPair, $isThree, $isFour, $isFullHouse];

        
        $afterIntell = $computer->play($drawn);

        $data = [
            "poker" => $drawn,
            "number" => $high,
            "rankfreq" => $list,
            "smart" => $afterIntell
        ];

       // return $this->render('proj/trial.html.twig', $data);
       return $this->render('proj/trial.html.twig', $data);
    }
}
