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

        return $this->render('card/card.html.twig');
    }

    #[Route("/card/deck", name: "card_deck")]
    public function cardDeck(
        SessionInterface $session
    ): Response {
        $deck = $session->get("newDeck");

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

        return $this->render('card/drawn_deck.html.twig', $data);
    }
}
