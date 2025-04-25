<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\Deck;
use App\Card\Game;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CardControllerTwig extends AbstractController
{
    #[Route("/card", name: "card")]
    public function card(
        SessionInterface $session
    ): Response {
        if ($session->get("newDeck") == []) {
            $deck = new Deck();
            $session->set("newDeck", $deck);
        }
        //$deck = new Deck();

        //$session->set("newDeck", $deck);
        //going to main card with links to undersidor
        return $this->render('card/card.html.twig');
    }

    #[Route("/card/deck", name: "card_deck")]
    public function cardDeck(
        SessionInterface $session
    ): Response {
        $deck = $session->get("newDeck");
        //$deck = new Deck();
        //$session->set("newDeck", $deck);

        $data = [
            "newDeck" => $deck->getString()
        ];

        return $this->render('card/card_deck.html.twig', $data);
    }

    #[Route("/card/shuffle", name: "shuffle")]
    public function shuffleDeck(
        SessionInterface $session
    ): Response {
        $deck = new Deck();
        $session->set("newDeck", $deck);
        $shuffled = $deck->shuffleDeck();

        $data = [
            "shuffleDeck" => $deck->getString($shuffled)
        ];

        return $this->render('card/shuffle_deck.html.twig', $data);
    }

    #[Route("/card/deck/draw", name: "deck_draw")]
    public function deckDraw(
        SessionInterface $session
    ): Response {
        $deck = $session->get("newDeck");
        $drawn = $deck->drawCard();

        $data = [
            "count" => $deck->getCount(),
            "drawCard" => $drawn->getAsString()
        ];

        return $this->render('card/drawn_deck.html.twig', $data);
    }

    #[Route("/card/deck/draw/{num<\d+>}", name: "num_draw")]
    public function numDraw(
        int $num,
        SessionInterface $session
    ): Response {

        $deck = $session->get("newDeck");
        $drawn = $deck->drawCards($num);

        $drawnCards = [];
        foreach ($drawn as $card) {
            $drawnCards[] = $card->getAsString();
        }

        $data = [
            "count" => $deck->getCount(),
            "drawCard" => $drawnCards
        ];
        //"drawCard" => $drawn->getString($drawn)
        return $this->render('card/drawn_deck.html.twig', $data);
    }

    #[Route("/game", name: "game")]
    public function game(
        SessionInterface $session
    ): Response {
        $twentyNew = $session->get("twentyNew");

        if (!$twentyNew || $twentyNew->getCount() === 0) {
            $twentyNew = new Game();
            $session->set("twentyNew", $twentyNew);
        }

        $session->set("drawnCards", []);
        $session->set("bankCards", []);
        $session->set("playerTotal", 0);
        $session->set("bankTotal", 0);
        $session->set('winner', 'none');
        return $this->render('card/game.html.twig');
    }

    #[Route("/game/play", name: "game_play")]
    public function gamePlay(
        SessionInterface $session
    ): Response {

        $drawnCards = $session->get("drawnCards");
        $playerTotal = $session->get("playerTotal");

        $gameCards = [];
        foreach ($drawnCards as $card) {
            $gameCards[] = $card->getAsString();
        }

        if ($playerTotal > 21) {
            $this->addFlash(
                'warning',
                'You lost the round,your sum is over 21!'
            );
            $session->set('winner', 'Bank');
        } elseif ($playerTotal === 21) {
            $this->addFlash(
                'notice',
                'You won the game, excellent you have 21!'
            );
            $session->set('winner', 'Spelare');
        }

        $data = [
            "drawCard" => $gameCards,
            "playerTotal" => $playerTotal
        ];


        return $this->render('card/play.html.twig', $data);
    }

    #[Route("/game/draw", name: "game_draw", methods: ['POST'])]
    public function gameDraw(
        SessionInterface $session
    ): Response {

        $deckPlay = $session->get("twentyNew");
        $drawnCards = $session->get("drawnCards");
        $totalPlayer = $session->get("playerTotal");

        $drawnCard = $deckPlay->drawCard();
        $cardValue = $deckPlay->getRank($drawnCard);

        $drawnCards [] = $drawnCard;

        $session->set("drawnCards", $drawnCards);
        $session->set("playerTotal", $cardValue + $totalPlayer);

        return $this->redirectToRoute('game_play');
    }

    #[Route("/game/bank", name: "game_bank", methods: ['POST'])]
    public function gameBank(
        SessionInterface $session
    ): Response {


        $deckPlay = $session->get("twentyNew");
        $drawnCards = $session->get("drawnCards");
        $totalPlayer = $session->get("playerTotal");
        $totalBank = $session->get("bankTotal");

        $gameCards = $deckPlay->getString($drawnCards);
        $bankCard = [];

        while ($totalBank < 17) {
            $drawnCard = $deckPlay->drawCard();
            $bankCard [] = $drawnCard;
            $cardValue = $deckPlay->getRank($drawnCard);
            $totalBank += $cardValue;
        }

        $bankCards = $deckPlay->getString($bankCard);

        $winner = $deckPlay->winLose($totalPlayer, $totalBank);

        if ($winner === 'spelare') {
            $this->addFlash('notice', 'Player won the game!');
            $winner = 'Spelare';
        } elseif ($winner === 'bank') {
            $this->addFlash('warning', 'Bank won the game!');
            $winner = 'Bank';
        }

        $data = [
            "drawCard" => $gameCards,
            "playerTotal" => $totalPlayer,
            "bankCards" => $bankCards,
            "bankTotal" => $totalBank
        ];

        $session->set('bankTotal', $totalBank);
        $session->set('bankCards', $bankCards);
        $session->set('winner', $winner);
        $twentyNew = new Game();
        $session->set("twentyNew", $twentyNew);

        return $this->render('card/bank.html.twig', $data);
    }

    #[Route("/game/doc", name: "game_doc")]
    public function gameDoc(): Response
    {
        //Renders to the documentation
        return $this->render('card/game_doc.html.twig');
    }
}
