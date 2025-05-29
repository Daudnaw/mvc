<?php

namespace App\Controller;

use App\Card\Poker;
use App\Card\Deck;
use App\Card\Computer;
use App\Card\Game;

use App\Entity\Acount;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ShowDownControllerTwig extends AbstractController
{
    #[Route("/proj/show", name: "show_down")]
    public function showDown(
        SessionInterface $session
    ): Response
    {
        $allPlayer = $session->get("allPlayer");
        $foldOne = $session->get('foldOne');
        $foldTwo = $session->get('foldTwo');

        if ( $foldOne == '1') {
            $allPlayer[0] = [];
        }

        if ( $foldTwo == '2') {
            $allPlayer[3] = [];
        }
        $session->set('allPlayer', $allPlayer);

       return $this->redirectToRoute('show_to_final');
    }

    #[Route("/proj/show/to/final", name: "show_to_final")]
    public function showToFinal(
        SessionInterface $session
    ): Response
    {
        $poker = new Poker();
        $allPlayer = $session->get('allPlayer');
        $score = [];

        foreach ($allPlayer as $hand) {
            $score[] = $poker->evaluateHand($hand);
        }

        $session->set('score',$score);

       return $this->redirectToRoute('show_final');
    }

    #[Route("/proj/show/final", name: "show_final")]
    public function showFinal(
        SessionInterface $session,
        ManagerRegistry $doctrine
    ): Response
    {
        //checking
        $entityManager = $doctrine->getManager();

        $acountOne = $entityManager->getRepository(Acount::class)->find(4);
        $acountTwo = $entityManager->getRepository(Acount::class)->find(5);
        $acountMonkey = $entityManager->getRepository(Acount::class)->find(6);
        $acountComputer = $entityManager->getRepository(Acount::class)->find(7);
        $acountHouse= $entityManager->getRepository(Acount::class)->find(8);
        //checking
        $score = $session->get('score');
        $player = ['Player One', 'Monkey Shonkey', 'Computer Shuter', 'Player Two'];
        $acount = ['acountOne', 'acountMonkey', 'acountComputer', 'acountTwo'];

        $winnerScore = max($score);
        $winnerIndex = array_search($winnerScore, $score);
        $winnerPlayer = $player[$winnerIndex];
        $winnerPrice = $session->get('totalRaise');

        $winnerAcount = $session->get($acount[$winnerIndex]);
        $winnerAcount += $winnerPrice;

        $session->set($acount[$winnerIndex], $winnerAcount);

        $data = [
            "winnerScore" => $winnerScore,
            'winnerPlayer' => $winnerPlayer,
            'winnerPrice' => $winnerPrice
        ];

        $acountOne->setBalance($session->get('acountOne'));
        $acountTwo->setBalance($session->get('acountTwo'));
        $acountMonkey->setBalance($session->get('acountMonkey'));
        $acountComputer->setBalance($session->get('acountComputer'));
        $acountHouse->setBalance($session->get('acountHouse'));

        $entityManager->persist($acountOne);
        $entityManager->persist($acountTwo);
        $entityManager->persist($acountMonkey);
        $entityManager->persist($acountComputer);
        $entityManager->persist($acountHouse);

        $entityManager->flush();

        $session->clear();

       return $this->render('proj/show_down.html.twig', $data);
    }

    #[Route("/proj/trial", name: "trial")]
    public function trial(
        SessionInterface $session
    ): Response
    {
        
        $deck = new Deck();
        $drawn = $deck->drawCards(5);

        $poker = New Poker();
        $computer = new Computer($poker, $deck);
        $high = $poker->getHighCard($drawn);
        $rankFreq = $poker->getRankFreq($drawn);
        $isPair = $poker->isPair($drawn);
        $isTwoPair = $poker->isTwoPair($drawn);
        $isThree = $poker->isThree($drawn);
        $isFour = $poker->isFour($drawn);
        $isFullHouse = $poker->isFullHouse($drawn);

        $list = [$isPair, $isTwoPair, $isThree, $isFour, $isFullHouse];

        
        $afterIntell = $computer->play($drawn);

        $data = [
            "poker" => $drawn,
            "number" => $high,
            "rankfreq" => $list,
            "smart" => $afterIntell
        ];

       return $this->render('proj/trial.html.twig', $data);
    }
}
