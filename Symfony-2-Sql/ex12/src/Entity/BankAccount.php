<?php

namespace App\Entity;

use App\Repository\BankAccountRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BankAccountRepository::class)]
class BankAccount
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Persons $personId = null;

    #[ORM\Column(length: 255)]
    private ?string $iban = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPersonId(): ?Persons
    {
        return $this->personId;
    }

    public function setPersonId(?Persons $personId): static
    {
        $this->personId = $personId;

        return $this;
    }

    public function getIban(): ?string
    {
        return $this->iban;
    }

    public function setIban(string $iban): static
    {
        $this->iban = $iban;

        return $this;
    }
}
