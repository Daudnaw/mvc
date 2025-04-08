<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\Deck;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class LuckyControllerJson
{
    #[Route("/api")]
    public function jsonRuotes(): Response
    {

        $data = [
            'Route_1' => '/api/quote'
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
        return $response;
    }

    #[Route("/api/quote")]
    public function jsonQuote(): Response
    {
        //$number = random_int(0, 100);
        $quote = ['A donkey dies of hunger if he has to choose between two piles of gras',
        'A goat never gets fat if lion is in the same room',
        'Beautiful thing about democracy is minorities are never right'];
        $randKey = array_rand($quote);
        $randquote = $quote[$randKey];
        $data = [
            'quote' => $randquote,
            'date' => date('Y-m-d'),
            'time' => date('H:i:s')
        ];

        //$response = new Response();
        //$response->setContent(json_encode($data));
        //$response->headers->set('Content-Type', 'application/json');

        //return $response;
        // return new JsonResponse($data);
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck")]
    public function jsonDeck(
    ): Response {

        $deck = new Deck();

        $data = [
            "newDeck" => $deck->getString(),
            "count" => $deck->getCount()
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/shuffle", methods: ['GET','POST'])]
    public function jsonShuffle(
        Request $request,
        SessionInterface $session
    ): Response {

        $deck = new Deck();
        $shuffled = $deck->shuffleDeck();
        $session->set("shuffledDeck", $deck);

        $data = [
            "shuffleDeck" => $deck->getString($shuffled)
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/draw", methods: ['GET', 'POST'])]
    public function jsonDraw(
        SessionInterface $session
    ): Response {

        $deck = $session->get("shuffledDeck");
        $drawn = $deck->drawCard();

        $data = [
            "count" => $deck->getCount(),
            "drawCard" => $drawn->getAsString()
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/draw/{num<\d+>}", methods: ['GET', 'POST'])]
    public function numDraw(
        int $num,
        SessionInterface $session
    ): Response {

        $deck = $session->get("shuffledDeck");
        $drawn = $deck->drawCards($num);

        $drawnCards = [];
        foreach ($drawn as $card) {
            $drawnCards[] = $card->getAsString();
        }

        $data = [
            "count" => $deck->getCount(),
            "drawCard" => $drawnCards
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
