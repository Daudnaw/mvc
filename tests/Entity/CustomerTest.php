<?php

namespace App\Tests\Entity;

use App\Entity\Customer;
use App\Entity\Acount;
use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{
    public function testInitialId(): void
    {
        $customer = new Customer();
        $this->assertNull($customer->getId());
    }

    public function testSetGetForname(): void
    {
        $customer = new Customer();
        $customer->setForname("Ali");
        $this->assertEquals("Ali", $customer->getForname());
    }

    public function testSetGetAftername(): void
    {
        $customer = new Customer();
        $customer->setAftername("Jamma");
        $this->assertEquals("Jamma", $customer->getAftername());
    }

    public function testSetGetAdress(): void
    {
        $customer = new Customer();
        $customer->setAdress("Ali Jamma 33");
        $this->assertEquals("Ali Jamma 33", $customer->getAdress());
    }

    public function testSetGetTelefon(): void
    {
        $customer = new Customer();
        $customer->setTelefon("01230123");
        $this->assertEquals("01230123", $customer->getTelefon());
    }

    public function testCustomerSetter(): void
    {
        $customer = new Customer();
        $customer->customerSetter("Ali", "Jamma", "Strasse uno", "01230123");

        $this->assertEquals("Ali", $customer->getForname());
        $this->assertEquals("Jamma", $customer->getAftername());
        $this->assertEquals("Strasse uno", $customer->getAdress());
        $this->assertEquals("01230123", $customer->getTelefon());
    }

    public function testAddRemoveAcount(): void
    {
        $customer = new Customer();
        $acount = new Acount();

        $this->assertCount(0, $customer->getAcounts());

        $customer->addAcount($acount);
        $this->assertCount(1, $customer->getAcounts());
        $this->assertSame($customer, $acount->getCustomer());

        $customer->removeAcount($acount);
        $this->assertCount(0, $customer->getAcounts());
        $this->assertNull($acount->getCustomer());
    }
}
