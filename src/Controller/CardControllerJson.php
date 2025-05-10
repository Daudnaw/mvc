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

class CardControllerJson
{
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

        return $this->createJsonResponse($data);
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

        return $this->createJsonResponse($data);
    }

    private function createJsonResponse(array $data): JsonResponse
    {
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }

}
