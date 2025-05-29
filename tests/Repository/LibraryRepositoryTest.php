<?php

namespace App\Tests\Repository;

use App\Entity\Library;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;

class LibraryRepositoryTest extends KernelTestCase
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

    public function testFindByIsbn(): void
    {

        $book = new Library();
        $book->setTitle('0000');
        $book->setWriter('GGG');
        $book->setIsbn('11-111-11');
        $book->setImage('cover.jpg');

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        $repo = $this->entityManager->getRepository(Library::class);
        $fetched = $repo->findByIsbn('11-111-11');

        $this->assertInstanceOf(Library::class, $fetched);
        $this->assertEquals('0000', $fetched->getTitle());
        $this->assertEquals('GGG', $fetched->getWriter());
        $this->assertEquals('11-111-11', $fetched->getIsbn());
    }

    public function testFindNull(): void
    {
        $repo = $this->entityManager->getRepository(Library::class);
        $result = $repo->findByIsbn('non-existent-isbn');
        $this->assertNull($result);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager?->close();
        $this->entityManager = null;
    }
}
