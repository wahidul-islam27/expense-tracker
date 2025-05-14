<?php

declare(strict_types=1);

namespace Tests\Unit;

use Doctrine\DBAL\DriverManager;
use PHPUnit\Framework\TestCase;
use App\DB;
use Doctrine\DBAL\Connection;
use App\App;
use App\Entities\Category;
use App\Entities\Expense;
use App\Entities\User;
use DateTime;

use function PHPUnit\Framework\assertNotNull;

class BaseTest extends TestCase
{

    protected $db;
    protected $driverManagerMock;
    protected $connectionMock;

    public const DATABASE_ACCESS_ERROR = "Database Access Exception";

    protected function setUp(): void
    {
        parent::setUp();

        $this->connectionMock = $this->createMock(Connection::class);
        $this->db = $this->createMock(DB::class);
        $this->db = $this->getMockBuilder(DB::class)->disableOriginalConstructor()->onlyMethods(['__call'])->getMock();

        $ref = new \ReflectionClass(App::class);
        $property = $ref->getProperty('db');
        $property->setAccessible(true);
        $property->setValue(null, $this->db);
    }

    public function testDb()
    {
        assertNotNull(true);
    }

    protected function getUser($id, $username)
    {
        $user = new User();
        $user->setId($id);
        $user->setUsername($username);
        $user->setMonthlyIncome("10000");
        $user->setRole('user');
        $user->setPassword("123456");
        $user->setIncomeAddDate($this->getTime());

        return $user;
    }

    protected function getUserObject($id, $username)
    {
        $user = [
            'id' => $id,
            'user_name' => $username,
            'monthly_income' => '10000',
            'role' => 'user',
            'password' => '123456',
            'income_add_time' => "2025-05-12 00:00:00"
        ];

        return $user;
    }

    protected function getExpenseObject($id, $categoryId)
    {
        $expense = [
            "expense_id" => $id,
            "user_id" => 1,
            "category_id" => $categoryId,
            "description" => "test descr",
            "amount" => "10000",
            "expense_date" => "2025-05-12 00:00:00"
        ];

        return $expense;
    }

    protected function getExpense($id, $categoryId)
    {
        $expense = new Expense();

        $expense->setId($id);
        $expense->setUser($this->getUser(1, 'test@ymail.com'));
        $expense->setCategory($this->getCategory(1));
        $expense->setDescription("test descr");
        $expense->setAmount("10000");
        $expense->setExpenseDate($this->getTime());

        return $expense;
    }

    protected function getCategory($id)
    {
        $category = new Category();

        $category->setId($id);
        $category->setCategoryName('test category');

        return $category;
    }

    protected function getReport()
    {
        return [
            "category_id" => 1,
            "category" => "test",
            "total" => "500"
        ];
    }

    private function getTime()
    {
        $time = "2025-05-12 00:00:00";
        $dateTime = new DateTime($time);

        return $dateTime;
    }
}
