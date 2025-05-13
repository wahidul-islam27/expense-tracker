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
use JsonSerializable;

#[Entity]
#[Table('user')]
class User implements JsonSerializable
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

    #[Column(name: 'password')]
    private string $password;

    #[Column(name: 'role')]
    private string $role;

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

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'monthly_income' => $this->monthlyIncome,
            'income_add_time' => $this->incomeAddTime,
            'role' => $this->role
        ];
    }
}
