<?php
namespace App\Tests\Repository;

use App\Entity\Acount;
use App\Entity\Customer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;

class AccountRepositoryTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager = null;

    protected function setUp(): void
    {
        self::bootKernel(); //intializing service container 
        //retriev entitymanager for query and stuff
        $this->entityManager = self::$kernel->getContainer()->get('doctrine')->getManager();

        $schemaTool = new SchemaTool($this->entityManager);//manage database for testing
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();//fetches metadata like entities
        $schemaTool->dropSchema($metadata);//drops the entire database for cleanstart
        $schemaTool->createSchema($metadata); //recreate tables from entities
    }

    public function testAccount(): void
    {
        $customer = new Customer();
        $customer->setForname('John');
        $customer->setAftername('Din');
        $customer->setAdress('Din');
        $customer->setTelefon('Din');

        $this->entityManager->persist($customer);

        $account = new Acount();
        $account->setForname('Ali');
        $account->setBalance(1000);
        $account->setCustomer($customer);

        $this->entityManager->persist($account);
        $this->entityManager->flush();

        $repo = $this->entityManager->getRepository(Acount::class);
        $fetched = $repo->find($account->getId());

        $this->assertInstanceOf(Acount::class, $fetched);
        $this->assertEquals('Ali', $fetched->getForname());
    }

    public function testFindBalance(): void
    {
        $customer = new Customer();
        $customer->setForname('John');
        $customer->setAftername('Din');
        $customer->setAdress('Din din');
        $customer->setTelefon('123456');
        $this->entityManager->persist($customer);

        $account = new Acount();
        $account->setForname('Ali');
        $account->setBalance(677);
        $account->setCustomer($customer);
        $this->entityManager->persist($account);
        $this->entityManager->flush();

        $repo = $this->entityManager->getRepository(Acount::class);
        $balance = $repo->findBalanceById($account->getId());

        $this->assertEquals(677, $balance);
    }

}
