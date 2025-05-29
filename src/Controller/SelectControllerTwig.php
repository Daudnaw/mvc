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

class SelectControllerTwig extends AbstractController
{
    #[Route("/proj/check/to/select", name: "check_to_select")]
    public function checkToSelect(
        SessionInterface $session
    ): Response
    {
       $session->set('selectOne', '1');
       $session->set('selectTwo', '0');

       $foldOne = $session->get('foldOne');
       if ($foldOne == '1') {
            $session->set('selectOne', '0');
            $session->set('selectTwo', '1');
            return $this->redirectToRoute('monkey_random');
       }

       return $this->redirectToRoute('first_select');
    }

    #[Route("/proj/dicardone", name: "discard_one", methods: ['POST'])]
    public function discardOne(
        SessionInterface $session,
        Request $request
    ): Response
    {
        $deck = $session->get('deck');
        $allPlayer = $session->get("allPlayer");

        $playerOne = $allPlayer[0];

        $input = $request->request->all();
        $indexes = $input['cardsToChange'] ?? [];


        foreach ($indexes as $index) {
            unset($playerOne[(int)$index]);
        }

        $playerOne = array_values($playerOne);
        $playerOne = array_merge($playerOne, $deck->drawCards(count($indexes)));
        
        $allPlayer[0] = $playerOne;
        $session->set('allPlayer', $allPlayer);

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

        $session->set("checkOne", '1');
        $session->set("checkTwo", '0');

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

        $session->set('selectOne', '0');
        $session->set('selectTwo', '1');
        $session->set("checkOne", '1');
        $session->set("checkTwo", '0');

        $foldTwo = $session->get('foldTwo');
        $foldOne = $session->get('foldOne');

        if ( $foldOne == '1' && $foldTwo == '2') {
            return $this->redirectToRoute('check_monkey');
        }
        if ($foldTwo == '2') {
            $session->set('checkOne', '1');
            return $this->redirectToRoute('second_display');
        }

       return $this->redirectToRoute('first_select');
    }

    #[Route("/proj/dicard/two", name: "discard_two", methods: ['POST'])]
    public function discardTwo(
        SessionInterface $session,
        Request $request
    ): Response
    {
        $deck = $session->get('deck');
        $allPlayer = $session->get("allPlayer");

        $playerTwo = $allPlayer[3];

        $input = $request->request->all();
        $indexes = $input['cardsToChange'] ?? [];

        foreach ($indexes as $index) {
            unset($playerTwo[(int)$index]);
        }

        $playerTwo = array_values($playerTwo);
        $playerTwo = array_merge($playerTwo, $deck->drawCards(count($indexes)));
        
        $allPlayer[3] = $playerTwo;
        $session->set('allPlayer', $allPlayer);

        $foldOne = $session->get('foldOne');
        if ($foldOne == '1') {
            $session->set("checkTwo", '1');
        }

       return $this->redirectToRoute('second_display');
    }
}
