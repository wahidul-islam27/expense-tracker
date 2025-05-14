<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\BusinessException;
use App\Exceptions\SystemException;
use App\Repositories\ExpenseRepository;

class ExpenseService
{
    public function __construct(
        protected ExpenseRepository $expenseRepo,
        protected UserService $userService,
        protected CategoryService $categoryService
    ) {}

    public function createExpense($expenseData): void
    {
        ServiceUtils::validateExpense($expenseData);

        $user = $this->getUser($expenseData['user_id']);
        $category = $this->getCategory($expenseData['category_id']);

        if ($user == null || $category == null) {
            throw new BusinessException(400, 'Object Not Found');
        }

        try {
            $this->expenseRepo->create($expenseData);
        } catch (\Exception $e) {
            throw new SystemException(500, $e->getMessage());
        }
    }

    public function updateExpense($id, $expenseData)
    {
        ServiceUtils::validateExpense($expenseData);

        try {
            $this->expenseRepo->update($id, $expenseData);
        } catch (\Exception $e) {
            throw new SystemException(500, $e->getMessage());
        }
    }

    public function deleteExpense($id)
    {
        $existingExpense = $this->expenseRepo->get($id);

        if ($existingExpense == null) {
            throw new BusinessException(400, 'expense not found');
        }

        $this->expenseRepo->delete($id);
    }

    public function getExpenseByUser($userId): array
    {
        $user = $this->getUser($userId);

        try {
            $expenseObjs = $this->expenseRepo->getExpenseByUserId($userId) ?? [];

            if (sizeof($expenseObjs) == 0) {
                throw new BusinessException(400, 'Expense not found');
            }
        } catch (\Exception $e) {
            throw new SystemException(500, $e->getMessage());
        }

        $expenses = [];

        foreach ($expenseObjs as $expenseObj) {
            $category = $this->getCategory($expenseObj['category_id']);

            $expenses[] = ServiceUtils::mapExpenseObjectToExpenseEntity($expenseObj, $user, $category);
        }

        return $expenses;
    }

    public function getExpenseByCategory($categoryId, $userId): array
    {
        $category = $this->getCategory($categoryId);
        $user = $this->getUser($userId);

        try {
            $expenseObjs = $this->expenseRepo->getExpenseByCategoryId($categoryId, $userId);

            if (sizeof($expenseObjs) == 0) {
                throw new BusinessException(400, 'Expense not found');
            }
        } catch (\Exception $e) {
            throw new SystemException(500, $e->getMessage());
        }

        $expenses = [];

        foreach ($expenseObjs as $expenseObj) {

            $expenses[] = ServiceUtils::mapExpenseObjectToExpenseEntity($expenseObj, $user, $category);
        }

        return $expenses;
    }

    public function getExpenseById($id)
    {
        $expenseObj = $this->expenseRepo->get($id) ?? [];

        if (empty($expenseObj)) {
            throw new BusinessException(400, 'No expense found');
        }

        $user = $this->getUser($expenseObj['user_id']);
        $category = $this->getCategory($expenseObj['category_id']);

        return ServiceUtils::mapExpenseObjectToExpenseEntity($expenseObj, $user, $category);
    }

    public function getExpenseByFilter($userId, $categoryFilter, $monthFilter): array
    {
        $expenseObjs = $this->expenseRepo->getExpenseByFilter($userId, (int) $categoryFilter, $monthFilter);

        if (empty($expenseObjs)) {
            throw new BusinessException(400, 'Expense not found');
        }

        $isCategoryInvalid = true;

        $user = $this->getUser($userId);
        if (!empty($categoryFilter)) {
            $category = $this->getCategory((int) $categoryFilter);
            $isCategoryInvalid = false;
        }

        $expenses = [];

        foreach ($expenseObjs as $expenseObj) {
            if ($isCategoryInvalid) {
                $category = $this->getCategory($expenseObj['category_id']);
            }
            $expenses[] = ServiceUtils::mapExpenseObjectToExpenseEntity($expenseObj, $user, $category);
        }
        return $expenses;
    }

    public function getReport($month, $userId)
    {
        $reportData = $this->expenseRepo->getReportByMonth($month, $userId);

        if (empty($reportData)) {
            throw new BusinessException(400, 'No Data Found for the month');
        }

        return $reportData;
    }

    public function downloadReport($userId, $month) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="expense-report.csv"');

        $output = fopen('php://output', 'w');



        fputcsv($output, ['Category', 'Description', 'Amount', 'Date']);

        $reports = $this->getReport($month, $userId);
        $expenses = [];
        foreach ($reports as $report) {
            $expenses[$report['category']][] = $this->getExpenseByFilter($userId, $report["category_id"], $month);
        }
        foreach ($expenses as $category => $items) {
            foreach ($items as $expenses) {
                foreach ($expenses as $expense) {
                    fputcsv($output, [
                        $category,
                        $expense->getDescription(),
                        $expense->getAmount(),
                        $expense->getExpenseDate()->format('Y-m-d'),
                    ]);
                }
            }
        }

        fclose($output);
        exit;
    }

    private function getUser($userId)
    {
        return $this->userService->getUserById($userId);
    }

    private function getCategory($categoryId)
    {
        return $this->categoryService->getCategory($categoryId);
    }
}
