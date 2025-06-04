<?php

namespace App\Controller;

use App\Entity\Library;
use App\Entity\Customer;
use App\Entity\Acount;

use App\Repository\CustomerRepository;
use App\Repository\AcountRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


final class PlayerControllerTwig extends AbstractController
{


    #[Route('/proj/player/create', name: 'player_create')]
    public function createplayerOne(
    ): Response {

        return $this->render('customer/player/one.html.twig');
    }

    #[Route('/proj/player/create/post', name: 'player_create_post', methods:['POST'])]
    public function createCustomerPost(
        Request $request,
        ManagerRegistry $doctrine
    ): Response {
        $entityManager = $doctrine->getManager();

        $forname = $request->request->get('forname');
        $aftername = $request->request->get('aftername');
        $adress = $request->request->get('adress');
        $tele = $request->request->get('tele');

        $customer = new Customer();
        $customer->customerSetter($forname, $aftername, $adress, $tele);

        $entityManager->persist($customer);

        $entityManager->flush();

        return $this->redirectToRoute('acount_player', [
            'customerId' => $customer->getId(),
            'forname' => $forname]);
    }

    #[Route('/proj/acount/create/player', name: 'acount_player')]
    public function createacountPost(
        Request $request,
        ManagerRegistry $doctrine,
        SessionInterface $session
    ): Response {
        $entityManager = $doctrine->getManager();

        $forname = $request->query->get('forname');
        $balance = 5000; //(int) $request->request->get('balance');
        $customerId = (int) $request->query->get('customerId');

        $customer = $entityManager->getRepository(Customer::class)->find($customerId);

        $acount = new Acount();
        $acount->accountSetter($forname, $balance);
        $acount->setCustomer($customer);

        $entityManager->persist($acount);

        $entityManager->flush();

        $which = $session->get('which');
        if ($which == 'monkey') {
            return $this->redirectToRoute('computer_create_post');
        }

        if ($which == 'computer') {
            return $this->render('customer/player/two.html.twig');
        }

        if ($which == 'alend') {
            return $this->redirectToRoute('house_create_post');
        }

        if ($which == 'end') {
            return $this->redirectToRoute('proj_start');
        }

        return $this->redirectToRoute('monkey_create_post');
    }

    #[Route('/proj/monkey/create/post', name: 'monkey_create_post')]
    public function createMonkeyPost(
        ManagerRegistry $doctrine,
        SessionInterface $session
    ): Response {
        $entityManager = $doctrine->getManager();
        $forname = 'Player';
        $customer = new Customer();
        $customer->customerSetter($forname, 'Monkey', 'monkey', 'banana');

        $entityManager->persist($customer);

        $entityManager->flush();
        $session->set('which', 'monkey');
        return $this->redirectToRoute('acount_player', [
            'customerId' => $customer->getId(),
            'forname' => $forname]);
    }

     #[Route('/proj/computer/create/post', name: 'computer_create_post')]
    public function createComputerPost(
        ManagerRegistry $doctrine,
        SessionInterface $session
    ): Response {
        $entityManager = $doctrine->getManager();
        $forname = 'Player';
        $customer = new Customer();
        $customer->customerSetter($forname, 'Computer', 'Computer', 'banana');

        $entityManager->persist($customer);

        $entityManager->flush();
        $session->set('which', 'computer');
        return $this->redirectToRoute('acount_player', [
            'customerId' => $customer->getId(),
            'forname' => $forname]);
    }

    #[Route('/proj/player/create/two', name: 'player_create_two', methods:['POST'])]
    public function createTwoPost(
        Request $request,
        ManagerRegistry $doctrine,
        SessionInterface $session
    ): Response {
        $entityManager = $doctrine->getManager();

        $forname = $request->request->get('forname');
        $aftername = $request->request->get('aftername');
        $adress = $request->request->get('adress');
        $tele = $request->request->get('tele');

        $customer = new Customer();
        $customer->customerSetter($forname, $aftername, $adress, $tele);

        $entityManager->persist($customer);

        $entityManager->flush();
        $session->set('which', 'alend');

        return $this->redirectToRoute('acount_player', [
            'customerId' => $customer->getId(),
            'forname' => $forname]);
    }

    #[Route('/proj/house/create/post', name: 'house_create_post')]
    public function createHousePost(
        ManagerRegistry $doctrine,
        SessionInterface $session
    ): Response {
        $entityManager = $doctrine->getManager();
        $forname = 'House';
        $customer = new Customer();
        $customer->customerSetter($forname, 'House', 'House', 'banana');

        $entityManager->persist($customer);

        $entityManager->flush();
        $session->set('which', 'end');
        return $this->redirectToRoute('acount_player', [
            'customerId' => $customer->getId(),
            'forname' => $forname]);
    }

}
