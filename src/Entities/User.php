<?php

declare(strict_types=1);

namespace App\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table('user')]
class User
{
    #[Id]
    #[Column]
    #[GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[Column(name: 'user_name')]
    private string $username;

    #[Column(name: 'monthly_income')]
    private string $monthlyIncome;

    #[Column(name: 'income_add_time')]
    private \DateTime $incomeAddTime;

    #[OneToMany(targetEntity: Expense::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    private Collection $expenseItems;

    public function __construct()
    {
        $this->expenseItems = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setMonthlyIncome(string $income): void
    {
        $this->monthlyIncome = $income;
    }

    public function getMonthlyIncome(): string
    {
        return $this->monthlyIncome;
    }

    public function setIncomeAddDate(\DateTime $date): void
    {
        $this->incomeAddTime = $date;
    }

    public function getIncomeAddTime(): \DateTime
    {
        return $this->incomeAddTime;
    }
}
