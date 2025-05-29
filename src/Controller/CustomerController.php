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


final class CustomerController extends AbstractController
{
    #[Route('/customer', name: 'app_customer')]
    public function index(): Response
    {
        return $this->render('customer/index.html.twig', [
            'controller_name' => 'CustomerController',
        ]);
    }

    #[Route('api/proj/customer', name: 'api_show_customers')]
    public function showAllCustomers(
        CustomerRepository $customerRepository
    ): Response {
        $customers = $customerRepository->findAll();

        $data = [];

        foreach ($customers as $customer) {
            $accounts = [];

            foreach ($customer->getAcounts() as $account) {
                $accounts[] = [
                    'id' => $account->getId(),
                    'forname' => $account->getForname(),
                    'balance' => $account->getBalance()
                ];
            }

            $data[] = [
                'id' => $customer->getId(),
                'forname' => $customer->getForname(),
                'aftername' => $customer->getAftername(),
                'adress' => $customer->getAdress(),
                'telefon' => $customer->getTelefon(),
                'accounts' => $accounts
            ];
        }

    $response = $this->json($data);
    $response->setEncodingOptions(
        $response->getEncodingOptions() | JSON_PRETTY_PRINT
    );

    return $response;
}


    #[Route('/proj/customer/create', name: 'customer_create')]
    public function createCustomer(
    ): Response {

        return $this->render('customer/customer_create.html.twig');
    }

    #[Route('/proj/customer/create/post', name: 'customer_create_post', methods:['POST'])]
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

        $this->addFlash(
            'notice',
            'Customer created, successfully!'
        );

        return $this->redirectToRoute('app_customer');
    }

     #[Route('/proj/customer/delete', name: 'customer_delete')]
    public function deleteCustomer(
    ): Response {

        return $this->render('customer/customer_delete.html.twig');
    }

    #[Route('/customer/delete/id', name: 'customer_delete_id')]
    public function deleteCustomerById(
        ManagerRegistry $doctrine,
        Request $request
    ): Response {
        $id = $request->request->get('id');
        $entityManager = $doctrine->getManager();
        $customer = $entityManager->getRepository(Customer::class)->find($id);

        if (!$customer) {
            throw $this->createNotFoundException(
                'No Customer found for id '.$id
            );
        }

        $entityManager->remove($customer);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Customer deleted, successfully!'
        );

        return $this->redirectToRoute('app_customer');
    }

    #[Route('/acount', name: 'app_acount')]
    public function acount(): Response
    {
        return $this->render('customer/acount.html.twig');
    }

    #[Route('/proj/acount/create', name: 'acount_create')]
    public function acountCustomer(
    ): Response {

        return $this->render('customer/account_create.html.twig');
    }
    
    #[Route('/proj/acount/create/post', name: 'acount_create_post', methods:['POST'])]
    public function createacountPost(
        Request $request,
        ManagerRegistry $doctrine
    ): Response {
        $entityManager = $doctrine->getManager();

        $forname = $request->request->get('forname');
        $balance = (int) $request->request->get('balance');
        $customerId = (int) $request->request->get('customer_id');

        $customer = $entityManager->getRepository(Customer::class)->find($customerId);

        $acount = new Acount();
        $acount->accountSetter($forname, $balance);
        $acount->setCustomer($customer);

        $entityManager->persist($acount);

        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Account created, successfully!'
        );

        return $this->redirectToRoute('app_acount');
    }

    #[Route('/proj/acount/update', name: 'acount_update')]
    public function acountUpdate(
    ): Response {

        return $this->render('customer/account_update.html.twig');
    }

    #[Route('/api/proj/account/update-balance', name: 'update_account_balance')]
    public function updateAccountBalance(
        Request $request,
        AcountRepository $acountRepository,
        ManagerRegistry $doctrine
    ): Response {
        $entityManager = $doctrine->getManager();
        $accountId = $request->request->get('id');; //(int) $request->request->get('account_id');
        $amount = $request->request->get('amount');; //(int) $request->request->get('amount'); // Can be positive or negative

        $account = $acountRepository->find($accountId);

        if (!$account) {
            return $this->json(['error' => 'Account not found'], 404);
        }

        $newBalance = $account->getBalance() + $amount;
        $account->setBalance($newBalance);

        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Account updated, successfully!'
        );

       return $this->redirectToRoute('app_acount');
    }



}
