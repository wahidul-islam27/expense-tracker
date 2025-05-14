<?php

declare(strict_types=1);

namespace App\Services;

use App\Entities\Category;
use App\Entities\Expense;
use App\Entities\User;
use App\Exceptions\BusinessException;
use App\Exceptions\InvalidInputException;
use DateTime;

class ServiceUtils
{

    /**
     * @param array
     * @return void
     * @throws BusinessException
     */
    public static function validateUser($user): void
    {
        if ($user == null) {
            throw new BusinessException(404, 'User not found');
        }

        static::validateEmail($user['user_name']);
    }

    /**
     * @param array
     * @return void
     * @throws BusinessException
     */
    public static function validateCategory($categoryObject): void
    {
        if ($categoryObject == null) {
            throw new BusinessException(404, 'Category is not available');
        }

        static::validateName($categoryObject['category_name']);
    }

    /**
     * @param array
     * @return void
     */
    public static function validateExpense($expenseObject): void
    {
        if ($expenseObject == null) {
            throw new BusinessException(400, 'Object Not Found');
        }

        static::validateDescription($expenseObject['description']);
        static::validateAmount($expenseObject['amount']);
        static::validateDate($expenseObject['expense_date']);
    }

    /**
     * @param string
     * @return void
     * @throws InvalidInputException
     */
    public static function validateName($name): void
    {
        if ($name == null || strlen($name) == 0) {
            throw new InvalidInputException("Name can not be empty");
        }

        if (static::hasSpecialChar($name)) {
            throw new InvalidInputException("Name has special characters");
        }

        if (strlen($name) > 50) {
            throw new InvalidInputException("Name length has exceed more than 50");
        }
    }

    public static function validateEmail($email): void
    {
        if ($email == null || strlen($email) == 0) {
            throw new InvalidInputException("Email can be empty");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidInputException($email . " is not valid");
        }
    }

    /**
     * @param string
     * @return void
     * @throws InvalidInputException
     */
    public static function validateAmount($amount): void
    {
        if ($amount == null || strlen($amount) == 0) {
            throw new InvalidInputException('Amount can be empty');
        }

        if (!static::IsValidNumber($amount)) {
            throw new InvalidInputException('Amount is invalid');
        }
    }

    /**
     * @param string
     * @return void
     * @throws InvalidInputException
     */
    public static function validateDescription($description): void
    {
        if (static::hasSpecialChar($description)) {
            throw new InvalidInputException("Description has special characters");
        }

        if (strlen($description) > 250) {
            throw new InvalidInputException("Description length has exceed more than 50");
        }
    }

    public static function validateDate($date)
    {
        if ($date == null || strlen($date) == 0) {
            throw new InvalidInputException('Expense date is required');
        }
    }

    /**
     * @param string
     * @return bool
     */
    private static function hasSpecialChar($value)
    {
        $regex = '/[^a-zA-Z0-9 _-]/';
        return preg_match($regex, $value) > 0;
    }

    private static function IsValidNumber($value)
    {
        $regex = '/^\d+(\.\d+)?$/';

        return preg_match($regex, $value);
    }

    public static function mapUserObjectToUserEntity(array $userObject): User
    {
        $user = new User();

        $user->setId($userObject['id']);
        $user->setUsername($userObject['user_name']);
        $user->setMonthlyIncome($userObject['monthly_income']);
        $user->setIncomeAddDate(new DateTime($userObject['income_add_time']));
        $user->setRole($userObject['role']);

        return $user;
    }

    public static function setCookieValue($token): void
    {
        setcookie("token", $token, time() + 3600, "/", "", true, true);
    }

    public static function mapCategoryObjectToCategoryEntity(array $categoryObject): Category
    {
        $category = new Category();

        $category->setId($categoryObject['category_id']);
        $category->setCategoryName($categoryObject['category_name']);

        return $category;
    }

    public static function mapExpenseObjectToExpenseEntity(array $expenseObject, $user, $category): Expense
    {
        $expense = new Expense();

        $expense->setId($expenseObject['expense_id']);
        $expense->setUser($user);
        $expense->setCategory($category);
        $expense->setDescription($expenseObject['description']);
        $expense->setAmount($expenseObject['amount']);
        $expense->setExpenseDate(new DateTime($expenseObject['expense_date']));

        return $expense;
    }
}
