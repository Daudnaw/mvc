<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\Deck;
use App\Card\Poker;
use App\Card\Computer;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ProjHomeControllerJson extends AbstractController
{
    #[Route("/proj/api", name: "api_overview")]
    public function apiOverview(): Response
    {
        return $this->render('api/main_api.html.twig');
    }

    #[Route("/proj/api/about", name: "api_about")]
    public function jsonabout(
        SessionInterface $session
    ): Response
    {
        $deck = new Deck();
        $session->set('playerOne', $deck->drawCards(5));

        $about = ['Now you dealt five cards to each player. Go back to main api proj/api and test which hands 
        you have gotten. If my computer is really smart and so on.'];

        date_default_timezone_set('Europe/Stockholm');
        $data = [
            'about' => $about,
            'date' => date('Y-m-d'),
            'time' => date('H:i:s')
        ];

        $deck = new Deck();
        $playerOne = $deck->drawCards(5);
        $playerTwo = $deck->drawCards(5);
        $playerMonkey = $deck->drawCards(5);
        $playerComputer = $deck->drawCards(5);
        $players = [$playerOne, $playerTwo, $playerMonkey, $playerComputer];
        $session->set('allPlayer', $players);

        $play = [];

        foreach ($players as $player) {
            $play[] = $deck->getString($player);
        }

        $session->set('hands', $play);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
        return $response;
    }

    #[Route("/proj/api/hands", name: "api_hands")]
    public function jsonHands(
        SessionInterface $session
    ): Response
    {
        $player = $session->get('hands');


        $data = [
            'PlayerOne' => $player[0],
            'PlayerTwo' => $player[1],
            'PlayerMonkey' => $player[2],
            'playerComputer' => $player[3]
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }

    #[Route("/proj/api/computer", name: "api_computer")]
    public function jsonSmart(
        SessionInterface $session
    ): Response
    {
        $deck = new Deck();
        $poker = new Poker();
        $computer = new Computer($poker, $deck);

        $allPlayer = $session->get("allPlayer");
        $beforeDecision = $allPlayer[3];

        $afterDecision = $computer->play($beforeDecision);
        //$allPlayer[3] = $afterDecision;
        $beforeDecision = $deck->getString($beforeDecision);
        $afterDecision = $deck->getString($afterDecision);
        $allPlayer[3] = $afterDecision;
        $session->set('allPlayer', $allPlayer);

        $data = [
            'Before Decision' => $beforeDecision,
            'After Decision' => $afterDecision
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }


    #[Route("/proj/api/post", name: "api_monkey", methods: ["POST"])]
    public function handlePost(
        Request $request,
        SessionInterface $session
    ): JsonResponse
    {
        $numCards = $request->request->get('number');

        $deck = new Deck();
        $poker = new Poker();
        $computer = new Computer($poker, $deck);

        $allPlayer = $session->get("allPlayer");
        $monkeyBefore = $allPlayer[2];
        $playerMonkey = $allPlayer[2];

        $cardIndexes = array_rand($playerMonkey, $numCards);
        $cardIndexes = (array) $cardIndexes;

        foreach ($cardIndexes as $index)  {
            unset($playerMonkey[$index]);
        }

        $playerMonkey = array_values($playerMonkey);
        $playerMonkeyAfter = array_merge($playerMonkey, $deck->drawCards($numCards));
        $allPlayer[2] = $playerMonkeyAfter;

        $beforeDecision = $deck->getString($monkeyBefore);
        $afterDecision = $deck->getString($playerMonkeyAfter);

        $session->set('allPlayer', $allPlayer);

        $data = [
            'Before Changing' => $beforeDecision,
            'After Changing' => $afterDecision
        ];

        $response = new JsonResponse($data);
            $response->setEncodingOptions(
                $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            );
            return $response;
    }

    #[Route("/proj/api/points", name: "api_points")]
    public function jsonPoints(
        SessionInterface $session
    ): Response
    {
        $deck = new Deck();
        $poker = new Poker();
        $computer = new Computer($poker, $deck);

        $allPlayer = $session->get("allPlayer");
        $score = [];

        foreach ($allPlayer as $hand) {
            $score[] = $poker->evaluateHand($hand);
        }

        $data = [
            'PlayerOne' => $score[0],
            'PlayerTwo' => $score[1],
            'PlayerMonkey' => $score[2],
            'PlayerComputer' => $score[3]
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }

    #[Route("/proj/api/highest", name: "api_high")]
    public function jsonHigh(
        SessionInterface $session
    ): Response
    {
        //$deck = new Deck();
        $poker = new Poker();

        $allPlayer = $session->get("allPlayer");
        
        $high = $poker->getHighCard($allPlayer[0]);

        $data = [
            'HighestCard' => $high
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }


     #[Route("/proj/api/{num<\d+>}", name: "num", methods:['GET', 'POST'])]
    public function num(
        int $num,
        SessionInterface $session
    ): Response {
        $poker = new Poker();

        $allPlayer = $session->get("allPlayer");
        
        $high = $poker->getHighCard($allPlayer[$num]);

        $data = [
            'HighestCard Player Computer' => $high
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }


}
