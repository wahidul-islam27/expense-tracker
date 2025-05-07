<?php

declare(strict_types=1);

namespace App\Entities;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'expense')]
class Expense
{
    #[Id]
    #[Column(name: 'category_id', GeneratedValue: 'AUTO')]
    private int $id;

    #[Column(name: 'description')]
    private string $description;

    #[ManyToOne(inversedBy: 'expenseItems')]
    private User $user;

    #[ManyToOne(inversedBy: 'expenseItem')]
    private Category $category;

    #[Column(name: 'amount')]
    private string $amount;

    #[Column(name: 'expense_date')]
    private \DateTime $expenseDate;

    public function getId(): int {
        return $this->id;
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function setUser(User $user): void {
        $this->user  = $user;
    }

    public function getUser(): User {
        return $this->user;
    }

    public function setCategory(Category $category): void {
        $this->category = $category;
    }

    public function setAmount(string $amount): void {
        $this->amount = $amount;
    }

    public function getAmount(): string {
        return $this->amount;
    }

    public function setExpenseDate(\DateTime $date): void {
        $this->expenseDate = $date;
    }

    public function getExpenseDate(): \DateTime {
        return $this->expenseDate;
    }
}
