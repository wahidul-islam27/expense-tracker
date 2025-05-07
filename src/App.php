<?php

declare(strict_types=1);

namespace App;

use App\Config;
use App\DB;
use App\Repositories\ExpenseRepository;
use DateTime;

class App
{
    private static DB $db;

    public function __construct(protected Config $config)
    {
        static::$db = new DB($config->db ?? []);
    }

    public static function db(): DB
    {
        return static::$db;
    }

    public function run()
    {
        $expenseRepo = new ExpenseRepository();

        $expense = [
            'user_id' => 1,
            'category_id' => 3,
            'description' => 'treatment',
            'amount' => '600',
            'expense_date' => (new DateTime())->format('Y-m-d H:i:s')
        ];

        $expenseRepo->delete(1);
    }
}
