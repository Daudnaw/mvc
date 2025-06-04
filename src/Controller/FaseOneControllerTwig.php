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

class FaseOneControllerTwig extends AbstractController
{
    #[Route("/proj/check/one/one", name: "check_one_one", methods: ['POST'])]
    public function checkOneOne(): Response
    {
        return $this->redirectToRoute('check_monkey');
    }

    #[Route("/proj/foldeone", name: "fold_one_one", methods: ['POST'])]
    public function foldOneOne(
        SessionInterface $session
    ): Response
    {
        $session->set("foldOne", '1');

        return $this->redirectToRoute('check_monkey');
    }

    #[Route("/proj/raise/one/one", name: "raise_one_one")]
    public function raiseOneOne(): Response
    {
        return $this->render('proj/raise_one_one.html.twig');
    }

    #[Route("/proj/check/monkey", name: "check_monkey", methods: ['GET','POST'])]
    public function checkMonkey(
        SessionInterface $session,
        Request $request
    ): Response
    {
        $totalRaise = $session->get('totalRaise');
        $raise = $request->request->get('raiseOne');

        $acountOne = $session->get('acountOne');
        $acountOne = $acountOne - $raise;
        $session->set("acountOne", $acountOne);

        $monkeyRaise = $raise + 10;
        $acountMonkey = $session->get('acountMonkey');
        $acountMonkey = $acountMonkey - $monkeyRaise;

        $session->set('acountMonkey', $acountMonkey);
        $session->set('totalRaise', $totalRaise + $monkeyRaise);

        $session->set('checkOne', '0');
        $session->set('checkTwo', '1');

        return $this->redirectToRoute('check_computer');
    }

    #[Route("/proj/check/computer", name: "check_computer")]
    public function checkComputer(
        SessionInterface $session
    ): Response
    {
        $poker = new Poker();
        $totalRaise = $session->get("totalRaise");
        $accountComputer = $session->get("acountComputer");
        $allPlayer = $session->get("allPlayer");
        $playerComputer = $allPlayer[2];
        $valueComputer = $poker->evaluateHand($playerComputer);

        $session->set("valueComputer", $valueComputer);

        if ($valueComputer >= 75) {
            $totalRaise += 100;
            $accountComputer -= $totalRaise;
        }

        if ($valueComputer >= 30 && $valueComputer <75) {
            $totalRaise += 50;
            $accountComputer -= $totalRaise;
        }

        if ($valueComputer <= 30) {
            $accountComputer -= $totalRaise;
        }

        $session->set('acountComputer', $accountComputer);
        $session->set('totalRaise', $totalRaise);

        $countRound = $session->get('countRound');

        $foldTwo = $session->get('foldTwo');
        if ($foldTwo == '2' && $countRound == 2) {
            return $this->redirectToRoute('show_down');
        }

        if ($countRound == 2) {
            return $this->redirectToRoute('second_display');
        }

        $session->set('countRound', 2);

       return $this->redirectToRoute('first_display');
    }

    #[Route("/proj/display/select", name: "first_select")]
    public function firstSelect(
        SessionInterface $session
    ): Response
    {
        $deck = New Game();
        $allPlayer = $session->get("allPlayer");

        $playerOne = $deck->getString($allPlayer[0]);
        $playerTwo = $deck->getString($allPlayer[3]);

        $foldOne = $session->get("foldOne");
        $foldTwo = $session->get("foldTwo");

        if ( $foldOne === '1') {
            $playerOne = null;
        }

        if ( $foldTwo === '2') {
            $playerTwo = null;
        }
        $playerMonkey = $deck->getString($allPlayer[1]);
        $playerComputer = $deck->getString($allPlayer[2]);

        $selectOne = $session->get('selectOne');
        $selectTwo = $session->get('selectTwo');
        $playerList = $session->get('playerList');

        $data = [
            'playerOne' => $playerOne,
            'playerMonkey' => $playerMonkey,
            'playerComputer' => $playerComputer,
            'playerTwo' => $playerTwo,
            'selectOne' => $selectOne,
            'selectTwo' => $selectTwo,
            'foldOne' => $foldOne,
            'foldTwo' => $foldTwo,
            'nameOne' => $playerList[0],
            'nameTwo' => $playerList[3]
        ];

        return $this->render('proj/select_one.html.twig', $data);
    }


    #[Route("/proj/fold/two/one", name: "fold_two_one", methods: ['POST'])]
    public function foldTwoOne(
        SessionInterface $session
    ): Response
    {
        $session->set("foldTwo", '2');
        $foldOne = $session->get('foldOne');

        if ($foldOne == '1') {
            $session->set('countRound', 2);
            return $this->redirectToRoute('monkey_random');
        }

       return $this->redirectToRoute('check_to_select');
    }

    #[Route("/proj/call/two/one", name: "call_two_one", methods: ['POST'])]
    public function callTwoOne(
        SessionInterface $session
    ): Response
    {
        $totalRaise = $session->get('totalRaise');
        $acountTwo = $session->get('acountTwo');
        $acountTwo -= $totalRaise;

        $session->set('acountTwo', $acountTwo);

       return $this->redirectToRoute('check_to_select');
    }

    #[Route("/proj/raise/two/one", name: "raise_from_two_one")]
    public function raiseTwoOne(): Response
    {
       return $this->render('proj/raise_two_one.html.twig');
    }

    #[Route("/proj/raise/two/in", name: "raise_two_one", methods: ['POST'])]
    public function raiseTwoPost(
        SessionInterface $session,
        Request $request
    ): Response
    {
        $raise = $request->request->get('raiseTwo');

        $totalRaise = $session->get('totalRaise');
        $totalRaise += $raise;

        $acountTwo = $session->get('acountTwo');
        $acountTwo -= $totalRaise;

        $session->set("acountTwo", $acountTwo);
        $session->set('totalRaise', $totalRaise);

       return $this->redirectToRoute('check_to_select');
    }
}
