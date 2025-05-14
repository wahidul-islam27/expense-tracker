<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Exceptions\MethodNotSupportedException;
use App\Repositories\BaseRepository;
use App\Repositories\ExpenseRepositoryInterface;


class ExpenseRepository extends BaseRepository implements ExpenseRepositoryInterface
{

    public function create($expenseData)
    {
        $this->db->insert('expense', [
            'user_id' => $expenseData['user_id'],
            'category_id' => $expenseData['category_id'],
            'description' => $expenseData['description'],
            'amount' => $expenseData['amount'],
            'expense_date' => $expenseData['expense_date']
        ]);
    }

    public function get($id)
    {
        return $this->db
            ->createQueryBuilder()
            ->select('expense_id', 'user_id', 'category_id', 'description', 'amount', 'expense_date')
            ->from('expense')
            ->where('expense_id = ?')
            ->setParameter(0, $id)
            ->fetchAssociative();
    }

    public function getAll()
    {
        throw new MethodNotSupportedException('Implementation not supported');
    }

    public function update($id, $expenseData)
    {
        return $this->db
            ->createQueryBuilder()
            ->update('expense')
            ->set('description', ':description')
            ->set('amount', ':amount')
            ->set('expense_date', ':expense_date')
            ->where('expense_id = :id')
            ->setParameter('description', $expenseData['description'])
            ->setParameter('amount', $expenseData['amount'])
            ->setParameter('expense_date', $expenseData['expense_date'])
            ->setParameter('id', $id)
            ->executeStatement();
    }

    public function delete($id)
    {
        return $this->db
            ->createQueryBuilder()
            ->delete('expense')
            ->where('expense_id = :id')
            ->setParameter('id', $id)
            ->executeStatement();
    }

    public function getExpenseByCategoryId($categoryId, $userId)
    {
        return $this->db
            ->createQueryBuilder()
            ->select('expense_id', 'user_id', 'description', 'amount', 'expense_date')
            ->from('expense')
            ->where('category_id = :category_id and user_id = :user_id')
            ->setParameter('category_id', $categoryId)
            ->setParameter('user_id', $userId)
            ->fetchAllAssociative();
    }

    public function getExpenseByUserId($userId)
    {
        return $this->db
            ->createQueryBuilder()
            ->select('expense_id', 'category_id', 'description', 'amount', 'expense_date')
            ->from('expense')
            ->where('user_id = :user_id')
            ->setParameter('user_id', $userId)
            ->fetchAllAssociative();
    }

    public function getExpenseByFilter($userId, $category, $month)
    {
        $query = $this->db
            ->createQueryBuilder()
            ->select('expense_id', 'category_id', 'description', 'amount', 'expense_date')
            ->from('expense')
            ->where('user_id = :user_id')
            ->setParameter('user_id', $userId);
        if ($month) {
            $query
                ->andWhere("DATE_FORMAT(expense_date, '%Y-%m') = :month")
                ->setParameter('month', $month);
        }

        if ($category) {
            $query
                ->andWhere('category_id = :category_id')
                ->setParameter('category_id', $category);
        }

        $query->orderBy('expense_date', 'DESC');
        return $query->fetchAllAssociative();
    }

    public function getReportByMonth($month, $userId)
    {
        return $this->db
            ->createQueryBuilder()
            ->select('c.category_id, c.category_name AS category', 'SUM(e.amount) as total')
            ->from('expense', 'e')
            ->leftJoin('e', 'category', 'c', 'c.category_id = e.category_id')
            ->where('DATE_FORMAT(e.expense_date, :monthFormat) = :month')
            ->andWhere('e.user_id = :user_id')
            ->groupBy('c.category_name')
            ->setParameter('monthFormat', '%Y-%m')
            ->setParameter('month', $month)
            ->setParameter('user_id', $userId)
            ->fetchAllAssociative();
    }
}
