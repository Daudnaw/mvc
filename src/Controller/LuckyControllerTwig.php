<?php

namespace App\Controller;

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
    public function sessionDelete(
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

    #[Route("/metrics", name: "metrics")]
    public function metrics(): Response
    {
        //Go to the report for clean code

        return $this->render('metrics.html.twig');
    }
}
