<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Entities\Category;
use App\Entities\User;
use App\Exceptions\BusinessException;
use App\Exceptions\SystemException;
use App\Repositories\ExpenseRepository;
use App\Services\CategoryService;
use App\Services\ExpenseService;
use App\Services\UserService;
use Tests\Unit\BaseTest;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertSame;

class ExpenseServiceTest extends BaseTest
{
    private $expenseRepositoryMock;
    private $expenseService;
    private $userServiceMock;
    private $categoryServiceMock;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var ExpenseRepository&\PHPUnit\Framework\MockObject\MockObject */
        $this->expenseRepositoryMock = $this->createMock(ExpenseRepository::class);

        /** @var UserService&\PHPUnit\Framework\MockObject\MockObject */
        $this->userServiceMock = $this->createMock(UserService::class);

        /** @var CategoryService&\PHPUnit\Framework\MockObject\MockObject */
        $this->categoryServiceMock = $this->createMock(CategoryService::class);

        $this->expenseService = new ExpenseService($this->expenseRepositoryMock, $this->userServiceMock, $this->categoryServiceMock);
    }

    public function testShouldCreateExpense()
    {
        $user = $this->getUser(1, 'test@ymail.com');
        $category = $this->getCategory(1);
        $expenseObj = $this->getExpenseObject(1, 1);

        $this->userServiceMock->expects($this->once())->method('getUserById')->with(1)->willReturn($user);
        $this->categoryServiceMock->expects($this->once())->method('getCategory')->with(1)->willReturn($category);
        $this->expenseRepositoryMock->expects($this->once())->method('create')->with($expenseObj);

        $this->expenseService->createExpense($expenseObj);
    }

    public function testShouldThrowExceptionForCreateExpense()
    {
        $user = $this->getUser(1, 'test@ymail.com');
        $category = $this->getCategory(1);
        $expenseObj = $this->getExpenseObject(1, 1);

        $this->userServiceMock->expects($this->once())->method('getUserById')->with(1)->willReturn($user);
        $this->categoryServiceMock->expects($this->once())->method('getCategory')->with(1)->willReturn($category);

        $this->expenseRepositoryMock->expects($this->once())->method('create')->willThrowException(new \Exception(parent::DATABASE_ACCESS_ERROR));

        $this->expectException(SystemException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage(parent::DATABASE_ACCESS_ERROR);

        $this->expenseService->createExpense($expenseObj);
    }

    // public function testShouldThrowExceptionForNullUserInCreateUser()
    // {
    //     $expenseObj = $this->getExpenseObject(1, 1);

    //     $this->userServiceMock->expects($this->once())->method('getUserById')->with(1)->willReturn(new User());
    //     $this->categoryServiceMock->expects($this->once())->method('getCategory')->with(1)->willReturn(new Category());

    //     $this->expectException(SystemException::class);
    //     $this->expectExceptionCode(500);

    //     $this->expenseService->createExpense($expenseObj);
    // }

    public function testShouldUpdateExpense()
    {
        $expenseObj = $this->getExpenseObject(1, 1);

        $this->expenseRepositoryMock->expects($this->once())->method('update')->with(1, $expenseObj);

        $this->expenseService->updateExpense(1, $expenseObj);
    }

    public function testShouldThrowExceptionForUpdateExpense()
    {
        $expenseObj = $this->getExpenseObject(1, 1);

        $this->expenseRepositoryMock->method('update')->willThrowException(new \Exception(parent::DATABASE_ACCESS_ERROR));

        $this->expectException(SystemException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage(parent::DATABASE_ACCESS_ERROR);

        $this->expenseService->updateExpense(1, $expenseObj);
    }

    public function testShouldDeleteExpense()
    {
        $expenseObj = $this->getExpenseObject(1, 1);

        $this->expenseRepositoryMock->expects($this->once())->method('get')->with(1)->willReturn($expenseObj);
        $this->expenseRepositoryMock->expects($this->once())->method('delete')->with(1);

        $this->expenseService->deleteExpense(1);
    }

    public function testShouldThrowExceptionForNullException()
    {
        $this->expenseRepositoryMock->expects($this->once())->method('get')->with(1)->willReturn(null);

        $this->expectException(BusinessException::class);
        $this->expectExceptionCode(400);

        $this->expenseService->deleteExpense(1);
    }

    public function testShouldGetExpenseByUser()
    {
        $user = $this->getUser(1, 'test@ymail.com');

        $expenseObjs = [];
        $expenseObj = $this->getExpenseObject(1, 1);
        $expenseObjs[] = $expenseObj;

        $expenses = [];
        $expense = $this->getExpense(1, 1);
        $expenses[] = $expense;

        $category = $this->getCategory(1);

        $this->userServiceMock->expects($this->once())->method('getUserById')->with(1)->willReturn($user);
        $this->expenseRepositoryMock->expects($this->once())->method('getExpenseByUserId')->with(1)->willReturn($expenseObjs);
        $this->categoryServiceMock->expects($this->once())->method('getCategory')->with(1)->willReturn($category);

        $result = $this->expenseService->getExpenseByUser(1);

        assertNotNull($result);
        assertEquals(1, sizeof($result));
        assertSame($expense->getId(), $result[0]->getId());
    }

    public function testShouldThrowExceptionForGetExpenseByUser()
    {
        $user = $this->getUser(1, 'test@ymail.com');

        $this->userServiceMock->expects($this->once())->method('getUserById')->with(1)->willReturn($user);
        $this->expenseRepositoryMock->expects($this->once())->method('getExpenseByUserId')->willThrowException(new \Exception(parent::DATABASE_ACCESS_ERROR));

        $this->expectException(SystemException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage(parent::DATABASE_ACCESS_ERROR);

        $this->expenseService->getExpenseByUser(1);
    }

    public function testShouldExpenseByCategory()
    {
        $user = $this->getUser(1, 'test@ymail.com');
        $category = $this->getCategory(1);

        $expenseObj = $this->getExpenseObject(1, 1);
        $expenseObjs[] = $expenseObj;

        $expense = $this->getExpense(1, 1);

        $this->userServiceMock->expects($this->once())->method('getUserById')->with(1)->willReturn($user);
        $this->categoryServiceMock->expects($this->once())->method('getCategory')->with(1)->willReturn($category);
        $this->expenseRepositoryMock->expects($this->once())->method('getExpenseByCategoryId')->with(1, 1)->willReturn($expenseObjs);

        $result = $this->expenseService->getExpenseByCategory(1, 1);

        assertNotNull($result);
        assertSame(1, sizeof($result));
        assertSame($expense->getId(), $result[0]->getId());
    }

    public function testShouldThrowExceptionForGetExpenseByCategory()
    {
        $user = $this->getUser(1, 'test@ymail.com');
        $category = $this->getCategory(1);

        $this->userServiceMock->expects($this->once())->method('getUserById')->with(1)->willReturn($user);
        $this->categoryServiceMock->expects($this->once())->method('getCategory')->with(1)->willReturn($category);
        $this->expenseRepositoryMock->expects($this->once())->method('getExpenseByCategoryId')->willThrowException(new \Exception(parent::DATABASE_ACCESS_ERROR));

        $this->expectException(SystemException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage(parent::DATABASE_ACCESS_ERROR);

        $this->expenseService->getExpenseByCategory(1, 1);
    }

    public function testGetExpenseById()
    {
        $expenseObj = $this->getExpenseObject(1, 1);
        $user = $this->getUser(1, 'test@ymail.com');
        $category = $this->getCategory(1);

        $this->expenseRepositoryMock->expects($this->once())->method('get')->with(1)->willReturn($expenseObj);
        $this->userServiceMock->expects($this->once())->method('getUserById')->with(1)->willReturn($user);
        $this->categoryServiceMock->expects($this->once())->method('getCategory')->with(1)->willReturn($category);

        $result = $this->expenseService->getExpenseById(1);

        assertNotNUll($result);
        assertSame($expenseObj["expense_id"], $result->getId());
    }

    public function testShouldThrowExceptionForGetExpenseById()
    {
        $this->expenseRepositoryMock->expects($this->once())->method('get')->with(1)->willReturn(null);

        $this->expectException(BusinessException::class);
        $this->expectExceptionCode(400);
        $this->expectExceptionMessage("No expense found");

        $this->expenseService->getExpenseById(1);
    }

    public function testShouldGetExpenseByFilter()
    {
        $expenseObj = $this->getExpenseObject(1, 1);
        $expenseObjs[] = $expenseObj;

        $user = $this->getUser(1, 'test@ymail.com');
        $category = $this->getCategory(1);

        $categoryFilter = 1;
        $monthFilter = '2025-04';

        $this->expenseRepositoryMock->expects($this->once())->method('getExpenseByFilter')->with(1, $categoryFilter, $monthFilter)->willReturn($expenseObjs);
        $this->userServiceMock->expects($this->once())->method('getUserById')->with(1)->willReturn($user);
        $this->categoryServiceMock->expects($this->once())->method('getCategory')->with(1)->willReturn($category);

        $result = $this->expenseService->getExpenseByFilter(1, $categoryFilter, $monthFilter);

        assertNotNull($result);
        assertSame(1, sizeof($result));
        assertSame($expenseObj['category_id'], $result[0]->getId());
    }

    public function testShouldGetExpenseByFilterForInavlidCategory()
    {
        $expenseObj = $this->getExpenseObject(1, 1);
        $expenseObjs[] = $expenseObj;

        $user = $this->getUser(1, 'test@ymail.com');
        $category = $this->getCategory(1);

        $categoryFilter = null;
        $monthFilter = '2025-04';

        $this->expenseRepositoryMock->expects($this->once())->method('getExpenseByFilter')->with(1, $categoryFilter, $monthFilter)->willReturn($expenseObjs);
        $this->userServiceMock->expects($this->once())->method('getUserById')->with(1)->willReturn($user);
        $this->categoryServiceMock->expects($this->once())->method('getCategory')->with(1)->willReturn($category);

        $result = $this->expenseService->getExpenseByFilter(1, $categoryFilter, $monthFilter);

        assertNotNull($result);
        assertSame(1, sizeof($result));
    }

    public function testShouldThrowExceptionForNullExpense()
    {
        $categoryFilter = null;
        $monthFilter = '2025-04';

        $this->expenseRepositoryMock->expects($this->once())->method('getExpenseByFilter')->with(1, $categoryFilter, $monthFilter)->willReturn(null);

        $this->expectException(BusinessException::class);
        $this->expectExceptionCode(400);
        $this->expectExceptionMessage("Expense not found");

        $this->expenseService->getExpenseByFilter(1, $categoryFilter, $monthFilter);
    }

    public function testShouldGetReport() {
        $report = $this->getReport();

        $month = "2025-04";

        $this->expenseRepositoryMock->expects($this->once())->method('getReportByMonth')->with($month, 1)->willReturn($report);

        $result = $this->expenseService->getReport($month, 1);

        assertNotNull($result);
    }

    public function testShouldThrowExceptionForReport() {

        $month = "2025-04";

        $this->expenseRepositoryMock->expects($this->once())->method('getReportByMonth')->with($month, 1)->willReturn(null);

        $this->expectException(BusinessException::class);
        $this->expectExceptionCode(400);
        $this->expectExceptionMessage("No Data Found for the month");

        $this->expenseService->getReport($month, 1);
    }
}
