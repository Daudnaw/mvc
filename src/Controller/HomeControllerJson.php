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

class HomeControllerJson
{
    #[Route("/api")]
    public function jsonRuotes(): Response
    {

        $data = [
            [
                'route' => '/api/quote',
                'description' => 'This route gives you a random quote and current time and date',
            ],
            [
                'route' => '/',
                'description' => 'Takes you to the home of my page',
            ],
            [
                'route' => '/about',
                'description' => 'This leads you to a page with some information about courses',
            ],
            [
                'route' => '/report',
                'description' => 'This page contains redovisningstext for all kmom',
            ],
            [
                'route' => '/lucky',
                'description' => 'This gives you a random number and some dancing images',
            ],
            [
                'route' => '/card',
                'description' => 'Explains class relations and uml diagram with links to subpages',
            ],
            [
                'route' => '/card/deck',
                'description' => 'You get a sorted deck of Cards according to color and numbers',
            ],
            [
                'route' => '/card/deck/shuffle',
                'description' => 'Gives you a mixed new deck of cards.',
            ],
            [
                'route' => '/card/deck/draw',
                'description' => 'Shows a drawn card and number of cards left in the deck',
            ],
            [
                'route' => '/card/deck/draw/:number',
                'description' => 'Draws and shows :number of cards and how many cards left in the game',
            ],
            [
                'route' => '/api/game',
                'description' => 'This route shows you the state of the game twentyOne',
            ],
            [
                'route' => '/api/library/books',
                'description' => 'This route shows you the all books in library.'
            ],
            [
                'route' => '/api/library/book/:vv-111-111',
                'description' => 'This route let you search with isbn vv-111-111 of a book.'
            ],
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
        $quote = ['A donkey dies of hunger if he has to choose between two piles of gras',
        'A goat never gets fat if lion is in the same room',
        'Beautiful thing about democracy is minorities are never right'];
        $randKey = array_rand($quote);
        $randquote = $quote[$randKey];
        date_default_timezone_set('Europe/Stockholm');
        $data = [
            'quote' => $randquote,
            'date' => date('Y-m-d'),
            'time' => date('H:i:s')
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
