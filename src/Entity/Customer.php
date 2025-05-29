<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $forname = null;

    #[ORM\Column(length: 255)]
    private ?string $aftername = null;

    #[ORM\Column(length: 255)]
    private ?string $adress = null;

    #[ORM\Column(length: 255)]
    private ?string $telefon = null;

    /**
     * @var Collection<int, Acount>
     */
    #[ORM\OneToMany(targetEntity: Acount::class, mappedBy: 'customer')]
    private Collection $acounts;

    public function __construct()
    {
        $this->acounts = new ArrayCollection();
    }

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

    public function getAftername(): ?string
    {
        return $this->aftername;
    }

    public function setAftername(string $aftername): static
    {
        $this->aftername = $aftername;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): static
    {
        $this->adress = $adress;

        return $this;
    }

    public function getTelefon(): ?string
    {
        return $this->telefon;
    }

    public function setTelefon(string $telefon): static
    {
        $this->telefon = $telefon;

        return $this;
    }

    /**
     * @return Collection<int, Acount>
     */
    public function getAcounts(): Collection
    {
        return $this->acounts;
    }

    public function addAcount(Acount $acount): static
    {
        if (!$this->acounts->contains($acount)) {
            $this->acounts->add($acount);
            $acount->setCustomer($this);
        }

        return $this;
    }

    public function removeAcount(Acount $acount): static
    {
        if ($this->acounts->removeElement($acount)) {
            // set the owning side to null (unless already changed)
            if ($acount->getCustomer() === $this) {
                $acount->setCustomer(null);
            }
        }

        return $this;
    }

        /**
     * Set multiple properties with optional values.
     *
     * @param string|null $title
     * @param string|null $writer
     * @param string|null $isbn
     * @param string|null $image
     * @return $this
     */
    public function customerSetter(
        ?string $forname = null,
        ?string $aftername = null,
        ?string $adress = null,
        ?string $telefon = null
    ): self {
        $this->setForname($forname ?? $this->getForname() ?? '');
        $this->setAftername($aftername ?? $this->getAftername() ?? '');
        $this->setAdress($adress ?? $this->getAdress() ?? '');
        $this->setTelefon($telefon ?? $this->getTelefon() ?? '');

        return $this;
    }
}
