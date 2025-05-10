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

class GameControllerJson
{
    #[Route("/api/game")]
    public function game(
        SessionInterface $session
    ): Response {
        $deckPlay = $session->get("twentyNew");
        $drawnCards = $session->get("drawnCards");
        $totalPlayer = $session->get("playerTotal");
        $totalBank = $session->get("bankTotal");
        $bankCards = $session->get("bankCards");
        $winner  = $session->get("winner");

        $gameCards = 'Game not started yat';

        if ($drawnCards) {
            $gameCards = $deckPlay->getString($drawnCards);
        }

        $data = [
            "drawCard" => $gameCards,
            "playerTotal" => $totalPlayer,
            "bankCards" => $bankCards,
            "bankTotal" => $totalBank,
            "winner" => $winner
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }

}
