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
#[Table(name: 'category')]
class Category implements JsonSerializable
{
    #[Id]
    #[Column(name: 'category_id', GeneratedValue: 'AUTO')]
    private int $id;

    #[Column(name: 'category_name')]
    private string $categoryName;

    #[OneToMany(targetEntity: Expense::class, mappedBy: 'category', cascade: ['persist', 'remove'])]
    private Collection $expenseItem;

    public function __construct()
    {
        $this->expenseItem = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setCategoryName(string $categoryName): void
    {
        $this->categoryName = $categoryName;
    }

    public function getCategoryName(): string
    {
        return $this->categoryName;
    }

    public function jsonSerialize(): mixed
    {
        return [
            "id" => $this->id,
            "category_name" => $this->categoryName
        ];
    }
}
