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

class ShowCardControllerJson
{
    #[Route("/api/deck")]
    public function jsonDeck(
    ): Response {

        $deck = new Deck();

        $data = [
            "newDeck" => $deck->getString(),
            "count" => $deck->getCount()
        ];

        return $this->createJsonResponse($data);
    }

    #[Route("/api/deck/shuffle", methods: ['GET','POST'])]
    public function jsonShuffle(
        SessionInterface $session
    ): Response {

        $deck = new Deck();
        $shuffled = $deck->shuffleDeck();
        $session->set("shuffledDeck", $deck);

        $data = [
            "shuffleDeck" => $deck->getString($shuffled)
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
