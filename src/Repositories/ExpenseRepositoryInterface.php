<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\Repository;

interface ExpenseRepositoryInterface extends Repository
{
    public function getExpenseByUserId($userId);
    public function getExpenseByCategoryId($categoryId, $userId);
    public function getExpenseByFilter($userId, $category, $month);
    public function getReportByMonth($month, $userId);
}
