<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\Deck;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class LuckyControllerTwig extends AbstractController
{
    #[Route("/lucky", name: "lucky")]
    public function number(): Response
    {
        $number = random_int(0, 100);

        $data = [
            'number' => $number
        ];

        return $this->render('lucky_number.html.twig', $data);
    }

    #[Route("/", name: "home")]
    public function home(
        Request $request,
        SessionInterface $session
    ): Response {
        $session->set("testing", 0);
        return $this->render('home.html.twig');
    }

    #[Route("/about", name: "about")]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    #[Route("/report", name: "report")]
    public function report(): Response
    {
        return $this->render('report.html.twig');
    }

    #[Route("/session", name: "session")]
    public function session(
        SessionInterface $session
    ): Response {
        $data = [
            'session' => $session->all(),
        ];

        return $this->render('session.html.twig', $data);
    }

    #[Route("/session/delete", name: "session_delete", methods: ['POST'])]
    public function session_delete(
        SessionInterface $session
    ): Response {

        $session->clear();
        //$_SESSION = [];

        $this->addFlash(
            'notice',
            'Your Session was cleared successfully!'
        );
        //session_destroy(); destroy even flash

        return $this->redirectToRoute('session');
    }

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
    public function card_deck(
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
    public function shuffle_deck(
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
    public function deck_draw(
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
}
