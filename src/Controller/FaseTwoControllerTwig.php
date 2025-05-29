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

class FaseTwoControllerTwig extends AbstractController
{
    #[Route("/proj/display/second", name: "second_display")]
    public function secondDisplay(
        SessionInterface $session
    ): Response
    {

        $deck = New Game();
        $allPlayer = $session->get("allPlayer");

       $playerOne = $deck->getString($allPlayer[0]);
       $playerTwo = $deck->getString($allPlayer[3]);

       $foldOne = $session->get("foldOne");
       $foldTwo = $session->get("foldTwo");
       $check = $session->get("checkOne");
       $checkTwo = $session->get("checkTwo");

        if ( $foldOne === '1') {
            $playerOne = null;
        }
        if ( $foldTwo === '2') {
            $playerTwo = null;
        }
        $playerMonkey = $deck->getString($allPlayer[1]);
        $playerComputer = $deck->getString($allPlayer[2]);

        $data = [
            'playerOne' => $playerOne,
            'playerMonkey' => $playerMonkey,
            'playerComputer' => $playerComputer,
            'playerTwo' => $playerTwo,
            'checkOne' => $check,
            'checkTwo' => $checkTwo
        ];

        return $this->render('proj/check_two.html.twig', $data);
    }

     #[Route("/proj/check/one/two", name: "call_one_two", methods: ['POST'])]
    public function callOneTwo(
        SessionInterface $session
    ): Response
    {
        $totalRaise = $session->get('totalRaise');
        $acountOne = $session->get('acountOne');

        $acountOne -= $totalRaise;

        $session->set('acountOne', $acountOne);

        return $this->redirectToRoute('check_to_two');
    }

    #[Route("/proj/fold/one/two", name: "fold_one_two", methods: ['POST'])]
    public function foldOneTwo(
        SessionInterface $session
    ): Response
    {
        $session->set("foldOne", '1');

        return $this->redirectToRoute('check_to_two');
    }

    #[Route("/proj/raise/one/two", name: "raise_one_two")]
    public function raiseOneTwo(
        SessionInterface $session
    ): Response
    {
        return $this->render('proj/raise_one_two.html.twig');
    }

    #[Route("/proj/check/to/two", name: "check_to_two")]
    public function checkToTwo(
        SessionInterface $session,
        Request $request
    ): Response
    {
        $raise = $request->request->get('raiseOne');

        $totalRaise = $session->get('totalRaise');
        $totalRaise += $raise;

        $acountOne = $session->get('acountOne');
        $acountOne -= $totalRaise;

        $session->set("acountOne", $acountOne);
        $session->set('totalRaise', $totalRaise);

        return $this->redirectToRoute('check_monkey');
    }

    #[Route("/proj/fold/two/two", name: "fold_two_two", methods: ['POST'])]
    public function foldTwoTwo(
        SessionInterface $session
    ): Response
    {
        $session->set("foldTwo", '2');

       return $this->redirectToRoute('show_down');
    }

    #[Route("/proj/call/two/two", name: "call_two_two", methods: ['POST'])]
    public function callTwoTwo(
        SessionInterface $session
    ): Response
    {
        $totalRaise = $session->get('totalRaise');
        $acountTwo = $session->get('acountTwo');
        $acountTwo -= $totalRaise;

        $session->set('acountTwo', $acountTwo);

       return $this->redirectToRoute('show_down');
    }
}
