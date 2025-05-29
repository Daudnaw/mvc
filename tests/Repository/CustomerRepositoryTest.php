<?php

namespace App\Tests\Repository;

use App\Entity\Customer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;

class CustomerRepositoryTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager = null;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }

    public function testPersistCustomer(): void
    {

        $customer = new Customer();
        $customer->setForname('Janne');
        $customer->setAftername('Die');
        $customer->setAdress('12345');
        $customer->setTelefon('555-1234');

        $this->entityManager->persist($customer);
        $this->entityManager->flush();

        $repo = $this->entityManager->getRepository(Customer::class);
        $fetched = $repo->find($customer->getId());

        $this->assertInstanceOf(Customer::class, $fetched);
        $this->assertEquals('Janne', $fetched->getForname());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager?->close();
        $this->entityManager = null;
    }
}
