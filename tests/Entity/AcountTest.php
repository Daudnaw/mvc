<?php

namespace App\Tests\Entity;

use App\Entity\Acount;
use App\Entity\Customer;
use PHPUnit\Framework\TestCase;

class AcountTest extends TestCase
{
    public function testInitialId(): void
    {
        $acount = new Acount();
        $this->assertNull($acount->getId());
    }

    public function testSetGetForname(): void
    {
        $acount = new Acount();
        $acount->setForname("Ali");
        $this->assertEquals("Ali", $acount->getForname());
    }

    public function testSetGetBalance(): void
    {
        $acount = new Acount();
        $acount->setBalance(505);
        $this->assertEquals(505, $acount->getBalance());
    }

    public function testSetGetCustomer(): void
    {
        $customer = new Customer();
        $acount = new Acount();
        $acount->setCustomer($customer);

        $this->assertSame($customer, $acount->getCustomer());
    }

    public function testSetter(): void
    {
        $acount = new Acount();
        $acount->accountSetter("Ali", 250);

        $this->assertEquals("Ali", $acount->getForname());
        $this->assertEquals(250, $acount->getBalance());
    }
}
