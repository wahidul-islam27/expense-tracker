<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Exceptions\BusinessException;
use App\Exceptions\SystemException;
use App\Services\CategoryService;
use App\Services\ExpenseService;
use App\View;

class HomeController extends Controller
{

    private $categories;

    public function __construct(private ExpenseService $expenseService, private CategoryService $categoryService)
    {
        parent::__construct();
    }

    public function index()
    {
        $this->init('user');

        if ($this->loggedInUserId[1] === 'admin') {
            header('Location: /expense-tracker/public/category');
            exit;
        }

        $this->categories = $this->categoryService->getAll();

        $categoryFilter = $_GET['category'] ?? null;
        $monthFilter = $_GET['month'] ?? null;
        $error = '';

        try {
            if (empty($categoryFilter) && empty($monthFilter)) {
                $expenses = $this->expenseService->getExpenseByUser($this->loggedInUserId[0]);
            } else {
                $expenses = $this->expenseService->getExpenseByFilter($this->loggedInUserId[0], $categoryFilter, $monthFilter);
            }
        } catch (SystemException $e) {
            $error = $e->getMessage();
        } catch (BusinessException $e) {
            $error = $e->getMessage();
        }


        return View::make('index', [
            'expenses' => isset($expenses) ? $expenses : [],
            'categories' => $this->categories,
            'errorMessage' => $error,
            'role' => $this->loggedInUserId[1]
        ]);
    }

    public function addExpense()
    {
        $this->init('user');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categoryId = $_POST['category_id'] ?? null;
            $description = $_POST['description'] ?? '';
            $amount = $_POST['amount'] ?? '';
            $date = $_POST['expense_date'] ?? '';

            $this->expenseService->createExpense(
                $this->createExpenseObject($categoryId, $description, $amount, $date, $this->loggedInUserId[0])
            );

            header('Location: /expense-tracker/public');
            exit;
        } else {
            $this->categories = $this->categoryService->getAll();
            echo View::make('expense', ['categories' => $this->categories]);
        }
    }

    public function editExpense()
    {
        $this->init('user');

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method']) && $_POST['_method'] === 'PUT') {
            $categoryId = $_POST['category_id'] ?? null;
            $description = $_POST['description'] ?? '';
            $amount = $_POST['amount'] ?? '';
            $date = $_POST['expense_date'] ?? '';
            $expenseId = $_GET['id'] ?? null;

            $this->expenseService->updateExpense(
                $expenseId,
                $this->createExpenseObject($categoryId, $description, $amount, $date, $this->loggedInUserId[0])
            );

            header('Location: /expense-tracker/public');
            exit;
        }

        $id = (int) $_GET['id'];

        $expense = $this->expenseService->getExpenseById($id);
        $categories = $this->categoryService->getAll();

        echo View::make('expense', ['categories' => $categories, 'expense' => $expense]);
    }

    public function update()
    {
        $this->expenseService->updateExpense(null, null);
    }

    public function delete()
    {
        $this->init('user');
        $expenseId = $_GET['id'];
        $this->expenseService->deleteExpense($expenseId);
        header('Location: /expense-tracker/public');
        exit;
    }

    public function report()
    {
        $this->init('user');

        $month = $_GET['month'] ?? date('Y-m');
        try {
            $reports = $this->expenseService->getReport($month, $this->loggedInUserId[0]);
            $expenses = [];
            foreach ($reports as $report) {
                $expenses[$report['category']][] = $this->expenseService->getExpenseByFilter($this->loggedInUserId[0], $report["category_id"], $month);
            }
        } catch (\Exception $e) {
            $error = $e;
        }
        echo View::make('report', [
            "reports" => isset($reports) ? $reports : [],
            "expenses" => isset($expenses) ? $expenses : []
        ]);
    }

    public function download()
    {
        $this->init('user');
        $month = $_GET['month'] ?? date('Y-m');

        $this->expenseService->downloadReport($this->loggedInUserId[0], $month);
    }

    private function createExpenseObject($categoryId, $description, $amount, $date, $userId)
    {
        return [
            'category_id' => $categoryId,
            'user_id' => $userId,
            'description' => $description,
            'amount' => $amount,
            'expense_date' => $date
        ];
    }

    private function init($role)
    {
        if (!$this->isUserLoggedIn) {
            die('Unauthorized user');
        }

        if ($this->loggedInUserId[1] === 'admin') {
            header('Location: /expense-tracker/public/category');
            exit;
        }

        if ($this->loggedInUserId[1] !== $role) {
            die("Access Denied");
        }
    }
}
