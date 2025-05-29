<?php

namespace App\Entity;

use App\Repository\AcountRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AcountRepository::class)]
class Acount
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $forname = null;

    #[ORM\Column(nullable: true)]
    private ?int $balance = null;

    #[ORM\ManyToOne(inversedBy: 'acounts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $customer = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getForname(): ?string
    {
        return $this->forname;
    }

    public function setForname(string $forname): static
    {
        $this->forname = $forname;

        return $this;
    }

    public function getBalance(): ?int
    {
        return $this->balance;
    }

    public function setBalance(?int $balance): static
    {
        $this->balance = $balance;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

        /**
     * Set multiple properties with optional values.
     *
     * @param string|null $title
     * @param int|null $writer
     * @return $this
     */
    public function accountSetter(
        ?string $forname = null,
        ?int $balance = null
    ): self {
        $this->setForname($forname ?? $this->getForname() ?? '');
        $this->setBalance($balance ?? $this->getBalance() ?? 0);

        return $this;
    }
}
