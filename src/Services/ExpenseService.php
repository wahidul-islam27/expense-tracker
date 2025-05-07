<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\CategoryRepository;
use App\Repositories\ExpenseRepository;
use App\Repositories\UserRepository;

class ExpenseService {
    private $expenseRepo;
    private $userRepo;
    private $categoryRepo;

    public function __construct()
    {
        $this->expenseRepo = new ExpenseRepository();
        $this->userRepo = new UserRepository();
        $this->categoryRepo = new CategoryRepository();
    }

    public function createExpense($expenseData): void {
        
    }
}